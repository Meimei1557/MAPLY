<?php

namespace Tests\Feature;

use App\Jobs\ValidateCandidatesJob;
use App\Models\AiKosCandidate;
use App\Models\AiSearchRun;
use App\Support\AiData\ValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Uji pipeline data AI secara end-to-end (queue sync di phpunit):
 * TAHAP 1 ingest multi-sumber -> TAHAP 2 ekstraksi Gemini -> TAHAP 3 validasi.
 * Semua HTTP eksternal (Overpass, Places, Gemini) di-fake.
 */
class AiPipelineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Backoff singkat agar test cepat bila ada HTTP gagal.
        config(['ai_pipeline.http_retry' => [10, 10, 10]]);

        // Test harus hermetik: kunci Gemini milik .env komputer lokal TIDAK boleh
        // terbaca di sini, kalau tidak pipeline (Tahap 2 & 3) akan memanggil API
        // sungguhan dan kandidat mendapat verdict sungguhan. Test yang memang
        // butuh AI mengatur kuncinya sendiri, mis. config(['services.gemini.key' => 'kunci-uji']).
        config(['services.gemini.key' => null]);

        // Semua HTTP eksternal wajib difake; permintaan liar langsung digagalkan.
        Http::preventStrayRequests();
    }

    private function admin()
    {
        return \App\Models\User::factory()->admin()->create();
    }

    private function overpassElements(): array
    {
        return [
            [
                'type' => 'node',
                'id' => 101,
                'lat' => -2.986444,
                'lon' => 104.734333,
                'tags' => [
                    'name' => 'Kost OSM Uji',
                    'addr:street' => 'Jl. Uji Raya',
                    'phone' => '081234567890',
                    'website' => 'https://kost-osm.example',
                ],
            ],
            [
                'type' => 'way',
                'id' => 202,
                'center' => ['lat' => -3.01, 'lon' => 104.75],
                'tags' => ['name' => 'Pondok Indekos Uji Dua'],
            ],
        ];
    }

    /**
     * Fake Gemini: ekstraksi valid untuk setiap id kandidat pada prompt.
     */
    private function fakeGeminiEkstraksiValid(): void
    {
        Http::fake([
            '*generateContent*' => function ($request) {
                $prompt = (string) data_get($request->data(), 'contents.0.parts.0.text', '');
                $bagian = explode('Balas dengan skema tepat ini', $prompt);
                preg_match_all('/"id":(\d+)/', $bagian[0] ?? '', $m);

                $hasil = [];

                foreach (array_unique($m[1] ?? []) as $id) {
                    $hasil[] = [
                        'id' => (int) $id,
                        'nama' => 'Kost Ekstraksi ' . $id,
                        'alamat' => 'Jl. Ekstraksi No. ' . $id,
                        'kecamatan' => null,
                        'harga_per_bulan' => 750000,
                        'tipe' => 'Putri',
                        'fasilitas' => ['WiFi'],
                        'telepon' => '+628123456789',
                    ];
                }

                return Http::response([
                    'candidates' => [
                        ['content' => ['parts' => [['text' => json_encode(['hasil' => $hasil])]]]],
                    ],
                ]);
            },
        ]);
    }

    /**
     * Fake Gemini: penilaian semantik (Lapis B) verdict valid confidence 0.95.
     */
    private function fakeGeminiVerdictValid(): void
    {
        Http::fake([
            '*generateContent*' => function ($request) {
                $prompt = (string) data_get($request->data(), 'contents.0.parts.0.text', '');
                $bagian = explode('Balas dengan skema tepat ini', $prompt);
                preg_match_all('/"id":(\d+)/', $bagian[0] ?? '', $m);

                $hasil = [];

                foreach (array_unique($m[1] ?? []) as $id) {
                    $hasil[] = [
                        'id' => (int) $id,
                        'verdict' => 'valid',
                        'confidence' => 0.95,
                        'reasons' => ['Bukti konsisten mengindikasikan kost beroperasi.'],
                    ];
                }

                return Http::response([
                    'candidates' => [
                        ['content' => ['parts' => [['text' => json_encode(['hasil' => $hasil])]]]],
                    ],
                ]);
            },
        ]);
    }

    public function test_pencarian_osm_menjalankan_pipeline_lengkap_hingga_validasi(): void
    {
        Http::fake(['*overpass*' => Http::response(['elements' => $this->overpassElements()])]);

        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'sources' => ['osm'],
        ])->assertRedirect()->assertSessionHas('success');

        $run = AiSearchRun::firstOrFail();

        // Chain sync: ingest -> tahap 2 -> tahap 3 selesai dalam satu aliran.
        $this->assertSame('completed', $run->status);
        $this->assertSame(2, $run->found_count);
        $this->assertSame(2, $run->new_count);
        $this->assertStringContainsString('OpenStreetMap (Overpass): 2 hasil', (string) $run->message);
        $this->assertNotNull($run->finished_at);
        $this->assertSame(['osm'], $run->sources);

        // Kandidat + bukti sumber per kandidat.
        $this->assertDatabaseCount('ai_kos_candidates', 2);
        $candidate = AiKosCandidate::where('provider_id', 'osm:node:101')->firstOrFail();
        $this->assertSame('Kost OSM Uji', $candidate->nama_kos);
        $this->assertSame('Palembang', $candidate->kota);
        $this->assertSame('081234567890', $candidate->telepon);
        $this->assertSame(1, $candidate->sources()->count());
        $this->assertNotNull($candidate->sources()->first()->evidence);

        // Tidak ada auto-publish dan AI belum menilai (key belum diatur).
        $this->assertNull($candidate->ai_verdict);
        $this->assertNull($candidate->ai_extracted);
        $this->assertNotSame('rejected', $candidate->verification_status);
        $this->assertDatabaseCount('kos', 0);
    }

    public function test_duplikat_provider_id_dilewati_saat_ingest(): void
    {
        Http::fake(['*overpass*' => Http::response(['elements' => $this->overpassElements()])]);

        $runLama = AiSearchRun::create(['region' => 'palembang', 'status' => 'completed']);
        AiKosCandidate::create([
            'search_run_id' => $runLama->id,
            'provider_id' => 'osm:node:101',
            'nama_kos' => 'Kost OSM Uji',
            'kota' => 'Palembang',
        ]);

        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'sources' => ['osm'],
        ])->assertRedirect();

        $run = AiSearchRun::latest('id')->firstOrFail();
        $this->assertSame('completed', $run->status);
        $this->assertSame(1, $run->duplicate_count);
        // Elemen kedua (osm:way:202) tetap menjadi kandidat baru.
        $this->assertSame(1, $run->new_count);
        $this->assertDatabaseCount('ai_kos_candidates', 2);
    }

    public function test_duplikat_nama_koordinat_otomatis_ditolak_oleh_validasi(): void
    {
        Http::fake(['*overpass*' => Http::response(['elements' => $this->overpassElements()])]);

        $runLama = AiSearchRun::create(['region' => 'palembang', 'status' => 'completed']);
        // Provider berbeda (lolos ingest) tetapi nama + koordinat sama dengan elemen OSM.
        AiKosCandidate::create([
            'search_run_id' => $runLama->id,
            'provider_id' => 'osm:node:999',
            'nama_kos' => 'Kost OSM Uji',
            'kota' => 'Palembang',
            'latitude' => -2.986444,
            'longitude' => 104.734333,
        ]);

        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'sources' => ['osm'],
        ])->assertRedirect();

        $run = AiSearchRun::latest('id')->firstOrFail();
        $this->assertSame('completed', $run->status);
        $this->assertSame(1, $run->rejected_count);

        $kembar = AiKosCandidate::where('provider_id', 'osm:node:101')->firstOrFail();
        $this->assertSame('rejected', $kembar->verification_status);
        $this->assertTrue(collect($kembar->verification_reasons)->contains(fn ($r) => str_contains((string) $r, 'Duplikat')));
    }

    public function test_ekstraksi_gemini_valid_tersimpan_tanpa_menimpa_kolom_asli(): void
    {
        config(['services.gemini.key' => 'kunci-uji']);

        Http::fake([
            '*overpass*' => Http::response(['elements' => $this->overpassElements()]),
        ]);
        $this->fakeGeminiEkstraksiValid();

        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'sources' => ['osm'],
        ])->assertRedirect();

        $run = AiSearchRun::firstOrFail();
        $this->assertSame('completed', $run->status);
        $this->assertStringContainsString('Pemrosesan AI selesai', (string) $run->message);

        $candidate = AiKosCandidate::where('provider_id', 'osm:node:101')->firstOrFail();

        // Kolom asli TIDAK ditimpa.
        $this->assertSame('Kost OSM Uji', $candidate->nama_kos);
        $this->assertNull($candidate->harga);
        $this->assertSame('Belum diketahui', $candidate->tipe);

        // Hasil ekstraksi tersimpan terpisah.
        $this->assertSame('Kost Ekstraksi ' . $candidate->id, $candidate->ai_extracted['nama']);
        $this->assertSame(750000, $candidate->ai_extracted['harga_per_bulan']);
        $this->assertSame('Putri', $candidate->ai_extracted['tipe']);
        $this->assertNotNull($candidate->ai_processed_at);
    }

    public function test_ekstraksi_gemini_invalid_diretry_dan_dicatat(): void
    {
        config(['services.gemini.key' => 'kunci-uji']);

        Http::fake([
            '*overpass*' => Http::response(['elements' => $this->overpassElements()]),
            '*generateContent*' => Http::response([
                'candidates' => [['content' => ['parts' => [['text' => 'maaf, saya tidak mengerti']]]]],
            ]),
        ]);

        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'sources' => ['osm'],
        ])->assertRedirect();

        $run = AiSearchRun::firstOrFail();
        $this->assertSame('completed', $run->status);

        $candidate = AiKosCandidate::where('provider_id', 'osm:node:101')->firstOrFail();
        $this->assertNull($candidate->ai_extracted);
        $this->assertNull($candidate->ai_processed_at);
        $this->assertTrue(collect($candidate->verification_reasons)->contains(
            fn ($r) => str_contains((string) $r, 'Pemrosesan AI gagal'),
        ));

        // Retry 1x: permintaan dikirim 2x untuk 2 kandidat (masing-masing batch).
        Http::assertSentCount(4);
    }

    public function test_penilaian_ai_menyimpan_verdict_dan_status(): void
    {
        config(['services.gemini.key' => 'kunci-uji']);
        $this->fakeGeminiVerdictValid();

        $run = AiSearchRun::create(['region' => 'palembang', 'status' => 'running', 'sources' => ['osm']]);
        $candidate = AiKosCandidate::create([
            'search_run_id' => $run->id,
            'provider_id' => 'uji:verdict',
            'nama_kos' => 'Kost Verdict Uji',
            'alamat' => 'Jl. Verdict No. 1',
            'kota' => 'Palembang',
            'latitude' => -2.986444,
            'longitude' => 104.734333,
            'telepon' => '081234567890',
            'harga' => 800000,
            'tipe' => 'Putri',
            'verification_status' => 'needs_review',
            'verification_reasons' => [],
            'raw_data' => ['bukti' => 'uji'],
            'source_name' => 'Sumber Uji',
            'found_at' => now(),
        ]);
        $candidate->sources()->create([
            'source_name' => 'Sumber Uji',
            'source_url' => 'https://sumber.example/kost',
            'evidence' => ['nama' => 'Kost Verdict Uji', 'alamat' => 'Jl. Verdict No. 1'],
        ]);

        (new ValidateCandidatesJob($run->id))->handle(app(ValidationService::class));

        $candidate = $candidate->fresh();
        $run = $run->fresh();

        // Skor: 0.5*100 + 0.35*95 + 0.15*100 = 98.25 -> 98 >= 75 & verdict valid.
        $this->assertSame('valid', $candidate->ai_verdict);
        $this->assertSame(0.95, $candidate->ai_confidence);
        $this->assertSame('strong_evidence', $candidate->verification_status);
        $this->assertSame(98, $candidate->trust_score);
        $this->assertSame('valid', $candidate->ai_analysis['verdict']);
        $this->assertNotEmpty($candidate->ai_analysis['reasons']);
        $this->assertSame((string) config('services.gemini.model'), $candidate->ai_analysis['model']);
        $this->assertSame(1, $run->valid_count);
        $this->assertSame(0, $run->rejected_count);
        $this->assertSame('completed', $run->status);
    }

    public function test_google_places_menghasilkan_kandidat(): void
    {
        config(['ai_pipeline.google_places.key' => 'places-kunci']);

        Http::fake([
            'places.googleapis.com/*' => Http::response([
                'places' => [
                    [
                        'id' => 'PLACE123',
                        'displayName' => ['text' => 'Kost Google Uji'],
                        'formattedAddress' => 'Jl. Google No. 1, Palembang',
                        'location' => ['latitude' => -2.99, 'longitude' => 104.75],
                        'nationalPhoneNumber' => '081234567890',
                        'websiteUri' => 'https://kost-google.example',
                        'primaryType' => 'lodging',
                    ],
                ],
            ]),
        ]);

        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'sources' => ['google_places'],
        ])->assertRedirect();

        $run = AiSearchRun::firstOrFail();
        $this->assertSame('completed', $run->status);
        $this->assertSame(1, $run->new_count);
        $this->assertStringContainsString('Google Places: 1 hasil', (string) $run->message);

        $candidate = AiKosCandidate::where('provider_id', 'gplaces:PLACE123')->firstOrFail();
        $this->assertSame('Kost Google Uji', $candidate->nama_kos);
        $this->assertSame('Google Places', $candidate->source_name);
        $this->assertSame('081234567890', $candidate->telepon);
        $this->assertSame(1, $candidate->sources()->count());
    }

    public function test_google_places_tanpa_kunci_ditolak_dengan_pesan_jelas(): void
    {
        config(['ai_pipeline.google_places.key' => null]);

        $this->actingAs($this->admin());

        // Sumber yang belum dikonfigurasi ditolak sebelum run dibuat (checkbox juga nonaktif di UI).
        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'sources' => ['osm', 'google_places'],
        ])->assertRedirect()->assertSessionHas('warning');

        $this->assertStringContainsString(
            'GOOGLE_PLACES_API_KEY belum diatur',
            (string) session('warning'),
        );

        $this->assertDatabaseCount('ai_search_runs', 0);
    }

    public function test_impor_json_menghasilkan_kandidat_dan_bukti(): void
    {
        $rows = [
            [
                'nama_kos' => 'Kost Impor Satu',
                'alamat' => 'Jl. Impor No. 1',
                'kecamatan' => 'Ilir Barat I',
                'kota' => 'Palembang',
                'harga' => 900000,
                'tipe' => 'Putri',
                'fasilitas' => ['WiFi', 'AC'],
                'telepon' => '081234567890',
                'sumber_nama' => 'Situs Publik',
                'sumber_url' => 'https://publik.example/kost-impor-satu',
            ],
            ['nama_kos' => 'Kost Impor Dua', 'alamat' => 'Jl. Impor No. 2', 'kota' => 'Indralaya'],
            ['alamat' => 'Tanpa nama, harus dilewati'],
        ];

        Http::fake(['*generateContent*' => Http::response([], 500)]);

        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.import.preview'), [
            'region' => 'palembang',
            'berkas' => UploadedFile::fake()->createWithContent('kost_uji.json', json_encode($rows, JSON_UNESCAPED_UNICODE)),
        ])->assertRedirect()->assertSessionHas('success');

        $this->get(route('admin.ai-kos.import.form'))
            ->assertOk()
            ->assertSee('Kost Impor Satu')
            ->assertSee('Kost Impor Dua');

        $this->post(route('admin.ai-kos.import.confirm'))->assertRedirect();

        $run = AiSearchRun::firstOrFail();
        $this->assertSame('completed', $run->status);
        $this->assertSame(['json_file'], $run->sources);
        $this->assertSame(2, $run->new_count);
        $this->assertStringContainsString('Berkas JSON/CSV: 2 hasil', (string) $run->message);

        $candidate = AiKosCandidate::where('nama_kos', 'Kost Impor Satu')->firstOrFail();
        $this->assertStringStartsWith('json:', (string) $candidate->provider_id);
        $this->assertSame(900000, $candidate->harga);
        $this->assertSame('Situs Publik', $candidate->source_name);
        $this->assertSame(['WiFi', 'AC'], $candidate->fasilitas);
        $this->assertSame(1, $candidate->sources()->count());

        // Berkas impor sementara sudah dibersihkan setelah ingest.
        $this->assertFalse(\Illuminate\Support\Facades\Storage::disk('local')->exists((string) data_get($run->options, 'import_path')));
    }

    public function test_impor_csv_menghasilkan_kandidat(): void
    {
        $csv = "nama_kos;alamat;kota;harga;fasilitas\nKost CSV Uji;Jl. CSV No. 3;Palembang;650000;WiFi|AC\n";

        Http::fake(['*generateContent*' => Http::response([], 500)]);

        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.import.preview'), [
            'region' => 'palembang',
            'berkas' => UploadedFile::fake()->createWithContent('kost_uji.csv', $csv),
        ])->assertRedirect()->assertSessionHas('success');

        $this->post(route('admin.ai-kos.import.confirm'))->assertRedirect();

        $run = AiSearchRun::firstOrFail();
        $this->assertSame('completed', $run->status);
        $this->assertSame(1, $run->new_count);

        $candidate = AiKosCandidate::where('nama_kos', 'Kost CSV Uji')->firstOrFail();
        $this->assertSame(650000, $candidate->harga);
        $this->assertSame(['WiFi', 'AC'], $candidate->fasilitas);
    }

    public function test_impor_ditolak_bila_struktur_tidak_valid(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.import.preview'), [
            'region' => 'palembang',
            'berkas' => UploadedFile::fake()->createWithContent('rusak.json', '{"bukan": "array"}'),
        ])->assertRedirect()->assertSessionHasErrors('berkas');

        $this->assertDatabaseCount('ai_search_runs', 0);
    }

    public function test_aksi_massal_reject(): void
    {
        $run = AiSearchRun::create(['region' => 'palembang', 'status' => 'completed']);
        $a = AiKosCandidate::create([
            'search_run_id' => $run->id, 'provider_id' => 'uji:a', 'nama_kos' => 'Kost A',
            'kota' => 'Palembang', 'verification_status' => 'needs_review',
        ]);
        $b = AiKosCandidate::create([
            'search_run_id' => $run->id, 'provider_id' => 'uji:b', 'nama_kos' => 'Kost B',
            'kota' => 'Palembang', 'verification_status' => 'needs_review',
        ]);

        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.bulk'), [
            'action' => 'reject',
            'ids' => [$a->id, $b->id],
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertSame('rejected', $a->fresh()->verification_status);
        $this->assertSame('rejected', $b->fresh()->verification_status);
        $this->assertNotNull($a->fresh()->reviewed_by);
    }

    public function test_guard_hanya_satu_run_aktif(): void
    {
        AiSearchRun::create(['region' => 'palembang', 'status' => 'running']);

        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'sources' => ['osm'],
        ])->assertRedirect()->assertSessionHas('warning');

        $this->assertDatabaseCount('ai_search_runs', 1);
    }

    public function test_semua_sumber_gagal_menandai_run_failed(): void
    {
        Http::fake(['*overpass*' => Http::response([], 500)]);

        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'sources' => ['osm'],
        ])->assertRedirect()->assertSessionHas('warning');

        $run = AiSearchRun::firstOrFail();
        $this->assertSame('failed', $run->status);
        $this->assertStringContainsString('GAGAL', (string) $run->message);
    }

    public function test_chatbot_gemini_lama_tidak_berubah_perilaku(): void
    {
        config(['services.gemini.key' => 'kunci-uji']);

        Http::fake([
            '*generateContent*' => Http::response([
                'candidates' => [['content' => ['parts' => [['text' => 'Halo! Saya Moy AI.']]]]],
            ]),
        ]);

        $this->actingAs(\App\Models\User::factory()->create());

        $this->post('/ai/chat', ['message' => 'halo'])
            ->assertOk()
            ->assertJsonPath('ai', 'gemini')
            ->assertJsonPath('message', 'Halo! Saya Moy AI.');
    }

    public function test_filter_tabel_kandidat(): void
    {
        $run = AiSearchRun::create(['region' => 'palembang', 'status' => 'completed']);
        AiKosCandidate::create([
            'search_run_id' => $run->id, 'provider_id' => 'uji:filter', 'nama_kos' => 'Kost Filter',
            'kota' => 'Palembang', 'verification_status' => 'strong_evidence', 'trust_score' => 90,
            'source_name' => 'OpenStreetMap',
        ]);

        $this->actingAs($this->admin());

        $this->get(route('admin.ai-kos.index', ['status' => 'strong_evidence']))
            ->assertOk()->assertSee('Kost Filter');

        $this->get(route('admin.ai-kos.index', ['status' => 'rejected']))
            ->assertOk()->assertDontSee('Kost Filter');

        $this->get(route('admin.ai-kos.index', ['skor_min' => 95]))
            ->assertOk()->assertDontSee('Kost Filter');
    }

    public function test_pencarian_berkala_membuat_dua_run_dengan_pipeline_lengkap(): void
    {
        Http::fake(['*overpass*' => Http::response(['elements' => $this->overpassElements()])]);
        config(['ai_pipeline.google_places.key' => null]); // tanpa kunci Places: hanya OSM.

        Artisan::call('maply:ai-search-scheduled');

        $runs = AiSearchRun::orderBy('id')->get();
        $this->assertSame(2, $runs->count());
        $this->assertSame(['palembang', 'indralaya'], $runs->pluck('region')->all());
        $this->assertSame(['osm'], $runs->first()->sources);

        // Jalankan antrean sinkron (phpunit) sampai habis: chain Tahap 2+3 selesai.
        while (DB::table('jobs')->count() > 0) {
            Artisan::call('queue:work', ['--once' => true]);
        }

        foreach ($runs as $run) {
            $this->assertSame('completed', $run->fresh()->status, "Run #{$run->id} harus selesai.");
        }
        $this->assertDatabaseCount('kos', 0);
    }

    public function test_pencarian_berkala_dilewati_bila_ada_run_aktif(): void
    {
        AiSearchRun::create(['region' => 'palembang', 'status' => 'running']);

        Artisan::call('maply:ai-search-scheduled');

        $this->assertDatabaseCount('ai_search_runs', 1);
    }

    public function test_pencarian_terstruktur_menerima_kriteria_dan_menolak_kampus_tak_kenal(): void
    {
        $this->actingAs($this->admin());

        // Kampus tidak dikenal -> gagal validasi.
        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'kampus' => 'kampus-tidak-ada',
            'sources' => ['osm'],
        ])->assertSessionHasErrors('kampus');

        // Kriteria lengkap -> tersimpan pada run options.
        Http::fake(['*overpass*' => Http::response(['elements' => $this->overpassElements()])]);

        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'kecamatan' => 'Ilir Barat II',
            'harga_min' => 400000,
            'harga_max' => 1500000,
            'tipe' => 'Putri',
            'max_results' => 10,
            'sources' => ['osm'],
        ])->assertRedirect()->assertSessionHas('success');

        $run = AiSearchRun::firstOrFail();
        $kriteria = data_get((array) $run->options, 'kriteria');
        $this->assertSame('Ilir Barat II', $kriteria['kecamatan'] ?? null);
        $this->assertSame(400000, $kriteria['harga_min'] ?? null);
        $this->assertSame(1500000, $kriteria['harga_max'] ?? null);
        $this->assertSame('Putri', $kriteria['tipe'] ?? null);
        $this->assertSame(10, $kriteria['max_results'] ?? null);

        // Kandidat di luar rentang kriteria mendapat catatan relevansi, bukan ditolak oleh kriteria.
        $candidate = AiKosCandidate::where('provider_id', 'osm:node:101')->firstOrFail();
        $this->assertNotSame('rejected', $candidate->fresh()->verification_status);
    }

    public function test_batas_maksimal_hasil_dihormati_saat_ingest(): void
    {
        Http::fake(['*overpass*' => Http::response(['elements' => $this->overpassElements()])]);
        $this->actingAs($this->admin());

        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'max_results' => 1,
            'sources' => ['osm'],
        ])->assertRedirect();

        $run = AiSearchRun::firstOrFail();
        $this->assertSame(1, $run->new_count);
        $this->assertStringContainsString('batas maksimal hasil', (string) $run->message);
    }

    public function test_alur_terstruktur_hingga_publish_muncul_di_tabel_kos(): void
    {
        // Pipeline lengkap dengan AI: ekstraksi valid + verdict valid.
        $this->fakeGeminiEkstraksiValid();
        $this->fakeGeminiVerdictValid();
        config(['services.gemini.key' => 'kunci-uji']);

        Http::fake(['*overpass*' => Http::response(['elements' => $this->overpassElements()])]);
        $this->actingAs($this->admin());

        // 1) Pencarian terstruktur.
        $this->post(route('admin.ai-kos.search'), [
            'region' => 'palembang',
            'keyword' => 'kost',
            'harga_max' => 1500000,
            'sources' => ['osm'],
        ])->assertRedirect()->assertSessionHas('success');

        // 2) Kandidat tersimpan dan tervalidasi.
        $candidate = AiKosCandidate::where('provider_id', 'osm:node:101')->firstOrFail();
        $this->assertSame('Palembang', $candidate->kota);
        $this->assertNotSame('rejected', $candidate->verification_status);

        // 3) Admin menyetujui kandidat -> diterbitkan ke tabel kos.
        $this->post(route('admin.ai-kos.approve', $candidate), [
            'nama_kos' => 'Kost OSM Uji',
            'alamat' => 'Jl. Uji Raya',
            'kota' => 'Palembang',
            'kecamatan' => 'Bukit Besar',
            'harga' => 750000,
            'tipe' => 'Putri',
            'telepon' => '081234567890',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('kos', [
            'nama_kos' => 'Kost OSM Uji',
            'status_data' => 'terverifikasi',
            'kota' => 'Palembang',
        ]);
        $this->assertSame('published', $candidate->fresh()->verification_status);
        $this->assertNotNull($candidate->fresh()->published_kos_id);
    }
}


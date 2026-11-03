<?php

namespace Tests\Feature;

use App\Models\Kampus;
use App\Models\KampusLokasi;
use App\Support\KampusDirectory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji sumber tunggal kampus (poin 8) & titik kampus (poin 9):
 * - Dashboard + filter kos memakai tabel kampus/kampus_lokasi.
 * - resolveReference mengenali titik kota config (ampera) dan kampus dari DB.
 * - Kampus tanpa koordinat tetap tampil di daftar dengan penanda.
 * - Seeder idempotent (aman dijalankan berulang).
 */
class KampusDirectoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Satu kampus Indralaya + satu kampus Palembang tanpa lokasi.
        $unsri = Kampus::create([
            'kode' => 'unsri',
            'nama_resmi' => 'Universitas Sriwijaya',
            'singkatan' => 'UNSRI',
            'status' => 'Negeri',
            'jenis' => 'Universitas',
            'aktif' => true,
            'kab_kota' => 'Ogan Ilir',
        ]);
        KampusLokasi::create([
            'kampus_id' => $unsri->id,
            'kode' => 'unsri-2',
            'nama_lokasi' => 'Kampus Indralaya',
            'is_utama' => true,
            'aktif' => true,
            'latitude' => -3.2169361,
            'longitude' => 104.6486667,
            'presisi' => 'gedung',
            'kab_kota' => 'Ogan Ilir',
            'sumber_koordinat' => 'Wikidata Q4200297',
            'verifikasi_koordinat' => [['url' => 'https://www.wikidata.org/wiki/Q4200297', 'tanggal' => '2026-11-02']],
        ]);

        Kampus::create([
            'kode' => 'kampus-tanpa-koordinat',
            'nama_resmi' => 'Sekolah Tinggi Uji Tanpa Koordinat',
            'status' => 'Swasta',
            'jenis' => 'Sekolah Tinggi',
            'aktif' => true,
            'kecamatan' => 'Ilir Barat II',
            'kab_kota' => 'Palembang',
        ]);
    }

    public function test_halaman_daftar_kampus_tampil_dengan_filter_dan_paginasi(): void
    {
        $this->actingAs(\App\Models\User::factory()->create());

        $this->get(route('kampus.index'))
            ->assertOk()
            ->assertSee('Universitas Sriwijaya')
            ->assertSee('Sekolah Tinggi Uji Tanpa Koordinat')
            ->assertSee('Koordinat belum terverifikasi');

        // Filter wilayah memisahkan Ogan Ilir dari Palembang.
        $this->get(route('kampus.index', ['wilayah' => 'Ogan Ilir']))
            ->assertOk()
            ->assertSee('Universitas Sriwijaya')
            ->assertDontSee('Sekolah Tinggi Uji Tanpa Koordinat');

        // Pencarian nama.
        $this->get(route('kampus.index', ['q' => 'sriwijaya']))
            ->assertOk()
            ->assertSee('Universitas Sriwijaya');

        // Filter koordinat "belum" hanya menampilkan yang tanpa titik.
        $this->get(route('kampus.index', ['koordinat' => 'belum']))
            ->assertOk()
            ->assertSee('Sekolah Tinggi Uji Tanpa Koordinat')
            ->assertDontSee('Universitas Sriwijaya');
    }

    public function test_resolve_reference_menerima_ampera_dan_kampus_dari_db(): void
    {
        // Titik kota dari config.
        [$key, $ref] = \App\Http\Controllers\KosController::resolveReference('ampera');
        $this->assertSame('ampera', $key);
        $this->assertSame('Pusat Kota (Ampera)', $ref['singkat']);

        // Kampus dari DB (kode lokasi maupun kode kampus).
        [$key, $ref] = \App\Http\Controllers\KosController::resolveReference('unsri-2');
        $this->assertSame('unsri-2', $key);
        $this->assertSame(-3.2169361, $ref['lat']);

        [$key, $ref] = \App\Http\Controllers\KosController::resolveReference('unsri');
        $this->assertSame('unsri', $key);
        $this->assertContains('UNSRI', [$ref['singkat'], strtok($ref['singkat'], ' ')]);

        // Kampus tanpa koordinat -> jatuh ke titik bawaan, tidak error.
        [$key, $ref] = \App\Http\Controllers\KosController::resolveReference('kampus-tanpa-koordinat');
        $this->assertSame(config('maply.titik_default'), $key);

        // Alias lama tetap berfungsi.
        [$key, $ref] = \App\Http\Controllers\KosController::resolveReference('indralaya');
        $this->assertSame('unsri-indralaya', $key);
    }

    public function test_filter_kos_menampilkan_kampus_dan_titik_kota_secara_terpisah(): void
    {
        $this->actingAs(\App\Models\User::factory()->create());

        $response = $this->get(route('kos.index'));
        $response->assertOk();

        $references = $response->original->gatherData()['references'] ?? null;
        $this->assertNotNull($references);
        $this->assertArrayHasKey('campus', $references);
        $this->assertArrayHasKey('kota', $references);
        $this->assertArrayHasKey('unsri-2', $references['campus']);
        $this->assertArrayHasKey('ampera', $references['kota']);
        $this->assertArrayHasKey('demang', $references['kota']);
        $this->assertArrayHasKey('jakabaring', $references['kota']);

        // Titik acuan config bertipe "Kampus" tidak boleh dobel muncul di grup titik kota.
        foreach ($references['kota'] as $r) {
            $this->assertNotSame('Kampus', $r['jenis']);
        }
    }

    public function test_kampus_untuk_peta_hanya_menyertakan_koordinat_terverifikasi(): void
    {
        $peta = KampusDirectory::kampusUntukPeta();
        $this->assertCount(1, $peta);
        $this->assertSame('unsri-2', $peta[0]['kode']);
        $this->assertSame('Universitas Sriwijaya', $peta[0]['nama']);
        $this->assertArrayHasKey('kos_sekitar', $peta[0]);
        $this->assertArrayHasKey('url', $peta[0]);

        // Kampus dengan presisi 'perkiraan' tidak masuk peta.
        $lokasi = KampusLokasi::first();
        $lokasi->update(['presisi' => 'perkiraan']);
        $this->assertCount(0, KampusDirectory::kampusUntukPeta());
    }

    public function test_dashboard_memakai_kampus_dari_db_sebagai_kartu_bulat(): void
    {
        $this->actingAs(\App\Models\User::factory()->create());

        $response = $this->get(route('dashboard'));
        $response->assertOk();

        $data = $response->original->gatherData();
        $this->assertArrayHasKey('kampusCards', $data);
        $this->assertArrayHasKey('unsri-2', $data['kampusCards']);
        $this->assertArrayHasKey('kampusPeta', $data);
        $this->assertCount(1, $data['kampusPeta']);
    }

    public function test_seeder_kampus_idempotent_dan_menyimpan_110_institusi(): void
    {
        // Mulai dari tabel bersih agar jumlah yang dihasilkan seeder terukur pasti.
        KampusLokasi::query()->delete();
        Kampus::query()->delete();

        // Jalankan dua kali (dipakai ulang oleh developer); jumlah harus tetap.
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'KampusSeeder', '--force' => true]);
        $jumlahKampus = Kampus::count();
        $jumlahLokasi = KampusLokasi::count();
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'KampusSeeder', '--force' => true]);

        $this->assertSame($jumlahKampus, Kampus::count());
        $this->assertSame($jumlahLokasi, KampusLokasi::count());
        $this->assertSame(110, $jumlahKampus);
        $this->assertGreaterThanOrEqual(27, $jumlahLokasi);

        // Kampus Indralaya wajib utuh (poin 8: jangan hapus Indralaya).
        foreach (['unsri', 'iaiqi', 'stitqi', 'stit-raudhatul-ulum'] as $kode) {
            $this->assertDatabaseHas('kampus', ['kode' => $kode]);
        }
        $this->assertDatabaseHas('kampus_lokasi', ['kode' => 'unsri-2']);
    }

    // ============================== REVISI 2: lingkaran berlogo kampus ==============================

    public function test_dashboard_menampilkan_semua_lingkaran_kampus_tanpa_chip_titik_kota(): void
    {
        $this->actingAs(\App\Models\User::factory()->create());

        // Seed kampus resmi supaya kartu terisi persis seperti produksi.
        KampusLokasi::query()->delete();
        Kampus::query()->delete();
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'KampusSeeder', '--force' => true]);

        $response = $this->get(route('dashboard'));
        $response->assertOk();

        $html = $response->getContent();
        // Semua 27 lokasi kampus tampil sebagai kartu bulat (tanpa limit kecil).
        $this->assertSame(27, substr_count($html, 'ref-card--kampus'));
        // Logo UNSRI (file benar-benar ada di public/logos/kampus) dirender di dalam lingkaran.
        $this->assertStringContainsString('logos/kampus/unsri.png', $html);
        // Kampus tanpa logo memakai lingkaran inisial (fallback).
        $this->assertStringContainsString('ref-initial', $html);
        // Baris chip titik kota (Ampera/Demang/Jakabaring) sudah dihapus dari section ini.
        $this->assertStringNotContainsString('titik-kota-row', $html);
        $this->assertStringNotContainsString('titik-chip', $html);
        // Link "Semua kampus" tetap ada.
        $this->assertStringContainsString(route('kampus.index'), $html);

        // Setiap lingkaran menuju pencarian kos dekat kampus tersebut.
        $this->assertDatabaseHas('kampus_lokasi', ['kode' => 'unsri-1']);
        $this->assertStringContainsString('href="'.route('kos.index', ['kampus' => 'unsri-1']).'"', $html);
    }

    public function test_database_seeder_menjalankan_kampus_seeder(): void
    {
        // Akar masalah lingkaran kosong: DatabaseSeeder dahulu tidak memanggil KampusSeeder.
        KampusLokasi::query()->delete();
        Kampus::query()->delete();

        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);

        $this->assertGreaterThan(0, Kampus::count(), 'php artisan db:seed harus mengisi tabel kampus.');
        $this->assertDatabaseHas('kampus', ['kode' => 'unsri']);
    }

    // ============================== REVISI 3: sidebar tanpa menu Ampera ==============================

    public function test_sidebar_tanpa_menu_ampera_tapi_fitur_ampera_tetap_ada(): void
    {
        $this->actingAs(\App\Models\User::factory()->create());

        $html = $this->get(route('dashboard'))->getContent();

        // Item menu teks "Ampera" dihapus dari sidebar.
        $this->assertStringNotContainsString('fa-bridge', $html);
        $this->assertStringNotContainsString('> Ampera</', $html);
        // Ilustrasi jembatan Ampera hadir sebagai dekorasi dasar sidebar.
        $this->assertStringContainsString('sidebar__ampera', $html);

        // Urutan menu sesuai referensi: Beranda, Cari Kos, Peta, Kampus & Area.
        $posBeranda = mb_strpos($html, '> Beranda');
        $posCari = mb_strpos($html, '> Cari Kos');
        $posPeta = mb_strpos($html, '> Peta');
        $posKampus = mb_strpos($html, '> Kampus &amp; Area');
        $this->assertNotFalse($posBeranda);
        $this->assertNotFalse($posCari);
        $this->assertNotFalse($posPeta);
        $this->assertNotFalse($posKampus);
        $this->assertTrue($posBeranda < $posCari && $posCari < $posPeta && $posPeta < $posKampus);

        // Fitur "kos dekat Ampera" tetap tersedia: dropdown "Dekat" hero memuat opsi ampera.
        $this->assertStringContainsString('value="ampera"', $html);
        // ... dan filter titik acuan di halaman /kos juga.
        $htmlKos = $this->get(route('kos.index'))->getContent();
        $this->assertStringContainsString('value="ampera"', $htmlKos);
        // Pencarian kos dekat Ampera tetap berfungsi.
        $this->get(route('kos.index', ['kampus' => 'ampera']))->assertOk();
    }

    // ============================== REVISI 4: tombol chat pil ==============================

    public function test_launcher_chat_bentuk_pil_chat_maply_ai(): void
    {
        $this->actingAs(\App\Models\User::factory()->create());

        $html = $this->get(route('dashboard'))->getContent();

        // Pil peluncur bertuliskan "Chat Maply AI" dengan ikon chat.
        $this->assertStringContainsString('Chat Maply AI', $html);
        $this->assertStringContainsString('fa-comment-dots', $html);
        // Launcher tidak lagi memakai gambar robot (logo Moy) sebagai isinya.
        $posToggle = mb_strpos($html, 'id="aiChatToggle"');
        $this->assertNotFalse($posToggle);
        $posTutup = mb_strpos($html, '</button>', $posToggle);
        $this->assertNotFalse($posTutup);
        $blokLauncher = mb_substr($html, $posToggle, $posTutup - $posToggle);
        $this->assertStringNotContainsString('<img', $blokLauncher);
        $this->assertStringContainsString('Chat Maply AI', $blokLauncher);
        // Panel chat (header Moy AI) tetap utuh.
        $this->assertStringContainsString('Moy AI', $html);
        $this->assertStringContainsString('id="aiChatPanel"', $html);
        $this->assertStringContainsString('id="aiChatMin"', $html);
        $this->assertStringContainsString('id="aiChatNew"', $html);
        $this->assertStringContainsString('id="aiChatClose"', $html);
    }

    // ============================== REVISI 5e: filter halaman /kampus ==============================

    public function test_halaman_kampus_filter_jenis_status_kecamatan_dan_paginasi(): void
    {
        $this->actingAs(\App\Models\User::factory()->create());

        // Negeri dengan koordinat.
        $this->get(route('kampus.index', ['status' => 'Negeri']))
            ->assertOk()
            ->assertSee('Universitas Sriwijaya');
        // Jenis institusi.
        $this->get(route('kampus.index', ['jenis' => 'Sekolah Tinggi']))
            ->assertOk()
            ->assertSee('Sekolah Tinggi Uji Tanpa Koordinat')
            ->assertDontSee('Universitas Sriwijaya');
        // Kecamatan: kampus uji berkecamatan Ilir Barat II.
        $this->get(route('kampus.index', ['kecamatan' => 'Ilir Barat II']))
            ->assertOk()
            ->assertSee('Sekolah Tinggi Uji Tanpa Koordinat')
            ->assertDontSee('Universitas Sriwijaya');
        // Status koordinat "belum".
        $this->get(route('kampus.index', ['koordinat' => 'belum']))
            ->assertOk()
            ->assertSee('Sekolah Tinggi Uji Tanpa Koordinat')
            ->assertDontSee('Universitas Sriwijaya');
        // Kombinasi pencarian + status + wilayah.
        $this->get(route('kampus.index', ['q' => 'sriwijaya', 'status' => 'Negeri', 'wilayah' => 'Ogan Ilir']))
            ->assertOk()
            ->assertSee('Universitas Sriwijaya')
            ->assertDontSee('Sekolah Tinggi Uji Tanpa Koordinat');
        // Nilai filter tetap terpilih setelah submit.
        $html = $this->get(route('kampus.index', ['status' => 'Negeri', 'jenis' => 'Universitas']))->getContent();
        $this->assertMatchesRegularExpression('/name="status".*?<option value="Negeri" selected/s', $html);
        $this->assertMatchesRegularExpression('/name="jenis".*?<option value="Universitas" selected/s', $html);

        // Paginasi membawa query string: 24 halaman x beberapa kampus dari seeder.
        KampusLokasi::query()->delete();
        Kampus::query()->delete();
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'KampusSeeder', '--force' => true]);
        $html = $this->get(route('kampus.index', ['status' => 'Swasta']))->getContent();
        $this->assertStringContainsString('status=Swasta', $html, 'Paginasi /kampus harus membawa filter status.');
    }
}

<?php

namespace Tests\Unit;

use App\Models\AiKosCandidate;
use App\Models\AiSearchRun;
use App\Support\AiData\DuplicateDetector;
use App\Support\AiData\GeminiClient;
use App\Support\AiData\ValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji gerbang deterministik (Lapis A), skor gabungan, dan pemetaan status (Tahap 3).
 * Lapis B diuji dengan verdict buatan — jalur HTTP Gemini diuji di AiPipelineTest.
 */
class ValidationServiceTest extends TestCase
{
    use RefreshDatabase;

    private ValidationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ValidationService(
            new DuplicateDetector(),
            new GeminiClient(),
        );
    }

    private function buatKandidat(array $override = []): AiKosCandidate
    {
        $run = AiSearchRun::create([
            'region' => 'palembang',
            'keyword' => 'kost',
            'status' => 'completed',
        ]);

        return AiKosCandidate::create(array_merge([
            'search_run_id' => $run->id,
            'provider_id' => 'uji:' . uniqid(),
            'nama_kos' => 'Kost Uji Damai',
            'alamat' => 'Jl. Uji No. 1, Palembang',
            'kota' => 'Palembang',
            'latitude' => -2.986444,
            'longitude' => 104.734333,
            'telepon' => '081234567890',
            'harga' => 750000,
            'tipe' => 'Putri',
            'trust_score' => 0,
            'verification_status' => 'needs_review',
            'verification_reasons' => [],
            'raw_data' => ['sumber' => 'uji'],
            'source_name' => 'Sumber Uji',
            'source_url' => 'https://contoh.example/kost',
            'found_at' => now(),
        ], $override));
    }

    public function test_koordinat_di_luar_bbox_menghasilkan_invalid(): void
    {
        $candidate = $this->buatKandidat(['latitude' => -6.2, 'longitude' => 106.8]); // Jakarta

        $gate = $this->service->deterministicGate($candidate);

        $this->assertSame('invalid', $gate['status']);
        $this->assertSame(0, $gate['score']);
        $this->assertCount(1, collect($gate['reasons'])->filter(fn ($r) => str_contains($r, 'di luar wilayah layanan')));

        $status = $this->service->apply($candidate, $gate);
        $this->assertSame('rejected', $status);
        $this->assertSame('rejected', $candidate->fresh()->verification_status);
    }

    public function test_nama_mengandung_kata_terlarang_menjadi_suspicious(): void
    {
        $candidate = $this->buatKandidat(['nama_kos' => 'Kost Dekat Hotel Santika']);

        $gate = $this->service->deterministicGate($candidate);

        $this->assertSame('suspicious', $gate['status']);
        $this->assertTrue(collect($gate['reasons'])->contains(fn ($r) => str_contains($r, 'kata terlarang')));

        // Tanpa AI: status tetap suspicious — admin yang memutuskan.
        // Skor gerbang di-cap 55, namun skor final ternormalisasi: (0.5*55 + 0.15*100)/0.65 = 65.
        $status = $this->service->apply($candidate, $gate);
        $this->assertSame('suspicious', $status);
        $this->assertSame(65, $candidate->fresh()->trust_score);
    }

    public function test_harga_mustahil_ditandai(): void
    {
        $candidate = $this->buatKandidat(['harga' => 20000]);

        $gate = $this->service->deterministicGate($candidate);

        $this->assertSame('ok', $gate['status']); // flag, bukan perubahan status langsung
        $this->assertTrue(collect($gate['reasons'])->contains(fn ($r) => str_contains($r, 'tidak wajar')));
        $this->assertLessThanOrEqual(60, $gate['score']);
    }

    public function test_telepon_tidak_valid_hanya_flag_ringan(): void
    {
        $candidate = $this->buatKandidat(['telepon' => 'hubungi saya']);

        $gate = $this->service->deterministicGate($candidate);

        $this->assertSame('ok', $gate['status']);
        $this->assertTrue(collect($gate['reasons'])->contains(fn ($r) => str_contains($r, 'Format nomor telepon tidak valid')));
    }

    public function test_duplikat_nama_koordinat_ditolak_dengan_alasan(): void
    {
        $this->buatKandidat();
        $kembar = $this->buatKandidat(['nama_kos' => 'kost uji damai']); // nama sama (beda kapital), koordinat sama

        $gate = $this->service->deterministicGate($kembar);

        $this->assertSame('rejected', $gate['status']);
        $this->assertTrue(collect($gate['reasons'])->contains(fn ($r) => str_contains($r, 'Duplikat')));
    }

    public function test_duplikat_dengan_tabel_kos_ditolak(): void
    {
        \App\Models\Kos::create([
            'nama_kos' => 'Kost Maply Lama',
            'alamat' => 'Jl. Lama No. 2',
            'kota' => 'Palembang',
            'tipe' => 'Putra',
            'latitude' => -2.986500,
            'longitude' => 104.734400,
        ]);

        $candidate = $this->buatKandidat(['nama_kos' => 'Kost Maply Lama']);

        $gate = $this->service->deterministicGate($candidate);

        $this->assertSame('rejected', $gate['status']);
        $this->assertTrue(collect($gate['reasons'])->contains(fn ($r) => str_contains($r, 'listing kos')));
    }

    /**
     * Pemetaan status sesuai spesifikasi:
     *   skor >= 75 DAN verdict valid -> strong_evidence
     *   skor 40-74 ATAU verdict suspicious -> needs_review
     *   verdict invalid ATAU skor < 40 -> rejected
     */
    public function test_pemetaan_status_sesuai_spesifikasi(): void
    {
        $kasus = [
            // [status gerbang, skor gerbang, verdict AI, confidence, status diharapkan]
            ['ok', 90, 'valid', 0.90, 'strong_evidence'],
            ['ok', 90, 'suspicious', 0.80, 'needs_review'],
            ['ok', 90, 'invalid', 0.90, 'rejected'],
            ['ok', 20, null, null, 'rejected'],              // tanpa AI, skor normalisasi < 40
            ['suspicious', 90, 'valid', 0.95, 'suspicious'], // kata terlarang: admin memutuskan
        ];

        foreach ($kasus as [$gateStatus, $skorGate, $verdict, $confidence, $diharapkan]) {
            $candidate = $this->buatKandidat();

            $gate = ['status' => $gateStatus, 'reasons' => ['uji'], 'score' => $skorGate];
            $ai = $verdict === null ? null : [
                'verdict' => $verdict,
                'confidence' => $confidence,
                'reasons' => ['alasan uji'],
                'model' => 'gemini-uji',
                'diproses_pada' => now()->toAtomString(),
            ];

            $status = $this->service->apply($candidate, $gate, $ai);

            $this->assertSame(
                $diharapkan,
                $status,
                'Kasus gate=' . $gateStatus . ' skor=' . $skorGate . ' verdict=' . ($verdict ?? 'null'),
            );
        }
    }

    public function test_skor_gabungan_mengikuti_formula(): void
    {
        // Kelengkapan penuh (6/6) = 100. Skor = 0.5*80 + 0.35*(0.8*100) + 0.15*100 = 83.
        $candidate = $this->buatKandidat();

        $gate = ['status' => 'ok', 'reasons' => [], 'score' => 80];
        $ai = ['verdict' => 'valid', 'confidence' => 0.8, 'reasons' => [], 'model' => 'gemini-uji', 'diproses_pada' => now()->toAtomString()];

        $status = $this->service->apply($candidate, $gate, $ai);

        $this->assertSame(83, $candidate->fresh()->trust_score);
        $this->assertSame('strong_evidence', $status);
        $this->assertSame(0.8, $candidate->fresh()->ai_confidence);
        $this->assertSame('valid', $candidate->fresh()->ai_verdict);
        $this->assertSame('gemini-uji', $candidate->fresh()->ai_analysis['model']);
    }

    public function test_tanpa_ai_bobot_dinormalisasi(): void
    {
        // det=80, kelengkapan=100 -> (0.5*80 + 0.15*100) / 0.65 = (40+15)/0.65 = 84.6 -> 85.
        $candidate = $this->buatKandidat();

        $gate = ['status' => 'ok', 'reasons' => [], 'score' => 80];

        $this->service->apply($candidate, $gate, null);

        $this->assertSame(85, $candidate->fresh()->trust_score);
        $this->assertNull($candidate->fresh()->ai_verdict);
    }

    public function test_kelengkapan_proporsi_field_terisi(): void
    {
        $this->assertSame(100, ValidationService::skorKelengkapan('Kost', 'Jl. X', -2.9, 104.7, '0812', 500000, 'Putri'));
        $this->assertSame(0, ValidationService::skorKelengkapan(null, null, null, null, null, null, 'Belum diketahui'));
        $this->assertSame(33, ValidationService::skorKelengkapan('Kost', null, -2.9, 104.7, null, null, 'Belum diketahui'));
    }

    public function test_format_telepon(): void
    {
        $this->assertTrue(ValidationService::teleponValid('081234567890'));
        $this->assertTrue(ValidationService::teleponValid('+62 812-3456-7890'));
        $this->assertFalse(ValidationService::teleponValid('12345'));
        $this->assertFalse(ValidationService::teleponValid(null));
    }

    public function test_kriteria_pencarian_menghasilkan_catatan_relevansi_tanpa_mengubah_status(): void
    {
        $candidate = $this->buatKandidat(['harga' => 750000, 'tipe' => 'Putri', 'kecamatan' => 'Ilir Barat II']);

        // Kriteria admin: harga 800rb-1jt, tipe Putra, kecamatan Sukarami -> semua tidak cocok.
        $gate = $this->service->deterministicGate($candidate, [
            'harga_min' => 800000,
            'harga_max' => 1000000,
            'tipe' => 'Putra',
            'kecamatan' => 'Sukarami',
        ]);

        $this->assertSame('ok', $gate['status']);
        $teks = implode('\n', $gate['reasons']);
        $this->assertStringContainsString('di bawah rentang', $teks);
        $this->assertStringContainsString('tidak cocok dengan kriteria admin', $teks);
        $this->assertStringContainsString('berbeda dari kriteria admin', $teks);

        // Tanpa kriteria: tidak ada catatan relevansi (perilaku lama tetap).
        $gateKosong = $this->service->deterministicGate($candidate);
        $this->assertSame([], array_values(array_filter($gateKosong['reasons'], fn ($r) => str_contains($r, 'kriteria admin'))));
    }
}

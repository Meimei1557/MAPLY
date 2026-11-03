<?php

namespace App\Support\AiData;

use App\Models\AiKosCandidate;
use Illuminate\Support\Facades\Log;

/**
 * ValidationService — TAHAP 3: penyaringan validitas data dengan 2 lapis.
 *
 * LAPIS A — Gerbang deterministik (gratis, selalu jalan):
 *   1. Koordinat wajib dalam bbox Palembang/Indralaya -> di luar = invalid.
 *   2. Nama mengandung kata terlarang -> suspicious (bukan reject; admin memutuskan).
 *   3. Harga mustahil (< Rp 50rb atau > Rp 20jt/bulan) -> flag.
 *   4. Format telepon tidak valid -> flag ringan.
 *   5. Duplikat (DuplicateDetector) -> rejected dengan alasan.
 *
 * LAPIS B — Penilaian semantik AI (Gemini) hanya untuk kandidat yang lolos Lapis A.
 *
 * Skor akhir gabungan (trust_score 0-100):
 *   0.5 x skor_deterministik + 0.35 x (confidence_ai x 100) + 0.15 x skor_kelengkapan.
 * Bila AI tidak tersedia, bobot dinormalisasi: (0.5 x det + 0.15 x kel) / 0.65.
 */
class ValidationService
{
    public function __construct(
        protected DuplicateDetector $duplicates,
        protected GeminiClient $gemini,
    ) {
    }

    // ================================================================ LAPIS A

    /**
     * Gerbang deterministik. Mengembalikan:
     * ['status' => ok|suspicious|invalid|rejected, 'reasons' => string[], 'score' => 0-100]
     *
     * $kriteria (opsional, poin 10b/c) menambahkan CATATAN RELEVANSI — harga di luar
     * rentang, tipe tidak cocok, kecamatan tidak cocok — tanpa mengubah status:
     * kriteria adalah preferensi pencarian, bukan penentu validitas.
     *
     * @return array{status: string, reasons: array, score: int}
     */
    public function deterministicGate(AiKosCandidate $candidate, array $kriteria = []): array
    {
        $reasons = [];
        $status = 'ok';

        // --- 1. Gerbang koordinat (bbox wilayah layanan). ---
        $dalamBbox = $this->koordinatDalamBbox($candidate->latitude, $candidate->longitude, $candidate->kota);

        if ($candidate->latitude !== null && $candidate->longitude !== null && !$dalamBbox) {
            $status = 'invalid';
            $reasons[] = 'Koordinat berada di luar wilayah layanan Maply (Palembang dan Indralaya).';
        }

        // --- 2. Kata terlarang pada nama -> suspicious. ---
        $terlarang = $this->kataTerlarang((string) $candidate->nama_kos);

        if ($terlarang !== [] && $status !== 'invalid') {
            $status = 'suspicious';
            $reasons[] = 'Nama mengandung kata terlarang ("' . implode('", "', $terlarang) . '"). Bisa saja kost berada di atas bangunan tersebut — admin yang memutuskan.';
        }

        // --- 3. Harga mustahil -> flag. ---
        $hargaFlag = false;

        if ($candidate->harga !== null) {
            $min = (int) config('ai_pipeline.validation.harga_min', 50000);
            $max = (int) config('ai_pipeline.validation.harga_max', 20000000);

            if ($candidate->harga < $min || $candidate->harga > $max) {
                $hargaFlag = true;
                $reasons[] = 'Harga ' . number_format($candidate->harga, 0, ',', '.') . ' per bulan tidak wajar (di luar rentang ' . number_format($min, 0, ',', '.') . ' - ' . number_format($max, 0, ',', '.') . ').';
            }
        }

        // --- 4. Format telepon tidak valid -> flag ringan. ---
        $teleponFlag = false;

        if ($candidate->telepon !== null && !self::teleponValid($candidate->telepon)) {
            $teleponFlag = true;
            $reasons[] = 'Format nomor telepon tidak valid (' . $candidate->telepon . ').';
        }

        // --- 5. Duplikat -> rejected dengan alasan. ---
        $alasanDuplikat = $this->duplicates->findNameCoordinateDuplicate(
            $candidate->nama_kos,
            $candidate->latitude,
            $candidate->longitude,
            ignoreId: $candidate->id,
        );

        if ($alasanDuplikat !== null) {
            $status = 'rejected';
            $reasons[] = $alasanDuplikat;
        }

        // --- 6. Catatan relevansi terhadap kriteria pencarian admin (tidak mengubah status). ---
        $reasons = array_merge($reasons, $this->catatanKriteria($candidate, $kriteria));

        return [
            'status' => $status,
            'reasons' => $reasons,
            'score' => $this->skorDeterministik($candidate, $status, $hargaFlag, $dalamBbox),
        ];
    }

    /**
     * Catatan relevansi kandidat terhadap kriteria pencarian admin (harga/tipe/kecamatan).
     *
     * @return string[]
     */
    protected function catatanKriteria(AiKosCandidate $candidate, array $kriteria): array
    {
        if ($kriteria === []) {
            return [];
        }

        $catatan = [];

        if (isset($kriteria['harga_min']) && $candidate->harga !== null && $candidate->harga < (int) $kriteria['harga_min']) {
            $catatan[] = 'Harga di bawah rentang pencarian admin (kriteria min ' . number_format((int) $kriteria['harga_min'], 0, ',', '.') . ').';
        }

        if (isset($kriteria['harga_max']) && $candidate->harga !== null && $candidate->harga > (int) $kriteria['harga_max']) {
            $catatan[] = 'Harga di atas rentang pencarian admin (kriteria maks ' . number_format((int) $kriteria['harga_max'], 0, ',', '.') . ').';
        }

        if (!empty($kriteria['tipe']) && $candidate->tipe !== null && $candidate->tipe !== 'Belum diketahui'
            && strcasecmp($candidate->tipe, (string) $kriteria['tipe']) !== 0) {
            $catatan[] = 'Tipe kos (' . $candidate->tipe . ') tidak cocok dengan kriteria admin (' . $kriteria['tipe'] . ').';
        }

        if (!empty($kriteria['kecamatan']) && $candidate->kecamatan !== null
            && mb_strtolower(trim($candidate->kecamatan)) !== mb_strtolower(trim((string) $kriteria['kecamatan']))) {
            $catatan[] = 'Kecamatan kandidat (' . $candidate->kecamatan . ') berbeda dari kriteria admin (' . $kriteria['kecamatan'] . ').';
        }

        return $catatan;
    }

    /**
     * Skor deterministik 0-100 dari kelengkapan bukti + penalti gerbang.
     */
    protected function skorDeterministik(
        AiKosCandidate $candidate,
        string $status,
        bool $hargaFlag,
        bool $dalamBbox,
    ): int {
        $score = 0;

        $score += trim((string) $candidate->nama_kos) !== '' ? 10 : 0;
        $score += ($candidate->latitude !== null && $candidate->longitude !== null && $dalamBbox) ? 25 : 0;
        $score += trim((string) $candidate->alamat) !== '' ? 15 : 0;
        $score += ($candidate->telepon !== null && self::teleponValid($candidate->telepon)) ? 10 : 0;
        $score += ($candidate->harga !== null && !$hargaFlag) ? 15 : (($candidate->harga !== null && $hargaFlag) ? 5 : 0);
        $score += ($candidate->tipe !== null && $candidate->tipe !== 'Belum diketahui') ? 10 : 0;
        $score += $candidate->sources()->exists() ? 15 : 0;

        $score = min(100, $score);

        if ($status === 'invalid' || $status === 'rejected') {
            return 0;
        }

        if ($status === 'suspicious') {
            return min($score, 55);
        }

        if ($hargaFlag) {
            return min($score, 60);
        }

        return $score;
    }

    /**
     * Cek koordinat dalam bbox wilayah (kota dikenali) atau salah satu bbox bila kota tidak dikenali.
     */
    public function koordinatDalamBbox(int|float|null $lat, int|float|null $lng, ?string $kota = null): bool
    {
        if (!is_numeric($lat) || !is_numeric($lng)) {
            return false;
        }

        $lat = (float) $lat;
        $lng = (float) $lng;

        $wilayah = collect((array) config('ai_pipeline.regions', []));

        // Kandidat tanpa kota dikenali: dianggap dalam wilayah bila masuk salah satu bbox.
        $diperiksa = $wilayah->filter(function ($region) use ($kota) {
            return $kota !== null && strcasecmp(trim($kota), $region['label']) === 0;
        });

        $diperiksa = $diperiksa->isNotEmpty() ? $diperiksa : $wilayah;

        return $diperiksa->contains(function ($region) use ($lat, $lng) {
            return $lat >= $region['bbox']['lat'][0] && $lat <= $region['bbox']['lat'][1]
                && $lng >= $region['bbox']['lng'][0] && $lng <= $region['bbox']['lng'][1];
        });
    }

    /**
     * Daftar kata terlarang yang muncul pada nama (case-insensitive).
     *
     * @return string[]
     */
    public function kataTerlarang(string $nama): array
    {
        $namaLower = mb_strtolower($nama);

        return collect((array) config('ai_pipeline.validation.kata_terlarang', []))
            ->filter(fn ($kata) => str_contains($namaLower, mb_strtolower($kata)))
            ->values()
            ->all();
    }

    /**
     * Format telepon Indonesia yang dapat diterima: 9-16 digit, boleh diawali +,
     * boleh memakai spasi/strip/kurung (dibersihkan dulu).
     */
    public static function teleponValid(?string $telepon): bool
    {
        if ($telepon === null) {
            return false;
        }

        $clean = preg_replace('/[\s\-().]/', '', trim($telepon)) ?? '';

        return preg_match('/^\+?\d{9,16}$/', $clean) === 1;
    }

    /**
     * Proporsi field terisi (0-100) dari: nama, alamat, koordinat, telepon, harga, tipe.
     */
    public static function skorKelengkapan(
        ?string $nama,
        ?string $alamat,
        int|float|null $lat,
        int|float|null $lng,
        ?string $telepon,
        int|float|null $harga,
        ?string $tipe,
    ): int {
        $terisi = 0;

        $terisi += ($nama !== null && trim($nama) !== '') ? 1 : 0;
        $terisi += ($alamat !== null && trim($alamat) !== '') ? 1 : 0;
        $terisi += (is_numeric($lat) && is_numeric($lng)) ? 1 : 0;
        $terisi += ($telepon !== null && trim($telepon) !== '') ? 1 : 0;
        $terisi += ($harga !== null && (int) $harga > 0) ? 1 : 0;
        $terisi += ($tipe !== null && $tipe !== 'Belum diketahui') ? 1 : 0;

        return (int) round($terisi / 6 * 100);
    }

    public static function skorKelengkapanKandidat(AiKosCandidate $candidate): int
    {
        return self::skorKelengkapan(
            $candidate->nama_kos,
            $candidate->alamat,
            $candidate->latitude,
            $candidate->longitude,
            $candidate->telepon,
            $candidate->harga,
            $candidate->tipe,
        );
    }

    // ================================================================ LAPIS B

    /**
     * Penilaian semantik AI untuk batch kandidat (maks 5 per request).
     *
     * Mengirim SEMUA bukti ai_candidate_sources + hasil ai_extracted, lalu meminta
     * verdict valid|suspicious|invalid beserta confidence dan maks 5 alasan (Bahasa Indonesia).
     *
     * @param  AiKosCandidate[]  $candidates
     * @return array<int, array{verdict: string, confidence: float, reasons: array, model: string, diproses_pada: string}>
     *         dipetakan berdasarkan id kandidat; kosong bila AI gagal/tidak tersedia.
     */
    public function aiAssessBatch(array $candidates): array
    {
        if ($candidates === [] || !GeminiClient::configured()) {
            return [];
        }

        $system = 'Kamu adalah validator data untuk aplikasi pencari kost Maply (Palembang dan Indralaya). '
            . "Pertanyaan yang dijawab: \"Bukti ini konsisten mengindikasikan tempat berfungsi sebagai kost/pondok indekos?\"\n"
            . "ATURAN MUTLAK:\n"
            . "1. Nilai bukti apa adanya. JANGAN mengarang fakta yang tidak ada pada bukti.\n"
            . "2. verdict hanya boleh: \"valid\" (bukti konsisten sebagai kost), \"suspicious\" (ragu/mencurigakan), atau \"invalid\" (bukti menunjukkan bukan kost).\n"
            . "3. confidence angka 0.0 sampai 1.0.\n"
            . "4. reasons maksimal 5 poin singkat dalam Bahasa Indonesia.\n"
            . "5. Balas HANYA JSON valid tanpa teks lain.";

        $items = [];

        foreach ($candidates as $candidate) {
            $items[] = [
                'id' => $candidate->id,
                'nama' => $candidate->nama_kos,
                'kota' => $candidate->kota,
                'data_tercatat' => [
                    'alamat' => $candidate->alamat,
                    'kecamatan' => $candidate->kecamatan,
                    'harga' => $candidate->harga,
                    'tipe' => $candidate->tipe,
                    'telepon' => $candidate->telepon,
                ],
                'hasil_ekstraksi_ai' => $candidate->ai_extracted,
                'bukti_sumber' => $candidate->sources
                    ->map(fn ($source) => [
                        'sumber' => $source->source_name,
                        'url' => $source->source_url,
                        'bukti' => self::potong($source->evidence),
                    ])
                    ->all(),
                'data_mentah' => self::potong($candidate->raw_data),
            ];
        }

        $prompt = "Nilai setiap kandidat berikut.\n"
            . json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            . "\n\nBalas dengan skema tepat ini: "
            . '{"hasil":[{"id":123,"verdict":"valid|suspicious|invalid","confidence":0.85,"reasons":["poin 1","poin 2"]}]}';

        $result = $this->gemini->generateJson($system, $prompt);

        if (!$result['ok']) {
            Log::warning('Maply pipeline: penilaian AI (Lapis B) gagal', [
                'error' => $result['error'],
            ]);

            return [];
        }

        $penilaian = [];

        foreach (($result['data']['hasil'] ?? []) as $row) {
            if (!is_array($row) || !isset($row['id'])) {
                continue;
            }

            $id = (int) $row['id'];
            $verdict = mb_strtolower(trim((string) ($row['verdict'] ?? '')));

            if (!in_array($verdict, ['valid', 'suspicious', 'invalid'], true)) {
                continue;
            }

            $confidence = (float) ($row['confidence'] ?? 0);
            $confidence = max(0.0, min(1.0, $confidence));

            $penilaian[$id] = [
                'verdict' => $verdict,
                'confidence' => $confidence,
                'reasons' => collect($row['reasons'] ?? [])
                    ->map(fn ($reason) => trim((string) $reason))
                    ->filter()
                    ->values()
                    ->take(5)
                    ->all(),
                'model' => (string) config('services.gemini.model', 'gemini-2.5-flash'),
                'diproses_pada' => now()->toAtomString(),
            ];
        }

        return $penilaian;
    }

    // ================================================================ GABUNGAN

    /**
     * Terapkan hasil Lapis A (+ Lapis B bila ada) ke kandidat:
     * hitung skor gabungan, tentukan status, dan simpan kolom AI.
     *
     * @param  array{status: string, reasons: array, score: int}  $gate  hasil deterministicGate()
     * @param  array|null  $ai  satu elemen hasil aiAssessBatch() atau null bila AI tidak jalan.
     * @return string status akhir kandidat.
     */
    public function apply(AiKosCandidate $candidate, array $gate, ?array $ai = null): string
    {
        $kelengkapan = self::skorKelengkapanKandidat($candidate);

        $bobot = [
            (float) config('ai_pipeline.validation.bobot_deterministik', 0.5),
            (float) config('ai_pipeline.validation.bobot_ai', 0.35),
            (float) config('ai_pipeline.validation.bobot_kelengkapan', 0.15),
        ];

        if ($ai !== null) {
            $skor = round(
                $bobot[0] * $gate['score']
                + $bobot[1] * (($ai['confidence'] ?? 0) * 100)
                + $bobot[2] * $kelengkapan,
            );
        } else {
            // AI tidak tersedia: normalisasi bobot agar skor tetap 0-100.
            $skor = round(($bobot[0] * $gate['score'] + $bobot[2] * $kelengkapan) / ($bobot[0] + $bobot[2]));
        }

        $skor = (int) max(0, min(100, $skor));

        $verdict = $ai['verdict'] ?? null;
        $status = $gate['status'];

        if ($status === 'rejected') {
            // Duplikat dari gerbang deterministik — tetap rejected.
        } elseif ($status === 'invalid' || $verdict === 'invalid' || $skor < 40) {
            $status = 'rejected';
        } elseif ($status === 'suspicious') {
            // Kata terlarang: biarkan admin memutuskan (bukan auto-reject).
            $status = 'suspicious';
        } elseif ($verdict === 'suspicious') {
            $status = 'needs_review';
        } elseif ($skor >= 75 && $verdict === 'valid') {
            $status = 'strong_evidence';
        } else {
            $status = 'needs_review';
        }

        // Gabungkan catatan lama (mis. kegagalan ekstraksi dari Tahap 2) dengan alasan baru.
        $reasons = collect([
            $candidate->verification_reasons ?? [],
            $gate['reasons'] ?? [],
            $ai['reasons'] ?? [],
        ])
            ->flatten()
            ->filter()
            ->unique()
            ->values()
            ->take(12)
            ->all();

        $update = [
            'trust_score' => $skor,
            'verification_status' => $status,
            'verification_reasons' => $reasons,
            'ai_verdict' => $verdict ?? $candidate->ai_verdict,
            'ai_confidence' => isset($ai['confidence']) ? $ai['confidence'] : $candidate->ai_confidence,
        ];

        if ($ai !== null) {
            $update['ai_analysis'] = [
                'verdict' => $ai['verdict'],
                'confidence' => $ai['confidence'],
                'reasons' => $ai['reasons'],
                'model' => $ai['model'] ?? (string) config('services.gemini.model', 'gemini-2.5-flash'),
                'diproses_pada' => $ai['diproses_pada'] ?? now()->toAtomString(),
            ];
        }

        $candidate->update($update);

        return $status;
    }

    /**
     * Batasi ukuran bukti yang dikirim ke AI (hemat token, tetap jujur isinya).
     */
    protected static function potong(mixed $value, int $maksKarakter = 4000): mixed
    {
        if ($value === null) {
            return null;
        }

        $encoded = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($encoded === false || mb_strlen($encoded) <= $maksKarakter) {
            return $value;
        }

        return mb_substr($encoded, 0, $maksKarakter);
    }
}

<?php

namespace App\Jobs;

use App\Models\AiKosCandidate;
use App\Models\AiSearchRun;
use App\Support\AiData\GeminiClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * TAHAP 2 — Pemrosesan data kandidat oleh AI (Gemini).
 *
 * Mengambil kandidat needs_review yang datanya belum lengkap, mengirim raw_data
 * ke Gemini dengan instruksi tegas, lalu menyimpan hasil ekstraksi ke kolom baru
 * ai_extracted — kolom asli kandidat TIDAK pernah ditimpa.
 *
 * Guardrail:
 *  - batch maks 5 kandidat per request;
 *  - retry 1x (di level aplikasi) bila balasan bukan JSON valid;
 *  - bila tetap gagal -> dicatat di verification_reasons lalu lanjut;
 *  - timeout HTTP 25 detik + log warning (ada di GeminiClient).
 */
class ProcessCandidateWithAiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 120;

    public function __construct(
        public ?int $runId = null,
        public array $candidateIds = [],
    ) {
    }

    public function handle(GeminiClient $gemini): void
    {
        $candidates = $this->kandidatUntukDiproses();

        if ($candidates->isEmpty()) {
            $this->tulisPesan('Pemrosesan AI: tidak ada kandidat yang perlu diproses.');
            return;
        }

        if (!GeminiClient::configured()) {
            $this->tulisPesan('Pemrosesan AI dilewati: GEMINI_API_KEY belum diatur pada .env. Kandidat tetap dapat diperiksa manual oleh admin.');
            return;
        }

        // Kriteria pencarian terstruktur dari run (poin 10b) — membantu ekstraksi, TIDAK mengizinkan karangan.
        $kriteria = $this->kriteriaRun();

        $batchSize = max(1, (int) config('ai_pipeline.gemini.batch_size', 5));
        $terproses = 0;
        $gagal = 0;

        foreach ($candidates->chunk($batchSize) as $batch) {
            $hasil = $this->ekstrakBatch($gemini, $batch->all(), kriteria: $kriteria);

            if ($hasil === null) {
                // Retry 1x bila JSON invalid.
                $hasil = $this->ekstrakBatch($gemini, $batch->all(), sudahRetry: true, kriteria: $kriteria);
            }

            if ($hasil === null) {
                foreach ($batch as $candidate) {
                    $this->catatGagal(
                        $candidate,
                        'Pemrosesan AI gagal: respons Gemini bukan JSON valid setelah 2 percobaan.',
                    );
                }
                $gagal += $batch->count();
                continue;
            }

            $peta = collect($hasil)
                ->keyBy(fn ($row) => (int) ($row['id'] ?? 0));

            foreach ($batch as $candidate) {
                $row = $peta->get($candidate->id);

                if ($row === null) {
                    $this->catatGagal($candidate, 'Pemrosesan AI: kandidat tidak ada pada respons Gemini.');
                    $gagal++;
                    continue;
                }

                $candidate->update([
                    'ai_extracted' => $this->normalisasi($row),
                    'ai_processed_at' => now(),
                ]);
                $terproses++;
            }
        }

        $this->tulisPesan("Pemrosesan AI selesai: {$terproses} kandidat diekstraksi, {$gagal} gagal.");
    }

    /**
     * Kandidat yang perlu diproses: kandidat run (atau daftar id eksplisit) yang
     * datanya belum lengkap dan belum pernah diproses AI.
     */
    protected function kandidatUntukDiproses()
    {
        $query = AiKosCandidate::query()->orderBy('id');

        if ($this->candidateIds !== []) {
            // Reproses eksplisit (command/aksi massal): proses apa adanya.
            return $query->whereIn('id', $this->candidateIds)
                ->limit((int) config('ai_pipeline.gemini.max_candidates_per_job', 400))
                ->get();
        }

        $query->whereNull('ai_processed_at')
            ->whereIn('verification_status', ['needs_review', 'suspicious'])
            ->where(function ($q) {
                $q->whereNull('harga')
                    ->orWhereNull('telepon')
                    ->orWhereNull('alamat')
                    ->orWhereNull('kecamatan')
                    ->orWhere('tipe', 'Belum diketahui');
            });

        if ($this->runId) {
            $query->where('search_run_id', $this->runId);
        }

        return $query
            ->limit((int) config('ai_pipeline.gemini.max_candidates_per_job', 400))
            ->get();
    }

    /** Kriteria terstruktur pada run ini (kosong bila tidak ada). */
    protected function kriteriaRun(): array
    {
        if (!$this->runId) {
            return [];
        }

        $run = AiSearchRun::find($this->runId);

        return is_array($kriteria = data_get($run?->options, 'kriteria')) ? $kriteria : [];
    }

    /**
     * Kirim satu batch ke Gemini. Mengembalikan array "hasil" atau null bila gagal.
     *
     * @return array|null
     */
    protected function ekstrakBatch(GeminiClient $gemini, array $batch, bool $sudahRetry = false, array $kriteria = []): ?array
    {
        $items = [];

        foreach ($batch as $candidate) {
            $items[] = [
                'id' => $candidate->id,
                'sumber' => $candidate->source_name,
                'url_sumber' => $candidate->source_url,
                'data_tercatat' => [
                    'nama' => $candidate->nama_kos,
                    'alamat' => $candidate->alamat,
                    'kecamatan' => $candidate->kecamatan,
                    'kota' => $candidate->kota,
                    'harga' => $candidate->harga,
                    'tipe' => $candidate->tipe,
                    'telepon' => $candidate->telepon,
                    'website' => $candidate->website,
                ],
                'data_mentah' => $candidate->raw_data,
            ];
        }

        $system = 'Kamu mengekstrak dan menormalisasi data kost Indonesia dari bukti mentah untuk aplikasi Maply. '
            . "ATURAN MUTLAK:\n"
            . "1. DILARANG mengarang. Jika bukti tidak memuat informasi tersebut, isi NULL.\n"
            . "2. nama: nama bersih tempat/kost tanpa awalan listing pasar. alamat: alamat lengkap bila ditemukan.\n"
            . "3. kecamatan: nama kecamatan hanya jika yakin dari bukti; selain itu NULL.\n"
            . "4. harga_per_bulan: integer rupiah PER BULAN. Contoh: \"Rp 500rb\"/\"500k\" (per bulan) -> 500000; "
            . "\"Rp 7.7 juta/tahun\" -> 641667 (7700000 dibagi 12, dibulatkan) HANYA karena periode eksplisit per tahun; "
            . "angka tanpa keterangan periode yang jelas -> NULL.\n"
            . "5. tipe: \"Putra\"|\"Putri\"|\"Campur\"|\"Belum diketahui\" — hanya jika ada bukti teks eksplisit.\n"
            . "6. fasilitas: daftar string dari bukti; kosongkan bila tidak ada.\n"
            . "7. telepon: format E.164 Indonesia (+62...). Selain itu NULL.\n"
            . "8. Balas HANYA JSON valid, tanpa penjelasan, tanpa pembungkus markdown.";

        if ($kriteria !== []) {
            $system .= "\nKriteria pencarian admin (konteks saja — JANGAN dipakai untuk mengisi data yang tidak ada di bukti): "
                . json_encode($kriteria, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $perintah = 'Ekstrak kandidat berikut:'
            . "\n" . json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            . "\n\nBalas dengan skema tepat ini: "
            . '{"hasil":[{"id":123,"nama":"...","alamat":"...","kecamatan":null,"harga_per_bulan":null,"tipe":"Belum diketahui","fasilitas":[],"telepon":null}]}';

        if ($sudahRetry) {
            $perintah = "PERBAIKI: jawaban sebelumnya bukan JSON valid. " . $perintah;
        }

        $result = $gemini->generateJson($system, $perintah);

        if (!$result['ok']) {
            return null;
        }

        $hasil = $result['data']['hasil'] ?? null;

        if (!is_array($hasil) || $hasil === []) {
            return null;
        }

        return $hasil;
    }

    /**
     * Normalisasi hasil ekstraksi menjadi payload ai_extracted yang aman disimpan.
     * Nilai tanpa bukti/tidak valid -> NULL (prinsip kejujuran data).
     */
    protected function normalisasi(array $row): array
    {
        $teks = fn ($value, $maks) => (is_string($value) && trim($value) !== '' && mb_strtolower(trim($value)) !== 'null')
            ? mb_substr(trim($value), 0, $maks)
            : null;

        $harga = $row['harga_per_bulan'] ?? null;

        return [
            'nama' => $teks($row['nama'] ?? null, 255),
            'alamat' => $teks($row['alamat'] ?? null, 2000),
            'kecamatan' => $teks($row['kecamatan'] ?? null, 100),
            'harga_per_bulan' => (is_numeric($harga) && (int) $harga > 0 && (int) $harga <= 100000000)
                ? (int) $harga
                : null,
            'tipe' => in_array($row['tipe'] ?? '', ['Putra', 'Putri', 'Campur', 'Belum diketahui'], true)
                ? $row['tipe']
                : null,
            'fasilitas' => collect(is_array($row['fasilitas'] ?? null) ? $row['fasilitas'] : [])
                ->map(fn ($item) => trim((string) $item))
                ->filter()
                ->unique()
                ->values()
                ->take(30)
                ->all(),
            'telepon' => $teks($row['telepon'] ?? null, 25),
        ];
    }

    /**
     * Catat kegagalan ekstraksi pada kandidat tanpa menghentikan pipeline.
     */
    protected function catatGagal(AiKosCandidate $candidate, string $alasan): void
    {
        $reasons = collect($candidate->verification_reasons ?? [])
            ->push($alasan)
            ->unique()
            ->values()
            ->take(15)
            ->all();

        $candidate->update(['verification_reasons' => $reasons]);
    }

    /**
     * Tambah pesan progres ke ai_search_runs.message (dan Log).
     */
    protected function tulisPesan(string $pesan): void
    {
        Log::info('Maply pipeline: ' . $pesan, ['run_id' => $this->runId, 'job' => static::class]);

        if (!$this->runId) {
            return;
        }

        $run = AiSearchRun::find($this->runId);

        if (!$run) {
            return;
        }

        $run->update([
            'message' => trim(($run->message ? $run->message . "\n" : '') . $pesan),
        ]);
    }

    public function failed(Throwable $e): void
    {
        Log::error('Maply pipeline: job pemrosesan AI gagal', [
            'run_id' => $this->runId,
            'error' => $e->getMessage(),
        ]);

        $this->tulisPesan('Pemrosesan AI (Tahap 2) gagal: ' . $e->getMessage());
    }
}

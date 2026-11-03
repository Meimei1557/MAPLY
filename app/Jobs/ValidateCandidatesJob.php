<?php

namespace App\Jobs;

use App\Models\AiKosCandidate;
use App\Models\AiSearchRun;
use App\Support\AiData\GeminiClient;
use App\Support\AiData\ValidationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * TAHAP 3 — Penyaringan validitas data (chain setelah Tahap 2).
 *
 * Untuk setiap kandidat yang belum direview admin:
 *  1. Lapis A (gerbang deterministik) — selalu jalan.
 *  2. Lapis B (penilaian semantik AI) — hanya untuk kandidat yang lolos Lapis A.
 *  3. Skor gabungan disimpan ke trust_score, status dipetakan otomatis.
 *
 * Hasil AI TIDAK PERNAH mem-publish — hanya memberi prioritas & badge review admin.
 */
class ValidateCandidatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 600;

    public function __construct(
        public ?int $runId = null,
        public array $candidateIds = [],
    ) {
    }

    public function handle(ValidationService $validation): void
    {
        $candidates = $this->kandidatUntukValidasi();

        if ($candidates->isEmpty()) {
            $this->tulisPesan('Validasi: tidak ada kandidat untuk divalidasi.');
            $this->selesaikanRun();
            return;
        }

        $lolosLapisA = [];
        $ditolakGerbang = 0;

        // Kriteria pencarian terstruktur dari run (poin 10b/c): jadi catatan relevansi.
        $run = $this->runId ? AiSearchRun::find($this->runId) : null;
        $kriteria = is_array($k = data_get($run?->options, 'kriteria')) ? $k : [];

        foreach ($candidates as $candidate) {
            $gate = $validation->deterministicGate($candidate, $kriteria);

            if ($gate['status'] === 'rejected') {
                $validation->apply($candidate, $gate);
                $ditolakGerbang++;
                continue;
            }

            $lolosLapisA[] = [$candidate, $gate];
        }

        // Lapis B: penilaian semantik AI per batch (maks 5 kandidat/request).
        $penilaian = [];

        if (!GeminiClient::configured()) {
            $this->tulisPesan('Penilaian AI (Lapis B) dilewati: GEMINI_API_KEY belum diatur pada .env. Hanya gerbang deterministik yang dipakai.');
        } else {
            foreach (collect($lolosLapisA)->chunk((int) config('ai_pipeline.gemini.batch_size', 5)) as $chunk) {
                $batch = $chunk->map(fn ($item) => $item[0])->values()->all();
                $penilaian += $validation->aiAssessBatch($batch);
            }
        }

        foreach ($lolosLapisA as [$candidate, $gate]) {
            $ai = $penilaian[$candidate->id] ?? null;

            if ($ai === null && GeminiClient::configured()) {
                // Catat kegagalan Lapis B agar admin tahu kandidat ini belum dinilai AI.
                $reasons = collect($candidate->verification_reasons ?? [])
                    ->push('Penilaian AI (Lapis B) tidak tersedia untuk kandidat ini; keputusan berdasarkan gerbang deterministik.')
                    ->unique()
                    ->values()
                    ->take(15)
                    ->all();
                $candidate->update(['verification_reasons' => $reasons]);
            }

            $validation->apply($candidate, $gate, $ai);
        }

        $buktiKuat = $candidates->where('verification_status', 'strong_evidence')->count();
        $ditolak = $candidates->where('verification_status', 'rejected')->count();
        $perluDiperiksa = $candidates->count() - $buktiKuat - $ditolak;

        $this->tulisPesan(
            "Validasi selesai: {$buktiKuat} bukti kuat, {$perluDiperiksa} perlu diperiksa admin, {$ditolak} ditolak (termasuk {$ditolakGerbang} duplikat gerbang deterministik)."
        );

        $this->selesaikanRun();

        Log::info('Maply pipeline: validasi selesai', [
            'run_id' => $this->runId,
            'total' => $candidates->count(),
            'strong' => $buktiKuat,
            'rejected' => $ditolak,
        ]);
    }

    /**
     * Kandidat yang divalidasi: milik run (atau daftar id eksplisit), belum
     * dipublikasikan dan belum direview admin.
     */
    protected function kandidatUntukValidasi()
    {
        $query = AiKosCandidate::query()->with('sources')->orderBy('id');

        if ($this->candidateIds !== []) {
            $query->whereIn('id', $this->candidateIds);
        } else {
            $query->whereNull('published_kos_id')
                ->whereNull('reviewed_by')
                ->where('verification_status', '!=', 'published');
        }

        if ($this->runId) {
            $query->where('search_run_id', $this->runId);
        }

        return $query->get();
    }

    /**
     * Tutup run: status completed + statistik akhir + waktu selesai.
     */
    protected function selesaikanRun(): void
    {
        if (!$this->runId) {
            return;
        }

        $run = AiSearchRun::find($this->runId);

        if (!$run) {
            return;
        }

        $run->update([
            'status' => 'completed',
            'valid_count' => $run->candidates()->where('verification_status', 'strong_evidence')->count(),
            'rejected_count' => $run->candidates()->where('verification_status', 'rejected')->count(),
            'finished_at' => now(),
        ]);
    }

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
        Log::error('Maply pipeline: job validasi gagal', [
            'run_id' => $this->runId,
            'error' => $e->getMessage(),
        ]);

        if ($this->runId) {
            $run = AiSearchRun::find($this->runId);

            if ($run) {
                $run->update([
                    'message' => trim(($run->message ? $run->message . "\n" : '') . 'Validasi (Tahap 3) gagal: ' . $e->getMessage()),
                ]);
            }
        }
    }
}

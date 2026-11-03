<?php

namespace App\Console\Commands;

use App\Jobs\ProcessCandidateWithAiJob;
use App\Jobs\ValidateCandidatesJob;
use App\Models\AiKosCandidate;
use Illuminate\Console\Command;

/**
 * Perintah artisan untuk memproses ulang kandidat melalui Tahap 2 (ekstraksi AI)
 * lalu Tahap 3 (validasi), misalnya setelah GEMINI_API_KEY baru diatur.
 *
 * Contoh:
 *   php artisan maply:ai-reprocess
 *   php artisan maply:ai-reprocess --limit=100
 *   php artisan maply:ai-reprocess --run=3
 */
class AiReprocessCommand extends Command
{
    protected $signature = 'maply:ai-reprocess {--limit=50} {--run=}';

    protected $description = 'Memproses ulang kandidat AI (ekstraksi Gemini + validasi) melalui antrean';

    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));
        $runId = $this->option('run') ? (int) $this->option('run') : null;

        $query = AiKosCandidate::query()
            ->whereNull('published_kos_id')
            ->whereNull('reviewed_by');

        if ($runId) {
            $query->where('search_run_id', $runId);
        }

        // Prioritaskan kandidat yang belum pernah diproses AI sama sekali.
        $ids = (clone $query)
            ->orderByRaw('ai_processed_at IS NULL DESC, trust_score DESC, id ASC')
            ->limit($limit)
            ->pluck('id')
            ->all();

        if ($ids === []) {
            $this->info('Tidak ada kandidat yang perlu diproses ulang.');

            return self::SUCCESS;
        }

        // Kelompokkan per run agar pesan progres muncul pada run yang benar.
        $perRun = AiKosCandidate::query()
            ->whereIn('id', $ids)
            ->get()
            ->groupBy(fn ($candidate) => $candidate->search_run_id ?? 0);

        $jumlah = 0;

        foreach ($perRun as $groupRunId => $candidates) {
            $groupIds = $candidates->pluck('id')->all();
            $jumlah += count($groupIds);

            ProcessCandidateWithAiJob::withChain([
                new ValidateCandidatesJob($groupRunId ?: null),
            ])->dispatch($groupRunId ?: null, $groupIds);
        }

        $this->info("Memproses ulang {$jumlah} kandidat melalui antrean (Tahap 2 + Tahap 3).");
        $this->line('Pastikan worker berjalan: php artisan queue:work');

        return self::SUCCESS;
    }
}

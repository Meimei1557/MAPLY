<?php

namespace App\Jobs;

use App\Models\AiKosCandidate;
use App\Models\AiSearchRun;
use App\Support\AiData\DuplicateDetector;
use App\Support\AiData\RawCandidate;
use App\Support\AiData\SourceConnectorManager;
use App\Support\AiData\ValidationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

/**
 * TAHAP 1 — Orchestrator ingest multi-sumber (pengganti SearchKosPlacesJob).
 *
 * Menerima AiSearchRun dengan daftar sumber terpilih (ai_search_runs.sources),
 * memanggil tiap connector berurutan, menulis progres per sumber ke
 * ai_search_runs.message, lalu menyimpan kandidat + bukti sumber.
 *
 * Chain: dipanggil dengan ->withChain([ProcessCandidateWithAiJob, ValidateCandidatesJob])
 * sehingga Tahap 2 dan 3 berjalan otomatis setelah ingest sukses.
 */
class IngestKosCandidatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 300;

    public function __construct(public int $runId)
    {
    }

    public function handle(
        SourceConnectorManager $manager,
        DuplicateDetector $duplicates,
        ValidationService $validation,
    ): void {
        $run = AiSearchRun::findOrFail($this->runId);

        $selected = array_values(array_filter((array) ($run->sources ?? []), fn ($key) => is_string($key) && $key !== ''));

        $run->update([
            'status' => 'running',
            'started_at' => now(),
            'message' => 'Ingest dimulai untuk sumber: ' . (implode(', ', $selected) ?: '(kosong)') . '.',
        ]);

        $messages = [];
        $found = 0;
        $new = 0;
        $dupes = 0;
        $failed = 0;
        $importPathSelesai = false;

        foreach ($selected as $key) {
            $connector = $manager->get($key);

            if ($connector === null) {
                $failed++;
                $messages[] = "Sumber {$key}: tidak dikenal, dilewati.";
                Log::warning('Maply pipeline: sumber tidak dikenal', ['run_id' => $run->id, 'source' => $key]);
                continue;
            }

            if (!$connector->isConfigured()) {
                $messages[] = "{$connector->label()}: dilewati — {$connector->unconfiguredReason()}";
                continue;
            }

            try {
                $results = $connector->search($run);
                $found += count($results);

                [$added, $dup, $lewatiBatas] = $this->persist($run, $results, $duplicates, $validation);
                $new += $added;
                $dupes += $dup;

                $messages[] = "{$connector->label()}: " . count($results) . " hasil, {$added} kandidat baru, {$dup} duplikat dilewati"
                    . ($lewatiBatas > 0 ? ", {$lewatiBatas} dilewati karena batas maksimal hasil" : '') . '.';

                if ($key === 'json_file') {
                    $importPathSelesai = true;
                }
            } catch (Throwable $e) {
                $failed++;
                $messages[] = "{$connector->label()}: GAGAL — " . Str::limit($e->getMessage(), 160);
                Log::error('Maply pipeline: sumber gagal diproses', [
                    'run_id' => $run->id,
                    'source' => $key,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Bersihkan berkas impor sementara setelah dibaca.
        if ($importPathSelesai) {
            $importPath = (string) data_get($run->options ?? [], 'import_path', '');

            if ($importPath !== '') {
                Storage::disk('local')->delete($importPath);
            }
        }

        // Semua sumber gagal total: tandai run failed dan hentikan chain (Tahap 2-3 tidak jalan).
        if ($selected !== [] && $failed === count($selected) && $new === 0) {
            $run->update([
                'status' => 'failed',
                'found_count' => $found,
                'new_count' => 0,
                'duplicate_count' => $dupes,
                'message' => implode("\n", $messages),
                'finished_at' => now(),
            ]);

            throw new RuntimeException('Semua sumber data gagal diproses: ' . Str::limit(implode(' | ', $messages), 280));
        }

        $messages[] = "Ingest selesai: {$new} kandidat baru. Melanjutkan ke pemrosesan AI (Tahap 2).";

        $run->update([
            'found_count' => $found,
            'new_count' => $new,
            'duplicate_count' => $dupes,
            'message' => implode("\n", $messages),
        ]);

        Log::info('Maply pipeline: ingest selesai', [
            'run_id' => $run->id,
            'found' => $found,
            'new' => $new,
            'duplicates' => $dupes,
            'failed_sources' => $failed,
        ]);
    }

    /**
     * Simpan daftar RawCandidate sebagai kandidat + bukti sumber per sumber.
     * Jumlah kandidat baru per run dibatasi kriteria max_results (poin 10b).
     *
     * @param  RawCandidate[]  $results
     * @return array{0: int, 1: int, 2: int} [disimpan, duplikat dilewati, dilewati karena batas hasil]
     */
    protected function persist(
        AiSearchRun $run,
        array $results,
        DuplicateDetector $duplicates,
        ValidationService $validation,
    ): array {
        $added = 0;
        $dupes = 0;
        $lewatiBatas = 0;
        $maksBaru = max(1, (int) data_get($run->options ?? [], 'kriteria.max_results', config('ai_pipeline.search.max_results', 50)));

        foreach ($results as $raw) {
            if ($added >= $maksBaru) {
                $lewatiBatas++;
                continue;
            }

            if (!$raw instanceof RawCandidate) {
                continue;
            }

            if ($raw->providerId === null || $raw->providerId === '') {
                Log::warning('Maply pipeline: kandidat tanpa provider_id dilewati', [
                    'run_id' => $run->id,
                    'nama' => $raw->nama,
                ]);
                continue;
            }

            // Duplikat provider_id: sama dengan perilaku lama (hindari pelanggaran unique).
            if ($duplicates->providerExists($raw->providerId)) {
                $dupes++;
                continue;
            }

            $kota = $raw->kota ?: (config('ai_pipeline.regions.' . ($run->region === 'indralaya' ? 'indralaya' : 'palembang') . '.label'));

            $candidate = AiKosCandidate::create([
                'search_run_id' => $run->id,
                'provider_id' => $raw->providerId,
                'nama_kos' => $raw->nama,
                'alamat' => $raw->alamat,
                'kecamatan' => $raw->kecamatan,
                'kota' => $kota,
                'telepon' => $raw->telepon,
                'website' => $raw->website,
                'foto_url' => $raw->fotoUrl,
                'latitude' => $raw->lat,
                'longitude' => $raw->lng,
                'google_maps_url' => $raw->googleMapsUrl,
                'fasilitas' => $raw->fasilitas,
                // Tipe & harga tidak dikarang; bila sumber tidak menyebut -> default/NULL.
                'tipe' => $raw->tipe ?: 'Belum diketahui',
                'harga' => $raw->harga,
                // Skor awal = kelengkapan data saja; skor final dihitung ulang di Tahap 3.
                'trust_score' => ValidationService::skorKelengkapan(
                    $raw->nama,
                    $raw->alamat,
                    $raw->lat,
                    $raw->lng,
                    $raw->telepon,
                    $raw->harga,
                    $raw->tipe,
                ),
                'verification_status' => 'needs_review',
                'verification_reasons' => [
                    'Kandidat baru dari ' . ($raw->sourceName ?: $raw->providerId) . '. Menunggu pemrosesan AI (Tahap 2) dan validasi otomatis (Tahap 3).',
                ],
                'raw_data' => $raw->rawData,
                'source_name' => $raw->sourceName,
                'source_url' => $raw->sourceUrl,
                'found_at' => now(),
            ]);

            // Bukti sumber per kandidat (wajib untuk review admin dan penilaian AI).
            $candidate->sources()->create([
                'source_name' => $raw->sourceName ?: $raw->providerId,
                'source_url' => $raw->sourceUrl,
                'evidence' => $raw->bukti(),
                'collected_at' => now(),
            ]);

            $added++;
        }

        return [$added, $dupes, $lewatiBatas];
    }

    /**
     * Hook kegagalan queue: catat ke run agar admin melihat status jujur.
     */
    public function failed(Throwable $e): void
    {
        $run = AiSearchRun::find($this->runId);

        if ($run && !in_array($run->status, ['completed', 'failed'], true)) {
            $run->update([
                'status' => 'failed',
                'finished_at' => now(),
                'message' => Str::limit('Pipeline ingest gagal: ' . $e->getMessage(), 500),
            ]);
        }

        Log::error('Maply pipeline: job ingest gagal', [
            'run_id' => $this->runId,
            'error' => $e->getMessage(),
        ]);
    }
}

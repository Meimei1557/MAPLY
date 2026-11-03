<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\AiSearchRun;
use App\Jobs\IngestKosCandidatesJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('maply:ai-search-scheduled', function () {
    // Sumber pencarian berkala: OSM selalu; Google Places hanya bila API key diatur.
    $sources = (array) config('ai_pipeline.scheduled_sources', ['osm']);

    if (app(\App\Support\AiData\GooglePlacesConnector::class)->isConfigured()) {
        $sources[] = 'google_places';
    }

    if (AiSearchRun::whereIn('status', ['queued', 'running'])->exists()) {
        $this->info('Pencarian Maply lain masih berjalan; jadwal dilewati.');
        return self::SUCCESS;
    }

    foreach (['palembang', 'indralaya'] as $region) {
        $run = AiSearchRun::create([
            'region' => $region,
            'keyword' => 'kost',
            'status' => 'queued',
            'message' => 'Pencarian berkala otomatis.',
            'sources' => $sources,
            'options' => [],
        ]);
        // Pipeline lengkap: ingest (Tahap 1) -> pemrosesan AI (Tahap 2) -> validasi (Tahap 3).
        IngestKosCandidatesJob::withChain([
            new App\Jobs\ProcessCandidateWithAiJob($run->id),
            new App\Jobs\ValidateCandidatesJob($run->id),
        ])->dispatch($run->id);
    }

    $this->info('Pencarian berkala Palembang dan Indralaya telah dijadwalkan (' . implode(', ', $sources) . ').');
    return self::SUCCESS;
})->purpose('Menjadwalkan pengumpulan kandidat kost Maply dari sumber data aktif');

Schedule::command('maply:ai-search-scheduled')->dailyAt('07:00')->withoutOverlapping();

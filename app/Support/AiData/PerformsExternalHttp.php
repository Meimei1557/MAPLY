<?php

namespace App\Support\AiData;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Trait bersama untuk connector yang menghubungi layanan eksternal.
 * Memberi backoff eksternal seragam: retry 3x exponential 2/4/8 detik.
 */
trait PerformsExternalHttp
{
    protected function http(): PendingRequest
    {
        return Http::retry(
            (array) config('ai_pipeline.http_retry', [2000, 4000, 8000]),
            throw: false,
        );
    }
}

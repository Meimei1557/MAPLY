<?php

namespace App\Support\AiData;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Klien Gemini khusus pipeline data (Tahap 2 & 3).
 *
 * Integrasi TIDAK mengganti chatbot lama: tetap memakai config('services.gemini.key'),
 * model yang sama, dan endpoint REST generativelanguage.googleapis.com.
 *
 * Guardrail: timeout HTTP 25 detik, backoff eksternal 2/4/8 detik, respons diminta
 * sebagai JSON (responseMimeType), dan kegagalan selalu dicatat ke Log + dikembalikan
 * sebagai array status (tidak pernah melempar exception ke pemanggil).
 */
class GeminiClient
{
    public function __construct(
        protected ?string $key = null,
        protected ?string $model = null,
    ) {
        $this->key ??= trim((string) config('services.gemini.key'));
        $this->model ??= (string) config('services.gemini.model', 'gemini-2.5-flash');
    }

    /**
     * True bila GEMINI_API_KEY sudah diatur.
     */
    public static function configured(): bool
    {
        return trim((string) config('services.gemini.key')) !== '';
    }

    /**
     * Kirim satu permintaan dan wajibkan balasan JSON.
     *
     * @return array{ok: bool, data: ?array, error: ?string, raw: ?string}
     */
    public function generateJson(string $systemInstruction, string $prompt): array
    {
        if (!$this->isKeyPresent()) {
            return ['ok' => false, 'data' => null, 'error' => 'GEMINI_API_KEY belum diatur pada file .env.', 'raw' => null];
        }

        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/'
            . rawurlencode($this->model)
            . ':generateContent';

        try {
            $response = Http::connectTimeout(5)
                ->timeout((int) config('ai_pipeline.gemini.timeout', 25))
                ->retry((array) config('ai_pipeline.http_retry', [2000, 4000, 8000]), throw: false)
                ->withHeaders([
                    'x-goog-api-key' => $this->key,
                ])
                ->post($endpoint, [
                    'systemInstruction' => [
                        'parts' => [['text' => $systemInstruction]],
                    ],
                    'contents' => [
                        ['role' => 'user', 'parts' => [['text' => $prompt]]],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.1,
                        'maxOutputTokens' => (int) config('ai_pipeline.gemini.max_output_tokens', 4096),
                        'responseMimeType' => 'application/json',
                    ],
                ]);
        } catch (Throwable $e) {
            Log::warning('Maply pipeline: koneksi ke Gemini gagal', [
                'error' => $e->getMessage(),
            ]);

            return [
                'ok' => false,
                'data' => null,
                'error' => 'Koneksi ke Gemini gagal: ' . $e->getMessage(),
                'raw' => null,
            ];
        }

        if (!$response->successful()) {
            Log::warning('Maply pipeline: permintaan Gemini gagal', [
                'status' => $response->status(),
                'body' => mb_substr($response->body(), 0, 500),
            ]);

            return [
                'ok' => false,
                'data' => null,
                'error' => 'Gemini menolak permintaan (HTTP ' . $response->status() . ').',
                'raw' => null,
            ];
        }

        $text = trim((string) data_get($response->json(), 'candidates.0.content.parts.0.text', ''));

        $data = $this->decodeJson($text);

        if ($data === null) {
            Log::warning('Maply pipeline: respons Gemini bukan JSON valid', [
                'potongan' => mb_substr($text, 0, 500),
            ]);

            return [
                'ok' => false,
                'data' => null,
                'error' => 'Respons Gemini bukan JSON yang valid.',
                'raw' => mb_substr($text, 0, 500),
            ];
        }

        return ['ok' => true, 'data' => $data, 'error' => null, 'raw' => null];
    }

    private function isKeyPresent(): bool
    {
        return $this->key !== '';
    }

    /**
     * Dekode teks menjadi JSON; toleran terhadap pembungkus ```json ... ```.
     */
    private function decodeJson(string $text): ?array
    {
        if ($text === '') {
            return null;
        }

        $clean = trim($text);
        $clean = preg_replace('/^```(?:json)?\s*/i', '', $clean) ?? $clean;
        $clean = preg_replace('/\s*```$/i', '', $clean) ?? $clean;

        $decoded = json_decode($clean, true);

        return is_array($decoded) ? $decoded : null;
    }
}

<?php

namespace App\Support\AiData;

use App\Models\AiSearchRun;
use RuntimeException;

/**
 * Connector URL publik tunggal (Tahap 1).
 *
 * Tanpa scraping agresif: 1 URL per request, timeout 10 detik, hanya mengambil
 * meta standar (title, description, og:*) sebagai kandidat MENTAH untuk review admin.
 */
class UrlConnector implements SourceConnector
{
    use PerformsExternalHttp;

    public function key(): string
    {
        return 'url';
    }

    public function label(): string
    {
        return 'URL Halaman Web';
    }

    public function isConfigured(): bool
    {
        return true; // URL diisi per run pada form pencarian admin.
    }

    public function unconfiguredReason(): string
    {
        return '';
    }

    public function search(AiSearchRun $run): array
    {
        $url = trim((string) data_get($run->options ?? [], 'url', ''));

        if ($url === '') {
            throw new RuntimeException('URL belum diisi pada form pencarian.');
        }

        if (!preg_match('#^https?://#i', $url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            throw new RuntimeException('URL tidak valid. Gunakan format http:// atau https://.');
        }

        $host = (string) parse_url($url, PHP_URL_HOST);

        if ($host === '' || $this->hostDilarang($host)) {
            throw new RuntimeException('URL tidak diizinkan: host lokal, privat, atau tidak dikenal.');
        }

        $regionKey = $run->region === 'indralaya' ? 'indralaya' : 'palembang';
        $label = config("ai_pipeline.regions.{$regionKey}.label");

        try {
            $response = $this->http()
                ->connectTimeout(5)
                ->timeout((int) config('ai_pipeline.url.timeout', 10))
                ->withHeaders([
                    'User-Agent' => (string) config('ai_pipeline.url.user_agent', 'MaplyKostManager/1.0'),
                    'Accept' => 'text/html,application/xhtml+xml',
                ])
                ->get($url);
        } catch (\Throwable $e) {
            throw new RuntimeException('Halaman tidak dapat diambil: ' . $e->getMessage());
        }

        if (!$response->successful()) {
            throw new RuntimeException('Halaman tidak dapat diambil (HTTP ' . $response->status() . ').');
        }

        $html = (string) $response->body();

        $title = $this->ekstrakTitle($html);
        $description = $this->ekstrakMeta($html, 'description');
        $og = [
            'title' => $this->ekstrakMeta($html, 'og:title'),
            'description' => $this->ekstrakMeta($html, 'og:description'),
            'image' => $this->ekstrakMeta($html, 'og:image'),
        ];

        // Nama kandidat: og:title > title > host (jaga jujur; bukan nama kost pasti — admin memutuskan).
        $nama = $og['title'] ?: $title ?: $host;

        $candidates = [];

        $candidates[] = new RawCandidate(
            nama: mb_substr($nama, 0, 255),
            alamat: ($og['description'] ?: $description) !== null
                ? mb_substr((string) ($og['description'] ?: $description), 0, 2000)
                : null,
            kota: $label,
            providerId: 'url:' . sha1($url),
            sourceName: 'Halaman Web',
            sourceUrl: $url,
            fotoUrl: $og['image'] !== null ? mb_substr($og['image'], 0, 1000) : null,
            rawData: [
                'url' => $url,
                'host' => $host,
                'http_status' => $response->status(),
                'title' => $title,
                'description' => $description,
                'og' => $og,
            ],
        );

        return $candidates;
    }

    /**
     * Cegah pengambilan host lokal/privat (SSRF dasar).
     */
    private function hostDilarang(string $host): bool
    {
        $host = mb_strtolower($host);

        if (in_array($host, ['localhost'], true) || str_ends_with($host, '.localhost')) {
            return true;
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return !filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
        }

        return false;
    }

    private function ekstrakTitle(string $html): ?string
    {
        if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches)) {
            return trim(html_entity_decode(strip_tags($matches[1]), ENT_QUOTES, 'UTF-8')) ?: null;
        }

        return null;
    }

    private function ekstrakMeta(string $html, string $key): ?string
    {
        $patterns = [
            '/<meta[^>]+(?:property|name)=["\']' . preg_quote($key, '/') . '["\'][^>]*content=["\'](.*?)["\']/is',
            '/<meta[^>]+content=["\'](.*?)["\'][^>]*(?:property|name)=["\']' . preg_quote($key, '/') . '["\']/is',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches)) {
                $value = trim(html_entity_decode(strip_tags($matches[1]), ENT_QUOTES, 'UTF-8'));

                if ($value !== '') {
                    return $value;
                }
            }
        }

        return null;
    }
}

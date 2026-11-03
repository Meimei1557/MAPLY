<?php

namespace App\Support\AiData;

use App\Models\AiSearchRun;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Connector OpenStreetMap (Overpass API).
 *
 * Logika pengambilan dipindahkan apa adanya dari SearchKosPlacesJob lama
 * (query, dua server cadangan, format respons, dan penyusunan alamat tetap sama).
 */
class OsmConnector implements SourceConnector
{
    use PerformsExternalHttp;

    public function key(): string
    {
        return 'osm';
    }

    public function label(): string
    {
        return 'OpenStreetMap (Overpass)';
    }

    public function isConfigured(): bool
    {
        return true; // Overpass gratis tanpa API key.
    }

    public function unconfiguredReason(): string
    {
        return '';
    }

    public function search(AiSearchRun $run): array
    {
        $regionKey = $run->region === 'indralaya' ? 'indralaya' : 'palembang';
        $region = config("ai_pipeline.regions.{$regionKey}");

        $elements = $this->fetchOsmElements(
            $region['osm_bbox'],
            $run->keyword ?: 'kost',
        );

        $candidates = [];

        foreach ($elements as $element) {
            $tags = $element['tags'] ?? [];

            $name = trim((string) ($tags['name'] ?? ''));

            if ($name === '') {
                continue;
            }

            $lat = data_get($element, 'lat');
            $lng = data_get($element, 'lon');

            if (
                (!is_numeric($lat) || !is_numeric($lng))
                && isset($element['center'])
            ) {
                $lat = data_get($element, 'center.lat');
                $lng = data_get($element, 'center.lon');
            }

            $osmType = $element['type'] ?? 'node';
            $osmId = $element['id'] ?? null;

            if (!$osmId) {
                continue;
            }

            $osmUrl = "https://www.openstreetmap.org/{$osmType}/{$osmId}";

            $candidates[] = new RawCandidate(
                nama: $name,
                alamat: $this->buildAddress($tags, $region['label']) ?: null,
                kota: $region['label'],
                telepon: $tags['contact:phone'] ?? $tags['phone'] ?? null,
                website: $tags['contact:website'] ?? $tags['website'] ?? null,
                lat: is_numeric($lat) ? (float) $lat : null,
                lng: is_numeric($lng) ? (float) $lng : null,
                providerId: "osm:{$osmType}:{$osmId}",
                sourceName: 'OpenStreetMap',
                sourceUrl: $osmUrl,
                googleMapsUrl: $osmUrl,
                // Tipe dan harga TIDAK dikarang: biarkan Tahap 2/3 dan admin yang memutuskan.
                rawData: [
                    'osm_type' => $osmType,
                    'osm_id' => $osmId,
                    'tags' => $tags,
                ],
            );
        }

        return $candidates;
    }

    /**
     * Mengambil data dari beberapa server Overpass (perilaku sama dengan job lama).
     */
    private function fetchOsmElements(string $bbox, string $keyword): array
    {
        $extra = preg_replace('/[^a-zA-Z0-9\s-]/u', '', $keyword);

        $pattern = 'kos|kost|pondok|asrama|penginapan|boarding|rumah singgah';

        if (
            trim((string) $extra) !== ''
            && !in_array(mb_strtolower(trim($extra)), ['kos', 'kost'], true)
        ) {
            $safeExtra = preg_quote(trim($extra), '/');
            $pattern = $safeExtra . '|' . $pattern;
        }

        $query = '[out:json][timeout:25];'
            . '(nwr["name"~"' . $pattern . '",i](' . $bbox . ');'
            . ');out center tags 80;';

        $endpoints = [
            'https://overpass-api.de/api/interpreter',
            'https://overpass.kumi.systems/api/interpreter',
        ];

        $errors = [];

        foreach ($endpoints as $endpoint) {
            try {
                $response = Http::connectTimeout(10)
                    ->timeout(35)
                    ->asForm()
                    ->withHeaders([
                        'User-Agent' => 'MaplyKostManager/1.0',
                    ])
                    ->post($endpoint, [
                        'data' => $query,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (
                        isset($data['elements'])
                        && is_array($data['elements'])
                    ) {
                        Log::info('Maply Overpass search successful', [
                            'endpoint' => $endpoint,
                            'result_count' => count($data['elements']),
                        ]);

                        return $data['elements'];
                    }

                    $errors[] = $endpoint . ': Format respons tidak valid.';
                } else {
                    $errors[] = $endpoint . ': HTTP ' . $response->status();
                }
            } catch (\Throwable $e) {
                $errors[] = $endpoint . ': ' . $e->getMessage();
            }
        }

        Log::error('Semua server Overpass gagal dihubungi', [
            'errors' => $errors,
        ]);

        throw new RuntimeException(
            'Semua server OpenStreetMap sedang tidak merespons. Silakan coba kembali beberapa saat lagi.'
        );
    }

    /**
     * Menyusun alamat dari tag OpenStreetMap (perilaku sama dengan job lama).
     */
    private function buildAddress(array $tags, string $region): string
    {
        if (!empty($tags['addr:full'])) {
            return trim($tags['addr:full']);
        }

        $parts = [];

        foreach ([
            'addr:housenumber',
            'addr:street',
            'addr:suburb',
            'addr:city',
            'addr:district',
        ] as $key) {
            if (!empty($tags[$key])) {
                $parts[] = $tags[$key];
            }
        }

        $parts[] = $region;
        $parts[] = 'Sumatera Selatan';

        return implode(', ', array_unique($parts));
    }
}

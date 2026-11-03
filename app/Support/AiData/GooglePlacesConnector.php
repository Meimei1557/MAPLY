<?php

namespace App\Support\AiData;

use App\Models\AiSearchRun;
use RuntimeException;

/**
 * Connector Google Places API (New) — places:searchText.
 *
 * Field mask minimal sesuai spesifikasi: displayName, formattedAddress, location,
 * nationalPhoneNumber, websiteUri, primaryType (+ id untuk provider_id).
 * Jika GOOGLE_PLACES_API_KEY kosong -> driver dilewati dengan pesan jelas.
 */
class GooglePlacesConnector implements SourceConnector
{
    use PerformsExternalHttp;

    public function key(): string
    {
        return 'google_places';
    }

    public function label(): string
    {
        return 'Google Places';
    }

    public function isConfigured(): bool
    {
        return trim((string) config('ai_pipeline.google_places.key')) !== '';
    }

    public function unconfiguredReason(): string
    {
        return 'GOOGLE_PLACES_API_KEY belum diatur pada file .env.';
    }

    public function search(AiSearchRun $run): array
    {
        $apiKey = trim((string) config('ai_pipeline.google_places.key'));

        if ($apiKey === '') {
            throw new RuntimeException(self::UNCONFIGURED_MESSAGE);
        }

        $regionKey = $run->region === 'indralaya' ? 'indralaya' : 'palembang';
        $region = config("ai_pipeline.regions.{$regionKey}");

        $textQuery = trim(($run->keyword ?: 'kost') . ' ' . $region['label']);

        $fieldMask = implode(',', [
            'places.id',
            'places.displayName',
            'places.formattedAddress',
            'places.location',
            'places.nationalPhoneNumber',
            'places.websiteUri',
            'places.primaryType',
        ]);

        $response = $this->http()
            ->connectTimeout(10)
            ->timeout(30)
            ->withHeaders([
                'X-Goog-Api-Key' => $apiKey,
                'X-Goog-FieldMask' => $fieldMask,
            ])
            ->post(config('ai_pipeline.google_places.endpoint'), [
                'textQuery' => $textQuery,
                'languageCode' => 'id',
                'regionCode' => 'ID',
                'pageSize' => (int) config('ai_pipeline.google_places.page_size', 20),
            ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                'Google Places menolak permintaan (HTTP ' . $response->status() . '). ' .
                'Periksa GOOGLE_PLACES_API_KEY, aktivasi Places API (New), dan kuota.'
            );
        }

        $places = $response->json('places', []);

        if (!is_array($places)) {
            throw new RuntimeException('Format respons Google Places tidak valid.');
        }

        $candidates = [];

        foreach ($places as $place) {
            $placeId = (string) ($place['id'] ?? '');

            $nama = trim((string) data_get($place, 'displayName.text', ''));

            if ($placeId === '' || $nama === '') {
                continue;
            }

            $lat = data_get($place, 'location.latitude');
            $lng = data_get($place, 'location.longitude');

            $mapsUrl = 'https://www.google.com/maps/place/?q=place_id:' . $placeId;

            $candidates[] = new RawCandidate(
                nama: $nama,
                alamat: data_get($place, 'formattedAddress') ?: null,
                kota: $region['label'],
                telepon: data_get($place, 'nationalPhoneNumber') ?: null,
                website: data_get($place, 'websiteUri') ?: null,
                lat: is_numeric($lat) ? (float) $lat : null,
                lng: is_numeric($lng) ? (float) $lng : null,
                providerId: 'gplaces:' . $placeId,
                sourceName: 'Google Places',
                sourceUrl: $mapsUrl,
                googleMapsUrl: $mapsUrl,
                rawData: [
                    'place_id' => $placeId,
                    'primary_type' => data_get($place, 'primaryType') ?: null,
                    'response' => $place,
                ],
            );
        }

        return $candidates;
    }

    public const UNCONFIGURED_MESSAGE = 'GOOGLE_PLACES_API_KEY belum diatur pada file .env.';
}

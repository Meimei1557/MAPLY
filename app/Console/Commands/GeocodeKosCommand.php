<?php

namespace App\Console\Commands;

use App\Models\Kos;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Throwable;

class GeocodeKosCommand extends Command
{
    protected $signature = 'maply:geocode-kos
        {--limit=20 : Maksimal kost yang diproses}
        {--retry : Coba kembali data yang gagal}
        {--delay=1200 : Jeda antarpermintaan dalam milidetik}';

    protected $description = 'Mencari kandidat koordinat kost berdasarkan alamat, jalan, dan kawasan.';

    public function handle(): int
    {
        $columns = [
            'latitude',
            'longitude',
            'koordinat_status',
            'geocoding_status',
            'geocoding_source',
            'geocoding_score',
            'geocoded_at',
            'geocoding_query',
        ];

        foreach ($columns as $column) {
            if (!Schema::hasColumn('kos', $column)) {
                $this->error(
                    "Kolom {$column} belum tersedia. Periksa migration."
                );

                return self::FAILURE;
            }
        }

        $limit = max(1, min(100, (int) $this->option('limit')));
        $delay = max(1100, (int) $this->option('delay'));

        $query = Kos::query()->where(function ($q) {
            $q->whereNull('latitude')
                ->orWhereNull('longitude')
                ->orWhereRaw('latitude NOT BETWEEN -90 AND 90')
                ->orWhereRaw('longitude NOT BETWEEN -180 AND 180');
        });

        if (!$this->option('retry')) {
            $query->where(function ($q) {
                $q->whereNull('geocoding_status')
                    ->orWhereNotIn(
                        'geocoding_status',
                        ['not_found', 'error']
                    );
            });
        }

        $items = $query->orderBy('id')->limit($limit)->get();

        if ($items->isEmpty()) {
            $this->info(
                'Tidak ada kost tanpa pasangan koordinat valid yang perlu diproses.'
            );

            return self::SUCCESS;
        }

        $ua = (string) config('app.name', 'Maply')
            . '/1.0 (geocoding for student project)';

        $candidateCount = 0;
        $reviewCount = 0;
        $failedCount = 0;
        $skippedCount = 0;

        foreach ($items as $kos) {
            $kos->refresh();

            // Jangan menimpa koordinat yang sudah ada dan valid.
            if ($this->validPair($kos->latitude, $kos->longitude)) {
                $skippedCount++;
                continue;
            }

            $city = (string) ($kos->kota ?? '');

            $address = $this->join([
                $kos->alamat ?? '',
                $kos->kecamatan ?? '',
                $city,
                $kos->provinsi ?: 'Sumatera Selatan',
                'Indonesia',
            ]);

            $queries = $this->makeQueries($kos, $city);

            if (empty($queries)) {
                $kos->forceFill([
                    'geocoding_status' => 'not_found',
                    'geocoding_source' => null,
                    'geocoding_score' => null,
                    'geocoding_query' => $address,
                ])->save();

                $failedCount++;

                $this->warn(
                    "#{$kos->id} {$kos->nama_kos}: alamat kosong atau tidak cukup untuk dicari."
                );

                continue;
            }

            $kos->forceFill([
                'geocoding_status' => 'processing',
                'geocoding_query' => $address,
            ])->save();

            $match = null;
            $source = null;
            $matchedQuery = null;
            $lastError = null;

            $nominatimCount = 0;
            $photonCount = 0;

            /*
             * Tahap pertama: Nominatim.
             */
            foreach ($queries as $q) {
                try {
                    $response = Http::withHeaders([
                        'User-Agent' => $ua,
                        'Accept-Language' => 'id,en',
                    ])->timeout(18)->get(
                        'https://nominatim.openstreetmap.org/search',
                        [
                            'q' => $q,
                            'format' => 'jsonv2',
                            'addressdetails' => 1,
                            'limit' => 8,
                            'countrycodes' => 'id',
                        ]
                    );

                    if ($response->successful()) {
                        $results = $response->json() ?: [];
                        $nominatimCount += count($results);

                        $match = $this->chooseRelevant(
                            $results,
                            $kos,
                            'nominatim'
                        );

                        if ($match) {
                            $source = 'Nominatim OpenStreetMap';
                            $matchedQuery = $q;
                            break;
                        }
                    } else {
                        $lastError = 'Nominatim HTTP '
                            . $response->status();

                        if (
                            $response->status() === 429 ||
                            $response->status() >= 500
                        ) {
                            break;
                        }
                    }
                } catch (Throwable $e) {
                    $lastError = 'Nominatim: ' . $e->getMessage();
                }

                usleep($delay * 1000);
            }

            /*
             * Tahap kedua: Photon, jika Nominatim belum menemukan kandidat.
             */
            $serviceUnavailable = $lastError &&
                (
                    str_contains($lastError, 'HTTP 429') ||
                    str_contains($lastError, 'HTTP 5')
                );

            if (!$match && !$serviceUnavailable) {
                foreach ($queries as $q) {
                    try {
                        $response = Http::withHeaders([
                            'User-Agent' => $ua,
                            'Accept-Language' => 'id,en',
                        ])->timeout(18)->get(
                            'https://photon.komoot.io/api/',
                            [
                                'q' => $q,
                                'limit' => 8,
                                'lang' => 'en',
                            ]
                        );

                        if ($response->successful()) {
                            $features = $response->json('features') ?? [];
                            $photonCount += count($features);

                            $match = $this->chooseRelevant(
                                $features,
                                $kos,
                                'photon'
                            );

                            if ($match) {
                                $source = 'Photon / OpenStreetMap';
                                $matchedQuery = $q;
                                break;
                            }
                        } else {
                            $lastError = 'Photon HTTP '
                                . $response->status();
                        }
                    } catch (Throwable $e) {
                        $lastError = 'Photon: ' . $e->getMessage();
                    }

                    usleep($delay * 1000);
                }
            }

            /*
             * Tidak ditemukan kandidat yang cukup cocok.
             */
            if (!$match) {
                $kos->forceFill([
                    'geocoding_status' => $lastError
                        ? 'error'
                        : 'not_found',
                    'geocoding_source' => 'Nominatim + Photon',
                    'geocoding_score' => null,
                    'geocoding_query' => $address,
                ])->save();

                $failedCount++;

                $this->warn(
                    "#{$kos->id} {$kos->nama_kos}: belum ditemukan " .
                    "kandidat yang cukup cocok " .
                    "(Nominatim: {$nominatimCount}; Photon: {$photonCount})" .
                    ($lastError ? " ({$lastError})" : '')
                );

                continue;
            }

            $lat = (float) $match['lat'];
            $lng = (float) $match['lon'];
            $score = (int) $match['score'];

            /*
             * Periksa ulang sebelum menyimpan.
             */
            $kos->refresh();

            if ($this->validPair($kos->latitude, $kos->longitude)) {
                $skippedCount++;
                continue;
            }

            /*
             * Tolak kandidat apabila koordinat yang sama sudah
             * dipakai kost lain. Ini tindakan konservatif, bukan
             * bukti bahwa kedua kost pasti berbeda lokasi.
             */
            if ($this->coordinateAlreadyUsed($lat, $lng, $kos->id)) {
                $kos->forceFill([
                    'geocoding_status' => 'not_found',
                    'geocoding_source' => $source,
                    'geocoding_score' => null,
                    'geocoding_query' => $matchedQuery,
                ])->save();

                $failedCount++;

                $this->warn(
                    "#{$kos->id} {$kos->nama_kos}: kandidat ditolak " .
                    'karena koordinat sama dengan kost lain. Perlu diperiksa.'
                );

                continue;
            }

            /*
             * Skor bukan persentase akurasi.
             * Kandidat dengan skor tinggi tetap harus diperiksa.
             */
            $status = $score >= 70
                ? 'candidate'
                : 'needs_review';

            $kos->forceFill([
                'latitude' => $lat,
                'longitude' => $lng,
                'koordinat_status' => 'perkiraan',
                'geocoding_status' => $status,
                'geocoding_source' => $source,
                'geocoding_score' => $score,
                'geocoded_at' => now(),
                'geocoding_query' => $matchedQuery,
            ])->save();

            if ($status === 'candidate') {
                $candidateCount++;
            } else {
                $reviewCount++;
            }

            $kind = $match['kind'] === 'area'
                ? 'titik kawasan'
                : 'kecocokan jalan';

            $this->line(
                "#{$kos->id} {$kos->nama_kos}: " .
                "{$lat}, {$lng} " .
                "(skor {$score}; {$status}; {$kind}; {$source})"
            );

            usleep($delay * 1000);
        }

        $this->newLine();

        $this->info(
            "Selesai. Kandidat: {$candidateCount}; " .
            "perlu verifikasi: {$reviewCount}; " .
            "gagal/tidak ditemukan: {$failedCount}; " .
            "dilewati: {$skippedCount}."
        );

        $this->comment(
            'Koordinat menunjukkan perkiraan jalan/kawasan, ' .
            'bukan kepastian lokasi pintu bangunan.'
        );

        return self::SUCCESS;
    }

    /**
     * Membuat kueri dengan prioritas alamat lengkap.
     */
    private function makeQueries(Kos $kos, string $city): array
    {
        $address = trim((string) ($kos->alamat ?? ''));
        $name = trim((string) ($kos->nama_kos ?? ''));
        $district = trim((string) ($kos->kecamatan ?? ''));
        $province = trim(
            (string) ($kos->provinsi ?: 'Sumatera Selatan')
        );

        $clean = preg_replace(
            '/\b(Jl\.?|Jln\.?|Jalan)\b/i',
            'Jalan',
            $address
        );

        $clean = preg_replace(
            '/\b(Lr\.?|Lrg\.?|Lorong)\b/i',
            'Lorong',
            $clean
        );

        $clean = preg_replace(
            '/\b(Gg\.?|Gang)\b/i',
            'Gang',
            $clean
        );

        $clean = preg_replace('/\s+/', ' ', $clean);

        $queries = [];

        // Alamat lengkap menjadi pencarian pertama.
        if ($clean !== '') {
            $queries[] = $this->join([
                $clean,
                $district,
                $city,
                $province,
                'Indonesia',
            ]);
        }

        // Prioritaskan nama lorong/gang.
        if (preg_match_all(
            '/\b(?:lorong|gang)\s+([^,()]+)/iu',
            $clean,
            $matches
        )) {
            foreach ($matches[1] as $part) {
                $part = trim($part);

                if ($part === '') {
                    continue;
                }

                $queries[] = $this->join([
                    'Lorong ' . $part,
                    $district,
                    $city,
                    $province,
                    'Indonesia',
                ]);

                $queries[] = $this->join([
                    $part,
                    $city,
                    $province,
                    'Indonesia',
                ]);
            }
        }

        // Nama jalan sebagai pencarian alternatif.
        foreach ($this->streetPhrases($clean) as $phrase) {
            $queries[] = $this->join([
                $phrase,
                $district,
                $city,
                $province,
                'Indonesia',
            ]);
        }

        // Nama kost menjadi pencarian tambahan.
        if ($name !== '') {
            $queries[] = $this->join([
                $name,
                $clean,
                $district,
                $city,
                $province,
                'Indonesia',
            ]);
        }

        return array_values(array_unique(array_filter(
            $queries,
            fn ($query) => trim($query) !== ''
        )));
    }

    /**
     * Memilih hasil yang cocok dengan kota, jalan, atau kawasan.
     */
    private function chooseRelevant(
        array $results,
        Kos $kos,
        string $provider
    ): ?array {
        $city = $this->norm((string) ($kos->kota ?? ''));
        $addressText = $this->norm((string) ($kos->alamat ?? ''));
        $streetPhrases = $this->streetPhrases(
            (string) ($kos->alamat ?? '')
        );
        $areaTokens = $this->areaTokens($addressText);

        $best = null;

        foreach ($results as $item) {
            if ($provider === 'photon') {
                $p = $item['properties'] ?? [];
                $coords = $item['geometry']['coordinates'] ?? null;

                if (!is_array($coords) || count($coords) < 2) {
                    continue;
                }

                $lat = $coords[1];
                $lon = $coords[0];

                $a = [
                    'road' => $p['street'] ?? '',
                    'house_number' => $p['housenumber'] ?? '',
                    'suburb' => $p['suburb'] ?? '',
                    'neighbourhood' => $p['neighbourhood'] ?? '',
                    'city' => $p['city'] ?? '',
                    'town' => $p['town'] ?? '',
                    'district' => $p['district'] ?? '',
                    'county' => $p['county'] ?? '',
                    'state' => $p['state'] ?? '',
                    'village' => $p['village'] ?? '',
                    'name' => $p['name'] ?? '',
                    'display_name' => implode(', ', array_filter([
                        $p['name'] ?? '',
                        $p['street'] ?? '',
                        $p['neighbourhood'] ?? '',
                        $p['suburb'] ?? '',
                        $p['district'] ?? '',
                        $p['city'] ?? '',
                        $p['county'] ?? '',
                        $p['state'] ?? '',
                    ])),
                ];
            } else {
                if (!isset($item['lat'], $item['lon'])) {
                    continue;
                }

                $lat = $item['lat'];
                $lon = $item['lon'];

                $a = $item['address'] ?? [];
                $a['display_name'] = $item['display_name'] ?? '';
                $a['name'] = $item['name'] ?? '';
            }

            if (!$this->validPair($lat, $lon)) {
                continue;
            }

            /*
             * Jangan menerima hasil dari kota/kawasan yang salah.
             */
            $place = $this->norm(implode(' ', [
                $a['city'] ?? '',
                $a['town'] ?? '',
                $a['municipality'] ?? '',
                $a['county'] ?? '',
                $a['district'] ?? '',
                $a['state'] ?? '',
                $a['suburb'] ?? '',
                $a['neighbourhood'] ?? '',
                $a['village'] ?? '',
                $a['locality'] ?? '',
                $a['display_name'] ?? '',
                $a['name'] ?? '',
            ]));

            if (str_contains($city, 'palembang')) {
                if (!str_contains($place, 'palembang')) {
                    continue;
                }
            } elseif (str_contains($city, 'indralaya')) {
                if (
                    !str_contains($place, 'indralaya') &&
                    !str_contains($place, 'ogan ilir')
                ) {
                    continue;
                }
            } elseif (
                $city !== '' &&
                !str_contains($place, $city)
            ) {
                continue;
            }

            /*
             * Periksa nomor rumah jika geocoder menyediakannya.
             */
            $expectedNumber = $this->extractHouseNumber(
                (string) ($kos->alamat ?? '')
            );

            $foundNumber = trim(
                (string) ($a['house_number'] ?? '')
            );

            if (
                $expectedNumber !== null &&
                $foundNumber !== '' &&
                strcasecmp($expectedNumber, $foundNumber) !== 0
            ) {
                continue;
            }

            $road = $this->norm(
                (string) ($a['road'] ?? $a['pedestrian'] ?? '')
            );

            $display = $this->norm(
                (string) ($a['display_name'] ?? '')
            );

            $locality = $this->norm(implode(' ', [
                $a['suburb'] ?? '',
                $a['neighbourhood'] ?? '',
                $a['village'] ?? '',
                $a['district'] ?? '',
            ]));

            $score = 0;
            $kind = null;

            /*
             * Jalan harus cocok dengan seluruh token penting.
             * Jangan mengandalkan satu kata umum saja.
             */
            foreach ($streetPhrases as $phrase) {
                $tokens = $this->meaningfulTokens($phrase);

                if (empty($tokens)) {
                    continue;
                }

                $allRoadTokens = true;

                foreach ($tokens as $token) {
                    if (!str_contains($road, $token)) {
                        $allRoadTokens = false;
                        break;
                    }
                }

                if ($allRoadTokens && $road !== '') {
                    $score = 65;
                    $kind = 'street';
                    break;
                }

                $allDisplayTokens = count($tokens) >= 2;

                foreach ($tokens as $token) {
                    if (!str_contains($display, $token)) {
                        $allDisplayTokens = false;
                        break;
                    }
                }

                if ($allDisplayTokens) {
                    $score = max($score, 50);
                    $kind = 'street';
                }
            }

            /*
             * Kecocokan kawasan saja memiliki skor rendah.
             */
            if ($score === 0) {
                foreach ($areaTokens as $token) {
                    $key = $this->norm($token);

                    if (
                        $key !== '' &&
                        (
                            str_contains($locality, $key) ||
                            str_contains(
                                $this->norm((string) ($a['name'] ?? '')),
                                $key
                            )
                        )
                    ) {
                        $score = 38;
                        $kind = 'area';
                        break;
                    }
                }
            }

            // Jangan menerima hasil yang hanya cocok dengan kota.
            if ($score === 0) {
                continue;
            }

            if (
                $kind === 'street' &&
                $locality !== '' &&
                $this->areaMatches($addressText, $locality)
            ) {
                $score += 12;
            }

            if ($kind === 'area') {
                $score = min($score, 45);
            }

            $score = min(90, $score);

            $candidate = [
                'lat' => (float) $lat,
                'lon' => (float) $lon,
                'score' => $score,
                'kind' => $kind,
            ];

            if (
                $best === null ||
                $candidate['score'] > $best['score']
            ) {
                $best = $candidate;
            }
        }

        return $best;
    }

    /**
     * Mengambil nomor rumah dari alamat.
     */
    private function extractHouseNumber(string $address): ?string
    {
        if (preg_match(
            '/\b(?:no|nomor|number)\.?\s*([0-9]+[a-z]?(?:\/[0-9]+[a-z]?)?)/iu',
            $address,
            $matches
        )) {
            return strtoupper($matches[1]);
        }

        return null;
    }

    /**
     * Mengecek apakah koordinat sudah dipakai kost lain.
     */
    private function coordinateAlreadyUsed(
        float $lat,
        float $lon,
        int $kosId
    ): bool {
        return Kos::query()
            ->where('id', '!=', $kosId)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereRaw(
                'ABS(latitude - ?) < 0.00001',
                [$lat]
            )
            ->whereRaw(
                'ABS(longitude - ?) < 0.00001',
                [$lon]
            )
            ->exists();
    }

    private function streetPhrases(string $address): array
    {
        $s = mb_strtolower($address);
        $phrases = [];

        if (preg_match_all('/\(([^)]+)\)/u', $s, $matches)) {
            foreach ($matches[1] as $inside) {
                if (preg_match(
                    '/\b(?:lorong|lrg\.?|lr\.?|gang|gg\.?)\s+(.+)/u',
                    $inside,
                    $m
                )) {
                    $phrases[] = trim($m[1]);
                }
            }
        }

        if (preg_match(
            '/\b(?:jalan|jl\.?|jln\.?)\s+([^,]+)/u',
            $s,
            $m
        )) {
            $main = preg_split(
                '/\s*\(|\b(?:no|nomor|number)\.?\s*|\b(?:rt|rw)\.?\s*/u',
                trim($m[1])
            )[0] ?? $m[1];

            $main = trim($main, " \t\n\r\0\x0B,.-");

            if ($main !== '') {
                $phrases[] = $main;
            }
        }

        if (preg_match_all(
            '/\b(?:lorong|lrg\.?|lr\.?|gang|gg\.?)\s+([^,()]+)/u',
            $s,
            $matches
        )) {
            foreach ($matches[1] as $part) {
                $part = preg_split(
                    '/\b(?:no|nomor|number)\.?\s*|\b(?:rt|rw)\.?\s*|\s+\d{1,5}[a-z]?\b/u',
                    trim($part)
                )[0] ?? $part;

                $part = trim($part, " \t\n\r\0\x0B,.-");

                if ($part !== '') {
                    $phrases[] = $part;
                }
            }
        }

        return array_values(array_unique(array_filter(
            $phrases,
            fn ($x) => count($this->meaningfulTokens($x)) > 0
        )));
    }

    private function meaningfulTokens(string $phrase): array
    {
        $normalized = $this->norm($phrase);
        $tokens = preg_split('/\s+/', $normalized) ?: [];

        $stop = [
            'jalan', 'jl', 'jln', 'lorong', 'lrg', 'lr',
            'gang', 'gg', 'no', 'nomor', 'rt', 'rw', 'depan',
        ];

        return array_values(array_filter(
            $tokens,
            fn ($token) =>
                $token !== '' &&
                !in_array($token, $stop, true) &&
                !preg_match('/^\d+[a-z]?$/', $token)
        ));
    }

    private function areaMatches(
        string $addressText,
        string $geocoderArea
    ): bool {
        foreach ($this->areaTokens($addressText) as $token) {
            if (
                str_contains(
                    $this->norm($geocoderArea),
                    $this->norm($token)
                )
            ) {
                return true;
            }
        }

        return false;
    }

    private function areaTokens(string $address): array
    {
        $known = [
            'indralaya raya',
            'indralaya mulia',
            'indralaya indah',
            'tanjung seteko',
            'komplek bhakti guna',
            'bhakti guna',
            'komperta taman indralaya',
            'taman indralaya',
            'bukit lama',
            'demang lebar daun',
            'macan kumbang',
            'srijaya negara',
            'kancil putih',
            'dwikora ii',
            'zuriah',
            'hasan as',
            'siguntang',
            'lorong tembesu',
            'lintas sumatra',
            'sakatiga',
            'tanjung pering',
            'komplek griya cipta utama',
            'komplek pertamina',
        ];

        $out = [];

        foreach ($known as $token) {
            if (str_contains($address, $token)) {
                $out[] = $token;
            }
        }

        return $out;
    }

    private function join(array $parts): string
    {
        $parts = array_map(
            fn ($x) => trim((string) $x),
            $parts
        );

        return implode(
            ', ',
            array_values(array_filter(
                $parts,
                fn ($x) => $x !== ''
            ))
        );
    }

    private function validPair($lat, $lon): bool
    {
        return is_numeric($lat) &&
            is_numeric($lon) &&
            (float) $lat >= -90 &&
            (float) $lat <= 90 &&
            (float) $lon >= -180 &&
            (float) $lon <= 180;
    }

    private function norm(string $s): string
    {
        $s = mb_strtolower(
            trim(preg_replace('/\s+/', ' ', $s))
        );

        $s = str_replace(['.', '-', '_'], ' ', $s);

        return trim(preg_replace('/\s+/', ' ', $s));
    }
}

<?php

namespace App\Services;

use App\Models\RuteCache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Klien OSRM (Open Source Routing Machine, basis data OpenStreetMap).
 * Menyediakan jarak/waktu perjalanan MELALUI JALAN sesuai moda (bukan garis lurus).
 * Kegagalan layanan tidak pernah menghasilkan estimasi palsu: kembalikan null.
 * Hasil sukses di-cache di tabel rute_cache untuk mengurangi beban layanan publik.
 */
class RuteService
{
    public const MODA = ['foot', 'bike', 'driving'];

    public function __construct(
        protected ?string $baseUrl = null,
        protected ?int $timeout = null,
    ) {}

    /**
     * Hitung rute. Return null bila gagal / tidak ditemukan.
     *
     * @return array{jarak_m: float, detik: float, geometri: array, mode: string, sumber: string}|null
     */
    public function rute(float $latAsal, float $lngAsal, float $latTujuan, float $lngTujuan, string $mode = 'foot'): ?array
    {
        if (!in_array($mode, self::MODA, true)) {
            $mode = 'foot';
        }

        // Cache dibulatkan ke ~1,1 m agar tiap titik unik tapi hit rate tinggi.
        $kAsal = [round($latAsal, 5), round($lngAsal, 5)];
        $kTujuan = [round($latTujuan, 5), round($lngTujuan, 5)];

        if ($cache = RuteCache::valid($kAsal, $kTujuan, $mode, config('maply.rute_cache_hari', 7))) {
            return [
                'jarak_m' => (float) $cache->jarak_m,
                'detik' => (float) $cache->durasi_s,
                'geometri' => $cache->geometri,
                'mode' => $mode,
                'sumber' => 'OSRM (cache '.$cache->created_at?->translatedFormat('j M Y').')',
            ];
        }

        $url = sprintf(
            '%s/route/v1/%s/%F,%F;%F,%F',
            rtrim($this->baseUrl ?: rtrim((string) config('maply.osrm_url'), '/'), '/'),
            $mode,
            $kAsal[1], $kAsal[0],
            $kTujuan[1], $kTujuan[0],
        );

        try {
            $resp = Http::timeout((int) ($this->timeout ?: config('maply.osrm_timeout', 8)))
                ->acceptJson()
                ->get($url, ['overview' => 'full', 'geometries' => 'geojson', 'alternatives' => 'false']);
        } catch (\Throwable $e) {
            Log::warning('OSRM tidak dapat dihubungi: '.$e->getMessage());

            return null;
        }

        if (!$resp->successful()) {
            return null;
        }

        $body = $resp->json();
        // code 'Ok' dan minimal 1 route; selain itu (NoRoute, NoSegment) => null.
        if (($body['code'] ?? null) !== 'Ok' || empty($body['routes'][0])) {
            return null;
        }

        $route = $body['routes'][0];
        $hasil = [
            'jarak_m' => (float) $route['distance'],
            'detik' => (float) $route['duration'],
            'geometri' => $route['geometry']['coordinates'] ?? [],
            'mode' => $mode,
            'sumber' => 'OSRM ('.$mode.')',
        ];

        try {
            RuteCache::simpan($kAsal, $kTujuan, $mode, $hasil);
        } catch (\Throwable $e) {
            // Cache gagal menyimpan tidak boleh menggagalkan respons.
        }

        return $hasil;
    }

    /** Format durasi dalam bahasa Indonesia tanpa pembulatan berlebihan. */
    public static function durasiLabel(float $detik): string
    {
        $menit = (int) round($detik / 60);
        if ($menit < 1) {
            return 'kurang dari 1 menit';
        }
        if ($menit < 60) {
            return $menit.' menit';
        }
        $jam = intdiv($menit, 60);
        $sisa = $menit % 60;

        return $jam.' jam'.($sisa ? ' '.$sisa.' menit' : '');
    }

    /** Jarak garis lurus (haversine) dalam km — BUKAN jarak perjalanan jalan. */
    public static function jarakGarisLurusKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $r = 6371.0088;
        $p1 = deg2rad($lat1);
        $p2 = deg2rad($lat2);
        $dp = $p2 - $p1;
        $dl = deg2rad($lng2 - $lng1);
        $a = sin($dp / 2) ** 2 + cos($p1) * cos($p2) * sin($dl / 2) ** 2;

        return 2 * $r * asin(min(1.0, sqrt($a)));
    }
}

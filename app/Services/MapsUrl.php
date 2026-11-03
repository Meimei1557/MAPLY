<?php

namespace App\Services;

/**
 * Pembuat tautan Google Maps berbasis KOORDINAT database.
 * Sumber kebenaran tunggal: nilai latitude/longitude yang tersimpan.
 * Jangan pernah memakai nama tempat / pencarian teks untuk tombol lokasi.
 */
class MapsUrl
{
    /** Tautan titik lokasi. Format resmi Google: query=LAT,LNG dengan 6 desimal. */
    public static function place(float|string|null $lat, float|string|null $lng): ?string
    {
        if (!is_numeric($lat) || !is_numeric($lng)) {
            return null;
        }

        return sprintf('https://www.google.com/maps/search/?api=1&query=%s,%s', self::f((float) $lat), self::f((float) $lng));
    }

    /** Tautan petunjuk arah dari titik asal ke tujuan. Urutan tidak boleh tertukar. */
    public static function directions(
        float|string|null $originLat,
        float|string|null $originLng,
        float|string|null $destLat,
        float|string|null $destLng,
        string $travelmode = 'driving',
    ): ?string {
        foreach ([$originLat, $originLng, $destLat, $destLng] as $v) {
            if (!is_numeric($v)) {
                return null;
            }
        }

        return sprintf(
            'https://www.google.com/maps/dir/?api=1&origin=%s,%s&destination=%s,%s&travelmode=%s',
            self::f((float) $originLat), self::f((float) $originLng),
            self::f((float) $destLat), self::f((float) $destLng),
            in_array($travelmode, ['driving', 'walking', 'bicycling', 'transit'], true) ? $travelmode : 'driving',
        );
    }

    /** Format koordinat: 6 desimal, titik desimal, tanpa spasi (aman untuk Google Maps). */
    public static function f(float $v): string
    {
        return rtrim(rtrim(number_format($v, 6, '.', ''), '0'), '.');
    }
}

<?php

namespace App\Support\AiData;

use App\Models\AiKosCandidate;
use App\Models\Kos;

/**
 * Deteksi duplikat — diekstrak dari SearchKosPlacesJob lama agar reusable
 * (dipakai saat ingest Tahap 1 dan gerbang deterministik Tahap 3).
 *
 * Aturan tetap sama:
 *   1. provider_id identik (mis. osm:node:123) -> duplikat.
 *   2. nama sama (case-insensitive) DAN koordinat berbeda < ±0.0005 derajat
 *      terhadap kandidat lain ATAU tabel kos -> duplikat.
 */
class DuplicateDetector
{
    /**
     * Presisi koordinat yang dianggap titik yang sama (derajat) — nilai lama dipertahankan.
     */
    public const COORD_TOLERANCE = 0.0005;

    /**
     * Cek provider_id sudah pernah ada pada ai_kos_candidates.
     */
    public function providerExists(?string $providerId): bool
    {
        if ($providerId === null || $providerId === '') {
            return false;
        }

        return AiKosCandidate::query()->where('provider_id', $providerId)->exists();
    }

    /**
     * Cari duplikat berdasarkan nama + koordinat terhadap kandidat lain dan tabel kos.
     *
     * $ignoreId dipakai Tahap 3 agar kandidat tidak membandingkan dirinya sendiri;
     * hanya baris dengan id LEBIH KECIL yang dianggap "asli" sehingga duplikasi
     * timbal-balik tidak saling menolak (baris pertama dipertahankan).
     *
     * @return string|null alasan duplikat (Bahasa Indonesia) atau null bila bukan duplikat.
     */
    public function findNameCoordinateDuplicate(
        ?string $nama,
        int|float|null $lat,
        int|float|null $lng,
        ?int $ignoreId = null,
    ): ?string {
        $nama = trim((string) $nama);

        if ($nama === '') {
            return null;
        }

        $coordsMatch = is_numeric($lat) && is_numeric($lng);

        // 1. Bandingkan dengan kandidat lain.
        $candidateQuery = AiKosCandidate::query()
            ->whereRaw('LOWER(nama_kos) = ?', [mb_strtolower($nama)])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->when(
                $coordsMatch,
                function ($query) use ($lat, $lng) {
                    $query->whereRaw(
                        'ABS(latitude - ?) < ? AND ABS(longitude - ?) < ?',
                        [(float) $lat, self::COORD_TOLERANCE, (float) $lng, self::COORD_TOLERANCE],
                    );
                },
                fn ($query) => $query->whereRaw('1 = 0'),
            )
            ->when(
                $ignoreId !== null,
                fn ($query) => $query->where('id', '<', $ignoreId),
            );

        if ($candidateQuery->exists()) {
            return 'Duplikat: kandidat lain dengan nama dan koordinat yang sama sudah tercatat (±0.0005 derajat).';
        }

        // 2. Bandingkan dengan data kost utama (listing tayang Maply).
        $kosMatch = Kos::query()
            ->whereRaw('LOWER(nama_kos) = ?', [mb_strtolower($nama)])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->when(
                $coordsMatch,
                function ($query) use ($lat, $lng) {
                    $query->whereRaw(
                        'ABS(latitude - ?) < ? AND ABS(longitude - ?) < ?',
                        [(float) $lat, self::COORD_TOLERANCE, (float) $lng, self::COORD_TOLERANCE],
                    );
                },
                fn ($query) => $query->whereRaw('1 = 0'),
            )
            ->exists();

        if ($kosMatch) {
            return 'Duplikat: sudah ada listing kos dengan nama dan koordinat yang sama pada data Maply.';
        }

        return null;
    }
}

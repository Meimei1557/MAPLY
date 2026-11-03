<?php

namespace App\Support\AiData;

/**
 * DTO hasil kandidat mentah dari sumber eksternal (Tahap 1).
 *
 * Prinsip kejujuran data: semua properti boleh NULL bila tidak ditemukan pada
 * sumber — JANGAN pernah mengisi nilai yang tidak ada buktinya.
 */
final class RawCandidate
{
    public function __construct(
        public readonly string $nama,
        public readonly ?string $alamat = null,
        public readonly ?string $kota = null,
        public readonly ?string $telepon = null,
        public readonly ?string $website = null,
        public readonly ?string $fotoUrl = null,
        public readonly ?float $lat = null,
        public readonly ?float $lng = null,
        public readonly ?string $providerId = null,
        public readonly ?string $sourceName = null,
        public readonly ?string $sourceUrl = null,
        public readonly ?string $googleMapsUrl = null,
        public readonly ?string $kecamatan = null,
        public readonly ?int $harga = null,
        public readonly ?string $tipe = null,
        public readonly array $fasilitas = [],
        public readonly array $rawData = [],
    ) {
    }

    /**
     * Representasi ringkas untuk bukti sumber (ai_candidate_sources.evidence).
     */
    public function bukti(): array
    {
        return array_filter([
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'kota' => $this->kota,
            'telepon' => $this->telepon,
            'website' => $this->website,
            'foto_url' => $this->fotoUrl,
            'latitude' => $this->lat,
            'longitude' => $this->lng,
            'provider_id' => $this->providerId,
            'sumber' => $this->sourceName,
            'url_sumber' => $this->sourceUrl,
            'url_peta' => $this->googleMapsUrl,
            'rincian' => $this->rawData ?: null,
        ], fn ($value) => $value !== null && $value !== []);
    }
}

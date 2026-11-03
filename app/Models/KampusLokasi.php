<?php

namespace App\Models;

use App\Services\MapsUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Lokasi fisik kampus yang benar-benar dipakai mahasiswa.
 * latitude/longitude di sini adalah SATU-SATUNYA sumber koordinat untuk
 * marker peta website DAN tautan Google Maps (konsisten keduanya).
 */
class KampusLokasi extends Model
{
    protected $table = 'kampus_lokasi';

    protected $fillable = [
        'kampus_id', 'kode', 'nama_lokasi', 'is_utama', 'aktif',
        'latitude', 'longitude', 'presisi',
        'alamat', 'kecamatan', 'kab_kota', 'provinsi',
        'logo_path', 'logo_sumber', 'logo_url_sumber',
        'sumber_koordinat', 'metode_koordinat', 'verifikasi_koordinat',
        'tanggal_cek', 'catatan',
    ];

    protected $casts = [
        'is_utama' => 'boolean',
        'aktif' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'verifikasi_koordinat' => 'array',
        'tanggal_cek' => 'date',
    ];

    public function kampus(): BelongsTo
    {
        return $this->belongsTo(Kampus::class, 'kampus_id');
    }

    public function getPunyaKoordinatAttribute(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    public function getPunyaLogoAttribute(): bool
    {
        return (bool) $this->logo_path && is_file(public_path($this->logo_path));
    }

    public function getKoordinatLabelAttribute(): string
    {
        return $this->presisi === 'gedung'
            ? 'Titik gedung/kampus (terverifikasi)'
            : 'Titik kawasan kampus (terverifikasi)';
    }

    public function getGoogleMapsUrlAttribute(): ?string
    {
        return MapsUrl::place($this->latitude, $this->longitude);
    }

    public function getUrlAttribute(): string
    {
        return route('kos.index', ['kampus' => $this->kode]);
    }
}

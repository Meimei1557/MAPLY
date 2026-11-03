<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Institusi perguruan tinggi (identitas). Lokasi fisik kuliah ada di KampusLokasi
 * agar satu institusi dengan beberapa kampus tidak mewakili satu koordinat saja.
 */
class Kampus extends Model
{
    protected $table = 'kampus';

    protected $fillable = [
        'kode', 'nama_resmi', 'singkatan', 'status', 'jenis', 'aktif',
        'alamat', 'kecamatan', 'kab_kota', 'provinsi', 'situs_resmi',
        'sumber_data', 'tanggal_cek', 'catatan',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'sumber_data' => 'array',
        'tanggal_cek' => 'date',
    ];

    public function lokasi(): HasMany
    {
        return $this->hasMany(KampusLokasi::class, 'kampus_id');
    }

    public function lokasiAktif(): HasMany
    {
        return $this->lokasi()->where('aktif', true)->orderByDesc('is_utama');
    }

    public function getLabelAttribute(): string
    {
        return $this->singkatan ?: $this->nama_resmi;
    }
}

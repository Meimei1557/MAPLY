<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/** Cache hasil perhitungan rute OSRM agar layanan publik tidak dibebani. */
class RuteCache extends Model
{
    protected $table = 'rute_cache';

    protected $fillable = [
        'mode', 'lat_asal', 'lng_asal', 'lat_tujuan', 'lng_tujuan',
        'jarak_m', 'durasi_s', 'geometri', 'provider',
    ];

    protected $casts = [
        'jarak_m' => 'float',
        'durasi_s' => 'float',
        'geometri' => 'array',
        'lat_asal' => 'float', 'lng_asal' => 'float',
        'lat_tujuan' => 'float', 'lng_tujuan' => 'float',
    ];

    public function scopeValid(Builder $q, array $asal, array $tujuan, string $mode, int $hari): ?self
    {
        return $q->where('mode', $mode)
            ->where('lat_asal', $asal[0])->where('lng_asal', $asal[1])
            ->where('lat_tujuan', $tujuan[0])->where('lng_tujuan', $tujuan[1])
            ->where('created_at', '>=', now()->subDays($hari))
            ->first();
    }

    public static function simpan(array $asal, array $tujuan, string $mode, array $hasil): self
    {
        return static::updateOrCreate(
            [
                'mode' => $mode,
                'lat_asal' => $asal[0], 'lng_asal' => $asal[1],
                'lat_tujuan' => $tujuan[0], 'lng_tujuan' => $tujuan[1],
            ],
            [
                'jarak_m' => $hasil['jarak_m'],
                'durasi_s' => $hasil['detik'],
                'geometri' => $hasil['geometri'],
                'provider' => 'osrm',
            ],
        );
    }
}

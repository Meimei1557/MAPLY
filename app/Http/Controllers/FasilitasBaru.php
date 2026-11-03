<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Support\Str;

/** Helper kecil pembuatan fasilitas (dipakai admin + seeder). */
class FasilitasBaru
{
    public static function buat(int $kosId, string $nama): ?Fasilitas
    {
        $nama = trim(Str::limit($nama, 60));
        if ($nama === '') {
            return null;
        }

        return Fasilitas::create(['kos_id' => $kosId, 'nama_fasilitas' => $nama]);
    }
}

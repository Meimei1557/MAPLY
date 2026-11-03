<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Support\KampusDirectory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'total' => Kos::tersedia()->count(),
            'publik' => Kos::tersedia()->where('status_data', '!=', 'contoh')->count(),
            'kecamatan' => Kos::tersedia()->whereNotNull('kecamatan')->distinct()->count('kecamatan'),
            'di_peta' => Kos::tersedia()->whereNotNull('latitude')->whereNotNull('longitude')->count(),
        ];

        // Data dari sumber publik ditampilkan lebih dulu daripada data contoh.
        $unggulan = Kos::tersedia()
            ->with('fasilitas')
            ->orderByRaw("case when status_data = 'contoh' then 1 else 0 end")
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $mapItems = Kos::tersedia()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(fn (Kos $k) => $k->toMapData())
            ->values();

        // Sumber tunggal daftar kampus: tabel kampus + kampus_lokasi.
        $kampusCards = KampusDirectory::kampusDenganLokasi();
        $titikKota = collect(KampusDirectory::titikKota());

        $refDefault = collect(config('maply.titik_acuan')[config('maply.titik_default')]);

        return view('dashboard', [
            'stats' => $stats,
            'unggulan' => $unggulan,
            'mapItems' => $mapItems,
            'kampusCards' => $kampusCards,
            'titikKota' => $titikKota,
            'kampusPeta' => KampusDirectory::kampusUntukPeta(),
            'refDefault' => $refDefault->only(['lat', 'lng', 'nama', 'singkat', 'terverifikasi'])->all(),
            'facilityOptions' => config('maply.fasilitas'),
        ]);
    }
}

<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "===== JUMLAH DATA DATABASE =====\n";

echo "Institusi aktif: " .
    DB::table('kampus')->where('aktif', true)->count() . "\n";

echo "Institusi nonaktif: " .
    DB::table('kampus')->where('aktif', false)->count() . "\n";

echo "Lokasi aktif: " .
    DB::table('kampus_lokasi')->where('aktif', true)->count() . "\n";

echo "Lokasi nonaktif: " .
    DB::table('kampus_lokasi')->where('aktif', false)->count() . "\n";

echo "\n===== DAFTAR LOKASI AKTIF =====\n";

$lokasi = DB::table('kampus_lokasi')
    ->join('kampus', 'kampus.id', '=', 'kampus_lokasi.kampus_id')
    ->where('kampus_lokasi.aktif', true)
    ->where('kampus.aktif', true)
    ->orderBy('kampus.nama_resmi')
    ->orderByDesc('kampus_lokasi.is_utama')
    ->select(
        'kampus.nama_resmi',
        'kampus_lokasi.kode',
        'kampus_lokasi.nama_lokasi',
        'kampus_lokasi.is_utama',
        'kampus_lokasi.latitude',
        'kampus_lokasi.longitude'
    )
    ->get();

foreach ($lokasi as $item) {
    echo "{$item->nama_resmi} | {$item->kode} | {$item->nama_lokasi} | Utama: {$item->is_utama} | {$item->latitude}, {$item->longitude}\n";
}

echo "\n===== INSTITUSI TANPA LOKASI AKTIF =====\n";

$tanpaLokasi = DB::table('kampus')
    ->where('kampus.aktif', true)
    ->whereNotExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('kampus_lokasi')
            ->whereColumn('kampus_lokasi.kampus_id', 'kampus.id')
            ->where('kampus_lokasi.aktif', true);
    })
    ->select('kode', 'nama_resmi')
    ->get();

foreach ($tanpaLokasi as $item) {
    echo "{$item->kode} | {$item->nama_resmi}\n";
}
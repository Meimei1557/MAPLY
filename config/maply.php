<?php

/*
|--------------------------------------------------------------------------
| Konfigurasi khusus Maply
|--------------------------------------------------------------------------
| titik_acuan  : titik yang dipakai untuk menghitung jarak kos (kampus, pusat kota, dll).
|                Hanya koordinat dengan 'terverifikasi' => true yang berasal dari sumber
|                publik yang bisa dirujuk (lihat 'sumber'). Tambahkan titik baru di sini.
| fasilitas    : daftar fasilitas standar untuk filter pencarian dan formulir admin.
*/

return [
    // Email kontak untuk kebijakan layanan Nominatim; atur NOMINATIM_EMAIL di .env.
    'geocoder_email' => env('NOMINATIM_EMAIL'),

    'titik_acuan' => [
        'unsri-bukit' => [
            'nama' => 'Universitas Sriwijaya — Kampus Bukit Besar',
            'singkat' => 'UNSRI Bukit Besar',
            'jenis' => 'Kampus',
            'ikon' => 'building-columns',
            'lat' => -2.986444,
            'lng' => 104.734333,
            'terverifikasi' => true,
            'sumber' => 'https://en.wikipedia.org/wiki/Sriwijaya_University',
        ],
        'ampera' => [
            'nama' => 'Pusat Kota — Jembatan Ampera / Benteng Kuto Besak',
            'singkat' => 'Pusat Kota (Ampera)',
            'jenis' => 'Pusat kota',
            'ikon' => 'city',
            'lat' => -2.988946,
            'lng' => 104.762056,
            'terverifikasi' => true,
            'sumber' => 'https://en.wikipedia.org/wiki/Ampera_LRT_station',
        ],
        'demang' => [
            'nama' => 'Demang Lebar Daun — Stasiun LRT Demang',
            'singkat' => 'Demang Lebar Daun',
            'jenis' => 'Kawasan perkantoran & kuliner',
            'ikon' => 'train-subway',
            'lat' => -2.964415,
            'lng' => 104.73588,
            'terverifikasi' => true,
            'sumber' => 'https://en.wikipedia.org/wiki/Demang_LRT_station',
        ],
        'jakabaring' => [
            'nama' => 'Jakabaring — Stasiun LRT Jakabaring',
            'singkat' => 'Jakabaring',
            'jenis' => 'Kawasan olahraga & perkantoran',
            'ikon' => 'person-running',
            'lat' => -3.02317,
            'lng' => 104.784682,
            'terverifikasi' => true,
            'sumber' => 'https://en.wikipedia.org/wiki/Jakabaring_LRT_station',
        ],
        'unsri-indralaya' => [
            'nama' => 'Universitas Sriwijaya — Kampus Indralaya',
            'singkat' => 'UNSRI Indralaya',
            'jenis' => 'Kampus',
            'ikon' => 'graduation-cap',
            // Pin Google Maps "Auditorium Universitas Sriwijaya" (kampus Indralaya), dikutip dari
            // halaman Lokanesia. Menggantikan koordinat lama (-3.22566, 104.65025) yang ±1,5 km meleset.
            'lat' => -3.2146643,
            'lng' => 104.647878,
            'terverifikasi' => true,
            'sumber' => 'https://lokanesia.com/kost-dekat-unsri-indralaya/',
        ],
        'uin-raden-fatah' => [
            'nama' => 'UIN Raden Fatah — Kampus Jl. Zainal Abidin Fikri (Fak. Dakwah & Komunikasi)',
            'singkat' => 'UIN Raden Fatah',
            'jenis' => 'Kampus',
            'ikon' => 'graduation-cap',
            'lat' => -2.9622054,
            'lng' => 104.7497334,
            'terverifikasi' => true,
            'sumber' => 'https://lokanesia.com/kost-dekat-uin-raden-fatah-palembang/',
        ],
    ],

    'titik_default' => 'unsri-bukit',

    // Filter wilayah pencarian (kolom `kos.kota`) beserta titik acuan bawaannya.
    'wilayah' => [
        'palembang' => ['label' => 'Palembang', 'kota' => 'Palembang', 'acuan' => 'unsri-bukit'],
        'indralaya' => ['label' => 'Indralaya (Ogan Ilir)', 'kota' => 'Indralaya', 'acuan' => 'unsri-indralaya'],
    ],

    // Kunci lama pada tautan/penanda halaman agar tidak rusak.
    'alias_titik' => [
        'palembang' => 'unsri-bukit',
        'indralaya' => 'unsri-indralaya',
    ],
// Menghubungkan kunci lama dengan kode lokasi kampus di database.
'alias_lokasi' => [
    'unsri-bukit' => 'unsri-1',
    'unsri-indralaya' => 'unsri-2',
    'uin-raden-fatah' => 'uin-raden-fatah-4',
],

    'fasilitas' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Dapur', 'Parkir', 'Laundry', 'CCTV'],

    'radius_opsi' => [1, 2, 3, 5, 10],

    'per_page' => 9,

    // Dipakai seeder. Atur lewat .env.
    'seed_contoh' => env('MAPLY_SEED_CONTOH', false),
    'admin_password' => env('MAPLY_ADMIN_PASSWORD', ''),
];

<?php

/*
|--------------------------------------------------------------------------
| Konfigurasi Pipeline Data AI Maply
|--------------------------------------------------------------------------
| Semua pengaturan pipeline 3 tahap ada di sini:
|   TAHAP 1 — Ingest multi-sumber (connector: osm, google_places, json_file, url).
|   TAHAP 2 — Pemrosesan/ekstraksi Gemini atas kandidat yang datanya belum lengkap.
|   TAHAP 3 — Validasi 2 lapis (gerbang deterministik + penilaian semantik AI).
|
| Prinsip kejujuran data: nilai yang tidak ditemukan di sumber = NULL, bukan dikarang.
| Hasil sumber eksternal selalu KANDIDAT, tidak pernah auto-publish.
*/

return [
    // -------------------------------------------------------------- Wilayah
    // label  : nama kota yang disimpan ke kolom kota.
    // osm_bbox : string bbox Overpass format (selatan,barat,utara,timur) — nilai sama dengan perilaku lama.
    // bbox   : [lat_min, lat_max, lng_min, lng_max] untuk gerbang deterministik Tahap 3.
    'regions' => [
        'palembang' => [
            'label' => 'Palembang',
            'osm_bbox' => '-3.12,104.60,-2.90,104.90',
            'bbox' => ['lat' => [-3.12, -2.90], 'lng' => [104.60, 104.90]],
        ],
        'indralaya' => [
            'label' => 'Indralaya',
            'osm_bbox' => '-3.48,104.55,-3.18,104.82',
            'bbox' => ['lat' => [-3.48, -3.18], 'lng' => [104.55, 104.82]],
        ],
    ],

    // ------------------------------------------------------- Connector (Tahap 1)
    // Peta kunci sumber -> kelas connector. Ditampilkan sebagai checkbox di form admin.
    'connectors' => [
        \App\Support\AiData\OsmConnector::class,
        \App\Support\AiData\GooglePlacesConnector::class,
        \App\Support\AiData\JsonFileConnector::class,
        \App\Support\AiData\UrlConnector::class,
    ],

    'google_places' => [
        'key' => env('GOOGLE_PLACES_API_KEY'),
        'endpoint' => 'https://places.googleapis.com/v1/places:searchText',
        'page_size' => 20,
    ],

    'url' => [
        // Tanpa scraping agresif: 1 URL per request, timeout 10 detik.
        'timeout' => 10,
        'user_agent' => 'MaplyKostManager/1.0 (pengambilan meta tunggal atas permintaan admin)',
    ],

    // Impor berkas JSON/CSV (JsonFileConnector).
    'import' => [
        'max_size_kb' => 5120,   // maks 5 MB
        'max_rows' => 2000,      // maks 2.000 baris
    ],

    // Pencarian terstruktur dari halaman admin (poin 10b).
    'search' => [
        'max_results' => 50,     // default jumlah maksimal kandidat baru per run.
        'max_results_max' => 200, // batas atas input admin.
    ],

    // --------------------------------------------------------- Gemini (Tahap 2 & 3)
    // Key dan model tetap memakai config services.gemini (sama dengan chatbot — jangan diubah).
    'gemini' => [
        'timeout' => 25,             // timeout HTTP 25 detik + log warning pada kegagalan.
        'batch_size' => 5,           // guardrail: maks 5 kandidat per request.
        'max_output_tokens' => 4096,
        'max_candidates_per_job' => 400,
    ],

    // Backoff eksternal: retry 3x exponential 2/4/8 detik (dipakai HTTP client Laravel).
    'http_retry' => [2000, 4000, 8000],

    // --------------------------------------------------------- Validasi (Tahap 3)
    'validation' => [
        // Nama mengandung kata terlarang -> suspicious (bukan langsung reject; admin memutuskan).
        'kata_terlarang' => [
            'hotel', 'resort', 'spa', 'salon', 'minimarket', 'indomaret', 'alfamart',
            'rumah sakit', 'klinik', 'puskesmas', 'bank', 'dealer', 'bengkel',
            'sekolah', 'kampus',
        ],
        // Harga mustahil per bulan -> flag.
        'harga_min' => 50000,
        'harga_max' => 20000000,
        // Skor akhir gabungan: 0.5 x deterministik + 0.35 x (confidence AI x 100) + 0.15 x kelengkapan.
        'bobot_deterministik' => 0.5,
        'bobot_ai' => 0.35,
        'bobot_kelengkapan' => 0.15,
    ],

    // Sumber yang dipakai pencarian berkala harian (routes/console.php).
    'scheduled_sources' => ['osm'],
];

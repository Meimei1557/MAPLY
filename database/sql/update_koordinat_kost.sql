-- ============================================================
-- UPDATE koordinat kost - sinkronisasi MAPLY
-- Total: 212 kost diupdate
-- Photon/OSM: 37 | Centroid: 175
-- CATATAN: koordinat PERKIRAAN, verifikasi via /admin/peta-koordinat
-- ============================================================

UPDATE `kos` SET
  `latitude` = -2.9823835,
  `longitude` = 104.7190773,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 10,
  `geocoding_query` = 'Jl. Kancil Putih V  G, Ilir Barat I, Palembang, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (kawasan, skor 10); belum dicek di lapangan. Belum ada: nomor telepon, koordinat.',
  `updated_at` = NOW()
WHERE `id` = 1;

UPDATE `kos` SET
  `latitude` = -2.9505602,
  `longitude` = 104.7498822,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Kemuning, Palembang, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: nomor telepon, koordinat.',
  `updated_at` = NOW()
WHERE `id` = 2;

UPDATE `kos` SET
  `latitude` = -2.9732880,
  `longitude` = 104.7203411,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 10,
  `geocoding_query` = 'Jl. Macan Kumbang VI, Ilir Barat I, Palembang, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (kawasan, skor 10); belum dicek di lapangan. Belum ada: harga, nomor telepon, koordinat, fasilitas.',
  `updated_at` = NOW()
WHERE `id` = 3;

UPDATE `kos` SET
  `latitude` = -2.9950319,
  `longitude` = 104.7165801,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 10,
  `geocoding_query` = 'Lrg. Persatuan, Ilir Barat I, Palembang, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (kawasan, skor 10); belum dicek di lapangan. Belum ada: harga, nomor telepon, koordinat, fasilitas.',
  `updated_at` = NOW()
WHERE `id` = 4;

UPDATE `kos` SET
  `latitude` = -2.9925916,
  `longitude` = 104.7269605,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 10,
  `geocoding_query` = 'Jl. Srijaya Negara, Ilir Barat I, Palembang, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (kawasan, skor 10); belum dicek di lapangan. Belum ada: harga, nomor telepon, koordinat, fasilitas.',
  `updated_at` = NOW()
WHERE `id` = 5;

UPDATE `kos` SET
  `latitude` = -2.9925916,
  `longitude` = 104.7269605,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 10,
  `geocoding_query` = 'Jl. Srijaya Negara, Ilir Barat I, Palembang, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (kawasan, skor 10); belum dicek di lapangan. Belum ada: harga, nomor telepon, koordinat.',
  `updated_at` = NOW()
WHERE `id` = 6;

UPDATE `kos` SET
  `latitude` = -2.9925916,
  `longitude` = 104.7269605,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 10,
  `geocoding_query` = 'Jl. Srijaya Negara, Ilir Barat I, Palembang, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (kawasan, skor 10); belum dicek di lapangan. Belum ada: harga, nomor telepon, koordinat, fasilitas.',
  `updated_at` = NOW()
WHERE `id` = 7;

UPDATE `kos` SET
  `latitude` = -2.9753921,
  `longitude` = 104.7464278,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Jl. Dwikora II, Ilir Barat I, Palembang, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (kawasan, skor 7); belum dicek di lapangan. Belum ada: harga, nomor telepon, koordinat.',
  `updated_at` = NOW()
WHERE `id` = 8;

UPDATE `kos` SET
  `latitude` = -2.9852498,
  `longitude` = 104.7306299,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 10,
  `geocoding_query` = 'Lorong Tembesu, Ilir Barat I, Palembang, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (kawasan, skor 10); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat belum ada (tautan sumber tidak memuat pin); lengkapi lewat Kelola Kos. Data bersumber dari direktori (tahun publikasi sumber 2025); konfirmasi harga dan ketersediaan ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 99;

UPDATE `kos` SET
  `latitude` = -3.2428485,
  `longitude` = 104.6986527,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 239;

UPDATE `kos` SET
  `latitude` = -3.2331597,
  `longitude` = 104.6648914,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Jl. Muhajirin, Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (kawasan, skor 7); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 240;

UPDATE `kos` SET
  `latitude` = -3.2106288,
  `longitude` = 104.6463150,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 6 kost berkoordinat di Indralaya',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Indralaya dengan offset ~250m (sumber: rata-rata 6 kost berkoordinat di Indralaya); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 241;

UPDATE `kos` SET
  `latitude` = -3.2320312,
  `longitude` = 104.6796466,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Komplek Bhakti Guna, Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (kawasan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 242;

UPDATE `kos` SET
  `latitude` = -3.2067181,
  `longitude` = 104.6881785,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Tanjung Seteko, Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (kawasan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 243;

UPDATE `kos` SET
  `latitude` = -3.3228670,
  `longitude` = 104.6952017,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'AH25, Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 244;

UPDATE `kos` SET
  `latitude` = -3.2428485,
  `longitude` = 104.6986527,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 245;

UPDATE `kos` SET
  `latitude` = -3.3228670,
  `longitude` = 104.6952017,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya Indah, Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 246;

UPDATE `kos` SET
  `latitude` = -3.2428485,
  `longitude` = 104.6986527,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 247;

UPDATE `kos` SET
  `latitude` = -3.2428485,
  `longitude` = 104.6986527,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 248;

UPDATE `kos` SET
  `latitude` = -3.2094172,
  `longitude` = 104.6425121,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Jl. Lintas Sumatra No., Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 249;

UPDATE `kos` SET
  `latitude` = -3.2428485,
  `longitude` = 104.6986527,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 250;

UPDATE `kos` SET
  `latitude` = -3.2428485,
  `longitude` = 104.6986527,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 251;

UPDATE `kos` SET
  `latitude` = -3.3228670,
  `longitude` = 104.6952017,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya Indah, Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 252;

UPDATE `kos` SET
  `latitude` = -3.2428485,
  `longitude` = 104.6986527,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 253;

UPDATE `kos` SET
  `latitude` = -3.2428485,
  `longitude` = 104.6986527,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 254;

UPDATE `kos` SET
  `latitude` = -3.2304212,
  `longitude` = 104.6540062,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Tanjung Pering, Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 255;

UPDATE `kos` SET
  `latitude` = -3.2428485,
  `longitude` = 104.6986527,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 256;

UPDATE `kos` SET
  `latitude` = -3.3228670,
  `longitude` = 104.6952017,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Jl. Indralaya Raya, Indralaya, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 257;

UPDATE `kos` SET
  `latitude` = -3.2219936,
  `longitude` = 104.6335273,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya Utara, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 258;

UPDATE `kos` SET
  `latitude` = -3.2219936,
  `longitude` = 104.6335273,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Indralaya Utara, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 259;

UPDATE `kos` SET
  `latitude` = -3.2089914,
  `longitude` = 104.6503486,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 101 kost berkoordinat di Indralaya Utara',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Indralaya Utara dengan offset ~250m (sumber: rata-rata 101 kost berkoordinat di Indralaya Utara); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 260;

UPDATE `kos` SET
  `latitude` = -3.2066842,
  `longitude` = 104.6526160,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 101 kost berkoordinat di Indralaya Utara',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Indralaya Utara dengan offset ~250m (sumber: rata-rata 101 kost berkoordinat di Indralaya Utara); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 261;

UPDATE `kos` SET
  `latitude` = -3.2094172,
  `longitude` = 104.6425121,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Timbangan, Indralaya Utara, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 262;

UPDATE `kos` SET
  `latitude` = -3.2094172,
  `longitude` = 104.6425121,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Timbangan, Indralaya Utara, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 263;

UPDATE `kos` SET
  `latitude` = -3.2094172,
  `longitude` = 104.6425121,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Timbangan, Indralaya Utara, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 264;

UPDATE `kos` SET
  `latitude` = -3.2059056,
  `longitude` = 104.6496672,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 101 kost berkoordinat di Indralaya Utara',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Indralaya Utara dengan offset ~250m (sumber: rata-rata 101 kost berkoordinat di Indralaya Utara); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 265;

UPDATE `kos` SET
  `latitude` = -3.2094172,
  `longitude` = 104.6425121,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Jl. Lintas Tengah, Indralaya Utara, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 266;

UPDATE `kos` SET
  `latitude` = -3.2094172,
  `longitude` = 104.6425121,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Jl. Lintas Tengah, Indralaya Utara, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik. Catatan tambahan: Sumber lain mencantumkan nomor berbeda untuk ''Kost Nina'' (0815-3297-5424) — kemungkinan listing berbeda atau pemilik yang sama dengan beberapa nomor.',
  `updated_at` = NOW()
WHERE `id` = 267;

UPDATE `kos` SET
  `latitude` = -3.2094172,
  `longitude` = 104.6425121,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 7,
  `geocoding_query` = 'Timbangan, Indralaya Utara, Indralaya, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 7); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik. Catatan tambahan: Listing Lokanesia ''D''Pangeran Kost'' memiliki telepon sama dengan ''Kost Pangeran (Indekos Pangeran)'' yang sudah ada — mungkin listing kembar; koordinat dipertahankan terpisah karena nama berbeda.',
  `updated_at` = NOW()
WHERE `id` = 268;

UPDATE `kos` SET
  `latitude` = -2.9761702,
  `longitude` = 104.7418930,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 10,
  `geocoding_query` = 'Ilir Barat I, Palembang, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 10); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 269;

UPDATE `kos` SET
  `latitude` = -2.9889056,
  `longitude` = 104.7369436,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'photon',
  `geocoding_score` = 10,
  `geocoding_query` = 'Bukit Lama, Ilir Barat I, Palembang, Sumatera Selatan',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat hasil geocoding Photon/OSM (perkiraan, skor 10); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 270;

UPDATE `kos` SET
  `latitude` = -2.9838354,
  `longitude` = 104.7275391,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 271;

UPDATE `kos` SET
  `latitude` = -2.9848519,
  `longitude` = 104.7299169,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 272;

UPDATE `kos` SET
  `latitude` = -2.9995623,
  `longitude` = 104.7768282,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 273;

UPDATE `kos` SET
  `latitude` = -2.9964456,
  `longitude` = 104.7785587,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 274;

UPDATE `kos` SET
  `latitude` = -2.9985928,
  `longitude` = 104.7787803,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 275;

UPDATE `kos` SET
  `latitude` = -2.9984828,
  `longitude` = 104.8179055,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Akamigas Plaju',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Plaju dengan offset ~250m (sumber: titik referensi kampus Akamigas Plaju); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 276;

UPDATE `kos` SET
  `latitude` = -2.9956659,
  `longitude` = 104.7793247,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 277;

UPDATE `kos` SET
  `latitude` = -2.9979937,
  `longitude` = 104.7766114,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 278;

UPDATE `kos` SET
  `latitude` = -2.9924988,
  `longitude` = 104.7751258,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UPGRI',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu I dengan offset ~250m (sumber: titik referensi kampus UPGRI); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 279;

UPDATE `kos` SET
  `latitude` = -2.9992313,
  `longitude` = 104.7763176,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 280;

UPDATE `kos` SET
  `latitude` = -2.9930474,
  `longitude` = 104.7764614,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UPGRI',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu I dengan offset ~250m (sumber: titik referensi kampus UPGRI); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 281;

UPDATE `kos` SET
  `latitude` = -2.9956594,
  `longitude` = 104.7794827,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 282;

UPDATE `kos` SET
  `latitude` = -2.9946038,
  `longitude` = 104.7783084,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UPGRI',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu I dengan offset ~250m (sumber: titik referensi kampus UPGRI); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 283;

UPDATE `kos` SET
  `latitude` = -2.9978023,
  `longitude` = 104.7785942,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 284;

UPDATE `kos` SET
  `latitude` = -2.9955480,
  `longitude` = 104.7789266,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 285;

UPDATE `kos` SET
  `latitude` = -2.9953865,
  `longitude` = 104.7787208,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: nomor telepon, koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 286;

UPDATE `kos` SET
  `latitude` = -2.9961721,
  `longitude` = 104.7760631,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 287;

UPDATE `kos` SET
  `latitude` = -2.9985555,
  `longitude` = 104.7772809,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 288;

UPDATE `kos` SET
  `latitude` = -2.9992960,
  `longitude` = 104.7769980,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 289;

UPDATE `kos` SET
  `latitude` = -2.9991900,
  `longitude` = 104.7772239,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 290;

UPDATE `kos` SET
  `latitude` = -2.9965166,
  `longitude` = 104.7776582,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus UM Palembang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Seberang Ulu II dengan offset ~250m (sumber: titik referensi kampus UM Palembang); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 291;

UPDATE `kos` SET
  `latitude` = -3.0006791,
  `longitude` = 104.8189205,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Akamigas Plaju',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Plaju dengan offset ~250m (sumber: titik referensi kampus Akamigas Plaju); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 292;

UPDATE `kos` SET
  `latitude` = -2.9632499,
  `longitude` = 104.7524592,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 293;

UPDATE `kos` SET
  `latitude` = -2.9613446,
  `longitude` = 104.7508216,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 294;

UPDATE `kos` SET
  `latitude` = -2.9637291,
  `longitude` = 104.7514216,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 295;

UPDATE `kos` SET
  `latitude` = -2.9102920,
  `longitude` = 104.6984083,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Poltekbang',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Sukarami dengan offset ~250m (sumber: titik referensi kampus Poltekbang); belum dicek di lapangan. Belum ada: koordinat, harga, fasilitas, jenis kost (putra/putri/campur). Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 296;

UPDATE `kos` SET
  `latitude` = -2.9596372,
  `longitude` = 104.7509759,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 297;

UPDATE `kos` SET
  `latitude` = -2.9618000,
  `longitude` = 104.7511990,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 298;

UPDATE `kos` SET
  `latitude` = -2.9603705,
  `longitude` = 104.7516559,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 299;

UPDATE `kos` SET
  `latitude` = -2.9634396,
  `longitude` = 104.7479364,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 300;

UPDATE `kos` SET
  `latitude` = -2.9630075,
  `longitude` = 104.7491146,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 301;

UPDATE `kos` SET
  `latitude` = -2.9635299,
  `longitude` = 104.7524905,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 302;

UPDATE `kos` SET
  `latitude` = -2.9602030,
  `longitude` = 104.7493493,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 303;

UPDATE `kos` SET
  `latitude` = -2.9613076,
  `longitude` = 104.7497541,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 304;

UPDATE `kos` SET
  `latitude` = -2.9600121,
  `longitude` = 104.7500702,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 305;

UPDATE `kos` SET
  `latitude` = -2.9632604,
  `longitude` = 104.7490091,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 306;

UPDATE `kos` SET
  `latitude` = -2.9617780,
  `longitude` = 104.7490896,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 307;

UPDATE `kos` SET
  `latitude` = -2.9616619,
  `longitude` = 104.7522650,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 308;

UPDATE `kos` SET
  `latitude` = -2.9625878,
  `longitude` = 104.7488725,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 309;

UPDATE `kos` SET
  `latitude` = -2.9595971,
  `longitude` = 104.7503236,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 310;

UPDATE `kos` SET
  `latitude` = -2.9641302,
  `longitude` = 104.7480115,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 311;

UPDATE `kos` SET
  `latitude` = -2.9640365,
  `longitude` = 104.7509132,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 312;

UPDATE `kos` SET
  `latitude` = -2.9699482,
  `longitude` = 104.7456284,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 313;

UPDATE `kos` SET
  `latitude` = -2.9735910,
  `longitude` = 104.7454257,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 314;

UPDATE `kos` SET
  `latitude` = -2.9689280,
  `longitude` = 104.7461632,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 315;

UPDATE `kos` SET
  `latitude` = -2.9810897,
  `longitude` = 104.7316940,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 316;

UPDATE `kos` SET
  `latitude` = -2.9738512,
  `longitude` = 104.7471212,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 317;

UPDATE `kos` SET
  `latitude` = -2.9705000,
  `longitude` = 104.7462025,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 318;

UPDATE `kos` SET
  `latitude` = -2.9725745,
  `longitude` = 104.7467224,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 319;

UPDATE `kos` SET
  `latitude` = -2.9640270,
  `longitude` = 104.7499498,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 320;

UPDATE `kos` SET
  `latitude` = -2.9716400,
  `longitude` = 104.7482867,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 321;

UPDATE `kos` SET
  `latitude` = -2.9695293,
  `longitude` = 104.7448345,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 322;

UPDATE `kos` SET
  `latitude` = -2.9714057,
  `longitude` = 104.7444109,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 323;

UPDATE `kos` SET
  `latitude` = -2.9813819,
  `longitude` = 104.7317427,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 324;

UPDATE `kos` SET
  `latitude` = -2.9724164,
  `longitude` = 104.7467123,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 325;

UPDATE `kos` SET
  `latitude` = -2.9708637,
  `longitude` = 104.7442818,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 326;

UPDATE `kos` SET
  `latitude` = -2.9700960,
  `longitude` = 104.7462145,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 327;

UPDATE `kos` SET
  `latitude` = -2.9820519,
  `longitude` = 104.7300419,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 328;

UPDATE `kos` SET
  `latitude` = -2.9739057,
  `longitude` = 104.7451384,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 329;

UPDATE `kos` SET
  `latitude` = -2.9738112,
  `longitude` = 104.7481631,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 330;

UPDATE `kos` SET
  `latitude` = -2.9695150,
  `longitude` = 104.7476759,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 331;

UPDATE `kos` SET
  `latitude` = -2.9723710,
  `longitude` = 104.7438072,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 332;

UPDATE `kos` SET
  `latitude` = -2.9695186,
  `longitude` = 104.7482523,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 333;

UPDATE `kos` SET
  `latitude` = -2.9734803,
  `longitude` = 104.7459476,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 334;

UPDATE `kos` SET
  `latitude` = -2.9735625,
  `longitude` = 104.7473206,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 335;

UPDATE `kos` SET
  `latitude` = -2.9700794,
  `longitude` = 104.7441596,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 336;

UPDATE `kos` SET
  `latitude` = -2.9715322,
  `longitude` = 104.7462666,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 337;

UPDATE `kos` SET
  `latitude` = -2.9725833,
  `longitude` = 104.7478798,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 338;

UPDATE `kos` SET
  `latitude` = -2.9838294,
  `longitude` = 104.7284491,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 339;

UPDATE `kos` SET
  `latitude` = -2.9832486,
  `longitude` = 104.7310398,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 340;

UPDATE `kos` SET
  `latitude` = -2.9849393,
  `longitude` = 104.7289487,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 341;

UPDATE `kos` SET
  `latitude` = -2.9689329,
  `longitude` = 104.7467670,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 342;

UPDATE `kos` SET
  `latitude` = -2.9717181,
  `longitude` = 104.7461055,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 343;

UPDATE `kos` SET
  `latitude` = -2.9733036,
  `longitude` = 104.7446411,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 344;

UPDATE `kos` SET
  `latitude` = -2.9842546,
  `longitude` = 104.7303317,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 345;

UPDATE `kos` SET
  `latitude` = -2.9847945,
  `longitude` = 104.7284912,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 346;

UPDATE `kos` SET
  `latitude` = -2.9735536,
  `longitude` = 104.7466731,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 347;

UPDATE `kos` SET
  `latitude` = -2.9848003,
  `longitude` = 104.7319172,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 348;

UPDATE `kos` SET
  `latitude` = -2.9696104,
  `longitude` = 104.7438719,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 349;

UPDATE `kos` SET
  `latitude` = -2.9847550,
  `longitude` = 104.7307350,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 350;

UPDATE `kos` SET
  `latitude` = -2.9848739,
  `longitude` = 104.7280517,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 351;

UPDATE `kos` SET
  `latitude` = -2.9692310,
  `longitude` = 104.7463728,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 352;

UPDATE `kos` SET
  `latitude` = -2.9715452,
  `longitude` = 104.7474407,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 353;

UPDATE `kos` SET
  `latitude` = -2.9819076,
  `longitude` = 104.7283422,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 354;

UPDATE `kos` SET
  `latitude` = -2.9734239,
  `longitude` = 104.7456729,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 355;

UPDATE `kos` SET
  `latitude` = -2.9838271,
  `longitude` = 104.7297253,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 356;

UPDATE `kos` SET
  `latitude` = -2.9822997,
  `longitude` = 104.7307570,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 357;

UPDATE `kos` SET
  `latitude` = -2.9810242,
  `longitude` = 104.7278822,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 358;

UPDATE `kos` SET
  `latitude` = -2.9718955,
  `longitude` = 104.7452141,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 359;

UPDATE `kos` SET
  `latitude` = -2.9816367,
  `longitude` = 104.7286334,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 360;

UPDATE `kos` SET
  `latitude` = -2.9849940,
  `longitude` = 104.7296332,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 361;

UPDATE `kos` SET
  `latitude` = -2.9717992,
  `longitude` = 104.7449103,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 362;

UPDATE `kos` SET
  `latitude` = -2.9846960,
  `longitude` = 104.7320065,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 363;

UPDATE `kos` SET
  `latitude` = -2.9716929,
  `longitude` = 104.7478243,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 364;

UPDATE `kos` SET
  `latitude` = -2.9711570,
  `longitude` = 104.7437705,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 365;

UPDATE `kos` SET
  `latitude` = -2.9689122,
  `longitude` = 104.7476977,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 366;

UPDATE `kos` SET
  `latitude` = -2.9597398,
  `longitude` = 104.7524078,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 367;

UPDATE `kos` SET
  `latitude` = -2.9817016,
  `longitude` = 104.7282217,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 368;

UPDATE `kos` SET
  `latitude` = -2.9621566,
  `longitude` = 104.7488447,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 369;

UPDATE `kos` SET
  `latitude` = -2.9625796,
  `longitude` = 104.7480691,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 370;

UPDATE `kos` SET
  `latitude` = -2.9720137,
  `longitude` = 104.7484441,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 371;

UPDATE `kos` SET
  `latitude` = -2.9846190,
  `longitude` = 104.7313105,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 372;

UPDATE `kos` SET
  `latitude` = -2.9836700,
  `longitude` = 104.7295052,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 373;

UPDATE `kos` SET
  `latitude` = -2.9811585,
  `longitude` = 104.7323673,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 374;

UPDATE `kos` SET
  `latitude` = -2.9831662,
  `longitude` = 104.7309822,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 375;

UPDATE `kos` SET
  `latitude` = -2.9851711,
  `longitude` = 104.7288737,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 376;

UPDATE `kos` SET
  `latitude` = -2.9690651,
  `longitude` = 104.7464135,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 377;

UPDATE `kos` SET
  `latitude` = -2.9618738,
  `longitude` = 104.7515158,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 378;

UPDATE `kos` SET
  `latitude` = -2.9736228,
  `longitude` = 104.7464385,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 379;

UPDATE `kos` SET
  `latitude` = -2.9834308,
  `longitude` = 104.7316537,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 380;

UPDATE `kos` SET
  `latitude` = -2.9851579,
  `longitude` = 104.7321940,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 381;

UPDATE `kos` SET
  `latitude` = -2.9735080,
  `longitude` = 104.7444467,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 382;

UPDATE `kos` SET
  `latitude` = -2.9829699,
  `longitude` = 104.7307662,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 383;

UPDATE `kos` SET
  `latitude` = -2.9809172,
  `longitude` = 104.7316657,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata kota Palembang (122 kost)',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan None dengan offset ~250m (sumber: rata-rata kota Palembang (122 kost)); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 384;

UPDATE `kos` SET
  `latitude` = -2.9694572,
  `longitude` = 104.7447487,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 385;

UPDATE `kos` SET
  `latitude` = -2.9829724,
  `longitude` = 104.7304871,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 386;

UPDATE `kos` SET
  `latitude` = -2.9838489,
  `longitude` = 104.7303085,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 387;

UPDATE `kos` SET
  `latitude` = -2.9712947,
  `longitude` = 104.7481911,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 388;

UPDATE `kos` SET
  `latitude` = -2.9635635,
  `longitude` = 104.7513569,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 389;

UPDATE `kos` SET
  `latitude` = -2.9727152,
  `longitude` = 104.7454965,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 390;

UPDATE `kos` SET
  `latitude` = -2.9825866,
  `longitude` = 104.7288901,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 391;

UPDATE `kos` SET
  `latitude` = -2.9630039,
  `longitude` = 104.7515352,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 392;

UPDATE `kos` SET
  `latitude` = -2.9855823,
  `longitude` = 104.7296816,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 393;

UPDATE `kos` SET
  `latitude` = -2.9809528,
  `longitude` = 104.7323706,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 394;

UPDATE `kos` SET
  `latitude` = -2.9642185,
  `longitude` = 104.7488417,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 395;

UPDATE `kos` SET
  `latitude` = -2.9846190,
  `longitude` = 104.7320564,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 396;

UPDATE `kos` SET
  `latitude` = -2.9815407,
  `longitude` = 104.7317865,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 397;

UPDATE `kos` SET
  `latitude` = -2.9840974,
  `longitude` = 104.7281789,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 398;

UPDATE `kos` SET
  `latitude` = -2.9817763,
  `longitude` = 104.7309078,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 399;

UPDATE `kos` SET
  `latitude` = -2.9828867,
  `longitude` = 104.7323263,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 400;

UPDATE `kos` SET
  `latitude` = -2.9706387,
  `longitude` = 104.7435567,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 401;

UPDATE `kos` SET
  `latitude` = -2.9604993,
  `longitude` = 104.7492728,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 402;

UPDATE `kos` SET
  `latitude` = -2.9705917,
  `longitude` = 104.7482123,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 403;

UPDATE `kos` SET
  `latitude` = -2.9639133,
  `longitude` = 104.7483531,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 404;

UPDATE `kos` SET
  `latitude` = -2.9733734,
  `longitude` = 104.7462837,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 405;

UPDATE `kos` SET
  `latitude` = -2.9845833,
  `longitude` = 104.7304143,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 406;

UPDATE `kos` SET
  `latitude` = -2.9609967,
  `longitude` = 104.7487939,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 407;

UPDATE `kos` SET
  `latitude` = -2.9614136,
  `longitude` = 104.7490958,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 408;

UPDATE `kos` SET
  `latitude` = -2.9714659,
  `longitude` = 104.7480443,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 409;

UPDATE `kos` SET
  `latitude` = -2.9696781,
  `longitude` = 104.7439791,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 410;

UPDATE `kos` SET
  `latitude` = -2.9624669,
  `longitude` = 104.7491593,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 411;

UPDATE `kos` SET
  `latitude` = -2.9738909,
  `longitude` = 104.7473732,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 412;

UPDATE `kos` SET
  `latitude` = -2.9613992,
  `longitude` = 104.7490857,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 413;

UPDATE `kos` SET
  `latitude` = -2.9702024,
  `longitude` = 104.7462760,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 414;

UPDATE `kos` SET
  `latitude` = -2.9624464,
  `longitude` = 104.7478243,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 415;

UPDATE `kos` SET
  `latitude` = -2.9855688,
  `longitude` = 104.7318057,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 416;

UPDATE `kos` SET
  `latitude` = -2.9814254,
  `longitude` = 104.7301181,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 417;

UPDATE `kos` SET
  `latitude` = -2.9604118,
  `longitude` = 104.7506885,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 418;

UPDATE `kos` SET
  `latitude` = -2.9731681,
  `longitude` = 104.7441548,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 419;

UPDATE `kos` SET
  `latitude` = -2.9805519,
  `longitude` = 104.7355612,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata kota Palembang (122 kost)',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan None dengan offset ~250m (sumber: rata-rata kota Palembang (122 kost)); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 420;

UPDATE `kos` SET
  `latitude` = -2.9699280,
  `longitude` = 104.7478211,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 421;

UPDATE `kos` SET
  `latitude` = -2.9694140,
  `longitude` = 104.7445680,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 422;

UPDATE `kos` SET
  `latitude` = -2.9726610,
  `longitude` = 104.7440316,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 423;

UPDATE `kos` SET
  `latitude` = -2.9700080,
  `longitude` = 104.7479383,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 424;

UPDATE `kos` SET
  `latitude` = -2.9718767,
  `longitude` = 104.7466209,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 425;

UPDATE `kos` SET
  `latitude` = -2.9638120,
  `longitude` = 104.7524253,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 426;

UPDATE `kos` SET
  `latitude` = -2.9602618,
  `longitude` = 104.7526570,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 427;

UPDATE `kos` SET
  `latitude` = -2.9605309,
  `longitude` = 104.7521830,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 428;

UPDATE `kos` SET
  `latitude` = -2.9644609,
  `longitude` = 104.7514587,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 429;

UPDATE `kos` SET
  `latitude` = -2.9722477,
  `longitude` = 104.7481717,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'titik referensi kampus Tridinandi',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Timur I dengan offset ~250m (sumber: titik referensi kampus Tridinandi); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 430;

UPDATE `kos` SET
  `latitude` = -2.9605736,
  `longitude` = 104.7520962,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 431;

UPDATE `kos` SET
  `latitude` = -2.9605310,
  `longitude` = 104.7491100,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 432;

UPDATE `kos` SET
  `latitude` = -2.9606479,
  `longitude` = 104.7483164,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 433;

UPDATE `kos` SET
  `latitude` = -2.9602240,
  `longitude` = 104.7520689,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 434;

UPDATE `kos` SET
  `latitude` = -2.9634726,
  `longitude` = 104.7518589,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 435;

UPDATE `kos` SET
  `latitude` = -2.9622833,
  `longitude` = 104.7493019,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 436;

UPDATE `kos` SET
  `latitude` = -2.9606081,
  `longitude` = 104.7489139,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 437;

UPDATE `kos` SET
  `latitude` = -2.9644665,
  `longitude` = 104.7487416,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 438;

UPDATE `kos` SET
  `latitude` = -2.9629435,
  `longitude` = 104.7520977,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 439;

UPDATE `kos` SET
  `latitude` = -2.9597503,
  `longitude` = 104.7491716,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 22 kost berkoordinat di Kemuning',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Kemuning dengan offset ~250m (sumber: rata-rata 22 kost berkoordinat di Kemuning); belum dicek di lapangan. Belum ada: nomor telepon, harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 440;

UPDATE `kos` SET
  `latitude` = -2.9827376,
  `longitude` = 104.7293885,
  `koordinat_status` = 'perkiraan',
  `geocoding_status` = 'perkiraan',
  `geocoding_source` = 'centroid',
  `geocoding_score` = 2,
  `geocoding_query` = 'rata-rata 100 kost berkoordinat di Ilir Barat I',
  `geocoded_at` = NOW(),
  `catatan_data` = 'Koordinat PERKIRAAN: centroid kecamatan Ilir Barat I dengan offset ~250m (sumber: rata-rata 100 kost berkoordinat di Ilir Barat I); belum dicek di lapangan. Belum ada: harga, fasilitas, jenis kost (putra/putri/campur), koordinat. Koordinat tidak dicantumkan sumber — pin Google Maps memerlukan halaman terpisah (JavaScript) yang tidak dapat diakses dari lingkungan kerja. Listing tetap disimpan dan muncul di daftar/pencarian, tetapi TIDAK muncul sebagai marker di peta sampai koordinat terverifikasi. Data bersumber dari direktori Lokanesia (tahun publikasi 2024–2025); konfirmasi harga, ketersediaan, dan jenis kost ke pemilik.',
  `updated_at` = NOW()
WHERE `id` = 441;


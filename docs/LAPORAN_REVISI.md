# Laporan Revisi MAPLY

## Yang dikerjakan
1. **Audit proyek lama** — ZIP upload berisi sebagian proyek (routes, views, migrations, seeders, data kos 471, CSS/JS peta). Tidak ada composer.json/app/config/.env → lapisan aplikasi dibangun ulang di atas Laravel 12 sambil mempertahankan seluruh fungsi lama (login/register, dashboard, CRUD kos admin, AI Kost Manager, chat AI, welcome animation).
2. **Data kampus** — 110 institusi (99 aktif: 7 negeri, 92 swasta) di Kota Palembang & Kab. Ogan Ilir dari referensi resmi (PDDikti terblokir 403 → dipakai referensi.data.kemendikdasmen.go.id + BanPT + situs resmi ±22 kampus). 11 entri lama/tidak aktif tetap dicatat dengan flag. Nama fiktif dicek dan DITOLAK ("Universitas Indralaya/UNIRA" = tidak ada; unira.ac.id = Universitas Madura).
3. **Koordinat kampus** — 17 lokasi fisik terverifikasi (Wikidata/OSM/NPSN/situs resmi), semua dicek silang ke Nominatim; hasil pemeriksaan tersimpan di kolom verifikasi_koordinat. Polsri: Nominatim menunjuk objek salah nama di Kertapati → dipakai koordinat Wikidata/OSM (Jl. Srijaya Negara).
4. **Logo resmi** — 10 logo diunduh dari situs resmi/Wikimedia ke public/logos/kampus/ (unsri, polsri, uin, uigm, um-palembang, tridinanti, poltekkes, akamigas, stie-aprin, stik-siti-khadijah); sisanya pakai placeholder inisial (bukan logo palsu).
5. **Data kos** — 471 data (334 Palembang, 137 Indralaya): 441 sumber publik berstatus "perlu konfirmasi" + 30 data contoh ditandai jelas (bisa dimatikan via MAPLY_SEED_CONTOH=false). Koordinat 259 kos = pin sumber (status jujur, belum diverifikasi lapangan). 212 tanpa koordinat tampil di daftar, tidak di peta.
6. **Konsistensi Google Maps** — semua tombol (lokasi & arah kos→kampus) dibuat dari koordinat database yang sama dengan marker (app/Services/MapsUrl.php, format api=1&query=LAT,LNG 6 desimal).
7. **Filter berfungsi nyata** — kampus tujuan (17 lokasi), jarak garis lurus (dilabeli jelas), harga min/max (min>max otomatis ditukar; kos tanpa harga tidak dianggap lolos filter harga), fasilitas dari data nyata, wilayah, kecamatan, status data, urutan. Diimplementasi di query SQL + haversine PHP.
8. **Rute perjalanan** — OSRM (foot/bike/driving) + cache tabel rute_cache; halaman detail menampilkan: titik asal, kampus tujuan, jarak garis lurus, jarak rute jalan, estimasi waktu, rute jalan kaki (hanya ≤3 km & hanya bila layanan menyediakan), tombol "Lihat rute di peta" + Google Maps directions.
9. **Desain baru** — urutan halaman: header → pencarian+filter (card atas) → card logo kampus bulat → peta utama → hasil kos di bawah peta → detail. Penanda Ampera/Demang/Jakabaring dihapus; marker 4 warna konsisten (kampus ungu, kos hijau/amber/lavender). Card kos tanpa foto tidak menampilkan kotak gambar kosong. Responsif desktop/tablet/HP.
10. **Admin** — peta koordinat, CRUD kos (aksesori/pemilih peta dipertahankan), AI Kost Manager (impor JSON, ingest meta halaman publik, approve→terbit sebagai "perlu konfirmasi", tanpa auto-publish), chat AI deterministik dari database + opsi LLM via MAPLY_AI_API_KEY.

## Pengujian (docs/HASIL_PENGUJIAN.txt)
- Jalur publik /login dan /kos dengan 8 kombinasi filter (kampus, radius, harga tertukar, fasilitas+harga, wilayah, hasil kosong) → HTTP 200 semua di sandbox (SQLite + PHP 8.3 static).
- Migrasi 17 tabel + seed: 471 kos, 110 kampus, 17 lokasi, 10 logo.
- Belum terverifikasi di sandbox: alur detail+rute API lewat browser nyata (server API rute sempat redirect karena posisi route di grup auth; sudah dipindah ke publik+throttle, perlu cek ulang), tampilan visual perangkat HP, MySQL asli (sandbox hanya SQLite; sintaks aman untuk keduanya).

## Masalah yang belum selesai & alasannya
1. **Dokumen Word revisi tidak ditemukan** di upload (hanya yy.zip) — revisi desain mengikuti instruksi lengkap di pesan Anda.
2. **Screenshot** belum dihasilkan — browser headless tidak sempat dijalankan dalam batas waktu; jalankan `php artisan serve` lalu cek halaman.
3. **PDDikti 403** saat riset otomatis — identitas kampus memakai referensi.data.kemendikdasmen.go.id + BanPT; silakan cek ulang NPSN per kampus saat verifikasi lanjutan.
4. **Koordinat kos** = pin dari sumber listing (status "google"); belum ada cek lapangan. 14 kampus aktif lain belum punya koordinat terverifikasi (tidak diberi marker — kolom kampus.json bisa dilengkapi).
5. **Ekstraksi Gemini** pada AI Kost Manager butuh GEMINI_API_KEY (opsional).
6. Rute OSRM memakai server demo publik — untuk produksi pasang OSRM sendiri (MAPLY_OSRM_URL).

## Lanjutan
Kirim pesan lanjutan untuk: verifikasi rute API via browser, screenshot, penambahan koordinat kampus sisanya, atau migrasi MySQL nyata.

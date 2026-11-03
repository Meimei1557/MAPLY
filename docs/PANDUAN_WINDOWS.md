# Panduan Instalasi Windows (PowerShell + XAMPP + phpMyAdmin)

## 1. Persiapan
- XAMPP dengan PHP >= 8.2 dan MySQL aktif (Control Panel → Start Apache & MySQL).
- Composer terpasang (https://getcomposer.org/download/).
- Salin seluruh folder proyek (mis. `C:\xampp\htdocs\maply`).

## 2. PowerShell — langkah per langkah
```powershell
cd C:\xampp\htdocs\maply
composer install
Copy-Item .env.example .env
notepad .env   # atur DB_CONNECTION=mysql, DB_DATABASE=maply, user root, password kosong
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```
Buka http://127.0.0.1:8000 — login admin `admin` / `admin123`.

## 3. Database di phpMyAdmin
- Buat database `maply` (utf8mb4_unicode_ci) SEBELUM `php artisan migrate`.
- Tabel dibuat otomatis: users, cache, jobs, kos, fasilitas, kampus, kampus_lokasi,
  rute_cache, ai_search_runs, ai_kos_candidates, ai_candidate_sources.
- Data terisi oleh seeder: KampusSeeder (110 institusi + 17 lokasi) dan KosSeeder (471 kos).
- PERINGATAN: `php artisan migrate:fresh` MENGHAPUS SEMUA DATA. Gunakan hanya pada database kosong.

## 4. Verifikasi Google Maps
1. Buka /kos, pilih kos, klik "Google Maps" → URL memuat `query=LAT,LNG` dari database.
2. Bandingkan dengan koordinat yang tampil di halaman detail (bagian Lokasi) — harus sama persis.
3. Tombol "Rute kos → kampus" memakai `origin=koordinat kos&destination=koordinat kampus`.

## 5. Pemecahan masalah
- `No application encryption key has been specified.` → `.env` belum ada atau `APP_KEY=` kosong. Jalankan `PERBAIKI-KEY.bat` (klik dua kali), atau di PowerShell:
  `Copy-Item .env.example .env` → `php artisan key:generate` → `php artisan config:clear` → `php artisan serve`.
- `could not find driver` → aktifkan ekstensi pdo_mysql di php.ini XAMPP.
- Logo tidak muncul → jalankan `php artisan storage:link`; logo kampus ada di `public/logos/kampus/`.
- Rute "belum tersedia" → layanan OSRM publik sedang tidak dapat dihubungi; coba lagi atau pasang OSRM sendiri (MAPLY_OSRM_URL).

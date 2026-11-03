# Menjalankan MAPLY di XAMPP (Windows)

**Kebutuhan:** XAMPP (Apache + MySQL/MariaDB, PHP 8.2+; ekstensi `pdo_mysql`, `mbstring`, `fileinfo`, `gd`), dan koneksi internet (peta OpenStreetMap, Leaflet, dan ikon dimuat dari CDN).

1. Ekstrak ZIP ke mis. `C:\xampp\htdocs\maply`. Folder `vendor` sudah disertakan (tidak perlu Composer).
2. Nyalakan **MySQL** di XAMPP Control Panel, buka `http://localhost/phpmyadmin`, buat database **`maply`** (collation `utf8mb4_unicode_ci`).
3. Salin `.env.example` menjadi `.env` (SETUP-MAPLY.bat melakukannya otomatis). Isi bawaan sudah cocok untuk XAMPP (`root`, tanpa password).
4. Klik dua kali **`SETUP-MAPLY.bat`** → membuat tabel dan mengisi **296** data kos + akun demo.
   - Alternatif lewat SQL: jalankan `php artisan migrate`, lalu di phpMyAdmin pilih database `maply` → *Import* → `database/maply_import_kos.sql` (akun demo dibuat dengan `php artisan db:seed --class=UserSeeder`).
5. Klik dua kali **`START-MAPLY.bat`** lalu buka **http://127.0.0.1:8000**.
6. Login admin: `admin` / `Admin@Maply2026` (ganti di server publik). Pengguna: `meisya` / `password`.

Pemeriksaan cepat: halaman *Cari Kos* menampilkan teks "229 marker ditampilkan di peta" (67 kos tanpa koordinat tetap muncul di daftar, tetapi tidak sebagai marker). Filter **Wilayah** memisahkan Palembang (159) dan Indralaya (137).
Pengujian otomatis (opsional, butuh `composer install` agar paket dev terpasang): `php artisan test`.

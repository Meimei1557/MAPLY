# Maply — Pencarian Kos Berdasarkan Lokasi (Palembang & Indralaya)

Laravel 12 · Blade · Leaflet + OpenStreetMap · MySQL atau SQLite.

## Instalasi

Kebutuhan: PHP 8.2+ (ekstensi `pdo_mysql`/`pdo_sqlite`, `mbstring`, `fileinfo`, `gd` opsional), Composer.

```bash
composer install
copy .env.example .env        # Linux/Mac: cp .env.example .env
php artisan key:generate
# atur database di .env (MySQL: buat database `maply`; atau DB_CONNECTION=sqlite)
php artisan migrate:fresh --seed
php artisan storage:link      # agar foto unggahan tampil
php artisan serve             # buka http://127.0.0.1:8000
```
Windows/XAMPP: jalankan `SETUP-MAPLY.bat` lalu `START-MAPLY.bat` (keduanya otomatis membuat `.env` dan mengisi `APP_KEY` bila kosong).
**Wajib `migrate:fresh --seed`** (bukan `migrate` saja): skema tabel `kos` dan `users` berubah.

> **Error `No application encryption key has been specified.`** → `.env` belum ada atau `APP_KEY=` masih kosong (umum terjadi setelah ekstrak ZIP baru, karena ZIP tidak ikut mengirim `.env` berisi kunci). Perbaikan cepat Windows: klik dua kali **`PERBAIKI-KEY.bat`**, atau manual:
> ```bat
> copy .env.example .env
> php artisan key:generate
> php artisan config:clear
> ```
> Jika folder ini adalah folder baru hasil ekstrak ZIP revisi, salin juga isi `.env` lama dari folder sebelumnya (khususnya `GOOGLE_PLACES_API_KEY` dan `NOMINATIM_EMAIL`) agar fitur AI Kost Manager tetap berfungsi.

## Akun demo (dibuat seeder)

| Peran | Login | Password |
|---|---|---|
| Administrator | `admin` atau `admin@maply.test` | `Admin@Maply2026` (ubah lewat `MAPLY_ADMIN_PASSWORD` di `.env`) |
| Pengguna | `meisya` atau `test@example.com` | `password` |

**Ganti password sebelum dipasang di server publik** dan set `APP_ENV=production`, `APP_DEBUG=false` (kotak akun demo di halaman login hanya tampil saat `APP_ENV=local`).

## Fitur
- Registrasi, login (email **atau** username), logout; password di-hash (bcrypt); sesi + `auth` middleware; throttle 10 percobaan/menit.
- Peran `admin` dan `user`. Hanya admin yang bisa tambah/edit/hapus kos (Gate `kelola-kos`, middleware `can:`, plus `authorize()` di FormRequest). Peran tidak bisa diisi lewat form.
- **Dua mode warna (gelap/terang)** — toggle ikon bulan/matahari di topbar dan halaman login; pilihan tersimpan di `localStorage`, bawaan mengikuti `prefers-color-scheme`, diterapkan lewat skrip kecil di `<head>` tanpa kedipan; semua halaman (termasuk peta Leaflet, chatbot, halaman auth/error, welcome animation) ikut tema, dengan kontras teks WCAG AA di kedua mode.
- **Latar kotak-kotak transparan** (murni CSS, garis variabel per tema) di halaman utama dan auth.
- **Moy AI** — asisten chat di pojok kanan bawah (logo tersendiri di `public/images/moy-ai-logo.svg`). Menjawab pertanyaan umum dan mencari kos dari database via Gemini.
- Pencarian nama/alamat/kecamatan/kota; filter wilayah (Palembang / Indralaya), harga, jenis, fasilitas, kecamatan, radius, jenis data; urut terdekat/termurah/termahal/terbaru/nama; pagination 9 per halaman.
- **Menu Ampera** di sidebar (jembatan Ampera/BKB sebagai titik acuan jarak) — juga tersedia di filter titik acuan dan peta.
- **Daftar kampus dari database** (tabel `kampus` + `kampus_lokasi`, 110 institusi Palembang & Ogan Ilir) — halaman `/kampus` dengan pencarian + filter negeri/swasta, jenis, wilayah, kecamatan, status koordinat, dan paginasi; kartu bulat berlogo di dashboard (fallback inisial untuk yang belum punya logo); titik kampus terverifikasi tampil di peta dengan popup "Cari kos dekat sini" + tombol layer "Kampus".
- Peta OpenStreetMap dengan *marker cluster* (marker berdekatan dikelompokkan dan terbuka saat di-zoom); penanda dapat diklik (nama, alamat, harga, jenis, fasilitas, telepon, jarak, status data, tautan detail, Google Maps); tombol "Peta" pada kartu; peta mengikuti filter; data tanpa koordinat dilewati tanpa error.
- CRUD admin dengan validasi, pemilih titik di peta, unggah foto, dialog konfirmasi hapus.
- Lupa password: **belum tersedia** (email belum dikonfigurasi); halaman menjelaskannya.
- Favorit disimpan di browser (localStorage), bukan di database.

## Data kos hasil pengumpulan (Oktober 2026)
`database/data/kos_hasil_pengumpulan.json` berisi **230 kos nyata** (123 Palembang, 107 Indralaya) dari daftar Lokanesia (nama, alamat, telepon, dan pin Google Maps tiap listing). Data diimpor otomatis oleh `KosSeeder` (atau lewat `database/maply_import_kos.sql`). Rincian jumlah ada di `LAPORAN-HASIL.md`; salinan Excel di `MAPLY_Data_Kost.xlsx`.
Harga, fasilitas, dan (untuk 200 data) jenis kos **tidak ada di sumber** sehingga dikosongkan; setiap data memiliki `catatan_data` yang menjelaskan apa yang belum lengkap. Seeder data contoh kini **nonaktif secara bawaan** (`MAPLY_SEED_CONTOH=false`).

## Data kos — baca ini
Setiap kos punya **status data**:
- **Perlu dikonfirmasi** — 8 listing lama + 230 listing baru (lihat atas). Yang lama: 8 listing dari sumber publik (tautan sumber tersimpan di tiap data). Harga/fasilitas yang tidak tercantum di sumber dikosongkan, tidak ditebak. Beberapa iklan sumber berumur beberapa tahun.
- **Terverifikasi** — status untuk admin setelah memastikan langsung. Belum ada data yang berstatus ini.
- **Data contoh** — 30 data dari versi awal Maply, hanya untuk demo fitur (bukan listing nyata). Lewati dengan `MAPLY_SEED_CONTOH=false`.

**Koordinat 8 listing publik dikosongkan** karena tidak ada koordinat bangunan yang dapat diverifikasi; kos tersebut tampil di daftar dan punya tautan pencarian Google Maps. Lengkapi lewat *Kelola Kos → Edit* (klik peta). Data tidak diambil dari Google Maps. Tidak ada nomor telepon pemilik yang disalin.

Titik acuan jarak berputar pada dua sumber: **titik non-kampus** (Pusat Kota/Ampera, Demang, Jakabaring) tetap di `config/maply.php`, sedangkan **kampus** dibaca dari tabel `kampus` + `kampus_lokasi` (sumber tunggal, lihat `app/Support/KampusDirectory.php`). Kampus tanpa koordinat terverifikasi tetap tampil di daftar dengan penanda "koordinat belum terverifikasi" dan tidak dipasang sebagai titik peta.

## Pengujian
```bash
php artisan test
```
Tes ada di `tests/Feature` (autentikasi, pencarian/filter/pagination/data kosong, hak akses CRUD admin, pipeline AI end-to-end, direktori kampus + seeder idempotent) dan `tests/Unit` (validasi kandidat AI).

## Struktur singkat
`app/Http/Controllers` (Auth, Dashboard, Kos, Admin/Kos) · `app/Http/Requests/KosRequest.php` · `app/Models` · `database/migrations`, `database/seeders`, `database/data/*.json` · `resources/views` · `public/css/maply.css`, `public/js/maply*.js` · `lang/id/validation.php`.


## AI Kost Manager & Moy AI

Panduan instalasi, integrasi Google Places API, trust score, antrean, scheduler, persetujuan admin, dan chatbot tersedia pada [PANDUAN-AI-KOST-MANAGER.md](PANDUAN-AI-KOST-MANAGER.md). Asisten chat bernama **Moy AI** (identitas visual: `public/images/moy-ai-logo.svg`) — nama aplikasi tetap "Maply".

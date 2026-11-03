# Catatan Perbaikan & Status Pengujian

## Masalah yang ditemukan di versi awal
1. **Login/DB:** `database.sqlite` bawaan hanya berisi tabel bawaan Laravel — tidak ada tabel `kos` dan **0 pengguna**, sehingga akun demo tidak ada bila memakai SQLite; `.env.example` malah memakai MySQL. Login hanya lewat email; tidak ada registrasi.
2. **Loop redirect:** `/` mengarah ke login, sedangkan middleware `guest` mengarahkan pengguna yang sudah login ke `/` → `ERR_TOO_MANY_REDIRECTS`. Diperbaiki di `bootstrap/app.php`.
3. **Pesan validasi** akan tampil sebagai kunci mentah (`validation.required`) karena locale `id` tanpa file bahasa. Ditambah `lang/id/validation.php`.
4. **Titik acuan "UNSRI Palembang" salah** (lihat README).
5. Teks `\n` literal tercetak di halaman daftar & detail; nama kos disisipkan ke HTML popup peta tanpa escape (XSS); rating "4,x" dan ulasan "Andi/Salsa" dikarang; foto stok Unsplash dipakai seolah foto kos; koordinat/harga wajib terisi.
6. Tidak ada peran/hak akses, tidak ada tombol tambah/edit/hapus, pagination, pengurutan, atau pencarian nama.

## Yang diubah
Semua tampilan ditulis ulang (palet hijau hutan–arang–krem–cokelat), skema database dilengkapi (koordinat/harga nullable, status data, sumber, foto, kontak, relasi pembuat), CRUD admin, peta lengkap, tes otomatis. `welcome.blade.php` bawaan Laravel (tidak dipakai) dan dua README lama dihapus/digabung ke README.md. Fitur lama dipertahankan: favorit, bagikan, WhatsApp, Google Maps, filter, reset, logout.
Rating & ulasan palsu dihapus (bukan diganti fitur ulasan).

## STATUS PENGUJIAN — JUJUR
Lingkungan tempat kode ini ditulis **tidak memiliki PHP/Composer dan tanpa internet**, sehingga **aplikasi belum pernah dijalankan** dan `php artisan test` **belum dieksekusi**. Yang sudah diperiksa:
- ✔ Keseimbangan kurung semua file PHP dan pasangan direktif Blade (pemeriksa skrip, bukan interpreter PHP).
- ✔ Sintaks JavaScript (`node --check`) untuk semua JS dan skrip inline.
- ✔ Data JSON: tipe valid, koordinat contoh dalam wilayah Palembang, nama unik, listing publik punya sumber.
- ✔ CSS pada halaman statis tiruan: tanpa overflow horizontal di 1366 px dan 390 px; layout rapi.
- ✘ **Belum teruji:** login/registrasi/logout, hak akses, CRUD, unggah foto, query pencarian/filter, migrasi, seeder, peta Leaflet (CDN tidak terjangkau), console browser, `storage/logs`.
Mohon jalankan `php artisan migrate:fresh --seed && php artisan test` lalu telusuri aplikasi; bila ada error, kirimkan pesannya agar saya perbaiki.

## Belum ada / butuh konfigurasi
Reset password via email (butuh `MAIL_*`), koordinat & foto kos nyata, verifikasi data, peran pemilik kos, ulasan, favorit di database, titik acuan UIN Raden Fatah/Polsri/UMP (tambahkan di `config/maply.php` setelah koordinatnya diverifikasi).


---
# Pembaruan 17 Oktober 2026 — integrasi data & hasil uji nyata
PHP 8.3 + Composer kini tersedia di lingkungan kerja, sehingga aplikasi **benar-benar dijalankan** (SQLite dan MariaDB 10.11). Bug yang ditemukan dan diperbaiki:
1. **ParseError Blade** pada `dashboard`, `kos/index`, dan `kos/show`: `@json(collect(...)->only([...]))` tidak dapat di-parse Blade, sehingga ketiga halaman utama error 500. Diganti dengan variabel `$refJs` yang disiapkan lewat `@php`.
2. **Route admin `{ko}` bukan `{kos}`**: `Route::resource('kos')` menyingkat parameter menjadi `ko`, sehingga edit/hapus kos tidak ter-binding (formulir edit kosong, hapus tidak menghapus apa pun). Diperbaiki dengan `->parameters(['kos' => 'kos'])`.
3. Folder `bootstrap/cache` dan `storage/framework/*` tidak ada di ZIP (kini disertakan).
4. Titik acuan UNSRI Indralaya (-3.22566, 104.65025) meleset ±1,5 km; diganti pin Google Maps Auditorium UNSRI (-3.2146643, 104.647878). Ditambah titik acuan UIN Raden Fatah.
Perubahan fitur: migrasi `2026_10_17_000000` (jenis kos "Belum diketahui", kolom `catatan_data`, status koordinat "google"), filter wilayah, marker cluster, popup lengkap, penghitung marker, penanda "Data belum lengkap", filter admin "belum ada koordinat/harga". Tes otomatis: 30 lulus (362 assertion). Peta diuji di jsdom: 210 marker dibuat dari 210 kos berkoordinat.

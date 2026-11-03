# Maply — AI Kost Manager & Moy AI

## Implementasi yang tersedia

- Pencarian kandidat tempat melalui Google Places API (New) untuk Palembang dan Indralaya.
- Proses pencarian dijalankan melalui Laravel Queue (`SearchKosPlacesJob`).
- Kandidat disimpan di `ai_kos_candidates`; sumber/evidence disimpan di `ai_candidate_sources`; histori proses di `ai_search_runs`.
- Trust Score deterministik 0–100 berdasarkan kelengkapan bukti Google Places. Skor bukan jaminan bahwa tempat tersebut benar-benar kost.
- Deteksi duplikat memakai Google Place ID serta kecocokan nama + alamat terhadap kandidat dan listing Maply.
- Persetujuan admin menerbitkan data yang diperiksa ke tabel `kos` yang sudah ada.
- Chatbot **Moy AI** (logo: `public/images/moy-ai-logo.svg`) mencari listing Maply berstatus `terverifikasi` dan menjawab pertanyaan umum lewat Gemini; Moy AI tidak mengarang listing dan selalu memperkenalkan dirinya sebagai Moy AI.
- Hasil tanpa harga, telepon, foto, atau koordinat tetap kosong/tidak diketahui. Google Places API pada implementasi ini tidak mengembalikan harga sewa kost atau fasilitas kamar secara andal.

## Batasan dan kejujuran data

Google Places merupakan direktori tempat/bisnis, bukan direktori khusus kost. Hasil pencarian bisa berisi tempat yang bukan kost. Karena itu semua hasil adalah **kandidat** dan perlu pemeriksaan manusia. Satu sumber Google Places tidak membuktikan status operasional atau jenis usaha. Trust Score adalah skor kelengkapan metadata sumber, bukan probabilitas kebenaran.

Integrasi saat ini hanya menggunakan Google Places API (New). Google Search, Facebook, Instagram, situs properti lain, dan scraping halaman web tidak diaktifkan. Jangan melakukan scraping yang melanggar ketentuan situs. Foto tidak diunduh/disalin; Places Photo memerlukan pemrosesan dan ketentuan atribusi tersendiri. Mode auto-publish belum diaktifkan; semua publikasi perlu persetujuan admin.

Moy AI (chatbot) memfilter kota, harga, tipe, dan beberapa fasilitas langsung dari database Maply sebelum hasilnya dikirim ke Gemini sebagai konteks; jawaban generatif dibatasi data tersebut. Kunci yang dipakai adalah `GEMINI_API_KEY` (lihat `config/services.php`).

## Persyaratan eksternal

1. Buat project di Google Cloud.
2. Aktifkan **Places API (New)** dan billing sesuai ketentuan Google.
3. Buat API Key, batasi key ke Places API dan batasi penggunaan sesuai lingkungan.
4. Masukkan ke `.env`:
   `GOOGLE_PLACES_API_KEY=isi_api_key_anda`
5. Jalankan `php artisan config:clear`.

Google dapat mengenakan biaya sesuai tarif dan kuota akun. Periksa harga dan kuota terbaru di dokumentasi Google Maps Platform sebelum mengaktifkan API.

## Instalasi di XAMPP / localhost

1. Ekstrak ZIP ke `C:\xampp\htdocs\maply`.
2. Salin `.env.example` menjadi `.env`.
3. Atur koneksi MySQL:
   - `DB_CONNECTION=mysql`
   - `DB_HOST=127.0.0.1`
   - `DB_PORT=3306`
   - `DB_DATABASE=maply`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=`
4. Buat database `maply` melalui phpMyAdmin.
5. Jalankan:
   ```bash
   composer install
   php artisan key:generate
   php artisan storage:link
   php artisan migrate
   php artisan config:clear
   ```
6. Atur `GOOGLE_PLACES_API_KEY` pada `.env`.
7. Jalankan server:
   ```bash
   php artisan serve
   ```
8. Jalankan worker pada terminal kedua:
   ```bash
   php artisan queue:work --tries=2 --timeout=100
   ```
9. Untuk pencarian berkala setiap hari pukul 07.00, aktifkan Laravel Scheduler. Di terminal development dapat memakai `php artisan schedule:work`; untuk Windows/XAMPP produksi, buat Task Scheduler yang menjalankan `php artisan schedule:run` setiap menit. Pencarian berkala melewati jadwal jika API Key belum diisi atau pencarian lain masih berjalan.
10. Login menggunakan akun admin yang sudah ada pada instalasi Maply. Menu **AI Kost Manager** hanya muncul untuk admin.

Untuk pengujian lokal tanpa Google API, fitur pencarian internet akan memberi pesan bahwa API Key belum diatur. Pencarian listing yang sudah ada melalui chatbot tetap dapat digunakan.

## Struktur database tambahan

- `ai_search_runs`: riwayat pencarian, status antrean, jumlah hasil/duplikat, dan pesan.
- `ai_kos_candidates`: data sementara, raw response, trust score, alasan verifikasi, status review, dan relasi listing hasil publikasi.
- `ai_candidate_sources`: catatan sumber dan bukti per kandidat.

Jalankan migrasi dengan `php artisan migrate`. Migrasi tidak menghapus tabel `kos` yang lama.

## Endpoint admin

Semua endpoint di bawah dilindungi middleware `auth` dan gate `can:kelola-kos`:
- `GET /admin/ai-kos`
- `POST /admin/ai-kos/search`
- `GET /admin/ai-kos/{candidate}`
- `POST /admin/ai-kos/{candidate}/approve`
- `POST /admin/ai-kos/{candidate}/reject`
- `DELETE /admin/ai-kos/{candidate}`

Chatbot:
- `POST /ai/chat` — wajib login, rate limited, hanya membaca data terverifikasi.

## Pengujian

Sintaks PHP dan pendaftaran route telah diperiksa. Migrasi dan automated tests perlu dijalankan di mesin XAMPP yang memiliki ekstensi PHP `pdo_mysql` (atau `pdo_sqlite` bila memakai SQLite), `mbstring`, `dom`, dan `xmlwriter`. Lingkungan pemeriksaan paket ini tidak memiliki ekstensi tersebut sehingga migrasi database dan PHPUnit tidak dapat dieksekusi di sini.

## Pencarian terstruktur & status pipeline (revisi 2026-11)

Formulir pencarian di `admin/ai-kos` kini menerima input spesifik: wilayah, kecamatan, "dekat kampus" (dropdown dari tabel `kampus`), rentang harga, tipe kos, jumlah maksimal hasil, dan sumber data. Kriteria tersimpan pada `ai_search_runs.options.kriteria`, dipakai Tahap 2 sebagai konteks ekstraksi (tanpa mengizinkan karangan) dan Tahap 3 sebagai catatan relevansi pada kandidat.

Status run ditampilkan sebagai badge (queued / running / completed / failed) beserta tombol **Coba ulang** yang mengulang run dengan kriteria yang sama. Bila `GEMINI_API_KEY` kosong, halaman menampilkan peringatan jelas dan pipeline tetap jalan hanya dengan gerbang deterministik. Bila `QUEUE_CONNECTION` bukan `sync`, UI menampilkan instruksi `php artisan queue:work`.

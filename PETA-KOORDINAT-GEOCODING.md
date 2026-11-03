# Pembaruan fitur koordinat Maply

## Yang ditambahkan
- Migrasi `2026_10_27_000001_add_geocoding_metadata_to_kos_table.php` menambahkan kolom metadata geocoding tanpa menghapus tabel/data.
- Perintah `php artisan maply:geocode-kos` mencari kandidat koordinat kost yang masih kosong lewat Nominatim.
- Halaman admin `/admin/peta-koordinat` menampilkan ringkasan koordinat dan daftar yang perlu diperiksa. Halaman hanya dapat dibuka oleh pengguna yang memiliki izin `kelola-kos`.
- Metadata mencatat status, sumber, skor kecocokan, waktu, dan query yang dipakai.
- Koordinat lama yang sudah terisi tidak ditimpa. Koordinat hasil pencarian ditandai `perkiraan`; sistem tidak mengubah `status_data` menjadi `terverifikasi`.

## Cara menjalankan di XAMPP
1. Buat backup database `maply` dari phpMyAdmin: pilih database `maply` → Export → Quick → SQL. Jangan jalankan `migrate:fresh`.
2. Ekstrak ZIP, lalu buka terminal pada folder `maply` (folder yang memiliki `artisan`).
3. Buat `.env` dari `.env.example` jika belum ada. Isi koneksi MySQL XAMPP: `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=maply`, `DB_USERNAME=root`, `DB_PASSWORD=` sesuai konfigurasi lokal. Jangan membagikan `.env`.
4. Pastikan tabel `kos` sudah tersedia dan impor `maply_import_kos_tambahan.sql` melalui phpMyAdmin bila 145 data tambahan belum dimasukkan.
5. Jalankan `php artisan migrate`. Perintah ini menambah metadata geocoding; tidak menjalankan reset database.
6. Jalankan batch kecil: `php artisan maply:geocode-kos --limit=20`. Jalankan kembali untuk batch selanjutnya. Untuk mencoba lagi data yang gagal: `php artisan maply:geocode-kos --limit=20 --retry`.
7. Atur `NOMINATIM_EMAIL` pada `.env` dengan email kontak operator yang valid. Perintah menerapkan jeda minimal 1,1 detik antarpemintaan, mengikuti batas layanan Nominatim publik.
8. Login dengan akun admin, buka `/admin/peta-koordinat`, lalu buka tautan “Periksa / edit koordinat” untuk memeriksa pin di peta. Jangan menandai kost terverifikasi sebelum titiknya diperiksa terhadap alamat/sumber yang dapat dipercaya.

## Status dan batasan penting
- `candidate` berarti kandidat cocok menurut pencocokan kota/alamat; bukan verifikasi lapangan. `needs_review`, `not_found`, dan `error` harus diperiksa manual.
- Data tanpa koordinat tetap tersimpan dan muncul di daftar; hanya data berkoordinat valid yang dapat menjadi marker.
- Nominatim bisa gagal, tidak mengenali alamat gang/nomor lokal, atau menghasilkan titik jalan/kawasan. Perbaikan manual tetap diperlukan.
- Geocoding sengaja tidak otomatis mengubah status verifikasi data kost.
- Pengujian yang dijalankan pada paket: pemeriksaan sintaks PHP untuk seluruh file PHP yang diubah, `php artisan list`, `php artisan route:list`, dan bantuan perintah geocoding. `php artisan test` belum bisa berjalan pada lingkungan build ini karena ekstensi PHP `dom`, `mbstring`, dan `xmlwriter` tidak tersedia. Permintaan geocoding langsung tidak dijalankan karena membutuhkan koneksi internet dan akan mengubah database yang dikonfigurasi.

## Geocoding kampus (3 November 2026)

Melengkapi poin 8 revisi: 94 institusi di `database/data/kampus.json` yang belum punya entri di
`kampus_lokasi.json` di-geocode dengan **Nominatim/OpenStreetMap** (jeda 1,15 detik/permintaan,
`countrycodes=id`, beberapa varian kueri: nama + alamat, nama + kota, singkatan umum seperti
AKBID/STIKES/STIE).

- **Diterima: 10 titik** (presisi `kawasan`, wajib lolos: koordinat dalam bbox Palembang/Ogan Ilir,
  `display_name` menyebut kota/kabupaten yang benar, dan token nama institusi cocok — token
  geografis generik seperti "Sumatera Selatan" dikecualikan). Setiap baris menyimpan
  `sumber_koordinat` (id objek OSM), `metode_koordinat`, dan `verifikasi_koordinat`
  (tautan OSM + tanggal + catatan kecocokan). Kode baris: `*{kampus}-1`.
- **Tidak diterima: 84 institusi** — tidak ditemukan di OSM sama sekali (kebanyakan Sekolah Tinggi
  dan Akademi kecil) sehingga TIDAK diberi titik peta. Mereka tetap tampil di halaman `/kampus`
  dan kartu dashboard dengan penanda "Koordinat belum terverifikasi".
  Daftar lengkapnya: `database/data/kampus_lokasi_rejected.json`.
- Jejak proses: `database/data/kampus_lokasi_geocoded.json` (hasil sebelum digabung) dan
  `database/data/geocode_log.txt`.
- Logo kampus TIDAK diunduh dari sumber luar (izin/lisensi tidak jelas). Kampus tanpa berkas logo
  di `public/logos/kampus/` tampil sebagai lingkaran inisial berwarna (hash dari kode kampus).

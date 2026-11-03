# Laporan Hasil — Integrasi Data Kost ke MAPLY (diperbarui 22 Oktober 2026, sesi 2)

## Ringkasan jumlah akhir (dihitung langsung dari database hasil seeding)
| Keterangan | Indralaya | Palembang |
|---|---:|---:|
| Jumlah data awal (sebelum sesi ini) | 107 | 131 |
| Data baru yang ditambahkan pada sesi ini | **30** | **28** |
| **Jumlah data unik akhir** | **137** | **159** |
| Target | 200 | 300 |
| Kekurangan target | 63 | 141 |
| **Total keseluruhan** | **296** dari 500 target | — |

## Apa yang dilakukan pada sesi ini (22 Oktober 2026, sesi 2)
- **Pencarian sumber publik** dilakukan di direktori Lokanesia (halaman yang **belum** di-scrape pada sesi 1):
  - `lokanesia.com/info-kost-di-indralaya-ogan-ilir/` — 28 Info Kost di Indralaya (Kec. Indralaya proper, BUKAN Indralaya Utara): mencakup kawasan Indralaya Mulia, Tanjung Seteko, Taman, Sakatiga, Tanjung Gelam, Indralaya Indah yang sebelumnya belum tercakup.
  - `lokanesia.com/info-kost-di-indralaya-utara-ogan-ilir/2/`, `/3/`, `/4/` — halaman lanjutan daftar 115 Info Kost di Indralaya Utara.
  - `lokanesia.com/kost-dekat-unsri-indralaya/2/`, `/3/` — halaman lanjutan daftar 95 Kost dekat UNSRI Indralaya.
  - `lokanesia.com/kost-putri-dekat-unsri-indralaya/` — daftar 15 Kost Putri dekat UNSRI Indralaya.
  - `lokanesia.com/kost-dekat-polsri-palembang/` — 110 Kost dekat POLSRI Palembang (Bukit Lama/Lorok Pakjo, sebagian besar sudah ada di data lama; 4 baru ditambahkan).
  - `lokanesia.com/kost-dekat-um-palembang/` — 80 Kost dekat Universitas Muhammadiyah Palembang (Seberang Ulu I/II, Plaju — belum tercakup sebelumnya; 19 baru ditambahkan).
  - `lokanesia.com/kost-putri-dekat-poltekkes-palembang/` — 21 Kost Putri dekat Poltekkes Palembang (Kemuning, Sukarami; 5 baru ditambahkan).
- **Total listing terkumpul dari sesi ini:** 76 (sebelum dedup) → **58 lolos dedup** (30 Indralaya + 28 Palembang).
- **Penghapusan duplikat:** 18 entri Palembang dihapus karena terdeteksi duplikat penuh (nama+telepon sama) atau duplikat lunak (nama sama, alamat serupa, kedua-duanya tanpa telepon) dengan data yang sudah ada.

## Yang perlu dipahami dengan jujur
- **Target 500 belum tercapai.** Hanya **296** data terkumpul (137 Indralaya + 159 Palembang). Kekurangan: 63 Indralaya + 141 Palembang = **204 data**.
- **Mengapa belum mencapai target?**
  1. Akses ke tool web (WebFetch/WebSearch) terkena batas pemakaian (rate-limit) di tengah sesi — hanya 1 halaman per 3-4 menit yang berhasil diakses pada paruh kedua sesi.
  2. Google Maps, Mamikos, dan sebagian besar situs iklan properti berbasis JavaScript tidak dapat diakses dari lingkungan kerja (sama seperti catatan sesi 1).
  3. Banyak listing di halaman-halaman Lokanesia yang di-fetch ternyata sudah ada di database (ganda nama+telepon dengan data lama), sehingga jumlah data BENAR-BARU lebih kecil dari jumlah listing di halaman sumber.
- **Tidak ada data fiktif.** Seluruh 58 entri baru diambil dari listing nyata di Lokanesia. Nama, alamat, dan telepon sesuai sumber; URL sumber disimpan pada kolom `sumber_url`. Harga, fasilitas, jenis kost, dan koordinat SENGAJA DIKOSONGKAN bila sumber tidak mencantumkannya — sesuai aturan "Dilarang membuat data kost fiktif atau mengarang informasi."
- **Koordinat entri baru: NULL semua.** Lokanesia tidak mencantumkan koordinat di teks listing — koordinat hanya tersedia melalui link "Lihat peta lokasi" yang memerlukan JavaScript + Google Maps (tidak terjangkau dari lingkungan kerja). Maka 58 entri baru **TIDAK muncul sebagai marker di peta** — hanya muncul di daftar dan hasil pencarian. Akan muncul sebagai marker setelah koordinat terverifikasi.
- **Tipe 'Belum diketahui'** dipakai untuk seluruh 58 entri baru karena Lokanesia tidak memuat info Putra/Putri/Campur di listing. Hanya 1 entri (Kost Putri "CERIA") yang namanya menyiratkan "Putri", tetapi tidak ditandai demikian karena bukan pernyataan resmi dari sumber.
- **Status verifikasi:** seluruh 296 data berstatus `perlu_konfirmasi` (tidak ada yang `terverifikasi`). Konfirmasi harga, ketersediaan, jenis kost, dan koordinat lapangan harus dilakukan langsung ke pemilik.

## Statistik data setelah pembaruan
| Ukuran | Jumlah |
|---|---|
| Total kost di database | **296** |
| Punya koordinat (muncul sebagai marker peta) | **229** (122 Palembang + 107 Indralaya) — tidak berubah dari sesi 1 |
| Tanpa koordinat (tersimpan, muncul di daftar saja) | **67** (37 Palembang + 30 Indralaya) |
| Punya nomor telepon | 240 |
| Punya harga | 2 |
| Punya fasilitas | 4 |
| Tipe diketahui (Putra/Putri/Campur) | 38 |
| Status `terverifikasi` | 0 |
| Status `perlu_konfirmasi` | 296 |

## Sebaran per kecamatan
### Indralaya (137 total)
| Kecamatan | Total | Berkoordinat | Ber telepon |
|---|---:|---:|---:|
| Indralaya Utara | 107 | 107 | 89 |
| Indralaya (proper) | 30 | 0 | 20 |

### Palembang (159 total)
| Kecamatan | Total | Berkoordinat | Ber telepon |
|---|---:|---:|---:|
| Ilir Barat I | 110 | 109 | 90 |
| Kemuning | 28 | 13 | 24 |
| Seberang Ulu II | 14 | 0 | 12 |
| Plaju | 3 | 0 | 3 |
| Seberang Ulu I | 3 | 0 | 3 |
| Sukarami | 1 | 0 | 1 |

## Daftar data yang masih kekurangan informasi
- **204 data kekurangan untuk mencapai target 500** (lihat tabel atas).
- **67 data tanpa koordinat** (37 Palembang + 30 Indralaya) — tidak muncul sebagai marker peta.
- **294 data tanpa harga** (hanya 2 yang punya harga dari `kos_publik.json` lama).
- **292 data tanpa fasilitas** (hanya 4 yang punya fasilitas dari `kos_publik.json` lama).
- **258 data dengan tipe 'Belum diketahui'** (hanya 38 yang punya tipe jelas).
- **56 data tanpa nomor telepon** (296 − 240).

## Pengujian yang dijalankan pada sesi ini (apa adanya)
- **Dijalankan:**
  1. Validasi JSON: 288 entri `kos_hasil_pengumpulan.json` + 8 entri `kos_publik.json` = 296 — semua lolos cek schema (tipe, status_data, kota).
  2. Impor `maply_import_kos.sql` ke database SQLite uji: **305 INSERT** (296 kos + 9 fasilitas) — **0 error**, **0 duplikat (nama_kos, alamat)**, **0 entri salah kategori** (Indralaya ↔ Palembang).
  3. `php -l` tidak dijalankan karena PHP tidak terpasang di lingkungan kerja.
- **Tidak dijalankan:**
  - `php artisan test` — PHPUnit tidak terpasang di folder `vendor` ZIP ini (tanpa paket dev). Angka di `tests/Feature/DataKostTest.php` sudah disesuaikan ke 288 / 137 / 151, tetapi tes **belum dieksekusi**; jalankan `composer install` lalu `php artisan test` di komputer Anda.
  - Tampilan visual di peramban dan tile OpenStreetMap (tidak terjangkau dari lingkungan kerja).
- **Migrasi/seeder di komputer Anda:** jalankan `php artisan migrate:fresh --seed` (SQLite/MySQL) → 296 kost akan masuk. Seeder `updateOrCreate` memastikan idempotensi — dijalankan ulang tidak menggandakan data.

## File yang diperbarui pada sesi ini
1. `database/data/kos_hasil_pengumpulan.json` — ditambah 58 entri (was 230, now **288**).
2. `database/maply_import_kos.sql` — diregenerasi sepenuhnya untuk **296 kost** + 9 fasilitas (was 238 kost).
3. `MAPLY_Data_Kost.xlsx` — diregenerasi: sheet "Data Kost" berisi 296 baris, sheet "Ringkasan" dengan statistik kecamatan/koordinat/sumber.
4. `tests/Feature/DataKostTest.php` — angka di `test_semua_data_json_masuk_database_tanpa_terlewat` diperbarui ke 288 / 137 / 151.
5. `LAPORAN-HASIL.md` — dokumen ini.

## File yang TIDAK diubah pada sesi ini (karena sudah benar dari sesi 1)
- `app/Models/Kos.php` — skema sudah mendukung `catatan_data`, `tipe='Belum diketahui'`, koordinat nullable.
- `database/migrations/*` — tidak ada perubahan skema.
- `app/Http/Controllers/KosController.php` — pencarian/filter/peta sudah benar: marker hanya tampilkan kos dengan koordinat; kos tanpa koordinat tetap muncul di daftar.
- `config/maply.php` — titik acuan tidak berubah.
- `database/seeders/KosSeeder.php` — `updateOrCreate(['nama_kos', 'alamat'], $row)` sudah idempoten.

## Catatan untuk sesi lanjutan
Untuk mencapai target 500, dibutuhkan:
- 63 Indralaya baru — bisa dicari di halaman lanjutan Lokanesia (`/info-kost-di-ogan-ilir/`, halaman 5+ jika ada) atau sumber lain seperti AyoJualRumah, JituProperty untuk kawasan Tanjung Seteko/Sakatiga/Indralaya Selatan.
- 141 Palembang baru — halaman lanjutan Lokanesia (`/kost-dekat-polsri-palembang/2..5/`, `/kost-dekat-um-palembang/2..4/`, `/kost-dekat-uin-raden-fatah-palembang/`, `/info-kost-di-palembang/`) — paginasi halaman ini sudah terlihat tetapi belum sempat di-fetch karena batas rate-limit tool.

Sampai batas tool di-reset, tambahan data tidak dapat dilakukan dari lingkungan kerja ini. Silakan jalankan ulang perintah untuk sesi lanjutan jika ingin menambah data lebih.

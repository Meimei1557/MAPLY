<?php

/*
 * PERBAIKI TEST MAPLY — versi otomatis.
 *
 * Latar belakang: test "pencarian osm menjalankan pipeline lengkap hingga validasi"
 * gagal karena kunci Gemini milik .env ikut terbaca saat phpunit berjalan, sehingga
 * pipeline memanggil API sungguhan dan kandidat mendapat verdict sungguhan.
 * Solusinya ada 2 perubahan kecil:
 *   1) phpunit.xml                      -> sembunyikan GEMINI_API_KEY saat test;
 *   2) tests/Feature/AiPipelineTest.php -> kunci dinolkan + tolak permintaan HTTP liar.
 *
 * Cara pakai:
 *   1. Simpan file ini di folder proyek: C:\xampp\htdocs\maplylolos\perbaiki-test.php
 *      (satu folder dengan file "artisan").
 *   2. Tutup editor yang sedang membuka kedua file tsb (biar tidak menimpa lagi).
 *   3. Jalankan:  php perbaiki-test.php
 *   4. Lanjutkan dengan:
 *        php artisan config:clear
 *        php artisan test --filter AiPipelineTest
 *
 * Script ini aman dijalankan berulang; perubahan yang sudah ada akan dilewati.
 */

echo "=== Perbaiki test Maply ===\n\n";
$berhasil = true;

// --------------------------------------------------------------- 1/2: phpunit.xml
$path1 = __DIR__ . DIRECTORY_SEPARATOR . 'phpunit.xml';
echo "[1/2] Memeriksa: $path1\n";

if (!is_file($path1)) {
    $berhasil = false;
    echo "      GAGAL: file tidak ditemukan. Pastikan script ini ada di ROOT proyek\n";
    echo "      (satu folder dengan file 'artisan').\n\n";
} elseif (strpos((string) file_get_contents($path1), 'GEMINI_API_KEY') !== false) {
    echo "      OK: baris GEMINI_API_KEY sudah ada, dilewati.\n\n";
} else {
    $isi = (string) file_get_contents($path1);

    if (substr_count($isi, '</php>') !== 1) {
        $berhasil = false;
        echo "      GAGAL: penanda '</php>' tidak ditemukan / ditemukan lebih dari sekali.\n\n";
    } else {
        $isi = str_replace(
            '</php>',
            "    <env name=\"GEMINI_API_KEY\" value=\"\" force=\"true\"/>\n    </php>",
            $isi
        );
        file_put_contents($path1, $isi);
        echo "      SELESAI: baris GEMINI_API_KEY ditambahkan sebelum </php>.\n\n";
    }
}

// ------------------------------------------- 2/2: tests/Feature/AiPipelineTest.php
$path2 = __DIR__ . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'Feature'
    . DIRECTORY_SEPARATOR . 'AiPipelineTest.php';
echo "[2/2] Memeriksa: $path2\n";

if (!is_file($path2)) {
    $berhasil = false;
    echo "      GAGAL: file tidak ditemukan. Pastikan script ini ada di ROOT proyek.\n\n";
} elseif (strpos((string) file_get_contents($path2), 'preventStrayRequests') !== false) {
    echo "      OK: setUp() sudah diperbaiki, dilewati.\n\n";
} else {
    $isi = (string) file_get_contents($path2);
    $anchor = "config(['ai_pipeline.http_retry' => [10, 10, 10]]);";

    if (substr_count($isi, $anchor) !== 1) {
        $berhasil = false;
        echo "      GAGAL: baris acuan pada setUp() tidak ditemukan. Kemungkinan file\n";
        echo "      sudah berubah. Kirim isi file ini ke asisten untuk diperiksa.\n\n";
    } else {
        $sisipan = $anchor . <<<'BLOK'


        // Test harus hermetik: kunci Gemini milik .env komputer lokal TIDAK boleh
        // terbaca di sini, kalau tidak pipeline (Tahap 2 & 3) akan memanggil API
        // sungguhan dan kandidat mendapat verdict sungguhan. Test yang memang
        // butuh AI mengatur kuncinya sendiri, mis. config(['services.gemini.key' => 'kunci-uji']).
        config(['services.gemini.key' => null]);

        // Semua HTTP eksternal wajib difake; permintaan liar langsung digagalkan.
        Http::preventStrayRequests();
BLOK;
        file_put_contents($path2, str_replace($anchor, $sisipan, $isi));
        echo "      SELESAI: setUp() kini menolkan kunci Gemini + menolak permintaan liar.\n\n";
    }
}

echo $berhasil
    ? "=== Selesai tanpa galat. Lanjutkan dengan: ===\n"
        . "    php artisan config:clear\n"
        . "    php artisan test --filter AiPipelineTest\n"
        . "Hasil yang diharapkan: 18 passed, durasi sekitar 0,5 detik.\n"
    : "=== Ada langkah yang GAGAL. Salin pesan di atas dan kirim ke asisten. ===\n";

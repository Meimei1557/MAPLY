-- ============================================================
-- Hapus 5 kampus tambahan yang sudah ter-import
-- Tujuan: kembalikan jumlah kampus dari 25 ke 20
-- ============================================================
-- Kampus yang dihapus:
--   1. Universitas Aisyiyah Palembang (unisa-palembang)
--   2. Universitas Palembang / UNPAL (unpal)
--   3. STISIPOL Candradimuka (stisipol-candradimuka)
--   4. ITB PalComTech (palcomtech)
--   5. STIE Informasi Teknologi Palembang (stie-it-palembang)
-- ============================================================

USE `maply`;

-- 1. Hapus dulu dari kampus_lokasi (karena ada foreign key ke tabel kampus)
DELETE FROM `kampus_lokasi`
WHERE `kampus_id` IN (
  SELECT `id` FROM `kampus`
  WHERE `kode` IN (
    'unisa-palembang',
    'unpal',
    'stisipol-candradimuka',
    'palcomtech',
    'stie-it-palembang'
  )
);

-- 2. Hapus dari tabel kampus
DELETE FROM `kampus`
WHERE `kode` IN (
  'unisa-palembang',
  'unpal',
  'stisipol-candradimuka',
  'palcomtech',
  'stie-it-palembang'
);

-- 3. Cek hasil
SELECT COUNT(*) AS total_kampus FROM `kampus` WHERE `aktif` = 1;
SELECT COUNT(*) AS total_lokasi FROM `kampus_lokasi` WHERE `aktif` = 1;

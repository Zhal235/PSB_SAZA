-- SQL Script: Menambahkan Kolom Price Versioning ke Tabel pembayaran_items
-- Jalankan script ini di database psb_saza

-- Langkah 1: Tambah kolom nominal_old (Harga Lama)
ALTER TABLE `pembayaran_items` 
ADD COLUMN `nominal_old` DECIMAL(12, 2) NULL AFTER `nominal` 
COMMENT 'Harga lama sebelum perubahan';

-- Langkah 2: Tambah kolom effective_date (Tanggal Efektif Harga Baru)
ALTER TABLE `pembayaran_items` 
ADD COLUMN `effective_date` DATE NULL AFTER `nominal_old` 
COMMENT 'Tanggal efektif harga baru';

-- Verifikasi: Lihat struktur tabel setelah perubahan
DESCRIBE `pembayaran_items`;

-- Verifikasi: Lihat data yang ada
SELECT id, nama, nominal, nominal_old, effective_date, item_type FROM `pembayaran_items`;

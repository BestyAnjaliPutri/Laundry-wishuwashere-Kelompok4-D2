-- ============================================================
-- DATABASE DUMP UNTUK DEPLOYMENT RAILWAY
-- Proyek: wishuwashere. Laundry (SIA Laundry Kelompok 4)
-- Database Engine: MySQL / MariaDB (InnoDB)
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

-- ------------------------------------------------------------
-- 1. TABEL USERS
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `role` ENUM('admin', 'operator') DEFAULT 'operator',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User default: 'kelompok 4' (pass: 'kelompok4') & 'admin' (pass: 'admin123')
INSERT INTO `users` (`id`, `username`, `password_hash`, `name`, `role`, `created_at`, `updated_at`) VALUES
(1, 'kelompok 4', '$2y$10$w0f5u1fDkWpGSmQn7ZlV1O6p79p9xRvyZ1W9r3lF3r0P5gG7Bsm.e', 'Kelompok 4 - KOMAK', 'admin', NOW(), NOW()),
(2, 'admin', '$2y$10$w0f5u1fDkWpGSmQn7ZlV1O6p79p9xRvyZ1W9r3lF3r0P5gG7Bsm.e', 'Administrator wishuwashere.', 'admin', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- ------------------------------------------------------------
-- 2. TABEL AKUN 1 (Klasifikasi Utama Akuntansi)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `akun1` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kode_akun1` VARCHAR(10) NOT NULL UNIQUE,
  `nama_akun1` VARCHAR(100) NOT NULL,
  `posisi` VARCHAR(20) DEFAULT 'Debit',
  `keterangan` VARCHAR(255) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `akun1` (`kode_akun1`, `nama_akun1`, `posisi`, `keterangan`, `created_at`, `updated_at`) VALUES
('1', 'Aset', 'Debit', 'Klasifikasi Aktiva / Harta Laundry', NOW(), NOW()),
('2', 'Liabilitas', 'Kredit', 'Kewajiban / Hutang Usaha & Beban Terutang', NOW(), NOW()),
('3', 'Ekuitas', 'Kredit', 'Modal Pemilik & Prive', NOW(), NOW()),
('4', 'Pendapatan', 'Kredit', 'Pendapatan Jasa Laundry & Operasional', NOW(), NOW()),
('5', 'Beban', 'Debit', 'Beban Operasional, Gaji, Listrik, Air, & Penyusutan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama_akun1` = VALUES(`nama_akun1`);

-- ------------------------------------------------------------
-- 3. TABEL AKUN 2 (Sub-Klasifikasi Akuntansi)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `akun2` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kode_akun1` VARCHAR(10) NULL,
  `nama_akun1` VARCHAR(100) NOT NULL,
  `kode_akun2` VARCHAR(10) NOT NULL UNIQUE,
  `nama_akun2` VARCHAR(100) NOT NULL,
  `posisi` VARCHAR(20) DEFAULT 'Debit',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `akun2` (`kode_akun1`, `nama_akun1`, `kode_akun2`, `nama_akun2`, `posisi`, `created_at`, `updated_at`) VALUES
('1', 'Aset', '11', 'Aset Lancar', 'Debit', NOW(), NOW()),
('1', 'Aset', '12', 'Aset Tetap', 'Debit', NOW(), NOW()),
('2', 'Liabilitas', '21', 'Liabilitas Lancar (Jangka Pendek)', 'Kredit', NOW(), NOW()),
('2', 'Liabilitas', '22', 'Liabilitas Jangka Panjang', 'Kredit', NOW(), NOW()),
('3', 'Ekuitas', '31', 'Ekuitas Pemilik', 'Kredit', NOW(), NOW()),
('4', 'Pendapatan', '41', 'Pendapatan Jasa Laundry', 'Kredit', NOW(), NOW()),
('5', 'Beban', '51', 'Beban Operasional & Administrasi', 'Debit', NOW(), NOW()),
('5', 'Beban', '52', 'Beban Perlengkapan & Penyusutan', 'Debit', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama_akun2` = VALUES(`nama_akun2`);

-- ------------------------------------------------------------
-- 4. TABEL AKUN (Bagan Akun 3 / Master Rekening)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `akun` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_akun1` VARCHAR(100) DEFAULT NULL,
  `nama_akun2` VARCHAR(100) DEFAULT NULL,
  `kode_akun` VARCHAR(20) NOT NULL UNIQUE,
  `nama_akun3` VARCHAR(150) NOT NULL,
  `posisi` VARCHAR(20) DEFAULT 'Debit',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `akun` (`nama_akun1`, `nama_akun2`, `kode_akun`, `nama_akun3`, `posisi`, `created_at`, `updated_at`) VALUES
('Aset', 'Aset Lancar', '1101', 'Kas di Tangan / Bank', 'Debit', NOW(), NOW()),
('Aset', 'Aset Lancar', '1102', 'Piutang Usaha Pelanggan', 'Debit', NOW(), NOW()),
('Aset', 'Aset Lancar', '1103', 'Perlengkapan Deterjen & Parfum', 'Debit', NOW(), NOW()),
('Aset', 'Aset Lancar', '1104', 'Sewa Dibayar di Muka', 'Debit', NOW(), NOW()),
('Aset', 'Aset Tetap', '1201', 'Peralatan Mesin Cuci & Dryer', 'Debit', NOW(), NOW()),
('Aset', 'Aset Tetap', '1202', 'Akumulasi Penyusutan Peralatan', 'Kredit', NOW(), NOW()),
('Liabilitas', 'Liabilitas Lancar', '2101', 'Utang Usaha / Operasional', 'Kredit', NOW(), NOW()),
('Liabilitas', 'Liabilitas Lancar', '2102', 'Beban Terutang (Accrued Expense)', 'Kredit', NOW(), NOW()),
('Liabilitas', 'Liabilitas Lancar', '2103', 'Pendapatan Diterima di Muka', 'Kredit', NOW(), NOW()),
('Ekuitas', 'Ekuitas Pemilik', '3101', 'Modal Pemilik wishuwashere.', 'Kredit', NOW(), NOW()),
('Ekuitas', 'Ekuitas Pemilik', '3102', 'Prive Pemilik wishuwashere.', 'Debit', NOW(), NOW()),
('Pendapatan', 'Pendapatan Jasa Laundry', '4101', 'Pendapatan Jasa Laundry', 'Kredit', NOW(), NOW()),
('Beban', 'Beban Operasional & Administrasi', '5101', 'Beban Pemakaian Bahan Deterjen', 'Debit', NOW(), NOW()),
('Beban', 'Beban Operasional & Administrasi', '5102', 'Beban Listrik, Air & Gas', 'Debit', NOW(), NOW()),
('Beban', 'Beban Operasional & Administrasi', '5103', 'Beban Gaji Karyawan', 'Debit', NOW(), NOW()),
('Beban', 'Beban Operasional & Administrasi', '5104', 'Beban Perawatan Mesin / Servis', 'Debit', NOW(), NOW()),
('Beban', 'Beban Perlengkapan & Penyusutan', '5105', 'Beban Penyusutan Peralatan Mesin', 'Debit', NOW(), NOW()),
('Beban', 'Beban Perlengkapan & Operasional Lain', '5106', 'Beban Perlengkapan & Operasional Lain', 'Debit', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama_akun3` = VALUES(`nama_akun3`);

-- ------------------------------------------------------------
-- 5. TABEL PAKET LAUNDRY
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `paket` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_paket` VARCHAR(100) NOT NULL,
  `jenis` ENUM('kiloan', 'satuan') NOT NULL,
  `harga` INT NOT NULL,
  `estimasi_hari` INT NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `paket` (`id`, `nama_paket`, `jenis`, `harga`, `estimasi_hari`, `created_at`, `updated_at`) VALUES
(1, 'Cuci Komplit Reguler (2 Hari)', 'kiloan', 7000, 2, NOW(), NOW()),
(2, 'Cuci Komplit Kilat (1 Hari)', 'kiloan', 10000, 1, NOW(), NOW()),
(3, 'Cuci Komplit Express (6 Jam)', 'kiloan', 15000, 1, NOW(), NOW()),
(4, 'Cuci Lipat Wangi (Tanpa Setrika)', 'kiloan', 5000, 2, NOW(), NOW()),
(5, 'Setrika Uap Presisi Saja', 'kiloan', 4500, 1, NOW(), NOW()),
(6, 'Bed Cover Single / Selimut Tebal', 'satuan', 25000, 3, NOW(), NOW()),
(7, 'Bed Cover King Size / Sprei Jumbo', 'satuan', 35000, 3, NOW(), NOW()),
(8, 'Jas / Blazer / Gaun Pesta Care', 'satuan', 30000, 2, NOW(), NOW())
ON DUPLICATE KEY UPDATE `harga` = VALUES(`harga`);

-- ------------------------------------------------------------
-- 6. TABEL PELANGGAN
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `no_hp` VARCHAR(25) DEFAULT NULL,
  `alamat` TEXT DEFAULT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pelanggan` (`id`, `nama`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'Ibu Siti Aminah', '081234567801', 'Jl. Pajajaran No. 12, Bogor', NOW(), NOW()),
(2, 'Bpk. Hendra Wijaya', '081234567802', 'Perum Baranangsiang Indah Blok B4', NOW(), NOW()),
(3, 'Kak Anisa Rahma', '081234567803', 'Kost Putri Melati, Dramaga Bogor', NOW(), NOW()),
(4, 'Mas Dimas Prasetyo', '081234567804', 'Jl. Malabar Ujung No. 8', NOW(), NOW()),
(5, 'Ibu Ratna Sari', '081234567805', 'Komplek IPB Baranangsiang A-10', NOW(), NOW()),
(6, 'Bpk. Bambang Sutrisno', '081234567806', 'Jl. Pandu Raya No. 45', NOW(), NOW()),
(7, 'Mbak Nadia Safitri', '081234567807', 'Apartemen Bogor Icon Tower A', NOW(), NOW()),
(8, 'Mas Rizky Ramadhan', '081234567808', 'Jl. Kumbang No. 3, Bogor Tengah', NOW(), NOW()),
(9, 'Ibu Dewi Sartika', '081234567809', 'Perumahan Bantar Kemang Asri C-12', NOW(), NOW()),
(10, 'Mas Aditya Pratama', '081234567810', 'Kost Putra IPB Dramaga', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`);

-- ------------------------------------------------------------
-- 7. TABEL TRANSAKSI KASIR
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kode_transaksi` VARCHAR(50) NOT NULL UNIQUE,
  `pelanggan_id` INT NOT NULL,
  `paket_id` INT NOT NULL,
  `berat_qty` DECIMAL(8,2) NOT NULL,
  `total_biaya` INT NOT NULL,
  `status_cucian` ENUM('Menunggu Dicuci','Sedang Dicuci','Proses Setrika','Siap Diambil','Selesai') DEFAULT 'Menunggu Dicuci',
  `status_bayar` ENUM('Belum Lunas','Lunas') DEFAULT 'Lunas',
  `metode_bayar` ENUM('Tunai','Transfer / QRIS') DEFAULT 'Tunai',
  `catatan` VARCHAR(255) DEFAULT NULL,
  `tgl_masuk` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `tgl_selesai` DATETIME NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  INDEX (`pelanggan_id`),
  INDEX (`paket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 8. TABEL JURNAL UMUM
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jurnal_umum` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `no_bukti` VARCHAR(50) NOT NULL UNIQUE,
  `tanggal` DATE NOT NULL,
  `tipe_transaksi` VARCHAR(100) NOT NULL,
  `keterangan` TEXT NOT NULL,
  `debit_akun_id` INT DEFAULT NULL,
  `debit_kode_akun` VARCHAR(20) NOT NULL,
  `debit_nama_akun` VARCHAR(150) NOT NULL,
  `kredit_akun_id` INT DEFAULT NULL,
  `kredit_kode_akun` VARCHAR(20) NOT NULL,
  `kredit_nama_akun` VARCHAR(150) NOT NULL,
  `nominal` INT NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `jurnal_umum` (`no_bukti`, `tanggal`, `tipe_transaksi`, `keterangan`, `debit_kode_akun`, `debit_nama_akun`, `kredit_kode_akun`, `kredit_nama_akun`, `nominal`, `created_at`, `updated_at`) VALUES
('JU-2611-001', '2026-11-01', 'Penyetoran Modal', 'Setoran Modal Awal Pendirian Usaha wishuwashere. Laundry', '1101', 'Kas di Tangan / Bank', '3101', 'Modal Pemilik wishuwashere.', 50000000, NOW(), NOW()),
('BKK-2611-001', '2026-11-03', 'Pembelian Perlengkapan', 'Pembelian Bahan Deterjen Matic & Softener Wangi 5 Jerigen (Tunai)', '1103', 'Perlengkapan Deterjen & Parfum', '1101', 'Kas di Tangan / Bank', 450000, NOW(), NOW()),
('BKK-2611-002', '2026-11-07', 'Pembelian Perlengkapan', 'Pembelian Stok Plastik Packing Bening & Hanger Kawat Laundry (Tunai)', '1103', 'Perlengkapan Deterjen & Parfum', '1101', 'Kas di Tangan / Bank', 320000, NOW(), NOW()),
('FB-2611-001', '2026-11-10', 'Pembelian Perlengkapan', 'Pembelian Stok Bahan Kimia, Alkali & Spotting Anti-Noda (Kredit CV Sumber Wangi)', '1103', 'Perlengkapan Deterjen & Parfum', '2101', 'Utang Usaha / Operasional', 1250000, NOW(), NOW()),
('BKK-2611-003', '2026-11-14', 'Pembelian Peralatan', 'Pembelian Mesin Steamer Boiler Uap Listrik Tambahan (Tunai)', '1201', 'Peralatan Mesin Cuci & Dryer', '1101', 'Kas di Tangan / Bank', 2500000, NOW(), NOW()),
('BKK-2611-004', '2026-11-18', 'Pembayaran Utang', 'Pembayaran Sebagian Utang Usaha Pembelian Bahan ke CV Sumber Wangi', '2101', 'Utang Usaha / Operasional', '1101', 'Kas di Tangan / Bank', 750000, NOW(), NOW()),
('BKK-2611-005', '2026-11-21', 'Pengeluaran Beban', 'Pembayaran Jasa Servis Rutin & Penggantian Sparepart V-Belt Mesin Cuci', '5104', 'Beban Perawatan Mesin / Servis', '1101', 'Kas di Tangan / Bank', 350000, NOW(), NOW()),
('BKK-2611-006', '2026-11-01', 'Sewa Dibayar di Muka', 'Pembayaran Sewa Tempat/Kios Usaha Laundry Dimuka 1 Tahun (Tunai)', '1104', 'Sewa Dibayar di Muka', '1101', 'Kas di Tangan / Bank', 1800000, NOW(), NOW()),
('BKK-2611-007', '2026-11-16', 'Penarikan Prive', 'Penarikan Kas Prive Pemilik wishuwashere. untuk Keperluan Pribadi', '3102', 'Prive Pemilik wishuwashere.', '1101', 'Kas di Tangan / Bank', 850000, NOW(), NOW()),
('BKK-2611-008', '2026-11-20', 'Pembayaran Beban Gaji', 'Pembayaran Gaji Karyawan Operasional Kasir & Penyetrika Paruh Waktu (Tunai)', '5103', 'Beban Gaji Karyawan', '1101', 'Kas di Tangan / Bank', 1250000, NOW(), NOW()),
('BKK-2611-009', '2026-11-23', 'Pengeluaran Beban', 'Pembayaran Biaya Cetak Banner, Brosur & Iklan Promosi Laundry (Tunai)', '5106', 'Beban Perlengkapan & Operasional Lain', '1101', 'Kas di Tangan / Bank', 300000, NOW(), NOW()),
('BKM-2611-002', '2026-11-08', 'Penerimaan Pendapatan', 'Penerimaan Pendapatan Jasa Laundry Borongan Seragam Hotel Salak Heritage', '1101', 'Kas di Tangan / Bank', '4101', 'Pendapatan Jasa Laundry', 1450000, NOW(), NOW()),
('BKM-2611-003', '2026-11-15', 'Penerimaan Pendapatan', 'Penerimaan Pendapatan Jasa Cuci Taplak Meja, Gordyn & Banner Wisuda IPB', '1101', 'Kas di Tangan / Bank', '4101', 'Pendapatan Jasa Laundry', 2100000, NOW(), NOW()),
('BKM-2611-004', '2026-11-19', 'Penerimaan Pendapatan', 'Penerimaan Uang Muka Paket Laundry Bulanan Kost Mahasiswa Baranangsiang', '1101', 'Kas di Tangan / Bank', '2103', 'Pendapatan Diterima di Muka', 600000, NOW(), NOW()),
('BKM-2611-005', '2026-11-22', 'Pelunasan Piutang', 'Pelunasan Piutang Usaha Cucian dari Pelanggan Kantor Notaris', '1101', 'Kas di Tangan / Bank', '1102', 'Piutang Usaha Pelanggan', 380000, NOW(), NOW()),
('FB-2611-002', '2026-11-05', 'Pendapatan Jasa', 'Pendapatan Jasa Laundry Seragam Kantor Notaris & Rekanan (Kredit / Tempo)', '1102', 'Piutang Usaha Pelanggan', '4101', 'Pendapatan Jasa Laundry', 1200000, NOW(), NOW())
ON DUPLICATE KEY UPDATE `nominal` = VALUES(`nominal`);

-- ------------------------------------------------------------
-- 9. TABEL JURNAL PENYESUAIAN (AJE)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jurnal_penyesuaian` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `no_bukti` VARCHAR(50) NOT NULL UNIQUE,
  `tanggal` DATE NOT NULL,
  `keterangan` TEXT NOT NULL,
  `debit_kode_akun` VARCHAR(20) NOT NULL,
  `debit_nama_akun` VARCHAR(150) NOT NULL,
  `kredit_kode_akun` VARCHAR(20) NOT NULL,
  `kredit_nama_akun` VARCHAR(150) NOT NULL,
  `nominal` INT NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `jurnal_penyesuaian` (`no_bukti`, `tanggal`, `keterangan`, `debit_kode_akun`, `debit_nama_akun`, `kredit_kode_akun`, `kredit_nama_akun`, `nominal`, `created_at`, `updated_at`) VALUES
('AJP-261130-001', '2026-11-30', 'Perlengkapan Deterjen Terpakai', '5101', 'Beban Pemakaian Bahan Deterjen', '1103', 'Perlengkapan Deterjen & Parfum', 550000, NOW(), NOW()),
('AJP-261130-002', '2026-11-30', 'Penyusutan Peralatan/Mesin Cuci', '5105', 'Beban Penyusutan Peralatan Mesin', '1202', 'Akumulasi Penyusutan Peralatan', 350000, NOW(), NOW()),
('AJP-261130-003', '2026-11-30', 'Beban Gaji Terutang (Accrued)', '5103', 'Beban Gaji Karyawan', '2102', 'Beban Terutang (Accrued Expense)', 500000, NOW(), NOW()),
('AJP-261130-004', '2026-11-30', 'Beban Listrik/Air Terutang (Accrued)', '5102', 'Beban Listrik, Air & Gas', '2102', 'Beban Terutang (Accrued Expense)', 275000, NOW(), NOW()),
('AJP-261130-005', '2026-11-30', 'Pendapatan Diterima di Muka (Unearned)', '2103', 'Pendapatan Diterima di Muka', '4101', 'Pendapatan Jasa Laundry', 250000, NOW(), NOW()),
('AJP-261130-006', '2026-11-30', 'Beban Dibayar di Muka (Prepaid)', '5106', 'Beban Perlengkapan & Operasional Lain', '1104', 'Sewa Dibayar di Muka', 150000, NOW(), NOW())
ON DUPLICATE KEY UPDATE `nominal` = VALUES(`nominal`);

SET FOREIGN_KEY_CHECKS = 1;

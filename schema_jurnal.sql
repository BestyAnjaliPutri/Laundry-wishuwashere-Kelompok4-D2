CREATE TABLE IF NOT EXISTS `jurnal_umum` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `no_bukti` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `tipe_transaksi` varchar(50) NOT NULL DEFAULT 'Umum',
  `keterangan` text NOT NULL,
  `debit_akun_id` int(11) DEFAULT NULL,
  `debit_kode_akun` varchar(20) NOT NULL,
  `debit_nama_akun` varchar(150) NOT NULL,
  `kredit_akun_id` int(11) DEFAULT NULL,
  `kredit_kode_akun` varchar(20) NOT NULL,
  `kredit_nama_akun` varchar(150) NOT NULL,
  `nominal` bigint(20) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tanggal` (`tanggal`),
  KEY `tipe_transaksi` (`tipe_transaksi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `jurnal_umum` 
(`no_bukti`, `tanggal`, `tipe_transaksi`, `keterangan`, `debit_kode_akun`, `debit_nama_akun`, `kredit_kode_akun`, `kredit_nama_akun`, `nominal`, `created_at`, `updated_at`)
VALUES
('JU-20261101-001', '2026-11-01', 'Penyetoran Modal', 'Penyetoran modal kas operasional awal laundry wishuwashere.', '1101', 'Kas di Tangan / Bank', '3101', 'Modal Pemilik wishuwashere.', 10000000, NOW(), NOW()),
('JU-20261102-002', '2026-11-02', 'Pembelian Perlengkapan', 'Pembelian perlengkapan deterjen cair konsentrat & softener 10 jerigen', '1103', 'Perlengkapan Deterjen & Parfum', '1101', 'Kas di Tangan / Bank', 650000, NOW(), NOW()),
('JU-20261103-003', '2026-11-03', 'Pembayaran Utang', 'Pembayaran cicilan utang operasional pembelian mesin', '2101', 'Utang Usaha / Operasional', '1101', 'Kas di Tangan / Bank', 500000, NOW(), NOW()),
('JU-20261104-004', '2026-11-04', 'Pelunasan Piutang', 'Penerimaan pelunasan pembayaran piutang cucian pelanggan', '1101', 'Kas di Tangan / Bank', '1102', 'Piutang Usaha Pelanggan', 150000, NOW(), NOW()),
('JU-20261105-005', '2026-11-05', 'Pengeluaran Beban', 'Pembayaran token listrik PLN & air PDAM outlet laundry', '5102', 'Beban Listrik, Air & Gas', '1101', 'Kas di Tangan / Bank', 320000, NOW(), NOW());

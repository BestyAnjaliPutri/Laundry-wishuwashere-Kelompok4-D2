<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi #<?= esc($transaksi['kode_transaksi']) ?> — wishuwashere. Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #f1f5f9;
            margin: 0;
            padding: 24px 12px;
            font-size: 13px;
            line-height: 1.4;
        }
        .sheet {
            background: #ffffff;
            width: 210mm;
            max-width: 820px;
            min-height: auto;
            margin: 0 auto;
            padding: 18mm 15mm;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            box-sizing: border-box;
            border: 1px solid #cbd5e1;
        }
        .header-laporan {
            text-align: center;
            margin-bottom: 22px;
        }
        .header-laporan h3 {
            font-size: 17px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }
        .header-laporan h4 {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 5px 0;
            letter-spacing: 0.5px;
        }
        .header-laporan p {
            font-size: 13px;
            font-weight: bold;
            margin: 0;
        }
        .unit-mata-uang {
            text-align: right;
            font-size: 13px;
            margin-bottom: 4px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 18px;
            border: 1px solid #000;
            padding: 10px 14px;
            background: #fafafa;
        }
        .table-akuntansi {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
            font-size: 13px;
            margin-bottom: 15px;
        }
        .table-akuntansi th, .table-akuntansi td {
            border: 1px solid #000;
            padding: 6px 10px;
        }
        .table-akuntansi th {
            font-weight: bold;
            text-align: center;
            background-color: #fff;
        }
        .table-akuntansi td.col-no {
            text-align: center;
            width: 45px;
        }
        .table-akuntansi td.col-nominal {
            text-align: right;
            width: 145px;
            font-variant-numeric: tabular-nums;
        }
        .table-akuntansi tr.row-total td {
            font-weight: bold;
            border-top: 1.5px solid #000;
        }
        .double-bottom {
            border-bottom: 3px double #000 !important;
        }
        .tanda-tangan {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sig-box {
            text-align: center;
            width: 220px;
            font-size: 13px;
        }
        .sig-space {
            height: 55px;
        }
        .syarat-ketentuan {
            font-size: 11px;
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px dashed #666;
            color: #333;
            line-height: 1.4;
        }

        /* Thermal Mini Mode (Jika memilih format struk) */
        .thermal-mode {
            display: none;
            width: 80mm;
            max-width: 320px;
            padding: 15px;
            background: #fff;
            border: 1px dashed #999;
            margin: 0 auto;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .sheet {
                width: 100% !important;
                max-width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Toolbar Aksi di Layar (Tidak Ikut Tercetak) -->
    <div class="no-print mb-3">
        <div class="card shadow-sm border" style="max-width: 820px; margin: 0 auto; border-color: #cbd5e1; border-radius: 10px;">
            <div class="card-body py-2 px-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <label class="fw-bold small mb-0 text-dark">
                        👤 Petugas TTD:
                    </label>
                    <select id="pilihPetugas" class="form-select form-select-sm" style="width: auto; min-width: 250px; font-weight: 600; border-radius: 8px;" onchange="gantiPetugas(this)">
                        <option value="Wardiansyach|NIM. J0414241069" selected>Wardiansyach (NIM. J0414241069)</option>
                        <option value="Besty Anjali Putri Setyadi|NIM. J0414241089">Besty Anjali Putri Setyadi (NIM. J0414241089)</option>
                        <option value="Solehatus Fuadah|NIM. J0414241147">Solehatus Fuadah (NIM. J0414241147)</option>
                        <option value="Muhammad Dwiki Riza|NIM. J0414241149">Muhammad Dwiki Riza (NIM. J0414241149)</option>
                        <option value="Putri Widyastuti|NIM. J0414241198">Putri Widyastuti (NIM. J0414241198)</option>
                        <option value="Muhammad Satria A.R|NIM. J0414241298">Muhammad Satria A.R (NIM. J0414241298)</option>
                    </select>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button onclick="window.print()" class="btn btn-dark btn-sm px-3 fw-bold" style="border-radius: 8px;">
                        🖨️ Cetak / Simpan PDF
                    </button>
                    <button onclick="window.close()" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 8px;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- LEMBAR DOKUMEN CETAK BAKU A4 PORTRAIT -->
    <div class="sheet" id="sheetResmi">
        <!-- HEADER 3 BARIS (SERAGAM DENGAN SELURUH DOKUMEN KEUANGAN LAINNYA) -->
        <div class="header-laporan">
            <h3>WISHUWASHERE. LAUNDRY</h3>
            <h4>NOTA TRANSAKSI &amp; KWITANSI PEMBAYARAN</h4>
            <p>No. Bukti / Nota: #<?= esc($transaksi['kode_transaksi']) ?> &nbsp;|&nbsp; Tanggal: <?= date('d F Y', strtotime($transaksi['tgl_masuk'])) ?></p>
        </div>

        <!-- INFORMASI PELANGGAN & TRANSAKSI -->
        <div class="info-grid">
            <div class="row g-2">
                <div class="col-6">
                    <table style="width: 100%; font-size: 12.5px;">
                        <tr>
                            <td style="width: 120px; font-weight: bold;">Nama Pelanggan</td>
                            <td style="width: 10px;">:</td>
                            <td><?= esc($transaksi['nama_pelanggan']) ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">No. Telepon / WA</td>
                            <td>:</td>
                            <td><?= esc($transaksi['no_hp'] ?: '-') ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Alamat Pelanggan</td>
                            <td>:</td>
                            <td><?= esc($transaksi['alamat'] ?? 'Kota Bogor') ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table style="width: 100%; font-size: 12.5px;">
                        <tr>
                            <td style="width: 130px; font-weight: bold;">Waktu Masuk</td>
                            <td style="width: 10px;">:</td>
                            <td><?= date('d/m/Y H:i', strtotime($transaksi['tgl_masuk'])) ?> WIB</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Estimasi Selesai</td>
                            <td>:</td>
                            <td><?= date('d/m/Y', strtotime($transaksi['tgl_selesai'])) ?> WIB</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Status Pembayaran</td>
                            <td>:</td>
                            <td>
                                <strong><?= esc($transaksi['status_bayar']) ?></strong> 
                                (<?= esc($transaksi['metode_bayar']) ?>)
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Status Pengerjaan</td>
                            <td>:</td>
                            <td><?= esc($transaksi['status_cucian']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- SATUAN MATA UANG -->
        <div class="unit-mata-uang">(Rp)</div>

        <!-- TABEL RINCIAN TRANSAKSI -->
        <table class="table-akuntansi">
            <thead>
                <tr>
                    <th style="width: 45px;">No</th>
                    <th>Uraian Paket Layanan Laundry</th>
                    <th style="width: 110px;">Jenis Paket</th>
                    <th style="width: 110px;">Kuantitas / Berat</th>
                    <th style="width: 140px;">Tarif Satuan</th>
                    <th style="width: 155px;">Subtotal Biaya</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-no">1</td>
                    <td>
                        <strong><?= esc($transaksi['nama_paket']) ?></strong>
                        <?php if (!empty($transaksi['catatan'])): ?>
                            <br><small style="font-style: italic; color: #444;">Catatan Khusus: <?= esc($transaksi['catatan']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center; text-transform: capitalize;"><?= esc($transaksi['jenis_paket']) ?></td>
                    <td style="text-align: center;"><?= esc($transaksi['berat_qty']) ?> <?= $transaksi['jenis_paket'] === 'kiloan' ? 'Kg' : 'Pcs' ?></td>
                    <td class="col-nominal"><?= number_format($transaksi['harga_paket'], 0, ',', '.') ?></td>
                    <td class="col-nominal"><?= number_format($transaksi['total_biaya'], 0, ',', '.') ?></td>
                </tr>
                <tr class="row-total">
                    <td colspan="5" style="text-align: right; padding-right: 15px;">TOTAL BIAYA TRANSAKSI</td>
                    <td class="col-nominal double-bottom">
                        <?= number_format($transaksi['total_biaya'], 0, ',', '.') ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- TERBILANG / KETERANGAN NOMINAL -->
        <div style="font-size: 12px; margin-bottom: 25px; background: #fff; padding: 6px 10px; border: 1px solid #000;">
            <strong>Terbilang:</strong> 
            <em># <?= esc(function_exists('terbilang') ? terbilang((int)$transaksi['total_biaya']) : number_format($transaksi['total_biaya'], 0, ',', '.')) ?> Rupiah #</em>
        </div>

        <!-- TANDA TANGAN (SERAGAM DENGAN SEMUA LAPORAN KEUANGAN) -->
        <div class="tanda-tangan">
            <div class="sig-box">
                <div>Penerima / Pelanggan,</div>
                <div class="sig-space"></div>
                <div style="font-weight: bold; text-decoration: underline;"><?= esc($transaksi['nama_pelanggan']) ?></div>
                <div style="font-size: 11.5px; color: #444;">Konsumen</div>
            </div>

            <div class="sig-box">
                <div>Bogor, <?= date('d F Y', strtotime($transaksi['tgl_masuk'])) ?></div>
                <div>Petugas Kasir / Operasional,</div>
                <div class="sig-space"></div>
                <div id="namaTtd" style="font-weight: bold; text-decoration: underline;">Wardiansyach</div>
                <div id="nimTtd" style="font-size: 11.5px;">NIM. J0414241069</div>
                <div style="font-size: 11px; color: #666;">(Kelompok 4 — KOMAK)</div>
            </div>
        </div>

        <!-- SYARAT & KETENTUAN LAYANAN -->
        <div class="syarat-ketentuan">
            <strong>Syarat &amp; Ketentuan Layanan wishuwashere. Laundry:</strong><br>
            1. Pengambilan cucian wajib membawa nota resmi ini (atau bukti fisik/digital nomor nota transaksi).<br>
            2. Kerusakan/luntur pakaian akibat sifat alami bahan tanpa konfirmasi awal di luar tanggung jawab pihak laundry.<br>
            3. Klaim kehilangan/kekurangan pakaian maksimal diajukan 1 x 24 jam setelah cucian diserahkan ke pelanggan.<br>
            4. Cucian yang tidak diambil dalam waktu lebih dari 30 hari kalender bukan lagi menjadi tanggung jawab wishuwashere.
        </div>
    </div>

    <!-- SCRIPT GANTI NAMA PETUGAS TTD SECARA LIVE -->
    <script>
        function gantiPetugas(selectElem) {
            const val = selectElem.value;
            const parts = val.split('|');
            const nama = parts[0] || 'Wardiansyach';
            const nim  = parts[1] || 'NIM. J0414241069';

            document.getElementById('namaTtd').innerText = nama;
            document.getElementById('nimTtd').innerText  = nim;
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Neraca Lajur 10 Kolom — wishuwashere.' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 12mm 15mm 12mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #f1f5f9;
            margin: 0;
            padding: 24px 12px;
            font-size: 11.5px;
            line-height: 1.35;
        }
        .sheet-landscape {
            background: #ffffff;
            width: 297mm;
            max-width: 1120px;
            min-height: 210mm;
            margin: 0 auto;
            padding: 15mm 12mm;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            box-sizing: border-box;
            border: 1px solid #cbd5e1;
        }
        .header-laporan {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-laporan h3 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 3px 0;
            letter-spacing: 0.5px;
        }
        .header-laporan h4 {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }
        .header-laporan p {
            font-size: 12px;
            font-weight: bold;
            margin: 0;
        }
        .unit-mata-uang {
            text-align: right;
            font-size: 11.5px;
            margin-bottom: 3px;
        }
        .table-akuntansi {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
            font-size: 11px;
        }
        .table-akuntansi th, .table-akuntansi td {
            border: 1px solid #000;
            padding: 4px 5px;
        }
        .table-akuntansi th {
            font-weight: bold;
            text-align: center;
            background-color: #fff;
        }
        .table-akuntansi td.col-code {
            text-align: center;
            width: 55px;
        }
        .table-akuntansi td.col-nominal {
            text-align: right;
            width: 68px;
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
            font-size: 12px;
        }
        .sig-space {
            height: 50px;
        }
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .sheet-landscape {
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
        <div class="card shadow-sm border" style="max-width: 1120px; margin: 0 auto; border-color: #cbd5e1; border-radius: 10px;">
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

    <!-- LEMBAR DOKUMEN CETAK A4 LANDSCAPE -->
    <div class="sheet-landscape">
        <!-- HEADER 3 BARIS (NAMA PERUSAHAAN DI ATAS, NAMA LAPORAN DI BAWAHNYA) -->
        <div class="header-laporan">
            <h3>WISHUWASHERE. LAUNDRY</h3>
            <h4>NERACA LAJUR 10 KOLOM</h4>
            <p>Periode <?= date('d F Y') ?></p>
        </div>

        <div class="unit-mata-uang">(Rp)</div>

        <!-- TABEL 10 KOLOM RESMI POLOS -->
        <table class="table-akuntansi">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 55px;">No. Akun</th>
                    <th rowspan="2">Nama Akun</th>
                    <th colspan="2">Neraca Saldo</th>
                    <th colspan="2">Penyesuaian</th>
                    <th colspan="2">Neraca Saldo Disesuaikan</th>
                    <th colspan="2">Laba / Rugi</th>
                    <th colspan="2">Neraca</th>
                </tr>
                <tr>
                    <th style="width: 68px;">Debit</th>
                    <th style="width: 68px;">Kredit</th>
                    <th style="width: 68px;">Debit</th>
                    <th style="width: 68px;">Kredit</th>
                    <th style="width: 68px;">Debit</th>
                    <th style="width: 68px;">Kredit</th>
                    <th style="width: 68px;">Debit</th>
                    <th style="width: 68px;">Kredit</th>
                    <th style="width: 68px;">Debit</th>
                    <th style="width: 68px;">Kredit</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($worksheet)): ?>
                    <tr>
                        <td colspan="12" class="text-center py-3">Belum ada akun di buku besar.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($worksheet as $w): ?>
                        <tr>
                            <td class="col-code"><?= esc($w['kode_akun']) ?></td>
                            <td><?= esc($w['nama_akun']) ?></td>
                            <td class="col-nominal"><?= $w['ns_d'] > 0 ? number_format($w['ns_d'], 0, ',', '.') : '-' ?></td>
                            <td class="col-nominal"><?= $w['ns_k'] > 0 ? number_format($w['ns_k'], 0, ',', '.') : '-' ?></td>
                            <td class="col-nominal"><?= $w['ajp_d'] > 0 ? number_format($w['ajp_d'], 0, ',', '.') : '-' ?></td>
                            <td class="col-nominal"><?= $w['ajp_k'] > 0 ? number_format($w['ajp_k'], 0, ',', '.') : '-' ?></td>
                            <td class="col-nominal"><?= $w['nsd_d'] > 0 ? number_format($w['nsd_d'], 0, ',', '.') : '-' ?></td>
                            <td class="col-nominal"><?= $w['nsd_k'] > 0 ? number_format($w['nsd_k'], 0, ',', '.') : '-' ?></td>
                            <td class="col-nominal"><?= $w['lr_d'] > 0 ? number_format($w['lr_d'], 0, ',', '.') : '-' ?></td>
                            <td class="col-nominal"><?= $w['lr_k'] > 0 ? number_format($w['lr_k'], 0, ',', '.') : '-' ?></td>
                            <td class="col-nominal"><?= $w['neraca_d'] > 0 ? number_format($w['neraca_d'], 0, ',', '.') : '-' ?></td>
                            <td class="col-nominal"><?= $w['neraca_k'] > 0 ? number_format($w['neraca_k'], 0, ',', '.') : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr class="row-total">
                    <td colspan="2" class="text-center">Jumlah</td>
                    <td class="col-nominal"><?= number_format($total_ns_d, 0, ',', '.') ?></td>
                    <td class="col-nominal"><?= number_format($total_ns_k, 0, ',', '.') ?></td>
                    <td class="col-nominal"><?= number_format($total_ajp_d, 0, ',', '.') ?></td>
                    <td class="col-nominal"><?= number_format($total_ajp_k, 0, ',', '.') ?></td>
                    <td class="col-nominal"><?= number_format($total_nsd_d, 0, ',', '.') ?></td>
                    <td class="col-nominal"><?= number_format($total_nsd_k, 0, ',', '.') ?></td>
                    <td class="col-nominal"><?= number_format($total_lr_d, 0, ',', '.') ?></td>
                    <td class="col-nominal"><?= number_format($total_lr_k, 0, ',', '.') ?></td>
                    <td class="col-nominal"><?= number_format($total_neraca_d, 0, ',', '.') ?></td>
                    <td class="col-nominal"><?= number_format($total_neraca_k, 0, ',', '.') ?></td>
                </tr>
                <tr style="font-weight: bold;">
                    <td colspan="2" class="text-center">Laba / (Rugi) Bersih</td>
                    <td colspan="6" class="text-center">-</td>
                    <td class="col-nominal"><?= $laba >= 0 ? number_format($laba, 0, ',', '.') : '-' ?></td>
                    <td class="col-nominal"><?= $laba < 0 ? number_format(abs($laba), 0, ',', '.') : '-' ?></td>
                    <td class="col-nominal"><?= $laba < 0 ? number_format(abs($laba), 0, ',', '.') : '-' ?></td>
                    <td class="col-nominal"><?= $laba >= 0 ? number_format($laba, 0, ',', '.') : '-' ?></td>
                </tr>
                <tr class="row-total">
                    <td colspan="2" class="text-center">Jumlah Seimbang</td>
                    <td class="col-nominal double-bottom"><?= number_format($total_ns_d, 0, ',', '.') ?></td>
                    <td class="col-nominal double-bottom"><?= number_format($total_ns_k, 0, ',', '.') ?></td>
                    <td class="col-nominal double-bottom"><?= number_format($total_ajp_d, 0, ',', '.') ?></td>
                    <td class="col-nominal double-bottom"><?= number_format($total_ajp_k, 0, ',', '.') ?></td>
                    <td class="col-nominal double-bottom"><?= number_format($total_nsd_d, 0, ',', '.') ?></td>
                    <td class="col-nominal double-bottom"><?= number_format($total_nsd_k, 0, ',', '.') ?></td>
                    <td class="col-nominal double-bottom"><?= number_format(max($total_lr_d, $total_lr_k), 0, ',', '.') ?></td>
                    <td class="col-nominal double-bottom"><?= number_format(max($total_lr_d, $total_lr_k), 0, ',', '.') ?></td>
                    <td class="col-nominal double-bottom"><?= number_format(max($total_neraca_d, $total_neraca_k), 0, ',', '.') ?></td>
                    <td class="col-nominal double-bottom"><?= number_format(max($total_neraca_d, $total_neraca_k), 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>

        <!-- TANDA TANGAN DUA PIHAK -->
        <div class="tanda-tangan">
            <div class="sig-box">
                <p class="mb-0">Dibuat Oleh,<br><strong>Petugas Akuntansi</strong></p>
                <div class="sig-space"></div>
                <p class="mb-0 text-decoration-underline fw-bold" id="ttdNama">Wardiansyach</p>
                <small id="ttdNim">NIM. J0414241069</small>
            </div>
            <div class="sig-box">
                <p class="mb-0">Bogor, <?= date('d F Y') ?><br>Mengetahui / Disetujui,</p>
                <div class="sig-space"></div>
                <p class="mb-0 text-decoration-underline fw-bold">( Kelompok 4 &mdash; KOMAK )</p>
                <small>wishuwashere. Laundry</small>
            </div>
        </div>
    </div>

    <script>
        function gantiPetugas(sel) {
            const val = sel.value.split('|');
            document.getElementById('ttdNama').textContent = val[0] || '';
            document.getElementById('ttdNim').textContent = val[1] || '';
        }
    </script>
</body>
</html>

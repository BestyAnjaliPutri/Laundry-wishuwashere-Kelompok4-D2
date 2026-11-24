<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Buku Besar — wishuwashere.' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 15mm 20mm 15mm;
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
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm 15mm;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            box-sizing: border-box;
            border: 1px solid #cbd5e1;
        }
        .header-laporan {
            text-align: center;
            margin-bottom: 25px;
        }
        .header-laporan h3 {
            font-size: 17px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }
        .header-laporan h4 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 6px 0;
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
        .buku-card {
            margin-bottom: 24px;
            page-break-inside: avoid;
        }
        .buku-header-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 4px;
            padding: 2px 2px;
        }
        .table-akuntansi {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
            font-size: 12px;
        }
        .table-akuntansi th, .table-akuntansi td {
            border: 1px solid #000;
            padding: 5px 8px;
        }
        .table-akuntansi th {
            font-weight: bold;
            text-align: center;
            background-color: #fff;
        }
        .table-akuntansi td.col-date {
            text-align: center;
            width: 80px;
        }
        .table-akuntansi td.col-ref {
            text-align: center;
            width: 90px;
        }
        .table-akuntansi td.col-nominal {
            text-align: right;
            width: 110px;
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
            margin-top: 45px;
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
            height: 60px;
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

    <!-- LEMBAR DOKUMEN CETAK A4 PORTRAIT -->
    <div class="sheet">
        <!-- HEADER 3 BARIS (NAMA PERUSAHAAN DI ATAS, NAMA LAPORAN DI BAWAHNYA) -->
        <div class="header-laporan">
            <h3>WISHUWASHERE. LAUNDRY</h3>
            <h4>BUKU BESAR</h4>
            <p>Periode <?= date('d F Y') ?></p>
        </div>

        <div class="unit-mata-uang">(Rp)</div>

        <?php if (empty($bukuBesar)): ?>
            <div class="text-center py-4">Belum ada posting di buku besar.</div>
        <?php else: ?>
            <?php foreach ($bukuBesar as $kode => $akun): ?>
                <?php 
                    $saldo = 0; 
                    $posisiNormal = in_array(substr($kode, 0, 1), ['1', '5']) ? 'D' : 'K';
                ?>
                <div class="buku-card">
                    <div class="buku-header-row">
                        <div>Nama Akun: <?= esc($akun['nama_akun']) ?></div>
                        <div>No. Akun: <?= esc($kode) ?></div>
                    </div>

                    <table class="table-akuntansi">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 80px;">Tanggal</th>
                                <th rowspan="2">Keterangan</th>
                                <th rowspan="2" style="width: 90px;">Ref</th>
                                <th rowspan="2" style="width: 110px;">Debit</th>
                                <th rowspan="2" style="width: 110px;">Kredit</th>
                                <th colspan="2">Saldo</th>
                            </tr>
                            <tr>
                                <th style="width: 110px;">Debit</th>
                                <th style="width: 110px;">Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($akun['transaksi'] as $t): ?>
                                <?php 
                                    if ($posisiNormal === 'D') {
                                        $saldo += ($t['debit'] - $t['kredit']);
                                    } else {
                                        $saldo += ($t['kredit'] - $t['debit']);
                                    }
                                    $isDebit = ($posisiNormal === 'D' && $saldo >= 0) || ($posisiNormal === 'K' && $saldo < 0);
                                ?>
                                <tr>
                                    <td class="col-date"><?= date('d/m/Y', strtotime($t['tanggal'])) ?></td>
                                    <td><?= esc($t['keterangan']) ?></td>
                                    <td class="col-ref"><?= esc($t['no_bukti']) ?></td>
                                    <td class="col-nominal"><?= $t['debit'] > 0 ? number_format($t['debit'], 0, ',', '.') : '-' ?></td>
                                    <td class="col-nominal"><?= $t['kredit'] > 0 ? number_format($t['kredit'], 0, ',', '.') : '-' ?></td>
                                    <td class="col-nominal"><?= $isDebit ? number_format(abs($saldo), 0, ',', '.') : '-' ?></td>
                                    <td class="col-nominal"><?= !$isDebit ? number_format(abs($saldo), 0, ',', '.') : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="row-total">
                                <td colspan="3" class="text-center">Jumlah</td>
                                <td class="col-nominal"><?= number_format($akun['total_debit'], 0, ',', '.') ?></td>
                                <td class="col-nominal"><?= number_format($akun['total_kredit'], 0, ',', '.') ?></td>
                                <td class="col-nominal double-bottom"><?= $isDebit ? number_format(abs($saldo), 0, ',', '.') : '-' ?></td>
                                <td class="col-nominal double-bottom"><?= !$isDebit ? number_format(abs($saldo), 0, ',', '.') : '-' ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

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

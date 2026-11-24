<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <style>
        .report-header-box {
            text-align: center;
            padding: 24px 20px 20px;
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
            border: 1.5px solid #bae6fd;
            border-radius: 16px;
            margin-bottom: 20px;
        }

        .report-company-title {
            font-size: 1.45rem;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 2px;
        }

        .report-main-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0284c7;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .report-period-text {
            font-size: 0.82rem;
            color: #64748b;
            font-weight: 600;
        }

        /* Banner Status Balance */
        .balance-banner {
            border-radius: 14px;
            padding: 14px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            border: 2px solid #86efac !important;
            background: #f0fdf4;
        }

        .balance-banner.unbalanced {
            border-color: #fda4af !important;
            background: #fff1f2;
        }

        .balance-icon-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: #ffffff;
            background: #16a34a;
            flex-shrink: 0;
        }

        .balance-banner.unbalanced .balance-icon-circle {
            background: #dc2626;
        }

        /* Mode Switcher Pill */
        .mode-switcher-card {
            background: #ffffff;
            border: 1.5px solid #bae6fd;
            border-radius: 14px;
            padding: 12px 18px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .btn-mode-pill {
            border: 1.5px solid #bae6fd;
            background: #f8fdff;
            color: #0369a1;
            font-weight: 700;
            font-size: 0.82rem;
            padding: 7px 18px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-mode-pill.active {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            color: #ffffff;
            border-color: #0284c7;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        }

        /* Table Card Financial Report Style */
        .trial-balance-card {
            background: #ffffff;
            border: 1.5px solid #bae6fd;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(2, 132, 199, 0.06);
        }

        .trial-balance-card-header {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-bottom: 1.5px solid #bae6fd;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-trial-balance {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
            font-size: 0.90rem;
        }

        .table-trial-balance thead th {
            background: #f8fdff !important;
            color: #0284c7 !important;
            font-weight: 800;
            font-size: 0.78rem;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            border-bottom: 2px solid #7dd3fc !important;
            padding: 12px 16px;
        }

        .table-trial-balance tbody td {
            padding: 11px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f9ff;
            border-top: 1px solid #f0f9ff;
            color: #1e293b;
        }

        .table-trial-balance tbody tr:hover {
            background-color: #f8fdff;
        }

        .category-separator-row td {
            background: #f0f9ff !important;
            font-weight: 800;
            color: #0369a1;
            font-size: 0.76rem;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 9px 16px !important;
            border-top: 1.5px solid #bae6fd !important;
            border-bottom: 1.5px solid #bae6fd !important;
        }

        .num-font {
            font-variant-numeric: tabular-nums;
            font-family: inherit;
        }

        /* Accounting Double Underline Footer */
        .accounting-total-row {
            background: #f8fdff !important;
            font-weight: 800;
            border-top: 2px solid #0284c7 !important;
            border-bottom: 3px double #0284c7 !important;
        }

        .accounting-total-row td {
            padding: 14px 16px !important;
            font-size: 1rem;
        }

        /* Print Media Styling */
        @media print {
            #sidebar, .top-navbar, .main-footer, .btn-mode-pill, .mode-switcher-card, .btn-print-box {
                display: none !important;
            }
            #main-wrapper {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            .trial-balance-card, .report-header-box {
                border: 1px solid #000000 !important;
                box-shadow: none !important;
            }
            .table-trial-balance thead th {
                border-bottom: 2px solid #000000 !important;
                color: #000000 !important;
            }
            .accounting-total-row {
                border-top: 1.5px solid #000000 !important;
                border-bottom: 3px double #000000 !important;
            }
        }
    </style>

    <!-- Top Action & Breadcrumb -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" style="color:#0284c7;">wishuwashere.</a></li>
                    <li class="breadcrumb-item active">Neraca Saldo</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0" style="color:#0369a1;">
                <i class="fa-solid fa-scale-balanced me-2"></i>Neraca Saldo (Trial Balance)
            </h4>
            <small class="text-muted">Format tata letak baku laporan keuangan akuntansi terintegrasi</small>
        </div>
        <div class="d-flex align-items-center gap-2 btn-print-box">
            <a href="<?= base_url('neraca-saldo/cetak') ?>" target="_blank" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm" style="border-radius:10px;">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Dokumen Resmi</span>
            </a>
            <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" onclick="window.print()" style="border-radius:10px; background:linear-gradient(135deg,#0284c7,#0369a1); border:none;">
                <i class="fa-solid fa-file-pdf"></i>
                <span class="d-none d-sm-inline">Cetak Cepat</span>
            </button>
        </div>
    </div>

    <!-- 1. KOP RESMI LAPORAN KEUANGAN (PERSIS STANDAR AKUNTANSI) -->
    <div class="report-header-box">
        <div class="report-company-title">wishuwashere. Laundry &amp; Care</div>
        <div class="report-main-title">NERACA SALDO (TRIAL BALANCE)</div>
        <div class="report-period-text">
            <span>Per Tanggal: <strong><?= date('d F Y') ?></strong></span>
            <span class="mx-2">&bull;</span>
            <span>Mata Uang: <strong>Rupiah (IDR)</strong></span>
        </div>
    </div>

    <!-- 2. STATUS BANNER BALANCE -->
    <?php 
        $isNetBalanced = ($totalNetDebit == $totalNetKredit);
        $isGrossBalanced = ($totalGrossDebit == $totalGrossKredit);
    ?>
    <div class="balance-banner <?= $isNetBalanced ? '' : 'unbalanced' ?>" id="statusBanner">
        <div class="balance-icon-circle">
            <i class="fa-solid <?= $isNetBalanced ? 'fa-check' : 'fa-triangle-exclamation' ?>"></i>
        </div>
        <div>
            <div class="fw-bold text-dark fs-6" id="bannerStatusTitle">
                <?= $isNetBalanced ? '✅ Neraca Saldo BALANCE!' : '❌ Neraca Saldo TIDAK Balance!' ?>
            </div>
            <div class="small text-muted" id="bannerStatusDetail">
                Total Debit: <strong class="text-primary" id="lblBannerDebit">Rp <?= number_format($totalNetDebit, 0, ',', '.') ?></strong>
                &nbsp;|&nbsp;
                Total Kredit: <strong class="text-danger" id="lblBannerKredit">Rp <?= number_format($totalNetKredit, 0, ',', '.') ?></strong>
            </div>
        </div>
    </div>

    <!-- 3. MODE TAMPILAN (SALDO BERSIH vs MUTASI BRUTO) -->
    <div class="mode-switcher-card">
        <div class="d-flex align-items-center gap-2">
            <span class="small fw-bold text-muted"><i class="fa-solid fa-sliders me-1 text-primary"></i>Pilihan Format Tampilan:</span>
            <button type="button" class="btn-mode-pill active" id="btnModeNet" onclick="setMode('net')">
                <i class="fa-solid fa-calculator me-1"></i> 1. Saldo Bersih Akhir (Standar Laporan)
            </button>
            <button type="button" class="btn-mode-pill" id="btnModeGross" onclick="setMode('gross')">
                <i class="fa-solid fa-arrows-split-up-and-left me-1"></i> 2. Mutasi Debit &amp; Kredit Bruto
            </button>
        </div>
        <div class="small text-muted fw-bold">
            <i class="fa-solid fa-circle-info text-info me-1"></i>
            <span id="lblModeKeterangan">Menampilkan saldo akhir setiap akun pada posisi normalnya.</span>
        </div>
    </div>

    <!-- 4. TABEL NERACA SALDO TATA LETAK LAPORAN KEUANGAN -->
    <div class="trial-balance-card">
        <div class="trial-balance-card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-table text-primary"></i>
                <strong style="color: #0369a1;">Neraca Saldo Percobaan &mdash; wishuwashere. Laundry</strong>
            </div>
            <span class="badge" style="background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd; font-size:0.75rem; padding:6px 12px; border-radius:20px;">
                <?= date('d F Y') ?>
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-trial-balance align-middle" id="tableTrialBalance">
                <thead>
                    <tr>
                        <th style="width: 140px;">KODE AKUN</th>
                        <th>NAMA AKUN</th>
                        <th class="text-end" style="width: 220px;">DEBIT (RP)</th>
                        <th class="text-end" style="width: 220px;">KREDIT (RP)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($neracaSaldo)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div style="font-size:2rem; margin-bottom:8px;">📒</div>
                                <strong class="d-block mb-1">Belum Ada Transaksi</strong>
                                <small>Catat transaksi pada Jurnal Umum terlebih dahulu.</small>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                            $currentCategory = '';
                            foreach ($neracaSaldo as $row): 
                                $cat = $row['kategori'] ?? 'Lainnya';
                                if ($cat !== $currentCategory):
                                    $currentCategory = $cat;
                        ?>
                                    <tr class="category-separator-row">
                                        <td colspan="4">
                                            <i class="fa-solid fa-folder-open me-2 text-primary"></i>
                                            Klasifikasi: <?= esc($currentCategory) ?>
                                        </td>
                                    </tr>
                        <?php 
                                endif; 
                        ?>
                            <tr class="account-row" 
                                data-net-debit="<?= $row['debit'] ?>" 
                                data-net-kredit="<?= $row['kredit'] ?>"
                                data-gross-debit="<?= $row['gross_debit'] ?>" 
                                data-gross-kredit="<?= $row['gross_kredit'] ?>">
                                <td>
                                    <span class="badge" style="background:#e0f2fe; color:#0369a1; font-weight:800; font-size:0.82rem; padding:5px 10px; border-radius:6px;">
                                        <?= esc($row['kode_akun']) ?>
                                    </span>
                                </td>
                                <td class="fw-bold" style="color: #0f172a;">
                                    <?= esc($row['nama_akun']) ?>
                                </td>
                                <!-- Kolom Debit -->
                                <td class="text-end num-font col-debit">
                                    <?php if ($row['debit'] > 0): ?>
                                        <span class="fw-bold text-primary"><?= number_format($row['debit'], 0, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">&mdash;</span>
                                    <?php endif; ?>
                                </td>
                                <!-- Kolom Kredit -->
                                <td class="text-end num-font col-kredit">
                                    <?php if ($row['kredit'] > 0): ?>
                                        <span class="fw-bold text-danger"><?= number_format($row['kredit'], 0, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">&mdash;</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="accounting-total-row">
                        <td colspan="2" class="text-center" style="letter-spacing: 2px; color: #0369a1;">
                            J U M L A H
                        </td>
                        <td class="text-end num-font fs-6 text-primary" id="footerTotalDebit">
                            <?= number_format($totalNetDebit, 0, ',', '.') ?>
                        </td>
                        <td class="text-end num-font fs-6 text-danger" id="footerTotalKredit">
                            <?= number_format($totalNetKredit, 0, ',', '.') ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<!-- SCRIPT UNTUK TOGGLE FORMAT TAMPILAN SECARA INSTAN -->
<script>
    const totalNetDebit    = <?= (int)$totalNetDebit ?>;
    const totalNetKredit   = <?= (int)$totalNetKredit ?>;
    const totalGrossDebit  = <?= (int)$totalGrossDebit ?>;
    const totalGrossKredit = <?= (int)$totalGrossKredit ?>;

    function formatNumber(num) {
        if (!num || num === 0) return '&mdash;';
        return Number(num).toLocaleString('id-ID');
    }

    function setMode(mode) {
        const btnNet   = document.getElementById('btnModeNet');
        const btnGross = document.getElementById('btnModeGross');
        const lblDesc  = document.getElementById('lblModeKeterangan');
        const rows     = document.querySelectorAll('.account-row');
        const footerD  = document.getElementById('footerTotalDebit');
        const footerK  = document.getElementById('footerTotalKredit');
        const bannerD  = document.getElementById('lblBannerDebit');
        const bannerK  = document.getElementById('lblBannerKredit');

        if (mode === 'gross') {
            btnNet.classList.remove('active');
            btnGross.classList.add('active');
            lblDesc.textContent = 'Menampilkan total akumulasi mutasi Debit dan Kredit bruto.';

            rows.forEach(r => {
                const gd = parseFloat(r.getAttribute('data-gross-debit')) || 0;
                const gk = parseFloat(r.getAttribute('data-gross-kredit')) || 0;
                
                const colD = r.querySelector('.col-debit');
                const colK = r.querySelector('.col-kredit');

                colD.innerHTML = gd > 0 ? `<span class="fw-bold text-primary">${formatNumber(gd)}</span>` : '<span class="text-muted">&mdash;</span>';
                colK.innerHTML = gk > 0 ? `<span class="fw-bold text-danger">${formatNumber(gk)}</span>` : '<span class="text-muted">&mdash;</span>';
            });

            footerD.textContent = Number(totalGrossDebit).toLocaleString('id-ID');
            footerK.textContent = Number(totalGrossKredit).toLocaleString('id-ID');
            bannerD.textContent = 'Rp ' + Number(totalGrossDebit).toLocaleString('id-ID');
            bannerK.textContent = 'Rp ' + Number(totalGrossKredit).toLocaleString('id-ID');
        } else {
            btnGross.classList.remove('active');
            btnNet.classList.add('active');
            lblDesc.textContent = 'Menampilkan saldo akhir setiap akun pada posisi normalnya (Standar Laporan).';

            rows.forEach(r => {
                const nd = parseFloat(r.getAttribute('data-net-debit')) || 0;
                const nk = parseFloat(r.getAttribute('data-net-kredit')) || 0;
                
                const colD = r.querySelector('.col-debit');
                const colK = r.querySelector('.col-kredit');

                colD.innerHTML = nd > 0 ? `<span class="fw-bold text-primary">${formatNumber(nd)}</span>` : '<span class="text-muted">&mdash;</span>';
                colK.innerHTML = nk > 0 ? `<span class="fw-bold text-danger">${formatNumber(nk)}</span>` : '<span class="text-muted">&mdash;</span>';
            });

            footerD.textContent = Number(totalNetDebit).toLocaleString('id-ID');
            footerK.textContent = Number(totalNetKredit).toLocaleString('id-ID');
            bannerD.textContent = 'Rp ' + Number(totalNetDebit).toLocaleString('id-ID');
            bannerK.textContent = 'Rp ' + Number(totalNetKredit).toLocaleString('id-ID');
        }
    }
</script>

<?= $this->endSection() ?>

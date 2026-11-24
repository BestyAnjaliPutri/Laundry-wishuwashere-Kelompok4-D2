<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <style>
        .filter-card-laporan {
            background: #ffffff;
            border: 1.5px solid #bae6fd;
            padding: 16px 20px;
            margin-bottom: 20px;
        }
        .btn-cetak-laporan {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
            color: #ffffff;
            font-weight: 700;
            border: none;
            padding: 8px 18px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 12px rgba(244, 63, 94, 0.25);
            transition: all 0.2s ease;
        }
        .btn-cetak-laporan:hover {
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(244, 63, 94, 0.35);
        }
        .neraca-header-custom {
            background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%) !important;
            border-bottom: 2px solid #bae6fd !important;
            color: #0369a1 !important;
            font-weight: 800 !important;
            padding: 12px 18px !important;
        }
        .neraca-header-pasiva {
            background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%) !important;
            border-bottom: 2px solid #bae6fd !important;
            color: #0c4a6e !important;
            font-weight: 800 !important;
            padding: 12px 18px !important;
        }
    </style>

    <!-- HEADER LAPORAN -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>">wishuwashere.</a></li>
                    <li class="breadcrumb-item active">Laporan Keuangan</li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-0" style="color: #0369a1;">Laporan Keuangan Lengkap 📑</h3>
            <small class="text-muted">Standar Sistem Informasi Akuntansi (SIA) &mdash; Cetak terpisah untuk tiap laporan keuangan.</small>
        </div>
    </div>

    <!-- 4 KARTU RINGKASAN EKSEKUTIF -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 h-100 border-start border-4 border-primary">
                <div class="text-muted small fw-bold text-uppercase">Pendapatan Usaha</div>
                <h4 class="fw-bold text-primary mb-0 mt-1">Rp <?= number_format($total_pendapatan_usaha, 0, ',', '.') ?></h4>
                <small class="text-muted">Total omzet cucian</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 h-100 border-start border-4 border-success">
                <div class="text-muted small fw-bold text-uppercase">Laba Bersih Usaha</div>
                <h4 class="fw-bold text-success mb-0 mt-1">Rp <?= number_format($laba_bersih, 0, ',', '.') ?></h4>
                <small class="text-muted">Setelah beban operasional</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 h-100 border-start border-4 border-info">
                <div class="text-muted small fw-bold text-uppercase">Total Aset (Aktiva)</div>
                <h4 class="fw-bold text-dark mb-0 mt-1">Rp <?= number_format($total_aset, 0, ',', '.') ?></h4>
                <small class="text-muted">Kas, piutang &amp; mesin</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 h-100 border-start border-4 border-warning">
                <div class="text-muted small fw-bold text-uppercase">Modal Akhir Pemilik</div>
                <h4 class="fw-bold text-dark mb-0 mt-1">Rp <?= number_format($modal_akhir, 0, ',', '.') ?></h4>
                <small class="text-muted">Ekuitas wishuwashere.</small>
            </div>
        </div>
    </div>

    <!-- TABS LAPORAN KEUANGAN LENGKAP -->
    <div class="card">
        <div class="card-header p-0 bg-light">
            <ul class="nav nav-tabs border-bottom-0" id="laporanTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= ($activeTab ?? 'labarugi') === 'labarugi' ? 'active' : '' ?>" id="tab-labarugi-btn" data-bs-toggle="tab" data-bs-target="#tab-labarugi" type="button" role="tab">
                        <i class="fa-solid fa-chart-line me-1 text-primary"></i> 1. Laba Rugi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= ($activeTab ?? '') === 'neraca' ? 'active' : '' ?>" id="tab-neraca-btn" data-bs-toggle="tab" data-bs-target="#tab-neraca" type="button" role="tab">
                        <i class="fa-solid fa-scale-balanced me-1 text-success"></i> 2. Neraca Keuangan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= ($activeTab ?? '') === 'aruskas' ? 'active' : '' ?>" id="tab-aruskas-btn" data-bs-toggle="tab" data-bs-target="#tab-aruskas" type="button" role="tab">
                        <i class="fa-solid fa-money-bill-transfer me-1 text-info"></i> 3. Arus Kas (Cash Flow)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= ($activeTab ?? '') === 'ekuitas' ? 'active' : '' ?>" id="tab-ekuitas-btn" data-bs-toggle="tab" data-bs-target="#tab-ekuitas" type="button" role="tab">
                        <i class="fa-solid fa-coins me-1 text-warning"></i> 4. Perubahan Ekuitas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= ($activeTab ?? '') === 'transaksi' ? 'active' : '' ?>" id="tab-jurnal-btn" data-bs-toggle="tab" data-bs-target="#tab-jurnal" type="button" role="tab">
                        <i class="fa-solid fa-book me-1 text-secondary"></i> 5. Riwayat Transaksi
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="laporanTabsContent">
                
                <!-- ================= 1. LAPORAN LABA RUGI ================= -->
                <div class="tab-pane fade <?= ($activeTab ?? 'labarugi') === 'labarugi' ? 'show active' : '' ?>" id="tab-labarugi" role="tabpanel">
                    
                    <!-- Top Bar: Judul & Tombol Cetak Spesifik Laba Rugi -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center pb-3 mb-3 border-bottom gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span style="font-size: 1.3rem;">🌸</span>
                            <h5 class="fw-bold mb-0 text-dark">Laporan Laba Rugi (Income Statement)</h5>
                        </div>
                        <a href="<?= base_url('laporan/cetak/laba-rugi?tgl_awal=' . ($tgl_awal ?? '') . '&tgl_akhir=' . ($tgl_akhir ?? '')) ?>" target="_blank" class="btn btn-cetak-laporan">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>📄 Cetak Laba Rugi (PDF)</span>
                        </a>
                    </div>

                    <!-- Filter Tanggal Sesuai Gambar 2 -->
                    <form action="<?= base_url('laporan') ?>" method="get" class="row g-2 align-items-end mb-4 p-3 bg-light border">
                        <input type="hidden" name="tab" value="labarugi">
                        <div class="col-12 col-sm-4 col-md-3">
                            <label class="form-label small fw-bold text-muted">Tanggal Awal</label>
                            <input type="date" name="tgl_awal" class="form-control form-control-sm" value="<?= esc($tgl_awal ?? '') ?>">
                        </div>
                        <div class="col-12 col-sm-4 col-md-3">
                            <label class="form-label small fw-bold text-muted">Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" class="form-control form-control-sm" value="<?= esc($tgl_akhir ?? '') ?>">
                        </div>
                        <div class="col-12 col-sm-4 col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary fw-bold px-3">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                            </button>
                            <a href="<?= base_url('laporan?tab=labarugi') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
                        </div>
                    </form>

                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-0 text-dark">wishuwashere. Laundry</h4>
                        <h5 class="fw-bold text-primary mb-1">LAPORAN LABA RUGI</h5>
                        <small class="text-muted">
                            Periode: <?= !empty($tgl_awal) ? date('d/m/Y', strtotime($tgl_awal)) : 'Awal Usaha' ?> s/d <?= !empty($tgl_akhir) ? date('d/m/Y', strtotime($tgl_akhir)) : date('d/m/Y') ?>
                        </small>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Keterangan Akun</th>
                                    <th class="text-end" style="width: 200px;">Rincian</th>
                                    <th class="text-end" style="width: 220px;">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-light fw-bold">
                                    <td colspan="3"><i class="fa-solid fa-arrow-down-long text-success me-1"></i> PENDAPATAN OPERASIONAL</td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Pendapatan Jasa Laundry (Kas Masuk)</td>
                                    <td class="text-end">Rp <?= number_format($pendapatan_lunas, 0, ',', '.') ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Pendapatan Jasa Belum Tertagih (Piutang)</td>
                                    <td class="text-end">Rp <?= number_format($piutang_belum_lunas, 0, ',', '.') ?></td>
                                    <td></td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="ps-4">Total Pendapatan Operasional</td>
                                    <td></td>
                                    <td class="text-end text-success">Rp <?= number_format($total_pendapatan_usaha, 0, ',', '.') ?></td>
                                </tr>

                                <tr class="table-light fw-bold">
                                    <td colspan="3"><i class="fa-solid fa-arrow-up-long text-danger me-1"></i> BEBAN OPERASIONAL USAHA</td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Beban Pemakaian Bahan Cuci (Deterjen, Pewangi, Softener)</td>
                                    <td class="text-end">Rp <?= number_format($beban_deterjen, 0, ',', '.') ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Beban Listrik, Air &amp; Gas Pengering</td>
                                    <td class="text-end">Rp <?= number_format($beban_listrik_air, 0, ',', '.') ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Beban Perawatan &amp; Maintenance Mesin Cuci</td>
                                    <td class="text-end">Rp <?= number_format($beban_mesin, 0, ',', '.') ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Beban Plastik Packing &amp; Operasional Lainnya</td>
                                    <td class="text-end">Rp <?= number_format($beban_lain, 0, ',', '.') ?></td>
                                    <td></td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="ps-4">Total Beban Operasional</td>
                                    <td></td>
                                    <td class="text-end text-danger">(Rp <?= number_format($total_beban, 0, ',', '.') ?>)</td>
                                </tr>

                                <tr class="table-success fw-bold fs-5">
                                    <td class="py-3">
                                        <i class="fa-solid fa-trophy text-warning me-1"></i>
                                        <?= $laba_bersih >= 0 ? 'LABA BERSIH USAHA (NET PROFIT)' : 'RUGI BERSIH USAHA' ?>
                                    </td>
                                    <td></td>
                                    <td class="text-end py-3 text-success">Rp <?= number_format($laba_bersih, 0, ',', '.') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= 2. NERACA KEUANGAN ================= -->
                <div class="tab-pane fade <?= ($activeTab ?? '') === 'neraca' ? 'show active' : '' ?>" id="tab-neraca" role="tabpanel">
                    
                    <!-- Top Bar: Judul & Tombol Cetak Spesifik Neraca -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center pb-3 mb-3 border-bottom gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span style="font-size: 1.3rem;">⚖️</span>
                            <h5 class="fw-bold mb-0 text-dark">Neraca Keuangan (Balance Sheet)</h5>
                        </div>
                        <a href="<?= base_url('laporan/cetak/neraca?tgl_awal=' . ($tgl_awal ?? '') . '&tgl_akhir=' . ($tgl_akhir ?? '')) ?>" target="_blank" class="btn btn-cetak-laporan">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>📄 Cetak Neraca Keuangan (PDF)</span>
                        </a>
                    </div>

                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-0 text-dark">wishuwashere. Laundry</h4>
                        <h5 class="fw-bold text-success mb-1">NERACA KEUANGAN (BALANCE SHEET)</h5>
                        <small class="text-muted">Per Tanggal <?= date('d F Y') ?></small>
                    </div>

                    <div class="row g-4">
                        <!-- SISI KIRI: ASET / AKTIVA (WARNA TULISAN BIRU GELAP JELAS - TIDAK PUTIH) -->
                        <div class="col-12 col-lg-6">
                            <div class="card h-100 border-2" style="border-color: #bae6fd;">
                                <div class="card-header neraca-header-custom">
                                    <i class="fa-solid fa-vault me-1"></i> ASET (AKTIVA)
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>AKUN ASET</th>
                                                <th class="text-end" style="width: 160px;">JUMLAH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="fw-bold" style="background: #f8fdff; color: #0369a1;">
                                                <td colspan="2"><i class="fa-solid fa-circle-dot me-1 text-info small"></i> Aset Lancar</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">Kas di Tangan / Bank</td>
                                                <td class="text-end">Rp <?= number_format($kas, 0, ',', '.') ?></td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">Piutang Usaha Pelanggan</td>
                                                <td class="text-end">Rp <?= number_format($piutang, 0, ',', '.') ?></td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">Persediaan Deterjen &amp; Perlengkapan</td>
                                                <td class="text-end">Rp <?= number_format($persediaan, 0, ',', '.') ?></td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">Sewa Dibayar di Muka</td>
                                                <td class="text-end">Rp <?= number_format($sewa_dibayar_muka ?? 0, 0, ',', '.') ?></td>
                                            </tr>
                                            <tr class="fw-bold">
                                                <td class="ps-4 text-primary">Total Aset Lancar</td>
                                                <td class="text-end text-primary">Rp <?= number_format($total_aset_lancar, 0, ',', '.') ?></td>
                                            </tr>

                                            <tr class="fw-bold" style="background: #f8fdff; color: #0369a1;">
                                                <td colspan="2"><i class="fa-solid fa-circle-dot me-1 text-info small"></i> Aset Tetap</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">Peralatan Mesin Cuci &amp; Dryer</td>
                                                <td class="text-end">Rp <?= number_format($peralatan_mesin, 0, ',', '.') ?></td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">Akumulasi Penyusutan Mesin</td>
                                                <td class="text-end text-danger">(Rp <?= number_format(abs($akumulasi_penyusutan), 0, ',', '.') ?>)</td>
                                            </tr>
                                            <tr class="fw-bold">
                                                <td class="ps-4 text-primary">Total Aset Tetap</td>
                                                <td class="text-end text-primary">Rp <?= number_format($total_aset_tetap, 0, ',', '.') ?></td>
                                            </tr>

                                            <tr class="fw-bold fs-6" style="background: #e0f2fe; color: #0369a1;">
                                                <td class="py-3">TOTAL ASET</td>
                                                <td class="text-end py-3">Rp <?= number_format($total_aset, 0, ',', '.') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- SISI KANAN: LIABILITAS & EKUITAS (WARNA TULISAN GELAP JELAS - TIDAK PUTIH) -->
                        <div class="col-12 col-lg-6">
                            <div class="card h-100 border-2" style="border-color: #bae6fd;">
                                <div class="card-header neraca-header-pasiva">
                                    <i class="fa-solid fa-coins me-1"></i> LIABILITAS &amp; EKUITAS (PASIVA)
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>AKUN LIABILITAS &amp; EKUITAS</th>
                                                <th class="text-end" style="width: 160px;">JUMLAH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="fw-bold" style="background: #f8fdff; color: #0c4a6e;">
                                                <td colspan="2"><i class="fa-solid fa-circle-dot me-1 text-warning small"></i> Liabilitas (Kewajiban)</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">Utang Usaha / Operasional</td>
                                                <td class="text-end">Rp <?= number_format($utang_usaha, 0, ',', '.') ?></td>
                                            </tr>
                                            <?php if (!empty($beban_terutang)): ?>
                                            <tr>
                                                <td class="ps-4">Beban Terutang (Accrued Expense)</td>
                                                <td class="text-end">Rp <?= number_format($beban_terutang, 0, ',', '.') ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if (!empty($pendapatan_dimuka)): ?>
                                            <tr>
                                                <td class="ps-4">Pendapatan Diterima di Muka</td>
                                                <td class="text-end">Rp <?= number_format($pendapatan_dimuka, 0, ',', '.') ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <tr class="fw-bold">
                                                <td class="ps-4 text-danger">Total Liabilitas</td>
                                                <td class="text-end text-danger">Rp <?= number_format($total_liabilitas ?? $utang_usaha, 0, ',', '.') ?></td>
                                            </tr>

                                            <tr class="fw-bold" style="background: #f8fdff; color: #0c4a6e;">
                                                <td colspan="2"><i class="fa-solid fa-circle-dot me-1 text-success small"></i> Ekuitas (Modal)</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">Modal Pemilik (Modal Awal)</td>
                                                <td class="text-end">Rp <?= number_format($modal_awal, 0, ',', '.') ?></td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4">Laba Bersih Berjalan</td>
                                                <td class="text-end">Rp <?= number_format($laba_bersih, 0, ',', '.') ?></td>
                                            </tr>
                                            <?php if (!empty($prive)): ?>
                                            <tr>
                                                <td class="ps-4">Prive Pemilik wishuwashere.</td>
                                                <td class="text-end text-danger">(Rp <?= number_format($prive, 0, ',', '.') ?>)</td>
                                            </tr>
                                            <?php endif; ?>
                                            <tr class="fw-bold">
                                                <td class="ps-4 text-success">Total Ekuitas Pemilik</td>
                                                <td class="text-end text-success">Rp <?= number_format($modal_akhir, 0, ',', '.') ?></td>
                                            </tr>

                                            <tr class="fw-bold fs-6" style="background: #e0f2fe; color: #0c4a6e;">
                                                <td class="py-3">TOTAL LIABILITAS &amp; EKUITAS</td>
                                                <td class="text-end py-3">Rp <?= number_format($total_liabilitas_ekuitas, 0, ',', '.') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3 p-2 bg-success-subtle text-success border border-success fw-bold">
                        <i class="fa-solid fa-circle-check me-1"></i> STATUS NERACA: BALANCED (Total Aset = Total Liabilitas &amp; Ekuitas = Rp <?= number_format($total_aset, 0, ',', '.') ?>)
                    </div>
                </div>

                <!-- ================= 3. LAPORAN ARUS KAS ================= -->
                <div class="tab-pane fade <?= ($activeTab ?? '') === 'aruskas' ? 'show active' : '' ?>" id="tab-aruskas" role="tabpanel">
                    
                    <!-- Top Bar: Judul & Tombol Cetak Spesifik Arus Kas -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center pb-3 mb-3 border-bottom gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span style="font-size: 1.3rem;">💵</span>
                            <h5 class="fw-bold mb-0 text-dark">Laporan Arus Kas (Cash Flow Statement)</h5>
                        </div>
                        <a href="<?= base_url('laporan/cetak/arus-kas?tgl_awal=' . ($tgl_awal ?? '') . '&tgl_akhir=' . ($tgl_akhir ?? '')) ?>" target="_blank" class="btn btn-cetak-laporan">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>📄 Cetak Arus Kas (PDF)</span>
                        </a>
                    </div>

                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-0 text-dark">wishuwashere. Laundry</h4>
                        <h5 class="fw-bold text-info mb-1">LAPORAN ARUS KAS</h5>
                        <small class="text-muted">Metode Langsung (Direct Method) &mdash; Periode Tahun <?= date('Y') ?></small>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Aktivitas Arus Kas</th>
                                    <th class="text-end" style="width: 200px;">Rincian</th>
                                    <th class="text-end" style="width: 220px;">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-light fw-bold">
                                    <td colspan="3"><i class="fa-solid fa-circle-check text-primary me-1"></i> 1. ARUS KAS DARI AKTIVITAS OPERASI</td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Penerimaan Kas dari Pelanggan Laundry</td>
                                    <td class="text-end">Rp <?= number_format($pendapatan_lunas, 0, ',', '.') ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Pengeluaran Kas untuk Beban Operasional (Bahan, Listrik, Air)</td>
                                    <td class="text-end">(Rp <?= number_format($total_beban, 0, ',', '.') ?>)</td>
                                    <td></td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="ps-4 text-primary">Arus Kas Bersih dari Aktivitas Operasi</td>
                                    <td></td>
                                    <td class="text-end text-primary">Rp <?= number_format($arus_kas_operasi, 0, ',', '.') ?></td>
                                </tr>

                                <tr class="table-light fw-bold">
                                    <td colspan="3"><i class="fa-solid fa-circle-check text-success me-1"></i> 2. ARUS KAS DARI AKTIVITAS INVESTASI</td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Pembelian Mesin Cuci &amp; Peralatan Usaha</td>
                                    <td class="text-end">Rp 0</td>
                                    <td></td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="ps-4 text-success">Arus Kas Bersih dari Aktivitas Investasi</td>
                                    <td></td>
                                    <td class="text-end text-success">Rp 0</td>
                                </tr>

                                <tr class="table-light fw-bold">
                                    <td colspan="3"><i class="fa-solid fa-circle-check text-warning me-1"></i> 3. ARUS KAS DARI AKTIVITAS PENDANAAN</td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Penerimaan Setoran Modal Pemilik / Prive</td>
                                    <td class="text-end">Rp 0</td>
                                    <td></td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="ps-4 text-warning">Arus Kas Bersih dari Aktivitas Pendanaan</td>
                                    <td></td>
                                    <td class="text-end text-warning">Rp 0</td>
                                </tr>

                                <tr class="table-primary fw-bold">
                                    <td>KENAIKAN (PENURUNAN) BERSIH KAS</td>
                                    <td></td>
                                    <td class="text-end">Rp <?= number_format($arus_kas_operasi, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Saldo Kas Awal Periode</td>
                                    <td></td>
                                    <td class="text-end">Rp <?= number_format($saldo_kas_awal, 0, ',', '.') ?></td>
                                </tr>
                                <tr class="table-success fw-bold fs-5">
                                    <td class="py-3">SALDO KAS AKHIR PERIODE</td>
                                    <td></td>
                                    <td class="text-end py-3 text-success">Rp <?= number_format($saldo_kas_akhir, 0, ',', '.') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= 4. PERUBAHAN EKUITAS ================= -->
                <div class="tab-pane fade <?= ($activeTab ?? '') === 'ekuitas' ? 'show active' : '' ?>" id="tab-ekuitas" role="tabpanel">
                    
                    <!-- Top Bar: Judul & Tombol Cetak Spesifik Perubahan Modal -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center pb-3 mb-3 border-bottom gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span style="font-size: 1.3rem;">🪙</span>
                            <h5 class="fw-bold mb-0 text-dark">Laporan Perubahan Modal (Equity Statement)</h5>
                        </div>
                        <a href="<?= base_url('laporan/cetak/ekuitas?tgl_awal=' . ($tgl_awal ?? '') . '&tgl_akhir=' . ($tgl_akhir ?? '')) ?>" target="_blank" class="btn btn-cetak-laporan">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>📄 Cetak Perubahan Modal (PDF)</span>
                        </a>
                    </div>

                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-0 text-dark">wishuwashere. Laundry</h4>
                        <h5 class="fw-bold text-warning mb-1">LAPORAN PERUBAHAN EKUITAS</h5>
                        <small class="text-muted">Periode Tahun <?= date('Y') ?></small>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Keterangan Perubahan Modal</th>
                                    <th class="text-end" style="width: 250px;">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Modal Awal Pemilik wishuwashere.</td>
                                    <td class="text-end">Rp <?= number_format($modal_awal, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td>Laba Bersih Berjalan (Penambah Modal)</td>
                                    <td class="text-end text-success">Rp <?= number_format($laba_bersih, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td>Pengambilan Pribadi (Prive Pemilik - Pengurang Modal)</td>
                                    <td class="text-end text-danger">(Rp <?= number_format($prive, 0, ',', '.') ?>)</td>
                                </tr>
                                <tr class="fw-bold table-light">
                                    <td>Penambahan Bersih Modal Periode Ini</td>
                                    <td class="text-end text-primary">Rp <?= number_format($laba_bersih - $prive, 0, ',', '.') ?></td>
                                </tr>
                                <tr class="table-warning fw-bold fs-5">
                                    <td class="py-3">MODAL AKHIR PEMILIK (PER <?= date('d F Y') ?>)</td>
                                    <td class="text-end py-3 text-dark">Rp <?= number_format($modal_akhir, 0, ',', '.') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= 5. RIWAYAT TRANSAKSI ================= -->
                <div class="tab-pane fade <?= ($activeTab ?? '') === 'transaksi' ? 'show active' : '' ?>" id="tab-jurnal" role="tabpanel">
                    
                    <!-- Top Bar: Judul & Tombol Cetak Spesifik Rekap Transaksi -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center pb-3 mb-3 border-bottom gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span style="font-size: 1.3rem;">📋</span>
                            <h5 class="fw-bold mb-0 text-dark">Rekap Riwayat Transaksi Cucian</h5>
                        </div>
                        <a href="<?= base_url('laporan/cetak/transaksi?tgl_awal=' . ($tgl_awal ?? '') . '&tgl_akhir=' . ($tgl_akhir ?? '')) ?>" target="_blank" class="btn btn-cetak-laporan">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>📄 Cetak Rekap Transaksi (PDF)</span>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Nota</th>
                                    <th>Waktu Masuk</th>
                                    <th>Pelanggan</th>
                                    <th>Paket Layanan</th>
                                    <th class="text-end">Total Biaya</th>
                                    <th>Status Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transaksi)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            Belum ada data transaksi untuk ditampilkan.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transaksi as $t): ?>
                                        <tr>
                                            <td><strong class="text-primary">#<?= esc($t['kode_transaksi']) ?></strong></td>
                                            <td><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
                                            <td><?= esc($t['nama_pelanggan']) ?></td>
                                            <td>
                                                <?= esc($t['nama_paket']) ?>
                                                <small class="text-muted">(<?= esc($t['berat_qty']) ?> <?= $t['jenis_paket'] === 'kiloan' ? 'Kg' : 'Pcs' ?>)</small>
                                            </td>
                                            <td class="text-end fw-bold">Rp <?= number_format($t['total_biaya'], 0, ',', '.') ?></td>
                                            <td>
                                                <span class="badge <?= $t['status_bayar'] === 'Lunas' ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= esc($t['status_bayar']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

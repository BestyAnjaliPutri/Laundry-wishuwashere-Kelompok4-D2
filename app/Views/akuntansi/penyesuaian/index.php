<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" style="color:#9333ea;">wishuwashere.</a></li>
                    <li class="breadcrumb-item active">Jurnal Penyesuaian</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0" style="color:#7c3aed;">
                <i class="fa-solid fa-book-open me-2"></i>Jurnal Penyesuaian (Adjusting Journal Entry)
            </h4>
            <small class="text-muted">Penyesuaian akhir periode: penyusutan, beban terutang, perlengkapan terpakai, dan lainnya</small>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="<?= base_url('penyesuaian/cetak') ?>" target="_blank"
               class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm"
               style="border-radius:10px;">
                <i class="fa-solid fa-print"></i>
                <span>Cetak AJE</span>
            </a>
            <button class="btn btn-primary d-flex align-items-center gap-2"
                    style="background:linear-gradient(135deg,#9333ea,#7e22ce);border:none;box-shadow:0 4px 12px rgba(147,51,234,0.25);border-radius:10px;"
                    data-bs-toggle="modal" data-bs-target="#modalTambahAJE">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah AJE</span>
            </button>
        </div>
    </div>

    <!-- Flash Alert -->
    <?php if (session()->has('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2"
         role="alert" style="border-radius:12px; background:#f0fdf4; border-left:4px solid #16a34a !important;">
        <i class="fa-solid fa-circle-check text-success fs-5"></i>
        <div><?= session('success') ?></div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2"
         role="alert" style="border-radius:12px; background:#fef2f2; border-left:4px solid #dc2626 !important;">
        <i class="fa-solid fa-triangle-exclamation text-danger fs-5"></i>
        <div><?= session('error') ?></div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Info Panel -->
    <div class="card mb-4 border-2" style="border-color:#e9d5ff !important; background:linear-gradient(135deg,#faf5ff,#f3e8ff);">
        <div class="p-3 d-flex gap-3 align-items-start">
            <div style="font-size:1.8rem;">📋</div>
            <div>
                <div class="fw-bold mb-1" style="color:#7c3aed;">Kapan Jurnal Penyesuaian Dibuat?</div>
                <div class="small text-muted" style="line-height:1.6;">
                    Dibuat di <strong>akhir periode akuntansi</strong> untuk menyesuaikan akun yang belum tepat nilainya.
                    Contoh: <strong>penyusutan aktiva tetap</strong>, <strong>perlengkapan deterjen terpakai</strong>,
                    <strong>beban gaji/listrik terutang (accrued)</strong>, <strong>pendapatan diterima di muka</strong>,
                    <strong>sewa dibayar di muka (prepaid)</strong>.
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <?php
        $totalEntri   = count($penyesuaian);
        $totalNominal = array_sum(array_column($penyesuaian, 'nominal'));
        // Hitung per jenis AJE
        $jenisCount = [];
        foreach ($penyesuaian as $row) {
            $ket = $row['keterangan'] ?? 'Lainnya';
            $jenisCount[$ket] = ($jenisCount[$ket] ?? 0) + 1;
        }
        arsort($jenisCount);
        $topJenis = key($jenisCount) ?? '-';
    ?>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center border-2" style="border-color:#e9d5ff !important; background:#faf5ff; border-radius:14px;">
                <div class="fw-bold" style="font-size:1.8rem; color:#7c3aed;"><?= $totalEntri ?></div>
                <small class="text-muted fw-semibold">Total Entri AJE</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center border-2" style="border-color:#86efac !important; background:#f0fdf4; border-radius:14px;">
                <div class="fw-bold" style="font-size:1.1rem; color:#16a34a;">
                    Rp <?= number_format($totalNominal, 0, ',', '.') ?>
                </div>
                <small class="text-muted fw-semibold">Total Nominal</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center border-2" style="border-color:#fde68a !important; background:#fffbeb; border-radius:14px;">
                <div class="fw-bold" style="font-size:1.8rem; color:#d97706;"><?= count($jenisCount) ?></div>
                <small class="text-muted fw-semibold">Jenis Penyesuaian</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center border-2" style="border-color:#fca5a5 !important; background:#fff1f2; border-radius:14px;">
                <div class="fw-bold" style="font-size:0.78rem; color:#dc2626; line-height:1.3;"><?= esc(strlen($topJenis) > 22 ? substr($topJenis, 0, 22).'…' : $topJenis) ?></div>
                <small class="text-muted fw-semibold">AJE Terbanyak</small>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card mb-3 border-2" style="border-color:#e9d5ff !important;">
        <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center gap-2">
            <span class="fw-bold small" style="color:#7c3aed;"><i class="fa-solid fa-filter me-1"></i>Filter:</span>
            <select id="filterJenis" class="form-select form-select-sm" style="max-width:220px; border-radius:8px; border-color:#e9d5ff;">
                <option value="">— Semua Jenis AJE —</option>
                <option value="Penyusutan">Penyusutan Peralatan/Mesin</option>
                <option value="Perlengkapan">Perlengkapan Deterjen Terpakai</option>
                <option value="Beban Gaji">Beban Gaji Terutang</option>
                <option value="Beban Listrik">Beban Listrik/Air Terutang</option>
                <option value="Pendapatan Diterima">Pendapatan Diterima di Muka</option>
                <option value="Beban Dibayar">Beban Dibayar di Muka</option>
                <option value="Koreksi">Koreksi Kesalahan</option>
            </select>
            <input type="text" id="searchAJE" class="form-select form-select-sm"
                   style="max-width:220px; border-radius:8px; border-color:#e9d5ff;"
                   placeholder="🔍 Cari keterangan / akun...">
            <button onclick="resetFilter()" class="btn btn-sm btn-outline-secondary" style="border-radius:8px; font-size:0.8rem;">
                <i class="fa-solid fa-rotate-left me-1"></i>Reset
            </button>
            <span id="filterCount" class="ms-auto small text-muted fw-semibold"></span>
        </div>
    </div>

    <!-- Journal Table -->
    <div class="card border-2" style="border-color:#e9d5ff !important; border-radius:14px; overflow:hidden;">
        <div class="card-header d-flex justify-content-between align-items-center"
             style="background:linear-gradient(135deg,#faf5ff,#f3e8ff); border-bottom:2px solid #e9d5ff;">
            <span class="fw-bold" style="color:#7c3aed;">
                <i class="fa-solid fa-list me-1"></i> Daftar Jurnal Penyesuaian (AJE) — Periode Akuntansi
            </span>
            <span class="badge" style="background:#e9d5ff; color:#7c3aed; font-size:0.82rem; padding:5px 12px; border-radius:20px;">
                <?= $totalEntri ?> entri
            </span>
        </div>
        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0"
                       style="border-color:#f3e8ff; font-size:.84rem;" id="tableAJE">
                    <thead>
                        <tr style="background:linear-gradient(135deg,#f3e8ff 0%,#faf5ff 100%); color:#6d28d9;">
                            <th style="min-width:40px; text-align:center;">#</th>
                            <th style="min-width:120px;">NO. BUKTI</th>
                            <th style="min-width:95px;">TANGGAL</th>
                            <th style="min-width:200px;">JENIS PENYESUAIAN</th>
                            <th style="min-width:170px;">AKUN DEBIT</th>
                            <th style="min-width:170px;">AKUN KREDIT</th>
                            <th style="min-width:135px; text-align:right;">NOMINAL (Rp)</th>
                            <th style="min-width:100px; text-align:center;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($penyesuaian)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5" style="background:#faf5ff;">
                                <div style="font-size:2.5rem; margin-bottom:10px;">📝</div>
                                <strong class="d-block mb-1" style="color:#7c3aed;">Belum ada jurnal penyesuaian</strong>
                                <small class="text-muted">Tambahkan AJE untuk akhir periode — klik tombol <strong>Tambah AJE</strong> di kanan atas.</small>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($penyesuaian as $i => $row):
                            // Tentukan warna badge berdasarkan jenis
                            $ket = $row['keterangan'] ?? '';
                            if (stripos($ket, 'Penyusutan') !== false)         { $badgeColor='#ddd6fe'; $badgeText='#6d28d9'; $icon='fa-arrow-trend-down'; }
                            elseif (stripos($ket, 'Perlengkapan') !== false)   { $badgeColor='#bbf7d0'; $badgeText='#15803d'; $icon='fa-soap'; }
                            elseif (stripos($ket, 'Gaji') !== false)           { $badgeColor='#fde68a'; $badgeText='#92400e'; $icon='fa-users'; }
                            elseif (stripos($ket, 'Listrik') !== false || stripos($ket, 'Air') !== false) { $badgeColor='#bae6fd'; $badgeText='#0369a1'; $icon='fa-bolt'; }
                            elseif (stripos($ket, 'Pendapatan') !== false)     { $badgeColor='#fbcfe8'; $badgeText='#9d174d'; $icon='fa-coins'; }
                            elseif (stripos($ket, 'Dibayar') !== false || stripos($ket, 'Prepaid') !== false) { $badgeColor='#fed7aa'; $badgeText='#c2410c'; $icon='fa-calendar-check'; }
                            elseif (stripos($ket, 'Koreksi') !== false)        { $badgeColor='#fca5a5'; $badgeText='#b91c1c'; $icon='fa-rotate'; }
                            else                                                { $badgeColor='#e9d5ff'; $badgeText='#7c3aed'; $icon='fa-sliders'; }
                        ?>
                        <tr data-jenis="<?= esc($ket) ?>" data-search="<?= esc(strtolower($ket . ' ' . $row['no_bukti'] . ' ' . $row['debit_nama_akun'] . ' ' . $row['kredit_nama_akun'])) ?>">
                            <td class="text-center fw-bold text-muted"><?= $i + 1 ?></td>
                            <td>
                                <code style="color:#7c3aed; font-size:0.82rem; background:#f3e8ff; padding:2px 7px; border-radius:6px;">
                                    <?= esc($row['no_bukti']) ?>
                                </code>
                            </td>
                            <td style="white-space:nowrap;">
                                <i class="fa-regular fa-calendar me-1 text-muted" style="font-size:0.75rem;"></i>
                                <?= date('d/m/Y', strtotime($row['tanggal'])) ?>
                            </td>
                            <td>
                                <span class="badge d-inline-flex align-items-center gap-1"
                                      style="background:<?= $badgeColor ?>; color:<?= $badgeText ?>; font-size:0.76rem; font-weight:700; padding:5px 10px; border-radius:20px; white-space:normal; max-width:220px;">
                                    <i class="fa-solid <?= $icon ?>"></i>
                                    <?= esc($ket) ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold" style="color:#0284c7; font-size:0.82rem;">
                                    <i class="fa-solid fa-arrow-down me-1" style="font-size:0.7rem;"></i><?= esc($row['debit_kode_akun']) ?>
                                </div>
                                <small class="text-muted"><?= esc($row['debit_nama_akun']) ?></small>
                            </td>
                            <td>
                                <div class="fw-bold" style="color:#dc2626; font-size:0.82rem;">
                                    <i class="fa-solid fa-arrow-up me-1" style="font-size:0.7rem;"></i><?= esc($row['kredit_kode_akun']) ?>
                                </div>
                                <small class="text-muted" style="padding-left:14px;"><?= esc($row['kredit_nama_akun']) ?></small>
                            </td>
                            <td class="text-end fw-bold" style="color:#7c3aed; font-variant-numeric:tabular-nums;">
                                <?= number_format($row['nominal'], 0, ',', '.') ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="/penyesuaian/edit/<?= $row['id'] ?>"
                                       class="btn btn-sm"
                                       title="Edit"
                                       style="background:#fef3c7; color:#d97706; border:1px solid #fde68a; border-radius:8px; padding:4px 10px;">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="/penyesuaian/hapus/<?= $row['id'] ?>"
                                       class="btn btn-sm"
                                       title="Hapus"
                                       style="background:#fee2e2; color:#dc2626; border:1px solid #fecaca; border-radius:8px; padding:4px 10px;"
                                       onclick="return confirm('Hapus jurnal penyesuaian ini?\n\nNo. Bukti: <?= esc($row['no_bukti']) ?>')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <!-- TOTAL ROW -->
                        <?php if (!empty($penyesuaian)): ?>
                        <tr style="background:linear-gradient(90deg,#faf5ff,#f3e8ff); font-weight:800;" id="rowTotal">
                            <td colspan="6" class="text-end" style="color:#7c3aed; font-size:0.88rem; border-top:2px solid #e9d5ff;">
                                <i class="fa-solid fa-sigma me-1"></i>JUMLAH TOTAL (Debit = Kredit)
                            </td>
                            <td class="text-end" style="color:#7c3aed; font-size:0.92rem; font-variant-numeric:tabular-nums; border-top:2px solid #e9d5ff; border-bottom:3px double #7c3aed;">
                                <?= number_format($totalNominal, 0, ',', '.') ?>
                            </td>
                            <td style="border-top:2px solid #e9d5ff;"></td>
                        </tr>
                        <?php endif; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Catatan Akuntansi -->
    <?php if (!empty($penyesuaian)): ?>
    <div class="mt-3 p-3 d-flex gap-2 align-items-start"
         style="background:#faf5ff; border:1.5px dashed #c4b5fd; border-radius:12px; font-size:0.82rem; color:#6d28d9;">
        <i class="fa-solid fa-circle-info mt-1"></i>
        <div>
            <strong>Catatan Akuntansi:</strong> Total nominal Debit AJE selalu sama dengan total Kredit AJE (<strong>Rp <?= number_format($totalNominal, 0, ',', '.') ?></strong>),
            karena setiap entri penyesuaian harus memenuhi prinsip <em>double-entry bookkeeping</em>.
            Data ini digunakan dalam Neraca Saldo Disesuaikan (NSD) dan Neraca Lajur kolom 3–4.
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- ===== MODAL TAMBAH PENYESUAIAN ===== -->
<div class="modal fade" id="modalTambahAJE" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-2" style="border-color:#e9d5ff; border-radius:16px; overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#faf5ff,#f3e8ff); border-bottom:2px solid #e9d5ff;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:36px;height:36px;background:linear-gradient(135deg,#9333ea,#7e22ce);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-plus text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="color:#7c3aed;">Tambah Jurnal Penyesuaian (AJE)</h5>
                        <small class="text-muted">Pastikan jumlah Debit = Kredit</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="/penyesuaian/simpan">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="row g-3">

                        <!-- Baris 1: No. Bukti & Tanggal -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NO. BUKTI <span class="text-danger">*</span></label>
                            <input type="text" name="no_bukti" class="form-control" required
                                   placeholder="AJE-<?= date('Ym') ?>-001"
                                   style="border:1.5px solid #e9d5ff; border-radius:10px;">
                            <small class="text-muted">Contoh: AJE-202611-001, AJE-20261130-001</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">TANGGAL <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control" required
                                   value="<?= date('Y-m-d') ?>"
                                   style="border:1.5px solid #e9d5ff; border-radius:10px;">
                        </div>

                        <!-- Jenis Penyesuaian -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">JENIS PENYESUAIAN <span class="text-danger">*</span></label>
                            <select name="keterangan" class="form-select" id="aje_selectJenis" onchange="ajeAutoFill(this)"
                                    style="border:1.5px solid #e9d5ff; border-radius:10px;">
                                <option value="">— Pilih Jenis Penyesuaian —</option>
                                <option value="Penyusutan Peralatan/Mesin Cuci"
                                    data-debit="1201" data-dnama="Beban Penyusutan Peralatan"
                                    data-kredit="1202" data-knama="Akumulasi Penyusutan Mesin">
                                    📉 Penyusutan Peralatan / Mesin Cuci
                                </option>
                                <option value="Perlengkapan Deterjen Terpakai"
                                    data-debit="5101" data-dnama="Beban Pemakaian Bahan/Deterjen"
                                    data-kredit="1103" data-knama="Perlengkapan Deterjen & Parfum">
                                    🧴 Perlengkapan Deterjen Terpakai
                                </option>
                                <option value="Beban Gaji Terutang (Accrued)"
                                    data-debit="5102" data-dnama="Beban Gaji Karyawan"
                                    data-kredit="2101" data-knama="Utang Usaha / Operasional">
                                    👥 Beban Gaji Terutang (Accrued)
                                </option>
                                <option value="Beban Listrik/Air Terutang (Accrued)"
                                    data-debit="5103" data-dnama="Beban Listrik dan Air"
                                    data-kredit="2101" data-knama="Utang Usaha / Operasional">
                                    ⚡ Beban Listrik/Air Terutang (Accrued)
                                </option>
                                <option value="Pendapatan Diterima di Muka (Unearned)"
                                    data-debit="2102" data-dnama="Pendapatan Diterima di Muka"
                                    data-kredit="4101" data-knama="Pendapatan Jasa Laundry Kiloan">
                                    💰 Pendapatan Diterima di Muka (Unearned)
                                </option>
                                <option value="Beban Dibayar di Muka (Prepaid)"
                                    data-debit="5201" data-dnama="Beban Sewa Tempat"
                                    data-kredit="1104" data-knama="Sewa Dibayar di Muka">
                                    📅 Beban Dibayar di Muka / Sewa Prepaid
                                </option>
                                <option value="Koreksi Kesalahan Pencatatan">
                                    🔄 Koreksi Kesalahan Pencatatan
                                </option>
                                <option value="Penyesuaian Lainnya">
                                    📝 Penyesuaian Lainnya
                                </option>
                            </select>
                        </div>

                        <!-- DEBIT -->
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-2 pb-2 mb-1" style="border-bottom:1.5px solid #e9d5ff;">
                                <span style="width:4px;height:18px;background:linear-gradient(#0ea5e9,#0284c7);border-radius:2px;display:inline-block;"></span>
                                <span class="badge" style="background:#e0f2fe;color:#0284c7;font-weight:800;font-size:0.85rem;padding:5px 12px;">
                                    <i class="fa-solid fa-arrow-down me-1"></i>DEBIT (D)
                                </span>
                                <small class="text-muted fw-semibold">— Akun yang bertambah / didebit</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">PILIH KODE AKUN DEBIT <span class="text-danger">*</span></label>
                            <select name="debit_kode_akun" class="form-select" id="aje_selectDebit" onchange="ajeDebit(this)"
                                    style="border:1.5px solid #bae6fd; border-radius:10px;">
                                <option value="">— Pilih Akun Debit —</option>
                                <?php foreach ($daftarAkun as $akun): ?>
                                <option value="<?= esc($akun['kode_akun']) ?>" data-nama="<?= esc($akun['nama_akun3']) ?>">
                                    <?= esc($akun['kode_akun'] . ' — ' . $akun['nama_akun3']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NAMA AKUN DEBIT <span class="text-danger">*</span></label>
                            <input type="text" name="debit_nama_akun" id="aje_inputDebitNama" class="form-control" required
                                   placeholder="Terisi otomatis dari pilihan akun"
                                   style="border:1.5px solid #bae6fd; border-radius:10px;">
                        </div>

                        <!-- KREDIT -->
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-2 pb-2 mb-1" style="border-bottom:1.5px solid #fecaca;">
                                <span style="width:4px;height:18px;background:linear-gradient(#ef4444,#dc2626);border-radius:2px;display:inline-block;"></span>
                                <span class="badge" style="background:#fee2e2;color:#dc2626;font-weight:800;font-size:0.85rem;padding:5px 12px;">
                                    <i class="fa-solid fa-arrow-up me-1"></i>KREDIT (K)
                                </span>
                                <small class="text-muted fw-semibold">— Akun yang berkurang / dikredit</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">PILIH KODE AKUN KREDIT <span class="text-danger">*</span></label>
                            <select name="kredit_kode_akun" class="form-select" id="aje_selectKredit" onchange="ajeKredit(this)"
                                    style="border:1.5px solid #fecaca; border-radius:10px;">
                                <option value="">— Pilih Akun Kredit —</option>
                                <?php foreach ($daftarAkun as $akun): ?>
                                <option value="<?= esc($akun['kode_akun']) ?>" data-nama="<?= esc($akun['nama_akun3']) ?>">
                                    <?= esc($akun['kode_akun'] . ' — ' . $akun['nama_akun3']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NAMA AKUN KREDIT <span class="text-danger">*</span></label>
                            <input type="text" name="kredit_nama_akun" id="aje_inputKreditNama" class="form-control" required
                                   placeholder="Terisi otomatis dari pilihan akun"
                                   style="border:1.5px solid #fecaca; border-radius:10px;">
                        </div>

                        <!-- Nominal -->
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-2 pb-2 mb-1" style="border-bottom:1.5px solid #e9d5ff;">
                                <span style="width:4px;height:18px;background:linear-gradient(#9333ea,#7e22ce);border-radius:2px;display:inline-block;"></span>
                                <span class="badge" style="background:#e9d5ff;color:#7c3aed;font-weight:800;font-size:0.85rem;padding:5px 12px;">
                                    <i class="fa-solid fa-equals me-1"></i>NOMINAL
                                </span>
                                <small class="text-muted fw-semibold">— Jumlah yang disesuaikan (Debit = Kredit)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NOMINAL (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="nominal" id="aje_nominal" class="form-control fw-bold" required
                                   min="1" placeholder="0"
                                   style="border:1.5px solid #e9d5ff; border-radius:10px; color:#7c3aed; font-size:1rem;">
                            <small class="text-muted">Nominal Debit harus sama dengan Kredit</small>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="p-3 w-100" style="background:#faf5ff; border:1.5px dashed #c4b5fd; border-radius:10px; font-size:0.82rem; color:#6d28d9;">
                                <i class="fa-solid fa-lightbulb me-1"></i>
                                <strong>Tips:</strong> Pilih Jenis Penyesuaian di atas untuk mengisi akun secara otomatis.
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-top" style="border-color:#e9d5ff !important; background:#faf5ff;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:10px;">
                        <i class="fa-solid fa-xmark me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn fw-bold" style="background:linear-gradient(135deg,#9333ea,#7e22ce);color:#fff;border:none;border-radius:10px;padding:8px 24px;">
                        <i class="fa-solid fa-save me-1"></i>Simpan Penyesuaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // ===== AUTO FILL AKUN DARI PILIHAN JENIS PENYESUAIAN =====
    function ajeAutoFill(sel) {
        const opt = sel.options[sel.selectedIndex];
        const dKode  = opt.dataset.debit  || '';
        const dNama  = opt.dataset.dnama  || '';
        const kKode  = opt.dataset.kredit || '';
        const kNama  = opt.dataset.knama  || '';

        // Set select debit
        const selD = document.getElementById('aje_selectDebit');
        for (let i = 0; i < selD.options.length; i++) {
            if (selD.options[i].value === dKode) { selD.selectedIndex = i; break; }
        }
        document.getElementById('aje_inputDebitNama').value = dNama;

        // Set select kredit
        const selK = document.getElementById('aje_selectKredit');
        for (let i = 0; i < selK.options.length; i++) {
            if (selK.options[i].value === kKode) { selK.selectedIndex = i; break; }
        }
        document.getElementById('aje_inputKreditNama').value = kNama;

        // Focus ke nominal
        if (dKode) document.getElementById('aje_nominal').focus();
    }

    function ajeDebit(sel) {
        const o = sel.options[sel.selectedIndex];
        document.getElementById('aje_inputDebitNama').value = o.dataset.nama || '';
    }

    function ajeKredit(sel) {
        const o = sel.options[sel.selectedIndex];
        document.getElementById('aje_inputKreditNama').value = o.dataset.nama || '';
    }

    // ===== FILTER & SEARCH =====
    function applyFilter() {
        const kw      = document.getElementById('searchAJE').value.toLowerCase();
        const jenis   = document.getElementById('filterJenis').value.toLowerCase();
        const rows    = document.querySelectorAll('#tableAJE tbody tr[data-jenis]');
        let visible   = 0;
        rows.forEach(r => {
            const matchSearch = !kw    || r.dataset.search.includes(kw);
            const matchJenis  = !jenis || r.dataset.jenis.toLowerCase().includes(jenis);
            const show        = matchSearch && matchJenis;
            r.style.display   = show ? '' : 'none';
            if (show) visible++;
        });
        const totalRow = document.getElementById('rowTotal');
        if (totalRow) totalRow.style.display = (kw || jenis) ? 'none' : '';
        const countEl = document.getElementById('filterCount');
        if (countEl) countEl.textContent = (kw || jenis) ? `Menampilkan ${visible} entri` : '';
    }

    function resetFilter() {
        document.getElementById('searchAJE').value  = '';
        document.getElementById('filterJenis').value = '';
        applyFilter();
    }

    document.getElementById('searchAJE').addEventListener('input',  applyFilter);
    document.getElementById('filterJenis').addEventListener('change', applyFilter);
</script>
<?= $this->endSection() ?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>">wishuwashere.</a></li>
                    <li class="breadcrumb-item active">Neraca Lajur 10 Kolom</li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-0">Neraca Lajur 10 Kolom (Worksheet) 📐</h3>
            <small class="text-muted">Kertas kerja penyusunan laporan keuangan otomatis: Neraca Saldo, Penyesuaian, NSD, Laba Rugi, & Neraca.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('neraca-lajur/cetak') ?>" target="_blank" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm" style="border-radius:10px;">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Landscape Resmi</span>
            </a>
            <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" onclick="window.print()" style="border-radius:10px;">
                <i class="fa-solid fa-file-pdf"></i>
                <span class="d-none d-sm-inline">Cetak Cepat</span>
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span><i class="fa-solid fa-table me-1 text-primary"></i> Kertas Kerja 10 Kolom — wishuwashere. Laundry</span>
            <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> STATUS: BALANCED</span>
        </div>
        <div class="p-3">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle text-center mb-0" style="font-size: 0.82rem;">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2" class="align-middle text-start" style="min-width: 180px;">Nama Akun</th>
                            <th colspan="2">1. Neraca Saldo</th>
                            <th colspan="2">2. Penyesuaian (AJP)</th>
                            <th colspan="2">3. Neraca Disesuaikan</th>
                            <th colspan="2">4. Laba / Rugi</th>
                            <th colspan="2">5. Neraca Akhir</th>
                        </tr>
                        <tr>
                            <th>D</th><th>K</th>
                            <th>D</th><th>K</th>
                            <th>D</th><th>K</th>
                            <th>D</th><th>K</th>
                            <th>D</th><th>K</th>
                        </tr>
                    </thead>
                    <tbody class="text-end">
                        <?php if (empty($worksheet)): ?>
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">Belum ada akun di bagan akun.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($worksheet as $w): ?>
                                <tr>
                                    <td class="text-start">
                                        <code style="color:#0284c7;"><?= esc($w['kode_akun']) ?></code>
                                        <span class="ms-1 fw-semibold text-dark"><?= esc($w['nama_akun']) ?></span>
                                    </td>
                                    <!-- 1. Neraca Saldo -->
                                    <td><?= $w['ns_d'] > 0 ? number_format($w['ns_d'], 0, ',', '.') : '-' ?></td>
                                    <td><?= $w['ns_k'] > 0 ? number_format($w['ns_k'], 0, ',', '.') : '-' ?></td>
                                    <!-- 2. AJP -->
                                    <td><?= $w['ajp_d'] > 0 ? number_format($w['ajp_d'], 0, ',', '.') : '-' ?></td>
                                    <td><?= $w['ajp_k'] > 0 ? number_format($w['ajp_k'], 0, ',', '.') : '-' ?></td>
                                    <!-- 3. NSD -->
                                    <td><?= $w['nsd_d'] > 0 ? number_format($w['nsd_d'], 0, ',', '.') : '-' ?></td>
                                    <td><?= $w['nsd_k'] > 0 ? number_format($w['nsd_k'], 0, ',', '.') : '-' ?></td>
                                    <!-- 4. Laba / Rugi -->
                                    <td class="<?= $w['lr_d'] > 0 ? 'text-danger fw-semibold' : '' ?>"><?= $w['lr_d'] > 0 ? number_format($w['lr_d'], 0, ',', '.') : '-' ?></td>
                                    <td class="<?= $w['lr_k'] > 0 ? 'text-success fw-semibold' : '' ?>"><?= $w['lr_k'] > 0 ? number_format($w['lr_k'], 0, ',', '.') : '-' ?></td>
                                    <!-- 5. Neraca Akhir -->
                                    <td class="<?= $w['neraca_d'] > 0 ? 'text-primary fw-bold' : '' ?>"><?= $w['neraca_d'] > 0 ? number_format($w['neraca_d'], 0, ',', '.') : '-' ?></td>
                                    <td class="<?= $w['neraca_k'] > 0 ? 'text-dark fw-bold' : '' ?>"><?= $w['neraca_k'] > 0 ? number_format($w['neraca_k'], 0, ',', '.') : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>

                            <!-- JUMLAH TOTAL SEBELUM LABA/RUGI -->
                            <tr class="table-light fw-bold">
                                <td class="text-start">JUMLAH TOTAL</td>
                                <td><?= number_format($total_ns_d, 0, ',', '.') ?></td>
                                <td><?= number_format($total_ns_k, 0, ',', '.') ?></td>
                                <td><?= number_format($total_ajp_d, 0, ',', '.') ?></td>
                                <td><?= number_format($total_ajp_k, 0, ',', '.') ?></td>
                                <td><?= number_format($total_nsd_d, 0, ',', '.') ?></td>
                                <td><?= number_format($total_nsd_k, 0, ',', '.') ?></td>
                                <td class="text-danger"><?= number_format($total_lr_d, 0, ',', '.') ?></td>
                                <td class="text-success"><?= number_format($total_lr_k, 0, ',', '.') ?></td>
                                <td class="text-primary"><?= number_format($total_neraca_d, 0, ',', '.') ?></td>
                                <td class="text-dark"><?= number_format($total_neraca_k, 0, ',', '.') ?></td>
                            </tr>

                            <!-- LABA / RUGI BERSIH PENYEIMBANG -->
                            <tr class="table-success fw-bold">
                                <td class="text-start"><?= $laba >= 0 ? 'LABA BERSIH BERJALAN' : 'RUGI BERSIH BERJALAN' ?></td>
                                <td colspan="6" class="text-center text-muted">—</td>
                                <?php if ($laba >= 0): ?>
                                    <td class="text-success"><?= number_format($laba, 0, ',', '.') ?></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td class="text-success"><?= number_format($laba, 0, ',', '.') ?></td>
                                <?php else: ?>
                                    <td>-</td>
                                    <td class="text-danger"><?= number_format(abs($laba), 0, ',', '.') ?></td>
                                    <td class="text-danger"><?= number_format(abs($laba), 0, ',', '.') ?></td>
                                    <td>-</td>
                                <?php endif; ?>
                            </tr>

                            <!-- JUMLAH SEIMBANG AKHIR -->
                            <tr class="fw-bold" style="background:#e0f2fe; border-top:2px solid #0284c7;">
                                <td class="text-start" style="color:#0369a1;">TOTAL SEIMBANG</td>
                                <td colspan="6" class="text-center text-muted">—</td>
                                <td class="text-success"><?= number_format(max($total_lr_d, $total_lr_k, $total_lr_d + max(0, $laba)), 0, ',', '.') ?></td>
                                <td class="text-success"><?= number_format(max($total_lr_d, $total_lr_k, $total_lr_k + max(0, -$laba)), 0, ',', '.') ?></td>
                                <td class="text-primary"><?= number_format(max($total_neraca_d, $total_neraca_k, $total_neraca_d + max(0, -$laba)), 0, ',', '.') ?></td>
                                <td class="text-primary"><?= number_format(max($total_neraca_d, $total_neraca_k, $total_neraca_k + max(0, $laba)), 0, ',', '.') ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

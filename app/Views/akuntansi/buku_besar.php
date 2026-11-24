<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb"><ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>" style="color:#0284c7;">wishuwashere.</a></li>
                <li class="breadcrumb-item active">Buku Besar / Posting</li>
            </ol></nav>
            <h4 class="fw-bold mb-0" style="color:#d97706;">
                <i class="fa-solid fa-book-bookmark me-2"></i>Buku Besar (General Ledger)
            </h4>
            <small class="text-muted">Posting semua transaksi per akun — rekap saldo berjalan setiap akun</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('buku-besar/cetak') ?>" target="_blank" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm" style="border-radius:10px;">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Dokumen Resmi</span>
            </a>
            <button class="btn d-flex align-items-center gap-2 shadow-sm" style="background:linear-gradient(135deg,#d97706,#b45309);color:#fff;border:none; border-radius:10px;" onclick="window.print()">
                <i class="fa-solid fa-file-pdf"></i><span class="d-none d-sm-inline">Cetak Cepat</span>
            </button>
        </div>
    </div>

    <?php if (empty($bukuBesar)): ?>
    <div class="card p-5 text-center border-2" style="border-color:#fde68a;">
        <div style="font-size:2rem; margin-bottom:8px;">📚</div>
        <strong>Belum ada posting</strong>
        <p class="text-muted mt-2">Tambahkan jurnal umum terlebih dahulu untuk melihat buku besar.</p>
    </div>
    <?php else: ?>

    <!-- Filter per Akun -->
    <div class="card mb-4 border-2 p-3" style="border-color:#fde68a !important; background:#fffbeb;">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <strong class="small" style="color:#d97706;"><i class="fa-solid fa-filter me-1"></i>Filter Akun:</strong>
            <button class="btn btn-sm filter-btn active" style="background:#d97706;color:#fff;border:none;" data-target="all">Semua</button>
            <?php foreach ($bukuBesar as $kode => $data): ?>
            <button class="btn btn-sm filter-btn" style="background:#fef3c7;color:#d97706;border:1px solid #fde68a;" data-target="akun-<?= $kode ?>">
                <?= esc($kode) ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>

    <?php foreach ($bukuBesar as $kode => $data): ?>
    <div class="card mb-4 border-2 buku-besar-card" id="akun-<?= $kode ?>" style="border-color:#fde68a !important;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:linear-gradient(135deg,#fffbeb,#fef3c7); border-bottom:2px solid #fde68a;">
            <div>
                <span class="badge me-2" style="background:#d97706;color:#fff;font-size:.9rem;"><?= esc($kode) ?></span>
                <strong style="color:#92400e;"><?= esc($data['nama_akun']) ?></strong>
            </div>
            <?php $saldo = $data['total_debit'] - $data['total_kredit']; ?>
            <div class="text-end">
                <div class="small text-muted">Saldo Akhir</div>
                <div class="fw-bold <?= $saldo >= 0 ? 'text-primary' : 'text-danger' ?> fs-6">
                    Rp <?= number_format(abs($saldo), 0, ',', '.') ?> <?= $saldo >= 0 ? '(D)' : '(K)' ?>
                </div>
            </div>
        </div>
        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle mb-0" style="font-size:.83rem; border-color:#fde68a;">
                    <thead style="background:#fef3c7;">
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Bukti</th>
                            <th>Keterangan</th>
                            <th class="text-end">Debit (Rp)</th>
                            <th class="text-end">Kredit (Rp)</th>
                            <th class="text-end">Saldo (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $saldoBerjalan = 0; ?>
                        <?php foreach ($data['transaksi'] as $t): ?>
                        <?php
                            $saldoBerjalan += $t['debit'] - $t['kredit'];
                        ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($t['tanggal'])) ?></td>
                            <td><code style="color:#d97706; font-size:.8rem;"><?= esc($t['no_bukti']) ?></code></td>
                            <td style="max-width:200px; white-space:normal;"><?= esc($t['keterangan']) ?></td>
                            <td class="text-end">
                                <?= $t['debit'] > 0 ? '<span class="fw-semibold text-primary">' . number_format($t['debit'], 0, ',', '.') . '</span>' : '<span class="text-muted">—</span>' ?>
                            </td>
                            <td class="text-end">
                                <?= $t['kredit'] > 0 ? '<span class="fw-semibold text-danger">' . number_format($t['kredit'], 0, ',', '.') . '</span>' : '<span class="text-muted">—</span>' ?>
                            </td>
                            <td class="text-end fw-bold <?= $saldoBerjalan >= 0 ? 'text-primary' : 'text-danger' ?>">
                                <?= number_format(abs($saldoBerjalan), 0, ',', '.') ?> <?= $saldoBerjalan >= 0 ? 'D' : 'K' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot style="background:#fef9c3;">
                        <tr class="fw-bold" style="border-top:2px solid #d97706;">
                            <td colspan="3" class="text-center" style="color:#92400e;">TOTAL</td>
                            <td class="text-end text-primary"><?= number_format($data['total_debit'], 0, ',', '.') ?></td>
                            <td class="text-end text-danger"><?= number_format($data['total_kredit'], 0, ',', '.') ?></td>
                            <td class="text-end <?= ($data['total_debit'] - $data['total_kredit']) >= 0 ? 'text-primary' : 'text-danger' ?>">
                                <?= number_format(abs($data['total_debit'] - $data['total_kredit']), 0, ',', '.') ?>
                                <?= ($data['total_debit'] - $data['total_kredit']) >= 0 ? 'D' : 'K' ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.dataset.target;
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.style.background = '#fef3c7'; b.style.color = '#d97706'; b.classList.remove('active');
            });
            this.style.background = '#d97706'; this.style.color = '#fff'; this.classList.add('active');
            if (target === 'all') {
                document.querySelectorAll('.buku-besar-card').forEach(c => c.style.display = '');
            } else {
                document.querySelectorAll('.buku-besar-card').forEach(c => {
                    c.style.display = c.id === target ? '' : 'none';
                });
            }
        });
    });
</script>

<style>
    @media print {
        #sidebar, .top-navbar, .main-footer, .btn, .filter-btn { display: none !important; }
        #main-wrapper { margin-left: 0 !important; }
        .buku-besar-card { display: block !important; page-break-inside: avoid; margin-bottom: 24px; }
    }
</style>
<?= $this->endSection() ?>

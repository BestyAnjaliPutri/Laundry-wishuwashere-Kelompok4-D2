<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>">wishuwashere.</a></li>
                    <li class="breadcrumb-item active">Jurnal Umum</li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-0">Jurnal Umum Laundry 📖</h3>
            <small class="text-muted">Pencatatan ayat jurnal transaksi debit & kredit otomatis dari order kasir laundry.</small>
        </div>
        <div>
            <a href="<?= base_url('kasir') ?>" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus-circle"></i>
                <span>+ Input Transaksi di Kasir</span>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <span><i class="fa-solid fa-book-open me-1 text-primary"></i> Buku Jurnal Umum</span>
            <span class="badge bg-dark"><?= count($transaksi) * 2 ?> Baris Jurnal</span>
        </div>
        <div class="p-3">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 130px;">Tanggal</th>
                            <th>No. Bukti / Keterangan Akun</th>
                            <th class="text-center" style="width: 100px;">Ref</th>
                            <th class="text-end" style="width: 180px;">Debit (Rp)</th>
                            <th class="text-end" style="width: 180px;">Kredit (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transaksi)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-book fs-3 d-block mb-2"></i>
                                    Belum ada transaksi di jurnal. Silakan buat transaksi penerimaan cucian di menu <a href="<?= base_url('kasir') ?>">Kasir</a>!
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                                $totalDebit = 0;
                                $totalKredit = 0;
                                foreach ($transaksi as $t): 
                                    $nominal = (int) $t['total_biaya'];
                                    $totalDebit += $nominal;
                                    $totalKredit += $nominal;
                                    $isLunas = $t['status_bayar'] === 'Lunas';
                            ?>
                                <!-- Baris Debit -->
                                <tr>
                                    <td rowspan="2" class="align-top"><?= date('d/m/Y', strtotime($t['created_at'])) ?></td>
                                    <td>
                                        <strong>#<?= esc($t['kode_transaksi']) ?></strong> - 
                                        <?= $isLunas ? 'Kas di Tangan (Penerimaan Tunai/QRIS)' : 'Piutang Usaha (Cucian Belum Lunas)' ?><br>
                                        <small class="text-muted">Pelanggan: <?= esc($t['nama_pelanggan']) ?> (<?= esc($t['nama_paket']) ?>)</small>
                                    </td>
                                    <td class="text-center"><span class="badge bg-light text-dark border"><?= $isLunas ? '1-101' : '1-102' ?></span></td>
                                    <td class="text-end text-primary fw-bold">Rp <?= number_format($nominal, 0, ',', '.') ?></td>
                                    <td class="text-end">-</td>
                                </tr>
                                <!-- Baris Kredit -->
                                <tr>
                                    <td class="ps-4">
                                        <i class="fa-solid fa-arrow-turn-up fa-rotate-90 text-muted me-1"></i>
                                        Pendapatan Jasa Laundry
                                    </td>
                                    <td class="text-center"><span class="badge bg-light text-dark border">4-101</span></td>
                                    <td class="text-end">-</td>
                                    <td class="text-end text-success fw-bold">Rp <?= number_format($nominal, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-light fw-bold fs-6">
                                <td colspan="3" class="text-center">TOTAL KESEIMBANGAN JURNAL</td>
                                <td class="text-end text-primary">Rp <?= number_format($totalDebit, 0, ',', '.') ?></td>
                                <td class="text-end text-success">Rp <?= number_format($totalKredit, 0, ',', '.') ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

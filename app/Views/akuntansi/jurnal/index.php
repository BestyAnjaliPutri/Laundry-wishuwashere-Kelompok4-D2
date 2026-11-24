<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" style="color:#0284c7;">wishuwashere.</a></li>
                    <li class="breadcrumb-item active">Jurnal Umum</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0" style="color:#0369a1;">
                <i class="fa-solid fa-book me-2"></i>Jurnal Umum (General Journal)
            </h4>
            <small class="text-muted">Pencatatan transaksi keuangan secara bebas (tidak terikat order kasir)</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('jurnal/cetak') ?>" target="_blank" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm" style="border-radius:10px;">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Jurnal</span>
            </a>
            <a href="<?= base_url('jurnal/tambah') ?>" class="btn btn-primary d-flex align-items-center gap-2" 
               style="background:linear-gradient(135deg,#0ea5e9,#0284c7); border:none; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25); border-radius:10px;">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Jurnal</span>
            </a>
            <button class="btn btn-outline-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahJurnal" style="border-radius:10px;">
                <i class="fa-solid fa-bolt"></i>
                <span class="d-none d-sm-inline">Popup Cepat</span>
            </button>
        </div>
    </div>

    <!-- Info Panel Persis Referensi Jurnal Penyesuaian -->
    <div class="card mb-4 border-2" style="border-color:#bae6fd !important; background:linear-gradient(135deg,#f0f9ff,#e0f2fe);">
        <div class="p-3 d-flex gap-3 align-items-start">
            <div style="font-size:1.8rem;">📋</div>
            <div>
                <div class="fw-bold mb-1" style="color:#0284c7;">Pencatatan Transaksi Jurnal Umum</div>
                <div class="small text-muted" style="line-height:1.6;">
                    Gunakan formulir ini untuk mencatat transaksi keuangan secara mandiri: <strong>penerimaan kas</strong>, <strong>pelunasan piutang</strong>, <strong>pembayaran utang</strong>, <strong>setoran modal</strong>, <strong>pengambilan prive</strong>, atau <strong>beban operasional</strong> lainnya.
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center border-2" style="border-color:#a5f3fc !important; background:#ecfeff;">
                <div class="fw-bold" style="font-size:1.6rem; color:#0891b2;"><?= count($jurnal) ?></div>
                <small class="text-muted fw-semibold">Total Entri</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center border-2" style="border-color:#86efac !important; background:#f0fdf4;">
                <div class="fw-bold" style="font-size:1.6rem; color:#16a34a;">
                    Rp <?= number_format(array_sum(array_column($jurnal, 'nominal')), 0, ',', '.') ?>
                </div>
                <small class="text-muted fw-semibold">Total Nominal</small>
            </div>
        </div>
    </div>

    <!-- Journal Table -->
    <div class="card border-2" style="border-color:#bae6fd !important;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold" style="color:#0369a1;">
                <i class="fa-solid fa-list me-1"></i> Daftar Jurnal Umum
            </span>
            <input type="text" id="searchJurnal" class="form-control" style="max-width:220px; font-size:.85rem;" placeholder="🔍 Cari jurnal...">
        </div>
        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" style="border-color:#e0f2fe; font-size:.85rem;" id="tableJurnal">
                    <thead>
                        <tr style="background:linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%); color:#0369a1;">
                            <th style="min-width:40px;">#</th>
                            <th style="min-width:120px;">NO. BUKTI</th>
                            <th style="min-width:100px;">TANGGAL</th>
                            <th style="min-width:130px;">TIPE</th>
                            <th style="min-width:200px;">KETERANGAN</th>
                            <th style="min-width:160px;">AKUN DEBIT</th>
                            <th style="min-width:160px;">AKUN KREDIT</th>
                            <th style="min-width:120px;" class="text-end">NOMINAL (RP)</th>
                            <th style="min-width:110px;" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($jurnal)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5" style="background:#f8fdff;">
                                <div style="font-size:2rem; margin-bottom:8px;">📒</div>
                                <strong class="d-block mb-1">Belum ada jurnal umum</strong>
                                <small class="text-muted">Klik "Tambah Entri Jurnal" untuk mulai mencatat.</small>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($jurnal as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><code style="color:#0284c7;"><?= esc($row['no_bukti']) ?></code></td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td>
                                <span class="badge" style="background:#e0f2fe; color:#0369a1; font-size:.75rem; font-weight:700;">
                                    <?= esc($row['tipe_transaksi']) ?>
                                </span>
                            </td>
                            <td style="max-width:200px; white-space:normal;"><?= esc($row['keterangan']) ?></td>
                            <td>
                                <div class="fw-bold" style="color:#0284c7;"><?= esc($row['debit_kode_akun']) ?></div>
                                <small class="text-muted"><?= esc($row['debit_nama_akun']) ?></small>
                            </td>
                            <td>
                                <div class="fw-bold" style="color:#dc2626;"><?= esc($row['kredit_kode_akun']) ?></div>
                                <small class="text-muted"><?= esc($row['kredit_nama_akun']) ?></small>
                            </td>
                            <td class="text-end fw-bold" style="color:#0369a1;">
                                <?= number_format($row['nominal'], 0, ',', '.') ?>
                            </td>
                            <td class="text-center">
                                <a href="/jurnal/edit/<?= $row['id'] ?>" class="btn btn-sm mb-1" style="background:#fef3c7; color:#d97706; border:1px solid #fde68a;">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="/jurnal/hapus/<?= $row['id'] ?>" class="btn btn-sm mb-1" style="background:#fee2e2; color:#dc2626; border:1px solid #fecaca;"
                                   onclick="return confirm('Hapus jurnal ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
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

<!-- ===== MODAL TAMBAH JURNAL ===== -->
<div class="modal fade" id="modalTambahJurnal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-2" style="border-color:#bae6fd;">
            <div class="modal-header" style="background:linear-gradient(135deg,#cffafe,#ecfeff); border-bottom:2px solid #a5f3fc;">
                <h5 class="modal-title fw-bold" style="color:#0891b2;">
                    <i class="fa-solid fa-plus-circle me-2"></i>Tambah Entri Jurnal Umum
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="/jurnal/simpan">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">No. Bukti</label>
                            <input type="text" name="no_bukti" class="form-control" required placeholder="JU-20261105-001">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Tipe Transaksi</label>
                            <select name="tipe_transaksi" class="form-select">
                                <option>Penyetoran Modal</option>
                                <option>Penerimaan Pendapatan</option>
                                <option>Pelunasan Piutang</option>
                                <option>Pembayaran Utang</option>
                                <option>Pembelian Perlengkapan</option>
                                <option>Pengeluaran Beban</option>
                                <option>Penerimaan Pinjaman</option>
                                <option>Lain-lain / Umum</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" required placeholder="Deskripsi transaksi jurnal..."></textarea>
                        </div>

                        <!-- DEBIT -->
                        <div class="col-12"><hr class="my-1"><strong class="small" style="color:#0284c7;"><i class="fa-solid fa-arrow-down me-1"></i>DEBIT (Bertambah)</strong></div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Akun Debit</label>
                            <select name="debit_kode_akun" class="form-select" id="selectDebit" onchange="fillDebitNama(this)">
                                <option value="">— Pilih Akun Debit —</option>
                                <?php foreach ($daftarAkun as $akun): ?>
                                <option value="<?= esc($akun['kode_akun']) ?>" data-nama="<?= esc($akun['nama_akun3']) ?>">
                                    <?= esc($akun['kode_akun'] . ' — ' . $akun['nama_akun3']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nama Akun Debit (otomatis)</label>
                            <input type="text" name="debit_nama_akun" id="inputDebitNama" class="form-control" required placeholder="Terisi otomatis">
                        </div>

                        <!-- KREDIT -->
                        <div class="col-12"><hr class="my-1"><strong class="small" style="color:#dc2626;"><i class="fa-solid fa-arrow-up me-1"></i>KREDIT (Berkurang)</strong></div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Akun Kredit</label>
                            <select name="kredit_kode_akun" class="form-select" id="selectKredit" onchange="fillKreditNama(this)">
                                <option value="">— Pilih Akun Kredit —</option>
                                <?php foreach ($daftarAkun as $akun): ?>
                                <option value="<?= esc($akun['kode_akun']) ?>" data-nama="<?= esc($akun['nama_akun3']) ?>">
                                    <?= esc($akun['kode_akun'] . ' — ' . $akun['nama_akun3']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nama Akun Kredit (otomatis)</label>
                            <input type="text" name="kredit_nama_akun" id="inputKreditNama" class="form-control" required placeholder="Terisi otomatis">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nominal (Rp)</label>
                            <input type="number" name="nominal" class="form-control" required min="1" placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top" style="border-color:#bae6fd !important;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-1"></i>Simpan Jurnal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function fillDebitNama(sel) {
        const opt = sel.options[sel.selectedIndex];
        document.getElementById('inputDebitNama').value = opt.dataset.nama || '';
    }
    function fillKreditNama(sel) {
        const opt = sel.options[sel.selectedIndex];
        document.getElementById('inputKreditNama').value = opt.dataset.nama || '';
    }

    // Live search table
    document.getElementById('searchJurnal').addEventListener('input', function() {
        const kw = this.value.toLowerCase();
        document.querySelectorAll('#tableJurnal tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(kw) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>

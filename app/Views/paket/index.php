<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" style="color:#0284c7;">wishuwashere.</a></li>
                    <li class="breadcrumb-item active">Master Paket</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0" style="color:#0369a1;">
                <i class="fa-solid fa-tags me-2"></i>Paket & Tarif Layanan Laundry
            </h4>
            <small class="text-muted">Kelola jenis layanan kiloan, satuan, tarif harga, dan estimasi waktu selesai.</small>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2"
                style="background:linear-gradient(135deg,#0ea5e9,#0284c7);border:none;border-radius:12px;box-shadow:0 4px 15px rgba(2,132,199,0.3);"
                data-bs-toggle="modal" data-bs-target="#modalTambahPaket">
            <i class="fa-solid fa-plus-circle"></i>
            <span class="fw-semibold">Tambah Paket Baru</span>
        </button>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 mb-3" role="alert"
         style="border-radius:12px;background:#f0fdf4;border-left:4px solid #22c55e !important;">
        <i class="fa-solid fa-circle-check me-2 text-success"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Summary -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center border-2" style="border-color:#bae6fd !important;background:#ecfeff;">
                <div class="fw-bold" style="font-size:1.6rem;color:#0891b2;"><?= count($paket) ?></div>
                <small class="text-muted fw-semibold">Paket Tersedia</small>
            </div>
        </div>
    </div>

    <!-- Tabel Paket -->
    <div class="card border-2" style="border-color:#bae6fd !important;">
        <div class="card-header d-flex justify-content-between align-items-center py-3"
             style="background:linear-gradient(135deg,#e0f2fe,#f0f9ff);border-bottom:2px solid #bae6fd;">
            <span class="fw-bold" style="color:#0369a1;">
                <i class="fa-solid fa-list me-1"></i> Data Paket Layanan
            </span>
            <input type="text" id="searchPaket" class="form-control" style="max-width:220px;font-size:.85rem;" placeholder="🔍 Cari paket...">
        </div>
        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" style="border-color:#e0f2fe;font-size:.85rem;" id="tablePaket">
                    <thead>
                        <tr style="background:linear-gradient(135deg,#e0f2fe,#f0f9ff);color:#0369a1;">
                            <th style="width:50px;">NO</th>
                            <th style="min-width:180px;">NAMA PAKET</th>
                            <th style="min-width:130px;">JENIS LAYANAN</th>
                            <th style="min-width:140px;">TARIF HARGA</th>
                            <th style="min-width:140px;">ESTIMASI PENGERJAAN</th>
                            <th style="min-width:100px;" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($paket)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5" style="background:#f8fdff;">
                                <div style="font-size:2rem;margin-bottom:8px;">🏷️</div>
                                <strong class="d-block mb-1">Belum ada paket layanan</strong>
                                <small class="text-muted">Tambahkan paket pertama untuk memulai.</small>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1; foreach ($paket as $p): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><strong><?= esc($p['nama_paket']) ?></strong></td>
                                <td>
                                    <?php if ($p['jenis'] === 'kiloan'): ?>
                                        <span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:.75rem;">⚖️ Kiloan (Per Kg)</span>
                                    <?php else: ?>
                                        <span class="badge" style="background:#cffafe;color:#0891b2;font-size:.75rem;">👔 Satuan (Per Pcs)</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong style="color:#16a34a;">Rp <?= number_format($p['harga'], 0, ',', '.') ?></strong>
                                    <small class="text-muted">/ <?= $p['jenis'] === 'kiloan' ? 'Kg' : 'Pcs' ?></small>
                                </td>
                                <td>
                                    <span class="badge" style="background:#f0f9ff;color:#0369a1;font-size:.75rem;border:1px solid #bae6fd;">
                                        <i class="fa-regular fa-clock me-1"></i><?= esc($p['estimasi_hari']) ?> Hari Kerja
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-edit-paket"
                                            data-bs-toggle="modal" data-bs-target="#modalEditPaket"
                                            data-id="<?= $p['id'] ?>"
                                            data-nama="<?= esc($p['nama_paket']) ?>"
                                            data-jenis="<?= esc($p['jenis']) ?>"
                                            data-harga="<?= esc($p['harga']) ?>"
                                            data-estimasi="<?= esc($p['estimasi_hari']) ?>"
                                            title="Edit" style="background:#fef3c7;color:#d97706;border:1px solid #fde68a;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <a href="<?= base_url('paket/hapus/' . $p['id']) ?>"
                                           onclick="return confirm('Hapus paket ini?')"
                                           class="btn btn-sm" title="Hapus"
                                           style="background:#fee2e2;color:#dc2626;border:1px solid #fecaca;">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
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

<!-- MODAL TAMBAH PAKET -->
<div class="modal fade" id="modalTambahPaket" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-2" style="border-color:#bae6fd;border-radius:18px;">
            <form action="<?= base_url('paket/simpan') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header py-3" style="background:linear-gradient(135deg,#e0f2fe,#f0f9ff);border-bottom:2px solid #bae6fd;border-radius:18px 18px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,#0ea5e9,#0284c7);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-tags text-white" style="font-size:.9rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" style="color:#0369a1;">Tambah Paket Baru</h5>
                            <small class="text-muted">Input jenis layanan & tarif harga</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-shirt me-1 text-primary"></i>Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="nama_paket" class="form-control" required placeholder="Contoh: Cuci Karpet Tebal" style="border:1.5px solid #bae6fd;border-radius:10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-weight-scale me-1 text-primary"></i>Jenis Tarif <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-select" required style="border:1.5px solid #bae6fd;border-radius:10px;">
                            <option value="kiloan">⚖️ Kiloan (Hitungan Kg)</option>
                            <option value="satuan">👔 Satuan (Hitungan Pcs/Item)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-money-bill-wave me-1 text-success"></i>Harga Tarif (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text fw-bold" style="background:#e0f2fe;border:1.5px solid #bae6fd;border-right:none;color:#0369a1;border-radius:10px 0 0 10px;">Rp</span>
                            <input type="number" name="harga" class="form-control" required placeholder="Contoh: 15000" style="border:1.5px solid #bae6fd;border-left:none;border-radius:0 10px 10px 0;">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-regular fa-clock me-1 text-primary"></i>Estimasi Pengerjaan (Hari) <span class="text-danger">*</span></label>
                        <input type="number" name="estimasi_hari" class="form-control" value="2" required style="border:1.5px solid #bae6fd;border-radius:10px;">
                    </div>
                </div>
                <div class="modal-footer py-3" style="background:#f8fdff;border-top:2px solid #bae6fd;border-radius:0 0 18px 18px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Batal</button>
                    <button type="submit" class="btn fw-bold px-4" style="background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;border:none;border-radius:10px;box-shadow:0 4px 12px rgba(2,132,199,0.25);">
                        <i class="fa-solid fa-check me-1"></i>Simpan Paket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT PAKET -->
<div class="modal fade" id="modalEditPaket" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-2" style="border-color:#fde68a;border-radius:18px;">
            <form id="formEditPaket" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-header py-3" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-bottom:2px solid #fde68a;border-radius:18px 18px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-pen-to-square text-white" style="font-size:.9rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" style="color:#92400e;">Edit Paket Laundry</h5>
                            <small class="text-muted">Perbarui nama, harga, dan estimasi</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#334155;">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="nama_paket" id="editNama" class="form-control" required style="border:1.5px solid #fde68a;border-radius:10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#334155;">Jenis Tarif <span class="text-danger">*</span></label>
                        <select name="jenis" id="editJenis" class="form-select" required style="border:1.5px solid #fde68a;border-radius:10px;">
                            <option value="kiloan">⚖️ Kiloan (Hitungan Kg)</option>
                            <option value="satuan">👔 Satuan (Hitungan Pcs/Item)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#334155;">Harga Tarif (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="editHarga" class="form-control" required style="border:1.5px solid #fde68a;border-radius:10px;">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small" style="color:#334155;">Estimasi Pengerjaan (Hari) <span class="text-danger">*</span></label>
                        <input type="number" name="estimasi_hari" id="editEstimasi" class="form-control" required style="border:1.5px solid #fde68a;border-radius:10px;">
                    </div>
                </div>
                <div class="modal-footer py-3" style="background:#fffdf0;border-top:2px solid #fde68a;border-radius:0 0 18px 18px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Batal</button>
                    <button type="submit" class="btn fw-bold px-4" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;border-radius:10px;box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                        <i class="fa-solid fa-check me-1"></i>Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-edit-paket').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('formEditPaket').action = '<?= base_url('paket/update/') ?>' + this.dataset.id;
            document.getElementById('editNama').value = this.dataset.nama;
            document.getElementById('editJenis').value = this.dataset.jenis;
            document.getElementById('editHarga').value = this.dataset.harga;
            document.getElementById('editEstimasi').value = this.dataset.estimasi;
        });
    });
    document.getElementById('searchPaket').addEventListener('input', function() {
        const kw = this.value.toLowerCase();
        document.querySelectorAll('#tablePaket tbody tr').forEach(r => { r.style.display = r.innerText.toLowerCase().includes(kw) ? '' : 'none'; });
    });
</script>
<?= $this->endSection() ?>

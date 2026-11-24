<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" style="color:#0284c7;">wishuwashere.</a></li>
                    <li class="breadcrumb-item active">Data Pelanggan</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0" style="color:#0369a1;">
                <i class="fa-solid fa-users me-2"></i>Master Data Pelanggan
            </h4>
            <small class="text-muted">Kelola buku kontak pelanggan, nomor WhatsApp, dan alamat antar-jemput.</small>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2"
                style="background:linear-gradient(135deg,#0ea5e9,#0284c7);border:none;border-radius:12px;box-shadow:0 4px 15px rgba(2,132,199,0.3);"
                data-bs-toggle="modal" data-bs-target="#modalTambahPelanggan">
            <i class="fa-solid fa-user-plus"></i>
            <span class="fw-semibold">Tambah Pelanggan Baru</span>
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
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 mb-3" role="alert"
         style="border-radius:12px;background:#fef2f2;border-left:4px solid #dc2626 !important;">
        <i class="fa-solid fa-circle-exclamation me-2 text-danger"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Summary -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center border-2" style="border-color:#bae6fd !important;background:#ecfeff;">
                <div class="fw-bold" style="font-size:1.6rem;color:#0891b2;"><?= count($pelanggan) ?></div>
                <small class="text-muted fw-semibold">Total Pelanggan</small>
            </div>
        </div>
    </div>

    <!-- Tabel Pelanggan -->
    <div class="card border-2" style="border-color:#bae6fd !important;">
        <div class="card-header d-flex justify-content-between align-items-center py-3"
             style="background:linear-gradient(135deg,#e0f2fe,#f0f9ff);border-bottom:2px solid #bae6fd;">
            <span class="fw-bold" style="color:#0369a1;">
                <i class="fa-solid fa-users me-1"></i> Data Pelanggan Terdaftar
            </span>
            <input type="text" id="searchPelanggan" class="form-control" style="max-width:220px;font-size:.85rem;" placeholder="🔍 Cari pelanggan...">
        </div>
        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" style="border-color:#e0f2fe;font-size:.85rem;" id="tablePelanggan">
                    <thead>
                        <tr style="background:linear-gradient(135deg,#e0f2fe,#f0f9ff);color:#0369a1;">
                            <th style="width:50px;">NO</th>
                            <th style="min-width:160px;">NAMA PELANGGAN</th>
                            <th style="min-width:140px;">KONTAK WHATSAPP</th>
                            <th style="min-width:200px;">ALAMAT DOMISILI</th>
                            <th style="min-width:110px;">TGL DAFTAR</th>
                            <th style="min-width:100px;" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pelanggan)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5" style="background:#f8fdff;">
                                    <div style="font-size:2rem;margin-bottom:8px;">👥</div>
                                    <strong class="d-block mb-1">Belum ada data pelanggan</strong>
                                    <small class="text-muted">Klik "Tambah Pelanggan Baru" untuk menambahkan data.</small>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($pelanggan as $plg): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><div class="fw-bold"><?= esc($plg['nama']) ?></div></td>
                                    <td>
                                        <?php if (!empty($plg['no_hp'])): ?>
                                            <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $plg['no_hp'])) ?>"
                                               target="_blank" class="btn btn-sm d-inline-flex align-items-center gap-1"
                                               style="background:#dcfce7;color:#16a34a;border:1px solid #86efac;font-size:.78rem;">
                                                <i class="fa-brands fa-whatsapp"></i> <?= esc($plg['no_hp']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><small class="text-muted"><?= esc($plg['alamat'] ?: 'Tidak ada alamat') ?></small></td>
                                    <td><small class="text-muted"><?= date('d/m/Y', strtotime($plg['created_at'])) ?></small></td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-edit-pelanggan"
                                                data-bs-toggle="modal" data-bs-target="#modalEditPelanggan"
                                                data-id="<?= $plg['id'] ?>"
                                                data-nama="<?= esc($plg['nama']) ?>"
                                                data-nohp="<?= esc($plg['no_hp']) ?>"
                                                data-alamat="<?= esc($plg['alamat']) ?>"
                                                title="Edit"
                                                style="background:#fef3c7;color:#d97706;border:1px solid #fde68a;">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <a href="<?= base_url('pelanggan/hapus/' . $plg['id']) ?>"
                                               onclick="return confirm('Hapus data pelanggan ini?')"
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

<!-- MODAL TAMBAH PELANGGAN -->
<div class="modal fade" id="modalTambahPelanggan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-2" style="border-color:#bae6fd;border-radius:18px;">
            <form action="<?= base_url('pelanggan/simpan') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header py-3" style="background:linear-gradient(135deg,#e0f2fe,#f0f9ff);border-bottom:2px solid #bae6fd;border-radius:18px 18px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,#0ea5e9,#0284c7);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-user-plus text-white" style="font-size:.9rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" style="color:#0369a1;">Tambah Pelanggan Baru</h5>
                            <small class="text-muted">Input data kontak pelanggan laundry</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-user me-1 text-primary"></i>Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: Budi Santoso" style="border:1.5px solid #bae6fd;border-radius:10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-brands fa-whatsapp me-1 text-success"></i>Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" class="form-control" required placeholder="Contoh: 08123456789" style="border:1.5px solid #bae6fd;border-radius:10px;">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-location-dot me-1 text-primary"></i>Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat pelanggan..." style="border:1.5px solid #bae6fd;border-radius:10px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer py-3" style="background:#f8fdff;border-top:2px solid #bae6fd;border-radius:0 0 18px 18px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Batal</button>
                    <button type="submit" class="btn fw-bold px-4" style="background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;border:none;border-radius:10px;box-shadow:0 4px 12px rgba(2,132,199,0.25);">
                        <i class="fa-solid fa-check me-1"></i>Simpan Pelanggan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT PELANGGAN -->
<div class="modal fade" id="modalEditPelanggan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-2" style="border-color:#fde68a;border-radius:18px;">
            <form id="formEditPelanggan" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-header py-3" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-bottom:2px solid #fde68a;border-radius:18px 18px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-pen-to-square text-white" style="font-size:.9rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" style="color:#92400e;">Edit Data Pelanggan</h5>
                            <small class="text-muted">Perbarui informasi kontak pelanggan</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#334155;">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editNamaPelanggan" class="form-control" required style="border:1.5px solid #fde68a;border-radius:10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#334155;">Nomor WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" id="editNoHp" class="form-control" required style="border:1.5px solid #fde68a;border-radius:10px;">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small" style="color:#334155;">Alamat Lengkap</label>
                        <textarea name="alamat" id="editAlamat" class="form-control" rows="3" style="border:1.5px solid #fde68a;border-radius:10px;"></textarea>
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
    document.querySelectorAll('.btn-edit-pelanggan').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('formEditPelanggan').action = '<?= base_url('pelanggan/update/') ?>' + this.dataset.id;
            document.getElementById('editNamaPelanggan').value = this.dataset.nama;
            document.getElementById('editNoHp').value = this.dataset.nohp;
            document.getElementById('editAlamat').value = this.dataset.alamat;
        });
    });
    document.getElementById('searchPelanggan').addEventListener('input', function() {
        const kw = this.value.toLowerCase();
        document.querySelectorAll('#tablePelanggan tbody tr').forEach(r => { r.style.display = r.innerText.toLowerCase().includes(kw) ? '' : 'none'; });
    });
</script>
<?= $this->endSection() ?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <style>
        .btn-add-new {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: #ffffff;
            font-weight: 700;
            padding: 9px 24px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-add-new:hover {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(2, 132, 199, 0.35);
        }

        .btn-action-edit {
            background-color: #f59e0b;
            color: #ffffff;
            border: none;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.15s ease;
        }
        .btn-action-edit:hover {
            background-color: #d97706;
            color: #ffffff;
            transform: scale(1.04);
        }

        .btn-action-delete {
            background-color: #f43f5e;
            color: #ffffff;
            border: none;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.15s ease;
        }
        .btn-action-delete:hover {
            background-color: #e11d48;
            color: #ffffff;
            transform: scale(1.04);
        }

        .account-card-top {
            background: #ffffff;
            border: 1.5px solid #bae6fd;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .account-table-card {
            background: #ffffff;
            border: 1.5px solid #bae6fd;
            border-radius: 12px;
            padding: 24px;
        }

        .account-table-title {
            color: #0284c7;
            font-size: 1.15rem;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-akun thead th {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
            font-weight: 800;
            font-size: 0.78rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: 1px solid #bae6fd;
            padding: 12px 14px;
        }

        .table-akun tbody td {
            border: 1px solid #f0f9ff;
            border-bottom: 1px solid #e0f2fe;
            padding: 12px 14px;
            color: #334155;
            font-size: 0.88rem;
            font-weight: 500;
        }

        .table-akun tbody tr:hover {
            background-color: #f8fdff;
        }

        .badge-posisi-debit {
            background-color: #e0f2fe;
            color: #0284c7;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .badge-posisi-kredit {
            background-color: #fef3c7;
            color: #d97706;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
        }
    </style>

    <!-- 1. TOP BAR: TOMBOL + ADD NEW -->
    <div class="account-card-top">
        <div>
            <button class="btn btn-add-new" data-bs-toggle="modal" data-bs-target="#modalAddAkun2">
                <i class="fa-solid fa-plus"></i>
                <span>Add New</span>
            </button>
        </div>
        <div class="d-flex align-items-center gap-2 text-muted small">
            <span style="font-size: 1.3rem;">🫧</span>
            <span class="fw-bold" style="color: #0284c7;">Akun - 2 (Sub-Klasifikasi / Kelompok)</span>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3 shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-check text-success fs-5"></i>
            <div><?= session()->getFlashdata('success') ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3 shadow-sm border-0" role="alert">
            <i class="fa-solid fa-triangle-exclamation text-danger fs-5"></i>
            <div><?= session()->getFlashdata('error') ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- 2. DATA TABLE CARD -->
    <div class="account-table-card">
        <div class="account-table-title">
            <i class="fa-solid fa-bars-staggered fs-5"></i>
            <span>Data Akun 2</span>
        </div>

        <!-- Controls: Entries & Search -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-3">
            <div class="d-flex align-items-center gap-2 small text-muted">
                <span>Show</span>
                <select id="entriesSelect" class="form-select form-select-sm" style="width: 75px; border-color: #fecdd3;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">All</option>
                </select>
                <span>entries</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="akunSearch" class="small text-muted fw-bold">Search:</label>
                <input type="text" id="akunSearch" class="form-control form-control-sm" placeholder="Ketik cari akun 2..." style="max-width: 240px; border-color: #fecdd3;">
            </div>
        </div>

        <!-- TABEL DATA AKUN 2 -->
        <div class="table-responsive">
            <table class="table table-akun align-middle mb-0" id="tableAkun2">
                <thead>
                    <tr>
                        <th style="width: 65px;">NO</th>
                        <th>NAMA AKUN 1 (INDUK)</th>
                        <th style="width: 140px;">KODE AKUN 2</th>
                        <th>NAMA AKUN 2</th>
                        <th style="width: 140px;">SALDO NORMAL</th>
                        <th class="text-center" style="width: 160px;">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarAkun2)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Belum ada data Akun 2. Klik <strong>"+ Add New"</strong> untuk menambahkan!
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($daftarAkun2 as $a): ?>
                            <tr class="akun-row">
                                <td class="fw-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <span class="badge" style="background:#e0f2fe; color:#0284c7; font-weight:700; padding:5px 12px; border-radius:6px;">
                                        <?= esc($a['nama_akun1'] ?: 'Aset') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge" style="background:#e0f2fe; color:#0284c7; font-weight:800; font-size:0.85rem; padding: 5px 12px; border-radius: 6px;">
                                        <?= esc($a['kode_akun2']) ?>
                                    </span>
                                </td>
                                <td class="fw-bold" style="color: #0f172a; font-size: 0.95rem;">
                                    <?= esc($a['nama_akun2']) ?>
                                </td>
                                <td>
                                    <span class="<?= ($a['posisi'] ?? 'Debit') === 'Debit' ? 'badge-posisi-debit' : 'badge-posisi-kredit' ?>">
                                        <?= esc($a['posisi'] ?? 'Debit') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-action-edit btn-edit-akun2" 
                                            data-id="<?= $a['id'] ?>"
                                            data-akun1="<?= esc($a['nama_akun1']) ?>"
                                            data-kode1="<?= esc($a['kode_akun1'] ?? '') ?>"
                                            data-kode2="<?= esc($a['kode_akun2']) ?>"
                                            data-nama2="<?= esc($a['nama_akun2']) ?>"
                                            data-posisi="<?= esc($a['posisi']) ?>">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>
                                    <a href="<?= base_url('akun/2/hapus/' . $a['id']) ?>" 
                                       class="btn btn-action-delete"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus Akun 2 \'<?= esc($a['nama_akun2']) ?>\'?')">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 text-muted small">
            <div id="tableInfo">Showing <?= count($daftarAkun2) ?> entries</div>
        </div>
    </div>

</div>

<!-- MODAL ADD AKUN 2 -->
<div class="modal fade" id="modalAddAkun2" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; border: 1.5px solid #bae6fd; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%); border-bottom: 1px solid #bae6fd;">
                <h6 class="modal-title fw-bold" style="color: #0369a1;">
                    <i class="fa-solid fa-bars-staggered me-1"></i> Tambah Data Akun 2
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('akun/2/simpan') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">INDUK AKUN 1 <span class="text-danger">*</span></label>
                        <select name="nama_akun1" class="form-select" id="add_select_akun1" required>
                            <option value="">-- Pilih Induk Akun 1 --</option>
                            <?php foreach ($daftarAkun1 as $ak1): ?>
                                <option value="<?= esc($ak1['nama_akun1']) ?>" data-kode1="<?= esc($ak1['kode_akun1']) ?>" data-posisi="<?= esc($ak1['posisi']) ?>">
                                    <?= esc($ak1['kode_akun1']) ?> - <?= esc($ak1['nama_akun1']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="kode_akun1" id="add_kode_akun1" value="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">KODE AKUN 2 <span class="text-danger">*</span></label>
                        <input type="text" name="kode_akun2" class="form-control" placeholder="Contoh: 11, 12, 21, 31, 41, 51" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA AKUN 2 <span class="text-danger">*</span></label>
                        <input type="text" name="nama_akun2" class="form-control" placeholder="Contoh: Aset Lancar, Aset Tetap, Liabilitas Lancar..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">SALDO NORMAL <span class="text-danger">*</span></label>
                        <select name="posisi" id="add_posisi" class="form-select" required>
                            <option value="Debit">Debit</option>
                            <option value="Kredit">Kredit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-add-new px-4">
                        <i class="fa-solid fa-check"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT AKUN 2 -->
<div class="modal fade" id="modalEditAkun2" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; border: 1.5px solid #fed7aa; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-bottom: 1px solid #fde68a;">
                <h6 class="modal-title fw-bold text-warning-emphasis">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit Data Akun 2
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditAkun2" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">INDUK AKUN 1 <span class="text-danger">*</span></label>
                        <select name="nama_akun1" class="form-select" id="edit_nama_akun1" required>
                            <option value="">-- Pilih Induk Akun 1 --</option>
                            <?php foreach ($daftarAkun1 as $ak1): ?>
                                <option value="<?= esc($ak1['nama_akun1']) ?>" data-kode1="<?= esc($ak1['kode_akun1']) ?>">
                                    <?= esc($ak1['kode_akun1']) ?> - <?= esc($ak1['nama_akun1']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="kode_akun1" id="edit_kode_akun1" value="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">KODE AKUN 2 <span class="text-danger">*</span></label>
                        <input type="text" id="edit_kode_akun2" name="kode_akun2" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA AKUN 2 <span class="text-danger">*</span></label>
                        <input type="text" id="edit_nama_akun2" name="nama_akun2" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">SALDO NORMAL <span class="text-danger">*</span></label>
                        <select id="edit_posisi2" name="posisi" class="form-select" required>
                            <option value="Debit">Debit</option>
                            <option value="Kredit">Kredit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold text-white px-4">
                        <i class="fa-solid fa-check"></i> Update Akun 2
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select Akun 1 sync in Add Modal
    const addSelect1 = document.getElementById('add_select_akun1');
    const addKode1 = document.getElementById('add_kode_akun1');
    const addPosisi = document.getElementById('add_posisi');

    if (addSelect1) {
        addSelect1.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            addKode1.value = opt.getAttribute('data-kode1') || '';
            const pos = opt.getAttribute('data-posisi');
            if (pos) addPosisi.value = pos;
        });
    }

    // Edit Modal Trigger
    const editBtns = document.querySelectorAll('.btn-edit-akun2');
    const modalEdit = new bootstrap.Modal(document.getElementById('modalEditAkun2'));
    const formEdit = document.getElementById('formEditAkun2');
    const editSelect1 = document.getElementById('edit_nama_akun1');
    const editKode1 = document.getElementById('edit_kode_akun1');

    if (editSelect1) {
        editSelect1.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            editKode1.value = opt.getAttribute('data-kode1') || '';
        });
    }

    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            formEdit.action = '<?= base_url('akun/2/update') ?>/' + id;
            editSelect1.value = this.getAttribute('data-akun1');
            editKode1.value = this.getAttribute('data-kode1');
            document.getElementById('edit_kode_akun2').value = this.getAttribute('data-kode2');
            document.getElementById('edit_nama_akun2').value = this.getAttribute('data-nama2');
            document.getElementById('edit_posisi2').value = this.getAttribute('data-posisi');
            modalEdit.show();
        });
    });

    // Client-side search
    const searchInput = document.getElementById('akunSearch');
    const tableRows = document.querySelectorAll('#tableAkun2 tbody tr.akun-row');

    searchInput.addEventListener('input', function() {
        const val = this.value.toLowerCase();
        tableRows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(val) ? '' : 'none';
        });
    });
});
</script>

<?= $this->endSection() ?>

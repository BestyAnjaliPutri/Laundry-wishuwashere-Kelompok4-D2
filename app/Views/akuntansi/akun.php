<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <style>
        /* Styling Khusus Halaman Data Akun Persis Referensi Video */
        .btn-add-new {
            background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%);
            color: #ffffff;
            font-weight: 700;
            padding: 9px 24px;
            border: none;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-add-new:hover {
            background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
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
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .account-table-card {
            background: #ffffff;
            border: 1.5px solid #bae6fd;
            padding: 24px;
        }

        .account-table-title {
            color: #0369a1;
            font-size: 1.15rem;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
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

        .sort-icon {
            font-size: 0.72rem;
            color: #7dd3fc;
            margin-left: 3px;
        }

        /* MODAL STYLING TERANG & BERSIH */
        .modal {
            z-index: 1060 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }
        .modal-dialog {
            z-index: 1065 !important;
        }
        .modal-content {
            background-color: #ffffff !important;
            border-radius: 0 !important;
            box-shadow: 0 10px 40px rgba(2, 132, 199, 0.25) !important;
        }
        .modal-body {
            background-color: #ffffff !important;
        }
        .modal-body .form-label {
            color: #0369a1 !important;
            font-weight: 700 !important;
            font-size: 0.78rem !important;
            margin-bottom: 5px !important;
        }
        .modal-body .form-control,
        .modal-body .form-select {
            background-color: #ffffff !important;
            color: #0f172a !important;
            border: 1.5px solid #7dd3fc !important;
            font-size: 0.92rem !important;
            padding: 8px 12px !important;
        }
        .modal-body .form-control:focus,
        .modal-body .form-select:focus {
            background-color: #ffffff !important;
            color: #0f172a !important;
            border-color: #0284c7 !important;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2) !important;
        }
        .modal-body .form-control::placeholder {
            color: #94a3b8 !important;
            font-style: italic;
        }
    </style>

    <!-- 1. TOP BAR: TOMBOL + ADD NEW -->
    <div class="account-card-top">
        <div>
            <button class="btn btn-add-new" data-bs-toggle="modal" data-bs-target="#modalAddAkun">
                <i class="fa-solid fa-plus"></i>
                <span>Add New</span>
            </button>
        </div>
        <div class="d-flex align-items-center gap-2 text-muted small">
            <span style="font-size: 1.3rem;">🌸</span>
            <span class="fw-bold" style="color: #0284c7;">Akun - 3 (Detail Bagan Akun Buku Besar)</span>
        </div>
    </div>

    <!-- 2. DATA TABLE CARD PERSIS DI GAMBAR 1 -->
    <div class="account-table-card">
        <div class="account-table-title">
            <span>🫧</span>
            <span>Data Akun 3</span>
        </div>

        <!-- Controls: Entries & Search -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-3">
            <div class="d-flex align-items-center gap-2 small text-muted">
                <span>Show</span>
                <select id="entriesSelect" class="form-select form-select-sm" style="width: 75px; border-color: #bae6fd;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">All</option>
                </select>
                <span>entries</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="akunSearch" class="small text-muted fw-bold">Search:</label>
                <input type="text" id="akunSearch" class="form-control form-control-sm" placeholder="Ketik cari akun..." style="max-width: 240px; border-color: #bae6fd;">
            </div>
        </div>

        <!-- TABEL DATA AKUN -->
        <div class="table-responsive">
            <table class="table table-akun align-middle mb-0" id="tableAkun">
                <thead>
                    <tr>
                        <th style="width: 65px;">NO <i class="fa-solid fa-arrows-up-down sort-icon"></i></th>
                        <th>NAMA AKUN 1 <i class="fa-solid fa-arrows-up-down sort-icon"></i></th>
                        <th>NAMA AKUN 2 <i class="fa-solid fa-arrows-up-down sort-icon"></i></th>
                        <th style="width: 140px;">KODE AKUN 3 <i class="fa-solid fa-arrows-up-down sort-icon"></i></th>
                        <th>NAMA AKUN 3 <i class="fa-solid fa-arrows-up-down sort-icon"></i></th>
                        <th class="text-center" style="width: 170px;">ACTION <i class="fa-solid fa-arrows-up-down sort-icon"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarAkun)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Belum ada data akun. Klik <strong>"+ Add New"</strong> untuk menambahkan akun baru!
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($daftarAkun as $a): ?>
                            <tr class="akun-row">
                                <td class="fw-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= esc($a['nama_akun1']) ?></span>
                                </td>
                                <td><?= esc($a['nama_akun2']) ?></td>
                                <td>
                                    <strong class="text-primary font-monospace" style="font-size: 0.95rem;"><?= esc($a['kode_akun']) ?></strong>
                                </td>
                                <td>
                                    <strong><?= esc($a['nama_akun3']) ?></strong>
                                    <span class="badge <?= $a['posisi'] === 'Debit' ? 'bg-info-subtle text-primary border border-info' : 'bg-success-subtle text-success border border-success' ?> ms-2" style="font-size: 0.68rem;">
                                        <?= esc($a['posisi']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Tombol Edit Warna Orange -->
                                        <button type="button" class="btn btn-action-edit btn-edit-akun"
                                            data-id="<?= $a['id'] ?>"
                                            data-akun1="<?= esc($a['nama_akun1']) ?>"
                                            data-akun2="<?= esc($a['nama_akun2']) ?>"
                                            data-kode="<?= esc($a['kode_akun']) ?>"
                                            data-akun3="<?= esc($a['nama_akun3']) ?>"
                                            data-posisi="<?= esc($a['posisi']) ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditAkun">
                                            <i class="fa-solid fa-pen"></i>
                                            <span>Edit</span>
                                        </button>

                                        <!-- Tombol Delete Warna Merah -->
                                        <a href="<?= base_url('akun/hapus/' . $a['id']) ?>" 
                                           class="btn btn-action-delete"
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus akun \'<?= esc($a['nama_akun3']) ?>\' (<?= esc($a['kode_akun']) ?>)?')">
                                            <i class="fa-solid fa-trash-can"></i>
                                            <span>Delete</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top small text-muted">
            <div id="countInfo">Menampilkan <?= count($daftarAkun) ?> akun terdaftar</div>
            <div>wishuwashere. Accounting Chart of Accounts</div>
        </div>
    </div>

</div>

<!-- ==================== MODAL ADD NEW AKUN ==================== -->
<div class="modal fade" id="modalAddAkun" tabindex="-1" aria-labelledby="modalAddAkunLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-2" style="border-color: #0ea5e9;">
            <div class="modal-header" style="background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%); border-bottom: 2px solid #bae6fd;">
                <h5 class="modal-title fw-bold" style="color: #0369a1;" id="modalAddAkunLabel">
                    <i class="fa-solid fa-plus-circle me-1 text-primary"></i> Tambah Akun Baru (+ Add New)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('akun/simpan') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA AKUN 1 (Klasifikasi Induk) <span class="text-danger">*</span></label>
                        <select name="nama_akun1" class="form-select" id="add_select_akun1" required>
                            <?php if (!empty($daftarAkun1)): ?>
                                <?php foreach ($daftarAkun1 as $ak1): ?>
                                    <option value="<?= esc($ak1['nama_akun1']) ?>" <?= $ak1['nama_akun1'] === 'Beban' ? 'selected' : '' ?>>
                                        <?= esc($ak1['kode_akun1']) ?> - <?= esc($ak1['nama_akun1']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="Aset">1 - Aset</option>
                                <option value="Liabilitas">2 - Liabilitas</option>
                                <option value="Ekuitas">3 - Ekuitas</option>
                                <option value="Pendapatan">4 - Pendapatan</option>
                                <option value="Beban" selected>5 - Beban</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA AKUN 2 (Sub-Klasifikasi) <span class="text-danger">*</span></label>
                        <?php if (!empty($daftarAkun2)): ?>
                            <select name="nama_akun2" class="form-select" id="add_select_akun2" required>
                                <?php foreach ($daftarAkun2 as $ak2): ?>
                                    <option value="<?= esc($ak2['nama_akun2']) ?>" data-akun1="<?= esc($ak2['nama_akun1']) ?>">
                                        <?= esc($ak2['kode_akun2']) ?> - <?= esc($ak2['nama_akun2']) ?> (<?= esc($ak2['nama_akun1']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <input type="text" name="nama_akun2" class="form-control" placeholder="Contoh: Beban Operasional, Aset Lancar" required value="Beban Operasional">
                        <?php endif; ?>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">KODE AKUN 3</label>
                            <input type="text" name="kode_akun" class="form-control font-monospace" placeholder="Contoh: 5105" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">POSISI NORMAL</label>
                            <select name="posisi" class="form-select" required>
                                <option value="Debit">Debit</option>
                                <option value="Kredit">Kredit</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA AKUN 3 (Nama Akun Spesifik)</label>
                        <input type="text" name="nama_akun3" class="form-control" placeholder="Contoh: Beban Asuransi, Beban Telepon" required>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-top: 1.5px solid #bae6fd;">
                    <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold" style="background: linear-gradient(135deg, #0ea5e9, #0284c7); border: none;">
                        <i class="fa-solid fa-save me-1"></i> Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL EDIT AKUN ==================== -->
<div class="modal fade" id="modalEditAkun" tabindex="-1" aria-labelledby="modalEditAkunLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-2" style="border-color: #f59e0b;">
            <div class="modal-header" style="background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%); border-bottom: 2px solid #fde68a;">
                <h5 class="modal-title fw-bold text-dark" id="modalEditAkunLabel">
                    <i class="fa-solid fa-pen-to-square me-1 text-warning"></i> Edit Data Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditAkun" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA AKUN 1 (Kategori Induk)</label>
                        <select name="nama_akun1" id="edit_nama_akun1" class="form-select" required>
                            <option value="Aset">Aset</option>
                            <option value="Liabilitas">Liabilitas</option>
                            <option value="Ekuitas">Ekuitas</option>
                            <option value="Pendapatan">Pendapatan</option>
                            <option value="Beban">Beban</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA AKUN 2 (Sub-Kategori)</label>
                        <input type="text" name="nama_akun2" id="edit_nama_akun2" class="form-control" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">KODE AKUN 3</label>
                            <input type="text" name="kode_akun" id="edit_kode_akun" class="form-control font-monospace" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">POSISI NORMAL</label>
                            <select name="posisi" id="edit_posisi" class="form-select" required>
                                <option value="Debit">Debit</option>
                                <option value="Kredit">Kredit</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA AKUN 3 (Nama Akun Spesifik)</label>
                        <input type="text" name="nama_akun3" id="edit_nama_akun3" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-top: 1.5px solid #fde68a;">
                    <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold text-white" style="background-color: #f59e0b; border: none;">
                        <i class="fa-solid fa-check me-1"></i> Update Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // 1. Live Search
    const searchInput = document.getElementById('akunSearch');
    const rows = document.querySelectorAll('.akun-row');
    const countInfo = document.getElementById('countInfo');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const val = this.value.toLowerCase().trim();
            let visibleCount = 0;
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(val)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            if (countInfo) {
                countInfo.textContent = `Menampilkan ${visibleCount} dari ${rows.length} akun`;
            }
        });
    }

    // 2. Entries limit selector
    const entriesSelect = document.getElementById('entriesSelect');
    if (entriesSelect) {
        entriesSelect.addEventListener('change', function() {
            const limit = parseInt(this.value);
            let count = 0;
            rows.forEach(row => {
                if (count < limit) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });
            if (countInfo) {
                countInfo.textContent = `Menampilkan ${Math.min(limit, rows.length)} dari ${rows.length} akun`;
            }
        });
    }

    // 3. Edit Modal Populator
    const editButtons = document.querySelectorAll('.btn-edit-akun');
    const formEdit = document.getElementById('formEditAkun');
    const editAkun1 = document.getElementById('edit_nama_akun1');
    const editAkun2 = document.getElementById('edit_nama_akun2');
    const editKode = document.getElementById('edit_kode_akun');
    const editAkun3 = document.getElementById('edit_nama_akun3');
    const editPosisi = document.getElementById('edit_posisi');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            formEdit.action = '<?= base_url('akun/update') ?>/' + id;
            editAkun1.value = this.dataset.akun1;
            editAkun2.value = this.dataset.akun2;
            editKode.value = this.dataset.kode;
            editAkun3.value = this.dataset.akun3;
            editPosisi.value = this.dataset.posisi;
        });
    });

    // 4. Autofocus on modal open
    const modalAdd = document.getElementById('modalAddAkun');
    if (modalAdd) {
        modalAdd.addEventListener('shown.bs.modal', function () {
            const kodeInput = modalAdd.querySelector('input[name="kode_akun"]');
            if (kodeInput) kodeInput.focus();
        });
    }
</script>

<?= $this->endSection() ?>

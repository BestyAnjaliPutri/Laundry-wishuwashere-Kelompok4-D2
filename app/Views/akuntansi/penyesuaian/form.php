<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-0 px-md-2">

    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" style="color:#9333ea;">wishuwashere.</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('penyesuaian') ?>" style="color:#9333ea;">Jurnal Penyesuaian</a></li>
                    <li class="breadcrumb-item active">Edit Penyesuaian</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0" style="color:#7c3aed;">
                <i class="fa-solid fa-pen-to-square me-2"></i>Edit Jurnal Penyesuaian (AJE)
            </h4>
            <small class="text-muted">Adjusting Journal Entries — penyesuaian akhir periode akuntansi</small>
        </div>
        <a href="<?= base_url('penyesuaian') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius:10px;">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Penyesuaian</span>
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">

            <!-- Form Card -->
            <div class="card border-2 shadow-sm" style="border-color:#e9d5ff !important; border-radius:18px;">
                <div class="card-header py-3 px-4" style="background:linear-gradient(135deg,#faf5ff,#f3e8ff); border-bottom:2px solid #e9d5ff; border-radius:18px 18px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:38px;height:38px;background:linear-gradient(135deg,#9333ea,#7e22ce);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-book-open text-white"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="color:#7c3aed;">
                                Edit Jurnal Penyesuaian: <code style="color:#9333ea;"><?= esc($penyesuaian['no_bukti'] ?? '') ?></code>
                            </div>
                            <div class="small text-muted">Pastikan jumlah debit = kredit agar jurnal balance</div>
                        </div>
                    </div>
                </div>

                <form method="post" action="<?= base_url('penyesuaian/update/' . $penyesuaian['id']) ?>">
                    <?= csrf_field() ?>
                    <div class="p-4">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">NO. BUKTI <span class="text-danger">*</span></label>
                                <input type="text" name="no_bukti" class="form-control"
                                       value="<?= esc($penyesuaian['no_bukti'] ?? '') ?>" required
                                       style="border:1.5px solid #e9d5ff;border-radius:10px;">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">TANGGAL <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control"
                                       value="<?= esc($penyesuaian['tanggal'] ?? date('Y-m-d')) ?>" required
                                       style="border:1.5px solid #e9d5ff;border-radius:10px;">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">JENIS PENYESUAIAN <span class="text-danger">*</span></label>
                                <select name="keterangan" class="form-select" style="border:1.5px solid #e9d5ff;border-radius:10px;">
                                    <?php
                                    $jenis = ['Penyusutan Peralatan/Mesin Cuci','Perlengkapan Deterjen Terpakai','Beban Gaji Terutang (Accrued)','Beban Listrik/Air Terutang (Accrued)','Pendapatan Diterima di Muka (Unearned)','Beban Dibayar di Muka (Prepaid)','Koreksi Kesalahan Pencatatan','Penyesuaian Lainnya'];
                                    $selectedKet = $penyesuaian['keterangan'] ?? '';
                                    foreach ($jenis as $j):
                                    ?>
                                    <option value="<?= $j ?>" <?= $selectedKet === $j ? 'selected' : '' ?>><?= $j ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- DEBIT -->
                            <div class="col-12 mt-2">
                                <div class="d-flex align-items-center gap-2 pb-2 border-bottom" style="border-color:#bae6fd !important;">
                                    <span style="width:4px;height:18px;background:linear-gradient(#0ea5e9,#0284c7);border-radius:2px;display:inline-block;"></span>
                                    <span class="badge" style="background:#e0f2fe;color:#0284c7;font-weight:800;font-size:0.85rem;padding:5px 12px;">DEBIT (D)</span>
                                    <small class="text-muted fw-bold">— Akun yang bertambah / didebit</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">PILIH KODE AKUN DEBIT <span class="text-danger">*</span></label>
                                <select name="debit_kode_akun" class="form-select" id="aje_selectDebit" onchange="ajeDebit(this)" required
                                        style="border:1.5px solid #bae6fd;border-radius:10px;">
                                    <option value="">— Pilih Akun Debit —</option>
                                    <?php foreach ($daftarAkun as $akun): ?>
                                        <option value="<?= esc($akun['kode_akun']) ?>" data-nama="<?= esc($akun['nama_akun3']) ?>"
                                            <?= (($penyesuaian['debit_kode_akun'] ?? '') == $akun['kode_akun']) ? 'selected' : '' ?>>
                                            <?= esc($akun['kode_akun']) ?> — <?= esc($akun['nama_akun3']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">NAMA AKUN DEBIT <span class="text-danger">*</span></label>
                                <input type="text" name="debit_nama_akun" id="aje_inputDebitNama" class="form-control"
                                       value="<?= esc($penyesuaian['debit_nama_akun'] ?? '') ?>" placeholder="Otomatis terisi..." required
                                       style="border:1.5px solid #bae6fd;border-radius:10px;background:#f8fdff;">
                            </div>

                            <!-- KREDIT -->
                            <div class="col-12 mt-2">
                                <div class="d-flex align-items-center gap-2 pb-2 border-bottom" style="border-color:#fecaca !important;">
                                    <span style="width:4px;height:18px;background:linear-gradient(#f43f5e,#dc2626);border-radius:2px;display:inline-block;"></span>
                                    <span class="badge" style="background:#fee2e2;color:#dc2626;font-weight:800;font-size:0.85rem;padding:5px 12px;">KREDIT (K)</span>
                                    <small class="text-muted fw-bold">— Akun yang berkurang / dikredit</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">PILIH KODE AKUN KREDIT <span class="text-danger">*</span></label>
                                <select name="kredit_kode_akun" class="form-select" id="aje_selectKredit" onchange="ajeKredit(this)" required
                                        style="border:1.5px solid #fecaca;border-radius:10px;">
                                    <option value="">— Pilih Akun Kredit —</option>
                                    <?php foreach ($daftarAkun as $akun): ?>
                                        <option value="<?= esc($akun['kode_akun']) ?>" data-nama="<?= esc($akun['nama_akun3']) ?>"
                                            <?= (($penyesuaian['kredit_kode_akun'] ?? '') == $akun['kode_akun']) ? 'selected' : '' ?>>
                                            <?= esc($akun['kode_akun']) ?> — <?= esc($akun['nama_akun3']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">NAMA AKUN KREDIT <span class="text-danger">*</span></label>
                                <input type="text" name="kredit_nama_akun" id="aje_inputKreditNama" class="form-control"
                                       value="<?= esc($penyesuaian['kredit_nama_akun'] ?? '') ?>" placeholder="Otomatis terisi..." required
                                       style="border:1.5px solid #fecaca;border-radius:10px;background:#fff8f8;">
                            </div>

                            <!-- Nominal -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">NOMINAL (RP) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text fw-bold" style="background:#f3e8ff;border:1.5px solid #e9d5ff;border-right:none;color:#7c3aed;border-radius:10px 0 0 10px;">Rp</span>
                                    <input type="number" name="nominal" class="form-control fw-bold"
                                           style="color:#7c3aed;font-size:1.05rem;border:1.5px solid #e9d5ff;border-left:none;border-radius:0 10px 10px 0;"
                                           value="<?= esc($penyesuaian['nominal'] ?? '') ?>" min="1" required>
                                </div>
                            </div>

                        </div>

                        <!-- Submit -->
                        <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top" style="border-color:#e9d5ff !important;">
                            <button type="submit" class="btn px-4 fw-bold d-inline-flex align-items-center gap-2"
                                    style="background:linear-gradient(135deg,#9333ea,#7e22ce);color:#fff;border:none;border-radius:10px;box-shadow:0 4px 12px rgba(147,51,234,0.3);">
                                <i class="fa-solid fa-save"></i>
                                <span>Update Penyesuaian</span>
                            </button>
                            <a href="<?= base_url('penyesuaian') ?>" class="btn btn-outline-secondary px-4" style="border-radius:10px;">
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function ajeDebit(sel) { document.getElementById('aje_inputDebitNama').value = sel.options[sel.selectedIndex].getAttribute('data-nama') || ''; }
    function ajeKredit(sel) { document.getElementById('aje_inputKreditNama').value = sel.options[sel.selectedIndex].getAttribute('data-nama') || ''; }
</script>

<?= $this->endSection() ?>

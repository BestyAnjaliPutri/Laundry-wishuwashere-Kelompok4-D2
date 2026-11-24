<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-0 px-md-2">

    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" style="color:#0284c7;">wishuwashere.</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('jurnal') ?>" style="color:#0284c7;">Jurnal Umum</a></li>
                    <li class="breadcrumb-item active"><?= $action === 'tambah' ? 'Tambah Jurnal' : 'Edit Jurnal' ?></li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0" style="color:#0369a1;">
                <i class="fa-solid <?= $action === 'tambah' ? 'fa-plus-circle' : 'fa-pen-to-square' ?> me-2"></i>
                <?= $action === 'tambah' ? 'Tambah Entri Jurnal Umum' : 'Edit Entri Jurnal Umum' ?>
            </h4>
            <small class="text-muted">Pencatatan transaksi jurnal umum bebas untuk kas, piutang, utang, modal, dll.</small>
        </div>
        <a href="<?= base_url('jurnal') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius:10px;">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Jurnal</span>
        </a>
    </div>

    <!-- Alert Flash -->
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert"
             style="border-radius:12px; background:#fef2f2; border-left:4px solid #dc2626 !important;">
            <i class="fa-solid fa-triangle-exclamation text-danger fs-5"></i>
            <div><?= session('error') ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-9">

            <!-- Form Card -->
            <div class="card border-2 shadow-sm" style="border-color:#bae6fd !important; border-radius:18px;">
                <div class="card-header py-3 px-4" style="background:linear-gradient(135deg,#e0f2fe,#f0f9ff); border-bottom:2px solid #bae6fd; border-radius:18px 18px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:38px;height:38px;background:linear-gradient(135deg,#0ea5e9,#0284c7);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-book text-white"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="color:#0369a1;">
                                <?= $action === 'tambah' ? 'Formulir Input Transaksi Jurnal Umum' : 'Edit Transaksi Jurnal: ' . esc($jurnal['no_bukti'] ?? '') ?>
                            </div>
                            <div class="small text-muted">Pastikan jumlah debit = kredit agar jurnal balance</div>
                        </div>
                    </div>
                </div>

                <form method="post" action="<?= $action === 'tambah' ? base_url('jurnal/simpan') : base_url('jurnal/update/' . $jurnal['id']) ?>">
                    <?= csrf_field() ?>
                    <div class="p-4">
                        <div class="row g-3">

                            <!-- Info Transaksi -->
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span style="width:4px;height:18px;background:linear-gradient(#0ea5e9,#0284c7);border-radius:2px;display:inline-block;"></span>
                                    <span class="fw-bold small text-uppercase" style="color:#0369a1;letter-spacing:.5px;">📋 Informasi Transaksi</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">NO. BUKTI <span class="text-danger">*</span></label>
                                <input type="text" name="no_bukti" class="form-control"
                                       value="<?= esc($jurnal['no_bukti'] ?? 'JU-' . date('Ymd-His')) ?>"
                                       placeholder="Contoh: JU-202611-001" required
                                       style="border:1.5px solid #bae6fd;border-radius:10px;">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">TANGGAL TRANSAKSI <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control"
                                       value="<?= esc($jurnal['tanggal'] ?? date('Y-m-d')) ?>" required
                                       style="border:1.5px solid #bae6fd;border-radius:10px;">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">TIPE TRANSAKSI <span class="text-danger">*</span></label>
                                <input type="text" name="tipe_transaksi" class="form-control" list="tipeTransaksiList"
                                       value="<?= esc($jurnal['tipe_transaksi'] ?? 'Penerimaan Kas') ?>"
                                       placeholder="Pilih atau ketik tipe transaksi..." required
                                       style="border:1.5px solid #bae6fd;border-radius:10px;">
                                <datalist id="tipeTransaksiList">
                                    <option value="Penerimaan Kas">
                                    <option value="Pengeluaran Kas">
                                    <option value="Penyetoran Modal">
                                    <option value="Penerimaan Pendapatan">
                                    <option value="Pelunasan Piutang">
                                    <option value="Pembayaran Utang">
                                    <option value="Pembelian Perlengkapan">
                                    <option value="Pengeluaran Beban">
                                    <option value="Pengambilan Prive">
                                    <option value="Lainnya">
                                </datalist>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">NOMINAL (RP) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text fw-bold" style="background:#e0f2fe;border:1.5px solid #bae6fd;border-right:none;color:#0369a1;border-radius:10px 0 0 10px;">Rp</span>
                                    <input type="number" name="nominal" id="inputNominal" class="form-control fw-bold"
                                           style="color:#0369a1;font-size:1.05rem;border:1.5px solid #bae6fd;border-left:none;border-radius:0 10px 10px 0;"
                                           value="<?= esc($jurnal['nominal'] ?? '') ?>"
                                           placeholder="Contoh: 150000" min="1" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">KETERANGAN TRANSAKSI <span class="text-danger">*</span></label>
                                <textarea name="keterangan" class="form-control" rows="2"
                                          placeholder="Contoh: Pembayaran piutang dari pelanggan Bapak Budi..."
                                          required style="border:1.5px solid #bae6fd;border-radius:10px;"><?= esc($jurnal['keterangan'] ?? '') ?></textarea>
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
                                <select name="debit_kode_akun" class="form-select" id="selectDebit" onchange="syncDebit(this)" required
                                        style="border:1.5px solid #bae6fd;border-radius:10px;">
                                    <option value="">— Pilih Akun Debit —</option>
                                    <?php foreach ($daftarAkun as $akun): ?>
                                        <option value="<?= esc($akun['kode_akun']) ?>" data-nama="<?= esc($akun['nama_akun3']) ?>"
                                            <?= (isset($jurnal['debit_kode_akun']) && $jurnal['debit_kode_akun'] == $akun['kode_akun']) ? 'selected' : '' ?>>
                                            <?= esc($akun['kode_akun']) ?> — <?= esc($akun['nama_akun3']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">NAMA AKUN DEBIT <span class="text-danger">*</span></label>
                                <input type="text" name="debit_nama_akun" id="inputDebitNama" class="form-control"
                                       value="<?= esc($jurnal['debit_nama_akun'] ?? '') ?>" placeholder="Otomatis terisi..." required
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
                                <select name="kredit_kode_akun" class="form-select" id="selectKredit" onchange="syncKredit(this)" required
                                        style="border:1.5px solid #fecaca;border-radius:10px;">
                                    <option value="">— Pilih Akun Kredit —</option>
                                    <?php foreach ($daftarAkun as $akun): ?>
                                        <option value="<?= esc($akun['kode_akun']) ?>" data-nama="<?= esc($akun['nama_akun3']) ?>"
                                            <?= (isset($jurnal['kredit_kode_akun']) && $jurnal['kredit_kode_akun'] == $akun['kode_akun']) ? 'selected' : '' ?>>
                                            <?= esc($akun['kode_akun']) ?> — <?= esc($akun['nama_akun3']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">NAMA AKUN KREDIT <span class="text-danger">*</span></label>
                                <input type="text" name="kredit_nama_akun" id="inputKreditNama" class="form-control"
                                       value="<?= esc($jurnal['kredit_nama_akun'] ?? '') ?>" placeholder="Otomatis terisi..." required
                                       style="border:1.5px solid #fecaca;border-radius:10px;background:#fff8f8;">
                            </div>

                        </div>

                        <!-- Submit -->
                        <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top" style="border-color:#bae6fd !important;">
                            <button type="submit" class="btn btn-primary px-4 fw-bold d-inline-flex align-items-center gap-2"
                                    style="background:linear-gradient(135deg,#0ea5e9,#0284c7);border:none;border-radius:10px;box-shadow:0 4px 12px rgba(2,132,199,0.3);">
                                <i class="fa-solid fa-save"></i>
                                <span><?= $action === 'tambah' ? 'Simpan Jurnal' : 'Update Jurnal' ?></span>
                            </button>
                            <a href="<?= base_url('jurnal') ?>" class="btn btn-outline-secondary px-4" style="border-radius:10px;">
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tips -->
            <div class="card mt-3 border-0" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-left:4px solid #f59e0b;border-radius:12px;">
                <div class="card-body py-2 px-3 d-flex gap-2 align-items-start">
                    <i class="fa-solid fa-lightbulb text-warning mt-1"></i>
                    <small class="text-muted">
                        <strong class="text-warning">Tips:</strong> Pilih akun dari dropdown → nama akun terisi otomatis.
                        Pastikan nominal Debit = Kredit. No. Bukti unik per transaksi (contoh: JU-YYYYMMDD-001).
                    </small>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function syncDebit(select) {
        document.getElementById('inputDebitNama').value = select.options[select.selectedIndex].getAttribute('data-nama') || '';
    }
    function syncKredit(select) {
        document.getElementById('inputKreditNama').value = select.options[select.selectedIndex].getAttribute('data-nama') || '';
    }
</script>

<?= $this->endSection() ?>

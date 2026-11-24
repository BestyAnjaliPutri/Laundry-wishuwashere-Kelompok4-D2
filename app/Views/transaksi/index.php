<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-0 px-md-2">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" style="color:#0284c7;">wishuwashere.</a></li>
                    <li class="breadcrumb-item active">Kasir & Transaksi</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0" style="color:#0369a1;">
                <i class="fa-solid fa-cash-register me-2"></i>Kasir & Transaksi Cucian
            </h4>
            <small class="text-muted">Pencatatan order baru, kalkulasi biaya otomatis, cetak nota, dan ubah status.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('kasir/seed') ?>" class="btn btn-outline-info d-flex align-items-center gap-2 px-3 py-2 shadow-sm"
               style="border-radius:12px; font-weight: 600;"
               onclick="return confirm('Tambahkan 30+ transaksi sampel realistis secara otomatis?')">
                <i class="fa-solid fa-wand-magic-sparkles text-info"></i>
                <span>+ Generate 30 Transaksi</span>
            </a>
            <button class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2"
                    style="background:linear-gradient(135deg,#0ea5e9,#0284c7);border:none;border-radius:12px;box-shadow:0 4px 15px rgba(2,132,199,0.3);"
                    data-bs-toggle="modal" data-bs-target="#modalKasir">
                <i class="fa-solid fa-plus-circle"></i>
                <span class="fw-semibold">Terima Cucian Baru</span>
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 mb-3" role="alert"
         style="border-radius:12px; background:#f0fdf4; border-left:4px solid #22c55e !important;">
        <i class="fa-solid fa-circle-check me-2 text-success"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 mb-3" role="alert"
         style="border-radius:12px; background:#fef2f2; border-left:4px solid #dc2626 !important;">
        <i class="fa-solid fa-circle-exclamation me-2 text-danger"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Summary Stats -->
    <?php
    $totalTransaksi = count($transaksi);
    $totalPendapatan = array_sum(array_column($transaksi, 'total_biaya'));
    $lunas = count(array_filter($transaksi, fn($t) => $t['status_bayar'] === 'Lunas'));
    $belumLunas = $totalTransaksi - $lunas;
    ?>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center border-2 h-100" style="border-color:#bae6fd !important; background:#ecfeff;">
                <div class="fw-bold" style="font-size:1.6rem; color:#0891b2;"><?= $totalTransaksi ?></div>
                <small class="text-muted fw-semibold">Total Transaksi</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center border-2 h-100" style="border-color:#86efac !important; background:#f0fdf4;">
                <div class="fw-bold" style="font-size:1.1rem; color:#16a34a;">
                    Rp <?= number_format($totalPendapatan, 0, ',', '.') ?>
                </div>
                <small class="text-muted fw-semibold">Total Pendapatan</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center border-2 h-100" style="border-color:#86efac !important; background:#f0fdf4;">
                <div class="fw-bold" style="font-size:1.6rem; color:#16a34a;"><?= $lunas ?></div>
                <small class="text-muted fw-semibold">✅ Lunas</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center border-2 h-100" style="border-color:#fca5a5 !important; background:#fff5f5;">
                <div class="fw-bold" style="font-size:1.6rem; color:#dc2626;"><?= $belumLunas ?></div>
                <small class="text-muted fw-semibold">⏳ Belum Lunas</small>
            </div>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="card border-2" style="border-color:#bae6fd !important;">
        <div class="card-header d-flex justify-content-between align-items-center py-3"
             style="background:linear-gradient(135deg,#e0f2fe,#f0f9ff); border-bottom:2px solid #bae6fd;">
            <span class="fw-bold" style="color:#0369a1;">
                <i class="fa-solid fa-receipt me-1"></i> Riwayat Transaksi Cucian
            </span>
            <input type="text" id="searchTransaksi" class="form-control" style="max-width:220px;font-size:.85rem;" placeholder="🔍 Cari transaksi...">
        </div>
        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" style="border-color:#e0f2fe; font-size:.85rem;" id="tableTransaksi">
                    <thead>
                        <tr style="background:linear-gradient(135deg,#e0f2fe,#f0f9ff); color:#0369a1;">
                            <th style="min-width:110px;">NO. NOTA</th>
                            <th style="min-width:140px;">PELANGGAN</th>
                            <th style="min-width:160px;">LAYANAN & BERAT</th>
                            <th style="min-width:120px;" class="text-end">TOTAL BIAYA</th>
                            <th style="min-width:130px;">STATUS CUCIAN</th>
                            <th style="min-width:120px;">STATUS BAYAR</th>
                            <th style="min-width:140px;">CATATAN</th>
                            <th style="min-width:100px;" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transaksi)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5" style="background:#f8fdff;">
                                <div style="font-size:2rem;margin-bottom:8px;">🧺</div>
                                <strong class="d-block mb-1">Belum ada transaksi cucian</strong>
                                <small class="text-muted">Klik tombol "Terima Cucian Baru" untuk membuat order pertama.</small>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($transaksi as $t): ?>
                        <tr>
                            <td>
                                <code style="color:#0284c7; font-size:.8rem;">#<?= esc($t['kode_transaksi']) ?></code><br>
                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></small>
                            </td>
                            <td>
                                <div class="fw-bold"><?= esc($t['nama_pelanggan']) ?></div>
                                <small class="text-muted"><i class="fa-solid fa-phone me-1"></i><?= esc($t['no_hp']) ?></small>
                            </td>
                            <td>
                                <span class="badge mb-1" style="background:#e0f2fe;color:#0369a1;font-size:.72rem;"><?= esc($t['jenis_paket']) ?></span>
                                <div class="small fw-bold"><?= esc($t['nama_paket']) ?></div>
                                <small class="text-muted"><?= esc($t['berat_qty']) ?> <?= $t['jenis_paket'] === 'kiloan' ? 'Kg' : 'Pcs' ?></small>
                            </td>
                            <td class="text-end fw-bold" style="color:#0369a1;">
                                Rp <?= number_format($t['total_biaya'], 0, ',', '.') ?>
                            </td>
                            <td>
                                <?php
                                $sc = $t['status_cucian'];
                                $badgeStyle = match($sc) {
                                    'Sedang Dicuci'  => 'background:#fef3c7;color:#d97706;',
                                    'Proses Setrika' => 'background:#cffafe;color:#0891b2;',
                                    'Siap Diambil'   => 'background:#dcfce7;color:#16a34a;',
                                    'Sudah Diambil'  => 'background:#f1f5f9;color:#64748b;',
                                    default          => 'background:#e0f2fe;color:#0369a1;',
                                };
                                $icons = match($sc) {
                                    'Sedang Dicuci'  => '🫧',
                                    'Proses Setrika' => '👕',
                                    'Siap Diambil'   => '✅',
                                    'Sudah Diambil'  => '📦',
                                    default          => '📋',
                                };
                                ?>
                                <span class="badge" style="<?= $badgeStyle ?> font-size:.75rem; padding:5px 9px;">
                                    <?= $icons ?> <?= esc($sc) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($t['status_bayar'] === 'Lunas'): ?>
                                    <span class="badge" style="background:#dcfce7;color:#16a34a;font-size:.75rem;padding:5px 9px;">
                                        ✅ Lunas <small>(<?= esc($t['metode_bayar']) ?>)</small>
                                    </span>
                                <?php else: ?>
                                    <span class="badge" style="background:#fee2e2;color:#dc2626;font-size:.75rem;padding:5px 9px;">
                                        ⏳ Belum Lunas
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><small class="text-muted"><?= esc($t['catatan'] ?: '—') ?></small></td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="<?= base_url('kasir/cetak/' . $t['id']) ?>" target="_blank"
                                       class="btn btn-sm" title="Cetak Nota"
                                       style="background:#e0f2fe;color:#0284c7;border:1px solid #bae6fd;">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-edit-status"
                                            data-bs-toggle="modal" data-bs-target="#modalUpdateStatus"
                                            data-id="<?= $t['id'] ?>" data-kode="<?= $t['kode_transaksi'] ?>"
                                            data-status="<?= $t['status_cucian'] ?>" data-bayar="<?= $t['status_bayar'] ?>"
                                            title="Update Status"
                                            style="background:#fef3c7;color:#d97706;border:1px solid #fde68a;">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
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

<!-- ===== MODAL KASIR ===== -->
<div class="modal fade" id="modalKasir" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-2" style="border-color:#bae6fd; border-radius:18px;">
            <form action="<?= base_url('kasir/simpan') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header py-3" style="background:linear-gradient(135deg,#e0f2fe,#f0f9ff); border-bottom:2px solid #bae6fd; border-radius:18px 18px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,#0ea5e9,#0284c7);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-shirt text-white" style="font-size:.9rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" style="color:#0369a1;">Terima Cucian Baru</h5>
                            <small class="text-muted">Input order cucian & kalkulasi biaya otomatis</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-user me-1 text-primary"></i>Pilih Pelanggan <span class="text-danger">*</span></label>
                            <select name="pelanggan_id" id="selectPelanggan" class="form-select" required style="border:1.5px solid #bae6fd;border-radius:10px;">
                                <option value="">— Pilih Pelanggan —</option>
                                <?php foreach ($pelanggan as $plg): ?>
                                    <option value="<?= $plg['id'] ?>"><?= esc($plg['nama']) ?> (<?= esc($plg['no_hp']) ?>)</option>
                                <?php endforeach; ?>
                                <option value="baru">➕ Pelanggan Baru (Input Langsung)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-box-open me-1 text-primary"></i>Paket Layanan <span class="text-danger">*</span></label>
                            <select name="paket_id" id="selectPaket" class="form-select" required style="border:1.5px solid #bae6fd;border-radius:10px;">
                                <option value="">— Pilih Layanan —</option>
                                <?php foreach ($paket as $p): ?>
                                    <option value="<?= $p['id'] ?>" data-harga="<?= $p['harga'] ?>">
                                        <?= esc($p['nama_paket']) ?> — Rp <?= number_format($p['harga'], 0, ',', '.') ?> (<?= $p['jenis'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Form Pelanggan Baru -->
                        <div class="col-12 d-none" id="formPelangganBaru">
                            <div class="p-3 rounded-3" style="background:#f0f9ff; border:1.5px solid #bae6fd;">
                                <h6 class="fw-bold mb-2" style="color:#0284c7;"><i class="fa-solid fa-user-plus me-1"></i>Data Pelanggan Baru</h6>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" name="nama_pelanggan_baru" class="form-control" placeholder="Nama Lengkap" style="border:1.5px solid #bae6fd;border-radius:10px;">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="no_hp_baru" class="form-control" placeholder="No. WhatsApp" style="border:1.5px solid #bae6fd;border-radius:10px;">
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="alamat_baru" class="form-control" placeholder="Alamat (opsional)" style="border:1.5px solid #bae6fd;border-radius:10px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-weight-scale me-1 text-primary"></i>Berat (Kg) / Qty (Pcs) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" min="0.1" name="berat_qty" id="inputBerat" class="form-control" value="1.0" required
                                   style="border:1.5px solid #bae6fd;border-radius:10px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-money-bill-wave me-1 text-success"></i>Total Biaya (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text fw-bold" style="background:#e0f2fe;border:1.5px solid #bae6fd;border-right:none;color:#0369a1;border-radius:10px 0 0 10px;">Rp</span>
                                <input type="text" id="displayTotal" class="form-control fw-bold" readonly value="0"
                                       style="border:1.5px solid #bae6fd;border-left:none;border-radius:0 10px 10px 0;color:#16a34a;font-size:1.05rem;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-credit-card me-1 text-primary"></i>Status Bayar</label>
                            <select name="status_bayar" class="form-select" style="border:1.5px solid #bae6fd;border-radius:10px;">
                                <option value="Belum Lunas">⏳ Belum Lunas (Bayar Nanti)</option>
                                <option value="Lunas">✅ Lunas (Bayar Sekarang)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-wallet me-1 text-primary"></i>Metode Pembayaran</label>
                            <select name="metode_bayar" class="form-select" style="border:1.5px solid #bae6fd;border-radius:10px;">
                                <option value="Tunai">💵 Tunai / Cash</option>
                                <option value="QRIS">📱 QRIS</option>
                                <option value="Transfer">🏦 Transfer Bank</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-note-sticky me-1 text-primary"></i>Catatan Khusus</label>
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: jangan pakai pewangi, ada noda membandel di kerah..."
                                      style="border:1.5px solid #bae6fd;border-radius:10px;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-3" style="background:#f8fdff; border-top:2px solid #bae6fd; border-radius:0 0 18px 18px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Batal</button>
                    <button type="submit" class="btn fw-bold px-4"
                            style="background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;border:none;border-radius:10px;box-shadow:0 4px 12px rgba(2,132,199,0.25);">
                        <i class="fa-solid fa-check me-1"></i>Simpan Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== MODAL UPDATE STATUS ===== -->
<div class="modal fade" id="modalUpdateStatus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-2" style="border-color:#fde68a; border-radius:18px;">
            <form action="<?= base_url('kasir/update-status') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="updateId">
                <div class="modal-header py-3" style="background:linear-gradient(135deg,#fffbeb,#fef3c7); border-bottom:2px solid #fde68a; border-radius:18px 18px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-pen-to-square text-white" style="font-size:.9rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" style="color:#92400e;">Update Status Cucian</h5>
                            <small class="text-muted">Perbarui progress dan pembayaran order</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#334155;">Nomor Nota</label>
                        <input type="text" id="updateKode" class="form-control fw-bold" readonly
                               style="border:1.5px solid #fde68a;border-radius:10px;background:#fffbeb;color:#92400e;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-shirt me-1 text-warning"></i>Status Pengerjaan Cucian</label>
                        <select name="status_cucian" id="updateStatusCucian" class="form-select" style="border:1.5px solid #fde68a;border-radius:10px;">
                            <option value="Baru Masuk">📋 Baru Masuk</option>
                            <option value="Sedang Dicuci">🫧 Sedang Dicuci</option>
                            <option value="Proses Setrika">👕 Proses Setrika</option>
                            <option value="Siap Diambil">✅ Siap Diambil</option>
                            <option value="Sudah Diambil">📦 Sudah Diambil</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small" style="color:#334155;"><i class="fa-solid fa-credit-card me-1 text-warning"></i>Status Pembayaran</label>
                        <select name="status_bayar" id="updateStatusBayar" class="form-select" style="border:1.5px solid #fde68a;border-radius:10px;">
                            <option value="Belum Lunas">⏳ Belum Lunas</option>
                            <option value="Lunas">✅ Lunas</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer py-3" style="background:#fffdf0; border-top:2px solid #fde68a; border-radius:0 0 18px 18px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Tutup</button>
                    <button type="submit" class="btn fw-bold px-4"
                            style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;border-radius:10px;box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                        <i class="fa-solid fa-check me-1"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('selectPelanggan').addEventListener('change', function() {
        document.getElementById('formPelangganBaru').classList.toggle('d-none', this.value !== 'baru');
    });

    const selectPaket = document.getElementById('selectPaket');
    const inputBerat = document.getElementById('inputBerat');
    const displayTotal = document.getElementById('displayTotal');

    function hitungTotal() {
        const opt = selectPaket.options[selectPaket.selectedIndex];
        const harga = opt ? parseFloat(opt.getAttribute('data-harga')) || 0 : 0;
        const berat = parseFloat(inputBerat.value) || 0;
        displayTotal.value = new Intl.NumberFormat('id-ID').format(Math.round(harga * berat));
    }

    selectPaket.addEventListener('change', hitungTotal);
    inputBerat.addEventListener('input', hitungTotal);

    document.querySelectorAll('.btn-edit-status').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('updateId').value = this.dataset.id;
            document.getElementById('updateKode').value = '#' + this.dataset.kode;
            document.getElementById('updateStatusCucian').value = this.dataset.status;
            document.getElementById('updateStatusBayar').value = this.dataset.bayar;
        });
    });

    // Live search
    document.getElementById('searchTransaksi').addEventListener('input', function() {
        const kw = this.value.toLowerCase();
        document.querySelectorAll('#tableTransaksi tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(kw) ? '' : 'none';
        });
    });
</script>

<?= $this->endSection() ?>

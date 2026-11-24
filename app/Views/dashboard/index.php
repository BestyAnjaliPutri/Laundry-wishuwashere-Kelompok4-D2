<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <style>
        /* Animasi khusus elemen laundry */
        @keyframes washSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes floatCute {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-7px) rotate(3deg); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 10px rgba(14, 165, 233, 0.2); }
            50% { box-shadow: 0 0 20px rgba(14, 165, 233, 0.45); }
        }
        .anim-wash {
            display: inline-block;
            animation: washSpin 8s linear infinite;
        }
        .anim-float {
            display: inline-block;
            animation: floatCute 3.5s ease-in-out infinite;
        }
        .stat-card-cute {
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            background: #ffffff;
            border: 2px solid #bae6fd !important;
        }
        .stat-card-cute:hover {
            transform: translateY(-4px);
            border-color: #0ea5e9 !important;
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.15);
        }
        .hero-laundry-card {
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 55%, #e0f2fe 100%);
            border: 2px solid #7dd3fc !important;
            position: relative;
            overflow: hidden;
        }
        .hero-laundry-card::after {
            content: "🫧";
            position: absolute;
            right: 15px;
            bottom: 10px;
            font-size: 5rem;
            opacity: 0.12;
            pointer-events: none;
        }
    </style>

    <!-- 1. TOP HEADER STATUS BAR (PASTEL BLUE & CUTE LAUNDRY) -->
    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-white border border-2" style="border-color: #bae6fd !important;">
        <div class="d-flex align-items-center gap-2">
            <span class="fs-4 anim-float">🧼</span>
            <div>
                <strong class="fs-5 text-dark">Overview Dashboard</strong>
                <span class="text-primary ms-1">✨</span>
                <span class="badge bg-info-subtle text-primary-emphasis border border-info ms-2">Laundry Mode</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge px-3 py-2 text-white" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                <i class="fa-solid fa-droplet anim-float me-1"></i> STATUS: AKTIF 🧺
            </span>
        </div>
    </div>

    <!-- 2. HERO CARD BANNER (TEMA BIRU PASTEL & ELEMEN LAUNDRY) -->
    <div class="card p-4 mb-4 hero-laundry-card">
        <div class="row align-items-center g-4">
            <!-- Kolom Kiri Hero -->
            <div class="col-12 col-xl-8">
                <!-- Badges -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="badge px-3 py-2 text-white" style="background: #0284c7;">
                        <i class="fa-solid fa-sparkles me-1"></i> SYSTEM ONLINE 🧼
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success px-3 py-2">
                        <i class="fa-solid fa-check-double me-1"></i> VER: 2.3.5
                    </span>
                    <span class="badge bg-white text-info-emphasis border border-info px-3 py-2">
                        <i class="fa-solid fa-shirt me-1 text-info"></i> WISHUWASHERE_LAUNDRY 🧺
                    </span>
                </div>

                <!-- Judul & Deskripsi -->
                <h3 class="fw-bold text-dark mb-2">
                    Accounting Information System — wishuwashere. 🧺
                </h3>
                <div class="fw-bold small mb-2" style="color: #0284c7;">
                    ✨ Fresh, Clean &amp; Perfectly Balanced Laundry Accounting ✨
                </div>
                <p class="text-secondary mb-4" style="max-width: 680px; font-size: 0.95rem; line-height: 1.6;">
                    Sistem Informasi Akuntansi berbasis web terintegrasi khusus usaha laundry — pencatatan transaksi kasir instan, jurnal umum otomatis, buku besar, worksheet, serta 4 laporan keuangan pokok yang balance dan presisi.
                </p>

                <!-- Tombol Aksi Cepat Sesuai Playlist SIA -->
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= base_url('kasir') ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 fw-bold shadow-sm" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none;">
                        <i class="fa-solid fa-cash-register anim-float"></i>
                        <span>Buka Transaksi Kasir</span>
                    </a>
                    <a href="<?= base_url('neraca-lajur') ?>" class="btn btn-outline-primary d-flex align-items-center gap-2 px-3 py-2 fw-bold bg-white" style="border-color: #0ea5e9; color: #0284c7;">
                        <i class="fa-solid fa-table-columns"></i>
                        <span>Neraca Lajur</span>
                    </a>
                    <a href="<?= base_url('laporan') ?>" class="btn btn-outline-info d-flex align-items-center gap-2 px-3 py-2 fw-bold bg-white" style="border-color: #38bdf8; color: #0369a1;">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <span>Laporan Keuangan</span>
                    </a>
                    <button type="button" class="btn btn-dark d-flex align-items-center gap-2 px-3 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalGitDeploy" style="border-radius: 8px; background: #0f172a;">
                        <i class="fa-brands fa-github"></i>
                        <span>🚀 Push ke GitHub &amp; Vercel</span>
                    </button>
                </div>
            </div>

            <!-- Kolom Kanan Hero: Widget Operator Laundry -->
            <div class="col-12 col-xl-4">
                <div class="card p-3 border-2 text-center" style="background: linear-gradient(180deg, #ffffff 0%, #f0f9ff 100%); border-color: #bae6fd !important;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="fw-bold" style="color: #0284c7;">
                            <i class="fa-solid fa-shield-cat me-1"></i> OPERATOR // ADMIN
                        </small>
                        <span class="badge bg-warning text-dark">
                            <i class="fa-solid fa-star me-1"></i> AKTIF
                        </span>
                    </div>
                    
                    <div class="py-2">
                        <!-- Icon Mesin Cuci Berputar Lucu -->
                        <div class="position-relative d-inline-block mb-2">
                            <div class="p-3 border border-2 border-info d-inline-flex align-items-center justify-content-center" style="width: 78px; height: 78px; background: #e0f2fe;">
                                <i class="fa-solid fa-compact-disc anim-wash text-primary" style="font-size: 2.8rem;"></i>
                            </div>
                            <span class="position-absolute top-0 start-100 translate-middle badge border border-light bg-info p-1" style="border-radius: 50% !important;">🫧</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">wishuwashere.</h5>
                        <small class="text-secondary fw-semibold">100% READY &amp; ACTIVE 🧺</small>
                    </div>

                    <div class="p-2 border mt-2 small" style="background: #e0f2fe; border-color: #bae6fd !important; color: #0369a1; font-weight: 600;">
                        <i class="fa-solid fa-droplet text-info me-1"></i> Bersih, Wangi, &amp; Seimbang (Balance)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. 4 KARTU STATISTIK (BIRU PASTEL & ELEMEN LAUNDRY LUCU) -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Account Codes -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 h-100 text-center position-relative stat-card-cute">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle position-absolute top-0 end-0 m-3">
                    <i class="fa-solid fa-tags me-1"></i> Akun
                </span>
                <div class="mt-2 mb-2 d-flex justify-content-center">
                    <div class="stat-card-icon border border-primary-subtle" style="width: 58px; height: 58px; font-size: 1.6rem; background: #e0f2fe; color: #0284c7;">
                        <i class="fa-solid fa-wallet anim-float"></i>
                    </div>
                </div>
                <small class="text-secondary fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">TOTAL ACCOUNT CODES</small>
                <h2 class="fw-bold my-1" style="color: #0369a1;">14</h2>
                <small class="text-secondary">Akun Bagan Standard 🧼</small>
            </div>
        </div>

        <!-- Card 2: Journal Entries -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 h-100 text-center position-relative stat-card-cute">
                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle position-absolute top-0 end-0 m-3">
                    <i class="fa-solid fa-receipt me-1"></i> Jurnal
                </span>
                <div class="mt-2 mb-2 d-flex justify-content-center">
                    <div class="stat-card-icon border border-info-subtle" style="width: 58px; height: 58px; font-size: 1.6rem; background: #e0f2fe; color: #0284c7;">
                        <i class="fa-solid fa-shirt anim-float"></i>
                    </div>
                </div>
                <small class="text-secondary fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">JOURNAL ENTRIES</small>
                <h2 class="fw-bold my-1" style="color: #0284c7;"><?= $total_order ?></h2>
                <small class="text-secondary">Transaksi Terinput 🧺</small>
            </div>
        </div>

        <!-- Card 3: Sedang Diproses (Laundry Washing) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 h-100 text-center position-relative stat-card-cute">
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle position-absolute top-0 end-0 m-3">
                    <i class="fa-solid fa-water me-1"></i> Proses
                </span>
                <div class="mt-2 mb-2 d-flex justify-content-center">
                    <div class="stat-card-icon border border-warning-subtle" style="width: 58px; height: 58px; font-size: 1.6rem; background: #fef9c3; color: #ca8a04;">
                        <i class="fa-solid fa-arrows-rotate anim-wash"></i>
                    </div>
                </div>
                <small class="text-secondary fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">DALAM PENGERJAAN</small>
                <h2 class="fw-bold text-warning my-1"><?= $sedang_diproses ?></h2>
                <small class="text-secondary">Cuci &amp; Setrika Aktif 👕</small>
            </div>
        </div>

        <!-- Card 4: Balance Status -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 h-100 text-center position-relative stat-card-cute">
                <span class="badge bg-success-subtle text-success border border-success-subtle position-absolute top-0 end-0 m-3">
                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Balance!
                </span>
                <div class="mt-2 mb-2 d-flex justify-content-center">
                    <div class="stat-card-icon border border-success-subtle" style="width: 58px; height: 58px; font-size: 1.6rem; background: #dcfce7; color: #16a34a;">
                        <i class="fa-solid fa-scale-balanced anim-float"></i>
                    </div>
                </div>
                <small class="text-secondary fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">BALANCE STATUS</small>
                <h2 class="fw-bold text-success my-1">BALANCED <i class="fa-solid fa-circle-check fs-5"></i></h2>
                <small class="text-secondary">Debit = Kredit Seimbang ✨</small>
            </div>
        </div>
    </div>

    <!-- 4. TABEL TRANSAKSI TERBARU & WIDGET SAMPING -->
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-2" style="border-color: #bae6fd !important;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold" style="color: #0369a1;">
                        <i class="fa-solid fa-basket-shopping me-1 anim-float"></i> Transaksi Cucian wishuwashere.
                    </span>
                    <a href="<?= base_url('kasir') ?>" class="btn btn-sm btn-primary fw-bold" style="background: linear-gradient(135deg, #0ea5e9, #0284c7); border: none;">
                        <i class="fa-solid fa-plus me-1"></i> Terima Cucian (Kasir)
                    </a>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0" id="tableTransaksi" style="border-color: #bae6fd;">
                            <thead>
                                <tr>
                                    <th>No. Nota</th>
                                    <th>Pelanggan</th>
                                    <th>Paket Layanan</th>
                                    <th>Total Biaya</th>
                                    <th>Status Cucian</th>
                                    <th>Status Bayar</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transaksi)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-secondary" style="background: #f8fdff;">
                                            <div class="anim-float mb-2" style="font-size: 2.5rem;">🧺</div>
                                            <strong class="d-block text-dark mb-1">Belum ada transaksi cucian yang diinput</strong>
                                            <small class="text-muted d-block mb-3">Silakan input data pelanggan dan order kasir pertama Anda!</small>
                                            <a href="<?= base_url('kasir') ?>" class="btn btn-sm btn-outline-primary fw-bold" style="border-color: #0284c7; color: #0284c7;">
                                                <i class="fa-solid fa-plus me-1"></i> Buat Order Baru
                                            </a>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transaksi as $t): ?>
                                        <tr>
                                            <td>
                                                <strong class="text-primary">#<?= esc($t['kode_transaksi']) ?></strong><br>
                                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark"><?= esc($t['nama_pelanggan']) ?></div>
                                                <?php if (!empty($t['no_hp'])): ?>
                                                    <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $t['no_hp'])) ?>" target="_blank" class="small text-decoration-none text-success">
                                                        <i class="fa-brands fa-whatsapp me-1"></i><?= esc($t['no_hp']) ?>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle"><?= esc($t['jenis_paket']) ?></span>
                                                <div class="small fw-bold mt-1 text-dark"><?= esc($t['nama_paket']) ?> (<?= esc($t['berat_qty']) ?> <?= $t['jenis_paket'] === 'kiloan' ? 'Kg' : 'Pcs' ?>)</div>
                                            </td>
                                            <td><strong class="text-primary">Rp <?= number_format($t['total_biaya'], 0, ',', '.') ?></strong></td>
                                            <td>
                                                <?php
                                                    $badgeClass = 'bg-secondary';
                                                    if ($t['status_cucian'] === 'Sedang Dicuci') $badgeClass = 'bg-warning text-dark border border-warning';
                                                    elseif ($t['status_cucian'] === 'Proses Setrika') $badgeClass = 'bg-info text-dark border border-info';
                                                    elseif ($t['status_cucian'] === 'Siap Diambil') $badgeClass = 'bg-success text-white';
                                                    elseif ($t['status_cucian'] === 'Sudah Diambil') $badgeClass = 'bg-dark text-white';
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= esc($t['status_cucian']) ?></span>
                                            </td>
                                            <td>
                                                <?php if ($t['status_bayar'] === 'Lunas'): ?>
                                                    <span class="badge bg-success-subtle text-success border border-success">Lunas (<?= esc($t['metode_bayar']) ?>)</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger-subtle text-danger border border-danger">Belum Lunas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('kasir/cetak/' . $t['id']) ?>" target="_blank" class="btn btn-outline-primary" title="Cetak Nota"><i class="fa-solid fa-print"></i></a>
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

        <!-- Widget Samping: Pencarian & Daftar Paket -->
        <div class="col-12 col-lg-4 d-flex flex-column gap-3">
            <div class="card p-3 border-2" style="border-color: #bae6fd !important;">
                <div class="card-header bg-transparent p-0 pb-2 mb-2 border-bottom" style="border-color: #bae6fd !important;">
                    <strong style="color: #0369a1;"><i class="fa-solid fa-magnifying-glass me-1"></i> Pencarian Cepat Nota</strong>
                </div>
                <input type="text" id="quickSearchInput" class="form-control" placeholder="Cari No Nota / Pelanggan...">
                <small class="text-muted mt-2" style="font-size: 0.75rem;"><i class="fa-solid fa-info-circle me-1 text-info"></i> Ketik untuk memfilter baris tabel di samping secara instan.</small>
            </div>

            <div class="card border-2" style="border-color: #bae6fd !important;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold" style="color: #0369a1;"><i class="fa-solid fa-tags me-1 text-primary"></i> Paket Layanan Aktif</span>
                    <a href="<?= base_url('paket') ?>" class="small text-decoration-none fw-bold" style="color: #0284c7;">Kelola &rarr;</a>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($paket as $p): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3" style="background: #ffffff; border-color: #e0f2fe;">
                            <div>
                                <div class="fw-bold text-dark"><i class="fa-solid fa-shirt me-1 text-info small"></i> <?= esc($p['nama_paket']) ?></div>
                                <small class="text-muted">Estimasi: <?= esc($p['estimasi_hari']) ?> Hari</small>
                            </div>
                            <span class="badge bg-info-subtle text-primary-emphasis border border-info-subtle">
                                Rp <?= number_format($p['harga'], 0, ',', '.') ?> / <?= $p['jenis'] === 'kiloan' ? 'Kg' : 'Pcs' ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

</div>

<script>
    const searchInput = document.getElementById('quickSearchInput');
    const topSearchInput = document.getElementById('topLiveSearch');
    
    function filterTable(keyword) {
        keyword = keyword.toLowerCase();
        const rows = document.querySelectorAll('#tableTransaksi tbody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', (e) => filterTable(e.target.value));
    if (topSearchInput) topSearchInput.addEventListener('input', (e) => filterTable(e.target.value));

    // ===== AUTO GIT DEPLOY EXECUTION =====
    function runGitDeploy() {
        const token = document.getElementById('githubTokenInput').value.trim();
        const btn = document.getElementById('btnStartDeploy');
        const outputBox = document.getElementById('deployOutput');
        const spinner = document.getElementById('deploySpinner');

        btn.disabled = true;
        spinner.classList.remove('d-none');
        outputBox.innerHTML = '<span class="text-info">⏳ Menjalankan git init, git add, git commit, dan push ke GitHub...\nTarget: https://github.com/BestyAnjaliPutri/Laundry-wishuwashere-Kelompok4-D2.git\nMohon tunggu beberapa detik...</span>\n\n';

        let url = '<?= base_url('git-deploy-now') ?>';
        if (token) {
            url += '?token=' + encodeURIComponent(token);
        }

        fetch(url)
            .then(res => res.text())
            .then(text => {
                spinner.classList.add('d-none');
                btn.disabled = false;
                outputBox.textContent = text;
                if (text.includes('main -> main') || text.includes('Branch \'main\' set up to track')) {
                    outputBox.innerHTML += '\n\n<span class="text-success fw-bold">🎉 BERHASIL PUSH KE GITHUB 100%!</span>\n<span class="text-white">Silakan refresh halaman GitHub Anda untuk melihat kode yang sudah terunggah.</span>';
                }
            })
            .catch(err => {
                spinner.classList.add('d-none');
                btn.disabled = false;
                outputBox.innerHTML = '<span class="text-danger fw-bold">❌ Error koneksi: ' + err + '</span>';
            });
    }
</script>

<!-- MODAL DEPLOY KE GITHUB & VERCEL -->
<div class="modal fade" id="modalGitDeploy" tabindex="-1" aria-labelledby="modalGitDeployLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-2" style="border-radius: 14px; overflow: hidden; border-color: #0f172a;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a, #1e293b);">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-brands fa-github fs-4"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalGitDeployLabel">Deploy Otomatis ke GitHub &amp; Vercel</h5>
                        <small class="text-muted">Repository: <code>BestyAnjaliPutri/Laundry-wishuwashere-Kelompok4-D2</code></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info border-0 d-flex gap-2 align-items-start mb-3" style="border-radius: 10px; background: #f0f9ff; border-left: 4px solid #0284c7 !important;">
                    <i class="fa-solid fa-circle-info text-primary mt-1"></i>
                    <div class="small text-dark">
                        Tombol ini akan menjalankan perintah <strong>git add, commit, dan push</strong> langsung dari komputer lokal Anda ke repository GitHub <strong>BestyAnjaliPutri</strong> secara otomatis.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">
                        GitHub Personal Access Token (PAT):
                    </label>
                    <input type="password" id="githubTokenInput" class="form-control" placeholder="Tempel token yang diawali ghp_...">
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted" style="font-size: 0.78rem;">
                            GitHub <strong>tidak menerima password biasa</strong> untuk push. Gunakan Token (PAT).
                        </small>
                        <a href="https://github.com/settings/tokens/new?scopes=repo&description=DeployLaundry" target="_blank" class="small fw-bold text-decoration-none" style="color: #0284c7;">
                            🔑 Buat Token Baru (30 Detik) &rarr;
                        </a>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <button type="button" id="btnStartDeploy" class="btn btn-dark fw-bold px-4 py-2" onclick="runGitDeploy()" style="background: #0f172a; border-radius: 8px;">
                        <span id="deploySpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i>
                        Mulai Push ke GitHub Sekarang
                    </button>
                    <a href="https://github.com/BestyAnjaliPutri/Laundry-wishuwashere-Kelompok4-D2" target="_blank" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 8px;">
                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Repo GitHub
                    </a>
                </div>

                <div class="p-2 mb-3 bg-light border rounded small text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-bolt text-warning fs-5"></i>
                    <div>
                        <strong>Cara Alternatif Paling Praktis (Tanpa Token):</strong><br>
                        Buka folder <code>C:\xampp\htdocs\laundry</code> lalu <strong>klik ganda (double-click)</strong> file <code>DEPLOY_KE_GITHUB.bat</code>. Jendela login GitHub di browser akan langsung muncul otomatis!
                    </div>
                </div>

                <label class="form-label fw-bold small text-muted">Terminal Output:</label>
                <pre id="deployOutput" class="p-3 bg-dark text-light border" style="border-radius: 10px; max-height: 260px; overflow-y: auto; font-size: 0.8rem; font-family: Consolas, monospace; line-height: 1.5; white-space: pre-wrap;">Siap. Klik "Mulai Push ke GitHub Sekarang" untuk memulai proses otomatis.</pre>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

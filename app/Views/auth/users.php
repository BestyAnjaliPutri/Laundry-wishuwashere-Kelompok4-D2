<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color:#0369a1;">
                <i class="fa-solid fa-users-gear me-2"></i>Manajemen User
            </h4>
            <small class="text-muted">Kelola akun login pengguna sistem</small>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="fa-solid fa-plus me-1"></i> Tambah User Baru
        </button>
    </div>

    <!-- Table -->
    <div class="card border-2" style="border-color:#bae6fd !important;">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="fa-solid fa-id-card text-primary"></i>
            <strong style="color:#0369a1;">Daftar User Sistem — wishuwashere.</strong>
        </div>
        <div class="p-3">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" style="border-color:#bae6fd; font-size:.88rem;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $i => $u): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><strong><?= esc($u['name']) ?></strong></td>
                            <td><code><?= esc($u['username']) ?></code></td>
                            <td>
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="badge bg-primary">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Operator</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="/users/edit/<?= $u['id'] ?>" class="btn btn-sm" style="background:#f4a261;color:#fff;">
                                    <i class="fa-solid fa-edit me-1"></i>Edit
                                </a>
                                <?php if ($u['id'] != session()->get('user_id')): ?>
                                <a href="/users/hapus/<?= $u['id'] ?>" class="btn btn-sm ms-1" style="background:#e63946;color:#fff;"
                                   onclick="return confirm('Hapus user <?= esc($u['name']) ?>?')">
                                    <i class="fa-solid fa-trash me-1"></i>Hapus
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambahUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-2" style="border-color:#bae6fd;">
            <div class="modal-header" style="background:linear-gradient(135deg,#e0f2fe,#f0f9ff); border-bottom:2px solid #bae6fd;">
                <h5 class="modal-title fw-bold" style="color:#0369a1;">
                    <i class="fa-solid fa-user-plus me-2"></i>Tambah User Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="/users/simpan">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required placeholder="Nama lengkap user">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="Username login">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Password baru">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <select name="role" class="form-select">
                            <option value="operator">Operator</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top" style="border-color:#bae6fd !important;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-1"></i>Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

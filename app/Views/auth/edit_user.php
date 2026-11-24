<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="/users" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
        <h4 class="fw-bold mb-0" style="color:#0369a1;"><i class="fa-solid fa-user-pen me-2"></i>Edit User</h4>
    </div>
    <div class="card border-2" style="border-color:#bae6fd !important; max-width:500px;">
        <div class="card-header" style="background:linear-gradient(135deg,#e0f2fe,#f0f9ff); border-bottom:2px solid #bae6fd;">
            <strong style="color:#0369a1;">Edit: <?= esc($user['name']) ?></strong>
        </div>
        <form method="post" action="/users/update/<?= $user['id'] ?>">
            <div class="p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="<?= esc($user['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" class="form-control" value="<?= esc($user['username']) ?>" disabled>
                    <small class="text-muted">Username tidak dapat diubah.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password Baru <small class="text-muted">(kosongkan jika tidak diganti)</small></label>
                    <input type="password" name="password" class="form-control" placeholder="Password baru...">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Role</label>
                    <select name="role" class="form-select">
                        <option value="operator" <?= $user['role'] === 'operator' ? 'selected' : '' ?>>Operator</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan Perubahan</button>
                    <a href="/users" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

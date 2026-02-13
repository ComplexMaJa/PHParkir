<?php $isEdit = isset($user) && $user; ?>

<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3><?= $isEdit ? 'Edit User' : 'Tambah User' ?></h3>
    <a href="index.php?page=users" class="btn btn-outline-primary">← Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?page=users&action=<?= $isEdit ? 'edit&id=' . $user['id_user'] : 'create' ?>">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap *</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" value="<?= e($isEdit ? $user['nama_lengkap'] : ($_POST['nama_lengkap'] ?? '')) ?>" required maxlength="50">
            </div>
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" class="form-control" value="<?= e($isEdit ? $user['username'] : ($_POST['username'] ?? '')) ?>" required maxlength="50">
            </div>
            <div class="form-group">
                <label for="password">Password <?= $isEdit ? '(kosongkan jika tidak diubah)' : '*' ?></label>
                <input type="password" id="password" name="password" class="form-control" <?= $isEdit ? '' : 'required' ?>>
            </div>
            <div class="form-group">
                <label for="role">Role *</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" <?= ($isEdit && $user['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="petugas" <?= ($isEdit && $user['role'] === 'petugas') ? 'selected' : '' ?>>Petugas</option>
                    <option value="owner" <?= ($isEdit && $user['role'] === 'owner') ? 'selected' : '' ?>>Owner</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status_aktif">Status</label>
                <select id="status_aktif" name="status_aktif" class="form-control">
                    <option value="1" <?= ($isEdit && $user['status_aktif'] == 1) ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= ($isEdit && $user['status_aktif'] == 0) ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah User' ?></button>
        </form>
    </div>
</div>

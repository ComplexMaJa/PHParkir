<?php $isEdit = isset($user) && $user; ?>

<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3><?= $isEdit ? 'Edit User' : 'Tambah User' ?></h3>
    <a href="index.php?page=users" class="btn btn-outline-primary">← Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?page=users&action=<?= $isEdit ? 'edit&id=' . $user['id'] : 'create' ?>">
            <div class="form-group">
                <label for="nama">Nama Lengkap *</label>
                <input type="text" id="nama" name="nama" class="form-control" value="<?= e($isEdit ? $user['nama'] : ($_POST['nama'] ?? '')) ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" class="form-control" value="<?= e($isEdit ? $user['username'] : ($_POST['username'] ?? '')) ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password <?= $isEdit ? '(kosongkan jika tidak diubah)' : '*' ?></label>
                <input type="password" id="password" name="password" class="form-control" <?= $isEdit ? '' : 'required' ?>>
            </div>
            <div class="form-group">
                <label for="role_id">Role *</label>
                <select id="role_id" name="role_id" class="form-control" required>
                    <option value="">-- Pilih Role --</option>
                    <?php foreach ($roles as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= ($isEdit && $user['role_id'] == $r['id']) ? 'selected' : '' ?>><?= e($r['nama_role']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="aktif" <?= ($isEdit && $user['status'] === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= ($isEdit && $user['status'] === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah User' ?></button>
        </form>
    </div>
</div>

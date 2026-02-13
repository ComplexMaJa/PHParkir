<?php $isEdit = isset($kendaraanData) && $kendaraanData; ?>

<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3><?= $isEdit ? 'Edit Kendaraan' : 'Tambah Kendaraan' ?></h3>
    <a href="index.php?page=kendaraan" class="btn btn-outline-primary">← Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?page=kendaraan&action=<?= $isEdit ? 'edit&id=' . $kendaraanData['id_kendaraan'] : 'create' ?>">
            <div class="form-group">
                <label for="plat_nomor">Plat Nomor *</label>
                <input type="text" id="plat_nomor" name="plat_nomor" class="form-control" value="<?= e($isEdit ? $kendaraanData['plat_nomor'] : ($_POST['plat_nomor'] ?? '')) ?>" required style="text-transform:uppercase;" maxlength="15">
            </div>
            <div class="form-group">
                <label for="jenis_kendaraan">Jenis Kendaraan *</label>
                <input type="text" id="jenis_kendaraan" name="jenis_kendaraan" class="form-control" value="<?= e($isEdit ? $kendaraanData['jenis_kendaraan'] : ($_POST['jenis_kendaraan'] ?? '')) ?>" required maxlength="20">
            </div>
            <div class="form-group">
                <label for="warna">Warna *</label>
                <input type="text" id="warna" name="warna" class="form-control" value="<?= e($isEdit ? $kendaraanData['warna'] : ($_POST['warna'] ?? '')) ?>" required maxlength="20">
            </div>
            <div class="form-group">
                <label for="pemilik">Pemilik *</label>
                <input type="text" id="pemilik" name="pemilik" class="form-control" value="<?= e($isEdit ? $kendaraanData['pemilik'] : ($_POST['pemilik'] ?? '')) ?>" required maxlength="100">
            </div>
            <div class="form-group">
                <label for="id_user">User *</label>
                <select id="id_user" name="id_user" class="form-control" required>
                    <option value="">-- Pilih User --</option>
                    <?php foreach ($userList as $u): ?>
                    <option value="<?= $u['id_user'] ?>" <?= ($isEdit && $kendaraanData['id_user'] == $u['id_user']) ? 'selected' : '' ?>><?= e($u['nama_lengkap']) ?> (<?= e($u['username']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Kendaraan' ?></button>
        </form>
    </div>
</div>

<?php $isEdit = isset($kendaraanData) && $kendaraanData; ?>

<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3><?= $isEdit ? 'Edit Kendaraan' : 'Tambah Kendaraan' ?></h3>
    <a href="index.php?page=kendaraan" class="btn btn-outline-primary">← Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?page=kendaraan&action=<?= $isEdit ? 'edit&id=' . $kendaraanData['id'] : 'create' ?>">
            <div class="form-group">
                <label for="jenis_kendaraan">Jenis Kendaraan *</label>
                <input type="text" id="jenis_kendaraan" name="jenis_kendaraan" class="form-control" value="<?= e($isEdit ? $kendaraanData['jenis_kendaraan'] : ($_POST['jenis_kendaraan'] ?? '')) ?>" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"><?= e($isEdit ? $kendaraanData['deskripsi'] : ($_POST['deskripsi'] ?? '')) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Kendaraan' ?></button>
        </form>
    </div>
</div>

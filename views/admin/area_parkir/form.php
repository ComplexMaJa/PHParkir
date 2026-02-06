<?php $isEdit = isset($areaData) && $areaData; ?>

<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3><?= $isEdit ? 'Edit Area Parkir' : 'Tambah Area Parkir' ?></h3>
    <a href="index.php?page=area_parkir" class="btn btn-outline-primary">← Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?page=area_parkir&action=<?= $isEdit ? 'edit&id=' . $areaData['id'] : 'create' ?>">
            <div class="form-group">
                <label for="nama_area">Nama Area *</label>
                <input type="text" id="nama_area" name="nama_area" class="form-control" value="<?= e($isEdit ? $areaData['nama_area'] : ($_POST['nama_area'] ?? '')) ?>" required>
            </div>
            <div class="form-group">
                <label for="kapasitas">Kapasitas *</label>
                <input type="number" id="kapasitas" name="kapasitas" class="form-control" min="1" value="<?= e($isEdit ? $areaData['kapasitas'] : ($_POST['kapasitas'] ?? '')) ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="aktif" <?= ($isEdit && $areaData['status'] === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= ($isEdit && $areaData['status'] === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Area' ?></button>
        </form>
    </div>
</div>

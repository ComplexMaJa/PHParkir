<?php $isEdit = isset($tarifData) && $tarifData; ?>

<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3><?= $isEdit ? 'Edit Tarif Parkir' : 'Tambah Tarif Parkir' ?></h3>
    <a href="index.php?page=tarif_parkir" class="btn btn-outline-primary">← Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?page=tarif_parkir&action=<?= $isEdit ? 'edit&id=' . $tarifData['id'] : 'create' ?>">
            <div class="form-group">
                <label for="kendaraan_id">Jenis Kendaraan *</label>
                <select id="kendaraan_id" name="kendaraan_id" class="form-control" required>
                    <option value="">-- Pilih Kendaraan --</option>
                    <?php foreach ($kendaraanList as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= ($isEdit && $tarifData['kendaraan_id'] == $k['id']) ? 'selected' : '' ?>><?= e($k['jenis_kendaraan']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tarif_per_jam">Tarif Per Jam (Rp) *</label>
                <input type="number" id="tarif_per_jam" name="tarif_per_jam" class="form-control" min="0" step="500" value="<?= e($isEdit ? $tarifData['tarif_per_jam'] : ($_POST['tarif_per_jam'] ?? '')) ?>" required>
            </div>
            <div class="form-group">
                <label for="tarif_flat">Tarif Flat / Awal (Rp)</label>
                <input type="number" id="tarif_flat" name="tarif_flat" class="form-control" min="0" step="500" value="<?= e($isEdit ? $tarifData['tarif_flat'] : ($_POST['tarif_flat'] ?? '0')) ?>">
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"><?= e($isEdit ? $tarifData['deskripsi'] : ($_POST['deskripsi'] ?? '')) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Tarif' ?></button>
        </form>
    </div>
</div>

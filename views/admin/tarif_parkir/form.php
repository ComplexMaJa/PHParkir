<?php $isEdit = isset($tarifData) && $tarifData; ?>

<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3><?= $isEdit ? 'Edit Tarif Parkir' : 'Tambah Tarif Parkir' ?></h3>
    <a href="index.php?page=tarif_parkir" class="btn btn-outline-primary">← Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?page=tarif_parkir&action=<?= $isEdit ? 'edit&id=' . $tarifData['id_tarif'] : 'create' ?>">
            <div class="form-group">
                <label for="jenis_kendaraan">Jenis Kendaraan *</label>
                <select id="jenis_kendaraan" name="jenis_kendaraan" class="form-control" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="motor" <?= ($isEdit && $tarifData['jenis_kendaraan'] === 'motor') ? 'selected' : '' ?>>Motor</option>
                    <option value="mobil" <?= ($isEdit && $tarifData['jenis_kendaraan'] === 'mobil') ? 'selected' : '' ?>>Mobil</option>
                    <option value="lainnya" <?= ($isEdit && $tarifData['jenis_kendaraan'] === 'lainnya') ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tarif_per_jam">Tarif Per Jam (Rp) *</label>
                <input type="number" id="tarif_per_jam" name="tarif_per_jam" class="form-control" min="0" step="500" value="<?= e($isEdit ? $tarifData['tarif_per_jam'] : ($_POST['tarif_per_jam'] ?? '')) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Tarif' ?></button>
        </form>
    </div>
</div>

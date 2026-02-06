<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3>🟢 Input Kendaraan Masuk</h3>
    <a href="index.php?page=transaksi" class="btn btn-outline-primary">← Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?page=transaksi&action=masuk">
            <div class="form-group">
                <label for="plat_nomor">Plat Nomor *</label>
                <input type="text" id="plat_nomor" name="plat_nomor" class="form-control" placeholder="Contoh: B 1234 ABC" value="<?= e($_POST['plat_nomor'] ?? '') ?>" required style="text-transform:uppercase;">
            </div>
            <div class="form-group">
                <label for="kendaraan_id">Jenis Kendaraan *</label>
                <select id="kendaraan_id" name="kendaraan_id" class="form-control" required>
                    <option value="">-- Pilih Kendaraan --</option>
                    <?php foreach ($kendaraanList as $k): ?>
                    <option value="<?= $k['id'] ?>"><?= e($k['jenis_kendaraan']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="area_parkir_id">Area Parkir *</label>
                <select id="area_parkir_id" name="area_parkir_id" class="form-control" required>
                    <option value="">-- Pilih Area --</option>
                    <?php foreach ($areaList as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= $a['terisi'] >= $a['kapasitas'] ? 'disabled' : '' ?>>
                        <?= e($a['nama_area']) ?> (Sisa: <?= max(0, $a['kapasitas'] - $a['terisi']) ?>/<?= $a['kapasitas'] ?>)
                        <?= $a['terisi'] >= $a['kapasitas'] ? '- PENUH' : '' ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success" style="padding:12px 32px;">🟢 Proses Masuk</button>
        </form>
    </div>
</div>

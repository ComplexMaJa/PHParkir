<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3>🟢 Input Kendaraan Masuk</h3>
    <a href="index.php?page=transaksi" class="btn btn-outline-primary">← Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?page=transaksi&action=masuk">
            <div class="form-group">
                <label for="id_kendaraan">Kendaraan *</label>
                <select id="id_kendaraan" name="id_kendaraan" class="form-control" required>
                    <option value="">-- Pilih Kendaraan --</option>
                    <?php foreach ($kendaraanList as $k): ?>
                    <option value="<?= $k['id_kendaraan'] ?>"><?= e($k['plat_nomor']) ?> - <?= e($k['jenis_kendaraan']) ?> (<?= e($k['warna']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_tarif">Tarif *</label>
                <select id="id_tarif" name="id_tarif" class="form-control" required>
                    <option value="">-- Pilih Tarif --</option>
                    <?php foreach ($tarifList as $t): ?>
                    <option value="<?= $t['id_tarif'] ?>"><?= e(ucfirst($t['jenis_kendaraan'])) ?> - <?= formatRupiah($t['tarif_per_jam']) ?>/jam</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_area">Area Parkir *</label>
                <select id="id_area" name="id_area" class="form-control" required>
                    <option value="">-- Pilih Area --</option>
                    <?php foreach ($areaList as $a): ?>
                    <option value="<?= $a['id_area'] ?>" <?= $a['terisi'] >= $a['kapasitas'] ? 'disabled' : '' ?>>
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

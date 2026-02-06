<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3>🔴 Proses Kendaraan Keluar</h3>
    <a href="index.php?page=transaksi" class="btn btn-outline-primary">← Kembali</a>
</div>

<!-- Search form -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="POST" action="index.php?page=transaksi&action=keluar">
            <div style="display:flex;gap:12px;align-items:end;">
                <div class="form-group" style="flex:1;margin-bottom:0;">
                    <label for="plat_nomor">Cari Plat Nomor</label>
                    <input type="text" id="plat_nomor" name="plat_nomor" class="form-control" placeholder="Masukkan plat nomor..." value="<?= e($_POST['plat_nomor'] ?? '') ?>" required style="text-transform:uppercase;">
                </div>
                <button type="submit" name="cari_plat" value="1" class="btn btn-primary" style="height:42px;">🔍 Cari</button>
            </div>
        </form>
    </div>
</div>

<?php if (isset($transaksiData) && $transaksiData): ?>
<div class="card">
    <div class="card-header">
        <h3>Data Kendaraan Ditemukan</h3>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
            <div>
                <p style="margin:4px 0;color:var(--gray-500);font-size:13px;">Kode Transaksi</p>
                <p style="margin:0;font-weight:600;"><code><?= e($transaksiData['kode_transaksi']) ?></code></p>
            </div>
            <div>
                <p style="margin:4px 0;color:var(--gray-500);font-size:13px;">Plat Nomor</p>
                <p style="margin:0;font-weight:600;"><?= e($transaksiData['plat_nomor']) ?></p>
            </div>
            <div>
                <p style="margin:4px 0;color:var(--gray-500);font-size:13px;">Jenis Kendaraan</p>
                <p style="margin:0;"><?= e($transaksiData['jenis_kendaraan']) ?></p>
            </div>
            <div>
                <p style="margin:4px 0;color:var(--gray-500);font-size:13px;">Area Parkir</p>
                <p style="margin:0;"><?= e($transaksiData['nama_area']) ?></p>
            </div>
            <div>
                <p style="margin:4px 0;color:var(--gray-500);font-size:13px;">Waktu Masuk</p>
                <p style="margin:0;font-weight:600;color:var(--primary);"><?= formatTanggal($transaksiData['waktu_masuk']) ?></p>
            </div>
            <div>
                <p style="margin:4px 0;color:var(--gray-500);font-size:13px;">Durasi (saat ini)</p>
                <?php
                    $durasi = time() - strtotime($transaksiData['waktu_masuk']);
                    $jam = floor($durasi / 3600);
                    $menit = floor(($durasi % 3600) / 60);
                ?>
                <p style="margin:0;font-weight:600;color:var(--danger);"><?= $jam ?> jam <?= $menit ?> menit</p>
            </div>
        </div>
        <form method="POST" action="index.php?page=transaksi&action=keluar">
            <input type="hidden" name="transaksi_id" value="<?= $transaksiData['id'] ?>">
            <button type="submit" name="proses_keluar" value="1" class="btn btn-danger" style="padding:12px 32px;" onclick="return confirm('Proses kendaraan keluar?')">🔴 Proses Keluar & Hitung Biaya</button>
        </form>
    </div>
</div>
<?php endif; ?>

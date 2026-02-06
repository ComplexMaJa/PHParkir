<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Parkir - <?= e($transaksiData['kode_transaksi']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background:var(--gray-100);padding:24px;">

<div style="text-align:center;margin-bottom:20px;" class="no-print">
    <a href="index.php?page=transaksi" class="btn btn-outline-primary">← Kembali ke Transaksi</a>
    <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Struk</button>
</div>

<div class="struk-container">
    <div class="struk-header">
        <h3>🅿️ PHParkir</h3>
        <p>Sistem Manajemen Parkir</p>
        <p style="font-size:11px;">Jl. Contoh Alamat No. 123, Kota</p>
    </div>

    <div class="struk-body">
        <div class="struk-row">
            <span>Kode Transaksi</span>
            <strong><?= e($transaksiData['kode_transaksi']) ?></strong>
        </div>
        <div class="struk-row">
            <span>Plat Nomor</span>
            <strong><?= e($transaksiData['plat_nomor']) ?></strong>
        </div>
        <div class="struk-row">
            <span>Jenis Kendaraan</span>
            <span><?= e($transaksiData['jenis_kendaraan']) ?></span>
        </div>
        <div class="struk-row">
            <span>Area Parkir</span>
            <span><?= e($transaksiData['nama_area']) ?></span>
        </div>
        <div class="struk-row">
            <span>Petugas</span>
            <span><?= e($transaksiData['petugas_nama']) ?></span>
        </div>

        <div style="border-top:1px dashed var(--gray-300);margin:12px 0;"></div>

        <div class="struk-row">
            <span>Waktu Masuk</span>
            <span><?= formatTanggal($transaksiData['waktu_masuk']) ?></span>
        </div>
        <div class="struk-row">
            <span>Waktu Keluar</span>
            <span><?= formatTanggal($transaksiData['waktu_keluar']) ?></span>
        </div>

        <?php if ($detailData): ?>
        <div class="struk-row">
            <span>Durasi</span>
            <span><?= $detailData['durasi_jam'] ?> jam</span>
        </div>

        <div style="border-top:1px dashed var(--gray-300);margin:12px 0;"></div>

        <div class="struk-row">
            <span>Tarif Flat</span>
            <span><?= formatRupiah($detailData['tarif_flat']) ?></span>
        </div>
        <div class="struk-row">
            <span>Tarif/Jam × <?= $detailData['durasi_jam'] ?></span>
            <span><?= formatRupiah($detailData['tarif_per_jam'] * $detailData['durasi_jam']) ?></span>
        </div>

        <div class="struk-row total">
            <span>TOTAL</span>
            <span><?= formatRupiah($detailData['total_biaya']) ?></span>
        </div>
        <?php else: ?>
        <div class="struk-row">
            <span>Status</span>
            <span class="badge badge-warning">Masih Parkir</span>
        </div>
        <?php endif; ?>
    </div>

    <div class="struk-footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Dicetak: <?= date('d/m/Y H:i:s') ?></p>
    </div>
</div>

</body>
</html>

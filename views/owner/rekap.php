<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3>📈 Rekap Transaksi & Pendapatan</h3>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="rekap">
            <div class="filter-bar">
                <div class="form-group">
                    <label for="start_date">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="<?= e($startDate) ?>">
                </div>
                <div class="form-group">
                    <label for="end_date">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="<?= e($endDate) ?>">
                </div>
                <button type="submit" class="btn btn-primary" style="height:42px;">🔍 Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon purple">💵</div>
        <div class="stat-info">
            <h4><?= formatRupiah($totalPendapatan) ?></h4>
            <p>Total Pendapatan (<?= date('d/m', strtotime($startDate)) ?> - <?= date('d/m/Y', strtotime($endDate)) ?>)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">📋</div>
        <div class="stat-info">
            <h4><?= number_format(count($rekap)) ?></h4>
            <p>Jumlah Transaksi Selesai</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">💰</div>
        <div class="stat-info">
            <h4><?= formatRupiah($stats['total_pendapatan']) ?></h4>
            <p>Total Pendapatan Keseluruhan</p>
        </div>
    </div>
</div>

<!-- Daily Summary -->
<?php if (!empty($pendapatanPerHari)): ?>
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <h3>Ringkasan Per Hari</h3>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jumlah Transaksi</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendapatanPerHari as $ph): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($ph['tanggal'])) ?></td>
                        <td><?= number_format($ph['jumlah_transaksi']) ?></td>
                        <td><strong><?= formatRupiah($ph['total_pendapatan']) ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Transaction Detail -->
<div class="card">
    <div class="card-header">
        <h3>Detail Transaksi</h3>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Plat Nomor</th>
                        <th>Kendaraan</th>
                        <th>Area</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Durasi</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rekap)): ?>
                    <tr><td colspan="9" style="text-align:center;padding:32px;color:var(--gray-500);">Tidak ada transaksi pada periode ini.</td></tr>
                    <?php else: ?>
                    <?php foreach ($rekap as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><code>#<?= $r['id_transaksi'] ?></code></td>
                        <td><strong><?= e($r['plat_nomor']) ?></strong></td>
                        <td><?= e($r['jenis_kendaraan']) ?></td>
                        <td><?= e($r['nama_area']) ?></td>
                        <td><?= formatTanggal($r['waktu_masuk']) ?></td>
                        <td><?= formatTanggal($r['waktu_keluar']) ?></td>
                        <td><?= $r['durasi_jam'] ? $r['durasi_jam'] . ' jam' : '-' ?></td>
                        <td><strong><?= $r['biaya_total'] ? formatRupiah($r['biaya_total']) : '-' ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

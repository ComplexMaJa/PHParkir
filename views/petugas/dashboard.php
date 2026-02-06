<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon green">🚗</div>
        <div class="stat-info">
            <h4><?= number_format($stats['kendaraan_terparkir']) ?></h4>
            <p>Kendaraan Terparkir</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">📋</div>
        <div class="stat-info">
            <h4><?= number_format($stats['transaksi_hari_ini']) ?></h4>
            <p>Transaksi Hari Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">💰</div>
        <div class="stat-info">
            <h4><?= formatRupiah($stats['pendapatan_hari_ini']) ?></h4>
            <p>Pendapatan Hari Ini</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Selamat Datang, Petugas!</h3>
    </div>
    <div class="card-body">
        <p>Gunakan menu di sidebar untuk mengelola transaksi parkir. Catat kendaraan masuk dan keluar, serta cetak struk parkir.</p>
        <div style="display:flex;gap:12px;margin-top:16px;flex-wrap:wrap;">
            <a href="index.php?page=transaksi&action=masuk" class="btn btn-success">🟢 Kendaraan Masuk</a>
            <a href="index.php?page=transaksi&action=keluar" class="btn btn-danger">🔴 Kendaraan Keluar</a>
            <a href="index.php?page=transaksi" class="btn btn-outline-primary">📋 Daftar Transaksi</a>
        </div>
    </div>
</div>

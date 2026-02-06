<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon purple">📊</div>
        <div class="stat-info">
            <h4><?= number_format($stats['total_transaksi']) ?></h4>
            <p>Total Transaksi</p>
        </div>
    </div>
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
        <h3>Selamat Datang, Admin!</h3>
    </div>
    <div class="card-body">
        <p>Kelola sistem parkir melalui menu di sidebar. Anda memiliki akses penuh untuk mengelola user, kendaraan, area parkir, tarif, dan melihat activity log.</p>
        <div style="display:flex;gap:12px;margin-top:16px;flex-wrap:wrap;">
            <a href="index.php?page=users" class="btn btn-primary">👥 Kelola User</a>
            <a href="index.php?page=kendaraan" class="btn btn-outline-primary">🚗 Kendaraan</a>
            <a href="index.php?page=area_parkir" class="btn btn-outline-primary">🅿️ Area Parkir</a>
            <a href="index.php?page=tarif_parkir" class="btn btn-outline-primary">💰 Tarif Parkir</a>
        </div>
    </div>
</div>

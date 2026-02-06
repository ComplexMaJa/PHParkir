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
        <div class="stat-icon yellow">💰</div>
        <div class="stat-info">
            <h4><?= formatRupiah($stats['pendapatan_hari_ini']) ?></h4>
            <p>Pendapatan Hari Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">💵</div>
        <div class="stat-info">
            <h4><?= formatRupiah($stats['total_pendapatan']) ?></h4>
            <p>Total Pendapatan</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Selamat Datang, Owner!</h3>
    </div>
    <div class="card-body">
        <p>Lihat rekap transaksi dan ringkasan pendapatan parkir melalui menu Rekap Transaksi di sidebar.</p>
        <div style="display:flex;gap:12px;margin-top:16px;">
            <a href="index.php?page=rekap" class="btn btn-primary">📈 Lihat Rekap</a>
        </div>
    </div>
</div>

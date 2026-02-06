<?php $filterStatus = $_GET['status'] ?? ''; ?>

<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3>Daftar Transaksi</h3>
    <div style="display:flex;gap:8px;">
        <a href="index.php?page=transaksi&action=masuk" class="btn btn-success">🟢 Kendaraan Masuk</a>
        <a href="index.php?page=transaksi&action=keluar" class="btn btn-danger">🔴 Kendaraan Keluar</a>
    </div>
</div>

<div class="filter-bar">
    <a href="index.php?page=transaksi" class="btn <?= $filterStatus === '' ? 'btn-primary' : 'btn-outline-primary' ?> btn-sm">Semua</a>
    <a href="index.php?page=transaksi&status=masuk" class="btn <?= $filterStatus === 'masuk' ? 'btn-primary' : 'btn-outline-primary' ?> btn-sm">Masuk</a>
    <a href="index.php?page=transaksi&status=keluar" class="btn <?= $filterStatus === 'keluar' ? 'btn-primary' : 'btn-outline-primary' ?> btn-sm">Keluar</a>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Plat Nomor</th>
                        <th>Kendaraan</th>
                        <th>Area</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transaksi)): ?>
                    <tr><td colspan="9" style="text-align:center;padding:32px;color:var(--gray-500);">Belum ada transaksi.</td></tr>
                    <?php else: ?>
                    <?php foreach ($transaksi as $i => $t): ?>
                    <tr>
                        <td><?= $pagination['offset'] + $i + 1 ?></td>
                        <td><code><?= e($t['kode_transaksi']) ?></code></td>
                        <td><strong><?= e($t['plat_nomor']) ?></strong></td>
                        <td><?= e($t['jenis_kendaraan']) ?></td>
                        <td><?= e($t['nama_area']) ?></td>
                        <td><?= formatTanggal($t['waktu_masuk']) ?></td>
                        <td><?= $t['waktu_keluar'] ? formatTanggal($t['waktu_keluar']) : '-' ?></td>
                        <td>
                            <?php if ($t['status'] === 'masuk'): ?>
                                <span class="badge badge-warning">Masuk</span>
                            <?php else: ?>
                                <span class="badge badge-success">Keluar</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($t['status'] === 'keluar'): ?>
                                <a href="index.php?page=transaksi&action=struk&id=<?= $t['id'] ?>" class="btn btn-info btn-sm">🧾 Struk</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= renderPagination($pagination, 'index.php?page=transaksi' . ($filterStatus ? '&status=' . $filterStatus : '')) ?>

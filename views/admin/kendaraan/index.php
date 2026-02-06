<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3>Data Kendaraan</h3>
    <a href="index.php?page=kendaraan&action=create" class="btn btn-primary">+ Tambah Kendaraan</a>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Kendaraan</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($kendaraan)): ?>
                    <tr><td colspan="4" style="text-align:center;padding:32px;color:var(--gray-500);">Belum ada data kendaraan.</td></tr>
                    <?php else: ?>
                    <?php foreach ($kendaraan as $i => $k): ?>
                    <tr>
                        <td><?= $pagination['offset'] + $i + 1 ?></td>
                        <td><strong><?= e($k['jenis_kendaraan']) ?></strong></td>
                        <td><?= e($k['deskripsi']) ?></td>
                        <td>
                            <a href="index.php?page=kendaraan&action=edit&id=<?= $k['id'] ?>" class="btn btn-info btn-sm">Edit</a>
                            <a href="index.php?page=kendaraan&action=delete&id=<?= $k['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= renderPagination($pagination, 'index.php?page=kendaraan') ?>

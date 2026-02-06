<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3>Area Parkir</h3>
    <a href="index.php?page=area_parkir&action=create" class="btn btn-primary">+ Tambah Area</a>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Area</th>
                        <th>Kapasitas</th>
                        <th>Terisi</th>
                        <th>Sisa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($areas)): ?>
                    <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--gray-500);">Belum ada data area parkir.</td></tr>
                    <?php else: ?>
                    <?php foreach ($areas as $i => $a): ?>
                    <tr>
                        <td><?= $pagination['offset'] + $i + 1 ?></td>
                        <td><strong><?= e($a['nama_area']) ?></strong></td>
                        <td><?= $a['kapasitas'] ?></td>
                        <td><?= $a['terisi'] ?></td>
                        <td><?= max(0, $a['kapasitas'] - $a['terisi']) ?></td>
                        <td>
                            <?php if ($a['status'] === 'aktif'): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?page=area_parkir&action=edit&id=<?= $a['id'] ?>" class="btn btn-info btn-sm">Edit</a>
                            <a href="index.php?page=area_parkir&action=delete&id=<?= $a['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus area ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= renderPagination($pagination, 'index.php?page=area_parkir') ?>

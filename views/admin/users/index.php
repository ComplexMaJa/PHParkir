<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3>Kelola User</h3>
    <a href="index.php?page=users&action=create" class="btn btn-primary">+ Tambah User</a>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray-500);">Belum ada data user.</td></tr>
                    <?php else: ?>
                    <?php foreach ($users as $i => $u): ?>
                    <tr>
                        <td><?= $pagination['offset'] + $i + 1 ?></td>
                        <td><?= e($u['nama']) ?></td>
                        <td><strong><?= e($u['username']) ?></strong></td>
                        <td><span class="badge badge-purple"><?= e($u['nama_role']) ?></span></td>
                        <td>
                            <?php if ($u['status'] === 'aktif'): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?page=users&action=edit&id=<?= $u['id'] ?>" class="btn btn-info btn-sm">Edit</a>
                            <?php if ($u['id'] != getUserId()): ?>
                            <a href="index.php?page=users&action=delete&id=<?= $u['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
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

<?= renderPagination($pagination, 'index.php?page=users') ?>

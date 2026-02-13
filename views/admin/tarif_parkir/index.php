<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3>Tarif Parkir</h3>
    <a href="index.php?page=tarif_parkir&action=create" class="btn btn-primary">+ Tambah Tarif</a>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Kendaraan</th>
                        <th>Tarif/Jam</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tarifs)): ?>
                    <tr><td colspan="4" style="text-align:center;padding:32px;color:var(--gray-500);">Belum ada data tarif.</td></tr>
                    <?php else: ?>
                    <?php foreach ($tarifs as $i => $t): ?>
                    <tr>
                        <td><?= $pagination['offset'] + $i + 1 ?></td>
                        <td><strong><?= e(ucfirst($t['jenis_kendaraan'])) ?></strong></td>
                        <td><?= formatRupiah($t['tarif_per_jam']) ?></td>
                        <td>
                            <a href="index.php?page=tarif_parkir&action=edit&id=<?= $t['id_tarif'] ?>" class="btn btn-info btn-sm">Edit</a>
                            <a href="index.php?page=tarif_parkir&action=delete&id=<?= $t['id_tarif'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus tarif ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= renderPagination($pagination, 'index.php?page=tarif_parkir') ?>

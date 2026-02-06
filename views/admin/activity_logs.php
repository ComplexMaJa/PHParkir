<div class="card-header" style="border:none;padding:0;margin-bottom:20px;">
    <h3>Activity Log</h3>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Detail</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray-500);">Belum ada activity log.</td></tr>
                    <?php else: ?>
                    <?php foreach ($logs as $i => $log): ?>
                    <tr>
                        <td><?= $pagination['offset'] + $i + 1 ?></td>
                        <td><?= formatTanggal($log['created_at']) ?></td>
                        <td><strong><?= e($log['user_nama'] ?? 'System') ?></strong></td>
                        <td><span class="badge badge-purple"><?= e($log['aktivitas']) ?></span></td>
                        <td><?= e($log['detail']) ?></td>
                        <td><code><?= e($log['ip_address']) ?></code></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= renderPagination($pagination, 'index.php?page=activity_logs') ?>

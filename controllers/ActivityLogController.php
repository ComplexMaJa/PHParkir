<?php
/**
 * Activity Log Controller (Admin)
 */

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/ActivityLogModel.php';

function handleActivityLogs() {
    requireRole(['Admin']);
    $model = new ActivityLogModel();
    $page = max(1, (int)($_GET['p'] ?? 1));
    $pagination = paginate($model->count(), 20, $page);
    $logs = $model->getAll($pagination['per_page'], $pagination['offset']);

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/activity_logs.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

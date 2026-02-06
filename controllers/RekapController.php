<?php
/**
 * Rekap Controller (Owner)
 */

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/TransaksiModel.php';

function handleRekap() {
    requireRole(['Owner']);
    $model = new TransaksiModel();

    $startDate = $_GET['start_date'] ?? date('Y-m-01');
    $endDate = $_GET['end_date'] ?? date('Y-m-d');

    $rekap = $model->getRekapByDateRange($startDate, $endDate);
    $totalPendapatan = $model->getTotalPendapatan($startDate, $endDate);
    $pendapatanPerHari = $model->getPendapatanPerHari($startDate, $endDate);
    $stats = $model->getDashboardStats();

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/owner/rekap.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

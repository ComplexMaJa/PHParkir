<?php
/**
 * Dashboard Controller
 */

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/TransaksiModel.php';

function handleDashboard() {
    requireLogin();

    $transaksiModel = new TransaksiModel();
    $stats = $transaksiModel->getDashboardStats();

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';

    $role = getUserRole();
    if ($role === 'Admin') {
        require __DIR__ . '/../views/admin/dashboard.php';
    } elseif ($role === 'Petugas') {
        require __DIR__ . '/../views/petugas/dashboard.php';
    } elseif ($role === 'Owner') {
        require __DIR__ . '/../views/owner/dashboard.php';
    }

    require __DIR__ . '/../views/layouts/footer.php';
}

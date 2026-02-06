<?php
/**
 * PHParkir - Parking Management System
 * Main Router / Entry Point
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/auth.php';
require_once __DIR__ . '/helpers/functions.php';

$page = $_GET['page'] ?? '';

// Redirect to login if not authenticated and not on login page
if (!isLoggedIn() && $page !== 'login') {
    header('Location: index.php?page=login');
    exit;
}

// Redirect to dashboard if already logged in and on login page
if (isLoggedIn() && ($page === 'login' || $page === '')) {
    header('Location: index.php?page=dashboard');
    exit;
}

// Route to controllers
switch ($page) {
    case 'login':
        require_once __DIR__ . '/controllers/AuthController.php';
        handleLogin();
        break;

    case 'logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        handleLogout();
        break;

    case 'dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        handleDashboard();
        break;

    // Admin routes
    case 'users':
        require_once __DIR__ . '/controllers/UserController.php';
        handleUsers();
        break;

    case 'kendaraan':
        require_once __DIR__ . '/controllers/KendaraanController.php';
        handleKendaraan();
        break;

    case 'area_parkir':
        require_once __DIR__ . '/controllers/AreaParkirController.php';
        handleAreaParkir();
        break;

    case 'tarif_parkir':
        require_once __DIR__ . '/controllers/TarifParkirController.php';
        handleTarifParkir();
        break;

    case 'activity_logs':
        require_once __DIR__ . '/controllers/ActivityLogController.php';
        handleActivityLogs();
        break;

    // Petugas routes
    case 'transaksi':
        require_once __DIR__ . '/controllers/TransaksiController.php';
        handleTransaksi();
        break;

    // Owner routes
    case 'rekap':
        require_once __DIR__ . '/controllers/RekapController.php';
        handleRekap();
        break;

    default:
        header('Location: index.php?page=dashboard');
        break;
}

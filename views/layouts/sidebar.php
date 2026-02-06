<?php
$currentPage = $_GET['page'] ?? 'dashboard';
$role = getUserRole();
?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        🅿️ <span>PHP</span>arkir
    </div>
    <nav class="sidebar-nav">
        <div class="sidebar-label">Menu</div>
        <a href="index.php?page=dashboard" class="sidebar-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
            <span class="icon">📊</span> Dashboard
        </a>

        <?php if ($role === 'Admin'): ?>
        <div class="sidebar-label">Manajemen</div>
        <a href="index.php?page=users" class="sidebar-link <?= $currentPage === 'users' ? 'active' : '' ?>">
            <span class="icon">👥</span> Kelola User
        </a>
        <a href="index.php?page=kendaraan" class="sidebar-link <?= $currentPage === 'kendaraan' ? 'active' : '' ?>">
            <span class="icon">🚗</span> Kendaraan
        </a>
        <a href="index.php?page=area_parkir" class="sidebar-link <?= $currentPage === 'area_parkir' ? 'active' : '' ?>">
            <span class="icon">🅿️</span> Area Parkir
        </a>
        <a href="index.php?page=tarif_parkir" class="sidebar-link <?= $currentPage === 'tarif_parkir' ? 'active' : '' ?>">
            <span class="icon">💰</span> Tarif Parkir
        </a>
        <div class="sidebar-label">Monitoring</div>
        <a href="index.php?page=activity_logs" class="sidebar-link <?= $currentPage === 'activity_logs' ? 'active' : '' ?>">
            <span class="icon">📋</span> Activity Log
        </a>
        <?php endif; ?>

        <?php if ($role === 'Petugas'): ?>
        <div class="sidebar-label">Transaksi</div>
        <a href="index.php?page=transaksi" class="sidebar-link <?= $currentPage === 'transaksi' && ($_GET['action'] ?? '') !== 'masuk' && ($_GET['action'] ?? '') !== 'keluar' ? 'active' : '' ?>">
            <span class="icon">📋</span> Daftar Transaksi
        </a>
        <a href="index.php?page=transaksi&action=masuk" class="sidebar-link <?= ($currentPage === 'transaksi' && ($_GET['action'] ?? '') === 'masuk') ? 'active' : '' ?>">
            <span class="icon">🟢</span> Kendaraan Masuk
        </a>
        <a href="index.php?page=transaksi&action=keluar" class="sidebar-link <?= ($currentPage === 'transaksi' && ($_GET['action'] ?? '') === 'keluar') ? 'active' : '' ?>">
            <span class="icon">🔴</span> Kendaraan Keluar
        </a>
        <?php endif; ?>

        <?php if ($role === 'Owner'): ?>
        <div class="sidebar-label">Laporan</div>
        <a href="index.php?page=rekap" class="sidebar-link <?= $currentPage === 'rekap' ? 'active' : '' ?>">
            <span class="icon">📈</span> Rekap Transaksi
        </a>
        <?php endif; ?>
    </nav>
</div>

<div class="main-content">
    <div class="top-navbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
            <span class="page-title"><?= e(ucfirst(str_replace('_', ' ', $currentPage))) ?></span>
        </div>
        <div class="user-info">
            <div class="user-avatar"><?= strtoupper(substr(getUserName(), 0, 1)) ?></div>
            <div>
                <div class="user-name"><?= e(getUserName()) ?></div>
                <div class="user-role"><?= e(getUserRole()) ?></div>
            </div>
            <a href="index.php?page=logout" class="btn-logout">Logout</a>
        </div>
    </div>

    <?php $flash = getFlash(); if ($flash): ?>
    <div class="page-content" style="padding-bottom:0;">
        <div class="alert alert-<?= $flash['type'] ?>"><?= e($flash['message']) ?></div>
    </div>
    <?php endif; ?>

    <div class="page-content">

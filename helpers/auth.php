<?php
/**
 * Authentication Helper Functions
 */

require_once __DIR__ . '/../config/database.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . 'index.php?page=login');
        exit;
    }
}

function requireRole($roles) {
    requireLogin();
    if (!is_array($roles)) {
        $roles = [$roles];
    }
    if (!in_array(getUserRole(), $roles)) {
        header('Location: ' . BASE_URL . 'index.php?page=dashboard');
        exit;
    }
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUserName() {
    return $_SESSION['user_nama'] ?? '';
}

function getUserRole() {
    return $_SESSION['user_role'] ?? '';
}

function getUserRoleId() {
    return $_SESSION['user_role_id'] ?? null;
}

function login($username, $password) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM tb_user WHERE username = ? AND status_aktif = 1 LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['user_nama'] = $user['nama_lengkap'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_role'] = ucfirst($user['role']);

        logActivity($user['id_user'], 'Login');
        return true;
    }
    return false;
}

function logout() {
    if (isLoggedIn()) {
        logActivity(getUserId(), 'Logout');
    }
    session_destroy();
    header('Location: ' . BASE_URL . 'index.php?page=login');
    exit;
}

function logActivity($userId, $aktivitas) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO tb_log_aktivitas (id_user, aktivitas, waktu_aktivitas) VALUES (?, ?, NOW())");
    $stmt->execute([$userId, $aktivitas]);
}

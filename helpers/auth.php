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
    $stmt = $db->prepare("SELECT u.*, r.nama_role FROM users u JOIN roles r ON u.role_id = r.id WHERE u.username = ? AND u.status = 'aktif' LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nama'] = $user['nama'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_role'] = $user['nama_role'];
        $_SESSION['user_role_id'] = $user['role_id'];

        logActivity($user['id'], 'Login', 'User ' . $user['username'] . ' berhasil login');
        return true;
    }
    return false;
}

function logout() {
    if (isLoggedIn()) {
        logActivity(getUserId(), 'Logout', 'User ' . getUserName() . ' logout');
    }
    session_destroy();
    header('Location: ' . BASE_URL . 'index.php?page=login');
    exit;
}

function logActivity($userId, $aktivitas, $detail = '') {
    $db = getDB();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $stmt = $db->prepare("INSERT INTO activity_logs (user_id, aktivitas, detail, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $aktivitas, $detail, $ip]);
}

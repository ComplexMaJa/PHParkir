<?php
/**
 * Auth Controller
 */

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';

function handleLogin() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            setFlash('danger', 'Username dan password harus diisi.');
            header('Location: index.php?page=login');
            exit;
        }

        if (login($username, $password)) {
            setFlash('success', 'Login berhasil! Selamat datang, ' . getUserName());
            header('Location: index.php?page=dashboard');
            exit;
        } else {
            setFlash('danger', 'Username atau password salah.');
            header('Location: index.php?page=login');
            exit;
        }
    }

    require __DIR__ . '/../views/auth/login.php';
}

function handleLogout() {
    logout();
}

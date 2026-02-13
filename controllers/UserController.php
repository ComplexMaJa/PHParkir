<?php
/**
 * User Controller (Admin)
 */

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/UserModel.php';

function handleUsers() {
    requireRole(['Admin']);
    $model = new UserModel();
    $action = $_GET['action'] ?? 'list';

    switch ($action) {
        case 'create':
            handleUserCreate($model);
            break;
        case 'edit':
            handleUserEdit($model);
            break;
        case 'delete':
            handleUserDelete($model);
            break;
        default:
            handleUserList($model);
    }
}

function handleUserList($model) {
    $page = max(1, (int)($_GET['p'] ?? 1));
    $pagination = paginate($model->count(), 10, $page);
    $users = $model->getAll($pagination['per_page'], $pagination['offset']);

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/users/index.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleUserCreate($model) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'role' => $_POST['role'] ?? '',
            'status_aktif' => (int)($_POST['status_aktif'] ?? 1),
        ];

        // Validation
        if (empty($data['nama_lengkap']) || empty($data['username']) || empty($data['password']) || empty($data['role'])) {
            setFlash('danger', 'Semua field wajib diisi.');
        } elseif (!in_array($data['role'], ['admin', 'petugas', 'owner'])) {
            setFlash('danger', 'Role tidak valid.');
        } elseif ($model->usernameExists($data['username'])) {
            setFlash('danger', 'Username sudah digunakan.');
        } else {
            $model->create($data);
            logActivity(getUserId(), 'Menambahkan user: ' . $data['username']);
            setFlash('success', 'User berhasil ditambahkan.');
            header('Location: index.php?page=users');
            exit;
        }
    }

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/users/form.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleUserEdit($model) {
    $id = (int)($_GET['id'] ?? 0);
    $user = $model->getById($id);
    if (!$user) {
        setFlash('danger', 'User tidak ditemukan.');
        header('Location: index.php?page=users');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'role' => $_POST['role'] ?? '',
            'status_aktif' => (int)($_POST['status_aktif'] ?? 1),
        ];

        if (empty($data['nama_lengkap']) || empty($data['username']) || empty($data['role'])) {
            setFlash('danger', 'Nama, username, dan role wajib diisi.');
        } elseif (!in_array($data['role'], ['admin', 'petugas', 'owner'])) {
            setFlash('danger', 'Role tidak valid.');
        } elseif ($model->usernameExists($data['username'], $id)) {
            setFlash('danger', 'Username sudah digunakan.');
        } else {
            $model->update($id, $data);
            logActivity(getUserId(), 'Mengedit user: ' . $data['username']);
            setFlash('success', 'User berhasil diperbarui.');
            header('Location: index.php?page=users');
            exit;
        }
    }

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/users/form.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleUserDelete($model) {
    $id = (int)($_GET['id'] ?? 0);
    if ($id === (int) getUserId()) {
        setFlash('danger', 'Tidak dapat menghapus akun sendiri.');
    } else {
        $user = $model->getById($id);
        if ($user) {
            try {
                $model->delete($id);
                logActivity(getUserId(), 'Menghapus user: ' . $user['username']);
                setFlash('success', 'User berhasil dihapus.');
            } catch (PDOException $e) {
                setFlash('danger', 'Gagal menghapus user. User mungkin masih terkait dengan data lain.');
            }
        }
    }
    header('Location: index.php?page=users');
    exit;
}

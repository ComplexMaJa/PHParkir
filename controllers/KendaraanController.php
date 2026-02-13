<?php
/**
 * Kendaraan Controller (Admin)
 */

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/KendaraanModel.php';
require_once __DIR__ . '/../models/UserModel.php';

function handleKendaraan() {
    requireRole(['Admin']);
    $model = new KendaraanModel();
    $action = $_GET['action'] ?? 'list';

    switch ($action) {
        case 'create':
            handleKendaraanCreate($model);
            break;
        case 'edit':
            handleKendaraanEdit($model);
            break;
        case 'delete':
            handleKendaraanDelete($model);
            break;
        default:
            handleKendaraanList($model);
    }
}

function handleKendaraanList($model) {
    $page = max(1, (int)($_GET['p'] ?? 1));
    $pagination = paginate($model->count(), 10, $page);
    $kendaraan = $model->getAll($pagination['per_page'], $pagination['offset']);

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/kendaraan/index.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleKendaraanCreate($model) {
    $userModel = new UserModel();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'plat_nomor' => strtoupper(trim($_POST['plat_nomor'] ?? '')),
            'jenis_kendaraan' => trim($_POST['jenis_kendaraan'] ?? ''),
            'warna' => trim($_POST['warna'] ?? ''),
            'pemilik' => trim($_POST['pemilik'] ?? ''),
            'id_user' => (int)($_POST['id_user'] ?? 0),
        ];

        if (empty($data['plat_nomor']) || empty($data['jenis_kendaraan']) || empty($data['warna']) || empty($data['pemilik']) || $data['id_user'] === 0) {
            setFlash('danger', 'Semua field wajib diisi.');
        } else {
            $model->create($data);
            logActivity(getUserId(), 'Menambahkan kendaraan: ' . $data['plat_nomor']);
            setFlash('success', 'Kendaraan berhasil ditambahkan.');
            header('Location: index.php?page=kendaraan');
            exit;
        }
    }

    $kendaraanData = null;
    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/kendaraan/form.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleKendaraanEdit($model) {
    $id = (int)($_GET['id'] ?? 0);
    $kendaraanData = $model->getById($id);
    if (!$kendaraanData) {
        setFlash('danger', 'Kendaraan tidak ditemukan.');
        header('Location: index.php?page=kendaraan');
        exit;
    }

    $userModel = new UserModel();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'plat_nomor' => strtoupper(trim($_POST['plat_nomor'] ?? '')),
            'jenis_kendaraan' => trim($_POST['jenis_kendaraan'] ?? ''),
            'warna' => trim($_POST['warna'] ?? ''),
            'pemilik' => trim($_POST['pemilik'] ?? ''),
            'id_user' => (int)($_POST['id_user'] ?? 0),
        ];

        if (empty($data['plat_nomor']) || empty($data['jenis_kendaraan']) || empty($data['warna']) || empty($data['pemilik']) || $data['id_user'] === 0) {
            setFlash('danger', 'Semua field wajib diisi.');
        } else {
            $model->update($id, $data);
            logActivity(getUserId(), 'Mengedit kendaraan: ' . $data['plat_nomor']);
            setFlash('success', 'Kendaraan berhasil diperbarui.');
            header('Location: index.php?page=kendaraan');
            exit;
        }
    }

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/kendaraan/form.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleKendaraanDelete($model) {
    $id = (int)($_GET['id'] ?? 0);
    $kendaraanData = $model->getById($id);
    if ($kendaraanData) {
        try {
            $model->delete($id);
            logActivity(getUserId(), 'Menghapus kendaraan: ' . $kendaraanData['plat_nomor']);
            setFlash('success', 'Kendaraan berhasil dihapus.');
        } catch (PDOException $e) {
            setFlash('danger', 'Gagal menghapus. Data kendaraan masih terkait dengan data lain.');
        }
    }
    header('Location: index.php?page=kendaraan');
    exit;
}

<?php
/**
 * Area Parkir Controller (Admin)
 */

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/AreaParkirModel.php';

function handleAreaParkir() {
    requireRole(['Admin']);
    $model = new AreaParkirModel();
    $action = $_GET['action'] ?? 'list';

    switch ($action) {
        case 'create':
            handleAreaCreate($model);
            break;
        case 'edit':
            handleAreaEdit($model);
            break;
        case 'delete':
            handleAreaDelete($model);
            break;
        default:
            handleAreaList($model);
    }
}

function handleAreaList($model) {
    $page = max(1, (int)($_GET['p'] ?? 1));
    $pagination = paginate($model->count(), 10, $page);
    $areas = $model->getAll($pagination['per_page'], $pagination['offset']);

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/area_parkir/index.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleAreaCreate($model) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nama_area' => trim($_POST['nama_area'] ?? ''),
            'kapasitas' => (int)($_POST['kapasitas'] ?? 0),
            'status' => $_POST['status'] ?? 'aktif',
        ];

        if (empty($data['nama_area']) || $data['kapasitas'] <= 0) {
            setFlash('danger', 'Nama area dan kapasitas wajib diisi.');
        } else {
            $model->create($data);
            logActivity(getUserId(), 'CRUD Area Parkir', 'Menambahkan area: ' . $data['nama_area']);
            setFlash('success', 'Area parkir berhasil ditambahkan.');
            header('Location: index.php?page=area_parkir');
            exit;
        }
    }

    $areaData = null;
    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/area_parkir/form.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleAreaEdit($model) {
    $id = (int)($_GET['id'] ?? 0);
    $areaData = $model->getById($id);
    if (!$areaData) {
        setFlash('danger', 'Area parkir tidak ditemukan.');
        header('Location: index.php?page=area_parkir');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nama_area' => trim($_POST['nama_area'] ?? ''),
            'kapasitas' => (int)($_POST['kapasitas'] ?? 0),
            'status' => $_POST['status'] ?? 'aktif',
        ];

        if (empty($data['nama_area']) || $data['kapasitas'] <= 0) {
            setFlash('danger', 'Nama area dan kapasitas wajib diisi.');
        } else {
            $model->update($id, $data);
            logActivity(getUserId(), 'CRUD Area Parkir', 'Mengedit area: ' . $data['nama_area']);
            setFlash('success', 'Area parkir berhasil diperbarui.');
            header('Location: index.php?page=area_parkir');
            exit;
        }
    }

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/area_parkir/form.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleAreaDelete($model) {
    $id = (int)($_GET['id'] ?? 0);
    $areaData = $model->getById($id);
    if ($areaData) {
        try {
            $model->delete($id);
            logActivity(getUserId(), 'CRUD Area Parkir', 'Menghapus area: ' . $areaData['nama_area']);
            setFlash('success', 'Area parkir berhasil dihapus.');
        } catch (PDOException $e) {
            setFlash('danger', 'Gagal menghapus. Area masih terkait dengan data transaksi.');
        }
    }
    header('Location: index.php?page=area_parkir');
    exit;
}

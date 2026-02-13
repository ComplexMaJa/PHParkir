<?php
/**
 * Tarif Parkir Controller (Admin)
 */

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/TarifParkirModel.php';

function handleTarifParkir() {
    requireRole(['Admin']);
    $model = new TarifParkirModel();
    $action = $_GET['action'] ?? 'list';

    switch ($action) {
        case 'create':
            handleTarifCreate($model);
            break;
        case 'edit':
            handleTarifEdit($model);
            break;
        case 'delete':
            handleTarifDelete($model);
            break;
        default:
            handleTarifList($model);
    }
}

function handleTarifList($model) {
    $page = max(1, (int)($_GET['p'] ?? 1));
    $pagination = paginate($model->count(), 10, $page);
    $tarifs = $model->getAll($pagination['per_page'], $pagination['offset']);

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/tarif_parkir/index.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleTarifCreate($model) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'jenis_kendaraan' => $_POST['jenis_kendaraan'] ?? '',
            'tarif_per_jam' => (float)($_POST['tarif_per_jam'] ?? 0),
        ];

        if (empty($data['jenis_kendaraan']) || !in_array($data['jenis_kendaraan'], ['motor', 'mobil', 'lainnya']) || $data['tarif_per_jam'] <= 0) {
            setFlash('danger', 'Jenis kendaraan dan tarif per jam wajib diisi dengan benar.');
        } else {
            $model->create($data);
            logActivity(getUserId(), 'Menambahkan tarif parkir');
            setFlash('success', 'Tarif parkir berhasil ditambahkan.');
            header('Location: index.php?page=tarif_parkir');
            exit;
        }
    }

    $tarifData = null;
    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/tarif_parkir/form.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleTarifEdit($model) {
    $id = (int)($_GET['id'] ?? 0);
    $tarifData = $model->getById($id);
    if (!$tarifData) {
        setFlash('danger', 'Tarif tidak ditemukan.');
        header('Location: index.php?page=tarif_parkir');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'jenis_kendaraan' => $_POST['jenis_kendaraan'] ?? '',
            'tarif_per_jam' => (float)($_POST['tarif_per_jam'] ?? 0),
        ];

        if (empty($data['jenis_kendaraan']) || !in_array($data['jenis_kendaraan'], ['motor', 'mobil', 'lainnya']) || $data['tarif_per_jam'] <= 0) {
            setFlash('danger', 'Jenis kendaraan dan tarif per jam wajib diisi dengan benar.');
        } else {
            $model->update($id, $data);
            logActivity(getUserId(), 'Mengedit tarif parkir');
            setFlash('success', 'Tarif parkir berhasil diperbarui.');
            header('Location: index.php?page=tarif_parkir');
            exit;
        }
    }

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/admin/tarif_parkir/form.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleTarifDelete($model) {
    $id = (int)($_GET['id'] ?? 0);
    $tarifData = $model->getById($id);
    if ($tarifData) {
        try {
            $model->delete($id);
            logActivity(getUserId(), 'Menghapus tarif parkir');
            setFlash('success', 'Tarif parkir berhasil dihapus.');
        } catch (PDOException $e) {
            setFlash('danger', 'Gagal menghapus tarif parkir.');
        }
    }
    header('Location: index.php?page=tarif_parkir');
    exit;
}

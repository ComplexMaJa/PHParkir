<?php
/**
 * Transaksi Controller (Petugas)
 */

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/TransaksiModel.php';
require_once __DIR__ . '/../models/KendaraanModel.php';
require_once __DIR__ . '/../models/AreaParkirModel.php';
require_once __DIR__ . '/../models/TarifParkirModel.php';

function handleTransaksi() {
    requireRole(['Petugas']);
    $action = $_GET['action'] ?? 'list';

    switch ($action) {
        case 'masuk':
            handleMasuk();
            break;
        case 'keluar':
            handleKeluar();
            break;
        case 'struk':
            handleStruk();
            break;
        default:
            handleTransaksiList();
    }
}

function handleTransaksiList() {
    $model = new TransaksiModel();
    $status = $_GET['status'] ?? null;
    $page = max(1, (int)($_GET['p'] ?? 1));
    $pagination = paginate($model->count($status), 10, $page);
    $transaksi = $model->getAll($pagination['per_page'], $pagination['offset'], $status);

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/petugas/transaksi/index.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleMasuk() {
    $kendaraanModel = new KendaraanModel();
    $areaModel = new AreaParkirModel();
    $tarifModel = new TarifParkirModel();
    $kendaraanList = $kendaraanModel->getAllNoPagination();
    $areaList = $areaModel->getAllActive();
    $tarifList = $tarifModel->getAllNoPagination();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idKendaraan = (int)($_POST['id_kendaraan'] ?? 0);
        $idArea = (int)($_POST['id_area'] ?? 0);
        $idTarif = (int)($_POST['id_tarif'] ?? 0);

        if ($idKendaraan === 0 || $idArea === 0 || $idTarif === 0) {
            setFlash('danger', 'Semua field wajib diisi.');
        } else {
            // Check if area is full
            $area = $areaModel->getById($idArea);
            if ($area && $area['terisi'] >= $area['kapasitas']) {
                setFlash('danger', 'Area parkir sudah penuh!');
            } else {
                $data = [
                    'id_kendaraan' => $idKendaraan,
                    'waktu_masuk' => date('Y-m-d H:i:s'),
                    'id_tarif' => $idTarif,
                    'id_user' => getUserId(),
                    'id_area' => $idArea,
                ];
                $transaksiModel = new TransaksiModel();
                $transaksiModel->masuk($data);
                $areaModel->incrementTerisi($idArea);

                $kendaraan = $kendaraanModel->getById($idKendaraan);
                logActivity(getUserId(), 'Kendaraan Masuk: ' . ($kendaraan ? $kendaraan['plat_nomor'] : ''));
                setFlash('success', 'Kendaraan berhasil masuk.');
                header('Location: index.php?page=transaksi');
                exit;
            }
        }
    }

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/petugas/transaksi/masuk.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleKeluar() {
    $transaksiModel = new TransaksiModel();
    $transaksiData = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cari_plat'])) {
        $plat = strtoupper(trim($_POST['plat_nomor'] ?? ''));
        if (!empty($plat)) {
            $transaksiData = $transaksiModel->getActiveByPlat($plat);
            if (!$transaksiData) {
                setFlash('danger', 'Kendaraan dengan plat ' . e($plat) . ' tidak ditemukan atau sudah keluar.');
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proses_keluar'])) {
        $transaksiId = (int)($_POST['transaksi_id'] ?? 0);
        $transaksiData = $transaksiModel->getById($transaksiId);

        if ($transaksiData && $transaksiData['status'] === 'masuk') {
            $waktuKeluar = date('Y-m-d H:i:s');
            $waktuMasuk = strtotime($transaksiData['waktu_masuk']);
            $waktuKeluarTs = strtotime($waktuKeluar);
            $durasiDetik = $waktuKeluarTs - $waktuMasuk;
            $durasiJam = max(1, (int)ceil($durasiDetik / 3600)); // minimum 1 jam

            // Get tarif
            $tarifPerJam = (float) $transaksiData['tarif_per_jam'];
            $totalBiaya = $tarifPerJam * $durasiJam;

            // Update transaksi with duration and cost
            $transaksiModel->keluar($transaksiId, $waktuKeluar, $durasiJam, $totalBiaya);

            // Decrement area
            $areaModel = new AreaParkirModel();
            $areaModel->decrementTerisi($transaksiData['id_area']);

            logActivity(getUserId(), 'Kendaraan Keluar: ' . $transaksiData['plat_nomor'] . ' - Biaya: ' . formatRupiah($totalBiaya));
            setFlash('success', 'Kendaraan berhasil keluar. Total biaya: ' . formatRupiah($totalBiaya));

            // Redirect to struk
            header('Location: index.php?page=transaksi&action=struk&id=' . $transaksiId);
            exit;
        } else {
            setFlash('danger', 'Transaksi tidak valid.');
        }
    }

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    require __DIR__ . '/../views/petugas/transaksi/keluar.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

function handleStruk() {
    $id = (int)($_GET['id'] ?? 0);
    $transaksiModel = new TransaksiModel();
    $transaksiData = $transaksiModel->getById($id);

    if (!$transaksiData) {
        setFlash('danger', 'Transaksi tidak ditemukan.');
        header('Location: index.php?page=transaksi');
        exit;
    }

    require __DIR__ . '/../views/petugas/transaksi/struk.php';
}

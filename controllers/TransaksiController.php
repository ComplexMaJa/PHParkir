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
    $kendaraanList = $kendaraanModel->getAllNoPagination();
    $areaList = $areaModel->getAllActive();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'kode_transaksi' => generateKodeTransaksi(),
            'plat_nomor' => strtoupper(trim($_POST['plat_nomor'] ?? '')),
            'kendaraan_id' => (int)($_POST['kendaraan_id'] ?? 0),
            'area_parkir_id' => (int)($_POST['area_parkir_id'] ?? 0),
            'user_id' => getUserId(),
            'waktu_masuk' => date('Y-m-d H:i:s'),
        ];

        if (empty($data['plat_nomor']) || $data['kendaraan_id'] === 0 || $data['area_parkir_id'] === 0) {
            setFlash('danger', 'Semua field wajib diisi.');
        } else {
            // Check if area is full
            $area = $areaModel->getById($data['area_parkir_id']);
            if ($area && $area['terisi'] >= $area['kapasitas']) {
                setFlash('danger', 'Area parkir sudah penuh!');
            } else {
                $transaksiModel = new TransaksiModel();
                $transaksiModel->masuk($data);
                $areaModel->incrementTerisi($data['area_parkir_id']);
                logActivity(getUserId(), 'Kendaraan Masuk', 'Plat: ' . $data['plat_nomor'] . ' - Kode: ' . $data['kode_transaksi']);
                setFlash('success', 'Kendaraan berhasil masuk. Kode: ' . $data['kode_transaksi']);
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
    $detailData = null;

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
            $durasiJam = max(1, ceil($durasiDetik / 3600)); // minimum 1 jam

            // Get tarif
            $tarifModel = new TarifParkirModel();
            $tarif = $tarifModel->getByKendaraanId($transaksiData['kendaraan_id']);
            $tarifPerJam = $tarif ? (float) $tarif['tarif_per_jam'] : 0;
            $tarifFlat = $tarif ? (float) $tarif['tarif_flat'] : 0;
            $totalBiaya = $tarifFlat + ($tarifPerJam * $durasiJam);

            // Update transaksi
            $transaksiModel->keluar($transaksiId, $waktuKeluar);

            // Create detail
            $transaksiModel->createDetail([
                'transaksi_id' => $transaksiId,
                'durasi_jam' => $durasiJam,
                'tarif_per_jam' => $tarifPerJam,
                'tarif_flat' => $tarifFlat,
                'total_biaya' => $totalBiaya,
            ]);

            // Decrement area
            $areaModel = new AreaParkirModel();
            $areaModel->decrementTerisi($transaksiData['area_parkir_id']);

            logActivity(getUserId(), 'Kendaraan Keluar', 'Plat: ' . $transaksiData['plat_nomor'] . ' - Biaya: ' . formatRupiah($totalBiaya));
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
    $detailData = $transaksiModel->getDetail($id);

    if (!$transaksiData) {
        setFlash('danger', 'Transaksi tidak ditemukan.');
        header('Location: index.php?page=transaksi');
        exit;
    }

    require __DIR__ . '/../views/petugas/transaksi/struk.php';
}

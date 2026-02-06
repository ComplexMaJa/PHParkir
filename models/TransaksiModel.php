<?php
/**
 * Transaksi Model
 */

require_once __DIR__ . '/../config/database.php';

class TransaksiModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($limit = 10, $offset = 0, $status = null) {
        $sql = "SELECT t.*, k.jenis_kendaraan, a.nama_area, u.nama as petugas_nama
                FROM transaksi t
                JOIN kendaraan k ON t.kendaraan_id = k.id
                JOIN area_parkir a ON t.area_parkir_id = a.id
                JOIN users u ON t.user_id = u.id";
        $params = [];

        if ($status) {
            $sql .= " WHERE t.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY t.id DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function count($status = null) {
        $sql = "SELECT COUNT(*) FROM transaksi";
        $params = [];
        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT t.*, k.jenis_kendaraan, a.nama_area, u.nama as petugas_nama
                FROM transaksi t
                JOIN kendaraan k ON t.kendaraan_id = k.id
                JOIN area_parkir a ON t.area_parkir_id = a.id
                JOIN users u ON t.user_id = u.id
                WHERE t.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByKode($kode) {
        $stmt = $this->db->prepare("SELECT t.*, k.jenis_kendaraan, a.nama_area, u.nama as petugas_nama
                FROM transaksi t
                JOIN kendaraan k ON t.kendaraan_id = k.id
                JOIN area_parkir a ON t.area_parkir_id = a.id
                JOIN users u ON t.user_id = u.id
                WHERE t.kode_transaksi = ?");
        $stmt->execute([$kode]);
        return $stmt->fetch();
    }

    public function masuk($data) {
        $stmt = $this->db->prepare("INSERT INTO transaksi (kode_transaksi, plat_nomor, kendaraan_id, area_parkir_id, user_id, waktu_masuk, status) VALUES (?, ?, ?, ?, ?, ?, 'masuk')");
        return $stmt->execute([
            $data['kode_transaksi'],
            strtoupper($data['plat_nomor']),
            $data['kendaraan_id'],
            $data['area_parkir_id'],
            $data['user_id'],
            $data['waktu_masuk']
        ]);
    }

    public function keluar($id, $waktuKeluar) {
        $stmt = $this->db->prepare("UPDATE transaksi SET waktu_keluar = ?, status = 'keluar' WHERE id = ? AND status = 'masuk'");
        return $stmt->execute([$waktuKeluar, $id]);
    }

    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }

    public function createDetail($data) {
        $stmt = $this->db->prepare("INSERT INTO detail_transaksi (transaksi_id, durasi_jam, tarif_per_jam, tarif_flat, total_biaya) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['transaksi_id'],
            $data['durasi_jam'],
            $data['tarif_per_jam'],
            $data['tarif_flat'],
            $data['total_biaya']
        ]);
    }

    public function getDetail($transaksiId) {
        $stmt = $this->db->prepare("SELECT * FROM detail_transaksi WHERE transaksi_id = ?");
        $stmt->execute([$transaksiId]);
        return $stmt->fetch();
    }

    public function getRekapByDateRange($startDate, $endDate) {
        $stmt = $this->db->prepare("SELECT t.*, k.jenis_kendaraan, a.nama_area, u.nama as petugas_nama, dt.durasi_jam, dt.total_biaya
                FROM transaksi t
                JOIN kendaraan k ON t.kendaraan_id = k.id
                JOIN area_parkir a ON t.area_parkir_id = a.id
                JOIN users u ON t.user_id = u.id
                LEFT JOIN detail_transaksi dt ON dt.transaksi_id = t.id
                WHERE t.status = 'keluar'
                AND DATE(t.waktu_keluar) BETWEEN ? AND ?
                ORDER BY t.waktu_keluar DESC");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function getTotalPendapatan($startDate = null, $endDate = null) {
        $sql = "SELECT COALESCE(SUM(dt.total_biaya), 0) as total FROM detail_transaksi dt JOIN transaksi t ON dt.transaksi_id = t.id WHERE t.status = 'keluar'";
        $params = [];
        if ($startDate && $endDate) {
            $sql .= " AND DATE(t.waktu_keluar) BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (float) $stmt->fetch()['total'];
    }

    public function getPendapatanPerHari($startDate, $endDate) {
        $stmt = $this->db->prepare("SELECT DATE(t.waktu_keluar) as tanggal, COUNT(*) as jumlah_transaksi, SUM(dt.total_biaya) as total_pendapatan
                FROM detail_transaksi dt
                JOIN transaksi t ON dt.transaksi_id = t.id
                WHERE t.status = 'keluar' AND DATE(t.waktu_keluar) BETWEEN ? AND ?
                GROUP BY DATE(t.waktu_keluar)
                ORDER BY tanggal DESC");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function getDashboardStats() {
        $stats = [];
        $stats['total_transaksi'] = (int) $this->db->query("SELECT COUNT(*) FROM transaksi")->fetchColumn();
        $stats['kendaraan_terparkir'] = (int) $this->db->query("SELECT COUNT(*) FROM transaksi WHERE status = 'masuk'")->fetchColumn();
        $stats['transaksi_hari_ini'] = (int) $this->db->query("SELECT COUNT(*) FROM transaksi WHERE DATE(waktu_masuk) = CURDATE()")->fetchColumn();
        $stats['pendapatan_hari_ini'] = (float) $this->db->query("SELECT COALESCE(SUM(dt.total_biaya),0) FROM detail_transaksi dt JOIN transaksi t ON dt.transaksi_id = t.id WHERE DATE(t.waktu_keluar) = CURDATE()")->fetchColumn();
        $stats['total_pendapatan'] = (float) $this->db->query("SELECT COALESCE(SUM(total_biaya),0) FROM detail_transaksi")->fetchColumn();
        return $stats;
    }

    public function getActiveByPlat($plat) {
        $stmt = $this->db->prepare("SELECT t.*, k.jenis_kendaraan, a.nama_area FROM transaksi t JOIN kendaraan k ON t.kendaraan_id = k.id JOIN area_parkir a ON t.area_parkir_id = a.id WHERE t.plat_nomor = ? AND t.status = 'masuk' LIMIT 1");
        $stmt->execute([strtoupper($plat)]);
        return $stmt->fetch();
    }
}

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
        $sql = "SELECT t.*, k.plat_nomor, k.jenis_kendaraan, k.warna, k.pemilik,
                       a.nama_area, u.nama_lengkap as petugas_nama, tf.tarif_per_jam
                FROM tb_transaksi t
                JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
                JOIN tb_area_parkir a ON t.id_area = a.id_area
                JOIN tb_user u ON t.id_user = u.id_user
                JOIN tb_tarif tf ON t.id_tarif = tf.id_tarif";
        $params = [];

        if ($status) {
            $sql .= " WHERE t.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY t.id_transaksi DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function count($status = null) {
        $sql = "SELECT COUNT(*) FROM tb_transaksi";
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
        $stmt = $this->db->prepare("SELECT t.*, k.plat_nomor, k.jenis_kendaraan, k.warna, k.pemilik,
                a.nama_area, u.nama_lengkap as petugas_nama, tf.tarif_per_jam
                FROM tb_transaksi t
                JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
                JOIN tb_area_parkir a ON t.id_area = a.id_area
                JOIN tb_user u ON t.id_user = u.id_user
                JOIN tb_tarif tf ON t.id_tarif = tf.id_tarif
                WHERE t.id_transaksi = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function masuk($data) {
        $stmt = $this->db->prepare("INSERT INTO tb_transaksi (id_kendaraan, waktu_masuk, id_tarif, durasi_jam, biaya_total, status, id_user, id_area) VALUES (?, ?, ?, 0, 0, 'masuk', ?, ?)");
        return $stmt->execute([
            $data['id_kendaraan'],
            $data['waktu_masuk'],
            $data['id_tarif'],
            $data['id_user'],
            $data['id_area']
        ]);
    }

    public function keluar($id, $waktuKeluar, $durasiJam, $biayaTotal) {
        $stmt = $this->db->prepare("UPDATE tb_transaksi SET waktu_keluar = ?, durasi_jam = ?, biaya_total = ?, status = 'keluar' WHERE id_transaksi = ? AND status = 'masuk'");
        return $stmt->execute([$waktuKeluar, $durasiJam, $biayaTotal, $id]);
    }

    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }

    public function getRekapByDateRange($startDate, $endDate) {
        $stmt = $this->db->prepare("SELECT t.*, k.plat_nomor, k.jenis_kendaraan, a.nama_area, u.nama_lengkap as petugas_nama
                FROM tb_transaksi t
                JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
                JOIN tb_area_parkir a ON t.id_area = a.id_area
                JOIN tb_user u ON t.id_user = u.id_user
                WHERE t.status = 'keluar'
                AND DATE(t.waktu_keluar) BETWEEN ? AND ?
                ORDER BY t.waktu_keluar DESC");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function getTotalPendapatan($startDate = null, $endDate = null) {
        $sql = "SELECT COALESCE(SUM(biaya_total), 0) as total FROM tb_transaksi WHERE status = 'keluar'";
        $params = [];
        if ($startDate && $endDate) {
            $sql .= " AND DATE(waktu_keluar) BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (float) $stmt->fetch()['total'];
    }

    public function getPendapatanPerHari($startDate, $endDate) {
        $stmt = $this->db->prepare("SELECT DATE(waktu_keluar) as tanggal, COUNT(*) as jumlah_transaksi, SUM(biaya_total) as total_pendapatan
                FROM tb_transaksi
                WHERE status = 'keluar' AND DATE(waktu_keluar) BETWEEN ? AND ?
                GROUP BY DATE(waktu_keluar)
                ORDER BY tanggal DESC");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function getDashboardStats() {
        $stats = [];
        $stats['total_transaksi'] = (int) $this->db->query("SELECT COUNT(*) FROM tb_transaksi")->fetchColumn();
        $stats['kendaraan_terparkir'] = (int) $this->db->query("SELECT COUNT(*) FROM tb_transaksi WHERE status = 'masuk'")->fetchColumn();
        $stats['transaksi_hari_ini'] = (int) $this->db->query("SELECT COUNT(*) FROM tb_transaksi WHERE DATE(waktu_masuk) = CURDATE()")->fetchColumn();
        $stats['pendapatan_hari_ini'] = (float) $this->db->query("SELECT COALESCE(SUM(biaya_total),0) FROM tb_transaksi WHERE status = 'keluar' AND DATE(waktu_keluar) = CURDATE()")->fetchColumn();
        $stats['total_pendapatan'] = (float) $this->db->query("SELECT COALESCE(SUM(biaya_total),0) FROM tb_transaksi WHERE status = 'keluar'")->fetchColumn();
        return $stats;
    }

    public function getActiveByKendaraan($idKendaraan) {
        $stmt = $this->db->prepare("SELECT t.*, k.plat_nomor, k.jenis_kendaraan, a.nama_area
                FROM tb_transaksi t
                JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
                JOIN tb_area_parkir a ON t.id_area = a.id_area
                WHERE t.id_kendaraan = ? AND t.status = 'masuk' LIMIT 1");
        $stmt->execute([$idKendaraan]);
        return $stmt->fetch();
    }

    public function getActiveByPlat($plat) {
        $stmt = $this->db->prepare("SELECT t.*, k.plat_nomor, k.jenis_kendaraan, a.nama_area
                FROM tb_transaksi t
                JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
                JOIN tb_area_parkir a ON t.id_area = a.id_area
                WHERE k.plat_nomor = ? AND t.status = 'masuk' LIMIT 1");
        $stmt->execute([strtoupper($plat)]);
        return $stmt->fetch();
    }
}

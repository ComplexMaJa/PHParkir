<?php
/**
 * Tarif Parkir Model
 */

require_once __DIR__ . '/../config/database.php';

class TarifParkirModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("SELECT t.*, k.jenis_kendaraan FROM tarif_parkir t JOIN kendaraan k ON t.kendaraan_id = k.id ORDER BY t.id DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count() {
        return (int) $this->db->query("SELECT COUNT(*) FROM tarif_parkir")->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT t.*, k.jenis_kendaraan FROM tarif_parkir t JOIN kendaraan k ON t.kendaraan_id = k.id WHERE t.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByKendaraanId($kendaraanId) {
        $stmt = $this->db->prepare("SELECT * FROM tarif_parkir WHERE kendaraan_id = ? LIMIT 1");
        $stmt->execute([$kendaraanId]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO tarif_parkir (kendaraan_id, tarif_per_jam, tarif_flat, deskripsi) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['kendaraan_id'], $data['tarif_per_jam'], $data['tarif_flat'], $data['deskripsi'] ?? '']);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE tarif_parkir SET kendaraan_id = ?, tarif_per_jam = ?, tarif_flat = ?, deskripsi = ? WHERE id = ?");
        return $stmt->execute([$data['kendaraan_id'], $data['tarif_per_jam'], $data['tarif_flat'], $data['deskripsi'] ?? '', $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tarif_parkir WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

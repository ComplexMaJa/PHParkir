<?php
/**
 * Kendaraan Model
 */

require_once __DIR__ . '/../config/database.php';

class KendaraanModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("SELECT * FROM kendaraan ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getAllNoPagination() {
        return $this->db->query("SELECT * FROM kendaraan ORDER BY jenis_kendaraan")->fetchAll();
    }

    public function count() {
        return (int) $this->db->query("SELECT COUNT(*) FROM kendaraan")->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM kendaraan WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO kendaraan (jenis_kendaraan, deskripsi) VALUES (?, ?)");
        return $stmt->execute([$data['jenis_kendaraan'], $data['deskripsi'] ?? '']);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE kendaraan SET jenis_kendaraan = ?, deskripsi = ? WHERE id = ?");
        return $stmt->execute([$data['jenis_kendaraan'], $data['deskripsi'] ?? '', $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM kendaraan WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

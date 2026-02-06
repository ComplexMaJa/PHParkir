<?php
/**
 * Area Parkir Model
 */

require_once __DIR__ . '/../config/database.php';

class AreaParkirModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("SELECT * FROM area_parkir ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getAllActive() {
        return $this->db->query("SELECT * FROM area_parkir WHERE status = 'aktif' ORDER BY nama_area")->fetchAll();
    }

    public function count() {
        return (int) $this->db->query("SELECT COUNT(*) FROM area_parkir")->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM area_parkir WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO area_parkir (nama_area, kapasitas, status) VALUES (?, ?, ?)");
        return $stmt->execute([$data['nama_area'], $data['kapasitas'], $data['status'] ?? 'aktif']);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE area_parkir SET nama_area = ?, kapasitas = ?, status = ? WHERE id = ?");
        return $stmt->execute([$data['nama_area'], $data['kapasitas'], $data['status'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM area_parkir WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function incrementTerisi($id) {
        $stmt = $this->db->prepare("UPDATE area_parkir SET terisi = terisi + 1 WHERE id = ? AND terisi < kapasitas");
        return $stmt->execute([$id]);
    }

    public function decrementTerisi($id) {
        $stmt = $this->db->prepare("UPDATE area_parkir SET terisi = GREATEST(0, terisi - 1) WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

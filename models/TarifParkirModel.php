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
        $stmt = $this->db->prepare("SELECT * FROM tb_tarif ORDER BY id_tarif DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count() {
        return (int) $this->db->query("SELECT COUNT(*) FROM tb_tarif")->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tb_tarif WHERE id_tarif = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByJenisKendaraan($jenis) {
        $stmt = $this->db->prepare("SELECT * FROM tb_tarif WHERE jenis_kendaraan = ? LIMIT 1");
        $stmt->execute([$jenis]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO tb_tarif (jenis_kendaraan, tarif_per_jam) VALUES (?, ?)");
        return $stmt->execute([$data['jenis_kendaraan'], $data['tarif_per_jam']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE tb_tarif SET jenis_kendaraan = ?, tarif_per_jam = ? WHERE id_tarif = ?");
        return $stmt->execute([$data['jenis_kendaraan'], $data['tarif_per_jam'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tb_tarif WHERE id_tarif = ?");
        return $stmt->execute([$id]);
    }

    public function getAllNoPagination() {
        return $this->db->query("SELECT * FROM tb_tarif ORDER BY jenis_kendaraan")->fetchAll();
    }
}

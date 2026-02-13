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
        $stmt = $this->db->prepare("SELECT k.*, u.nama_lengkap FROM tb_kendaraan k JOIN tb_user u ON k.id_user = u.id_user ORDER BY k.id_kendaraan DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getAllNoPagination() {
        return $this->db->query("SELECT * FROM tb_kendaraan ORDER BY plat_nomor")->fetchAll();
    }

    public function count() {
        return (int) $this->db->query("SELECT COUNT(*) FROM tb_kendaraan")->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT k.*, u.nama_lengkap FROM tb_kendaraan k JOIN tb_user u ON k.id_user = u.id_user WHERE k.id_kendaraan = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO tb_kendaraan (plat_nomor, jenis_kendaraan, warna, pemilik, id_user) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            strtoupper($data['plat_nomor']),
            $data['jenis_kendaraan'],
            $data['warna'],
            $data['pemilik'],
            $data['id_user']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE tb_kendaraan SET plat_nomor = ?, jenis_kendaraan = ?, warna = ?, pemilik = ?, id_user = ? WHERE id_kendaraan = ?");
        return $stmt->execute([
            strtoupper($data['plat_nomor']),
            $data['jenis_kendaraan'],
            $data['warna'],
            $data['pemilik'],
            $data['id_user'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tb_kendaraan WHERE id_kendaraan = ?");
        return $stmt->execute([$id]);
    }

    public function getByPlatNomor($plat) {
        $stmt = $this->db->prepare("SELECT * FROM tb_kendaraan WHERE plat_nomor = ? LIMIT 1");
        $stmt->execute([strtoupper($plat)]);
        return $stmt->fetch();
    }
}

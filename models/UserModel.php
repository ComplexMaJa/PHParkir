<?php
/**
 * User Model
 */

require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("SELECT * FROM tb_user ORDER BY id_user DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count() {
        return (int) $this->db->query("SELECT COUNT(*) FROM tb_user")->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tb_user WHERE id_user = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO tb_user (nama_lengkap, username, password, role, status_aktif) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nama_lengkap'],
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'],
            $data['status_aktif'] ?? 1
        ]);
    }

    public function update($id, $data) {
        $fields = "nama_lengkap = ?, username = ?, role = ?, status_aktif = ?";
        $params = [$data['nama_lengkap'], $data['username'], $data['role'], $data['status_aktif']];

        if (!empty($data['password'])) {
            $fields .= ", password = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $params[] = $id;
        $stmt = $this->db->prepare("UPDATE tb_user SET $fields WHERE id_user = ?");
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tb_user WHERE id_user = ?");
        return $stmt->execute([$id]);
    }

    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM tb_user WHERE username = ?";
        $params = [$username];
        if ($excludeId) {
            $sql .= " AND id_user != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }
}

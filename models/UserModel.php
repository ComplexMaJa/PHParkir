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
        $stmt = $this->db->prepare("SELECT u.*, r.nama_role FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.id DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count() {
        return (int) $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT u.*, r.nama_role FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO users (nama, username, password, role_id, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nama'],
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role_id'],
            $data['status'] ?? 'aktif'
        ]);
    }

    public function update($id, $data) {
        $fields = "nama = ?, username = ?, role_id = ?, status = ?";
        $params = [$data['nama'], $data['username'], $data['role_id'], $data['status']];

        if (!empty($data['password'])) {
            $fields .= ", password = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $params[] = $id;
        $stmt = $this->db->prepare("UPDATE users SET $fields WHERE id = ?");
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM users WHERE username = ?";
        $params = [$username];
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function getRoles() {
        return $this->db->query("SELECT * FROM roles ORDER BY id")->fetchAll();
    }
}

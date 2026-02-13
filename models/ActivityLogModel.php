<?php
/**
 * Activity Log Model
 */

require_once __DIR__ . '/../config/database.php';

class ActivityLogModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("SELECT al.*, u.nama_lengkap as user_nama, u.username FROM tb_log_aktivitas al LEFT JOIN tb_user u ON al.id_user = u.id_user ORDER BY al.id_log DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count() {
        return (int) $this->db->query("SELECT COUNT(*) FROM tb_log_aktivitas")->fetchColumn();
    }
}

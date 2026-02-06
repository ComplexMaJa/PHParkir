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
        $stmt = $this->db->prepare("SELECT al.*, u.nama as user_nama, u.username FROM activity_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.id DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count() {
        return (int) $this->db->query("SELECT COUNT(*) FROM activity_logs")->fetchColumn();
    }
}

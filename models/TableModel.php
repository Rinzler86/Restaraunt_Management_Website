<?php
class TableModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Get all tables
    public function getAllTables() {
        $query = "SELECT * FROM tables";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Check if a table is available
    public function isTableAvailable($tableId) {
        $query = "SELECT Available FROM tables WHERE TableID = :tableId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tableId', $tableId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['Available'];
    }

    public function getTableStatus($tableId) {
    $query = "SELECT COUNT(*) as count FROM orders WHERE TableID = :tableId AND DineIn = 1 AND Completed = 0";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':tableId', $tableId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0; // Returns true if there's an active dine-in order
    }

}


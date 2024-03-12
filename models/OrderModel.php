<?php
class OrderModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function updateOrder($orderId, $tableId, $employeeId, $dineIn, $completed) {
        $query = "UPDATE orders SET TableID = :tableId, EmployeeID = :employeeId, DineIn = :dineIn, Completed = :completed WHERE OrderID = :orderId";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->bindParam(':tableId', $tableId);
        $stmt->bindParam(':employeeId', $employeeId);
        $stmt->bindParam(':dineIn', $dineIn);
        $stmt->bindParam(':completed', $completed);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function addOrder($tableId, $employeeId, $dineIn) {
        $query = "INSERT INTO orders (TableID, EmployeeID, DineIn) VALUES (:tableId, :employeeId, :dineIn)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tableId', $tableId);
        $stmt->bindParam(':employeeId', $employeeId);
        $stmt->bindParam(':dineIn', $dineIn);

        return $stmt->execute();
    }
    
    public function deleteOrder($orderId) {
        $query = "DELETE FROM orders WHERE OrderID = :orderId";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':orderId', $orderId);

        return $stmt->execute();
    }
    
    public function getOrder($orderId) {
    $query = "SELECT * FROM orders WHERE OrderID = :orderId";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getAll() {
    try {
        $query = "SELECT * FROM orders";
        $stmt = $this->db->prepare($query);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle the exception
        return [];
    }
}
}


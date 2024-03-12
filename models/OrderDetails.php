<?php
class OrderDetailsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Add order details
    public function addOrderDetail($orderId, $foodId, $quantity, $priceAtTimeOfOrder) {
        $query = "INSERT INTO orderdetails (OrderID, FoodID, Quantity, PriceAtTimeOfOrder) VALUES (:orderId, :foodId, :quantity, :priceAtTimeOfOrder)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->bindParam(':foodId', $foodId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':priceAtTimeOfOrder', $priceAtTimeOfOrder);

        return $stmt->execute();
    }

    
}


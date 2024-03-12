<?php
class FoodModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addFood($name, $description, $price, $inStock, $imageUrl = null) {
    $query = "INSERT INTO food (Name, Description, Price, InStock, ImageUrl) VALUES (:name, :description, :price, :inStock, :imageUrl)";
    
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':inStock', $inStock, PDO::PARAM_INT);
    $stmt->bindParam(':imageUrl', $imageUrl, PDO::PARAM_STR);

    return $stmt->execute();
    }

    
    public function updateFood($foodId, $name, $description, $price, $inStock, $imageUrl = null) {
    $query = "UPDATE food SET Name = :name, Description = :description, Price = :price, InStock = :inStock, ImageUrl = :imageUrl WHERE FoodID = :foodId";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':foodId', $foodId, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':inStock', $inStock, PDO::PARAM_INT);
    $stmt->bindParam(':imageUrl', $imageUrl, PDO::PARAM_STR);

    return $stmt->execute();
    }


    public function deleteFood($foodId) {
        $query = "DELETE FROM food WHERE FoodID = :foodId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':foodId', $foodId, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public function getAll() {
    $query = "SELECT * FROM food";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getFood($foodId) {
        $query = "SELECT * FROM food WHERE FoodID = :foodId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':foodId', $foodId, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAllFoods() {
        $query = "SELECT * FROM food"; // Adjust the table name if different
        $stmt = $this->db->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

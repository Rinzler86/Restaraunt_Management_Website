<?php
class EmployeeModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function updateEmployee($employeeId, $firstName, $lastName, $roleId, $wage, $imageUrl = null) {
    $query = "UPDATE employees SET FirstName = :firstName, LastName = :lastName, RoleID = :roleId, Wage = :wage, ImageUrl = :imageUrl WHERE EmployeeID = :employeeId";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
    $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
    $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
    $stmt->bindParam(':wage', $wage);
    $stmt->bindParam(':imageUrl', $imageUrl, PDO::PARAM_STR);

    return $stmt->execute();
    }

    
    public function addEmployee($firstName, $lastName, $roleId, $wage, $imageUrl = null) {
    $query = "INSERT INTO employees (FirstName, LastName, RoleID, Wage, ImageUrl) VALUES (:firstName, :lastName, :roleId, :wage, :imageUrl)";
    
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
    $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
    $stmt->bindParam(':wage', $wage);
    $stmt->bindParam(':imageUrl', $imageUrl, PDO::PARAM_STR);

    return $stmt->execute();
    }


    public function deleteEmployee($employeeId) {
        $query = "DELETE FROM employees WHERE EmployeeID = :employeeId";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':employeeId', $employeeId);

        return $stmt->execute();
    }


    public function getAll() {
    $query = "SELECT * FROM employees";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getEmployee($employeeId) {
        $query = "SELECT * FROM employees WHERE EmployeeID = :employeeId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
}

}

<?php
class RoleModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Method to fetch all roles
    public function getAllRoles() {
        $query = "SELECT * FROM roles";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to fetch a single role by ID
    public function getRole($roleId) {
        $query = "SELECT * FROM roles WHERE RoleID = :roleId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to add a new role
    public function addRole($roleName) {
        $query = "INSERT INTO roles (RoleName) VALUES (:roleName)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':roleName', $roleName, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Method to update an existing role
    public function updateRole($roleId, $roleName) {
        $query = "UPDATE roles SET RoleName = :roleName WHERE RoleID = :roleId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
        $stmt->bindParam(':roleName', $roleName, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Method to delete a role
    public function deleteRole($roleId) {
        $query = "DELETE FROM roles WHERE RoleID = :roleId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}


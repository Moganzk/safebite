<?php
/**
 * Vendor Model
 * Handles vendor-related database operations
 * (This is our "items" table for the assignment)
 */

require_once __DIR__ . '/../config/database.php';

class Vendor {
    private $conn;
    private $table = 'vendors';

    public $id;
    public $name;
    public $description;
    public $location;
    public $phone;
    public $vendor_type;
    public $user_id;
    public $created_at;
    public $updated_at;

    /**
     * Constructor
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Get all vendors with user information
     * @return array
     */
    public function getAll() {
        $query = "SELECT v.*, u.username, u.email 
                  FROM {$this->table} v
                  LEFT JOIN users u ON v.user_id = u.id
                  ORDER BY v.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Get vendor by ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $query = "SELECT v.*, u.username, u.email 
                  FROM {$this->table} v
                  LEFT JOIN users u ON v.user_id = u.id
                  WHERE v.id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Get vendors by user ID
     * @param int $userId
     * @return array
     */
    public function getByUserId($userId) {
        $query = "SELECT v.*, u.username, u.email 
                  FROM {$this->table} v
                  LEFT JOIN users u ON v.user_id = u.id
                  WHERE v.user_id = :user_id
                  ORDER BY v.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Create new vendor
     * @return int|false Vendor ID on success, false on failure
     */
    public function create() {
        $query = "INSERT INTO {$this->table} 
                  (name, description, location, phone, vendor_type, user_id) 
                  VALUES (:name, :description, :location, :phone, :vendor_type, :user_id)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':vendor_type', $this->vendor_type);
        $stmt->bindParam(':user_id', $this->user_id);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    /**
     * Update vendor
     * @return bool
     */
    public function update() {
        $query = "UPDATE {$this->table} 
                  SET name = :name,
                      description = :description,
                      location = :location,
                      phone = :phone,
                      vendor_type = :vendor_type
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':vendor_type', $this->vendor_type);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    /**
     * Delete vendor
     * @return bool
     */
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    /**
     * Check if user owns vendor
     * @param int $vendorId
     * @param int $userId
     * @return bool
     */
    public function isOwner($vendorId, $userId) {
        $query = "SELECT id FROM {$this->table} 
                  WHERE id = :id AND user_id = :user_id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $vendorId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetch() !== false;
    }

    /**
     * Search vendors
     * @param string $searchTerm
     * @return array
     */
    public function search($searchTerm) {
        $query = "SELECT v.*, u.username, u.email 
                  FROM {$this->table} v
                  LEFT JOIN users u ON v.user_id = u.id
                  WHERE v.name LIKE :search 
                     OR v.description LIKE :search 
                     OR v.location LIKE :search
                  ORDER BY v.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $searchParam = "%{$searchTerm}%";
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Get vendors by type
     * @param string $type
     * @return array
     */
    public function getByType($type) {
        $query = "SELECT v.*, u.username, u.email 
                  FROM {$this->table} v
                  LEFT JOIN users u ON v.user_id = u.id
                  WHERE v.vendor_type = :type
                  ORDER BY v.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Count total vendors
     * @return int
     */
    public function count() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Count vendors by user
     * @param int $userId
     * @return int
     */
    public function countByUser($userId) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
}

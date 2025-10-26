<?php
/**
 * Inspection Model
 * Handles inspection-related database operations
 * @author Johnson Siptiek Saruni
 * @copyright 2025
 */

require_once __DIR__ . '/../config/database.php';

class Inspection {
    private $conn;
    private $table = 'inspections';

    public $id;
    public $vendor_id;
    public $inspector_id;
    public $inspection_date;
    public $hygiene_score;
    public $food_safety_score;
    public $overall_rating;
    public $findings;
    public $violations;
    public $recommendations;
    public $follow_up_required;
    public $follow_up_date;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Get all inspections for a vendor
     */
    public function getByVendorId($vendorId) {
        $query = "SELECT i.*, u.username as inspector_name 
                  FROM {$this->table} i
                  LEFT JOIN users u ON i.inspector_id = u.id
                  WHERE i.vendor_id = :vendor_id
                  ORDER BY i.inspection_date DESC, i.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':vendor_id', $vendorId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Get inspection by ID
     */
    public function getById($id) {
        $query = "SELECT i.*, u.username as inspector_name, v.name as vendor_name
                  FROM {$this->table} i
                  LEFT JOIN users u ON i.inspector_id = u.id
                  LEFT JOIN vendors v ON i.vendor_id = v.id
                  WHERE i.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Create new inspection
     */
    public function create() {
        $query = "INSERT INTO {$this->table} 
                  (vendor_id, inspector_id, inspection_date, hygiene_score, food_safety_score, 
                   overall_rating, findings, violations, recommendations, follow_up_required, 
                   follow_up_date, status) 
                  VALUES (:vendor_id, :inspector_id, :inspection_date, :hygiene_score, 
                          :food_safety_score, :overall_rating, :findings, :violations, 
                          :recommendations, :follow_up_required, :follow_up_date, :status)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':vendor_id', $this->vendor_id);
        $stmt->bindParam(':inspector_id', $this->inspector_id);
        $stmt->bindParam(':inspection_date', $this->inspection_date);
        $stmt->bindParam(':hygiene_score', $this->hygiene_score);
        $stmt->bindParam(':food_safety_score', $this->food_safety_score);
        $stmt->bindParam(':overall_rating', $this->overall_rating);
        $stmt->bindParam(':findings', $this->findings);
        $stmt->bindParam(':violations', $this->violations);
        $stmt->bindParam(':recommendations', $this->recommendations);
        $stmt->bindParam(':follow_up_required', $this->follow_up_required);
        $stmt->bindParam(':follow_up_date', $this->follow_up_date);
        $stmt->bindParam(':status', $this->status);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    /**
     * Get latest inspection for a vendor
     */
    public function getLatestByVendorId($vendorId) {
        $query = "SELECT i.*, u.username as inspector_name 
                  FROM {$this->table} i
                  LEFT JOIN users u ON i.inspector_id = u.id
                  WHERE i.vendor_id = :vendor_id
                  ORDER BY i.inspection_date DESC, i.created_at DESC
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':vendor_id', $vendorId);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Get average scores for a vendor
     */
    public function getAverageScores($vendorId) {
        $query = "SELECT 
                    AVG(hygiene_score) as avg_hygiene,
                    AVG(food_safety_score) as avg_food_safety
                  FROM {$this->table}
                  WHERE vendor_id = :vendor_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':vendor_id', $vendorId);
        $stmt->execute();

        return $stmt->fetch();
    }
}

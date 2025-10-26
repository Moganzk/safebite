<?php
/**
 * User Model
 * Handles user-related database operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/PasswordHelper.php';

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $password;
    public $role;
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
     * Find user by username
     * @param string $username
     * @return array|false User data or false
     */
    public function findByUsername($username) {
        $query = "SELECT id, username, email, password, role, created_at, updated_at 
                  FROM {$this->table} 
                  WHERE username = :username 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Find user by email
     * @param string $email
     * @return array|false User data or false
     */
    public function findByEmail($email) {
        $query = "SELECT id, username, email, password, role, created_at, updated_at 
                  FROM {$this->table} 
                  WHERE email = :email 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Find user by ID
     * @param int $id
     * @return array|false User data or false
     */
    public function findById($id) {
        $query = "SELECT id, username, email, role, created_at, updated_at 
                  FROM {$this->table} 
                  WHERE id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Create new user
     * @return bool|int User ID on success, false on failure
     */
    public function create() {
        $query = "INSERT INTO {$this->table} (username, email, password, role) 
                  VALUES (:username, :email, :password, :role)";

        $stmt = $this->conn->prepare($query);

        // Hash password
        $hashedPassword = PasswordHelper::hash($this->password);

        // Bind parameters
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $this->role);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    /**
     * Update user
     * @return bool
     */
    public function update() {
        $query = "UPDATE {$this->table} 
                  SET username = :username, 
                      email = :email, 
                      role = :role 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    /**
     * Update password
     * @param int $userId
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword($userId, $newPassword) {
        $query = "UPDATE {$this->table} 
                  SET password = :password 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $hashedPassword = PasswordHelper::hash($newPassword);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId);

        return $stmt->execute();
    }

    /**
     * Delete user
     * @return bool
     */
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    /**
     * Get all users
     * @return array
     */
    public function getAll() {
        $query = "SELECT id, username, email, role, created_at, updated_at 
                  FROM {$this->table} 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Verify login credentials
     * @param string $username
     * @param string $password
     * @return array|false User data on success, false on failure
     */
    public function verifyLogin($username, $password) {
        $user = $this->findByUsername($username);

        if ($user && PasswordHelper::verify($password, $user['password'])) {
            // Remove password from returned data
            unset($user['password']);
            return $user;
        }

        return false;
    }

    /**
     * Check if username exists
     * @param string $username
     * @return bool
     */
    public function usernameExists($username) {
        return $this->findByUsername($username) !== false;
    }

    /**
     * Check if email exists
     * @param string $email
     * @return bool
     */
    public function emailExists($email) {
        return $this->findByEmail($email) !== false;
    }
}

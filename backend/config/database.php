<?php
/**
 * Database Configuration and Connection Handler
 * SafeBite Tracker - Food Safety Inspection System
 * 
 * @author Johnson Siptiek Saruni
 * @year 2025
 * @description Handles database connections using PDO with environment variable support
 */

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function __construct() {
        // Load environment variables from .env file
        $this->loadEnv();
        
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_DATABASE') ?: 'safebite';
        $this->username = getenv('DB_USERNAME') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';
        $this->port = getenv('DB_PORT') ?: '3306';
    }

    /**
     * Load environment variables from .env file
     */
    private function loadEnv() {
        $envFile = dirname(__DIR__) . '/.env';
        
        if (!file_exists($envFile)) {
            return;
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parse KEY=VALUE format
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                if (!empty($key)) {
                    putenv("$key=$value");
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        }
    }

    /**
     * Create database connection
     * @return PDO|null
     */
    public function connect() {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Set PDO attributes
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
            return null;
        }

        return $this->conn;
    }

    /**
     * Close database connection
     */
    public function disconnect() {
        $this->conn = null;
    }

    /**
     * Get database name
     * @return string
     */
    public function getDatabaseName() {
        return $this->db_name;
    }
}

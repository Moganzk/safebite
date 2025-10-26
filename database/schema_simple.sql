-- SafeBite Tracker Database Schema
-- @author Johnson Siptiek Saruni
-- @copyright 2025
-- Simple schema matching assignment requirements: users + vendors (items)

-- Drop existing tables if they exist
DROP TABLE IF EXISTS vendors;
DROP TABLE IF EXISTS users;

-- Users table (inspectors who can login)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vendors table (this is our "items" table for the assignment)
CREATE TABLE vendors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    contact VARCHAR(50) NOT NULL,
    added_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_added_by (added_by),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample users with hashed passwords
-- All passwords are: "password123" (hashed with password_hash())
INSERT INTO users (username, email, password) VALUES
('inspector1', 'inspector1@safebite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('inspector2', 'inspector2@safebite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('inspector3', 'inspector3@safebite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample vendors
INSERT INTO vendors (name, location, contact, added_by) VALUES
('Mama Njeri Kitchen', 'Nairobi CBD, Tom Mboya Street', '+254712345678', 1),
('Ocean Fresh Restaurant', 'Mombasa, Nyali Beach', '+254723456789', 1),
('Java House Cafe', 'Westlands, Nairobi', '+254734567890', 2),
('Street Food Corner', 'Thika Road, Roasters', '+254745678901', 2),
('Highlands Grill', 'Karen, Bogani Road', '+254756789012', 3);

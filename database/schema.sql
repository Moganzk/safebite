-- SafeBite Tracker Database Schema
-- Phase 1: Core Assignment Requirements (users + vendors as "items")

-- Drop existing tables if they exist
DROP TABLE IF EXISTS vendors;
DROP TABLE IF EXISTS users;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('inspector', 'supervisor', 'admin') DEFAULT 'inspector',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Vendors table (this is our "items" table for the assignment)
CREATE TABLE vendors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255),
    phone VARCHAR(20),
    vendor_type ENUM('restaurant', 'street_food', 'cafe', 'catering', 'other') DEFAULT 'other',
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample users with hashed passwords
-- Password for all sample users: "password123"
INSERT INTO users (username, email, password, role) VALUES
('john_inspector', 'john@safebite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'inspector'),
('jane_supervisor', 'jane@safebite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor'),
('admin_user', 'admin@safebite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample vendors
INSERT INTO vendors (name, description, location, phone, vendor_type, user_id) VALUES
('Mama Njeri Kitchen', 'Popular street food vendor serving traditional Kenyan dishes', 'Nairobi CBD, Tom Mboya Street', '+254712345678', 'street_food', 1),
('Ocean Fresh Restaurant', 'Seafood restaurant with ocean view', 'Mombasa, Nyali Beach', '+254723456789', 'restaurant', 1),
('Java House Cafe', 'Modern coffee shop and eatery', 'Westlands, Nairobi', '+254734567890', 'cafe', 2);

-- Phase 2: Extended tables for full SafeBite functionality (to be added later)
-- These will be created after meeting core assignment requirements

/*
Future tables to add:
- inspections
- inspection_findings
- inspection_photos
- compliance_reports
*/

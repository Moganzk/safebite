-- Migration: Seed sample data
-- Created: 2025-10-06
-- Description: Insert sample users and vendors for testing

-- Sample users (password for all: "password123")
INSERT INTO users (username, email, password, role) VALUES
('john_inspector', 'john@safebite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'inspector'),
('jane_supervisor', 'jane@safebite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor'),
('admin_user', 'admin@safebite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE username=username;

-- Sample vendors
INSERT INTO vendors (name, description, location, phone, vendor_type, user_id) VALUES
('Mama Njeri Kitchen', 'Popular street food vendor serving traditional Kenyan dishes', 'Nairobi CBD, Tom Mboya Street', '+254712345678', 'street_food', 1),
('Ocean Fresh Restaurant', 'Seafood restaurant with ocean view', 'Mombasa, Nyali Beach', '+254723456789', 'restaurant', 1),
('Java House Cafe', 'Modern coffee shop and eatery', 'Westlands, Nairobi', '+254734567890', 'cafe', 2);

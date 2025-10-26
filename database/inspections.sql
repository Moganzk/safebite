-- SafeBite Tracker - Inspections Table
-- @author Johnson Siptiek Saruni
-- @copyright 2025

-- Create inspections table
CREATE TABLE IF NOT EXISTS inspections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT NOT NULL,
    inspector_id INT NOT NULL,
    inspection_date DATE NOT NULL,
    hygiene_score INT NOT NULL CHECK (hygiene_score BETWEEN 0 AND 100),
    food_safety_score INT NOT NULL CHECK (food_safety_score BETWEEN 0 AND 100),
    overall_rating ENUM('excellent', 'good', 'satisfactory', 'needs_improvement', 'poor') NOT NULL,
    findings TEXT,
    violations TEXT,
    recommendations TEXT,
    follow_up_required BOOLEAN DEFAULT FALSE,
    follow_up_date DATE,
    status ENUM('draft', 'submitted', 'approved') DEFAULT 'submitted',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE CASCADE,
    FOREIGN KEY (inspector_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_vendor_id (vendor_id),
    INDEX idx_inspector_id (inspector_id),
    INDEX idx_inspection_date (inspection_date),
    INDEX idx_overall_rating (overall_rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add some sample inspection data
INSERT INTO inspections (vendor_id, inspector_id, inspection_date, hygiene_score, food_safety_score, overall_rating, findings, violations, recommendations, follow_up_required) VALUES
(1, 1, CURDATE() - INTERVAL 30 DAY, 85, 90, 'good', 'Kitchen area is well-maintained. Food storage follows proper guidelines.', 'Minor: Hand washing signage needs updating.', 'Update hand washing posters to current health department standards.', TRUE),
(1, 1, CURDATE() - INTERVAL 7 DAY, 92, 95, 'excellent', 'All previous violations corrected. Excellent compliance with food safety standards.', 'None', 'Continue current practices. Schedule routine inspection in 6 months.', FALSE),
(2, 1, CURDATE() - INTERVAL 15 DAY, 78, 75, 'satisfactory', 'Kitchen cleanliness acceptable but needs improvement. Refrigeration units functioning properly.', 'Moderate: Food storage containers not properly labeled with dates. Cleaning schedule not displayed.', 'Implement proper food labeling system. Display cleaning schedule prominently.', TRUE),
(3, 2, CURDATE() - INTERVAL 20 DAY, 95, 98, 'excellent', 'Outstanding hygiene practices. Staff well-trained in food safety protocols.', 'None', 'Excellent standards maintained. Continue training programs.', FALSE),
(4, 2, CURDATE() - INTERVAL 45 DAY, 65, 70, 'needs_improvement', 'Several areas require immediate attention. Food handling practices need improvement.', 'Major: Improper food storage temperatures. Cross-contamination risk in prep area. Staff not wearing proper protective gear.', 'Immediate corrective actions required. Follow-up inspection scheduled in 2 weeks.', TRUE);

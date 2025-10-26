<?php
/**
 * Inspection Controller
 * Handles inspection-related requests
 * @author Johnson Siptiek Saruni
 * @copyright 2025
 */

require_once __DIR__ . '/../models/Inspection.php';
require_once __DIR__ . '/../utils/Validator.php';

class InspectionController {
    
    /**
     * Get inspections for a vendor
     */
    public function getByVendor($vendorId) {
        $inspectionModel = new Inspection();
        $inspections = $inspectionModel->getByVendorId($vendorId);

        Response::success($inspections);
    }

    /**
     * Create new inspection
     */
    public function store() {
        // Check authentication
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            Response::unauthorized('You must be logged in');
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // Validate
        $validator = new Validator($data);
        $validator->required('vendor_id', 'Vendor ID is required')
                  ->required('inspection_date', 'Inspection date is required')
                  ->required('hygiene_score', 'Hygiene score is required')
                  ->required('food_safety_score', 'Food safety score is required')
                  ->required('overall_rating', 'Overall rating is required');

        if ($validator->fails()) {
            Response::validationError($validator->errors());
        }

        // Create inspection
        $inspectionModel = new Inspection();
        $inspectionModel->vendor_id = $data['vendor_id'];
        $inspectionModel->inspector_id = $_SESSION['user_id'];
        $inspectionModel->inspection_date = $data['inspection_date'];
        $inspectionModel->hygiene_score = $data['hygiene_score'];
        $inspectionModel->food_safety_score = $data['food_safety_score'];
        $inspectionModel->overall_rating = $data['overall_rating'];
        $inspectionModel->findings = isset($data['findings']) ? $data['findings'] : '';
        $inspectionModel->violations = isset($data['violations']) ? $data['violations'] : '';
        $inspectionModel->recommendations = isset($data['recommendations']) ? $data['recommendations'] : '';
        $inspectionModel->follow_up_required = isset($data['follow_up_required']) ? $data['follow_up_required'] : false;
        $inspectionModel->follow_up_date = isset($data['follow_up_date']) ? $data['follow_up_date'] : null;
        $inspectionModel->status = isset($data['status']) ? $data['status'] : 'submitted';

        $inspectionId = $inspectionModel->create();

        if ($inspectionId) {
            $inspection = $inspectionModel->getById($inspectionId);
            Response::success($inspection, SUCCESS_CREATED, HTTP_CREATED);
        } else {
            Response::databaseError('Failed to create inspection');
        }
    }
}

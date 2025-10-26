<?php
/**
 * Vendor Controller
 * Handles vendor CRUD operations (Items for the assignment)
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/Vendor.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';

class VendorController {
    /**
     * Get all vendors
     */
    public function index() {
        $vendorModel = new Vendor();
        $vendors = $vendorModel->getAll();

        Response::success($vendors);
    }

    /**
     * Get single vendor
     */
    public function show($id) {
        if (!$id || !is_numeric($id)) {
            Response::error('Invalid vendor ID', HTTP_BAD_REQUEST);
        }

        $vendorModel = new Vendor();
        $vendor = $vendorModel->getById($id);

        if (!$vendor) {
            Response::notFound('Vendor not found');
        }

        Response::success($vendor);
    }

    /**
     * Create new vendor
     */
    public function store() {
        // Check authentication
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            Response::unauthorized('You must be logged in to add a vendor');
        }

        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate input
        $validator = new Validator($data);
        $validator->required('name', 'Vendor name is required')
                  ->required('description', 'Description is required')
                  ->minLength('name', 3, 'Vendor name must be at least 3 characters')
                  ->minLength('description', 10, 'Description must be at least 10 characters');

        if ($validator->fails()) {
            Response::validationError($validator->errors());
        }

        // Create vendor
        $vendorModel = new Vendor();
        $vendorModel->name = Validator::sanitize($data['name']);
        $vendorModel->description = Validator::sanitize($data['description']);
        $vendorModel->location = isset($data['location']) ? Validator::sanitize($data['location']) : '';
        $vendorModel->phone = isset($data['phone']) ? Validator::sanitize($data['phone']) : '';
        $vendorModel->vendor_type = isset($data['vendor_type']) ? $data['vendor_type'] : VENDOR_OTHER;
        $vendorModel->user_id = $_SESSION['user_id'];

        $vendorId = $vendorModel->create();

        if ($vendorId) {
            $vendor = $vendorModel->getById($vendorId);
            Response::success($vendor, SUCCESS_CREATED, HTTP_CREATED);
        } else {
            Response::databaseError('Failed to create vendor');
        }
    }

    /**
     * Update vendor
     */
    public function update($id) {
        // Check authentication
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            Response::unauthorized('You must be logged in to update a vendor');
        }

        if (!$id || !is_numeric($id)) {
            Response::error('Invalid vendor ID', HTTP_BAD_REQUEST);
        }

        // Check if vendor exists
        $vendorModel = new Vendor();
        $vendor = $vendorModel->getById($id);

        if (!$vendor) {
            Response::notFound('Vendor not found');
        }

        // Check ownership (user can only edit their own vendors)
        if (!$vendorModel->isOwner($id, $_SESSION['user_id'])) {
            Response::forbidden('You can only edit vendors you created');
        }

        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate input
        $validator = new Validator($data);
        $validator->required('name', 'Vendor name is required')
                  ->required('description', 'Description is required')
                  ->minLength('name', 3, 'Vendor name must be at least 3 characters')
                  ->minLength('description', 10, 'Description must be at least 10 characters');

        if ($validator->fails()) {
            Response::validationError($validator->errors());
        }

        // Update vendor
        $vendorModel->id = $id;
        $vendorModel->name = Validator::sanitize($data['name']);
        $vendorModel->description = Validator::sanitize($data['description']);
        $vendorModel->location = isset($data['location']) ? Validator::sanitize($data['location']) : $vendor['location'];
        $vendorModel->phone = isset($data['phone']) ? Validator::sanitize($data['phone']) : $vendor['phone'];
        $vendorModel->vendor_type = isset($data['vendor_type']) ? $data['vendor_type'] : $vendor['vendor_type'];

        if ($vendorModel->update()) {
            $updatedVendor = $vendorModel->getById($id);
            Response::success($updatedVendor, SUCCESS_UPDATED);
        } else {
            Response::databaseError('Failed to update vendor');
        }
    }

    /**
     * Delete vendor
     */
    public function delete($id) {
        // Check authentication
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            Response::unauthorized('You must be logged in to delete a vendor');
        }

        if (!$id || !is_numeric($id)) {
            Response::error('Invalid vendor ID', HTTP_BAD_REQUEST);
        }

        // Check if vendor exists
        $vendorModel = new Vendor();
        $vendor = $vendorModel->getById($id);

        if (!$vendor) {
            Response::notFound('Vendor not found');
        }

        // Check ownership (user can only delete their own vendors)
        if (!$vendorModel->isOwner($id, $_SESSION['user_id'])) {
            Response::forbidden('You can only delete vendors you created');
        }

        // Delete vendor
        $vendorModel->id = $id;
        
        if ($vendorModel->delete()) {
            Response::success(null, SUCCESS_DELETED);
        } else {
            Response::databaseError('Failed to delete vendor');
        }
    }

    /**
     * Get vendors by current logged-in user
     */
    public function myVendors() {
        // Check authentication
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            Response::unauthorized('You must be logged in');
        }

        $vendorModel = new Vendor();
        $vendors = $vendorModel->getByUserId($_SESSION['user_id']);

        Response::success($vendors);
    }

    /**
     * Search vendors
     */
    public function search() {
        $searchTerm = isset($_GET['q']) ? $_GET['q'] : '';

        if (empty($searchTerm)) {
            Response::error('Search term is required', HTTP_BAD_REQUEST);
        }

        $vendorModel = new Vendor();
        $vendors = $vendorModel->search($searchTerm);

        Response::success($vendors);
    }
}

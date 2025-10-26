<?php
/**
 * Main API Entry Point
 * Routes all API requests
 */

// Configure session for cross-origin requests
session_set_cookie_params([
    'lifetime' => 86400, // 24 hours
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Start session
session_start();

// Load configuration and dependencies
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/CorsMiddleware.php';
require_once __DIR__ . '/../utils/Response.php';

// Handle CORS
CorsMiddleware::handle();

// Get request method and path
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Parse the request URI - decode URL encoding first
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$urlPath = parse_url($requestUri, PHP_URL_PATH);
$urlPath = urldecode($urlPath); // Decode %20 to spaces
$path = str_replace($scriptName, '', $urlPath);
$path = trim($path, '/');

// Split path into segments
$segments = explode('/', $path);
$endpoint = isset($segments[0]) ? $segments[0] : '';
$id = isset($segments[1]) && is_numeric($segments[1]) ? (int)$segments[1] : null;

// Route the request
try {
    switch ($endpoint) {
        case 'login':
            if ($requestMethod === 'POST') {
                require_once __DIR__ . '/../controllers/AuthController.php';
                $controller = new AuthController();
                $controller->login();
            } else {
                Response::error('Method not allowed', 405);
            }
            break;

        case 'logout':
            if ($requestMethod === 'POST') {
                require_once __DIR__ . '/../controllers/AuthController.php';
                $controller = new AuthController();
                $controller->logout();
            } else {
                Response::error('Method not allowed', 405);
            }
            break;

        case 'me':
            if ($requestMethod === 'GET') {
                require_once __DIR__ . '/../controllers/AuthController.php';
                $controller = new AuthController();
                $controller->me();
            } else {
                Response::error('Method not allowed', 405);
            }
            break;

        case 'check':
            if ($requestMethod === 'GET') {
                require_once __DIR__ . '/../controllers/AuthController.php';
                $controller = new AuthController();
                $controller->check();
            } else {
                Response::error('Method not allowed', 405);
            }
            break;

        case 'vendors':
            require_once __DIR__ . '/../controllers/VendorController.php';
            $controller = new VendorController();

            if ($requestMethod === 'GET' && $id) {
                // Get single vendor
                $controller->show($id);
            } elseif ($requestMethod === 'GET' && isset($_GET['my'])) {
                // Get user's vendors
                $controller->myVendors();
            } elseif ($requestMethod === 'GET' && isset($_GET['q'])) {
                // Search vendors
                $controller->search();
            } elseif ($requestMethod === 'GET') {
                // Get all vendors
                $controller->index();
            } elseif ($requestMethod === 'POST') {
                // Create vendor
                $controller->store();
            } elseif ($requestMethod === 'PUT' && $id) {
                // Update vendor
                $controller->update($id);
            } elseif ($requestMethod === 'DELETE' && $id) {
                // Delete vendor
                $controller->delete($id);
            } else {
                Response::error('Method not allowed', 405);
            }
            break;

        case 'inspections':
            require_once __DIR__ . '/../controllers/InspectionController.php';
            $controller = new InspectionController();

            if ($requestMethod === 'GET' && isset($_GET['vendor_id'])) {
                // Get inspections for a vendor
                $controller->getByVendor($_GET['vendor_id']);
            } elseif ($requestMethod === 'POST') {
                // Create inspection
                $controller->store();
            } else {
                Response::error('Method not allowed', 405);
            }
            break;

        case '':
            // API root
            Response::success([
                'name' => APP_NAME,
                'version' => APP_VERSION,
                'endpoints' => [
                    'POST /login' => 'Login user',
                    'POST /logout' => 'Logout user',
                    'GET /me' => 'Get current user',
                    'GET /check' => 'Check authentication status',
                    'GET /vendors' => 'Get all vendors',
                    'GET /vendors/{id}' => 'Get single vendor',
                    'GET /vendors?my=true' => 'Get my vendors',
                    'GET /vendors?q=search' => 'Search vendors',
                    'POST /vendors' => 'Create vendor',
                    'PUT /vendors/{id}' => 'Update vendor',
                    'DELETE /vendors/{id}' => 'Delete vendor',
                    'GET /inspections?vendor_id={id}' => 'Get vendor inspections',
                    'POST /inspections' => 'Create inspection report'
                ]
            ], 'SafeBite Tracker API');
            break;

        default:
            Response::notFound('Endpoint not found');
            break;
    }
} catch (Exception $e) {
    Response::error('Internal server error: ' . $e->getMessage(), HTTP_INTERNAL_ERROR);
}

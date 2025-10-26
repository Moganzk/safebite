<?php
/**
 * Application Constants
 * SafeBite Tracker - Food Safety Inspection System
 */

// Application Settings
define('APP_NAME', 'SafeBite Tracker');
define('APP_VERSION', '1.0.0');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost:3000');

// Security Settings
define('SESSION_LIFETIME', 3600); // 1 hour in seconds
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes

// User Roles
define('ROLE_INSPECTOR', 'inspector');
define('ROLE_SUPERVISOR', 'supervisor');
define('ROLE_ADMIN', 'admin');

// Vendor Types
define('VENDOR_RESTAURANT', 'restaurant');
define('VENDOR_STREET_FOOD', 'street_food');
define('VENDOR_CAFE', 'cafe');
define('VENDOR_CATERING', 'catering');
define('VENDOR_OTHER', 'other');

// File Upload Settings
define('UPLOAD_MAX_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg']);
define('UPLOAD_PATH', dirname(__DIR__) . '/uploads/');

// Pagination
define('ITEMS_PER_PAGE', 10);

// Date Format
define('DATE_FORMAT', 'Y-m-d H:i:s');
define('DISPLAY_DATE_FORMAT', 'd/m/Y H:i');

// API Response Codes
define('HTTP_OK', 200);
define('HTTP_CREATED', 201);
define('HTTP_BAD_REQUEST', 400);
define('HTTP_UNAUTHORIZED', 401);
define('HTTP_FORBIDDEN', 403);
define('HTTP_NOT_FOUND', 404);
define('HTTP_INTERNAL_ERROR', 500);

// Error Messages
define('ERROR_INVALID_CREDENTIALS', 'Invalid username or password');
define('ERROR_UNAUTHORIZED', 'You are not authorized to perform this action');
define('ERROR_NOT_FOUND', 'Resource not found');
define('ERROR_VALIDATION_FAILED', 'Validation failed');
define('ERROR_DATABASE', 'Database error occurred');

// Success Messages
define('SUCCESS_LOGIN', 'Login successful');
define('SUCCESS_LOGOUT', 'Logout successful');
define('SUCCESS_CREATED', 'Record created successfully');
define('SUCCESS_UPDATED', 'Record updated successfully');
define('SUCCESS_DELETED', 'Record deleted successfully');

// CORS Settings
define('CORS_ALLOWED_ORIGINS', [
    'http://localhost:3000',
    'http://localhost:8080',
    'http://127.0.0.1:3000'
]);

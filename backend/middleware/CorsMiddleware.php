<?php
/**
 * CORS Middleware
 * Handles Cross-Origin Resource Sharing
 */

class CorsMiddleware {
    /**
     * Set CORS headers
     */
    public static function handle() {
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        // Check if origin is allowed
        if (in_array($origin, CORS_ALLOWED_ORIGINS)) {
            header("Access-Control-Allow-Origin: $origin");
        } else {
            header("Access-Control-Allow-Origin: " . CORS_ALLOWED_ORIGINS[0]);
        }

        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Max-Age: 86400"); // Cache preflight for 24 hours

        // Handle OPTIONS request (preflight)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}

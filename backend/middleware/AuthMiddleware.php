<?php
/**
 * Authentication Middleware
 * Checks if user is authenticated
 */

class AuthMiddleware {
    /**
     * Check if user is authenticated
     * @return bool
     */
    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Require authentication or return error
     */
    public static function require() {
        if (!self::check()) {
            require_once __DIR__ . '/../utils/Response.php';
            Response::unauthorized('Authentication required');
        }

        // Check session timeout
        if (isset($_SESSION['login_time'])) {
            $elapsed = time() - $_SESSION['login_time'];
            if ($elapsed > SESSION_LIFETIME) {
                self::logout();
                Response::unauthorized('Session expired. Please login again');
            }
        }

        return true;
    }

    /**
     * Get current user ID
     * @return int|null
     */
    public static function userId() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }

    /**
     * Get current user role
     * @return string|null
     */
    public static function userRole() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['role']) ? $_SESSION['role'] : null;
    }

    /**
     * Check if user has specific role
     * @param string $role
     * @return bool
     */
    public static function hasRole($role) {
        return self::userRole() === $role;
    }

    /**
     * Require specific role
     * @param string|array $roles Single role or array of roles
     */
    public static function requireRole($roles) {
        self::require();

        $userRole = self::userRole();
        $allowedRoles = is_array($roles) ? $roles : [$roles];

        if (!in_array($userRole, $allowedRoles)) {
            require_once __DIR__ . '/../utils/Response.php';
            Response::forbidden('You do not have permission to access this resource');
        }
    }

    /**
     * Logout user
     */
    private static function logout() {
        $_SESSION = [];
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        session_destroy();
    }
}

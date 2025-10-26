<?php
/**
 * Authentication Controller
 * Handles user login and logout
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';

class AuthController {
    /**
     * Login user
     * Required fields: username, password
     */
    public function login() {
        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate input
        $validator = new Validator($data);
        $validator->required('username', 'Username is required')
                  ->required('password', 'Password is required');

        if ($validator->fails()) {
            Response::validationError($validator->errors());
        }

        $username = Validator::sanitize($data['username']);
        $password = $data['password'];

        // Verify credentials
        $userModel = new User();
        $user = $userModel->verifyLogin($username, $password);

        if (!$user) {
            Response::error(ERROR_INVALID_CREDENTIALS, HTTP_UNAUTHORIZED);
        }

        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Store user data in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();

        // Return success with user data
        Response::success([
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ], SUCCESS_LOGIN);
    }

    /**
     * Logout user
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Destroy session
        $_SESSION = [];
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        session_destroy();

        Response::success(null, SUCCESS_LOGOUT);
    }

    /**
     * Get current user info
     */
    public function me() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            Response::unauthorized('Not authenticated');
        }

        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);

        if (!$user) {
            Response::error('User not found', HTTP_NOT_FOUND);
        }

        Response::success([
            'user' => $user
        ]);
    }

    /**
     * Check if user is authenticated
     */
    public function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $isAuthenticated = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];

        Response::success([
            'authenticated' => $isAuthenticated,
            'user' => $isAuthenticated ? [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['role']
            ] : null
        ]);
    }
}

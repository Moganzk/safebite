<?php
/**
 * Password Helper Utility
 * Handles password hashing and verification
 */

class PasswordHelper {
    /**
     * Hash a password
     * @param string $password Plain text password
     * @return string Hashed password
     */
    public static function hash($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify a password against a hash
     * @param string $password Plain text password
     * @param string $hash Hashed password
     * @return bool True if password matches, false otherwise
     */
    public static function verify($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Check if password meets minimum requirements
     * @param string $password Password to validate
     * @return array ['valid' => bool, 'errors' => array]
     */
    public static function validate($password) {
        $errors = [];

        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            $errors[] = "Password must be at least " . PASSWORD_MIN_LENGTH . " characters long";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Generate a random password
     * @param int $length Password length
     * @return string Generated password
     */
    public static function generate($length = 12) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        $maxIndex = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $maxIndex)];
        }

        return $password;
    }
}

<?php
/**
 * Response Utility Class
 * Handles JSON API responses
 */

class Response {
    /**
     * Send JSON response
     * @param int $statusCode HTTP status code
     * @param array $data Response data
     */
    public static function json($statusCode, $data = []) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Send success response
     * @param mixed $data Response data
     * @param string $message Success message
     * @param int $statusCode HTTP status code
     */
    public static function success($data = null, $message = 'Success', $statusCode = HTTP_OK) {
        $response = [
            'success' => true,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        self::json($statusCode, $response);
    }

    /**
     * Send error response
     * @param string $message Error message
     * @param int $statusCode HTTP status code
     * @param mixed $errors Additional error details
     */
    public static function error($message = 'An error occurred', $statusCode = HTTP_BAD_REQUEST, $errors = null) {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        self::json($statusCode, $response);
    }

    /**
     * Send unauthorized response
     * @param string $message Error message
     */
    public static function unauthorized($message = ERROR_UNAUTHORIZED) {
        self::error($message, HTTP_UNAUTHORIZED);
    }

    /**
     * Send forbidden response
     * @param string $message Error message
     */
    public static function forbidden($message = ERROR_UNAUTHORIZED) {
        self::error($message, HTTP_FORBIDDEN);
    }

    /**
     * Send not found response
     * @param string $message Error message
     */
    public static function notFound($message = ERROR_NOT_FOUND) {
        self::error($message, HTTP_NOT_FOUND);
    }

    /**
     * Send validation error response
     * @param array $errors Validation errors
     * @param string $message Error message
     */
    public static function validationError($errors, $message = ERROR_VALIDATION_FAILED) {
        self::error($message, HTTP_BAD_REQUEST, $errors);
    }

    /**
     * Send database error response
     * @param string $message Error message
     */
    public static function databaseError($message = ERROR_DATABASE) {
        self::error($message, HTTP_INTERNAL_ERROR);
    }
}

<?php
/**
 * Validator Utility Class
 * Handles input validation
 */

class Validator {
    private $errors = [];
    private $data = [];

    /**
     * Constructor
     * @param array $data Data to validate
     */
    public function __construct($data = []) {
        $this->data = $data;
    }

    /**
     * Validate required field
     * @param string $field Field name
     * @param string $message Custom error message
     * @return self
     */
    public function required($field, $message = null) {
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field][] = $message ?: "$field is required";
        }
        return $this;
    }

    /**
     * Validate email format
     * @param string $field Field name
     * @param string $message Custom error message
     * @return self
     */
    public function email($field, $message = null) {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = $message ?: "$field must be a valid email address";
        }
        return $this;
    }

    /**
     * Validate minimum length
     * @param string $field Field name
     * @param int $length Minimum length
     * @param string $message Custom error message
     * @return self
     */
    public function minLength($field, $length, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field][] = $message ?: "$field must be at least $length characters";
        }
        return $this;
    }

    /**
     * Validate maximum length
     * @param string $field Field name
     * @param int $length Maximum length
     * @param string $message Custom error message
     * @return self
     */
    public function maxLength($field, $length, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field][] = $message ?: "$field must not exceed $length characters";
        }
        return $this;
    }

    /**
     * Validate that field matches another field
     * @param string $field Field name
     * @param string $matchField Field to match against
     * @param string $message Custom error message
     * @return self
     */
    public function matches($field, $matchField, $message = null) {
        if (isset($this->data[$field]) && isset($this->data[$matchField]) 
            && $this->data[$field] !== $this->data[$matchField]) {
            $this->errors[$field][] = $message ?: "$field must match $matchField";
        }
        return $this;
    }

    /**
     * Validate numeric value
     * @param string $field Field name
     * @param string $message Custom error message
     * @return self
     */
    public function numeric($field, $message = null) {
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field][] = $message ?: "$field must be numeric";
        }
        return $this;
    }

    /**
     * Validate value is in allowed list
     * @param string $field Field name
     * @param array $allowed Allowed values
     * @param string $message Custom error message
     * @return self
     */
    public function in($field, $allowed, $message = null) {
        if (isset($this->data[$field]) && !in_array($this->data[$field], $allowed)) {
            $this->errors[$field][] = $message ?: "$field must be one of: " . implode(', ', $allowed);
        }
        return $this;
    }

    /**
     * Check if validation passed
     * @return bool
     */
    public function passes() {
        return empty($this->errors);
    }

    /**
     * Check if validation failed
     * @return bool
     */
    public function fails() {
        return !$this->passes();
    }

    /**
     * Get validation errors
     * @return array
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * Get validated data
     * @return array
     */
    public function validated() {
        return $this->data;
    }

    /**
     * Sanitize string input
     * @param string $input Input to sanitize
     * @return string
     */
    public static function sanitize($input) {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
}

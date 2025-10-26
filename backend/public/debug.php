<?php
/**
 * Debug Backend Routing
 */

echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";

$scriptName = dirname($_SERVER['SCRIPT_NAME']);
echo "Script Name Dir: " . $scriptName . "\n";

$requestUri = $_SERVER['REQUEST_URI'];
$path = str_replace($scriptName, '', parse_url($requestUri, PHP_URL_PATH));
echo "Path after replacement: " . $path . "\n";

$path = trim($path, '/');
echo "Path after trim: " . $path . "\n";

$segments = explode('/', $path);
echo "Segments: " . print_r($segments, true) . "\n";

$endpoint = isset($segments[0]) ? $segments[0] : '';
echo "Endpoint: '" . $endpoint . "'\n";
?>
<?php
// Define custom session save path within the project to avoid server permission issues
$save_path = dirname(dirname(__DIR__)) . '/tmp/sessions';

// Create directory if it doesn't exist
if (!file_exists($save_path)) {
    mkdir($save_path, 0755, true);
}

// Set the session save path
session_save_path($save_path);

// Enable error reporting to catch any startup errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

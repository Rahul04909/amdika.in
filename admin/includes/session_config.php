<?php
// Define custom session save path within the project to avoid server permission issues
$save_path = dirname(dirname(__DIR__)) . '/tmp/sessions';

// Create directory if it doesn't exist
if (!file_exists($save_path)) {
    mkdir($save_path, 0755, true);
}

// Set session parameters only if no session is active
if (session_status() === PHP_SESSION_NONE) {
    // Set the session save path
    session_save_path($save_path);
    
    // Start the session
    session_start();
}
?>

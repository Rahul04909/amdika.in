<?php
// Initialize session
require_once 'includes/session_config.php';

// Unset all session values
$_SESSION = array();

// Get session parameters 
$params = session_get_cookie_params();

// Delete the actual cookie
setcookie(session_name(), '', time() - 42000, 
    $params["path"], $params["domain"], 
    $params["secure"], $params["httponly"]
);

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit;
?>

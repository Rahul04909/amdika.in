<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Basic path handling to find login.php
    // If we are in admin/ subdirectory, login is ../login.php, if in admin root, login is ./login.php
    // However, header location is relative to the requested URL.
    // Assuming this file is included in files located in /admin/ or /admin/subfolders/
    
    // Check where we are roughly
    $current_path = $_SERVER['PHP_SELF'];
    $path_parts = explode('/', trim($current_path, '/'));
    
    // Simple redirect - Adjust dependent on where this file is included from
    // For simplicity in this project (flat admin or 1 level deep):
    
    // We'll use a robust way assuming 'admin' is in the URL:
    // If we are in /amadika/admin/index.php -> login.php
    // If we are in /amadika/admin/products/manage.php -> ../login.php
    
    // Let's just define a ROOT constant or use relative paths based on depth.
    
    $depth = count(explode('/', dirname($_SERVER['PHP_SELF']))) - count(explode('/', $_SERVER['DOCUMENT_ROOT']));
    // This depth might be tricky.
    
    // Reliable Fallback: Absolute path from web root if we know it, or relative.
    // Since we are in WAMP wwith 'amadika' folder.
    header("Location: /admin/login.php"); 
    exit;
}
?>

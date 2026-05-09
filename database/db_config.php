<?php
// Database configuration
$host = 'localhost'; // Standard 20i/StackCP database host
$dbname = 'mineib_i1_amadika';
$username = 'mineib_i1_mineib';
$password = 'Rd14072003@./';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Detailed error reporting for debugging
    error_log("Connection failed (" . $conn->connect_errno . "): " . $conn->connect_error);
    die("❌ Connection failed: " . $conn->connect_error . " (Error No: " . $conn->connect_errno . "). Please verify if the database exists in 20i panel.");
}
?>
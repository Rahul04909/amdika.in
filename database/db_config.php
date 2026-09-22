<?php
// Database configuration
$host = 'localhost'; // Standard 20i/StackCP database host
$dbname = 'mineib_i1_amadika';
$username = 'mineib_i1_mineib';
$password = 'Rd14072003@./';

// Enable mysqli exceptions so they can be caught
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Try production connection
    $conn = new mysqli($host, $username, $password, $dbname);
} catch (mysqli_sql_exception $e) {
    // Local development fallback credentials
    $local_creds = [
        ['localhost', 'root', '', 'amadik_ecom'],
        ['localhost', 'root', '', 'amadika'],
        ['localhost', 'root', '', 'mineib_i1_amadika']
    ];
    
    $conn = null;
    foreach ($local_creds as $creds) {
        try {
            $conn = new mysqli($creds[0], $creds[1], $creds[2], $creds[3]);
            if (!$conn->connect_error) {
                break;
            }
        } catch (mysqli_sql_exception $local_e) {
            $conn = null;
        }
    }
    
    if (!$conn) {
        error_log("Connection failed: " . $e->getMessage());
        die("❌ Connection failed: " . $e->getMessage() . ". Please verify if database settings are correct.");
    }
}
?>
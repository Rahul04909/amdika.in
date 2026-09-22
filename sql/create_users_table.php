<?php
try {
    // Database configuration
    $host = 'localhost';
    $dbname = 'amadik_ecom';
    $username = 'amadik_ecom';
    $password = 'Rd14072003@./';

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Create Users Table
    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        mobile VARCHAR(15) NOT NULL,
        country VARCHAR(50) NOT NULL,
        state VARCHAR(50) NOT NULL,
        city VARCHAR(50) NOT NULL,
        pincode VARCHAR(20) NOT NULL,
        address TEXT NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql_users) === TRUE) {
        echo "Table 'users' created successfully.\n";
    } else {
        throw new Exception("Error creating users table: " . $conn->error);
    }

    $conn->close();

} catch (Throwable $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>

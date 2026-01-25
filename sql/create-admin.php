<?php
// Include database configuration
require_once '../database/db_config.php';

// SQL to create admins table
$sql_create_table = "CREATE TABLE IF NOT EXISTS admins (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_create_table) === TRUE) {
    echo "Table 'admins' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Default admin credentials
$admin_user = 'admin';
$admin_pass = 'admin123';
$hashed_pass = password_hash($admin_pass, PASSWORD_DEFAULT);

// Check if admin already exists
$check_sql = "SELECT id FROM admins WHERE username = '$admin_user'";
$result = $conn->query($check_sql);

if ($result->num_rows == 0) {
    // Insert default admin
    $sql_insert = "INSERT INTO admins (username, password) VALUES ('$admin_user', '$hashed_pass')";
    
    if ($conn->query($sql_insert) === TRUE) {
        echo "Default admin created successfully.<br>";
        echo "Username: " . $admin_user . "<br>";
        echo "Password: " . $admin_pass . "<br>";
    } else {
        echo "Error creating admin: " . $conn->error . "<br>";
    }
} else {
    echo "Default admin user already exists.<br>";
}

$conn->close();
?>

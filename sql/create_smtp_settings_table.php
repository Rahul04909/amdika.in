<?php
require_once __DIR__ . '/../database/db_config.php';

// SQL to create smtp_settings table
$sql = "CREATE TABLE IF NOT EXISTS smtp_settings (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    host VARCHAR(255) NOT NULL,
    port INT(5) NOT NULL DEFAULT 587,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    encryption ENUM('ssl', 'tls', 'none') DEFAULT 'tls',
    from_email VARCHAR(255) NOT NULL,
    from_name VARCHAR(255) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'smtp_settings' created successfully";
    
    // Insert default row if not exists
    $check = $conn->query("SELECT id FROM smtp_settings LIMIT 1");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO smtp_settings (host, port, username, password, encryption, from_email, from_name) 
                      VALUES ('smtp.gmail.com', 587, 'example@gmail.com', '', 'tls', 'example@gmail.com', 'Amadika')");
        echo " & Default settings inserted.";
    }
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>

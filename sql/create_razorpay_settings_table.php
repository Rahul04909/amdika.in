<?php
require_once __DIR__ . '/../database/db_config.php';

// SQL to create razorpay_settings table
$sql = "CREATE TABLE IF NOT EXISTS razorpay_settings (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key_id VARCHAR(255) NOT NULL,
    key_secret VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'inactive',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'razorpay_settings' created successfully";
    
    // Insert default row if not exists
    $check = $conn->query("SELECT id FROM razorpay_settings LIMIT 1");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO razorpay_settings (key_id, key_secret, status) 
                      VALUES ('rzp_test_placeholder', 'secret_placeholder', 'inactive')");
        echo " & Default settings inserted.";
    }
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>

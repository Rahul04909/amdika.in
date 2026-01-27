<?php
require_once __DIR__ . '/../database/db_config.php';

// SQL to create coupons table
$sql = "CREATE TABLE IF NOT EXISTS coupons (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_percent INT(3) NOT NULL,
    min_order_value DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    valid_till DATE NOT NULL,
    total_usage_limit INT(11) NOT NULL DEFAULT 0,
    used_count INT(11) NOT NULL DEFAULT 0,
    status ENUM('active', 'expired') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (code),
    INDEX (status)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'coupons' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>

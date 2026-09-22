<?php
require_once '../database/db_config.php';

$sql = "CREATE TABLE IF NOT EXISTS product_deletion_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    category_name VARCHAR(255),
    deleted_by_ip VARCHAR(45) NOT NULL,
    deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    full_snapshot JSON,
    INDEX (product_id),
    INDEX (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql)) {
    echo "✅ product_deletion_log table created successfully!";
} else {
    echo "❌ Error creating table: " . $conn->error;
}
?>

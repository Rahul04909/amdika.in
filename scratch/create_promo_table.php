<?php
require_once __DIR__ . '/../database/db_config.php';

$sql = "CREATE TABLE IF NOT EXISTS category_promos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    type ENUM('image', 'video') DEFAULT 'image',
    media_path VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255) NULL,
    link_url VARCHAR(255) NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table category_promos created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>

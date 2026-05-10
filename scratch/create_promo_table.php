<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting table creation script...<br>";

require_once __DIR__ . '/../database/db_config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Database connected successfully!<br>";

$sql = "CREATE TABLE IF NOT EXISTS category_promos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    type ENUM('image', 'video') DEFAULT 'image',
    media_path VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255) NULL,
    link_url VARCHAR(255) NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

if ($conn->query($sql) === TRUE) {
    echo "Table 'category_promos' created successfully!<br>";
    
    // Now try to add the Foreign Key separately
    $fk_sql = "ALTER TABLE category_promos ADD CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE CASCADE";
    if ($conn->query($fk_sql) === TRUE) {
        echo "Foreign Key constraint added successfully!<br>";
    } else {
        echo "Note: Foreign Key could not be added (it might already exist): " . $conn->error . "<br>";
    }
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}
?>

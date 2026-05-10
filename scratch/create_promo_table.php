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
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    category_id INT(11) NOT NULL,
    type ENUM('image', 'video') DEFAULT 'image',
    media_path VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255) NULL,
    link_url VARCHAR(255) NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

if ($conn->query($sql) === TRUE) {
    echo "Table 'category_promos' created successfully!<br>";
    
    // Use a try-catch for the Foreign Key to avoid Fatal Error
    try {
        $fk_sql = "ALTER TABLE category_promos ADD CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE CASCADE";
        if ($conn->query($fk_sql) === TRUE) {
            echo "Foreign Key constraint added successfully!<br>";
        } else {
            echo "Note: Foreign Key could not be added: " . $conn->error . "<br>";
            echo "Attempting with UNSIGNED type...<br>";
            
            // Try changing to UNSIGNED if that's the mismatch
            $conn->query("ALTER TABLE category_promos MODIFY category_id INT(11) UNSIGNED NOT NULL");
            if ($conn->query($fk_sql) === TRUE) {
                echo "Foreign Key added successfully with UNSIGNED type!<br>";
            } else {
                echo "Still failing: " . $conn->error . "<br>";
            }
        }
    } catch (mysqli_sql_exception $e) {
        echo "FK Error caught: " . $e->getMessage() . "<br>";
        echo "Continuing without FK for now...<br>";
    }
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}
?>

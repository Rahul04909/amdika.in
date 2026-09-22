<?php
require_once __DIR__ . '/../database/db_config.php';

try {
    // 1. Create Colors Table
    $sql_colors = "CREATE TABLE IF NOT EXISTS colors (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        hex_code VARCHAR(7) NOT NULL, -- e.g. #FFFFFF
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql_colors) === TRUE) {
        echo "Table 'colors' created successfully.\n";
    } else {
        throw new Exception("Error creating colors table: " . $conn->error);
    }

    // 2. Create Product Color Variants Table
    $sql_variants = "CREATE TABLE IF NOT EXISTS product_color_variants (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        product_id INT(11) UNSIGNED NOT NULL,
        color_id INT(11) UNSIGNED NOT NULL,
        price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
        image_path VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        FOREIGN KEY (color_id) REFERENCES colors(id) ON DELETE CASCADE
    )";

    if ($conn->query($sql_variants) === TRUE) {
        echo "Table 'product_color_variants' created successfully.\n";
    } else {
        throw new Exception("Error creating product_color_variants table: " . $conn->error);
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>

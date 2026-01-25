<?php
try {
    $host = 'localhost';
    $dbname = 'amadik_ecom';
    $username = 'amadik_ecom';
    $password = 'Rd14072003@./';

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $sql = "CREATE TABLE IF NOT EXISTS product_categories (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        slug VARCHAR(191) NOT NULL UNIQUE,
        image VARCHAR(255) DEFAULT NULL,
        description TEXT DEFAULT NULL,
        seo_title VARCHAR(255) DEFAULT NULL,
        seo_description VARCHAR(255) DEFAULT NULL,
        seo_keywords VARCHAR(255) DEFAULT NULL,
        seo_featured_image VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully.\n";
    } else {
        throw new Exception("Error creating table: " . $conn->error);
    }

    $conn->close();
} catch (Throwable $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>

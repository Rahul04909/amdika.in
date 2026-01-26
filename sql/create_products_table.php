<?php
try {
    // Direct connection to avoid include path issues in CLI
    $host = 'localhost';
    $dbname = 'amadik_ecom';
    $username = 'amadik_ecom';
    $password = 'Rd14072003@./';

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // 1. Create Products Table
    $sql_products = "CREATE TABLE IF NOT EXISTS products (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        category_id INT(11) UNSIGNED NOT NULL,
        name VARCHAR(255) NOT NULL,
        slug VARCHAR(191) NOT NULL UNIQUE,
        description TEXT DEFAULT NULL,
        
        -- Media
        featured_image VARCHAR(255) DEFAULT NULL,
        gallery_images JSON DEFAULT NULL,
        video_url VARCHAR(255) DEFAULT NULL,
        
        -- Pricing
        mrp DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
        sale_price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
        discount_percent INT(3) DEFAULT 0,
        
        -- SEO
        seo_title VARCHAR(255) DEFAULT NULL,
        seo_description VARCHAR(255) DEFAULT NULL,
        seo_keywords VARCHAR(255) DEFAULT NULL,
        schema_markup JSON DEFAULT NULL,
        
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE CASCADE
    )";

    if ($conn->query($sql_products) === TRUE) {
        echo "Table 'products' created successfully.\n";
    } else {
        throw new Exception("Error creating products table: " . $conn->error);
    }

    // 2. Create Product Reviews Table
    $sql_reviews = "CREATE TABLE IF NOT EXISTS product_reviews (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        product_id INT(11) UNSIGNED NOT NULL,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        rating INT(1) NOT NULL,
        message TEXT DEFAULT NULL,
        image VARCHAR(255) DEFAULT NULL,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if ($conn->query($sql_reviews) === TRUE) {
        echo "Table 'product_reviews' created successfully.\n";
    } else {
        throw new Exception("Error creating product_reviews table: " . $conn->error);
    }

    $conn->close();

} catch (Throwable $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>

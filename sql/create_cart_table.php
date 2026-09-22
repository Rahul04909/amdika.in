<?php
try {
    // Database configuration
    $host = 'localhost';
    $dbname = 'amadik_ecom';
    $username = 'amadik_ecom';
    $password = 'Rd14072003@./';

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Create Cart Table
    // We use session_id for guest carts, and user_id (nullable) if they log in later.
    $sql_cart = "CREATE TABLE IF NOT EXISTS cart (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(255) NOT NULL,
        user_id INT(11) UNSIGNED DEFAULT NULL,
        product_id INT(11) UNSIGNED NOT NULL,
        quantity INT(11) UNSIGNED NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if ($conn->query($sql_cart) === TRUE) {
        echo "Table 'cart' created successfully.\n";
    } else {
        throw new Exception("Error creating cart table: " . $conn->error);
    }

    $conn->close();

} catch (Throwable $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>

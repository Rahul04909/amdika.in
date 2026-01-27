<?php
require_once __DIR__ . '/../database/db_config.php';

// SQL to create orders table
$sql_orders = "CREATE TABLE IF NOT EXISTS orders (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_no VARCHAR(50) NOT NULL UNIQUE,
    user_id INT(11) UNSIGNED NOT NULL,
    total_sale_price DECIMAL(10,2) NOT NULL,
    total_gst DECIMAL(10,2) NOT NULL,
    delivery_charge DECIMAL(10,2) NOT NULL DEFAULT 60.00,
    coupon_code VARCHAR(50) DEFAULT NULL,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    final_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    payment_id VARCHAR(100) DEFAULT NULL,
    address_details JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql_orders) === TRUE) {
    echo "Table 'orders' created successfully.<br>";
} else {
    echo "Error creating table 'orders': " . $conn->error . "<br>";
}

// SQL to create order_items table
$sql_items = "CREATE TABLE IF NOT EXISTS order_items (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT(11) UNSIGNED NOT NULL,
    product_id INT(11) UNSIGNED NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT(11) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    gst_percent INT(3) NOT NULL,
    gst_amount DECIMAL(10,2) NOT NULL,
    total_line_amount DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
)";

if ($conn->query($sql_items) === TRUE) {
    echo "Table 'order_items' created successfully.<br>";
} else {
    echo "Error creating table 'order_items': " . $conn->error . "<br>";
}

$conn->close();
?>

<?php
require_once __DIR__ . '/../database/db_config.php';

echo "Starting Database Verification...\n";

try {
    // 1. Check colors table for hex_code column
    $res = $conn->query("SHOW COLUMNS FROM colors LIKE 'hex_code'");
    if ($res->num_rows == 0) {
        $conn->query("ALTER TABLE colors ADD COLUMN hex_code VARCHAR(7) AFTER name");
        echo "Added 'hex_code' to 'colors' table.\n";
    } else {
        echo "'colors' table is correct.\n";
    }

    // 2. Check cart table for color_id column
    $res = $conn->query("SHOW COLUMNS FROM cart LIKE 'color_id'");
    if ($res->num_rows == 0) {
        $conn->query("ALTER TABLE cart ADD COLUMN color_id INT(11) UNSIGNED DEFAULT NULL AFTER product_id");
        $conn->query("ALTER TABLE cart ADD CONSTRAINT fk_cart_color FOREIGN KEY (color_id) REFERENCES colors(id) ON DELETE SET NULL");
        echo "Added 'color_id' to 'cart' table.\n";
    } else {
        echo "'cart' table is correct.\n";
    }

    // 3. Check order_items table for color_name column
    $res = $conn->query("SHOW COLUMNS FROM order_items LIKE 'color_name'");
    if ($res->num_rows == 0) {
        $conn->query("ALTER TABLE order_items ADD COLUMN color_name VARCHAR(100) DEFAULT NULL AFTER product_name");
        echo "Added 'color_name' to 'order_items' table.\n";
    } else {
        echo "'order_items' table is correct.\n";
    }

    echo "Verification Complete. All tables are in sync.\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>

<?php
require_once __DIR__ . '/../database/db_config.php';

try {
    $sql = "ALTER TABLE order_items ADD COLUMN color_name VARCHAR(100) DEFAULT NULL AFTER product_name";

    if ($conn->query($sql) === TRUE) {
        echo "Table 'order_items' updated with 'color_name' column successfully.\n";
    } else {
        throw new Exception("Error updating order_items table: " . $conn->error);
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>

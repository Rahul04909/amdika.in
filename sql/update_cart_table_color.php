<?php
require_once __DIR__ . '/../database/db_config.php';

try {
    $sql = "ALTER TABLE cart ADD COLUMN color_id INT(11) UNSIGNED DEFAULT NULL AFTER product_id,
            ADD CONSTRAINT fk_cart_color FOREIGN KEY (color_id) REFERENCES colors(id) ON DELETE SET NULL";

    if ($conn->query($sql) === TRUE) {
        echo "Table 'cart' updated with 'color_id' column successfully.\n";
    } else {
        throw new Exception("Error updating cart table: " . $conn->error);
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>

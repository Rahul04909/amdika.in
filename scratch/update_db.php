<?php
require_once 'database/db_config.php';
$sql = "ALTER TABLE product_color_variants ADD COLUMN IF NOT EXISTS gallery_images TEXT NULL AFTER image_path";
if ($conn->query($sql)) {
    echo "Column added successfully";
} else {
    echo "Error adding column: " . $conn->error;
}
?>

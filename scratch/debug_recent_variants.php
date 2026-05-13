<?php
require_once '../database/db_config.php';
$res = $conn->query("SELECT p.name, v.* FROM product_color_variants v JOIN products p ON v.product_id = p.id ORDER BY v.id DESC LIMIT 5");
while($row = $res->fetch_assoc()) {
    echo "Product: " . $row['name'] . "\n";
    echo "Image Path: " . $row['image_path'] . "\n";
    echo "Gallery: " . $row['gallery_images'] . "\n";
    echo "-------------------\n";
}
?>

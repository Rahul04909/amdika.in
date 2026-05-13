<?php
require_once '../database/db_config.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id) {
    $res = $conn->query("SELECT * FROM product_color_variants WHERE product_id = $id");
    while($row = $res->fetch_assoc()) {
        echo "Variant ID: " . $row['id'] . "\n";
        echo "Image Path: " . $row['image_path'] . "\n";
        echo "Gallery: " . $row['gallery_images'] . "\n";
        echo "-------------------\n";
    }
} else {
    echo "Provide id param";
}
?>

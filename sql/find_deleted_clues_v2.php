<?php
require_once '../database/db_config.php';

echo "<h2>Searching for Deleted Product Clues (Advanced)...</h2>";

// Check Variants for missing products
echo "<h3>Checking variants of deleted products:</h3>";
$sql = "SELECT DISTINCT product_id FROM product_color_variants WHERE product_id NOT IN (SELECT id FROM products)";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #eee;'><th>Deleted Product ID</th><th>Variant Details Found</th></tr>";
    while ($row = $res->fetch_assoc()) {
        $pid = $row['product_id'];
        $v_res = $conn->query("SELECT * FROM product_color_variants WHERE product_id = $pid");
        echo "<tr><td>$pid</td><td><ul>";
        while($v = $v_res->fetch_assoc()){
            echo "<li>Price: " . $v['price'] . " | Image: " . $v['image_path'] . "</li>";
        }
        echo "</ul></td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No traces found in variants table.</p>";
}
?>

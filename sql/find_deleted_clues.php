<?php
require_once '../database/db_config.php';

echo "<h2>Searching for Deleted Product Clues...</h2>";

// 1. Check Order Items for missing products
echo "<h3>1. Finding products from Order History:</h3>";
$sql = "SELECT DISTINCT product_id FROM order_items WHERE product_id NOT IN (SELECT id FROM products)";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #eee;'><th>ID</th><th>Found in Order Items?</th><th>Names Found in Orders</th></tr>";
    while ($row = $res->fetch_assoc()) {
        $pid = $row['product_id'];
        // Try to get the name from order_items (if stored there) or just show the ID
        $name_res = $conn->query("SELECT product_name FROM order_items WHERE product_id = $pid LIMIT 1");
        $pname = ($name_res && $name_res->num_rows > 0) ? $name_res->fetch_assoc()['product_name'] : "Unknown Name";
        
        echo "<tr><td>$pid</td><td>YES</td><td>$pname</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No clues found in order history.</p>";
}

// 2. Check for "Orphaned" images (Images exist but product doesn't)
echo "<h3>2. Checking for orphaned images (potential clues):</h3>";
$dir = "../assets/images/products/";
if (is_dir($dir)) {
    $files = scandir($dir);
    echo "<ul>";
    $count = 0;
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        $path = "assets/images/products/" . $file;
        $check = $conn->query("SELECT id FROM products WHERE featured_image = '$path'");
        if ($check->num_rows == 0) {
            echo "<li>Orphaned Image: <b>$file</b> (No product uses this)</li>";
            $count++;
        }
    }
    if ($count == 0) echo "<li>No orphaned images found.</li>";
    echo "</ul>";
}
?>

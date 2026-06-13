<?php
require_once __DIR__ . '/../database/db_config.php';

echo "CATEGORIES:\n";
$res = $conn->query("SELECT id, name, slug FROM product_categories");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo " - ID: {$row['id']} | Name: {$row['name']} | Slug: {$row['slug']}\n";
    }
}

echo "\nPRODUCTS (Recent 5):\n";
$res = $conn->query("SELECT id, name, slug, category_id, sale_price FROM products ORDER BY id DESC LIMIT 5");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo " - ID: {$row['id']} | Name: {$row['name']} | Slug: {$row['slug']} | CatID: {$row['category_id']}\n";
    }
}
?>

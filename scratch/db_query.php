<?php
require_once __DIR__ . '/../database/db_config.php';

echo "PRODUCTS TABLE COLUMNS:\n";
$res = $conn->query("DESCRIBE products");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "Field: {$row['Field']} | Type: {$row['Type']} | Null: {$row['Null']} | Key: {$row['Key']}\n";
    }
}
?>

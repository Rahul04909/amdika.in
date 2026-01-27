<?php
require_once __DIR__ . '/../database/db_config.php';

// SQL to add gst_percent column
$sql = "ALTER TABLE products ADD COLUMN gst_percent INT(3) NOT NULL DEFAULT 18 AFTER sale_price";

if ($conn->query($sql) === TRUE) {
    echo "Column 'gst_percent' added successfully to 'products' table.";
} else {
    // Check if duplicate column error to avoid alarming user on re-runs
    if (strpos($conn->error, 'Duplicate column name') !== false) {
         echo "Column 'gst_percent' already exists.";
    } else {
        echo "Error updating table: " . $conn->error;
    }
}

$conn->close();
?>

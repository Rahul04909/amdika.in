<?php
require_once 'database/db_config.php';

$sql = "CREATE TABLE IF NOT EXISTS best_deals_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_ids TEXT, /* JSON array or comma-separated string of category IDs */
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'best_deals_settings' created successfully";
    
    // Insert default row if not exists
    $check = $conn->query("SELECT * FROM best_deals_settings LIMIT 1");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO best_deals_settings (category_ids) VALUES ('[]')");
        echo " and default row inserted.";
    }
} else {
    echo "Error creating table: " . $conn->error;
}
?>

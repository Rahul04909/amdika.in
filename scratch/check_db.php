<?php
require_once '../database/db_config.php';
$res = $conn->query("DESCRIBE product_color_variants");
while($row = $res->fetch_assoc()) 
    {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>

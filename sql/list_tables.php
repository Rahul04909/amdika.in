<?php
require_once '../database/db_config.php';
$res = $conn->query("SHOW TABLES");
while($row = $res->fetch_row()) {
    echo $row[0] . "\n";
}
?>

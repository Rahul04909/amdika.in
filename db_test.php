<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h3>Database Diagnostic Test (MariaDB/20i)</h3>";

$dbname = 'amadik_ecom-313439a91d';
$username = 'amadik_ecom-313439a91d';
$password = 'Rd14072003@./';

$tests = [
    'localhost' => 'localhost',
    '127.0.0.1' => '127.0.0.1',
    'mysql.stackcp.com' => 'mysql.stackcp.com',
    'shareddb-cluster.host' => 'shareddb-cluster.host' // Placeholder for common variations
];

// If they have a specific host in their panel, they should add it here.
// But we'll try mysql.stackcp.com which is the most common for 20i.

foreach ($tests as $label => $host) {
    echo "Testing <b>$label</b>... ";
    try {
        $conn = @new mysqli($host, $username, $password, $dbname);
        if ($conn->connect_error) {
            echo "<span style='color:red;'>FAILED</span> (".$conn->connect_errno.": ".$conn->connect_error.")<br>";
        } else {
            echo "<span style='color:green;'>SUCCESS!</span> Using host: $host <br>";
            echo "Server Info: " . $conn->server_info . "<br>";
            $conn->close();
        }
    } catch (Exception $e) {
        echo "<span style='color:red;'>EXCEPTION</span>: " . $e->getMessage() . "<br>";
    }
}

echo "<h4>Environment Info:</h4>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "<p><i>Note: On 20i, the hostname is usually <b>mysql.stackcp.com</b> or a specific cluster name found in your My20i panel under 'MySQL Databases'.</i></p>";
?>

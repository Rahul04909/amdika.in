<?php
try {
    // Include database connection (relative to sql folder)
    require_once __DIR__ . '/../database/db_config.php';

    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Database connection not established.");
    }

    // Create Visitor Logs Table
    $sql_visitor_logs = "CREATE TABLE IF NOT EXISTS visitor_logs (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(255) NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        user_agent TEXT DEFAULT NULL,
        device_type VARCHAR(50) DEFAULT 'Desktop',
        browser VARCHAR(100) DEFAULT 'Unknown',
        os VARCHAR(100) DEFAULT 'Unknown',
        page_url VARCHAR(255) DEFAULT NULL,
        referrer VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY idx_created_at (created_at),
        KEY idx_session_id (session_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    if ($conn->query($sql_visitor_logs) === TRUE) {
        echo "Table 'visitor_logs' created successfully.\n";
    } else {
        throw new Exception("Error creating visitor_logs table: " . $conn->error);
    }

    $conn->close();

} catch (Throwable $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>

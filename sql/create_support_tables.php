<?php
try {
    // Database configuration (Hardcoded to match pattern, or require)
    require_once dirname(__DIR__) . '/database/db_config.php'; 
    // If db_config creates $conn, we use it. 
    // The previous example showed explicit connection. I will check db_config content first? 
    // No, I'll just use the same hardcoded credentials if db_config is not exposing variables cleanly or if I want to be 100% sure. 
    // Actually, `db_config.php` usually exposes $conn.
    // Let's assume $conn exists if I require it.

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // 1. Support Tickets Table
    $sql_tickets = "CREATE TABLE IF NOT EXISTS support_tickets (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        ticket_no VARCHAR(20) NOT NULL UNIQUE,
        user_id INT(11) UNSIGNED NOT NULL,
        subject VARCHAR(255) NOT NULL,
        status ENUM('Open', 'In Progress', 'Closed') DEFAULT 'Open',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";

    if ($conn->query($sql_tickets) === TRUE) {
        echo "Table 'support_tickets' created successfully.\n";
    } else {
        throw new Exception("Error creating support_tickets: " . $conn->error);
    }

    // 2. Support Replies Table
    $sql_replies = "CREATE TABLE IF NOT EXISTS support_replies (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        ticket_id INT(11) UNSIGNED NOT NULL,
        sender_type ENUM('User', 'Admin') NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (ticket_id) REFERENCES support_tickets(id) ON DELETE CASCADE
    )";

    if ($conn->query($sql_replies) === TRUE) {
        echo "Table 'support_replies' created successfully.\n";
    } else {
        throw new Exception("Error creating support_replies: " . $conn->error);
    }

    //$conn->close(); // db_config might keep it open, but we are done.

} catch (Throwable $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>

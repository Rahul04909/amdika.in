<?php
// If run via browser, require auth. If run via CLI, allow it without web session auth.
if (php_sapi_name() !== 'cli') {
    require_once __DIR__ . '/../../admin/includes/auth.php';
}
require_once __DIR__ . '/../../database/db_config.php';

// SQL to create blogs table
$sql = "CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    summary TEXT NULL,
    content LONGTEXT NULL,
    featured_image VARCHAR(255) DEFAULT NULL,
    author VARCHAR(100) DEFAULT 'Admin',
    seo_title VARCHAR(255) DEFAULT NULL,
    seo_description VARCHAR(255) DEFAULT NULL,
    seo_keywords VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($conn->query($sql)) {
    echo "✅ Blogs table set up successfully!\n";
} else {
    echo "❌ Error setting up blogs table: " . $conn->error . "\n";
}
?>

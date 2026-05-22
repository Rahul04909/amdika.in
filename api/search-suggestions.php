<?php
header('Content-Type: application/json');
require_once '../database/db_config.php';
require_once '../includes/image_helper.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$search = "%$query%";
$sql = "SELECT id, name, slug, featured_image, sale_price FROM products 
        WHERE status = 'active' AND (name LIKE ? OR description LIKE ?) 
        ORDER BY name ASC LIMIT 10";

$conn->set_charset("utf8mb4");

try {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'Database error']);
        exit;
    }

    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = [];
while ($row = $result->fetch_assoc()) {
    $img = !empty($row['featured_image']) ? $row['featured_image'] : 'assets/images/placeholder.jpg';
    $resized_img = get_resized_image($img, 100, 100, 'cover');
    
        $suggestions[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'slug' => $row['slug'],
            'price' => '₹' . number_with_commas((float)$row['sale_price']),
            'image' => $resized_img
        ];
    }

    echo json_encode($suggestions);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

function number_with_commas($number) {
    return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $number);
}

<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
require_once '../database/db_config.php';

// Support both FormData/POST and raw JSON inputs
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Only POST is allowed.']);
    exit;
}

// Parse request data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$company = isset($_POST['company']) ? trim($_POST['company']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;
$quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : null;
$occasion = isset($_POST['occasion']) ? trim($_POST['occasion']) : null;
$message = isset($_POST['message']) ? trim($_POST['message']) : null;

// Fallback to JSON payload if $_POST is empty
if (empty($name) && empty($company) && empty($email)) {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if ($data) {
        $name = isset($data['name']) ? trim($data['name']) : '';
        $company = isset($data['company']) ? trim($data['company']) : '';
        $email = isset($data['email']) ? trim($data['email']) : '';
        $phone = isset($data['phone']) ? trim($data['phone']) : null;
        $quantity = isset($data['quantity']) ? trim($data['quantity']) : null;
        $occasion = isset($data['occasion']) ? trim($data['occasion']) : null;
        $message = isset($data['message']) ? trim($data['message']) : null;
    }
}

// Server-side validation
if (empty($name)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter your name.']);
    exit;
}
if (empty($company)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter your company name.']);
    exit;
}
if (empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter your email address.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO `corporate_gift_proposals` (`name`, `company`, `email`, `phone`, `quantity`, `occasion`, `message`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare database statement.']);
        exit;
    }
    
    $stmt->bind_param("sssssss", $name, $company, $email, $phone, $quantity, $occasion, $message);
    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => "Thank you, {$name}. We have received your B2B inquiry for {$company} and will get back to you shortly."
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to execute database statement: ' . $stmt->error]);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'An error occurred while saving your request: ' . $e->getMessage()]);
}
?>

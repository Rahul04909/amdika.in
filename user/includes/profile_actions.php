<?php
require_once dirname(dirname(__DIR__)) . '/includes/session_config.php';
require_once dirname(dirname(__DIR__)) . '/database/db_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'update_info') {
    $field = $_POST['field'] ?? '';
    $value = trim($_POST['value'] ?? '');

    if (empty($field) || empty($value)) {
        echo json_encode(['status' => 'error', 'message' => 'Value cannot be empty']);
        exit;
    }

    $allowed_fields = ['name', 'email', 'mobile'];
    if (!in_array($field, $allowed_fields)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid field']);
        exit;
    }

    // specific validation
    if ($field === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
        exit;
    }
    if ($field === 'mobile' && !preg_match('/^[0-9]{10}$/', $value)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid mobile number']);
        exit;
    }

    // Check unique if email or mobile
    if ($field === 'email' || $field === 'mobile') {
        $check = $conn->prepare("SELECT id FROM users WHERE $field = ? AND id != ?");
        $check->bind_param("si", $value, $user_id);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
             echo json_encode(['status' => 'error', 'message' => "$field already exists"]);
             exit;
        }
    }

    // Update
    $stmt = $conn->prepare("UPDATE users SET $field = ? WHERE id = ?");
    $stmt->bind_param("si", $value, $user_id);
    
    if ($stmt->execute()) {
        if ($field === 'name') {
            $_SESSION['user_name'] = $value;
        }
        echo json_encode(['status' => 'success', 'message' => 'Updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);

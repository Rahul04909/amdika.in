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

if ($action === 'change_password') {
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    if ($new_pass !== $confirm_pass) {
        echo json_encode(['status' => 'error', 'message' => 'New passwords do not match']);
        exit;
    }

    if (strlen($new_pass) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters']);
        exit;
    }

    // Verify Old Password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit;
    }

    $row = $res->fetch_assoc();
    if (!password_verify($current_pass, $row['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Incorrect current password']);
        exit;
    }

    // Update Password
    $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update->bind_param("si", $new_hash, $user_id);
    
    if ($update->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Password changed successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);

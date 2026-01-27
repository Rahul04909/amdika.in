<?php
ob_start();
session_start();
require_once '../database/db_config.php';

// Clear output
ob_clean();
header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'register') {
    // Sanitize Inputs
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $mobile = $conn->real_escape_string(trim($_POST['mobile']));
    $address = $conn->real_escape_string(trim($_POST['address']));
    $city = $conn->real_escape_string(trim($_POST['city']));
    $pincode = $conn->real_escape_string(trim($_POST['pincode']));
    $state = $conn->real_escape_string(trim($_POST['state']));
    $country = $conn->real_escape_string(trim($_POST['country']));
    $password = $_POST['password'];

    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Required fields missing']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
        exit;
    }

    if (strlen($password) < 8) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters']);
        exit;
    }

    // Check if Email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already registered. Please login.']);
        exit;
    }

    // Hash Password
    $hashed_pass = password_hash($password, PASSWORD_BCRYPT);

    // Insert User
    $sql = "INSERT INTO users (name, email, mobile, address, city, pincode, state, country, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $name, $email, $mobile, $address, $city, $pincode, $state, $country, $hashed_pass);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed: ' . $stmt->error]);
    }
} 
elseif ($action === 'login') {
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter email and password']);
        exit;
    }

    // Check User
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Login Success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Update Cart with User ID (Merge session cart to user)
            $session_id = session_id();
            if ($session_id) {
                // Determine if we should merge or just assign. For now, assign existing session cart to user.
                $update_cart = $conn->prepare("UPDATE cart SET user_id = ? WHERE session_id = ?");
                $update_cart->bind_param("is", $user['id'], $session_id);
                $update_cart->execute();
            }

            echo json_encode(['status' => 'success', 'message' => 'Login successful', 'redirect' => 'index.php']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
}
else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>

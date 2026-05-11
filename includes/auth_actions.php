<?php
ob_start();
require_once 'session_config.php';
require_once '../database/db_config.php';

// Clear output
ob_clean();
header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : '';

// --- SMS API Helper ---
function sendOTP($mobile, $otp) {
    $user = "MINEIB";
    $authkey = "925eZtQUZpM";
    $sender = "DIAWOR";
    $entityid = "1401455390000019913";
    $templateid = "1707177832194767263";
    $params = [
        'user' => $user,
        'authkey' => $authkey,
        'sender' => $sender,
        'mobile' => $mobile,
        'text' => "Dear User, Your OTP for login to your $otp. Do not share this OTP with anyone for security reasons. OTP valid for 10 minutes. Metait",
        'entityid' => $entityid,
        'templateid' => $templateid
    ];
    $url = "https://amazesms.in/api/pushsms?" . http_build_query($params);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        $response = "CURL Error: " . $err;
    }
    
    // Debug log
    $log = date('Y-m-d H:i:s') . " | Mobile: $mobile | Response: $response\n";
    file_put_contents(__DIR__ . '/sms_debug.log', $log, FILE_APPEND);
    
    return $response;
}

if ($action === 'send_otp') {
    $mobile = $conn->real_escape_string(trim($_POST['mobile']));
    
    if (empty($mobile) || strlen($mobile) != 10) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a valid 10-digit mobile number']);
        exit;
    }

    $otp = rand(100000, 999999);
    $_SESSION['temp_otp'] = $otp;
    $_SESSION['temp_mobile'] = $mobile;
    $_SESSION['otp_time'] = time();

    error_log("Amadika Auth: send_otp action for $mobile");
    $api_res = sendOTP($mobile, $otp);
    error_log("Amadika Auth: sendOTP result: " . $api_res);
    
    // Check for common success indicators or just non-empty response if not explicitly 'error'
    if ($api_res && stripos($api_res, 'error') === false && stripos($api_res, 'fail') === false) {
        echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP. Please try again. Raw: ' . strip_tags($api_res)]);
    }
} 
elseif ($action === 'send_register_otp') {
    $mobile = $conn->real_escape_string(trim($_POST['mobile']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    error_log("Amadika Auth: send_register_otp for $mobile, $email");
    
    // Check if mobile or email exists
    $check = $conn->prepare("SELECT id FROM users WHERE mobile = ? OR email = ? LIMIT 1");
    $check->bind_param("ss", $mobile, $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        error_log("Amadika Auth: User already exists");
        echo json_encode(['status' => 'error', 'message' => 'Mobile or Email already registered. Please login.']);
        exit;
    }

    $otp = rand(100000, 999999);
    $_SESSION['reg_otp'] = $otp;
    $_SESSION['reg_mobile'] = $mobile;
    $_SESSION['reg_time'] = time();

    error_log("Amadika Auth: Calling sendOTP for registration");
    $api_res = sendOTP($mobile, $otp);
    error_log("Amadika Auth: sendOTP registration result: " . $api_res);
    
    if ($api_res && stripos($api_res, 'error') === false && stripos($api_res, 'fail') === false) {
        echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP. Please try again. Raw: ' . strip_tags($api_res)]);
    }
}
elseif ($action === 'verify_and_register') {
    $mobile = $conn->real_escape_string(trim($_POST['mobile']));
    $otp = trim($_POST['otp']);
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];
    $address = $conn->real_escape_string(trim($_POST['address']));
    $city = $conn->real_escape_string(trim($_POST['city']));
    $state = $conn->real_escape_string(trim($_POST['state']));
    $pincode = $conn->real_escape_string(trim($_POST['pincode']));
    $country = "India";

    if (empty($otp) || $otp != $_SESSION['reg_otp'] || $mobile != $_SESSION['reg_mobile']) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired OTP']);
        exit;
    }

    $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
    $ins = $conn->prepare("INSERT INTO users (name, mobile, email, password, address, city, state, pincode, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $ins->bind_param("sssssssss", $name, $mobile, $email, $hashed_pass, $address, $city, $state, $pincode, $country);
    
    if ($ins->execute()) {
        $new_user_id = $conn->insert_id;
        unset($_SESSION['reg_otp']);
        unset($_SESSION['reg_mobile']);
        
        // Auto-login after registration
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['user_name'] = $name;
        
        // Merge cart
        $session_id = session_id();
        if ($session_id) {
            $update_cart = $conn->prepare("UPDATE cart SET user_id = ? WHERE session_id = ?");
            $update_cart->bind_param("is", $new_user_id, $session_id);
            $update_cart->execute();
        }

        echo json_encode(['status' => 'success', 'message' => 'Registration successful!', 'redirect' => 'user/index.php']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed: ' . $conn->error]);
    }
}
elseif ($action === 'verify_otp') {
    $mobile = $conn->real_escape_string(trim($_POST['mobile']));
    $otp = trim($_POST['otp']);

    if (empty($otp) || $otp != $_SESSION['temp_otp'] || $mobile != $_SESSION['temp_mobile']) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired OTP']);
        exit;
    }

    // Check if OTP is older than 10 mins
    if (time() - $_SESSION['otp_time'] > 600) {
        echo json_encode(['status' => 'error', 'message' => 'OTP has expired. Please resend.']);
        exit;
    }

    // Check if user exists
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE mobile = ? LIMIT 1");
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
    } else {
        // Auto-Register new mobile user
        $name = "Amadika User";
        $email = $mobile . "@amadika.in";
        $dummy_pass = password_hash(bin2hex(random_bytes(8)), PASSWORD_BCRYPT);
        
        $ins = $conn->prepare("INSERT INTO users (name, mobile, email, password) VALUES (?, ?, ?, ?)");
        $ins->bind_param("ssss", $name, $mobile, $email, $dummy_pass);
        
        if ($ins->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_name'] = $name;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Auto-registration failed: ' . $conn->error]);
            exit;
        }
    }

    // Merge cart
    $session_id = session_id();
    if ($session_id) {
        $update_cart = $conn->prepare("UPDATE cart SET user_id = ? WHERE session_id = ?");
        $update_cart->bind_param("is", $_SESSION['user_id'], $session_id);
        $update_cart->execute();
    }
    
    session_write_close();
    echo json_encode(['status' => 'success', 'message' => 'Login successful', 'redirect' => 'user/index.php']);
}
elseif ($action === 'register') {
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
                $update_cart = $conn->prepare("UPDATE cart SET user_id = ? WHERE session_id = ?");
                $update_cart->bind_param("is", $user['id'], $session_id);
                $update_cart->execute();
            }
            
            // Explicitly save session
            session_write_close();

            $redirect = isset($_POST['redirect']) && !empty($_POST['redirect']) ? $_POST['redirect'] : 'user/index.php';
            echo json_encode(['status' => 'success', 'message' => 'Login successful', 'redirect' => $redirect]);
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

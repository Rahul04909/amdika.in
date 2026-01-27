<?php
ob_start();
if(session_status() === PHP_SESSION_NONE) session_start();
require_once '../database/db_config.php';

// Clear output
ob_clean();
header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : '';
$session_id = session_id(); // Use current session ID to track user cart

if (empty($session_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Session ID missing']);
    exit;
}

if ($action === 'add') {
    $product_id = intval($_POST['product_id']);
    $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    
    if ($product_id > 0) {
        // Check if item exists in cart
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ?");
        $stmt->bind_param("si", $session_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update quantity
            $row = $result->fetch_assoc();
            $new_qty = $row['quantity'] + $qty;
            $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $update->bind_param("ii", $new_qty, $row['id']);
            $update->execute();
        } else {
            // Insert new item
            $insert = $conn->prepare("INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)");
            $insert->bind_param("sii", $session_id, $product_id, $qty);
            $insert->execute();
        }
        
        // Get updated count
        $count_res = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE session_id = '$session_id'");
        $count_row = $count_res->fetch_assoc();
        $total_items = $count_row['total'] ?? 0;
        
        echo json_encode(['status' => 'success', 'message' => 'Product added to cart', 'cart_count' => $total_items]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Product']);
    }
} 
elseif ($action === 'update') {
    $product_id = intval($_POST['product_id']);
    $qty = intval($_POST['quantity']); 
    
    if ($product_id > 0 && $qty > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE session_id = ? AND product_id = ?");
        $stmt->bind_param("isi", $qty, $session_id, $product_id);
        
        if($stmt->execute()) {
             echo json_encode(['status' => 'success', 'message' => 'Cart updated']);
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Update failed']);
        }
    } elseif ($product_id > 0 && $qty == 0) {
        // Remove if qty is 0
        $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ? AND product_id = ?");
        $stmt->bind_param("si", $session_id, $product_id);
        $stmt->execute();
        echo json_encode(['status' => 'success', 'message' => 'Item removed']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Data']);
    }
}
elseif ($action === 'remove') {
    $product_id = intval($_POST['product_id']);
    $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ? AND product_id = ?");
    $stmt->bind_param("si", $session_id, $product_id);
    
    if($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Item removed']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Deletion failed']);
    }
}
elseif ($action === 'count') {
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    echo json_encode(['status' => 'success', 'count' => ($result['total'] ?? 0)]);
}
else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Action']);
}
?>

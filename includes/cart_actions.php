<?php
session_start();
require_once '../database/db_config.php';

// Initialize Cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'add') {
    $product_id = intval($_POST['product_id']);
    $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    
    if ($product_id > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $qty;
        } else {
            $_SESSION['cart'][$product_id] = $qty;
        }
        
        echo json_encode(['status' => 'success', 'message' => 'Product added to cart', 'cart_count' => array_sum($_SESSION['cart'])]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Product']);
    }
} 
elseif ($action === 'update') {
    $product_id = intval($_POST['product_id']);
    $qty = intval($_POST['quantity']); // Absolute quantity
    
    if ($product_id > 0 && $qty > 0) {
        $_SESSION['cart'][$product_id] = $qty;
        echo json_encode(['status' => 'success', 'message' => 'Cart updated']);
    } elseif ($product_id > 0 && $qty == 0) {
        unset($_SESSION['cart'][$product_id]);
        echo json_encode(['status' => 'success', 'message' => 'Item removed']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Data']);
    }
}
elseif ($action === 'remove') {
    $product_id = intval($_POST['product_id']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        echo json_encode(['status' => 'success', 'message' => 'Item removed']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Item not found']);
    }
}
elseif ($action === 'count') {
    echo json_encode(['status' => 'success', 'count' => array_sum($_SESSION['cart'])]);
}
else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Action']);
}
?>

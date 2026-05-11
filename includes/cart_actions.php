<?php
ob_start();
// Use centralized session config
require_once 'session_config.php';
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
    $color_id = isset($_POST['color_id']) && !empty($_POST['color_id']) ? intval($_POST['color_id']) : NULL;
    
    if ($product_id > 0) {
        // Check if item exists in cart (include color_id in check)
        if ($color_id === NULL) {
            $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ? AND color_id IS NULL");
            $stmt->bind_param("si", $session_id, $product_id);
        } else {
            $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ? AND color_id = ?");
            $stmt->bind_param("sii", $session_id, $product_id, $color_id);
        }
        
        if (!$stmt->execute()) {
             echo json_encode(['status' => 'error', 'message' => 'DB Select Error: ' . $stmt->error]);
             exit;
        }
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update quantity
            $row = $result->fetch_assoc();
            $new_qty = $row['quantity'] + $qty;
            $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $update->bind_param("ii", $new_qty, $row['id']);
            if (!$update->execute()) {
                echo json_encode(['status' => 'error', 'message' => 'DB Update Error: ' . $update->error]);
                exit;
            }
        } else {
            // Insert new item
            $insert = $conn->prepare("INSERT INTO cart (session_id, product_id, color_id, quantity) VALUES (?, ?, ?, ?)");
            $insert->bind_param("siii", $session_id, $product_id, $color_id, $qty);
            
            if (!$insert->execute()) {
                echo json_encode(['status' => 'error', 'message' => 'DB Insert Error: ' . $insert->error]);
                exit;
            }
        }
        
        // Get updated count
        $count_res = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE session_id = '$session_id'");
        $count_row = $count_res->fetch_assoc();
        $total_items = $count_row['total'] ?? 0;
        
        echo json_encode(['status' => 'success', 'message' => 'Product added to cart', 'cart_count' => $total_items]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Product ID: ' . $product_id]);
    }
} 
elseif ($action === 'update') {
    $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
    $product_id = intval($_POST['product_id']);
    $qty = intval($_POST['quantity']); 
    
    if ($qty > 0) {
        if ($cart_id > 0) {
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND session_id = ?");
            $stmt->bind_param("iis", $qty, $cart_id, $session_id);
        } else {
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE session_id = ? AND product_id = ?");
            $stmt->bind_param("isi", $qty, $session_id, $product_id);
        }
        
        if($stmt->execute()) {
             echo json_encode(['status' => 'success', 'message' => 'Cart updated']);
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Update failed: ' . $stmt->error]);
        }
    } elseif ($qty == 0) {
        // Remove if qty is 0
        if ($cart_id > 0) {
            $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND session_id = ?");
            $stmt->bind_param("is", $cart_id, $session_id);
        } else {
            $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ? AND product_id = ?");
            $stmt->bind_param("si", $session_id, $product_id);
        }
        $stmt->execute();
        echo json_encode(['status' => 'success', 'message' => 'Item removed']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Data']);
    }
}
elseif ($action === 'remove') {
    $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
    $product_id = intval($_POST['product_id']);
    
    if ($cart_id > 0) {
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND session_id = ?");
        $stmt->bind_param("is", $cart_id, $session_id);
    } else {
        $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ? AND product_id = ?");
        $stmt->bind_param("si", $session_id, $product_id);
    }
    
    if($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Item removed']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Deletion failed: ' . $stmt->error]);
    }
}
elseif ($action === 'count') {
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    echo json_encode(['status' => 'success', 'count' => ($result['total'] ?? 0)]);
}
elseif ($action === 'fetch') {
    $sql = "SELECT c.id as cart_row_id, c.quantity, p.name, p.sale_price, p.featured_image, p.slug, cl.name as color_name, cv.price as variant_price, cv.image_path as variant_image
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            LEFT JOIN colors cl ON c.color_id = cl.id
            LEFT JOIN product_color_variants cv ON (c.product_id = cv.product_id AND c.color_id = cv.color_id)
            WHERE c.session_id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    $total_price = 0;
    while($row = $result->fetch_assoc()) {
        $price = ($row['variant_price'] > 0) ? $row['variant_price'] : $row['sale_price'];
        $image = (!empty($row['variant_image'])) ? $row['variant_image'] : $row['featured_image'];
        
        $items[] = [
            'cart_row_id' => $row['cart_row_id'],
            'name' => $row['name'],
            'quantity' => $row['quantity'],
            'price' => $price,
            'image' => $image,
            'slug' => $row['slug'],
            'color' => $row['color_name']
        ];
        $total_price += ($price * $row['quantity']);
    }
    
    echo json_encode([
        'status' => 'success',
        'items' => $items,
        'total' => $total_price,
        'count' => count($items)
    ]);
}
else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Action']);
}
?>

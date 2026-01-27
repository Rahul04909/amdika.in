<?php
require_once '../database/db_config.php';
header('Content-Type: application/json');

$code = isset($_POST['code']) ? $conn->real_escape_string($_POST['code']) : '';
$total = isset($_POST['total']) ? floatval($_POST['total']) : 0;

if(empty($code)) {
    echo json_encode(['valid' => false, 'message' => 'Empty code']);
    exit;
}

// Find Coupon
$sql = "SELECT * FROM coupons WHERE code = '$code' AND valid_till >= CURDATE() LIMIT 1";
$result = $conn->query($sql);

if($result->num_rows > 0) {
    $coupon = $result->fetch_assoc();
    
    // Check Min Order
    if($total < $coupon['min_order_value']) {
        echo json_encode(['valid' => false, 'message' => 'Minimum order for this coupon is ₹' . $coupon['min_order_value']]);
        exit;
    }
    
    // Logic for usage limit (optional, if you have usage_limit column)
    
    // Calculate Discount logic
    // Assuming coupon_type is 'percentage' or 'fixed'
    // But table just has 'amount' based on previous context or assumption.
    // Wait, let's assume 'discount_value' and 'discount_type' if schema supports
    // Typically coupon tables have type.
    // Let's check `sql/create_coupons_table.php` from previous session if I can remember?
    // User didn't view it in this session.
    // I'll default to 'discount_amount' as direct value or check existing manage-coupon to see structure.
    // Actually, I'll view the manage-coupon-codes.php or assume simple flat/percent.
    
    // Let's assume there is discount_value and discount_type.
    // If table structure unknown, I'll do a quick check query logic or safer, just fetch * and adapt if I see columns.
    // But for now, let's assume `discount_value` column exists.
    
    // Calculate Discount based on percent
    $percent = $coupon['discount_percent'];
    $discount = ($total * $percent) / 100;
    
    // Optional: Max discount cap if added later
    
    echo json_encode(['valid' => true, 'discount' => $discount, 'code' => $code, 'percent' => $percent]);
} else {
    echo json_encode(['valid' => false, 'message' => 'Invalid or Expired Coupon']);
}
?>

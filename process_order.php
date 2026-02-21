<?php
require_once 'includes/session_config.php';
require_once 'database/db_config.php';
require_once 'vendor/autoload.php';

use Razorpay\Api\Api;
use PHPMailer\PHPMailer\PHPMailer;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id == 0) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// 1. Fetch Inputs
$payment_id = $_POST['payment_id'] ?? '';
$address_json = $_POST['address'] ?? '{}';
$coupon_code = $_POST['coupon_code'] ?? '';
// Re-calculate totals on backend for security
// ...

// 2. Fetch Razorpay Settings & Verify Payment
$rzp_settings = $conn->query("SELECT * FROM razorpay_settings WHERE status='active' LIMIT 1")->fetch_assoc();
if (!$rzp_settings) {
    echo json_encode(['status' => 'error', 'message' => 'Payment Gateway Error']);
    exit;
}

$api = new Api($rzp_settings['key_id'], $rzp_settings['key_secret']);

try {
    $payment = $api->payment->fetch($payment_id);
    
    if ($payment->status == 'authorized') {
        $payment = $payment->capture(['amount' => $payment->amount]);
    }
    
    if ($payment->status != 'captured') {
        throw new Exception('Payment not captured');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Payment Verification Failed: ' . $e->getMessage()]);
    exit;
}

// 3. Calculate Totals (Redundant but safe)
$session_id = session_id();
$sql_cart = "SELECT p.*, c.quantity as qty, cl.name as color_name, cv.price as variant_price 
             FROM cart c 
             JOIN products p ON c.product_id = p.id 
             LEFT JOIN colors cl ON c.color_id = cl.id
             LEFT JOIN product_color_variants cv ON (c.product_id = cv.product_id AND c.color_id = cv.color_id)
             WHERE c.session_id = '$session_id'";
$cart_res = $conn->query($sql_cart);
$items = [];
$total_price = 0;
$total_gst = 0;

while($row = $cart_res->fetch_assoc()) {
    $qty = $row['qty'];
    $price = ($row['variant_price'] > 0) ? $row['variant_price'] : $row['sale_price'];
    $gst_p = $row['gst_percent'];
    $gst_amt = ($price * $qty * $gst_p) / 100;
    
    $items[] = [
        'product_id' => $row['id'],
        'name' => $row['name'],
        'color_name' => $row['color_name'],
        'qty' => $qty,
        'price' => $price,
        'gst_percent' => $gst_p,
        'gst_amount' => $gst_amt,
        'line_total' => $price * $qty
    ];
    
    $total_price += $price * $qty;
    $total_gst += $gst_amt;
}

// Coupon Logic
$discount_amount = 0;
// Delivery Logic: Free over 500
$delivery_charge = ($total_price > 500) ? 0 : 60;

if(!empty($coupon_code)) {
    $cpn_res = $conn->query("SELECT * FROM coupons WHERE code = '$coupon_code'");
    if($cpn_res->num_rows > 0) {
        $cp = $cpn_res->fetch_assoc();
        $pre_total = $total_price + $total_gst + $delivery_charge;
        $discount_amount = ($pre_total * $cp['discount_percent']) / 100;
    }
}

$final_amount = ($total_price + $total_gst + $delivery_charge) - $discount_amount;

// 4. Save Order
$order_no = 'AMDK-' . date('ymd') . '-' . rand(100, 999);
$addr_obj = json_decode($address_json, true);

$stmt = $conn->prepare("INSERT INTO orders (order_no, user_id, total_sale_price, total_gst, delivery_charge, coupon_code, discount_amount, final_amount, payment_status, payment_id, address_details) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'paid', ?, ?)");
$stmt->bind_param("siddddssds", $order_no, $user_id, $total_price, $total_gst, $delivery_charge, $coupon_code, $discount_amount, $final_amount, $payment_id, $address_json);

if($stmt->execute()) {
    $order_id = $stmt->insert_id;
    
    // Save Items
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, color_name, quantity, price, gst_percent, gst_amount, total_line_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach($items as $item) {
        $stmt_item->bind_param("iissidddd", $order_id, $item['product_id'], $item['name'], $item['color_name'], $item['qty'], $item['price'], $item['gst_percent'], $item['gst_amount'], $item['line_total']);
        $stmt_item->execute();
    }
    
    // 5. Generate Invoice PDF
    $mpdf = new \Mpdf\Mpdf();
    $invoice_html = '
    <div style="font-family: sans-serif;">
        <div style="width: 100%; border-bottom: 1px solid #ccc; padding-bottom: 20px;">
            <table width="100%">
                <tr>
                    <td width="50%"><img src="assets/images/amdika-logo.png" width="150" /></td>
                    <td width="50%" style="text-align: right;">
                        <h2>INVOICE</h2>
                        <p>Order #: '.$order_no.'<br>Date: '.date('d M Y').'</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div style="margin-top: 20px;">
            <table width="100%">
                <tr>
                    <td width="50%">
                        <strong>Billed To:</strong><br>
                        '.$addr_obj['name'].'<br>
                        '.$addr_obj['address'].'<br>
                        '.$addr_obj['city'].', '.$addr_obj['state'].' - '.$addr_obj['pincode'].'<br>
                        Phone: '.$addr_obj['phone'].'
                    </td>
                    <td width="50%" style="text-align: right;">
                        <strong>Sold By:</strong><br>
                        Amadika Store<br>
                        123, Tech Plaza<br>
                        New Delhi, India
                    </td>
                </tr>
            </table>
        </div>
        
        <div style="margin-top: 30px;">
            <table width="100%" style="border-collapse: collapse; border: 1px solid #ddd;" cellpadding="10">
                <tr style="background:#f5f5f5;">
                    <th style="border: 1px solid #ddd; text-align: left;">Product</th>
                    <th style="border: 1px solid #ddd;">Qty</th>
                    <th style="border: 1px solid #ddd;">Price</th>
                     <th style="border: 1px solid #ddd;">GST %</th>
                    <th style="border: 1px solid #ddd;">Total</th>
                </tr>';
                
    foreach($items as $item) {
        $invoice_html .= '
        <tr>
            <td style="border: 1px solid #ddd;">
                '.$item['name'].'
                '.(!empty($item['color_name']) ? '<br><small>Color: '.$item['color_name'].'</small>' : '').'
            </td>
            <td style="border: 1px solid #ddd; text-align: center;">'.$item['qty'].'</td>
            <td style="border: 1px solid #ddd; text-align: right;">'.number_format($item['price'],2).'</td>
             <td style="border: 1px solid #ddd; text-align: center;">'.$item['gst_percent'].'%</td>
            <td style="border: 1px solid #ddd; text-align: right;">'.number_format($item['line_total'],2).'</td>
        </tr>';
    }
    
    $invoice_html .= '
                <tr>
                    <td colspan="4" style="text-align: right; border: 1px solid #ddd;"><strong>Sub Total</strong></td>
                    <td style="text-align: right; border: 1px solid #ddd;">'.number_format($total_price,2).'</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right; border: 1px solid #ddd;"><strong>Tax (GST)</strong></td>
                    <td style="text-align: right; border: 1px solid #ddd;">'.number_format($total_gst,2).'</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right; border: 1px solid #ddd;"><strong>Delivery</strong></td>
                    <td style="text-align: right; border: 1px solid #ddd;">'.number_format($delivery_charge,2).'</td>
                </tr>';
                
    if($discount_amount > 0) {
        $invoice_html .= '
                <tr>
                    <td colspan="4" style="text-align: right; border: 1px solid #ddd;"><strong>Discount</strong></td>
                    <td style="text-align: right; border: 1px solid #ddd; color: green;">-'.number_format($discount_amount,2).'</td>
                </tr>';
    }
    
    $invoice_html .= '
                <tr>
                    <td colspan="4" style="text-align: right; border: 1px solid #ddd; font-size: 16px;"><strong>Grand Total</strong></td>
                    <td style="text-align: right; border: 1px solid #ddd; font-size: 16px;"><strong>'.number_format($final_amount,2).'</strong></td>
                </tr>
            </table>
        </div>
        
        <div style="margin-top: 40px; text-align: center; color: #777;">
            <p>Thank you for shopping with us!</p>
        </div>
    </div>';
    
    $mpdf->WriteHTML($invoice_html);
    $pdf_content = $mpdf->Output('', 'S'); // String output
    
    // 6. Send Email
    $smtp_res = $conn->query("SELECT * FROM smtp_settings LIMIT 1");
    if($smtp_res->num_rows > 0) {
         $smtp = $smtp_res->fetch_assoc();
         
         $mail = new PHPMailer(true);
         try {
            $mail->isSMTP();
            $mail->Host = $smtp['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $smtp['username'];
            $mail->Password = $smtp['password'];
            $mail->SMTPSecure = $smtp['encryption'];
            $mail->Port = $smtp['port'];
            
            $mail->setFrom($smtp['from_email'], $smtp['from_name']);
            // User email? We don't have user email in `users` table check for this session?
            // Assuming `users` table has `email`. 
            // In a real app we fetch it. 
            // Let's fetch user email.
            $u_res = $conn->query("SELECT email, name FROM users WHERE id = $user_id");
            if($u_res->num_rows > 0) {
                $u_data = $u_res->fetch_assoc();
                $mail->addAddress($u_data['email'], $u_data['name']);
                
                $mail->isHTML(true);
                $mail->Subject = 'Order Confirmation - ' . $order_no;
                $mail->Body = "Hi " . $u_data['name'] . ",<br><br>Thank you for your order! Your order <b>$order_no</b> has been successfully placed.<br>Please find your invoice attached.<br><br>Regards,<br>Amadika Team";
                
                $mail->addStringAttachment($pdf_content, 'Invoice_'.$order_no.'.pdf');
                
                $mail->send();
            }
         } catch (Exception $e) {
             // Email failed, log it but don't fail order
         }
    }
    
    // 7. Clear Cart
    $conn->query("DELETE FROM cart WHERE session_id = '$session_id'");
    
    echo json_encode(['status' => 'success', 'order_no' => $order_no]);
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'DB Insert Failed']);
}
?>

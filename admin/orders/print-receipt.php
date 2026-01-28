<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';
require_once '../../vendor/autoload.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Order ID");
}

$order_id = intval($_GET['id']);

// 1. Fetch Order Details
$sql = "SELECT * FROM orders WHERE id = $order_id LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Order not found");
}

$order = $result->fetch_assoc();
$order_no = $order['order_no'];
$addr_obj = json_decode($order['address_details'], true);

// 2. Fetch Order Items
$items_sql = "SELECT * FROM order_items WHERE order_id = $order_id";
$items_res = $conn->query($items_sql);
$items = [];
while ($row = $items_res->fetch_assoc()) {
    $items[] = $row;
}

// 3. Generate PDF (Logic copied from process_order.php)
$mpdf = new \Mpdf\Mpdf();

$invoice_html = '
<div style="font-family: sans-serif;">
    <div style="width: 100%; border-bottom: 1px solid #ccc; padding-bottom: 20px;">
        <table width="100%">
            <tr>
                <td width="50%"><img src="../../assets/images/amdika-logo.png" width="150" /></td>
                <td width="50%" style="text-align: right;">
                    <h2>INVOICE</h2>
                    <p>Order #: '.$order_no.'<br>Date: '.date('d M Y', strtotime($order['created_at'])).'</p>
                </td>
            </tr>
        </table>
    </div>
    
    <div style="margin-top: 20px;">
        <table width="100%">
            <tr>
                <td width="50%">
                    <strong>Billed To:</strong><br>
                    '.($addr_obj['name'] ?? 'N/A').'<br>
                    '.($addr_obj['address'] ?? 'N/A').'<br>
                    '.($addr_obj['city'] ?? '').', '.($addr_obj['state'] ?? '').' - '.($addr_obj['pincode'] ?? '').'<br>
                    Phone: '.($addr_obj['phone'] ?? $addr_obj['mobile'] ?? 'N/A').'
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
        <td style="border: 1px solid #ddd;">'.$item['product_name'].'</td>
        <td style="border: 1px solid #ddd; text-align: center;">'.$item['quantity'].'</td>
        <td style="border: 1px solid #ddd; text-align: right;">'.number_format($item['price'],2).'</td>
         <td style="border: 1px solid #ddd; text-align: center;">'.$item['gst_percent'].'%</td>
        <td style="border: 1px solid #ddd; text-align: right;">'.number_format($item['total_line_amount'],2).'</td>
    </tr>';
}

$invoice_html .= '
            <tr>
                <td colspan="4" style="text-align: right; border: 1px solid #ddd;"><strong>Sub Total</strong></td>
                <td style="text-align: right; border: 1px solid #ddd;">'.number_format($order['total_sale_price'],2).'</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right; border: 1px solid #ddd;"><strong>Tax (GST)</strong></td>
                <td style="text-align: right; border: 1px solid #ddd;">'.number_format($order['total_gst'],2).'</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right; border: 1px solid #ddd;"><strong>Delivery</strong></td>
                <td style="text-align: right; border: 1px solid #ddd;">'.number_format($order['delivery_charge'],2).'</td>
            </tr>';
            
if($order['discount_amount'] > 0) {
    $invoice_html .= '
            <tr>
                <td colspan="4" style="text-align: right; border: 1px solid #ddd;"><strong>Discount</strong></td>
                <td style="text-align: right; border: 1px solid #ddd; color: green;">-'.number_format($order['discount_amount'],2).'</td>
            </tr>';
}

$invoice_html .= '
            <tr>
                <td colspan="4" style="text-align: right; border: 1px solid #ddd; font-size: 16px;"><strong>Grand Total</strong></td>
                <td style="text-align: right; border: 1px solid #ddd; font-size: 16px;"><strong>'.number_format($order['final_amount'],2).'</strong></td>
            </tr>
        </table>
    </div>
    
    <div style="margin-top: 40px; text-align: center; color: #777;">
        <p>This is a computer generated invoice.</p>
    </div>
</div>';

$mpdf->WriteHTML($invoice_html);
$mpdf->Output('Invoice_'.$order_no.'.pdf', 'I'); // 'I' for Inline browser view
?>

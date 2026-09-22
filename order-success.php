<?php
if(!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit;
}
$order_id = htmlspecialchars($_GET['order_id']);
$page_title = 'Order Success';
include 'includes/header.php';
?>
<div class="container mt-5 mb-5 text-center">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 50px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <i class="fas fa-check-circle text-success" style="font-size: 80px; margin-bottom: 20px;"></i>
        <h2 class="fw-bold text-success mb-3">Order Placed Successfully!</h2>
        <p class="text-muted fs-5">Your order <strong><?php echo $order_id; ?></strong> has been confirmed.</p>
        <p class="mb-4">We have sent a confirmation email with the invoice to your registered email address.</p>
        
        <a href="index.php" class="btn btn-primary px-5 py-2 fw-semibold">Continue Shopping</a>
    </div>
</div>
<?php include 'includes/footer.php'; ?>

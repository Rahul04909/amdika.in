<?php
require_once 'database/db_config.php';
// Session start is usually in header or auth check, but let's ensure it here as well
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = 'My Cart';
include 'includes/header.php';

$session_id = session_id();
$products = [];
$total_mrp = 0;
$total_discount = 0;
$total_price = 0;
$total_gst = 0;
$final_payable = 0;

if ($session_id) {
    // Fetch items from cart table joined with products
    $sql = "SELECT p.*, c.quantity as qty, c.id as cart_row_id 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.session_id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $qty = $row['qty'];
            $products[] = $row;
            
            $gst_percent = $row['gst_percent'];
            $gst_amount = ($row['sale_price'] * $qty * $gst_percent) / 100;
            
            $total_mrp += $row['mrp'] * $qty;
            $total_price += $row['sale_price'] * $qty;
            $total_gst += $gst_amount;
        }
        
        $delivery_charge = 60;
        $final_payable = $total_price + $total_gst + $delivery_charge;
    }
    $total_discount = $total_mrp - $total_price;
}
?>

<style>
    body { background-color: #f1f3f6; }
    .cart-container { padding: 20px 0; }
    
    /* Left: Item List */
    .cart-items-card { background: #fff; border-radius: 2px; box-shadow: 0 1px 2px 0 rgba(0,0,0,.1); margin-bottom: 20px; }
    .cart-header { padding: 15px 24px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
    .cart-title { font-size: 18px; font-weight: 500; margin: 0; }
    
    .cart-item { padding: 24px; border-bottom: 1px solid #f0f0f0; position: relative; }
    .cart-item:last-child { border-bottom: none; }
    
    .item-img-container { width: 112px; height: 112px; float: left; position: relative; text-align: center; }
    .item-img { max-width: 100%; max-height: 100%; object-fit: contain; }
    
    .item-details { margin-left: 135px; min-height: 112px; }
    .item-title { font-size: 16px; color: #212121; font-weight: 500; display: block; margin-bottom: 8px; text-decoration: none; }
    .item-title:hover { color: #2874f0; }
    .seller-text { font-size: 12px; color: #878787; margin-bottom: 10px; display: block; }
    
    .price-row { display: flex; align-items: baseline; gap: 10px; margin-bottom: 15px; }
    .final-price { font-size: 18px; font-weight: 600; color: #212121; }
    .mrp-strike { font-size: 14px; color: #878787; text-decoration: line-through; }
    .disc-green { font-size: 14px; color: #388e3c; font-weight: 600; }
    
    /* Quantity Control */
    .qty-control { display: flex; align-items: center; gap: 10px; }
    .qty-btn { 
        width: 28px; height: 28px; border-radius: 50%; border: 1px solid #c2c2c2; background: #fff; 
        display: flex; align-items: center; justify-content: center; font-size: 12px; cursor: pointer; color: #212121;
    }
    .qty-input { width: 46px; height: 28px; text-align: center; border: 1px solid #c2c2c2; font-size: 14px; font-weight: 500; }
    .qty-btn:hover { border-color: #212121; }
    
    .action-links { margin-left: 20px; font-size: 16px; font-weight: 500; text-transform: uppercase; cursor: pointer; color: #212121; }
    .action-links:hover { color: #2874f0; }

    /* Right: Price Details */
    .price-card { background: #fff; border-radius: 2px; box-shadow: 0 1px 2px 0 rgba(0,0,0,.1); position: sticky; top: 90px; }
    .price-header { padding: 13px 24px; border-bottom: 1px solid #f0f0f0; } 
    .price-header span { font-size: 16px; font-weight: 500; color: #878787; text-transform: uppercase; }
    .price-body { padding: 0 24px; }
    .price-row-item { display: flex; justify-content: space-between; margin: 20px 0; font-size: 16px; color: #212121; }
    .total-row { border-top: 1px dashed #e0e0e0; border-bottom: 1px dashed #e0e0e0; padding: 20px 0; font-weight: 600; font-size: 18px; }
    .savings-msg { color: #388e3c; font-weight: 500; font-size: 16px; padding: 20px 24px; border-top: 1px solid #f0f0f0; }
    
    .place-order-wrapper { padding: 16px 24px; background: #fff; border-top: 1px solid #f0f0f0; box-shadow: 0 -2px 10px 0 rgba(0,0,0,.1); }
    .btn-place-order { background: #fb641b; color: #fff; width: 100%; border: none; padding: 16px; font-size: 16px; font-weight: 500; text-transform: uppercase; border-radius: 2px; box-shadow: 0 1px 2px 0 rgba(0,0,0,.2); }
    .btn-place-order:hover { background: #f55b10; }

    /* Empty Cart */
    .empty-cart { background: #fff; height: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center; box-shadow: 0 1px 2px 0 rgba(0,0,0,.1); }
    .empty-img { width: 200px; opacity: 0.7; margin-bottom: 20px; }

    @media (max-width: 991px) {
        .place-order-wrapper { position: fixed; bottom: 0; left: 0; width: 100%; z-index: 100; }
        .cart-container { padding-bottom: 80px; }
    }
    @media (max-width: 768px) {
        .item-details { margin-left: 0; margin-top: 10px; clear: both; }
        .item-img-container { float: none; margin: 0 auto 10px; }
    }
</style>

<div class="container cart-container">
    <div class="row">
        <?php if (!empty($products)): ?>
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="cart-items-card">
                    <div class="cart-header">
                        <h2 class="cart-title">My Cart (<?php echo count($products); ?>)</h2>
                    </div>
                    
                    <?php foreach($products as $p): ?>
                        <div class="cart-item" id="item-<?php echo $p['id']; ?>">
                            <div class="item-img-container">
                                <a href="product-details.php?slug=<?php echo $p['slug']; ?>">
                                    <img src="<?php echo $p['featured_image']; ?>" class="item-img">
                                </a>
                            </div>
                            <div class="item-details">
                                <a href="product-details.php?slug=<?php echo $p['slug']; ?>" class="item-title"><?php echo htmlspecialchars($p['name']); ?></a>
                                <span class="seller-text">Best Seller</span>
                                
                                <div class="price-row">
                                    <span class="mrp-strike">₹<?php echo number_format($p['mrp']); ?></span>
                                    <span class="final-price">₹<?php echo number_format($p['sale_price']); ?></span>
                                    <span class="disc-green"><?php echo $p['discount_percent']; ?>% Off</span>
                                </div>
                                <div class="small text-muted mb-2">GST <?php echo $p['gst_percent']; ?>% included</div>
                                
                                <div class="d-flex align-items-center flex-wrap gap-4">
                                    <div class="qty-control">
                                        <button class="qty-btn" onclick="updateQty(<?php echo $p['id']; ?>, -1)">-</button>
                                        <input type="text" class="qty-input" value="<?php echo $p['qty']; ?>" readonly>
                                        <button class="qty-btn" onclick="updateQty(<?php echo $p['id']; ?>, 1)">+</button>
                                    </div>
                                    <div class="d-flex">
                                        <span class="action-links" onclick="removeFromCart(<?php echo $p['id']; ?>)">REMOVE</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Place Order Button (Mobile visual check or specific place?) 
                     Generally mobile has sticky bottom. Desktop has right column.
                     Adding a button here usually helpful for mobile flow if not sticky. 
                     But we have sticky logic. 
                -->
            </div>
            
            <!-- Price Sidebar -->
            <div class="col-lg-4">
                <div class="price-card">
                    <div class="price-header">
                        <span>Price Details</span>
                    </div>
                    <div class="price-body">
                        <div class="price-row-item">
                            <span>Price (<?php echo count($products); ?> items)</span>
                            <span>₹<?php echo number_format($total_mrp); ?></span>
                        </div>
                        <div class="price-row-item">
                            <span>Discount</span>
                            <span class="text-success">- ₹<?php echo number_format($total_discount); ?></span>
                        </div>
                        <div class="price-row-item">
                            <span>GST Amount</span>
                            <span>₹<?php echo number_format($total_gst); ?></span>
                        </div>
                        <div class="price-row-item">
                            <span>Delivery Charges</span>
                            <span class="text-success">₹<?php echo number_format($delivery_charge); ?></span>
                        </div>
                        <div class="price-row-item total-row">
                            <span>Total Amount</span>
                            <span>₹<?php echo number_format($final_payable); ?></span>
                        </div>
                    </div>
                    <?php if($total_discount > 0): ?>
                        <div class="savings-msg">You will save ₹<?php echo number_format($total_discount); ?> on this order</div>
                    <?php endif; ?>
                    
                    <!-- Desktop Button (Hidden on mobile if using sticky) -->
                    <div class="place-order-wrapper d-none d-lg-block">
                         <button class="btn-place-order" onclick="window.location.href='checkout.php'">Place Order</button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Sticky Footer -->
            <div class="place-order-wrapper d-lg-none">
                <div class="row align-items-center">
                    <div class="col-6">
                        <small>Total Amount</small>
                        <div class="fw-bold fs-5">₹<?php echo number_format($final_payable); ?></div>
                    </div>
                    <div class="col-6">
                         <button class="btn-place-order" onclick="window.location.href='checkout.php'">Place Order</button>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Empty Cart -->
            <div class="col-12">
                <div class="empty-cart">
                    <img src="https://rukminim2.flixcart.com/www/800/800/promos/16/05/2019/d438a32e-765a-4d8b-b4a6-520b560971e8.png" class="empty-img" alt="Empty Cart">
                    <h3>Your cart is empty!</h3>
                    <p class="text-muted mb-4">Add items to it now.</p>
                    <a href="index.php" class="btn btn-primary px-5 py-2">Shop Now</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    function updateQty(id, change) {
        const input = document.querySelector(`#item-${id} .qty-input`);
        let newQty = parseInt(input.value) + change;
        if(newQty < 1) return; // Use remove instead for 0

        fetch('includes/cart_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=update&product_id=${id}&quantity=${newQty}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                location.reload(); // Simplest way to recalculate totals
            } else {
                alert(data.message);
            }
        });
    }

    function removeFromCart(id) {
        if(!confirm('Are you sure you want to remove this item?')) return;
        
        fetch('includes/cart_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=remove&product_id=${id}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                location.reload();
            }
        });
    }
</script>

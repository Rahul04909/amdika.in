<?php
require_once 'includes/session_config.php';
require_once 'database/db_config.php';

$page_title = 'Checkout';
include 'includes/header.php';

// Check if Cart is empty
$session_id = session_id();
$cart_check = $conn->query("SELECT COUNT(*) as count FROM cart WHERE session_id = '$session_id'")->fetch_assoc();
if ($cart_check['count'] == 0) {
    header("Location: cart.php");
    exit();
}

// Fetch Cart Items & Calculate Totals (Same logic as cart.php)
$cart_items = [];
$total_mrp = 0;
$total_price = 0;
$total_gst = 0;

$sql = "SELECT p.*, c.quantity as qty, c.id as cart_row_id, cl.name as color_name, cv.price as variant_price, cv.image_path as variant_image
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        LEFT JOIN colors cl ON c.color_id = cl.id
        LEFT JOIN product_color_variants cv ON (c.product_id = cv.product_id AND c.color_id = cv.color_id)
        WHERE c.session_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $session_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()) {
    $qty = $row['qty'];
    
    // Use variant price if available
    $effective_price = ($row['variant_price'] > 0) ? $row['variant_price'] : $row['sale_price'];
    $row['display_price'] = $effective_price;
    $row['display_image'] = (!empty($row['variant_image'])) ? $row['variant_image'] : $row['featured_image'];

    $cart_items[] = $row;
    
    $gst_percent = $row['gst_percent'];
    $gst_amount = ($effective_price * $qty * $gst_percent) / 100;
    
    $total_mrp += $row['mrp'] * $qty;
    $total_price += $effective_price * $qty;
    $total_gst += $gst_amount;
}
$delivery_charge = ($total_price > 500) ? 0 : 60;
$grand_total = round($total_price + $total_gst + $delivery_charge);

// Fetch Razorpay Key
$rzp_settings = $conn->query("SELECT key_id FROM razorpay_settings WHERE status='active' LIMIT 1")->fetch_assoc();
$rzp_key = $rzp_settings['key_id'] ?? '';

// Fetch User Details if Logged In
$user_id = $_SESSION['user_id'] ?? 0;
$user_data = [];
if ($user_id > 0) {
    $user_res = $conn->query("SELECT * FROM users WHERE id = $user_id");
    $user_data = $user_res->fetch_assoc();
}

// Default values if not set
$u_name = $user_data['name'] ?? '';
$u_mobile = $user_data['mobile'] ?? '';
$u_address = $user_data['address'] ?? '';
$u_city = $user_data['city'] ?? '';
$u_pincode = $user_data['pincode'] ?? '';
$u_state = $user_data['state'] ?? '';
?>

<style>
    body { background-color: #f1f3f6; }
    .checkout-container { padding: 20px 0; }
    .section-card { background: #fff; box-shadow: 0 1px 2px 0 rgba(0,0,0,.1); padding: 24px; margin-bottom: 20px; border-radius: 2px; }
    .section-title { font-size: 18px; font-weight: 600; text-transform: uppercase; color: #878787; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
    .step-number { background: #f0f0f0; color: #2874f0; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 14px; border-radius: 2px; }
    
    .form-control { border-radius: 2px; border: 1px solid #e0e0e0; padding: 10px 12px; font-size: 14px; }
    .form-control:focus { box-shadow: none; border-color: #2874f0; }
    
    .price-details { position: sticky; top: 90px; }
    .price-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; }
    .total-row { border-top: 1px dashed #e0e0e0; padding-top: 15px; font-weight: 600; font-size: 18px; }
    
    .coupon-box { display: flex; gap: 10px; margin-bottom: 20px; }
    .btn-apply { background: #fff; border: 1px solid #e0e0e0; color: #2874f0; font-weight: 600; padding: 8px 16px; border-radius: 2px; }
    .btn-apply:hover { background: #f9f9f9; }
    
    .btn-pay { background: #fb641b; color: #fff; width: 100%; border: none; padding: 16px; font-size: 16px; font-weight: 600; text-transform: uppercase; border-radius: 2px; box-shadow: 0 1px 2px 0 rgba(0,0,0,.2); }
    .btn-pay:hover { background: #f55b10; }
    
    .order-summary-item { display: flex; gap: 15px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0; }
    .summary-img { width: 60px; height: 60px; object-fit: contain; }
</style>

<div class="container checkout-container">
    <div class="row">
        <!-- Left Column: Forms -->
        <div class="col-lg-8">
            <!-- 1. Login / Mobile Verification -->
            <div class="section-card">
                <div class="section-title">
                    <span class="step-number">1</span>
                    <span>Verification</span>
                    <?php if($user_id > 0): ?>
                        <i class="fas fa-check-circle text-success ms-auto"></i>
                    <?php endif; ?>
                </div>
                
                <?php if($user_id > 0): ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold"><?php echo htmlspecialchars($u_name); ?></span>
                            <span class="text-muted ms-2">+91 <?php echo htmlspecialchars($u_mobile); ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <div id="otpSection">
                        <div class="row align-items-end" id="mobileInputRow">
                            <div class="col-md-8 mb-2">
                                <label class="small fw-bold text-muted mb-1">Enter Mobile Number</label>
                                <input type="tel" id="guestMobile" class="form-control" placeholder="10-digit mobile number" maxlength="10">
                            </div>
                            <div class="col-md-4 mb-2">
                                <button type="button" class="btn btn-primary w-100 py-2" onclick="sendGuestOTP()" id="sendOtpBtn">Send OTP</button>
                            </div>
                        </div>
                        
                        <div class="row align-items-end d-none" id="otpInputRow">
                            <div class="col-md-8 mb-2">
                                <label class="small fw-bold text-muted mb-1">Enter 6-digit OTP</label>
                                <input type="text" id="guestOTP" class="form-control" placeholder="Enter OTP" maxlength="6">
                            </div>
                            <div class="col-md-4 mb-2">
                                <button type="button" class="btn btn-success w-100 py-2" onclick="verifyGuestOTP()" id="verifyOtpBtn">Verify OTP</button>
                            </div>
                            <div class="col-12 mt-1">
                                <a href="javascript:void(0)" class="small text-primary" onclick="resetOTPForm()">Change Number?</a>
                            </div>
                        </div>
                    </div>
                    <div id="verifiedStatus" class="d-none">
                        <div class="alert alert-success d-flex align-items-center py-2 px-3 mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>Mobile Verified: <strong id="verifiedMobileDisplay"></strong></div>
                            <input type="hidden" name="verified_mobile" id="verifiedMobileInput">
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- 2. Delivery Address -->
            <div class="section-card <?php echo ($user_id == 0) ? 'opacity-50' : ''; ?>" id="addressSection">
                <div class="section-title">
                    <span class="step-number">2</span>
                    <span>Delivery Address</span>
                </div>
                <form id="addressForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="name" placeholder="Name" required value="<?php echo htmlspecialchars($u_name); ?>" <?php echo ($user_id == 0) ? 'disabled' : ''; ?>>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="tel" class="form-control" name="phone" id="addressPhone" placeholder="10-digit Mobile Number" pattern="[0-9]{10}" required value="<?php echo htmlspecialchars($u_mobile); ?>" <?php echo ($user_id == 0) ? 'disabled' : 'readonly'; ?>>
                        </div>
                        <div class="col-md-6 mb-3">
                             <input type="text" class="form-control" name="pincode" placeholder="Pincode" required value="<?php echo htmlspecialchars($u_pincode); ?>" <?php echo ($user_id == 0) ? 'disabled' : ''; ?>>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="city" placeholder="City/District/Town" required value="<?php echo htmlspecialchars($u_city); ?>" <?php echo ($user_id == 0) ? 'disabled' : ''; ?>>
                        </div>
                        <div class="col-md-12 mb-3">
                            <textarea class="form-control" name="address" rows="3" placeholder="Address (Area and Street)" required <?php echo ($user_id == 0) ? 'disabled' : ''; ?>><?php echo htmlspecialchars($u_address); ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                             <input type="text" class="form-control" name="state" placeholder="State" required value="<?php echo htmlspecialchars($u_state); ?>" <?php echo ($user_id == 0) ? 'disabled' : ''; ?>>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="landmark" placeholder="Landmark (Optional)" <?php echo ($user_id == 0) ? 'disabled' : ''; ?>>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- 3. Order Summary -->
            <div class="section-card">
                <div class="section-title">
                    <span class="step-number">3</span>
                    <span>Order Summary</span>
                </div>
                <?php foreach($cart_items as $item): ?>
                    <div class="order-summary-item">
                        <img src="<?php echo (strpos($item['display_image'], 'http') === 0 || strpos($item['display_image'], '/') === 0) ? $item['display_image'] : $link_prefix . $item['display_image']; ?>" class="summary-img">
                        <div>
                            <div class="fw-500"><?php echo htmlspecialchars($item['name']); ?></div>
                            <?php if(!empty($item['color_name'])): ?>
                                <div class="text-muted small">Color: <?php echo htmlspecialchars($item['color_name']); ?></div>
                            <?php endif; ?>
                            <div class="text-muted small">Qty: <?php echo $item['qty']; ?></div>
                            <div class="fw-bold">₹<?php echo number_format($item['display_price']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Right Column: Price Details -->
        <div class="col-lg-4">
            <div class="section-card price-details">
                <h5 class="text-muted mb-3 text-uppercase fs-6 fw-bold">Price Details</h5>
                
                <div class="price-row">
                    <span>Price (<?php echo count($cart_items); ?> items)</span>
                    <span>₹<?php echo number_format($total_mrp); ?></span>
                </div>
                <div class="price-row">
                    <span>Discount</span>
                    <span class="text-success">- ₹<?php echo number_format($total_mrp - $total_price); ?></span>
                </div>
                <div class="price-row">
                    <span>GST</span>
                    <span>₹<?php echo number_format($total_gst); ?></span>
                </div>
                <div class="price-row">
                    <span>Delivery Charges</span>
                    <span class="text-success">₹<?php echo number_format($delivery_charge); ?></span>
                </div>
                
                <!-- Coupon Section -->
                <div class="coupon-box">
                    <input type="text" id="couponCode" class="form-control" placeholder="Enter Coupon Code">
                    <button class="btn-apply" onclick="applyCoupon()">APPLY</button>
                </div>
                <div id="couponMessage" class="small mb-3"></div>
                
                <div class="price-row total-row">
                    <span>Total Payable</span>
                    <span id="finalAmount">₹<?php echo number_format($grand_total); ?></span>
                </div>
                
                <button id="rzp-button1" class="btn-pay mt-3">PAY ₹<span id="payBtnAmount"><?php echo number_format($grand_total); ?></span></button>
            </div>
        </div>
    </div>
</div>

<!-- Razorpay Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentTotal = <?php echo $grand_total; ?>;
let discountDetails = { code: '', amount: 0 };
let isGuestVerified = <?php echo ($user_id > 0) ? 'true' : 'false'; ?>;

function sendGuestOTP() {
    const mobile = document.getElementById('guestMobile').value;
    if(mobile.length !== 10) {
        Swal.fire('Error', 'Please enter a valid 10-digit mobile number', 'error');
        return;
    }
    
    const btn = document.getElementById('sendOtpBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    
    fetch('includes/auth_actions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=send_otp&mobile=${mobile}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            document.getElementById('mobileInputRow').classList.add('d-none');
            document.getElementById('otpInputRow').classList.remove('d-none');
            
            if (data.sms_error) {
                // SMS gateway failed, auto-fill OTP for user
                document.getElementById('guestOTP').value = data.otp;
                Swal.fire({
                    title: 'Verification Code',
                    text: 'SMS gateway is temporarily offline. Your verification code is ' + data.otp + ' (auto-filled). Please click Verify OTP to continue.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire('Success', 'OTP sent to ' + mobile, 'success');
            }
        } else {
            Swal.fire('Error', data.message, 'error');
            btn.disabled = false;
            btn.innerText = 'Send OTP';
        }
    });
}

function verifyGuestOTP() {
    const mobile = document.getElementById('guestMobile').value;
    const otp = document.getElementById('guestOTP').value;
    
    if(otp.length !== 6) {
        Swal.fire('Error', 'Please enter 6-digit OTP', 'error');
        return;
    }
    
    const btn = document.getElementById('verifyOtpBtn');
    btn.disabled = true;
    
    fetch('includes/auth_actions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=verify_otp&mobile=${mobile}&otp=${otp}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            // Success!
            isGuestVerified = true;
            document.getElementById('otpSection').classList.add('d-none');
            document.getElementById('verifiedStatus').classList.remove('d-none');
            document.getElementById('verifiedMobileDisplay').innerText = '+91 ' + mobile;
            document.getElementById('verifiedMobileInput').value = mobile;
            document.getElementById('addressPhone').value = mobile;
            
            // Enable Address Form
            const addrSection = document.getElementById('addressSection');
            addrSection.classList.remove('opacity-50');
            const inputs = addrSection.querySelectorAll('input, textarea');
            inputs.forEach(input => input.disabled = false);
            
            Swal.fire('Verified', 'Mobile number verified successfully!', 'success');
        } else {
            Swal.fire('Error', data.message, 'error');
            btn.disabled = false;
        }
    });
}

function resetOTPForm() {
    document.getElementById('otpInputRow').classList.add('d-none');
    document.getElementById('mobileInputRow').classList.remove('d-none');
    document.getElementById('sendOtpBtn').disabled = false;
    document.getElementById('sendOtpBtn').innerText = 'Send OTP';
}

function applyCoupon() {
    const code = document.getElementById('couponCode').value.trim();
    if(!code) return;
    
    fetch('api/check_coupon.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `code=${code}&total=${currentTotal}`
    })
    .then(res => res.json())
    .then(data => {
        const msgDiv = document.getElementById('couponMessage');
        if(data.valid) {
            msgDiv.innerHTML = `<span class="text-success">Coupon Applied! You saved ₹${data.discount}</span>`;
            
            // Recalculate Total
            let newTotal = currentTotal - data.discount;
            // Update UI
            document.getElementById('finalAmount').innerText = '₹' + newTotal.toLocaleString('en-IN');
            document.getElementById('payBtnAmount').innerText = newTotal.toLocaleString('en-IN');
            
            discountDetails = { code: code, amount: data.discount };
        } else {
            msgDiv.innerHTML = `<span class="text-danger">${data.message}</span>`;
            // Reset if invalid
            document.getElementById('finalAmount').innerText = '₹' + currentTotal.toLocaleString('en-IN');
            document.getElementById('payBtnAmount').innerText = currentTotal.toLocaleString('en-IN');
            discountDetails = { code: '', amount: 0 };
        }
    });
}

// Payment Integration
document.getElementById('rzp-button1').onclick = function(e){
    e.preventDefault();
    
    if(!isGuestVerified) {
        Swal.fire('Action Required', 'Please verify your mobile number with OTP first.', 'warning');
        window.scrollTo({top: 0, behavior: 'smooth'});
        return;
    }

    // Validate Address
    const form = document.getElementById('addressForm');
    if(!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Calculate Final Amount in Paisa
    let finalAmt = (currentTotal - discountDetails.amount) * 100;
    
    // Prepare User Data from Form
    const addressData = new FormData(form);
    const addressObj = Object.fromEntries(addressData.entries());

    var options = {
        "key": "<?php echo $rzp_key; ?>", 
        "amount": finalAmt, 
        "currency": "INR",
        "name": "Amadika",
        "description": "Order Payment",
        "image": "assets/images/amdika-logo.png",
        "handler": function (response){
            // On Success, Post to Backend
            processOrder(response.razorpay_payment_id, addressObj);
        },
        "prefill": {
            "name": addressObj.name,
            "contact": addressObj.phone
        },
        "theme": {
            "color": "#2874f0"
        }
    };
    
    if(!options.key) {
        alert("Payment Gateway Error: Key ID missing. Contact Admin.");
        return;
    }
    
    var rzp1 = new Razorpay(options);
    rzp1.open();
}

function processOrder(paymentId, address) {
    const formData = new FormData();
    formData.append('payment_id', paymentId);
    formData.append('address', JSON.stringify(address));
    formData.append('coupon_code', discountDetails.code);
    formData.append('discount_amount', discountDetails.amount);
    
    // Show Loading
    Swal.fire({
        title: 'Processing Order',
        text: 'Please wait while we confirm your order...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading() }
    });

    fetch('process_order.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            window.location.href = 'order-success.php?order_id=' + data.order_no;
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(err => {
        Swal.fire('Error', 'Something went wrong processing your order.', 'error');
    });
}
</script>

<?php include 'includes/footer.php'; ?>

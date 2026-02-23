<?php
// Page Setup
$page_title = "Fees and Payments - Amadika";
include '../../includes/header.php'; 
?>

<style>
    /* --- Fees Page Specific Styles --- */
    .fees-hero {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('../../assets/images/banners/banner-1.png');
        background-size: cover;
        background-position: center;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
    }
    .fees-hero h1 { font-size: 2.5rem; font-weight: 700; }
    
    .section-padding { padding: 60px 0; }
    
    .payment-method-card {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        border: 1px solid #eee;
        transition: all 0.3s;
        text-align: center;
        height: 100%;
    }
    .payment-method-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-color: #ff9f00;
    }
    .payment-icon {
        font-size: 40px;
        color: #ff9f00;
        margin-bottom: 20px;
    }
    .method-badge {
        display: inline-block;
        padding: 5px 15px;
        background: #f0f0f0;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #666;
        margin-top: 10px;
    }
    .security-banner {
        background: #212121;
        color: #fff;
        padding: 40px;
        border-radius: 12px;
    }
</style>

<!-- Hero Section -->
<section class="fees-hero">
    <div class="container">
        <h1 data-aos="fade-up">Fees and Payments</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">Transparent billing and secure transactions.</p>
    </div>
</section>

<!-- Methods -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h5 class="text-warning text-uppercase fw-bold">Payment Methods</h5>
            <h2 class="fw-bold">We accept all major payments</h2>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="payment-method-card">
                    <div class="payment-icon"><i class="fas fa-credit-card"></i></div>
                    <h5>Cards</h5>
                    <p class="text-muted small">Visa, Mastercard, Rupay, and Amex credit/debit cards accepted.</p>
                    <span class="method-badge">Instant</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="payment-method-card">
                    <div class="payment-icon"><i class="fas fa-mobile-alt"></i></div>
                    <h5>UPI & Wallets</h5>
                    <p class="text-muted small">Google Pay, PhonePe, Paytm, and all major UPI apps.</p>
                    <span class="method-badge">Instant</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="payment-method-card">
                    <div class="payment-icon"><i class="fas fa-university"></i></div>
                    <h5>Net Banking</h5>
                    <p class="text-muted small">Support for over 50+ major Indian banks for direct transfer.</p>
                    <span class="method-badge">Instant</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="payment-method-card">
                    <div class="payment-icon"><i class="fas fa-hand-holding-usd"></i></div>
                    <h5>COD</h5>
                    <p class="text-muted small">Pay with cash upon receiving your order at your doorstep.</p>
                    <span class="method-badge">On Delivery</span>
                </div>
            </div>
        </div>

        <div class="row mt-5 pt-4">
            <div class="col-lg-12">
                <div class="security-banner d-flex flex-column flex-md-row align-items-center justify-content-between text-center text-md-start">
                    <div>
                        <h4 class="fw-bold"><i class="fas fa-shield-alt me-2 text-warning"></i> Secure Payment Gateway</h4>
                        <p class="mb-0 opacity-75">All transactions are processed via Razorpay's 128-bit SSL encrypted secure connection.</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <img src="../../assets/images/amdika-logo.png" style="height: 30px; filter: brightness(0) invert(1); opacity: 0.5;" alt="Razorpay Logo Placeholder">
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-6">
                <h4 class="fw-bold mb-4">Transaction Fees</h4>
                <div class="p-4 bg-light rounded">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span>Online Payments (Prepaid)</span>
                            <span class="fw-bold text-success">Zero Fees</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span>Cash on Delivery (COD)</span>
                            <span class="fw-bold">₹49 Handling Fee</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Convenience Fee</span>
                            <span class="fw-bold text-success">Included in MRP</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <h4 class="fw-bold mb-4">Payment Refund Timelines</h4>
                <div class="p-4 bg-light rounded">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span>UPI & Wallets</span>
                            <span class="fw-bold">24 - 48 Hours</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span>Debit & Credit Cards</span>
                            <span class="fw-bold">5 - 7 Business Days</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Bank Transfer (NEFT)</span>
                            <span class="fw-bold">2 - 3 Business Days</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>

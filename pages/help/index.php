<?php
// Page Setup
$page_title = "Help & FAQ - Amadika Support";
include '../../includes/header.php'; 
?>

<style>
    /* --- Help Page Specific Styles --- */
    .help-hero {
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
    .help-hero h1 { font-size: 2.5rem; font-weight: 700; }
    
    .section-padding { padding: 60px 0; }
    
    .faq-accordion .accordion-item {
        border: none;
        margin-bottom: 20px;
        border-radius: 8px !important;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .faq-accordion .accordion-button {
        padding: 20px;
        font-weight: 600;
        background-color: #fff;
    }
    .faq-accordion .accordion-button:not(.collapsed) {
        color: #ff9f00;
        background-color: #fff8e1;
        box-shadow: none;
    }
    .faq-accordion .accordion-body {
        padding: 20px;
        color: #666;
        line-height: 1.8;
    }
    .search-support {
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        margin-top: -60px;
        position: relative;
        z-index: 10;
        border: 1px solid #eee;
    }
    .category-link {
        display: block;
        padding: 15px;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        text-decoration: none;
        color: #444;
        transition: all 0.3s;
        margin-bottom: 10px;
    }
    .category-link:hover, .category-link.active {
        background: #ff9f00;
        color: #fff;
        border-color: #ff9f00;
    }
</style>

<!-- Hero Section -->
<section class="help-hero">
    <div class="container">
        <h1 data-aos="fade-up">How can we help?</h1>
    </div>
</section>

<!-- Content -->
<section class="section-padding">
    <div class="container">
        <!-- Search bar/Info box -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="search-support text-center mb-5">
                    <h4 class="fw-bold mb-3">Find answers to your questions</h4>
                    <p class="text-muted">Browse categories or look through our most common FAQs below.</p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Sidebar Categories -->
            <div class="col-lg-3 mb-4">
                <h5 class="fw-bold mb-4">Help Categories</h5>
                <a href="#" class="category-link active"><i class="fas fa-shopping-cart me-2"></i> Orders & Shopping</a>
                <a href="#" class="category-link"><i class="fas fa-truck me-2"></i> Shipping & Delivery</a>
                <a href="#" class="category-link"><i class="fas fa-undo me-2"></i> Returns & Refunds</a>
                <a href="#" class="category-link"><i class="fas fa-user-circle me-2"></i> My Account</a>
                <a href="#" class="category-link"><i class="fas fa-credit-card me-2"></i> Payment Issues</a>
            </div>

            <!-- FAQs -->
            <div class="col-lg-9">
                <div class="faq-accordion accordion" id="helpAccordion">
                    <!-- Q1 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
                                How do I track my order?
                            </button>
                        </h2>
                        <div id="q1" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                You can track your order by clicking on the 'Track Order' link in your order confirmation email. Alternatively, log in to your account and visit the 'My Orders' section to see live status updates.
                            </div>
                        </div>
                    </div>
                    <!-- Q2 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
                                What are your delivery charges?
                            </button>
                        </h2>
                        <div id="q2" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                We offer free standard shipping on all orders above ₹999. For orders below this amount, a flat delivery fee of ₹49-99 is applicable depending on your location.
                            </div>
                        </div>
                    </div>
                    <!-- Q3 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q3">
                                Can I cancel my order?
                            </button>
                        </h2>
                        <div id="q3" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                Yes, you can cancel your order before it has been shipped. Navigate to your 'My Orders' section and click on 'Cancel Order'. If the order has already been shipped, you can refuse the delivery or return it after receipt.
                            </div>
                        </div>
                    </div>
                    <!-- Q4 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q4">
                                Is my payment secure?
                            </button>
                        </h2>
                        <div id="q4" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                Absolutely. We use industry-standard encryption and secure payment gateways like Razorpay to process all transactions. Your sensitive data is never stored on our servers.
                            </div>
                        </div>
                    </div>
                    <!-- Q5 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q5">
                                Do you offer Cash on Delivery (COD)?
                            </button>
                        </h2>
                        <div id="q5" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                Yes, COD is available for most pin codes across India for orders up to ₹10,000. A small COD handling fee may be applicable for some regions.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Still need help? -->
                <div class="mt-5 p-4 bg-light rounded text-center">
                    <h5 class="fw-bold mb-2">Still have questions?</h5>
                    <p class="text-muted mb-3">If you couldn't find what you were looking for, our team is always here to help.</p>
                    <a href="../contact-us/" class="btn btn-warning text-white fw-bold">Contact Support</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>

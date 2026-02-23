<?php
// Page Setup
$page_title = "Warranty, Return & Refund - Amadika";
include '../../includes/header.php'; 
?>

<style>
    /* --- Policy Page Specific Styles --- */
    .policy-hero {
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
    .policy-hero h1 { font-size: 2.5rem; font-weight: 700; }
    
    .section-padding { padding: 60px 0; }
    
    .policy-content {
        background: #fff;
        padding: 40px;
        border-radius: 8px;
        border: 1px solid #eaeaea;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .policy-section { margin-bottom: 30px; }
    .policy-section h3 { 
        font-size: 1.5rem; 
        font-weight: 600; 
        color: #212121; 
        margin-bottom: 15px;
        position: relative;
        padding-bottom: 10px;
    }
    .policy-section h3::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background: #ff9f00;
        border-radius: 2px;
    }
    .policy-section p, .policy-section li { 
        color: #666; 
        line-height: 1.8; 
        font-size: 15px;
    }
    .policy-section ul { padding-left: 20px; }
    .last-updated { font-style: italic; color: #888; font-size: 13px; margin-bottom: 20px; }
</style>

<!-- Hero Section -->
<section class="policy-hero">
    <div class="container">
        <h1 data-aos="fade-up">Warranty, Return & Refund</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">Simple and transparent policies for your peace of mind.</p>
    </div>
</section>

<!-- Content Section -->
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="policy-content">
                    <p class="last-updated">Last Updated: February 23, 2026</p>
                    
                    <div class="policy-section">
                        <h3>1. Warranty Policy</h3>
                        <p>We take pride in the quality of our products. Most Amadika products come with a standard 1-year limited warranty against manufacturing defects from the date of purchase.</p>
                        <ul>
                            <li>The warranty covers defects in materials and workmanship.</li>
                            <li>It does not cover damage caused by misuse, accidents, or normal wear and tear.</li>
                            <li>To claim warranty, please provide your original invoice or order ID.</li>
                        </ul>
                    </div>

                    <div class="policy-section">
                        <h3>2. Return Policy</h3>
                        <p>If you're not completely satisfied with your purchase, you can return it within 7 days of delivery.</p>
                        <ul>
                            <li>Items must be in their original packaging, unused, and with all tags attached.</li>
                            <li>Personalized or custom-made items are not eligible for return.</li>
                            <li>Hygiene-sensitive products (like certain innerwear or beauty tools) cannot be returned once opened.</li>
                        </ul>
                    </div>

                    <div class="policy-section">
                        <h3>3. Exchange Process</h3>
                        <p>Need a different size or color? We offer a one-time free exchange for eligible items within the 7-day return window. Contact our support team to initiate the process.</p>
                    </div>

                    <div class="policy-section">
                        <h3>4. Refund Conditions</h3>
                        <p>Once we receive and inspect your returned item, we will notify you of the approval or rejection of your refund.</p>
                        <ul>
                            <li>Approved refunds are processed within 5-7 business days.</li>
                            <li>Refunds are credited back to the original payment method (Credit Card, Debit Card, Net Banking, or UPI).</li>
                            <li>For Cash on Delivery (COD) orders, refunds will be issued to your bank account via UPI or transfer.</li>
                        </ul>
                    </div>

                    <div class="policy-section">
                        <h3>5. Non-Refundable Items</h3>
                        <p>Certain items are non-refundable, including gift cards, downloadable software products, and items marked as "Final Sale".</p>
                    </div>

                    <div class="policy-section">
                        <h3>6. Contact Our Support</h3>
                        <p>Email us at returns@amadika.in for any assistance regarding your returns or warranty claims.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>

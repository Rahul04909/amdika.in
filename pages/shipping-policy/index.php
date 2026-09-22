<?php
// Page Setup
$page_title = "Shipping & Delivery Policy - Amadika";
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
        <h1 data-aos="fade-up">Shipping & Delivery</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">Quick, safe, and reliable delivery to your doorstep.</p>
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
                        <h3>1. Order Processing Time</h3>
                        <p>All orders are processed within 1-2 business days. Orders are not shipped or delivered on weekends or public holidays.</p>
                        <p>If we are experiencing a high volume of orders, shipments may be delayed by a few days. Please allow additional days in transit for delivery.</p>
                    </div>

                    <div class="policy-section">
                        <h3>2. Shipping Destinations & Rates</h3>
                        <p>We ship to all major cities and towns across India. Shipping charges for your order will be calculated and displayed at checkout.</p>
                        <ul>
                            <li>Standard Shipping: Free on orders above ₹999.</li>
                            <li>Express Shipping: Additional charges applicable based on weight and distance.</li>
                        </ul>
                    </div>

                    <div class="policy-section">
                        <h3>3. Delivery Estimates</h3>
                        <p>Delivery usually takes between 3 to 7 business days depending on your location. Metro cities often see faster delivery (2-4 days).</p>
                    </div>

                    <div class="policy-section">
                        <h3>4. Tracking Your Order</h3>
                        <p>You will receive a Shipment Confirmation email once your order has shipped containing your tracking number(s). The tracking number will be active within 24 hours.</p>
                    </div>

                    <div class="policy-section">
                        <h3>5. Damaged During Transit</h3>
                        <p>Amadika is liable for any products damaged or lost during shipping. If you received your order damaged, please contact us immediately to file a claim. Please save all packaging materials and damaged goods before filing a claim.</p>
                    </div>

                    <div class="policy-section">
                        <h3>6. Contact Us</h3>
                        <p>If you have any further questions about our shipping and delivery, please contact us at delivery@amadika.in.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>

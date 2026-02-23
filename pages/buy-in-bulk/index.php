<?php
// Page Setup
$page_title = "Buy In Bulk / B2B - Amadika";
include '../../includes/header.php'; 
?>

<style>
    /* --- Bulk Page Specific Styles --- */
    .bulk-hero {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('../../assets/images/banners/banner-1.png');
        background-size: cover;
        background-position: center;
        height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
    }
    .bulk-hero h1 { font-size: 3rem; font-weight: 700; }
    
    .section-padding { padding: 60px 0; }
    
    .bulk-feature {
        padding: 30px;
        border-radius: 8px;
        background: #f8f9fa;
        text-align: center;
        height: 100%;
        border: 1px solid #eee;
    }
    .feature-icon {
        font-size: 35px;
        color: #ff9f00;
        margin-bottom: 15px;
    }
    .enquiry-card {
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        border-top: 5px solid #ff9f00;
    }
</style>

<!-- Hero Section -->
<section class="bulk-hero">
    <div class="container">
        <h1 data-aos="fade-up">Grow With Amadika</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">Wholesale pricing and dedicated support for bulk orders.</p>
    </div>
</section>

<!-- Features -->
<section class="section-padding">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="bulk-feature">
                    <div class="feature-icon"><i class="fas fa-tags"></i></div>
                    <h5>Wholesale Pricing</h5>
                    <p class="text-muted mb-0">Get up to 40% discount on major product categories when you order in volume.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bulk-feature">
                    <div class="feature-icon"><i class="fas fa-user-tie"></i></div>
                    <h5>Dedicated Account Manager</h5>
                    <p class="text-muted mb-0">A single point of contact for all your order tracking and customization needs.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bulk-feature">
                    <div class="feature-icon"><i class="fas fa-shipping-fast"></i></div>
                    <h5>Priority Shipping</h5>
                    <p class="text-muted mb-0">Free pallets shipping and priority dispatch for all verified bulk partners.</p>
                </div>
            </div>
        </div>

        <div class="row align-items-center mt-5 pt-4">
            <div class="col-lg-5 mb-5 mb-lg-0">
                <h5 class="text-warning text-uppercase fw-bold">Bulk Enquiry</h5>
                <h2 class="fw-bold mb-4">Let's build a partnership</h2>
                <p class="text-muted" style="line-height: 1.8;">
                    Whether you are an institution, a retail store owner, or planning a corporate gift event, Amadika provides the most competitive rates and quality assurance.
                </p>
                <div class="mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="fw-bold">GST Invoice Available</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="fw-bold">Global Shipping Support</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="fw-bold">Custom Branding Options</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="enquiry-card">
                    <h4 class="fw-bold mb-4">Request a Bulk Quote</h4>
                    <form action="#" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Rahul Sharma" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Business Email</label>
                                <input type="email" class="form-control" name="email" placeholder="rahul@company.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" name="phone" placeholder="+91 00000 00000" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Business Name</label>
                                <input type="text" class="form-control" name="business" placeholder="Amadika Enterprise" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Estimated Order Value (₹)</label>
                                <select class="form-select" name="value">
                                    <option value="50-100k">₹50,000 - ₹1,00,000</option>
                                    <option value="100-500k">₹1,00,000 - ₹5,00,000</option>
                                    <option value="500k+">₹5,00,000+</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Tell us about your requirements</label>
                                <textarea class="form-control" name="message" rows="4" placeholder="Mention products and quantities..."></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning text-white fw-bold w-100 py-3 mt-4">Submit Enquiry</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>

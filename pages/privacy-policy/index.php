<?php
// Page Setup
$page_title = "Privacy Policy - Amadika";
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
        <h1 data-aos="fade-up">Privacy Policy</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">Your privacy is our top priority.</p>
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
                        <h3>1. Introduction</h3>
                        <p>Welcome to Amadika. We are committed to protecting your personal information and your right to privacy. If you have any questions or concerns about our policy, or our practices with regards to your personal information, please contact us at support@amadika.in.</p>
                    </div>

                    <div class="policy-section">
                        <h3>2. Information We Collect</h3>
                        <p>We collect personal information that you voluntarily provide to us when you register on the Website, express an interest in obtaining information about us or our products, or otherwise when you contact us.</p>
                        <ul>
                            <li>Personal Data: Name, shipping address, email address, and telephone number.</li>
                            <li>Payment Data: Payment instrument information (handled by secure payment gateways like Razorpay).</li>
                            <li>Usage Data: Information about how you use our website, products, and services.</li>
                        </ul>
                    </div>

                    <div class="policy-section">
                        <h3>3. How We Use Your Information</h3>
                        <p>We use personal information collected via our Website for a variety of business purposes described below:</p>
                        <ul>
                            <li>To facilitate account creation and logon process.</li>
                            <li>To fulfill and manage your orders.</li>
                            <li>To send administrative information to you.</li>
                            <li>To post testimonials with your consent.</li>
                            <li>To deliver targeted advertising to you.</li>
                        </ul>
                    </div>

                    <div class="policy-section">
                        <h3>4. Data Security</h3>
                        <p>We have implemented appropriate technical and organizational security measures designed to protect the security of any personal information we process. However, please also remember that we cannot guarantee that the internet itself is 100% secure.</p>
                    </div>

                    <div class="policy-section">
                        <h3>5. Cookies</h3>
                        <p>We may use cookies and similar tracking technologies to access or store information. You can set your browser to refuse all or some browser cookies, but some parts of the Website may then be inaccessible or not function properly.</p>
                    </div>

                    <div class="policy-section">
                        <h3>6. Contact Us</h3>
                        <p>If you have questions or comments about this policy, you may email us at support@amadika.in or visit our Contact Us page.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>

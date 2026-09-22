<?php
// Page Setup
$page_title = "Terms & Conditions - Amadika";
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
        <h1 data-aos="fade-up">Terms & Conditions</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">The rules of the road for using Amadika.</p>
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
                        <h3>1. Acceptance of Terms</h3>
                        <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement. In addition, when using these particular services, you shall be subject to any posted guidelines or rules applicable to such services.</p>
                    </div>

                    <div class="policy-section">
                        <h3>2. User Accounts</h3>
                        <p>If you create an account on the Website, you are responsible for maintaining the security of your account and you are fully responsible for all activities that occur under the account. You must immediately notify Amadika of any unauthorized uses of your account or any other breaches of security.</p>
                    </div>

                    <div class="policy-section">
                        <h3>3. Intellectual Property</h3>
                        <p>The Website and its original content, features, and functionality are owned by Amadika and are protected by international copyright, trademark, patent, trade secret, and other intellectual property or proprietary rights laws.</p>
                    </div>

                    <div class="policy-section">
                        <h3>4. Prohibited Activities</h3>
                        <p>You may not use the Website for any purpose that is unlawful or prohibited by these terms. You may not use the Website in any manner that could damage, disable, overburden, or impair the Website or interfere with any other party's use and enjoyment of the Website.</p>
                    </div>

                    <div class="policy-section">
                        <h3>5. Limitation of Liability</h3>
                        <p>In no event shall Amadika, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your access to or use of or inability to access or use the Website.</p>
                    </div>

                    <div class="policy-section">
                        <h3>6. Governing Law</h3>
                        <p>These Terms shall be governed and construed in accordance with the laws of India, without regard to its conflict of law provisions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>

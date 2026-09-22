<?php
// Page Setup
$page_title = "Contact Us - Amadika";
include '../../includes/header.php'; 
?>

<style>
    /* --- Contact Us Specific Styles --- */
    .contact-hero {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('../../assets/images/banners/banner-1.png');
        background-size: cover;
        background-position: center;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
    }
    .contact-hero h1 { font-size: 3rem; font-weight: 700; }
    
    .section-padding { padding: 60px 0; }
    
    .contact-card {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        text-align: center;
        border: 1px solid #eaeaea;
        transition: transform 0.3s;
        height: 100%;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    .contact-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
    .contact-icon {
        width: 60px; height: 60px;
        background: #fff8e1;
        color: #ff9f00;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
    }
    
    .form-control {
        padding: 12px 15px;
        border: 1px solid #eee;
        background-color: #f9f9f9;
        font-size: 14px;
        border-radius: 4px;
    }
    .form-control:focus {
        border-color: #ff9f00;
        box-shadow: none;
        background-color: #fff;
    }
    .map-container {
        height: 100%;
        min-height: 400px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    iframe { width: 100%; height: 100%; border: 0; }
</style>

<!-- Hero Section -->
<section class="contact-hero">
    <div class="container">
        <h1 data-aos="fade-up">Get in Touch</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">We'd love to hear from you. Here's how you can reach us.</p>
    </div>
</section>

<!-- Contact Info Cards -->
<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h5>Our Head Office</h5>
                    <p class="text-muted mb-0">  A-14 Dlf-I nhpc faridabad Haryana 121003</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                    <h5>Call Us</h5>
                    <!-- <p class="text-muted mb-1">+91 98716 26089</p> -->
                    <p class="text-muted mb-1">+91 84476 16924</p>
                    <p class="text-muted mb-0">Mon - Sat, 9am - 7pm</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <h5>Email Us</h5>
                    <p class="text-muted mb-1">support@amadika.in</p>
                    <p class="text-muted mb-0">info@amadika.in</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form & Map Section -->
<section class="section-padding pt-0">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-6">
                <div class="mb-4">
                    <h5 class="text-warning text-uppercase fw-bold">Send a Message</h5>
                    <h2 class="fw-bold">We are here to help</h2>
                </div>
                <form action="#" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Your Name</label>
                                <input type="text" class="form-control" name="name" placeholder="John Doe" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Your Email</label>
                                <input type="email" class="form-control" name="email" placeholder="john@example.com" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject</label>
                        <input type="text" class="form-control" name="subject" placeholder="How can we help?" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Message</label>
                        <textarea class="form-control" name="message" rows="5" placeholder="Write your message here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning text-white fw-bold px-4 py-3 rounded-pill">Send Message</button>
                </form>
            </div>
            
            <!-- Map -->
            <div class="col-lg-6">
                <div class="map-container">
                    <!-- Google Map Embed Placeholder -->
                    <iframe src="https://maps.google.com/maps?q=28.460806,77.309394&z=15&output=embed" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>

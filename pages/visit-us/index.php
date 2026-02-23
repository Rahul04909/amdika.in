<?php
// Page Setup
$page_title = "Visit Us - Amadika Store";
include '../../includes/header.php'; 
?>

<style>
    /* --- Visit Us Specific Styles --- */
    .visit-hero {
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
    .visit-hero h1 { font-size: 3rem; font-weight: 700; }
    
    .section-padding { padding: 60px 0; }
    
    .info-card {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        border: 1px solid #eaeaea;
        height: 100%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .info-icon {
        font-size: 30px;
        color: #ff9f00;
        margin-bottom: 20px;
    }
    .map-container {
        height: 450px;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .store-img-wrapper {
        border-radius: 8px;
        overflow: hidden;
        height: 250px;
        margin-bottom: 20px;
    }
    .store-img { width: 100%; height: 100%; object-fit: cover; }
    
    .timing-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #eee;
    }
    .timing-row:last-child { border-bottom: none; }
</style>

<!-- Hero Section -->
<section class="visit-hero">
    <div class="container">
        <h1 data-aos="fade-up">Visit Our Store</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">Experience the Amadika quality in person.</p>
    </div>
</section>

<!-- Store Info Section -->
<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="info-card">
                    <div class="info-icon"><i class="fas fa-map-marked-alt"></i></div>
                    <h4 class="fw-bold">Our Location</h4>
                    <p class="text-muted">123 Commerce Plaza, Main Mall Road,<br>Greater Kailash, New Delhi,<br>India - 110048</p>
                    <hr>
                    <p class="text-muted"><i class="fas fa-phone me-2"></i> +91 98765 43210</p>
                    <p class="text-muted"><i class="fas fa-envelope me-2"></i> store@amadika.in</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="info-card">
                    <div class="info-icon"><i class="fas fa-clock"></i></div>
                    <h4 class="fw-bold">Operating Hours</h4>
                    <div class="timing-row"><span>Monday - Friday</span><span class="fw-bold">10:00 AM - 08:30 PM</span></div>
                    <div class="timing-row"><span>Saturday</span><span class="fw-bold">10:00 AM - 09:00 PM</span></div>
                    <div class="timing-row"><span>Sunday</span><span class="fw-bold text-danger">Closed</span></div>
                    <p class="text-muted mt-3 small">* Timing may vary on public holidays.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="info-card">
                    <div class="info-icon"><i class="fas fa-store"></i></div>
                    <h4 class="fw-bold">Store Highlights</h4>
                    <ul class="text-muted ps-3">
                        <li class="mb-2">Exclusive In-store discounts</li>
                        <li class="mb-2">Free product personalized demo</li>
                        <li class="mb-2">Easy exchanges & returns</li>
                        <li class="mb-2">Expert style consultations</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Store Images -->
        <div class="row g-4 mt-4">
            <div class="col-md-4">
                <div class="store-img-wrapper">
                    <img src="../../assets/images/banners/banner-1.png" alt="Store Front" class="store-img">
                </div>
            </div>
            <div class="col-md-4">
                <div class="store-img-wrapper">
                    <img src="../../assets/images/banners/banner-1.png" alt="Interior" class="store-img">
                </div>
            </div>
            <div class="col-md-4">
                <div class="store-img-wrapper">
                    <img src="../../assets/images/banners/banner-1.png" alt="Product Display" class="store-img">
                </div>
            </div>
        </div>

        <!-- Map -->
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d224345.83923192776!2d77.0688975472412!3d28.52728034389636!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd5b347eb62d%3A0x37205b715389640!2sDelhi!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>

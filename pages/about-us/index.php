<?php
// Page Setup
$page_title = "About Us - Amadika";
include '../../includes/header.php'; 
?>

<style>
    /* --- About Us Specific Styles --- */
    .about-hero {
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
    
    .about-hero h1 { font-size: 3rem; font-weight: 700; margin-bottom: 15px; }
    .about-hero p { font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto; }

    .section-padding { padding: 60px 0; }
    
    .story-img-wrapper {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .story-img { width: 100%; height: auto; transition: transform 0.5s; }
    .story-img-wrapper:hover .story-img { transform: scale(1.05); }

    .value-card {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        text-align: center;
        border: 1px solid #eee;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
    }
    .value-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        border-color: #ff9f00; /* Brand Warning Color */
    }
    .value-icon {
        font-size: 40px;
        color: #ff9f00;
        margin-bottom: 20px;
        display: inline-block;
    }
    
    .stats-section {
        background-color: #f8f9fa;
        text-align: center;
    }
    .stat-number { font-size: 2.5rem; font-weight: 700; color: #212121; }
    .stat-label { font-size: 1rem; color: #666; text-transform: uppercase; letter-spacing: 1px; }

    .team-img {
        width: 120px; 
        height: 120px; 
        border-radius: 50%; 
        object-fit: cover;
        margin-bottom: 15px;
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>

<!-- Hero Section -->
<section class="about-hero">
    <div class="container">
        <h1 data-aos="fade-up">Who We Are</h1>
        <p data-aos="fade-up" data-aos-delay="100">Building the future of e-commerce with passion, integrity, and innovation since 1999.</p>
    </div>
</section>

<!-- Our Story Section -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="story-img-wrapper">
                    <img src="../../assets/images/demo-data/product.jpg" alt="Our Story" class="story-img">
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <h5 class="text-warning text-uppercase fw-bold mb-2">Our Story</h5>
                <h2 class="fw-bold mb-4">From Humble Beginnings to Industry Leaders</h2>
                <p class="text-muted mb-3" style="line-height: 1.8;">
                    Amadika started with a simple idea: to make quality products accessible to everyone. What began as a small local shop has grown into a premier online destination for customers worldwide. We believe in the power of technology to connect people with the things they love.
                </p>
                <p class="text-muted" style="line-height: 1.8;">
                    Over 25 years, we have served millions of happy customers, constantly evolving our collection to match modern trends while maintaining timeless quality. Our journey is driven by a single mission - customer satisfaction above all else.
                </p>
                <div class="mt-4">
                    <img src="../../assets/images/amdika-logo.png" alt="Signature" style="height: 40px; opacity: 0.8;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section-padding pt-0">
    <div class="container">
        <div class="text-center mb-5">
            <h5 class="text-warning text-uppercase fw-bold">Why Choose Us</h5>
            <h2 class="fw-bold">Our Core Values</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-gem"></i></div>
                    <h4>Premium Quality</h4>
                    <p class="text-muted">We never compromise on quality. Every product is handpicked and tested to ensure it meets our rigorous standards.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-users"></i></div>
                    <h4>Customer First</h4>
                    <p class="text-muted">You are at the heart of everything we do. Our dedicated support team is here to ensure your experience is seamless.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-truck-fast"></i></div>
                    <h4>Fast Delivery</h4>
                    <p class="text-muted">We understand the excitement of a new purchase. Our logistics network ensures your order reaches you in record time.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section-padding stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <div class="stat-number">25+</div>
                <div class="stat-label">Years Experience</div>
            </div>
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <div class="stat-number">10k+</div>
                <div class="stat-label">Products</div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-number">1M+</div>
                <div class="stat-label">Happy Customers</div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Support</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section-padding text-center">
    <div class="container">
        <h2 class="fw-bold mb-3">Ready to start shopping?</h2>
        <p class="text-muted mb-4">Explore our latest collection and find exactly what you need.</p>
        <a href="../../products.php" class="btn btn-warning text-white px-5 py-3 fw-bold rounded-pill shadow-sm">Explore Collection</a>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>

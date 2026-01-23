<style>
/* --- Hero Slider Component --- */
.hero-section {
    position: relative;
    overflow: hidden;
    margin-top: 1px; /* Separation from header */
}

/* Base Carousel Item Styling */
.hero-carousel-item {
    height: 400px; /* Default Desktop Height */
    background-color: #f0f0f0; /* Fallback */
    position: relative;
}

.hero-carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures image covers area without distortion */
    object-position: center;
}

/* Premium Navigation Buttons */
.hero-control-btn {
    width: 45px;
    height: 90px;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 4px; /* Slight rounding but rectangular feel like Flipkart */
    opacity: 0; /* Hidden by default */
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.1);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.hero-section:hover .hero-control-btn {
    opacity: 1; /* Show on hover */
}

.carousel-control-prev.hero-control-btn {
    left: 0;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.carousel-control-next.hero-control-btn {
    right: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.hero-control-icon {
    color: var(--secondary-color, #2D3436);
    font-size: 20px;
}

/* User dots at bottom */
.carousel-indicators [data-bs-target] {
    background-color: var(--white, #fff);
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid transparent; /* Hitbox increase */
    opacity: 0.7;
    margin: 0 5px;
}

.carousel-indicators .active {
    background-color: var(--accent-gold, #D4A017);
    opacity: 1;
    transform: scale(1.2);
}

/* Mobile Optimizations */
@media (max-width: 991px) {
    .hero-carousel-item {
        height: 300px;
    }
}

@media (max-width: 576px) {
    .hero-carousel-item {
        height: 180px; /* Compact mobile slider like apps */
    }
    
    .hero-control-btn {
        display: none; /* Hide arrows on mobile, swipe is natural */
    }
}
</style>

<section class="hero-section mb-3">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
        <!-- Indicators -->
        <div class="carousel-indicators mb-3">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active hero-carousel-item">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=2070&auto=format&fit=crop" alt="Fashion Sale" class="d-block w-100">
                <!-- Optional Overlay/Content could go here -->
            </div>
            
            <!-- Slide 2 -->
            <div class="carousel-item hero-carousel-item">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2070&auto=format&fit=crop" alt="New Arrivals" class="d-block w-100">
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item hero-carousel-item">
                <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=2070&auto=format&fit=crop" alt="Special Offers" class="d-block w-100">
            </div>
        </div>

        <!-- Controls (Hidden on mobile via CSS) -->
        <button class="carousel-control-prev hero-control-btn" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <i class="fa-solid fa-chevron-left hero-control-icon"></i>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next hero-control-btn" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <i class="fa-solid fa-chevron-right hero-control-icon"></i>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

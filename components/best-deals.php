<style>
/* --- Best Deals Component --- */
.best-deals-section {
    font-family: 'Poppins', sans-serif;
    background-color: #fff;
    margin-top: 20px;
}

.best-deals-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.section-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 15px;
}

.best-deals-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #212121;
    margin: 0;
}

.view-all-btn {
    background-color: #2874f0;
    color: #fff;
    padding: 10px 20px;
    border-radius: 2px;
    font-weight: 500;
    font-size: 13px;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    transition: box-shadow 0.2s;
}

.view-all-btn:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    color: #fff;
    text-decoration: none;
}

/* Layout Grid */
.best-deals-layout {
    display: flex;
    gap: 15px;
}

.deals-slider-area {
    flex: 1;
    min-width: 0; /* Fix flex overflow */
    position: relative;
    padding: 10px 0;
}

.deals-banner-area {
    width: 230px; /* Fixed width for banner */
    flex-shrink: 0;
    display: flex;
    align-items: stretch;
}

.deals-banner-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    cursor: pointer;
    transition: opacity 0.2s;
}

.deals-banner-img:hover {
    opacity: 0.9;
}

/* Scroll Container */
.best-deals-slider {
    display: flex;
    overflow-x: auto;
    gap: 15px;
    padding-bottom: 10px;
    scroll-behavior: smooth;
    scrollbar-width: none;
}

.best-deals-slider::-webkit-scrollbar {
    display: none;
}

/* Product Card */
.bd-product-card {
    flex: 0 0 180px; /* Fixed width */
    text-align: center;
    padding: 15px;
    transition: transform 0.2s, box-shadow 0.2s;
    border: 1px solid transparent;
    border-radius: 4px;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.bd-product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-color: #f0f0f0;
    color: inherit; 
}

.bd-img-wrapper {
    width: 150px;
    height: 150px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bd-product-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s;
}

.bd-product-card:hover .bd-product-img {
    transform: scale(1.05);
}

.bd-product-name {
    font-size: 14px;
    font-weight: 500;
    color: #212121;
    margin-bottom: 5px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%;
}

.bd-price-info {
    margin-top: auto;
}

.bd-price-label {
    color: #388e3c; /* Green color for offer */
    font-size: 13px;
    margin-bottom: 2px;
}

.bd-price-value {
    font-size: 16px;
    font-weight: 600;
    color: #212121;
}

/* Navigation Buttons */
.bd-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 80px;
    background-color: rgba(255, 255, 255, 0.9);
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 5;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    opacity: 0;
    transition: opacity 0.3s;
}

.deals-slider-area:hover .bd-nav-btn {
    opacity: 1;
}

.bd-prev-btn {
    left: 0;
    border-radius: 0 4px 4px 0;
}

.bd-next-btn {
    right: 0;
    border-radius: 4px 0 0 4px;
}

/* Responsive */
@media (max-width: 991px) {
    .best-deals-layout {
        flex-direction: column;
    }
    
    .deals-banner-area {
        width: 100%;
        height: auto;
        max-height: 200px; /* Limit height on mobile */
    }

    .deals-banner-img {
        object-fit: cover;
    }
}

@media (max-width: 576px) {
    .bd-product-card {
        flex: 0 0 150px;
        padding: 10px;
    }

    .bd-img-wrapper {
        width: 120px;
        height: 120px;
    }
    
    .best-deals-title {
        font-size: 1.2rem;
    }

    .view-all-btn {
        padding: 8px 15px;
        font-size: 12px;
    }
}
</style>

<section class="best-deals-section container mb-5">
    <div class="best-deals-container">
        <!-- Header -->
        <div class="section-header-row">
            <h2 class="best-deals-title">Best Deals on Leather Bags</h2>
            <a href="#" class="view-all-btn">VIEW ALL</a>
        </div>

        <!-- Layout -->
        <div class="best-deals-layout">
            <!-- Left: Slider -->
            <div class="deals-slider-area">
                <button class="bd-nav-btn bd-prev-btn" id="bdPrevBtn"><i class="fas fa-chevron-left"></i></button>
                <button class="bd-nav-btn bd-next-btn" id="bdNextBtn"><i class="fas fa-chevron-right"></i></button>

                <div class="best-deals-slider" id="bestDealsSlider">
                    <!-- Product 1 -->
                    <a href="#" class="bd-product-card">
                        <div class="bd-img-wrapper">
                            <img src="assets/images/demo-data/product.jpg" alt="Galaxy S24" class="bd-product-img">
                        </div>
                        <h3 class="bd-product-name">Galaxy S24 5G</h3>
                        <div class="bd-price-info">
                            <div class="bd-price-label">Just ₹79,999</div>
                            <div class="bd-price-value">From ₹79,999</div>
                        </div>
                    </a>

                    <!-- Product 2 -->
                    <a href="#" class="bd-product-card">
                        <div class="bd-img-wrapper">
                             <img src="assets/images/demo-data/product.jpg" alt="iPhone 15" class="bd-product-img">
                        </div>
                        <h3 class="bd-product-name">Apple iPhone 15</h3>
                        <div class="bd-price-info">
                            <div class="bd-price-label">Incl of offers</div>
                            <div class="bd-price-value">From ₹65,999</div>
                        </div>
                    </a>
                    
                    <!-- Product 3 -->
                    <a href="#" class="bd-product-card">
                        <div class="bd-img-wrapper">
                             <img src="assets/images/demo-data/product.jpg" alt="Vivo T2 Pro" class="bd-product-img">
                        </div>
                        <h3 class="bd-product-name">Vivo T2 Pro 5G</h3>
                        <div class="bd-price-info">
                             <div class="bd-price-label">Just ₹21,999</div>
                             <div class="bd-price-value">From ₹21,999</div>
                        </div>
                    </a>

                    <!-- Product 4 -->
                    <a href="#" class="bd-product-card">
                        <div class="bd-img-wrapper">
                            <img src="assets/images/demo-data/product.jpg" alt="Poco X6" class="bd-product-img">
                        </div>
                        <h3 class="bd-product-name">Poco X6 Neo</h3>
                        <div class="bd-price-info">
                            <div class="bd-price-label">Just ₹13,999*</div>
                            <div class="bd-price-value">From ₹13,999*</div>
                        </div>
                    </a>

                    <!-- Product 5 -->
                    <a href="#" class="bd-product-card">
                        <div class="bd-img-wrapper">
                             <img src="assets/images/demo-data/product.jpg" alt="Realme 12x" class="bd-product-img">
                        </div>
                        <h3 class="bd-product-name">Realme 12x 5G</h3>
                        <div class="bd-price-info">
                             <div class="bd-price-label">Min ₹1000 Off</div>
                             <div class="bd-price-value">From ₹10,999</div>
                        </div>
                    </a>

                    <!-- Product 6 -->
                    <a href="#" class="bd-product-card">
                        <div class="bd-img-wrapper">
                             <img src="assets/images/demo-data/product.jpg" alt="Redmi Note 13" class="bd-product-img">
                        </div>
                        <h3 class="bd-product-name">Redmi Note 13</h3>
                        <div class="bd-price-info">
                             <div class="bd-price-label">From ₹15,499</div>
                             <div class="bd-price-value">From ₹15,499</div>
                        </div>
                    </a>

                     <!-- Product 7 -->
                     <a href="#" class="bd-product-card">
                        <div class="bd-img-wrapper">
                             <img src="assets/images/demo-data/product.jpg" alt="Moto G34" class="bd-product-img">
                        </div>
                        <h3 class="bd-product-name">Motorola G34 5G</h3>
                        <div class="bd-price-info">
                             <div class="bd-price-label">Just ₹10,999*</div>
                             <div class="bd-price-value">From ₹10,999*</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Right: Banner -->
            <div class="deals-banner-area">
                <img src="assets/images/banners/banner-1.png" alt="Flight Booking Offer" class="deals-banner-img img-fluid">
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('bestDealsSlider');
        const prevBtn = document.getElementById('bdPrevBtn');
        const nextBtn = document.getElementById('bdNextBtn');
        
        if (!slider) return;

        // Scroll Amount
        const getScrollAmount = () => {
             // Scroll by approx 3 cards visible on desktop, or 1 on mobile
             return slider.firstElementChild.clientWidth + 15; // Width + Gap
        };

        // Navigation
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                slider.scrollBy({ left: -(getScrollAmount() * 2), behavior: 'smooth' });
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                slider.scrollBy({ left: (getScrollAmount() * 2), behavior: 'smooth' });
            });
        }

        // Autoplay
        let autoplayInterval;
        const startAutoplay = () => {
            autoplayInterval = setInterval(() => {
                if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                    // Reset to start if reached end
                    slider.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    slider.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
                }
            }, 3000); // 3 seconds
        };

        const stopAutoplay = () => {
            clearInterval(autoplayInterval);
        };

        // Start autoplay
        startAutoplay();

        // Pause on hover
        slider.addEventListener('mouseenter', stopAutoplay);
        slider.addEventListener('mouseleave', startAutoplay);
    });
</script>

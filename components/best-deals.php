<?php
// Fetch Settings
$bd_settings_result = $conn->query("SELECT category_ids FROM best_deals_settings WHERE id = 1");
$bd_category_ids = [];
if ($bd_settings_result && $bd_settings_result->num_rows > 0) {
    $row = $bd_settings_result->fetch_assoc();
    $bd_category_ids = json_decode($row['category_ids'], true) ?: [];
}

// Fetch Products if categories are selected
$bd_products = [];
if (!empty($bd_category_ids)) {
    // Sanitize again just to be safe
    $bd_ids_clean = array_map('intval', $bd_category_ids);
    $bd_ids_str = implode(',', $bd_ids_clean);
    
    // Query Limit 10 for best deals
    $sql = "SELECT * FROM products WHERE category_id IN ($bd_ids_str) ORDER BY id DESC LIMIT 10";
    $result = $conn->query($sql);
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $bd_products[] = $row;
        }
    }
}
?>
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
    height: 0; 
    min-height: 100%;
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
        /* Removed max-height to allow full banner visibility without overlap */
        margin-top: 15px; /* Add spacing between slider and banner */
        border-radius: 4px;
        overflow: hidden;
    }

    .deals-banner-img {
        width: 100%;
        height: auto; /* Allow natural height */
        min-height: auto;
        object-fit: cover;
        display: block; /* Remove inline-block spacing */
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
            <h2 class="best-deals-title">Best Deals</h2>
            <a href="products.php" class="view-all-btn">VIEW ALL</a>
        </div>

        <!-- Layout -->
        <div class="best-deals-layout">
            <!-- Left: Slider -->
            <div class="deals-slider-area">
                <button class="bd-nav-btn bd-prev-btn" id="bdPrevBtn"><i class="fas fa-chevron-left"></i></button>
                <button class="bd-nav-btn bd-next-btn" id="bdNextBtn"><i class="fas fa-chevron-right"></i></button>

                <div class="best-deals-slider" id="bestDealsSlider">
                    <?php if (!empty($bd_products)): ?>
                        <?php foreach($bd_products as $prod): ?>
                            <!-- Product -->
                            <a href="product-details.php?slug=<?php echo $prod['slug']; ?>" class="bd-product-card">
                                <div class="bd-img-wrapper">
                                    <img src="<?php echo !empty($prod['featured_image']) ? $prod['featured_image'] : 'assets/images/demo-data/product.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($prod['name']); ?>" 
                                         class="bd-product-img">
                                </div>
                                <h3 class="bd-product-name"><?php echo htmlspecialchars($prod['name']); ?></h3>
                                <div class="bd-price-info">
                                    <?php if($prod['discount_percent'] > 0): ?>
                                        <div class="bd-price-label">Min <?php echo $prod['discount_percent']; ?>% Off</div>
                                    <?php endif; ?>
                                    <div class="bd-price-value">₹<?php echo number_format($prod['sale_price']); ?></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-4 text-center w-100 text-muted">
                            <i class="fas fa-box-open fa-2x mb-2"></i>
                            <p>No Best Deals currently available.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: Banner -->
            <div class="deals-banner-area">
                <img src="assets/images/banners/banner-1.png" alt="Offer Banner" class="deals-banner-img img-fluid">
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
             // Check if children exist
             if(slider.firstElementChild) {
                return slider.firstElementChild.clientWidth + 15; // Width + Gap
             }
             return 200;
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
            // Only autoplay if content overflows
            if (slider.scrollWidth <= slider.clientWidth) return;

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

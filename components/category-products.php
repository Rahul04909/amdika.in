<style>
/* --- Category Products Component --- */
.category-products-section {
    background-color: #fff;
    padding: 16px 0;
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    padding: 16px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 10px;
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.05);
}

.category-title {
    font-size: 22px;
    font-weight: 600;
    color: #212121;
    margin: 0;
}

.view-all-btn {
    background-color: #2874f0;
    color: #fff;
    font-weight: 500;
    padding: 10px 20px;
    border-radius: 2px;
    text-decoration: none;
    box-shadow: 0 2px 4px 0 rgba(0,0,0,.2);
    font-size: 13px;
    text-transform: uppercase;
}

.view-all-btn:hover {
    color: #fff;
    box-shadow: 0 4px 6px 0 rgba(0,0,0,.2);
}

/* --- Hybrid Product Container --- */
.cp-product-container {
    padding: 10px 5px;
}

/* Desktop: Slider Layout */
@media (min-width: 992px) {
    .cp-product-container {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none; /* Hide scrollbar Firefox */
    }
    .cp-product-container::-webkit-scrollbar {
        display: none; /* Hide scrollbar Chrome/Safari */
    }
    
    .cp-product-item {
        flex: 0 0 220px; /* Fixed width for slider items */
        min-width: 220px;
    }

    /* Navigation Buttons (Desktop only) */
    .cp-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 90px;
        background-color: rgba(255, 255, 255, 0.9);
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 5;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        opacity: 0; /* Hidden by default */
        transition: opacity 0.3s;
    }

    .category-products-section:hover .cp-nav-btn {
        opacity: 1;
    }

    .cp-prev-btn { left: 0; border-radius: 0 4px 4px 0; }
    .cp-next-btn { right: 0; border-radius: 4px 0 0 4px; }
    
    .cp-load-more-container { display: none; } /* Hide load more on desktop */
}

/* Mobile: Grid Layout */
@media (max-width: 991px) {
    .cp-product-container {
        display: grid;
        grid-template-columns: 1fr 1fr; /* 2 Columns */
        gap: 10px;
        padding: 0 10px;
    }
    
    .cp-product-item {
        width: 100%;
    }

    /* Hide items beyond first 4 initially */
    .cp-mobile-hidden {
        display: none;
    }

    .cp-nav-btn { display: none; } /* Hide slider buttons on mobile */

    .cp-load-more-container {
        text-align: center;
        margin-top: 20px;
        padding-bottom: 20px;
        width: 100%;
    }

    .cp-load-more-btn {
        background: #fff;
        border: 1px solid #f0f0f0;
        color: #2874f0;
        font-weight: 500;
        padding: 10px 40px;
        border-radius: 2px;
        box-shadow: 0 2px 4px 0 rgba(0,0,0,.2);
        font-size: 14px;
        text-transform: uppercase;
        width: 90%;
    }
}


/* --- Product Card Core Styling --- */
.premium-product-card {
    background: #fff;
    position: relative;
    display: flex;
    flex-direction: column;
    padding: 16px;
    height: 100%;
    transition: box-shadow 0.2s ease, transform 0.1s;
    border: 1px solid #f0f0f0; /* Subtle border for definition */
    border-radius: 4px;
}

.premium-product-card:hover {
    box-shadow: 0 3px 16px 0 rgba(0,0,0,.11);
    transform: translateY(-2px);
    z-index: 2;
    border-color: transparent;
}

.card-wishlist-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    color: #c2c2c2;
    font-size: 18px;
    cursor: pointer;
    z-index: 10;
    background: none;
    border: none;
}
.card-wishlist-btn:hover { color: #ff4343; }

.product-img-wrapper {
    position: relative;
    width: 100%;
    height: 180px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.rating-badge {
    background-color: #388e3c;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 3px;
    display: inline-flex;
    align-items: center;
    margin-right: 8px;
}
.rating-badge i { font-size: 10px; margin-left: 2px; }
.review-count { color: #878787; font-size: 13px; font-weight: 500; }

.product-title {
    font-size: 14px;
    font-weight: 500;
    color: #212121;
    margin-top: 8px;
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
    height: 40px; 
}

.price-container {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    margin-top: 4px;
}

.current-price { font-size: 18px; font-weight: 600; color: #212121; margin-right: 10px; }
.original-price { font-size: 14px; color: #878787; text-decoration: line-through; margin-right: 10px; }
.discount-text { font-size: 13px; color: #388e3c; font-weight: 700; }

/* Mobile Card Adjustments */
@media (max-width: 767px) {
    .premium-product-card { padding: 10px; }
    .product-img-wrapper { height: 140px; }
    .product-title { font-size: 13px; height: 36px; }
    .current-price { font-size: 15px; }
    .rating-badge { padding: 1px 4px; font-size: 11px; }
}
</style>

<section class="category-products-section position-relative">
    <div class="container container-custom-rounded bg-white p-0 position-relative">
        <!-- Header -->
        <div class="category-header rounded-top">
            <h2 class="category-title">Gardening & Landscaping</h2>
            <a href="#" class="view-all-btn d-none d-lg-block">View All</a>
        </div>

        <!-- Desktop Navigation Buttons -->
        <button class="cp-nav-btn cp-prev-btn" id="cpPrevBtn"><i class="fas fa-chevron-left"></i></button>
        <button class="cp-nav-btn cp-next-btn" id="cpNextBtn"><i class="fas fa-chevron-right"></i></button>

        <!-- Products Container -->
        <div class="cp-product-container" id="cpProductContainer">
            
            <!-- Product 1 -->
            <div class="cp-product-item">
                <div class="premium-product-card">
                    <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                    <div class="product-img-wrapper">
                        <img src="assets/images/demo-data/product.jpg" class="product-img">
                    </div>
                    <div><span class="rating-badge">4.4 <i class="fa-solid fa-star"></i></span><span class="review-count">(1,234)</span></div>
                    <h3 class="product-title">Neptune 12V Battery Sprayer Pump</h3>
                    <div class="price-container">
                        <span class="current-price">₹2,450</span><span class="original-price">₹5,500</span><span class="discount-text">55% off</span>
                    </div>
                </div>
            </div>

            <!-- Product 2 -->
            <div class="cp-product-item">
                <div class="premium-product-card">
                    <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                    <div class="product-img-wrapper">
                        <img src="assets/images/demo-data/product.jpg" class="product-img">
                    </div>
                    <div><span class="rating-badge">4.8 <i class="fa-solid fa-star"></i></span><span class="review-count">(67)</span></div>
                    <h3 class="product-title">Balwaan Krishi WP-33R Water Pump</h3>
                    <div class="price-container">
                        <span class="current-price">₹12,399</span><span class="original-price">₹25,399</span><span class="discount-text">51% off</span>
                    </div>
                </div>
            </div>

            <!-- Product 3 -->
            <div class="cp-product-item">
                <div class="premium-product-card">
                    <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                    <div class="product-img-wrapper">
                        <img src="assets/images/demo-data/product.jpg" class="product-img">
                    </div>
                    <div><span class="rating-badge">4.5 <i class="fa-solid fa-star"></i></span><span class="review-count">(28)</span></div>
                    <h3 class="product-title">Neptune Simplify Farming 6.5 HP</h3>
                    <div class="price-container">
                        <span class="current-price">₹9,049</span><span class="original-price">₹18,000</span><span class="discount-text">49% off</span>
                    </div>
                </div>
            </div>

            <!-- Product 4 -->
            <div class="cp-product-item">
                <div class="premium-product-card">
                    <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                    <div class="product-img-wrapper">
                        <img src="assets/images/demo-data/product.jpg" class="product-img">
                    </div>
                    <div><span class="rating-badge">4.2 <i class="fa-solid fa-star"></i></span><span class="review-count">(450)</span></div>
                    <h3 class="product-title">Spear 22 inch 62cc 2 Stroke Chain Saw</h3>
                    <div class="price-container">
                        <span class="current-price">₹6,250</span><span class="original-price">₹9,990</span><span class="discount-text">37% off</span>
                    </div>
                </div>
            </div>

            <!-- Product 5 (Hidden on Mobile Initially) -->
            <div class="cp-product-item cp-mobile-hidden">
                <div class="premium-product-card">
                    <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                    <div class="product-img-wrapper">
                        <img src="assets/images/demo-data/product.jpg" class="product-img">
                    </div>
                    <div><span class="rating-badge">4.7 <i class="fa-solid fa-star"></i></span><span class="review-count">(7)</span></div>
                    <h3 class="product-title">Spear 6 inch 2Ah 4000rpm Cordless</h3>
                    <div class="price-container">
                        <span class="current-price">₹2,590</span><span class="original-price">₹5,990</span><span class="discount-text">56% off</span>
                    </div>
                </div>
            </div>

            <!-- Product 6 -->
            <div class="cp-product-item cp-mobile-hidden">
                <div class="premium-product-card">
                    <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                    <div class="product-img-wrapper">
                        <img src="assets/images/demo-data/product.jpg" class="product-img">
                    </div>
                    <div><span class="rating-badge">4.1 <i class="fa-solid fa-star"></i></span><span class="review-count">(89)</span></div>
                    <h3 class="product-title">Kisan Kraft Heavy Duty Sprayer</h3>
                    <div class="price-container">
                        <span class="current-price">₹3,450</span><span class="original-price">₹6,500</span><span class="discount-text">46% off</span>
                    </div>
                </div>
            </div>

             <!-- Product 7 -->
             <div class="cp-product-item cp-mobile-hidden">
                <div class="premium-product-card">
                    <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                    <div class="product-img-wrapper">
                        <img src="assets/images/demo-data/product.jpg" class="product-img">
                    </div>
                    <div><span class="rating-badge">4.9 <i class="fa-solid fa-star"></i></span><span class="review-count">(15)</span></div>
                    <h3 class="product-title">Garden Tool Kit 5 Pc</h3>
                    <div class="price-container">
                        <span class="current-price">₹799</span><span class="original-price">₹1,999</span><span class="discount-text">60% off</span>
                    </div>
                </div>
            </div>

            <!-- Product 8 -->
            <div class="cp-product-item cp-mobile-hidden">
                <div class="premium-product-card">
                    <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                    <div class="product-img-wrapper">
                        <img src="assets/images/demo-data/product.jpg" class="product-img">
                    </div>
                    <div><span class="rating-badge">4.6 <i class="fa-solid fa-star"></i></span><span class="review-count">(342)</span></div>
                    <h3 class="product-title">Falcon Pruning Secateur</h3>
                    <div class="price-container">
                        <span class="current-price">₹450</span><span class="original-price">₹800</span><span class="discount-text">43% off</span>
                    </div>
                </div>
            </div>

            <!-- Product 9 -->
            <div class="cp-product-item cp-mobile-hidden">
                <div class="premium-product-card">
                    <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                    <div class="product-img-wrapper">
                        <img src="assets/images/demo-data/product.jpg" class="product-img">
                    </div>
                    <div><span class="rating-badge">4.3 <i class="fa-solid fa-star"></i></span><span class="review-count">(56)</span></div>
                    <h3 class="product-title">Automatic Watering System</h3>
                    <div class="price-container">
                        <span class="current-price">₹1,299</span><span class="original-price">₹2,500</span><span class="discount-text">48% off</span>
                    </div>
                </div>
            </div>

            <!-- Product 10 -->
            <div class="cp-product-item cp-mobile-hidden">
                <div class="premium-product-card">
                    <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                    <div class="product-img-wrapper">
                        <img src="assets/images/demo-data/product.jpg" class="product-img">
                    </div>
                    <div><span class="rating-badge">4.5 <i class="fa-solid fa-star"></i></span><span class="review-count">(220)</span></div>
                    <h3 class="product-title">Heavy Duty Garden Hose 20m</h3>
                    <div class="price-container">
                        <span class="current-price">₹999</span><span class="original-price">₹1,500</span><span class="discount-text">33% off</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Load More Link (Mobile Only) -->
        <div class="cp-load-more-container d-lg-none">
            <button class="cp-load-more-btn" id="cpLoadMoreBtn">Load More</button>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('cpProductContainer');
        const prevBtn = document.getElementById('cpPrevBtn');
        const nextBtn = document.getElementById('cpNextBtn');
        const loadMoreBtn = document.getElementById('cpLoadMoreBtn');

        if (!container) return;

        // --- Desktop Slider Logic ---
        const scrollAmount = 250; // Scroll distance

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
        }

        // --- Mobile Load More Logic ---
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                // Find all hidden items
                const hiddenItems = container.querySelectorAll('.cp-mobile-hidden');
                
                // Reveal them
                hiddenItems.forEach(item => {
                    item.classList.remove('cp-mobile-hidden');
                });
                
                // Hide the button after loading all
                this.parentElement.style.display = 'none';
            });
        }
    });
</script>

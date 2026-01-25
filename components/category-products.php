<style>
/* --- Category Products Component --- */
.category-products-section {
    background-color: #f1f3f6; /* Common e-commerce background */
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

/* Product Grid */
.product-grid-container {
    background-color: transparent;
}

/* Product Card - Premium Design */
.premium-product-card {
    background: #fff;
    position: relative;
    display: flex;
    flex-direction: column;
    padding: 16px;
    height: 100%;
    transition: box-shadow 0.2s ease, transform 0.1s;
    border-radius: 4px;
    margin-bottom: 0; /* Handled by grid gap */
}

.premium-product-card:hover {
    box-shadow: 0 3px 16px 0 rgba(0,0,0,.11);
    transform: translateY(-2px);
    z-index: 2;
}

/* Wishlist Icon */
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

.card-wishlist-btn:hover {
    color: #ff4343;
}

/* Image */
.product-img-wrapper {
    position: relative;
    width: 100%;
    height: 200px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: opacity 0.3s ease;
}

/* Rating Badge */
.rating-badge {
    background-color: #388e3c;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 3px;
    display: inline-flex;
    align-items: center;
    vertical-align: middle;
    margin-right: 8px;
}

.rating-badge i {
    font-size: 10px;
    margin-left: 2px;
}

.review-count {
    color: #878787;
    font-size: 13px;
    font-weight: 500;
}

/* Details */
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
    height: 40px; /* Force consistent height for alignment */
}

.price-container {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    margin-top: 4px;
}

.current-price {
    font-size: 18px;
    font-weight: 600;
    color: #212121;
    margin-right: 10px;
}

.original-price {
    font-size: 14px;
    color: #878787;
    text-decoration: line-through;
    margin-right: 10px;
}

.discount-text {
    font-size: 13px;
    color: #388e3c;
    font-weight: 700;
}

/* Mobile Responsiveness */
@media (max-width: 991px) {
    .product-img-wrapper {
        height: 180px;
    }
}

@media (max-width: 767px) {
    /* On mobile, maybe 2 columns with reduced padding */
    .premium-product-card {
        padding: 12px;
    }
    
    .product-img-wrapper {
        height: 150px;
    }
    
    .product-title {
        font-size: 13px;
        height: 38px;
    }
    
    .current-price {
        font-size: 16px;
    }
    
    .rating-badge {
        padding: 2px 4px;
        font-size: 11px;
    }
}
</style>

<section class="category-products-section">
    <div class="container container-custom-rounded bg-white p-0">
        <!-- Header -->
        <div class="category-header rounded-top">
            <h2 class="category-title">Gardening & Landscaping</h2>
            <a href="#" class="view-all-btn">View All</a>
        </div>

        <!-- Products Grid -->
        <div class="product-grid-container p-3">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
                
                <!-- Product 1 -->
                <div class="col">
                    <div class="premium-product-card">
                        <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                        
                        <div class="product-img-wrapper">
                            <img src="assets/images/demo-data/product.jpg" alt="Neptune Sprayer" class="product-img">
                        </div>
                        
                        <div>
                            <span class="rating-badge">4.4 <i class="fa-solid fa-star"></i></span>
                            <span class="review-count">(1,234)</span>
                        </div>
                        
                        <h3 class="product-title">Neptune 12V Battery Sprayer Pump</h3>
                        
                        <div class="price-container">
                            <span class="current-price">₹2,450</span>
                            <span class="original-price">₹5,500</span>
                            <span class="discount-text">55% off</span>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="col">
                    <div class="premium-product-card">
                        <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                        
                        <div class="product-img-wrapper">
                            <img src="assets/images/demo-data/product.jpg" alt="Water Pump" class="product-img">
                        </div>
                        
                        <div>
                            <span class="rating-badge">4.8 <i class="fa-solid fa-star"></i></span>
                            <span class="review-count">(67)</span>
                        </div>
                        
                        <h3 class="product-title">Balwaan Krishi WP-33R Water Pump</h3>
                        
                        <div class="price-container">
                            <span class="current-price">₹12,399</span>
                            <span class="original-price">₹25,399</span>
                            <span class="discount-text">51% off</span>
                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="col">
                    <div class="premium-product-card">
                        <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                        
                        <div class="product-img-wrapper">
                            <img src="assets/images/demo-data/product.jpg" alt="Petrol Engine" class="product-img">
                        </div>
                        
                        <div>
                            <span class="rating-badge">4.5 <i class="fa-solid fa-star"></i></span>
                            <span class="review-count">(28)</span>
                        </div>
                        
                        <h3 class="product-title">Neptune Simplify Farming 6.5 HP</h3>
                        
                        <div class="price-container">
                            <span class="current-price">₹9,049</span>
                            <span class="original-price">₹18,000</span>
                            <span class="discount-text">49% off</span>
                        </div>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="col d-none d-md-block"> <!-- Hidden on very small screens if needed, or adjust grid -->
                    <div class="premium-product-card">
                        <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                        
                        <div class="product-img-wrapper">
                            <img src="assets/images/demo-data/product.jpg" alt="Chain Saw" class="product-img">
                        </div>
                        
                        <div>
                            <span class="rating-badge">3.9 <i class="fa-solid fa-star"></i></span>
                            <span class="review-count">(450)</span>
                        </div>
                        
                        <h3 class="product-title">Spear 22 inch 62cc 2 Stroke Chain Saw</h3>
                        
                        <div class="price-container">
                            <span class="current-price">₹6,250</span>
                            <span class="original-price">₹9,990</span>
                            <span class="discount-text">37% off</span>
                        </div>
                    </div>
                </div>

                <!-- Product 5 -->
                <div class="col d-none d-lg-block">
                    <div class="premium-product-card">
                        <button class="card-wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                        
                        <div class="product-img-wrapper">
                            <img src="assets/images/demo-data/product.jpg" alt="Cordless Saw" class="product-img">
                        </div>
                        
                        <div>
                            <span class="rating-badge">4.7 <i class="fa-solid fa-star"></i></span>
                            <span class="review-count">(7)</span>
                        </div>
                        
                        <h3 class="product-title">Spear 6 inch 2Ah 4000rpm Cordless</h3>
                        
                        <div class="price-container">
                            <span class="current-price">₹2,590</span>
                            <span class="original-price">₹5,990</span>
                            <span class="discount-text">56% off</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

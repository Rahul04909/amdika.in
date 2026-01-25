<style>
/* --- Top Deals Widgets (Amazon Style) --- */
.top-deals-container {
    background-color: transparent; /* Container background transparent to let cards show */
}

/* Widget Card */
.td-widget-card {
    background-color: #fff;
    padding: 20px 20px 15px; /* Added 15px bottom padding */
    height: 100%;
    border: 1px solid #ddd;   /* Light gray border like screenshot */
    display: flex;
    flex-direction: column;
}

.td-widget-title {
    font-size: 21px;          /* Larger title like "Best Sellers..." */
    font-weight: 700;
    color: #0F1111;           /* Amazon dark text */
    margin-bottom: 10px;
    line-height: 1.3;
}

/* "See more" link */
.td-see-more {
    margin-top: auto;
    padding-top: 10px;
    font-size: 13px;
    color: #007185;
    text-decoration: none;
    font-weight: 500;
}
.td-see-more:hover {
    color: #c7511f;
    text-decoration: underline;
}

/* --- Widget Type 1: Single Hero + Thumbnails (Best Sellers) --- */
.td-hero-img-wrapper {
    width: 100%;
    margin-bottom: 1px;
    text-align: center;
}
.td-hero-img {
    max-width: 100%;
    max-height: 240px;
    object-fit: contain;
}
.td-product-desc {
    font-size: 13px;
    color: #0F1111;
    margin-bottom: 4px;
    line-height: 1.4;
}
.td-price-block {
    display: flex;
    align-items: baseline;
    gap: 5px;
    margin-bottom: 10px;
}
.td-price-symbol {
    font-size: 12px;
    position: relative;
    top: -5px;
}
.td-price-whole {
    font-size: 21px; /* Emphasized price */
    font-weight: 500;
}
.td-price-fraction {
    font-size: 12px;
    position: relative;
    top: -5px;
}
.td-mrp {
    font-size: 12px;
    color: #565959;
    text-decoration: line-through;
}
.td-thumbnails-row {
    display: flex;
    gap: 8px;
    margin-bottom: 5px;
}
.td-thumb-box {
    border: 1px solid #a8a8a8;
    border-radius: 4px;
    padding: 2px;
    cursor: pointer;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.td-thumb-img {
    max-width: 100%;
    max-height: 100%;
}

/* --- Widget Type 2: 2x2 Grid (Keep Shopping / Discounts) --- */
.td-grid-row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -5px; /* Negative margin for gaps */
}
.td-grid-col {
    width: 50%;
    padding: 0 5px 25px 5px; /* Bottom padding for spacing */
    box-sizing: border-box;
}
.td-grid-item {
    display: flex;
    flex-direction: column;
    height: 100%;
    cursor: pointer;
}
.td-grid-img-wrapper {
    height: 120px; /* Uniform height for grid images */
    display: flex;
    align-items: center;
    justify-content: center; /* Center horizontally */
    margin-bottom: 4px;
    overflow: hidden;
}
.td-grid-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain; /* Ensure full image is seen */
    align-self: center; /* Vertical center */
}
.td-grid-label {
    font-size: 12px;
    color: #0F1111;
    line-height: 1.3;
}
.td-grid-price {
    font-size: 15px; /* Price below image */
    font-weight: 500;
    color: #0F1111;
}

/* Mobile Adjustments */
@media (max-width: 767px) {
    .td-widget-card {
        margin-bottom: 15px; /* Spacing between stacked cards */
        height: auto;
    }
    .td-hero-img {
        max-height: 200px;
    }
}
</style>

<section class="top-deals-section mb-4">
    <div class="container top-deals-container p-0">
        <div class="row g-3"> <!-- Bootstrap gutter -->
            
            <!-- Widget 1: Best Sellers (Single Large + Thumbs) -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="td-widget-card">
                    <h3 class="td-widget-title">Best Sellers in Home & Kitchen</h3>
                    
                    <div class="td-hero-img-wrapper">
                        <img src="assets/images/demo-data/product.jpg" alt="Garbage Bags" class="td-hero-img">
                    </div>
                    
                    <div class="td-product-desc">
                        Amazon Brand - Presto! Garbage Bags | Medium | 180 Count
                    </div>
                    
                    <div class="td-price-block">
                        <span class="td-price-symbol">₹</span>
                        <span class="td-price-whole">335</span>
                        <span class="td-mrp">M.R.P: ₹480.00</span>
                    </div>

                    <!-- Thumbnails Row -->
                    <div class="td-thumbnails-row">
                        <div class="td-thumb-box"><img src="assets/images/demo-data/product.jpg" class="td-thumb-img"></div>
                        <div class="td-thumb-box"><img src="assets/images/demo-data/product.jpg" class="td-thumb-img"></div>
                        <div class="td-thumb-box"><img src="assets/images/demo-data/product.jpg" class="td-thumb-img"></div>
                        <div class="td-thumb-box"><img src="assets/images/demo-data/product.jpg" class="td-thumb-img"></div>
                    </div>

                    <a href="#" class="td-see-more">See more</a>
                </div>
            </div>

            <!-- Widget 2: Keep Shopping For (2x2 Grid with Prices) -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="td-widget-card">
                    <h3 class="td-widget-title">Keep shopping for</h3>
                    
                    <div class="td-grid-row">
                        <!-- Item 1 -->
                        <div class="td-grid-col">
                            <div class="td-grid-item">
                                <div class="td-grid-img-wrapper">
                                    <img src="assets/images/demo-data/product.jpg" alt="Samsung S23" class="td-grid-img">
                                </div>
                                <div class="td-grid-label">Samsung Galaxy S...</div>
                                <div class="td-grid-price">₹74,999</div>
                            </div>
                        </div>
                        <!-- Item 2 -->
                        <div class="td-grid-col">
                            <div class="td-grid-item">
                                <div class="td-grid-img-wrapper">
                                    <img src="assets/images/demo-data/product.jpg" alt="iQOO 15" class="td-grid-img">
                                </div>
                                <div class="td-grid-label">iQOO 15 (Legend...</div>
                                <div class="td-grid-price">₹72,998</div>
                            </div>
                        </div>
                        <!-- Item 3 -->
                        <div class="td-grid-col">
                            <div class="td-grid-item">
                                <div class="td-grid-img-wrapper">
                                    <img src="assets/images/demo-data/product.jpg" alt="OnePlus 15R" class="td-grid-img">
                                </div>
                                <div class="td-grid-label">OnePlus 15R | 12...</div>
                                <div class="td-grid-price">₹47,998</div>
                            </div>
                        </div>
                        <!-- Item 4 -->
                        <div class="td-grid-col">
                            <div class="td-grid-item">
                                <div class="td-grid-img-wrapper">
                                    <img src="assets/images/demo-data/product.jpg" alt="iPhone 15" class="td-grid-img">
                                </div>
                                <div class="td-grid-label">iPhone 15 256 GB...</div>
                                <div class="td-grid-price">₹99,000</div>
                            </div>
                        </div>
                    </div>

                    <a href="#" class="td-see-more">See more</a>
                </div>
            </div>

            <!-- Widget 3: Discount Category A (2x2 Grid Images Only) -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="td-widget-card">
                    <h3 class="td-widget-title">Up to 60% off | Cookware & tools</h3>
                    
                    <div class="td-grid-row">
                        <!-- Item 1 -->
                        <div class="td-grid-col">
                            <div class="td-grid-item">
                                <div class="td-grid-img-wrapper">
                                    <img src="assets/images/demo-data/product.jpg" alt="Cookware" class="td-grid-img">
                                </div>
                                <div class="td-grid-label">Cookware</div>
                            </div>
                        </div>
                        <!-- Item 2 -->
                        <div class="td-grid-col">
                            <div class="td-grid-item">
                                <div class="td-grid-img-wrapper">
                                    <img src="assets/images/demo-data/product.jpg" alt="Knives" class="td-grid-img">
                                </div>
                                <div class="td-grid-label">Knives & Tools</div>
                            </div>
                        </div>
                        <!-- Item 3 -->
                        <div class="td-grid-col">
                            <div class="td-grid-item">
                                <div class="td-grid-img-wrapper">
                                    <img src="assets/images/demo-data/product.jpg" alt="Colanders" class="td-grid-img">
                                </div>
                                <div class="td-grid-label">Colanders</div>
                            </div>
                        </div>
                        <!-- Item 4 -->
                        <div class="td-grid-col">
                            <div class="td-grid-item">
                                <div class="td-grid-img-wrapper">
                                    <img src="assets/images/demo-data/product.jpg" alt="Mugs" class="td-grid-img">
                                </div>
                                <div class="td-grid-label">Mugs & Cups</div>
                            </div>
                        </div>
                    </div>

                    <a href="#" class="td-see-more">See more</a>
                </div>
            </div>

            <!-- Widget 4: Discount Category B (2x2 Grid Images Only) -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="td-widget-card">
                    <h3 class="td-widget-title">Up to 70% off | Kitchen products</h3>
                    
                    <div class="td-grid-row">
                        <!-- Item 1 -->
                        <div class="td-grid-col">
                            <div class="td-grid-item">
                                <div class="td-grid-img-wrapper">
                                    <img src="assets/images/demo-data/product.jpg" alt="Bottles" class="td-grid-img">
                                </div>
                                <div class="td-grid-label">Water Bottles</div>
                            </div>
                        </div>
                        <!-- Item 2 -->
                        <div class="td-grid-col">
                            <div class="td-grid-item">
                                <div class="td-grid-img-wrapper">
                                    <img src="assets/images/demo-data/product.jpg" alt="Spice Racks" class="td-grid-img">
                                </div>
                                <div class="td-grid-label">Spice Racks</div>
                            </div>
                        </div>
                        <!-- Item 3 -->
                        <div class="td-grid-col">
                            <div class="td-grid-item">
                                <div class="td-grid-img-wrapper">
                                    <img src="assets/images/demo-data/product.jpg" alt="Choppers" class="td-grid-img">
                                </div>
                                <div class="td-grid-label">Choppers</div>
                            </div>
                        </div>
                        <!-- Item 4 -->
                        <div class="td-grid-col">
                            <div class="td-grid-item">
                                <div class="td-grid-img-wrapper">
                                    <img src="assets/images/demo-data/product.jpg" alt="Tools" class="td-grid-img">
                                </div>
                                <div class="td-grid-label">Kitchen Tools</div>
                            </div>
                        </div>
                    </div>

                    <a href="#" class="td-see-more">See more</a>
                </div>
            </div>

        </div>
    </div>
</section>

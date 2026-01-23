<section class="category-section mt-4 mb-5">
    <div class="container container-custom-rounded bg-white p-4 shadow-sm border-gold position-relative">
        <!-- Section Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="section-title text-uppercase fw-bold mb-0">
                <span class="text-secondary">Explore</span> <span class="text-gold">Categories</span>
            </h3>
            <div class="category-nav-buttons d-none d-md-block">
                <button id="scrollLeftBtn" class="btn btn-outline-gold rounded-circle me-1"><i class="fa-solid fa-chevron-left"></i></button>
                <button id="scrollRightBtn" class="btn btn-outline-gold rounded-circle"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

        <!-- Categories Slider Container -->
        <div id="categoryScrollContainer" class="category-scroll-container">
            <!-- Category Item 1 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="../assets/images/demo-data/product-categories-showcase/wall-decor.webp" alt="Wall Decor" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Wall Decor</h6>
                </a>
            </div>

            <!-- Category Item 2 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="https://via.placeholder.com/150/f8f9fa/2b3445?text=Lamp" alt="Lighting" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Lighting & Lamps</h6>
                </a>
            </div>

            <!-- Category Item 3 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="https://via.placeholder.com/150/f8f9fa/2b3445?text=Home" alt="Home Decor" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Home Furnishings</h6>
                </a>
            </div>

            <!-- Category Item 4 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="https://via.placeholder.com/150/f8f9fa/2b3445?text=Beauty" alt="Beauty" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Beauty</h6>
                </a>
            </div>

            <!-- Category Item 5 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="https://via.placeholder.com/150/f8f9fa/2b3445?text=Decor" alt="Sports" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Decorative Showpieces</h6>
                </a>
            </div>

            <!-- Category Item 6 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="https://via.placeholder.com/150/f8f9fa/2b3445?text=Candle" alt="Candles" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Candels & Frangnences</h6>
                </a>
            </div>
            
            <!-- Category Item 7 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="https://via.placeholder.com/150/f8f9fa/2b3445?text=Table" alt="Table Decor" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Table Decor</h6>
                </a>
            </div>

             <!-- Category Item 8 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="https://via.placeholder.com/150/f8f9fa/2b3445?text=Religious" alt="Religious" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Religious Items</h6>
                </a>
            </div>

            <!-- Category Item 9 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="https://via.placeholder.com/150/f8f9fa/2b3445?text=Storage" alt="Storage" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Storage & Organizers</h6>
                </a>
            </div>
            
            <!-- Category Item 10 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="https://via.placeholder.com/150/f8f9fa/2b3445?text=Seasonal" alt="Seasonal" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Seasonal Decor</h6>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Simple Script for Horizontal Scroll -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scrollContainer = document.getElementById('categoryScrollContainer');
        const scrollLeftBtn = document.getElementById('scrollLeftBtn');
        const scrollRightBtn = document.getElementById('scrollRightBtn');
        const scrollAmount = 300; // Adjust scroll distance

        if(scrollLeftBtn && scrollRightBtn && scrollContainer) {
            scrollLeftBtn.addEventListener('click', function() {
                scrollContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });

            scrollRightBtn.addEventListener('click', function() {
                scrollContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
        }
    });
</script>

<style>
/* --- Product Categories Component --- */
.container-custom-rounded {
    border-radius: 12px;
}

.border-gold {
    border: 1px solid rgba(212, 160, 23, 0.2);
}

.text-gold {
    color: var(--accent-gold);
}

.btn-outline-gold {
    border-color: var(--accent-gold);
    color: var(--accent-gold);
}

.btn-outline-gold:hover {
    background-color: var(--accent-gold);
    color: var(--white);
}

.category-card {
    transition: all 0.3s ease;
    background-color: var(--warm-bg);
    border: 1px solid transparent;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    border-color: var(--accent-gold);
    background-color: var(--white);
    text-decoration: none;
}

.cat-img-wrapper {
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: var(--white);
    padding: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    transition: all 0.3s;
}

.category-card:hover .cat-img-wrapper {
    box-shadow: 0 0 0 3px var(--accent-gold);
}

.category-card:hover .cat-name {
    color: var(--primary-color) !important;
}

/* Mobile optimizations for categories */
@media (max-width: 767px) {
    .category-section .container-custom-rounded {
        padding: 1.5rem 1rem !important;
        /* Slightly less padding on mobile */
    }

    .cat-img-wrapper {
        width: 80px;
        height: 80px;
    }

    .cat-name {
        font-size: 13px;
    }
}

/* Horizontal Scroll Slider */
.category-scroll-container {
    display: flex;
    overflow-x: auto;
    gap: 20px;
    padding-bottom: 5px;
    padding-top: 10px; /* Prevent hover clipping */
    /* Tiny padding */
    scrollbar-width: none;
    /* Firefox */
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

.category-scroll-container::-webkit-scrollbar {
    display: none;
    /* Chrome/Safari */
}

.category-item {
    flex: 0 0 auto;
    width: 160px;
    /* Fixed width for consistent slider feel */
}

@media (max-width: 767px) {
    .category-item {
        width: 130px;
        /* Smaller width on mobile */
    }
}
</style>
<section class="category-section mt-4 mb-5">
    <div class="container container-custom-rounded bg-white p-4 shadow-sm border-gold position-relative">
        <!-- Section Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="section-title text-uppercase fw-bold mb-0">
                <span class="text-secondary">Explore</span> <span class="text-gold">Categories</span>
            </h3>
            <div class="category-nav-buttons d-none d-md-block">
                <button id="catScrollLeftBtn" class="btn btn-outline-gold rounded-circle me-1"><i class="fa-solid fa-chevron-left"></i></button>
                <button id="catScrollRightBtn" class="btn btn-outline-gold rounded-circle"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

        <!-- Categories Slider Container -->
        <div id="categoryScrollContainer" class="category-scroll-container">
            <!-- Category Item 1 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="assets/images/demo-data/product.jpg" alt="Wall Decor" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Wall Decor</h6>
                </a>
            </div>

            <!-- Category Item 2 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="assets/images/demo-data/product.jpg" alt="Lighting" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Lighting & Lamps</h6>
                </a>
            </div>

            <!-- Category Item 3 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="assets/images/demo-data/product.jpg" alt="Home Decor" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Home Furnishings</h6>
                </a>
            </div>

            <!-- Category Item 4 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="assets/images/demo-data/product.jpg" alt="Beauty" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Beauty</h6>
                </a>
            </div>

            <!-- Category Item 5 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="assets/images/demo-data/product.jpg" alt="Sports" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Decorative Showpieces</h6>
                </a>
            </div>

            <!-- Category Item 6 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="assets/images/demo-data/product.jpg" alt="Candles" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Candels & Frangnences</h6>
                </a>
            </div>
            
            <!-- Category Item 7 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="assets/images/demo-data/product.jpg" alt="Table Decor" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Table Decor</h6>
                </a>
            </div>

             <!-- Category Item 8 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="assets/images/demo-data/product.jpg" alt="Religious" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Religious Items</h6>
                </a>
            </div>

            <!-- Category Item 9 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="assets/images/demo-data/product.jpg" alt="Storage" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Storage & Organizers</h6>
                </a>
            </div>
            
            <!-- Category Item 10 -->
            <div class="category-item">
                <a href="#" class="category-card d-block text-center p-3 rounded h-100">
                    <div class="cat-img-wrapper mb-3 mx-auto">
                        <img src="assets/images/demo-data/product.jpg" alt="Seasonal" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="cat-name fw-bold text-secondary mb-0">Seasonal Decor</h6>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Script for Autoplay and Horizontal Scroll -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scrollContainer = document.getElementById('categoryScrollContainer');
        const scrollLeftBtn = document.getElementById('catScrollLeftBtn');
        const scrollRightBtn = document.getElementById('catScrollRightBtn');
        
        // Configuration
        const scrollSpeed = 2; // Pixels per interval (Smooth continuous feel)
        const intervalTime = 30; // Milliseconds
        let isAutoScrolling = true;
        let animationId;

        // Clone items for infinite scroll effect (optional, or just bounce back)
        // For simplicity, we will just scroll and reset when reaching end
        
        // Autoplay Function
        const autoScroll = () => {
            if (!isAutoScrolling) return;

             // Check if we've reached the end
             if (scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer.scrollWidth - 1) {
                // If at end, smoothly scroll back to start
                // Or snap back: scrollContainer.scrollLeft = 0;
                 scrollContainer.scrollTo({ left: 0, behavior: 'smooth' });
                 
                 // Pause briefly after reset then continue
                 isAutoScrolling = false;
                 setTimeout(() => {
                     isAutoScrolling = true;
                     autoScroll();
                 }, 1000);
                 return;
            }

            scrollContainer.scrollLeft += 1; // Slow constant scroll
            animationId = requestAnimationFrame(autoScroll);
        };

        // Start Auto Scroll
        // Use Interval for consistent speed control instead of rAF which might be too fast
        let autoScrollInterval = setInterval(() => {
            if (isAutoScrolling) {
                if (scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer.scrollWidth - 1) {
                    scrollContainer.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    scrollContainer.scrollLeft += 1;
                }
            }
        }, 20); // Adjust speed here


        // Button Controls
        if(scrollLeftBtn && scrollRightBtn) {
            scrollLeftBtn.addEventListener('click', function() {
                scrollContainer.scrollBy({ left: -200, behavior: 'smooth' });
            });

            scrollRightBtn.addEventListener('click', function() {
                scrollContainer.scrollBy({ left: 200, behavior: 'smooth' });
            });
        }

        // Pause on Hover
        scrollContainer.addEventListener('mouseenter', () => {
            isAutoScrolling = false;
        });

        scrollContainer.addEventListener('mouseleave', () => {
             isAutoScrolling = true;
        });
    });
</script>

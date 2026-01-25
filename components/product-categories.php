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
            <?php
            // Include DB Config (using __DIR__ to be safe relative to this file)
            require_once __DIR__ . '/../database/db_config.php';
            
            $cat_sql = "SELECT * FROM product_categories ORDER BY created_at DESC";
            $cat_result = $conn->query($cat_sql);
            
            if ($cat_result && $cat_result->num_rows > 0) {
                while($cat = $cat_result->fetch_assoc()) {
                    $cat_name = htmlspecialchars($cat['name']);
                    $cat_slug = htmlspecialchars($cat['slug']); // Future use for links
                    
                    // Image Path Handling
                    // DB stores 'assets/images/categories/...'
                    // If empty or file missing, use placeholder.
                    // Since included in index.php (root), path is relative to root.
                    $img_src = !empty($cat['image']) ? $cat['image'] : 'assets/images/demo-data/product.jpg';
                    
                    // Fallback if file doesn't exist (optional, expensive to check every file on render? 
                    // Browsers handle 404s, but file_exists check prevents broken icons if desired. 
                    // Let's rely on valid paths from Admin)
                    ?>
                    <div class="category-item">
                        <a href="products.php?category=<?php echo $cat_slug; ?>" class="category-card d-block text-center p-3 rounded h-100">
                            <div class="cat-img-wrapper mb-3 mx-auto">
                                <img src="<?php echo $img_src; ?>" alt="<?php echo $cat_name; ?>" class="img-fluid rounded-circle" style="width:100%; height:100%; object-fit:cover;">
                            </div>
                            <h6 class="cat-name fw-bold text-secondary mb-0"><?php echo $cat_name; ?></h6>
                        </a>
                    </div>
                    <?php
                }
            } else {
                // Optional: Fallback if no categories found (or show nothing)
                echo '<p class="text-center w-100 text-muted">No categories found.</p>';
            }
            ?>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('categoryScrollContainer');
        const prevBtn = document.getElementById('catScrollLeftBtn');
        const nextBtn = document.getElementById('catScrollRightBtn');

        if (!slider) return;

        // Scroll Amount matches item width + gap
        const getScrollAmount = () => {
            // First item width + gap (20px defined in CSS)
            const item = slider.querySelector('.category-item');
            return item ? item.getBoundingClientRect().width + 20 : 200;
        };

        // Navigation
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                slider.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                slider.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
            });
        }

        // Autoplay Logic
        let autoplayInterval;
        const startAutoplay = () => {
            autoplayInterval = setInterval(() => {
                // Check if we are near the end
                if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                    // Reset to start
                    slider.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    // Scroll by one item
                    slider.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
                }
            }, 3000); // 3 Seconds per slide
        };

        const stopAutoplay = () => {
            clearInterval(autoplayInterval);
        };

        // Initialize
        startAutoplay();

        // Pause on Hover
        slider.addEventListener('mouseenter', stopAutoplay);
        slider.addEventListener('mouseleave', startAutoplay);
    });
</script>

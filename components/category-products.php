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
    width: 100%;
    height: 100%;
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

<!-- Dynamic Category Products Component -->
<?php
// Ensure DB connection
require_once __DIR__ . '/../database/db_config.php';

// Fetch Categories with at least one active product
// Using DISTINCT to avoid picking categories with no products (optimization)
// Or just fetch all and check count.
$cat_query = "SELECT * FROM product_categories ORDER BY created_at DESC";
$cat_res = $conn->query($cat_query);

if ($cat_res && $cat_res->num_rows > 0):
    $cat_index = 0;
    while($category = $cat_res->fetch_assoc()):
        $cat_id = $category['id'];
        $cat_name = htmlspecialchars($category['name']);
        $cat_slug = htmlspecialchars($category['slug']); // For view all link
        
        // Fetch Products for this Category (Limit 10 for slider)
        $prod_sql = "SELECT * FROM products WHERE category_id = $cat_id AND status = 'active' ORDER BY created_at DESC LIMIT 10";
        $prod_res = $conn->query($prod_sql);
        
        if($prod_res && $prod_res->num_rows > 0):
            $cat_index++;
            $unique_id = "cat_" . $cat_id . "_" . time(); // Unique ID for JS scoping
?>

<section class="category-products-section position-relative mb-3">
    <div class="container container-custom-rounded bg-white p-0 position-relative" id="container_<?php echo $unique_id; ?>">
        <!-- Header -->
        <div class="category-header rounded-top">
            <h2 class="category-title"><?php echo $cat_name; ?></h2>
            <a href="products.php?category=<?php echo $cat_slug; ?>" class="view-all-btn d-none d-lg-block">View All</a>
        </div>

        <!-- Desktop Navigation Buttons (Scoped by structure in JS) -->
        <button class="cp-nav-btn cp-prev-btn" data-target="slider_<?php echo $unique_id; ?>"><i class="fas fa-chevron-left"></i></button>
        <button class="cp-nav-btn cp-next-btn" data-target="slider_<?php echo $unique_id; ?>"><i class="fas fa-chevron-right"></i></button>

        <!-- Products Container -->
        <div class="cp-product-container" id="slider_<?php echo $unique_id; ?>">
            
            <?php 
            while($prod = $prod_res->fetch_assoc()): 
                $p_name = htmlspecialchars($prod['name']);
                $p_slug = htmlspecialchars($prod['slug']); // Future link
                $p_img = !empty($prod['featured_image']) ? $prod['featured_image'] : 'assets/images/demo-data/product.jpg';
                $p_mrp = $prod['mrp'];
                $p_sale = $prod['sale_price'];
                $p_disc = $prod['discount_percent'];
                
                // Random Rating Logic (4.0 to 5.0)
                $rating = number_format(4.0 + (rand(0, 10) / 10), 1);
                $reviews = rand(5, 500);
            ?>
            <div class="cp-product-item">
                <div class="premium-product-card">
                    <div class="product-img-wrapper">
                        <a href="product-details.php?slug=<?php echo $p_slug; ?>" class="d-block w-100 h-100">
                            <img src="<?php echo $p_img; ?>" class="product-img" alt="<?php echo $p_name; ?>">
                        </a>
                    </div>
                    <div>
                        <span class="rating-badge"><?php echo $rating; ?> <i class="fa-solid fa-star"></i></span>
                        <span class="review-count">(<?php echo $reviews; ?>)</span>
                    </div>
                    <a href="product-details.php?slug=<?php echo $p_slug; ?>" class="text-decoration-none">
                        <h3 class="product-title"><?php echo $p_name; ?></h3>
                    </a>
                    <div class="price-container">
                        <span class="current-price">₹<?php echo number_format((float)$p_sale); ?></span>
                        <span class="original-price">₹<?php echo number_format((float)$p_mrp); ?></span>
                        <span class="discount-text"><?php echo $p_disc; ?>% off</span>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>

        </div>

    </div>
</section>

<?php 
        endif; // End products check
    endwhile; // End category loop
endif; // End category check
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all sliders logic
        const navBtns = document.querySelectorAll('.cp-nav-btn');
        
        navBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const container = document.getElementById(targetId);
                const scrollAmount = 300; // Adjust scroll step

                if (container) {
                    if (this.classList.contains('cp-prev-btn')) {
                        container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                    } else {
                        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                    }
                }
            });
        });
        
        // Mobile Load More is complex with multiple sections. 
        // Simplest strategy: Show all rows or use CSS horizontal scroll on mobile?
        // The current CSS sets ".cp-product-container" to grid on mobile. 
        // Hiding extra items for each category might be tedious. 
        // Let's rely on the CSS 'grid' which will show all 10 items in 2 columns stack. 
        // 10 items is fine for mobile scroll. Removing the "Load More" complexity for now 
        // as it simplifies the multi-instance logic. CSS defines mobile view directly.
    });
</script>

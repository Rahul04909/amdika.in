<style>
/* --- Modern Product Categories (Nykaa Style) --- */
.category-section-wrapper {
    background-color: #fff;
    padding: 30px 0;
    overflow: hidden;
}

.category-header {
    padding: 0 20px;
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: baseline;
}

.category-header h2 {
    font-size: 22px;
    font-weight: 700;
    color: #232f3e;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.category-header .view-all {
    font-size: 14px;
    color: #2874f0;
    font-weight: 600;
    text-decoration: none;
}

.category-slider {
    display: flex;
    overflow-x: auto;
    gap: 15px;
    padding: 10px 20px 20px 20px;
    scrollbar-width: none; /* Firefox */
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

.category-slider::-webkit-scrollbar {
    display: none; /* Chrome/Safari */
}

.category-bubble-item {
    flex: 0 0 auto;
    width: 110px;
    text-align: center;
    text-decoration: none !important;
    transition: transform 0.3s ease;
}

.category-bubble-item:hover {
    transform: translateY(-5px);
}

.bubble-img-container {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    margin: 0 auto 12px;
    padding: 3px;
    border: 1px solid #eee;
    background: #fff;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.category-bubble-item:hover .bubble-img-container {
    border-color: #2874f0;
    box-shadow: 0 4px 15px rgba(40, 116, 240, 0.15);
}

.bubble-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.category-bubble-item:hover .bubble-img {
    transform: scale(1.1);
}

.bubble-name {
    font-size: 13px;
    font-weight: 600;
    color: #444;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 34px;
}

/* Desktop Navigation Arrows (Visible only on hover of section) */
.category-section-wrapper {
    position: relative;
}

.cat-nav-btn {
    position: absolute;
    top: 55%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    background: #fff;
    border: none;
    border-radius: 50%;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 10;
    display: none;
    align-items: center;
    justify-content: center;
    color: #333;
    transition: all 0.2s;
}

.cat-nav-btn:hover {
    background: #2874f0;
    color: #fff;
}

.cat-prev { left: 10px; }
.cat-next { right: 10px; }

@media (min-width: 992px) {
    .category-section-wrapper:hover .cat-nav-btn {
        display: flex;
    }
    
    .category-bubble-item {
        width: 140px;
    }
    
    .bubble-img-container {
        width: 120px;
        height: 120px;
    }
    
    .bubble-name {
        font-size: 15px;
    }
}

@media (max-width: 768px) {
    .category-header h2 { font-size: 18px; }
    .category-slider { gap: 10px; padding: 5px 15px 15px 15px; }
    .category-bubble-item { width: 90px; }
    .bubble-img-container { width: 80px; height: 80px; }
    .bubble-name { font-size: 12px; height: 32px; }
}
</style>

<section class="category-section-wrapper">
    <div class="category-header">
        <h2>Shop By <span style="color: #2874f0;">Category</span></h2>
        <a href="products.php" class="view-all">View All</a>
    </div>

    <!-- Navigation Arrows -->
    <button id="catPrev" class="cat-nav-btn cat-prev"><i class="fa-solid fa-chevron-left"></i></button>
    <button id="catNext" class="cat-nav-btn cat-next"><i class="fa-solid fa-chevron-right"></i></button>

    <div id="catSlider" class="category-slider">
        <?php
        require_once __DIR__ . '/../database/db_config.php';
        require_once __DIR__ . '/../includes/image_helper.php';
        
        $cat_sql = "SELECT * FROM product_categories ORDER BY created_at DESC";
        $cat_result = $conn->query($cat_sql);
        
        if ($cat_result && $cat_result->num_rows > 0) {
            while($cat = $cat_result->fetch_assoc()) {
                $cat_name = htmlspecialchars($cat['name']);
                $cat_slug = htmlspecialchars($cat['slug']);
                $img_path = !empty($cat['image']) ? $cat['image'] : 'assets/images/demo-data/product.jpg';
                
                $bubble_img = get_resized_image($img_path, 200, 200, 'cover');
                ?>
                <a href="products.php?category=<?php echo $cat_slug; ?>" class="category-bubble-item">
                    <div class="bubble-img-container">
                        <img src="<?php echo $bubble_img; ?>" alt="<?php echo $cat_name; ?>" class="bubble-img">
                    </div>
                    <span class="bubble-name"><?php echo $cat_name; ?></span>
                </a>
                <?php
            }
        }
        ?>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('catSlider');
        const prev = document.getElementById('catPrev');
        const next = document.getElementById('catNext');

        if (!slider) return;

        const scrollAmount = 300;

        if (prev) {
            prev.addEventListener('click', () => {
                slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
        }

        if (next) {
            next.addEventListener('click', () => {
                slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
        }
    });
</script>

<!-- Import Elegant Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600;1,700&display=swap" rel="stylesheet">

<style>
/* --- Modern Product Categories (Professional Bubbles) --- */
.category-section-wrapper {
    background-color: #fff;
    padding: 50px 0;
    overflow: hidden;
}

.category-header {
    padding: 0 20px;
    margin-bottom: 40px;
    text-align: center;
}

.category-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    font-style: italic;
    color: #232f3e;
    letter-spacing: 1px;
    margin: 0;
    position: relative;
    display: inline-block;
}

.category-header h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 2px;
    background-color: var(--accent-gold, #d4a017);
}

.category-slider {
    display: flex;
    overflow-x: auto;
    gap: 25px;
    padding: 10px 20px 30px 20px;
    scrollbar-width: none;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

.category-slider::-webkit-scrollbar {
    display: none;
}

.category-bubble-item {
    flex: 0 0 auto;
    width: 130px;
    text-align: center;
    text-decoration: none !important;
    transition: transform 0.3s ease;
}

.category-bubble-item:hover {
    transform: translateY(-8px);
}

.bubble-img-container {
    width: 110px;
    height: 110px;
    border-radius: 50%; /* Back to circular bubbles */
    margin: 0 auto 15px;
    padding: 4px;
    border: 1px solid #eee;
    background: #fff;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

.category-bubble-item:hover .bubble-img-container {
    border-color: var(--accent-gold, #d4a017);
    box-shadow: 0 8px 20px rgba(212, 160, 23, 0.2);
}

.bubble-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.category-bubble-item:hover .bubble-img {
    transform: scale(1.15);
}

.bubble-name {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 40px;
    transition: color 0.3s;
}

.category-bubble-item:hover .bubble-name {
    color: var(--accent-gold, #d4a017);
}

/* Navigation Buttons */
.category-section-wrapper {
    position: relative;
}

.cat-nav-btn {
    position: absolute;
    top: 55%;
    transform: translateY(-50%);
    width: 45px;
    height: 45px;
    background: #fff;
    border: none;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    z-index: 10;
    display: none;
    align-items: center;
    justify-content: center;
    color: #333;
    transition: all 0.2s;
}

.cat-nav-btn:hover {
    background: #232f3e;
    color: #fff;
}

.cat-prev { left: 15px; }
.cat-next { right: 15px; }

@media (min-width: 992px) {
    .category-section-wrapper:hover .cat-nav-btn {
        display: flex;
    }
    
    .category-bubble-item {
        width: 180px; /* 5-6 items logic */
    }
    
    .bubble-img-container {
        width: 160px;
        height: 160px;
    }
    
    .bubble-name {
        font-size: 16px;
    }
}

@media (max-width: 768px) {
    .category-header h2 { font-size: 22px; }
    .category-slider { gap: 15px; padding: 10px 15px 20px 15px; }
    .category-bubble-item { width: 100px; }
    .bubble-img-container { width: 90px; height: 90px; }
    .bubble-name { font-size: 12px; height: 34px; }
}
</style>

<section class="category-section-wrapper">
    <div class="category-header">
        <h2>Shop By <span style="color: var(--accent-gold, #d4a017);">Category</span></h2>
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
                
                $bubble_img = get_resized_image($img_path, 300, 300, 'cover');
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

        const scrollAmount = 400;

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

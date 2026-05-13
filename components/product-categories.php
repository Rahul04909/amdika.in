<!-- Import Elegant Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600;1,700&display=swap" rel="stylesheet">

<style>
/* --- Trending Categories (Sample Match) --- */
.category-section-wrapper {
    background-color: #fff;
    padding: 60px 0;
    position: relative;
}

.trending-box {
    margin: 0 20px;
    background: #1a2b4e; /* Match professional dark ticker theme */
    border-radius: 30px;
    padding: 50px 0 50px 0;
    position: relative;
}

.category-header {
    text-align: center;
    margin-bottom: 40px;
}

.category-header h2 {
    font-family: 'Rubik', sans-serif;
    font-size: 38px;
    font-weight: 800;
    color: #fff; /* White text for dark theme */
    margin-bottom: 10px;
}

.category-header p {
    font-size: 16px;
    color: rgba(255,255,255,0.7);
    margin-bottom: 0;
}

.category-slider {
    display: flex;
    overflow-x: auto;
    gap: 20px;
    padding: 0 30px;
    scrollbar-width: none;
    scroll-behavior: smooth;
    margin-bottom: 0; /* Fully contained now */
    -webkit-overflow-scrolling: touch;
}

.category-slider::-webkit-scrollbar {
    display: none;
}

.trending-cat-card {
    flex: 0 0 auto;
    width: 240px;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    text-decoration: none !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.trending-cat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}

.trending-cat-img-box {
    width: 100%;
    height: 180px;
    overflow: hidden;
}

.trending-cat-img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.trending-cat-card:hover .trending-cat-img-box img {
    transform: scale(1.1);
}

.trending-cat-info {
    padding: 20px 15px;
    text-align: center;
    background: #fff;
    color: #1a2b4e;
}

.trending-cat-info h3 {
    font-size: 19px;
    font-weight: 700;
    margin: 0 0 5px 0;
}

.trending-cat-info p {
    font-size: 12px;
    margin: 0;
    font-weight: 500;
    color: #666;
}

/* Navigation */
.cat-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50%;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    z-index: 10;
    display: none;
    align-items: center;
    justify-content: center;
    color: #fff;
    transition: all 0.3s;
}

.cat-nav-btn:hover {
    background: var(--accent-gold, #d4a017);
    border-color: var(--accent-gold, #d4a017);
    color: #fff;
}

@media (min-width: 992px) {
    .category-section-wrapper:hover .cat-nav-btn {
        display: flex;
    }
}

@media (max-width: 768px) {
    .category-section-wrapper { padding: 30px 0; }
    .trending-box { margin: 0; border-radius: 0; padding: 40px 0 40px 0; }
    .category-header h2 { font-size: 28px; }
    .category-header p { font-size: 14px; padding: 0 20px; }
    .category-slider { padding: 0 15px; margin-bottom: 0; gap: 15px; }
    .trending-cat-card { width: 170px; border-radius: 15px; }
    .trending-cat-img-box { height: 130px; }
    .trending-cat-info { padding: 12px 10px; }
    .trending-cat-info h3 { font-size: 15px; }
    .trending-cat-info p { font-size: 11px; }
}
</style>

<section class="category-section-wrapper">
    <div class="trending-box">
        <div class="category-header">
            <h2>Trending Categories</h2>
            <p>Explore popular and premium collections at Amadika.in</p>
        </div>

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
                    $cat_desc = !empty($cat['description']) ? strip_tags($cat['description']) : 'Explore Collection';
                    if(strlen($cat_desc) > 25) $cat_desc = substr($cat_desc, 0, 22) . '...';
                    
                    $img_path = !empty($cat['image']) ? $cat['image'] : 'assets/images/demo-data/product.jpg';
                    $bubble_img = get_resized_image($img_path, 400, 300, 'cover');
                    ?>
                    <a href="products.php?category=<?php echo $cat_slug; ?>" class="trending-cat-card">
                        <div class="trending-cat-img-box">
                            <img src="<?php echo $bubble_img; ?>" alt="<?php echo $cat_name; ?>">
                        </div>
                        <div class="trending-cat-info">
                            <h3><?php echo $cat_name; ?></h3>
                            <p><?php echo $cat_desc; ?></p>
                        </div>
                    </a>
                    <?php
                }
            }
            ?>
        </div>

        <!-- Navigation Arrows (Moved inside trending-box for perfect alignment) -->
        <button id="catPrev" class="cat-nav-btn" style="left: -22px;"><i class="fa-solid fa-chevron-left"></i></button>
        <button id="catNext" class="cat-nav-btn" style="right: -22px;"><i class="fa-solid fa-chevron-right"></i></button>
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

<!-- Import Elegant Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600;1,700&display=swap" rel="stylesheet">

<style>
/* --- Trending Categories (Sample Match) --- */
.category-section-wrapper {
    background-color: #fff;
    padding: 40px 0;
    position: relative;
}

.trending-box {
    margin: 0 20px;
    background: #dbf8f1;
    border-radius: 25px;
    padding: 40px 0 0 0;
    position: relative;
}

.category-header {
    text-align: center;
    margin-bottom: 30px;
}

.category-header h2 {
    font-family: 'Rubik', sans-serif;
    font-size: 36px;
    font-weight: 800;
    color: #1a2b4e;
    margin-bottom: 5px;
}

.category-header p {
    font-size: 15px;
    color: #666;
    margin-bottom: 0;
}

.category-slider {
    display: flex;
    overflow-x: auto;
    gap: 20px;
    padding: 0 20px 40px 20px;
    scrollbar-width: none;
    scroll-behavior: smooth;
    margin-bottom: -60px; /* Pulls cards down to hang off the edge */
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
    background: linear-gradient(180deg, #62ecd3 0%, #30d5c8 100%);
    color: #1a2b4e;
}

.trending-cat-info h3 {
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 5px 0;
}

.trending-cat-info p {
    font-size: 13px;
    margin: 0;
    font-weight: 500;
    opacity: 0.8;
}

/* Navigation */
.cat-nav-btn {
    position: absolute;
    top: 70%;
    transform: translateY(-50%);
    width: 45px;
    height: 45px;
    background: #fff;
    border: none;
    border-radius: 50%;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    z-index: 10;
    display: none;
    align-items: center;
    justify-content: center;
    color: #1a2b4e;
    transition: all 0.2s;
}

.cat-nav-btn:hover {
    background: #1a2b4e;
    color: #fff;
}

@media (min-width: 992px) {
    .category-section-wrapper:hover .cat-nav-btn {
        display: flex;
    }
}

@media (max-width: 768px) {
    .category-section-wrapper { padding: 20px 0; }
    .trending-box { margin: 0; border-radius: 0; padding: 30px 0 0 0; }
    .category-header h2 { font-size: 24px; }
    .category-header p { font-size: 13px; padding: 0 15px; }
    .category-slider { padding: 0 15px 30px 15px; margin-bottom: -40px; gap: 12px; }
    .trending-cat-card { width: 155px; border-radius: 12px; }
    .trending-cat-img-box { height: 115px; }
    .trending-cat-info { padding: 10px; }
    .trending-cat-info h3 { font-size: 14px; }
    .trending-cat-info p { font-size: 10px; }
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

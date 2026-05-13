<!-- Import Elegant Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600;1,700&display=swap" rel="stylesheet">

<style>
/* --- Trending Categories (Sample Match) --- */
.category-section-wrapper {
    background-color: #fff;
    padding: 40px 0 80px 0;
    position: relative;
    overflow: hidden;
}

.trending-bg-shape {
    position: absolute;
    top: 20px;
    left: 2.5%;
    width: 95%;
    height: 300px; /* Exact height to cover title and half-card */
    background: #dbf8f1;
    border-radius: 20px;
    z-index: 1;
}

.category-header {
    position: relative;
    z-index: 2;
    text-align: center;
    padding-top: 40px;
    margin-bottom: 30px;
}

.category-header h2 {
    font-family: 'Rubik', sans-serif;
    font-size: 38px;
    font-weight: 800;
    color: #1a2b4e;
    margin-bottom: 5px;
}

.category-header p {
    font-size: 15px;
    color: #666;
    font-weight: 400;
    margin-bottom: 0;
}

.category-slider {
    position: relative;
    z-index: 2;
    display: flex;
    overflow-x: auto;
    gap: 20px;
    padding: 0 5%;
    scrollbar-width: none;
    scroll-behavior: smooth;
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
    .category-header h2 { font-size: 26px; }
    .category-header p { font-size: 13px; padding: 0 20px; }
    .trending-bg-shape { height: 220px; top: 0; width: 100%; left: 0; border-radius: 0; }
    .category-slider { padding: 0 15px; }
    .trending-cat-card { width: 160px; border-radius: 12px; }
    .trending-cat-img-box { height: 120px; }
    .trending-cat-info { padding: 12px 10px; }
    .trending-cat-info h3 { font-size: 15px; }
    .trending-cat-info p { font-size: 10px; }
}
</style>

<section class="category-section-wrapper">
    <div class="trending-bg-shape"></div>
    
    <div class="category-header">
        <h2>Trending Categories</h2>
        <p>Explore popular and premium collections at Amadika.in</p>
    </div>

    <!-- Navigation Arrows -->
    <button id="catPrev" class="cat-nav-btn cat-prev" style="left: 20px;"><i class="fa-solid fa-chevron-left"></i></button>
    <button id="catNext" class="cat-nav-btn cat-next" style="right: 20px;"><i class="fa-solid fa-chevron-right"></i></button>

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
                // Limit description length
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

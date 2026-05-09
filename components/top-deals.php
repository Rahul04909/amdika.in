<style>
/* --- Ultra-Premium Interactive Top Deals --- */
.top-deals-section-wrapper {
    background: linear-gradient(180deg, #ffffff 0%, #fcfaf7 100%);
    padding: 20px 0 60px 0;
    overflow: hidden;
}

.td-container-fluid {
    width: 100%;
    padding: 0 40px;
}

.td-main-header {
    text-align: center;
    margin-bottom: 50px;
}

.td-main-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 36px;
    font-weight: 700;
    font-style: italic;
    color: #1a1a1a;
    position: relative;
    display: inline-block;
}

.td-main-header h2::after {
    content: '';
    position: absolute;
    bottom: -15px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: var(--accent-gold, #d4a017);
    border-radius: 2px;
}

/* Interactive Glass Widget */
.td-interactive-widget {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 30px;
    height: 100%;
    border: 1px solid rgba(212, 160, 23, 0.1);
    transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    display: flex;
    flex-direction: column;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
}

.td-interactive-widget:hover {
    transform: translateY(-15px);
    box-shadow: 0 25px 50px rgba(212, 160, 23, 0.1);
    border-color: var(--accent-gold, #d4a017);
    background: #fff;
}

.td-widget-tag {
    position: absolute;
    top: -12px;
    left: 30px;
    background: var(--accent-gold, #d4a017);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 4px 15px;
    border-radius: 30px;
    letter-spacing: 1px;
    box-shadow: 0 4px 10px rgba(212, 160, 23, 0.3);
}

.td-widget-title {
    font-family: 'Poppins', sans-serif;
    font-size: 22px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.td-widget-title i {
    color: var(--accent-gold, #d4a017);
    font-size: 18px;
}

/* Hero Display (Interactive) */
.td-interactive-hero {
    position: relative;
    margin-bottom: 25px;
}

.td-hero-main-img-box {
    width: 100%;
    height: 240px;
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.td-hero-main-img-box img {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
    transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.td-interactive-widget:hover .td-hero-main-img-box img {
    transform: scale(1.1);
}

.td-hero-info {
    text-align: center;
}

.td-hero-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.td-hero-price {
    font-size: 22px;
    font-weight: 800;
    color: var(--accent-gold, #d4a017);
}

/* Thumbnail Switcher */
.td-hero-thumbs-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.td-interactive-thumb {
    border: 2px solid #f0f0f0;
    border-radius: 10px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    background: #fff;
}

.td-interactive-thumb:hover, .td-interactive-thumb.active {
    border-color: var(--accent-gold, #d4a017);
    background: rgba(212, 160, 23, 0.05);
}

.td-interactive-thumb img {
    max-width: 80%;
    max-height: 80%;
    object-fit: contain;
}

/* Dynamic Grid (2x2) */
.td-interactive-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.td-grid-item-wrap {
    text-decoration: none !important;
    display: flex;
    flex-direction: column;
    group: hover;
}

.td-grid-img-wrap {
    width: 100%;
    height: 130px;
    background: #fff;
    border-radius: 12px;
    border: 1px solid #f5f5f5;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s;
}

.td-grid-item-wrap:hover .td-grid-img-wrap {
    border-color: var(--accent-gold, #d4a017);
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

.td-grid-img-wrap img {
    max-width: 85%;
    max-height: 85%;
    object-fit: contain;
    transition: transform 0.4s;
}

.td-grid-item-wrap:hover .td-grid-img-wrap img {
    transform: scale(1.15);
}

.td-grid-title-text {
    font-size: 13px;
    font-weight: 600;
    color: #555;
    text-align: center;
    height: 36px;
    overflow: hidden;
}

.td-grid-price-text {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a1a;
    text-align: center;
}

/* Interactive Footer Button */
.td-action-link {
    margin-top: auto;
    width: 100%;
    padding: 12px;
    background: transparent;
    border: 2px solid var(--accent-gold, #d4a017);
    color: var(--accent-gold, #d4a017);
    border-radius: 30px;
    text-align: center;
    font-weight: 700;
    text-decoration: none;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.td-interactive-widget:hover .td-action-link {
    background: var(--accent-gold, #d4a017);
    color: #fff;
    box-shadow: 0 10px 20px rgba(212, 160, 23, 0.2);
}

@media (max-width: 991px) {
    .td-container-fluid { padding: 0 20px; }
    .td-interactive-widget { margin-bottom: 40px; padding: 20px; }
}
</style>

<section class="top-deals-section-wrapper">
    <div class="td-container-fluid">
        <div class="td-main-header">
            <h2>Trending <span style="color: var(--accent-gold, #d4a017);">Boutique</span></h2>
        </div>

        <div class="row g-4">
            <?php
            require_once __DIR__ . '/../database/db_config.php';
            require_once __DIR__ . '/../includes/image_helper.php';

            if(!function_exists('get_premium_deals')){
                function get_premium_deals($conn, $limit, $offset = 0) {
                    $sql = "SELECT * FROM products ORDER BY id DESC LIMIT $limit OFFSET $offset";
                    $result = $conn->query($sql);
                    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
                }
            }

            $hero_batch = get_premium_deals($conn, 1);
            $trending_grid = get_premium_deals($conn, 4, 1);
            $popular_grid = get_premium_deals($conn, 4, 5);
            $style_grid = get_premium_deals($conn, 4, 9);
            ?>

            <!-- Interactive Hero Widget -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="td-interactive-widget">
                    <span class="td-widget-tag">New Arrival</span>
                    <h3 class="td-widget-title"><i class="fa-solid fa-bolt"></i> Newly Added</h3>
                    
                    <?php if(!empty($hero_batch)): 
                        $lp = $hero_batch[0];
                        $feat_img = get_resized_image($lp['featured_image'], 400, 400, 'contain');
                        $gallery = !empty($lp['gallery_images']) ? json_decode($lp['gallery_images'], true) : [];
                        $thumbs = array_slice($gallery ?: [], 0, 4);
                        $unique_hero_id = "hero_main_" . $lp['id'];
                    ?>
                        <div class="td-interactive-hero">
                            <a href="product-details.php?slug=<?php echo $lp['slug']; ?>" class="text-decoration-none">
                                <div class="td-hero-main-img-box">
                                    <img id="<?php echo $unique_hero_id; ?>" src="<?php echo $feat_img; ?>" alt="<?php echo htmlspecialchars($lp['name']); ?>">
                                </div>
                                <div class="td-hero-info">
                                    <h4 class="td-hero-name"><?php echo htmlspecialchars($lp['name']); ?></h4>
                                    <div class="td-hero-price">₹<?php echo number_format($lp['sale_price']); ?></div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="td-hero-thumbs-row">
                            <?php foreach($thumbs as $index => $timg): 
                                $t_resized = get_resized_image($timg, 150, 150, 'contain');
                            ?>
                                <div class="td-interactive-thumb <?php echo $index === 0 ? 'active' : ''; ?>" 
                                     onmouseover="updateHeroImg('<?php echo $unique_hero_id; ?>', '<?php echo $t_resized; ?>', this)">
                                    <img src="<?php echo $t_resized; ?>">
                                </div>
                            <?php endforeach; ?>
                            <?php for($i = count($thumbs); $i < 4; $i++): ?>
                                <div class="td-interactive-thumb"><img src="<?php echo $feat_img; ?>" style="opacity: 0.1;"></div>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                    
                    <a href="products.php" class="td-action-link mt-4">
                        Explore Now <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Dynamic Interactive Grids -->
            <?php 
            $sections = [
                ['title' => 'Hot Trends', 'icon' => 'fa-fire', 'tag' => 'Trending', 'data' => $trending_grid],
                ['title' => 'Popular', 'icon' => 'fa-star', 'tag' => 'Best Seller', 'data' => $popular_grid],
                ['title' => 'Style Guide', 'icon' => 'fa-gem', 'tag' => 'Handpicked', 'data' => $style_grid]
            ];

            foreach($sections as $sec): ?>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="td-interactive-widget">
                        <span class="td-widget-tag"><?php echo $sec['tag']; ?></span>
                        <h3 class="td-widget-title"><i class="fa-solid <?php echo $sec['icon']; ?>"></i> <?php echo $sec['title']; ?></h3>
                        
                        <div class="td-interactive-grid">
                            <?php foreach($sec['data'] as $item): 
                                $img_url = get_resized_image($item['featured_image'], 200, 200, 'contain');
                            ?>
                                <a href="product-details.php?slug=<?php echo $item['slug']; ?>" class="td-grid-item-wrap">
                                    <div class="td-grid-img-wrap">
                                        <img src="<?php echo $img_url; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    </div>
                                    <div class="td-grid-title-text"><?php echo mb_strimwidth(htmlspecialchars($item['name']), 0, 18, "..."); ?></div>
                                    <div class="td-grid-price-text">₹<?php echo number_format($item['sale_price']); ?></div>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <a href="products.php" class="td-action-link">
                            View All <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<script>
    function updateHeroImg(targetId, newSrc, thumbElement) {
        const mainImg = document.getElementById(targetId);
        if (mainImg) {
            mainImg.src = newSrc;
            
            // Update active state
            const parent = thumbElement.parentElement;
            parent.querySelectorAll('.td-interactive-thumb').forEach(t => t.classList.remove('active'));
            thumbElement.classList.add('active');
        }
    }
</script>

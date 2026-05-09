<style>
/* --- Premium Interactive Top Deals --- */
.top-deals-section-wrapper {
    background: #ffffff;
    padding: 10px 0 40px 0;
    overflow: hidden;
    position: relative; /* For background pseudo-element */
}

/* Lifestyle Background Image */
.top-deals-section-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('https://img.magnific.com/free-photo/amazed-young-woman-shopaholic-holding-colorful-shopping-bags-look-amused-shop-buying-thi_1258-119761.jpg?semt=ais_hybrid&w=740&q=80');
    background-size: cover;
    background-position: center bottom;
    background-attachment: fixed;
    opacity: 0.05; /* Low transparency as requested */
    pointer-events: none;
    z-index: 0;
}

.td-container-fluid {
    width: 100%;
    padding: 0 40px;
    position: relative;
    z-index: 1; /* Stay above background */
}

.td-main-header {
    text-align: center;
    margin-bottom: 35px;
}

.td-main-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    font-style: italic;
    color: #1a1a1a;
    position: relative;
    display: inline-block;
}

.td-main-header h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 2px;
    background: var(--accent-gold, #d4a017);
    border-radius: 2px;
}

/* Compact Interactive Widget */
.td-interactive-widget {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    height: 100%;
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease-out;
    display: flex;
    flex-direction: column;
    position: relative;
}

/* Floating Hero Widget (No Box) */
.td-hero-widget-special {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding-top: 10px;
}

.td-interactive-widget:hover {
    transform: translateY(-5px); /* More subtle lift */
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    border-color: var(--accent-gold, #d4a017);
}

.td-hero-widget-special:hover {
    transform: none !important;
}

.td-widget-tag {
    position: absolute;
    top: -10px;
    left: 20px;
    background: var(--accent-gold, #d4a017);
    color: #fff;
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 3px 12px;
    border-radius: 4px;
    letter-spacing: 1px;
}

.td-widget-title {
    font-family: 'Poppins', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.td-widget-title i {
    color: var(--accent-gold, #d4a017);
    font-size: 16px;
}

/* Hero Display (Compact) */
.td-interactive-hero {
    position: relative;
    margin-bottom: 15px;
}

.td-hero-main-img-box {
    width: 100%;
    /* Removed fixed height and background box for a cleaner look */
    overflow: hidden;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
}

.td-hero-main-img-box img {
    max-width: 100%;
    height: auto;
    max-height: 220px;
    object-fit: contain;
    transition: transform 0.4s;
    /* Remove any potential box shadow or border from the image itself */
    border: none;
    outline: none;
}

.td-interactive-widget:hover .td-hero-main-img-box img {
    transform: scale(1.05); /* Subtle scale */
}

.td-hero-info {
    text-align: center;
}

.td-hero-name {
    font-size: 14px;
    font-weight: 600;
    color: #444;
    margin-bottom: 4px;
    height: 20px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.td-hero-price {
    font-size: 18px;
    font-weight: 700;
    color: var(--accent-gold, #d4a017);
}

/* Thumbnail Switcher (Compact) */
.td-hero-thumbs-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 6px;
}

.td-interactive-thumb {
    border: 1px solid #eee;
    border-radius: 6px;
    height: 40px; /* Reduced height */
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    background: #fff;
}

.td-interactive-thumb:hover, .td-interactive-thumb.active {
    border-color: var(--accent-gold, #d4a017);
}

/* Dynamic Grid (Compact 2x2) */
.td-interactive-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 15px;
}

.td-grid-item-wrap {
    text-decoration: none !important;
    display: flex;
    flex-direction: column;
}

.td-grid-img-wrap {
    width: 100%;
    height: 100px; /* Reduced height */
    background: #fff;
    border-radius: 8px;
    border: 1px solid #f5f5f5;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.td-grid-img-wrap img {
    max-width: 80%;
    max-height: 80%;
    object-fit: contain;
    transition: transform 0.3s;
}

.td-grid-item-wrap:hover .td-grid-img-wrap img {
    transform: scale(1.08); /* Subtle scale */
}

.td-grid-title-text {
    font-size: 11px;
    font-weight: 600;
    color: #666;
    text-align: center;
    height: 16px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.td-grid-price-text {
    font-size: 13px;
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
                <div class="td-interactive-widget td-hero-widget-special">
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

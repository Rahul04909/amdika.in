<style>
/* --- Premium Top Deals Section --- */
.top-deals-section-wrapper {
    background-color: #ffffff;
    padding: 40px 0;
    overflow: hidden;
}

.td-container-fluid {
    width: 100%;
    padding: 0 40px;
}

.td-main-header {
    text-align: center;
    margin-bottom: 45px;
}

.td-main-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    font-style: italic;
    color: #1a1a1a;
    margin-bottom: 10px;
}

/* Widget Card Styling */
.td-premium-widget {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    height: 100%;
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.td-premium-widget:hover {
    box-shadow: 0 15px 40px rgba(0,0,0,0.06);
    border-color: var(--accent-gold, #d4a017);
}

.td-widget-title {
    font-family: 'Poppins', sans-serif;
    font-size: 20px;
    font-weight: 700;
    color: #232f3e;
    margin-bottom: 20px;
    border-left: 4px solid var(--accent-gold, #d4a017);
    padding-left: 15px;
}

/* Hero Widget (Newly Added) */
.td-hero-box {
    text-align: center;
    margin-bottom: 20px;
}

.td-hero-img-container {
    width: 100%;
    height: 220px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.td-hero-img-container img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.4s;
}

.td-hero-box:hover img {
    transform: scale(1.05);
}

.td-hero-name {
    font-size: 15px;
    font-weight: 600;
    color: #444;
    margin-bottom: 8px;
    height: 40px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.td-hero-price {
    font-size: 20px;
    font-weight: 700;
    color: var(--accent-gold, #d4a017);
}

.td-hero-thumbs {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-top: 15px;
}

.td-thumb-item {
    border: 1px solid #eee;
    border-radius: 4px;
    padding: 2px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
}

/* Grid Widget (2x2) */
.td-grid-box {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.td-grid-card {
    text-decoration: none !important;
    display: flex;
    flex-direction: column;
}

.td-grid-img-container {
    width: 100%;
    height: 120px;
    background: #fff;
    border-radius: 6px;
    border: 1px solid #f5f5f5;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.td-grid-img-container img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s;
}

.td-grid-card:hover img {
    transform: scale(1.1);
}

.td-grid-title {
    font-size: 12px;
    font-weight: 600;
    color: #666;
    height: 32px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.td-grid-price {
    font-size: 14px;
    font-weight: 700;
    color: #333;
}

/* Footer Link */
.td-footer-link {
    margin-top: auto;
    font-size: 14px;
    color: var(--accent-gold, #d4a017);
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.td-footer-link:hover {
    gap: 12px;
    color: #000;
}

@media (max-width: 991px) {
    .td-container-fluid { padding: 0 20px; }
    .td-premium-widget { padding: 15px; margin-bottom: 20px; }
    .td-hero-img-container { height: 180px; }
}

@media (max-width: 576px) {
    .td-main-header h2 { font-size: 24px; }
    .td-widget-title { font-size: 18px; }
    .td-hero-thumbs { grid-template-columns: repeat(4, 1fr); }
    .td-thumb-item { height: 40px; }
}
</style>

<section class="top-deals-section-wrapper">
    <div class="td-container-fluid">
        <div class="td-main-header">
            <h2>Trending <span style="color: var(--accent-gold, #d4a017);">Now</span></h2>
        </div>

        <div class="row g-4">
            <?php
            require_once __DIR__ . '/../database/db_config.php';
            require_once __DIR__ . '/../includes/image_helper.php';

            // Helper to fetch products
            if(!function_exists('get_td_products')){
                function get_td_products($conn, $limit, $offset = 0) {
                    $sql = "SELECT * FROM products ORDER BY id DESC LIMIT $limit OFFSET $offset";
                    $result = $conn->query($sql);
                    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
                }
            }

            $latest_batch = get_td_products($conn, 1);
            $trending_batch = get_td_products($conn, 4, 1);
            $explore_batch = get_td_products($conn, 4, 5);
            $recommended_batch = get_td_products($conn, 4, 9);
            ?>

            <!-- Newly Added Widget -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="td-premium-widget">
                    <h3 class="td-widget-title">Newly Added</h3>
                    <?php if(!empty($latest_batch)): 
                        $lp = $latest_batch[0];
                        $feat_img = get_resized_image($lp['featured_image'], 400, 400, 'contain');
                        $gallery = !empty($lp['gallery_images']) ? json_decode($lp['gallery_images'], true) : [];
                        $thumbs = array_slice($gallery ?: [], 0, 4);
                    ?>
                        <div class="td-hero-box">
                            <a href="product-details.php?slug=<?php echo $lp['slug']; ?>" class="text-decoration-none">
                                <div class="td-hero-img-container">
                                    <img src="<?php echo $feat_img; ?>" alt="<?php echo htmlspecialchars($lp['name']); ?>">
                                </div>
                                <h4 class="td-hero-name"><?php echo htmlspecialchars($lp['name']); ?></h4>
                                <div class="td-hero-price">₹<?php echo number_format($lp['sale_price']); ?></div>
                            </a>
                        </div>
                        <div class="td-hero-thumbs">
                            <?php foreach($thumbs as $timg): ?>
                                <div class="td-thumb-item">
                                    <img src="<?php echo get_resized_image($timg, 100, 100, 'contain'); ?>" class="img-fluid">
                                </div>
                            <?php endforeach; ?>
                            <?php for($i = count($thumbs); $i < 4; $i++): ?>
                                <div class="td-thumb-item">
                                    <img src="<?php echo $feat_img; ?>" class="img-fluid" style="opacity: 0.3;">
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                    <a href="products.php" class="td-footer-link mt-4">View Collection <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>

            <!-- Grid Widgets Generator -->
            <?php 
            $grid_configs = [
                ['title' => 'Trending Style', 'data' => $trending_batch],
                ['title' => 'More to Explore', 'data' => $explore_batch],
                ['title' => 'For You', 'data' => $recommended_batch]
            ];

            foreach($grid_configs as $config): ?>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="td-premium-widget">
                        <h3 class="td-widget-title"><?php echo $config['title']; ?></h3>
                        <div class="td-grid-box">
                            <?php foreach($config['data'] as $item): 
                                $gimg = get_resized_image($item['featured_image'], 200, 200, 'contain');
                            ?>
                                <a href="product-details.php?slug=<?php echo $item['slug']; ?>" class="td-grid-card">
                                    <div class="td-grid-img-container">
                                        <img src="<?php echo $gimg; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    </div>
                                    <span class="td-grid-title"><?php echo htmlspecialchars($item['name']); ?></span>
                                    <span class="td-grid-price">₹<?php echo number_format($item['sale_price']); ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <a href="products.php" class="td-footer-link">See All <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>

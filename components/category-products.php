<style>
/* =============================================================
   Handcrafted Category Collections — Hidesign Luxury Showcase
   ============================================================= */

.cp-section-wrapper {
    background-color: #ffffff;
    padding: 60px 0 30px;
    overflow: hidden;
}

.cp-container {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 30px;
}

/* Category Block Header */
.cp-block-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 28px;
    padding-bottom: 16px;
    border-bottom: 1px solid #ebe6df;
}

.cp-header-left span {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 10.5px;
    font-weight: 500;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #9a6431;
    margin-bottom: 4px;
}

.cp-block-header h2 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 28px;
    font-weight: 600;
    color: #1a1614;
    margin: 0;
}

.cp-view-all {
    font-family: 'Outfit', sans-serif;
    font-size: 12px;
    color: #1a1614;
    font-weight: 600;
    text-decoration: none !important;
    text-transform: uppercase;
    letter-spacing: 1.8px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 2px;
    border-bottom: 1px solid #c59b27;
}

.cp-view-all:hover {
    color: #c59b27;
    gap: 12px;
}

/* Horizontal Slider */
.cp-slider-outer {
    position: relative;
    margin-bottom: 60px;
}

.cp-slider-inner {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    padding: 12px 4px 24px;
    scrollbar-width: none;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

.cp-slider-inner::-webkit-scrollbar {
    display: none;
}

/* Premium Category Product Card */
.cp-card {
    flex: 0 0 260px;
    background: #ffffff;
    border-radius: 4px;
    padding: 14px;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    position: relative;
    border: 1px solid #ebe6df;
    text-decoration: none !important;
    display: flex;
    flex-direction: column;
}

.cp-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 32px rgba(28, 25, 23, 0.08);
    border-color: #c59b27;
}

.cp-img-box {
    width: 100%;
    height: 220px;
    border-radius: 2px;
    background: #faf8f5;
    margin-bottom: 14px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
}

.cp-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.cp-card:hover .cp-img {
    transform: scale(1.08);
}

.cp-rating {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 6px;
}

.cp-stars {
    color: #c59b27;
    font-size: 10px;
}

.cp-rev-count {
    font-family: 'Outfit', sans-serif;
    font-size: 10.5px;
    color: #8c827a;
}

.cp-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 15.5px;
    font-weight: 600;
    color: #1a1614;
    margin-bottom: 10px;
    height: 42px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    line-height: 1.35;
    transition: color 0.25s ease;
}

.cp-card:hover .cp-name {
    color: #9a6431;
}

.cp-price-row {
    margin-top: auto;
    display: flex;
    align-items: baseline;
    gap: 8px;
    padding-top: 8px;
    border-top: 1px solid #f2eee8;
}

.cp-sale-price {
    font-family: 'Outfit', sans-serif;
    font-size: 17px;
    font-weight: 700;
    color: #1a1614;
}

.cp-reg-price {
    font-family: 'Outfit', sans-serif;
    font-size: 12.5px;
    color: #a89f91;
    text-decoration: line-through;
}

/* Nav Buttons */
.cp-btn {
    position: absolute;
    top: 45%;
    transform: translateY(-50%);
    width: 42px;
    height: 42px;
    background: #ffffff;
    border-radius: 50%;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
    z-index: 5;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #eae5dd;
    color: #1a1614;
    cursor: pointer;
    opacity: 0;
    transition: all 0.3s ease;
}

.cp-slider-outer:hover .cp-btn {
    opacity: 1;
}

.cp-btn:hover {
    background: #c59b27;
    border-color: #c59b27;
    color: #1a1614;
}

.cp-prev { left: -14px; }
.cp-next { right: -14px; }

/* Category Promo Styles */
.cp-promo-container {
    width: 100%;
    margin-top: 25px;
    margin-bottom: 50px;
    border-radius: 4px;
    overflow: hidden;
}

.cp-promo-banner img {
    width: 100%;
    height: auto;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}

.cp-promo-banner:hover img {
    transform: scale(1.01);
}

.cp-video-wrapper {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
}

.cp-video-wrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

@media (max-width: 991px) {
    .cp-container { padding: 0 20px; }
    .cp-card { flex: 0 0 230px; }
    .cp-img-box { height: 180px; }
    .cp-block-header h2 { font-size: 24px; }
    .cp-btn { display: none; }
}

@media (max-width: 576px) {
    .cp-container { padding: 0 15px; }
    .cp-card { flex: 0 0 200px; padding: 10px; }
    .cp-img-box { height: 160px; }
    .cp-block-header { flex-direction: column; align-items: flex-start; gap: 8px; margin-bottom: 20px; }
    .cp-block-header h2 { font-size: 21px; }
    .cp-name { font-size: 14px; }
    .cp-sale-price { font-size: 15px; }
    .cp-slider-outer { margin-bottom: 40px; }
}
</style>

<?php
require_once __DIR__ . '/../database/db_config.php';
require_once __DIR__ . '/../includes/image_helper.php';

// Logic: Display categories which have at least 5 products
$cat_sql = "SELECT c.*, COUNT(p.id) as product_count 
            FROM product_categories c
            JOIN products p ON c.id = p.category_id
            WHERE p.status = 'active' 
            AND p.featured_image IS NOT NULL 
            AND p.featured_image != ''
            GROUP BY c.id
            HAVING product_count >= 5
            ORDER BY c.created_at DESC";

$cat_res = $conn->query($cat_sql);

if ($cat_res && $cat_res->num_rows > 0):
?>

<section class="cp-section-wrapper">
    <div class="cp-container">
        <?php while($category = $cat_res->fetch_assoc()): 
            $cid = $category['id'];
            $cname = htmlspecialchars($category['name']);
            $cslug = htmlspecialchars($category['slug']);
            
            // Fetch items
            $psql = "SELECT * FROM products WHERE category_id = $cid AND status = 'active' AND featured_image IS NOT NULL AND featured_image != '' ORDER BY id DESC LIMIT 10";
            $pres = $conn->query($psql);
            $unique_cat_id = "slider_" . $cid . "_" . rand(100, 999);
        ?>
            <div class="cp-block-header">
                <div class="cp-header-left">
                    <span>CURATED COLLECTION</span>
                    <h2><?php echo $cname; ?></h2>
                </div>
                <a href="products.php?category=<?php echo $cslug; ?>" class="cp-view-all">
                    <span>Explore All</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="cp-slider-outer">
                <button class="cp-btn cp-prev" onclick="scrollSlider('<?php echo $unique_cat_id; ?>', -1)" aria-label="Previous">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="cp-btn cp-next" onclick="scrollSlider('<?php echo $unique_cat_id; ?>', 1)" aria-label="Next">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                
                <div id="<?php echo $unique_cat_id; ?>" class="cp-slider-inner">
                    <?php while($prod = $pres->fetch_assoc()): 
                        $pimg = $prod['featured_image'];
                        if (empty($pimg) || !file_exists(__DIR__ . '/../' . $pimg)) continue;
                        
                        $presized = get_resized_image($pimg, 400, 400, 'contain');
                        $rating_val = 4 + (rand(0, 10)/10);
                    ?>
                        <a href="<?php echo $link_prefix; ?>product/<?php echo $prod['slug']; ?>" class="cp-card">
                            <div class="cp-img-box">
                                <img src="<?php echo $presized; ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" class="cp-img" loading="lazy">
                            </div>
                            
                            <div class="cp-rating">
                                <div class="cp-stars">
                                    <?php for($i=1; $i<=5; $i++): ?>
                                        <i class="fa-<?php echo ($i <= $rating_val) ? 'solid' : 'regular'; ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="cp-rev-count">(<?php echo rand(20, 200); ?>)</span>
                            </div>

                            <h3 class="cp-name"><?php echo htmlspecialchars($prod['name']); ?></h3>
                            
                            <?php
                            $gst_pct = isset($prod['gst_percent']) ? $prod['gst_percent'] : 0;
                            $inc_sale = $prod['sale_price'] + ($prod['sale_price'] * $gst_pct / 100);
                            $inc_reg = $prod['regular_price'] + ($prod['regular_price'] * $gst_pct / 100);
                            ?>
                            <div class="cp-price-row">
                                <span class="cp-sale-price">₹<?php echo number_format($inc_sale); ?></span>
                                <?php if($prod['regular_price'] > $prod['sale_price']): ?>
                                    <span class="cp-reg-price">₹<?php echo number_format($inc_reg); ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Category Promo (Banner or Video) -->
            <?php 
                $promo_sql = "SELECT * FROM category_promos WHERE category_id = $cid AND status = 'active' LIMIT 1";
                $promo_res = $conn->query($promo_sql);
                if($promo_res && $promo_res->num_rows > 0):
                    $promo = $promo_res->fetch_assoc();
            ?>
                <div class="cp-promo-container">
                    <?php if($promo['type'] == 'image'): ?>
                        <a href="<?php echo $promo['link_url'] ?: 'products.php?category='.$cslug; ?>" class="cp-promo-banner">
                            <img src="<?php echo $promo['media_path']; ?>" alt="Collection Spotlight" class="img-fluid" loading="lazy">
                        </a>
                    <?php else: ?>
                        <div class="cp-video-wrapper">
                            <iframe width="100%" height="450" src="<?php echo $promo['media_path']; ?>" title="Collection Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</section>

<script>
    if (typeof scrollSlider !== 'function') {
        function scrollSlider(id, direction) {
            const slider = document.getElementById(id);
            if (slider) {
                const amount = 320 * direction;
                slider.scrollBy({ left: amount, behavior: 'smooth' });
            }
        }
    }
</script>

<?php endif; ?>

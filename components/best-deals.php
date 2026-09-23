<style>
/* =============================================================
   The Icons & Bestsellers — Hidesign Luxury Product Showcase
   ============================================================= */

.best-deals-section-wrapper {
    background-color: #ffffff;
    padding: 70px 0 80px;
    overflow: hidden;
    position: relative;
}

.deals-container {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 30px;
}

.deals-header {
    text-align: center;
    margin-bottom: 50px;
}

.deals-kicker {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: #9a6431;
    margin-bottom: 8px;
}

.deals-header h2 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 34px;
    font-weight: 600;
    color: #1a1614;
    margin: 0 0 12px;
}

.deals-header .deals-subtitle {
    font-family: 'Outfit', sans-serif;
    font-size: 14px;
    font-weight: 300;
    color: #6b635b;
    max-width: 520px;
    margin: 0 auto;
    line-height: 1.5;
}

.deals-header .deals-divider {
    width: 48px;
    height: 2px;
    background-color: #c59b27;
    margin: 16px auto 0;
}

/* Slider Track Layout */
.best-deals-slider-outer {
    position: relative;
}

.best-deals-slider-container {
    display: flex;
    gap: 24px;
    overflow-x: auto;
    padding: 15px 5px 30px;
    scrollbar-width: none;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

.best-deals-slider-container::-webkit-scrollbar {
    display: none;
}

/* Luxury Product Card */
.deal-product-card {
    flex: 0 0 290px;
    background: #ffffff;
    border-radius: 4px;
    padding: 16px;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    position: relative;
    border: 1px solid #ebe6df;
    text-decoration: none !important;
    display: flex;
    flex-direction: column;
}

.deal-product-card:hover {
    transform: translateY(-8px);
    border-color: #c59b27;
    box-shadow: 0 16px 36px rgba(28, 25, 23, 0.08);
}

.deal-img-box {
    width: 100%;
    height: 260px;
    border-radius: 2px;
    background: #faf8f5;
    margin-bottom: 18px;
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 14px;
}

.deal-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.deal-product-card:hover .deal-img {
    transform: scale(1.08);
}

.deal-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #1a1614;
    color: #e5b94c;
    font-family: 'Outfit', sans-serif;
    font-size: 9.5px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 2px;
    z-index: 2;
    border: 1px solid rgba(197, 155, 39, 0.4);
}

.deal-content {
    padding: 0 4px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.deal-category {
    font-family: 'Outfit', sans-serif;
    font-size: 10.5px;
    font-weight: 500;
    text-transform: uppercase;
    color: #9a6431;
    letter-spacing: 2px;
    margin-bottom: 6px;
}

.deal-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 17px;
    font-weight: 600;
    color: #1a1614;
    margin-bottom: 12px;
    line-height: 1.35;
    height: 46px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    transition: color 0.25s ease;
}

.deal-product-card:hover .deal-name {
    color: #9a6431;
}

.deal-price-row {
    margin-top: auto;
    display: flex;
    align-items: baseline;
    gap: 10px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f2eee8;
}

.deal-sale-price {
    font-family: 'Outfit', sans-serif;
    font-size: 19px;
    font-weight: 700;
    color: #1a1614;
    letter-spacing: -0.2px;
}

.deal-reg-price {
    font-family: 'Outfit', sans-serif;
    font-size: 13.5px;
    color: #a89f91;
    text-decoration: line-through;
}

.deal-tax-note {
    font-family: 'Outfit', sans-serif;
    font-size: 10px;
    color: #8c827a;
    letter-spacing: 0.5px;
    margin-top: 4px;
}

.deal-action-link {
    margin-top: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-family: 'Outfit', sans-serif;
    font-size: 11.5px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #1a1614;
    transition: all 0.25s ease;
}

.deal-product-card:hover .deal-action-link {
    color: #c59b27;
}

.deal-action-link i {
    transition: transform 0.25s ease;
}

.deal-product-card:hover .deal-action-link i {
    transform: translateX(4px);
}

/* Interactive Navigation */
.deal-nav-btn {
    position: absolute;
    top: 45%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    background: #ffffff;
    border: 1px solid #eae5dd;
    border-radius: 50%;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1a1614;
    font-size: 14px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.deal-nav-btn:hover {
    background: #c59b27;
    border-color: #c59b27;
    color: #1a1614;
}

.deal-prev { left: -16px; }
.deal-next { right: -16px; }

@media (max-width: 991px) {
    .best-deals-section-wrapper { padding: 50px 0 60px; }
    .deals-container { padding: 0 20px; }
    .deal-product-card { flex: 0 0 250px; }
    .deal-img-box { height: 220px; }
    .deals-header h2 { font-size: 28px; }
}

@media (max-width: 576px) {
    .best-deals-section-wrapper { padding: 40px 0 45px; }
    .deals-container { padding: 0 15px; }
    .deals-header { margin-bottom: 28px; }
    .deals-kicker { font-size: 10px; letter-spacing: 2.5px; }
    .deals-header h2 { font-size: 23px; }
    .deal-product-card { flex: 0 0 215px; padding: 12px; }
    .deal-img-box { height: 180px; margin-bottom: 12px; }
    .deal-name { font-size: 15px; height: 40px; }
    .deal-sale-price { font-size: 16px; }
    .deal-nav-btn { display: none; }
}
</style>

<?php
if (!isset($bd_products)) {
    $bd_settings_result = $conn->query("SELECT category_ids FROM best_deals_settings WHERE id = 1");
    $bd_category_ids = [];
    if ($bd_settings_result && $bd_settings_result->num_rows > 0) {
        $row = $bd_settings_result->fetch_assoc();
        $bd_category_ids = json_decode($row['category_ids'], true) ?: [];
    }

    $bd_products = [];
    if (!empty($bd_category_ids)) {
        $bd_ids_str = implode(',', array_map('intval', $bd_category_ids));
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN product_categories c ON p.category_id = c.id 
                WHERE p.category_id IN ($bd_ids_str) 
                AND p.featured_image IS NOT NULL 
                AND p.featured_image != ''
                ORDER BY p.id DESC LIMIT 10";
        $result = $conn->query($sql);
        if ($result) {
            while($row = $result->fetch_assoc()) {
                $bd_products[] = $row;
            }
        }
    }
}
?>

<section class="best-deals-section-wrapper">
    <div class="deals-container">
        <div class="deals-header">
            <span class="deals-kicker">ICONIC CREATIONS</span>
            <h2>Timeless Bestsellers</h2>
            <p class="deals-subtitle">Handcrafted in genuine full-grain leather, sculpted to develop character and age gracefully with every journey.</p>
            <div class="deals-divider"></div>
        </div>

        <div class="best-deals-slider-outer">
            <!-- Navigation -->
            <button id="dealPrev" class="deal-nav-btn deal-prev" aria-label="Previous">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button id="dealNext" class="deal-nav-btn deal-next" aria-label="Next">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <div id="dealSlider" class="best-deals-slider-container">
                <?php if (!empty($bd_products)): ?>
                    <?php foreach($bd_products as $prod): 
                        $img = $prod['featured_image'];
                        if (empty($img) || !file_exists(__DIR__ . '/../' . $img)) continue;
                        $resized_img = get_resized_image($img, 450, 450, 'contain');
                    ?>
                        <a href="<?php echo $link_prefix; ?>product/<?php echo $prod['slug']; ?>" class="deal-product-card">
                            <?php if(!empty($prod['discount_percent']) && $prod['discount_percent'] > 0): ?>
                                <div class="deal-badge"><?php echo $prod['discount_percent']; ?>% OFF</div>
                            <?php else: ?>
                                <div class="deal-badge">ICONIC</div>
                            <?php endif; ?>
                            
                            <div class="deal-img-box">
                                <img src="<?php echo $resized_img; ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" class="deal-img" loading="lazy">
                            </div>

                            <div class="deal-content">
                                <span class="deal-category"><?php echo htmlspecialchars($prod['category_name'] ?? 'Leather Goods'); ?></span>
                                <h3 class="deal-name"><?php echo htmlspecialchars($prod['name']); ?></h3>
                                <?php
                                $gst_pct = isset($prod['gst_percent']) ? $prod['gst_percent'] : 0;
                                $inc_sale = $prod['sale_price'] + ($prod['sale_price'] * $gst_pct / 100);
                                $inc_reg = $prod['regular_price'] + ($prod['regular_price'] * $gst_pct / 100);
                                ?>
                                <div class="deal-price-row">
                                    <span class="deal-sale-price">₹<?php echo number_format($inc_sale); ?></span>
                                    <?php if($prod['regular_price'] > $prod['sale_price']): ?>
                                        <span class="deal-reg-price">₹<?php echo number_format($inc_reg); ?></span>
                                    <?php endif; ?>
                                </div>
                                <span class="deal-tax-note">Includes GST &bull; Pan-India Shipping</span>
                                <div class="deal-action-link">
                                    <span>Discover Details</span>
                                    <i class="fa-solid fa-arrow-right-long"></i>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center w-100 text-muted" style="padding: 40px 0;">Our iconic leather collection is being updated. Explore full catalog.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('dealSlider');
        const prev = document.getElementById('dealPrev');
        const next = document.getElementById('dealNext');

        if (!slider) return;
        const scrollAmount = 350;

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

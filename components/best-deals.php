<style>
/* --- Premium Best Deals Section --- */
.best-deals-section-wrapper {
    background-color: #ffffff; /* Pure white background as requested */
    padding: 40px 0; /* Reduced padding to tighten spacing */
    overflow: hidden;
}

.deals-container-fluid {
    width: 100%;
    padding: 0 40px;
}

.deals-header {
    text-align: center;
    margin-bottom: 45px;
}

.deals-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    font-style: italic;
    color: #1a1a1a;
    margin-bottom: 10px;
}

.deals-header .subtitle {
    font-size: 14px;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 2px;
    display: block;
}

/* Slider Layout */
.best-deals-slider-container {
    position: relative;
    display: flex;
    gap: 20px;
    overflow-x: auto;
    padding: 20px 5px;
    scrollbar-width: none;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

.best-deals-slider-container::-webkit-scrollbar {
    display: none;
}

/* Premium Product Card */
.deal-product-card {
    flex: 0 0 280px;
    background: #fff;
    border-radius: 12px;
    padding: 15px;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    position: relative;
    border: 1px solid #f0f0f0; /* Subtle border */
    text-decoration: none !important;
    display: flex;
    flex-direction: column;
}

.deal-product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    border-color: var(--accent-gold, #d4a017);
}

.deal-img-box {
    width: 100%;
    height: 250px;
    border-radius: 8px;
    background: #ffffff; /* White background for image box */
    margin-bottom: 15px;
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.deal-img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* Changed to contain for perfect fit */
    padding: 10px;
    transition: transform 0.6s ease;
}

.deal-product-card:hover .deal-img {
    transform: scale(1.1);
}

.deal-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #e31e24;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    z-index: 2;
}

.deal-content {
    padding: 5px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.deal-category {
    font-size: 11px;
    text-transform: uppercase;
    color: #999;
    letter-spacing: 1px;
    margin-bottom: 5px;
}

.deal-name {
    font-size: 16px;
    font-weight: 600;
    color: #222;
    margin-bottom: 10px;
    line-height: 1.4;
    height: 44px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.deal-price-row {
    margin-top: auto;
    display: flex;
    align-items: center;
    gap: 10px;
}

.deal-sale-price {
    font-size: 18px;
    font-weight: 700;
    color: var(--accent-gold, #d4a017);
}

.deal-reg-price {
    font-size: 14px;
    color: #bbb;
    text-decoration: line-through;
}

/* Interactive Navigation */
.deal-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 45px;
    height: 45px;
    background: #fff;
    border: none;
    border-radius: 50%;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    transition: all 0.3s;
    cursor: pointer;
}

.deal-nav-btn:hover {
    background: var(--accent-gold, #d4a017);
    color: #fff;
}

.deal-prev { left: 10px; }
.deal-next { right: 10px; }

@media (max-width: 991px) {
    .deals-container-fluid { padding: 0 20px; }
    .deal-product-card { flex: 0 0 240px; }
    .deal-img-box { height: 200px; }
    .deals-header h2 { font-size: 26px; }
}

@media (max-width: 576px) {
    .best-deals-section-wrapper { padding: 40px 0; }
    .deal-product-card { flex: 0 0 200px; padding: 10px; }
    .deal-img-box { height: 180px; }
    .deal-name { font-size: 14px; height: 38px; }
    .deal-sale-price { font-size: 16px; }
    .deal-nav-btn { display: none; } /* Better to scroll by touch on mobile */
}
</style>

<?php
// Re-fetch data if not already defined (in case of standalone testing)
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
    <div class="deals-container-fluid">
        <div class="deals-header">
            <span class="subtitle">Handpicked For You</span>
            <h2>Best <span style="color: var(--accent-gold, #d4a017);">Deals</span></h2>
        </div>

        <div style="position: relative;">
            <!-- Navigation -->
            <button id="dealPrev" class="deal-nav-btn deal-prev"><i class="fa-solid fa-chevron-left"></i></button>
            <button id="dealNext" class="deal-nav-btn deal-next"><i class="fa-solid fa-chevron-right"></i></button>

            <div id="dealSlider" class="best-deals-slider-container">
                <?php if (!empty($bd_products)): ?>
                    <?php foreach($bd_products as $prod): 
                        $img = $prod['featured_image'];
                        
                        // Strict check: skip if image is missing on disk
                        if (empty($img) || !file_exists(__DIR__ . '/../' . $img)) continue;
                        
                        $resized_img = get_resized_image($img, 400, 500, 'contain'); // Preserves full product aspect ratio
                    ?>
                        <a href="product-details.php?slug=<?php echo $prod['slug']; ?>" class="deal-product-card">
                            <?php if($prod['discount_percent'] > 0): ?>
                                <div class="deal-badge"><?php echo $prod['discount_percent']; ?>% OFF</div>
                            <?php endif; ?>
                            
                            <div class="deal-img-box">
                                <img src="<?php echo $resized_img; ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" class="deal-img">
                            </div>

                            <div class="deal-content">
                                <span class="deal-category"><?php echo htmlspecialchars($prod['category_name'] ?? 'Collection'); ?></span>
                                <h3 class="deal-name"><?php echo htmlspecialchars($prod['name']); ?></h3>
                                <?php
                                $gst_pct = isset($prod['gst_percent']) ? $prod['gst_percent'] : 0;
                                $inc_sale = $prod['sale_price'] + ($prod['sale_price'] * $gst_pct / 100);
                                $inc_reg = $prod['regular_price'] + ($prod['regular_price'] * $gst_pct / 100);
                                ?>
                                <div class="deal-price-row">
                                    <span class="deal-sale-price">₹<?php echo number_format($inc_sale); ?> <small class="text-muted" style="font-size: 10px; font-weight:normal;">Inc. GST</small></span>
                                    <?php if($prod['regular_price'] > $prod['sale_price']): ?>
                                        <span class="deal-reg-price">₹<?php echo number_format($inc_reg); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center w-100 text-muted">No deals found today. Check back soon!</p>
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

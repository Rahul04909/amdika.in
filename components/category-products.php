<style>
/* --- Premium Category Products Section --- */
.cp-section-wrapper {
    background-color: #ffffff;
    padding: 20px 0;
    overflow: hidden;
}

.cp-container-fluid {
    width: 100%;
    padding: 0 40px;
}

/* Category Block Header */
.cp-block-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f0f0f0;
}

.cp-block-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    font-weight: 700;
    font-style: italic;
    color: #1a1a1a;
    margin: 0;
}

.cp-view-all {
    font-size: 14px;
    color: var(--accent-gold, #d4a017);
    font-weight: 700;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s;
}

.cp-view-all:hover {
    color: #000;
    letter-spacing: 2px;
}

/* Horizontal Slider */
.cp-slider-outer {
    position: relative;
    margin-bottom: 50px;
}

.cp-slider-inner {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    padding: 15px 5px;
    scrollbar-width: none;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

.cp-slider-inner::-webkit-scrollbar {
    display: none;
}

/* Premium Category Product Card */
.cp-card {
    flex: 0 0 240px;
    background: #fff;
    border-radius: 12px;
    padding: 12px;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    position: relative;
    border: 1px solid #f0f0f0;
    text-decoration: none !important;
    display: flex;
    flex-direction: column;
}

.cp-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.05);
    border-color: var(--accent-gold, #d4a017);
}

.cp-img-box {
    width: 100%;
    height: 200px;
    border-radius: 8px;
    background: #fff;
    margin-bottom: 12px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cp-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 8px;
    transition: transform 0.5s;
}

.cp-card:hover .cp-img {
    transform: scale(1.08);
}

.cp-rating {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 8px;
}

.cp-stars {
    color: #ffc107;
    font-size: 11px;
}

.cp-rev-count {
    font-size: 11px;
    color: #999;
}

.cp-name {
    font-size: 14px;
    font-weight: 600;
    color: #222;
    margin-bottom: 10px;
    height: 38px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    line-height: 1.4;
}

.cp-price-row {
    margin-top: auto;
    display: flex;
    align-items: center;
    gap: 8px;
}

.cp-sale-price {
    font-size: 16px;
    font-weight: 700;
    color: var(--accent-gold, #d4a017);
}

.cp-reg-price {
    font-size: 12px;
    color: #ccc;
    text-decoration: line-through;
}

/* Nav Buttons */
.cp-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    z-index: 5;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    opacity: 0;
    transition: all 0.3s;
}

.cp-slider-outer:hover .cp-btn {
    opacity: 1;
}

.cp-prev { left: -10px; }
.cp-next { right: -10px; }

@media (max-width: 991px) {
    .cp-container-fluid { padding: 0 20px; }
    .cp-card { flex: 0 0 200px; }
    .cp-img-box { height: 160px; }
    .cp-btn { display: none; }
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
            GROUP BY c.id
            HAVING product_count >= 5
            ORDER BY c.created_at DESC";

$cat_res = $conn->query($cat_sql);

if ($cat_res && $cat_res->num_rows > 0):
?>

<section class="cp-section-wrapper">
    <div class="cp-container-fluid">
        <?php while($category = $cat_res->fetch_assoc()): 
            $cid = $category['id'];
            $cname = htmlspecialchars($category['name']);
            $cslug = htmlspecialchars($category['slug']);
            
            // Fetch items
            $psql = "SELECT * FROM products WHERE category_id = $cid AND status = 'active' ORDER BY id DESC LIMIT 10";
            $pres = $conn->query($psql);
            $unique_cat_id = "slider_" . $cid . "_" . rand(100, 999);
        ?>
            <div class="cp-block-header">
                <h2><?php echo $cname; ?> <span style="color: var(--accent-gold, #d4a017);">Collection</span></h2>
                <a href="products.php?category=<?php echo $cslug; ?>" class="cp-view-all">View All</a>
            </div>

            <div class="cp-slider-outer">
                <button class="cp-btn cp-prev" onclick="scrollSlider('<?php echo $unique_cat_id; ?>', -1)"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="cp-btn cp-next" onclick="scrollSlider('<?php echo $unique_cat_id; ?>', 1)"><i class="fa-solid fa-chevron-right"></i></button>
                
                <div id="<?php echo $unique_cat_id; ?>" class="cp-slider-inner">
                    <?php while($prod = $pres->fetch_assoc()): 
                        $pimg = !empty($prod['featured_image']) ? $prod['featured_image'] : 'assets/images/demo-data/product.jpg';
                        $presized = get_resized_image($pimg, 300, 300, 'contain');
                        $rating_val = 4 + (rand(0, 10)/10);
                    ?>
                        <a href="product-details.php?slug=<?php echo $prod['slug']; ?>" class="cp-card">
                            <div class="cp-img-box">
                                <img src="<?php echo $presized; ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" class="cp-img">
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
                            
                            <div class="cp-price-row">
                                <span class="cp-sale-price">₹<?php echo number_format($prod['sale_price']); ?></span>
                                <?php if($prod['regular_price'] > $prod['sale_price']): ?>
                                    <span class="cp-reg-price">₹<?php echo number_format($prod['regular_price']); ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<script>
    if (typeof scrollSlider !== 'function') {
        function scrollSlider(id, direction) {
            const slider = document.getElementById(id);
            if (slider) {
                const amount = 300 * direction;
                slider.scrollBy({ left: amount, behavior: 'smooth' });
            }
        }
    }
</script>

<?php endif; ?>

<link rel="stylesheet" href="<?php echo $assets_path; ?>css/fitness-showcase.css">

<?php
if (!isset($conn)) {
    require_once __DIR__ . '/../database/db_config.php';
}
if (!isset($link_prefix)) {
    $link_prefix = $base_path ?? '/';
}

$per_page = 4;

// ─── Try reading from DB ───────────────────────────────────────
$db_collections = [];
$table_check = $conn->query("SHOW TABLES LIKE 'collections'");
if ($table_check && $table_check->num_rows > 0) {
    $col_res = $conn->query("SELECT c.*, p.slug as main_slug FROM collections c LEFT JOIN products p ON c.main_product_id = p.id WHERE c.status = 'active' ORDER BY c.sort_order ASC, c.created_at DESC");
    if ($col_res && $col_res->num_rows > 0) {
        while ($col = $col_res->fetch_assoc()) {
            $prods_ids = json_decode($col['selected_products'], true) ?? [];
            $prods_data = [];
            if (!empty($prods_ids)) {
                $ids_str = implode(',', array_map('intval', $prods_ids));
                $p_res = $conn->query("SELECT id, name, slug, featured_image, mrp, sale_price FROM products WHERE id IN ($ids_str) AND status = 'active'");
                if ($p_res) {
                    while ($p = $p_res->fetch_assoc()) {
                        $prods_data[] = $p;
                    }
                }
            }
            $db_collections[] = [
                'amk'         => $col['amk_code'],
                'name'        => $col['model_name'],
                'hero_image'  => $col['hero_image'],
                'main_slug'   => $col['main_slug'],
                'products'    => $prods_data,
            ];
        }
    }
}

// ─── Fallback hardcoded data ────────────────────────────────────
$hero_images_fallback = [
    'collection-1.jpeg', 'style-1.png', 'style-2.png', 'banner-2.png', 'banner-3.png',
    'prod_1769666277_feat.png', 'prod_1769664231_feat.jpeg', 'prod_1769668560_feat.png',
    'prod_1769668868_feat.png', 'prod_1769672667_feat.jpeg', 'prod_1769674164_feat.jpeg',
    'prod_1769680450_feat.png', 'prod_1769684162_feat.png', 'prod_1769686628_feat.png',
    'prod_1769687740_feat.png',
];

$prod_img_fallback = [
    'Basket'          => 'prod_1769577427_feat.png',
    'Belt'            => 'prod_1769577659_feat.png',
    'Candle Holder'   => 'prod_1769604957_feat.jpeg',
    'Coaster'         => 'prod_1770626934_feat.jpeg',
    'Desk Organizer'  => 'prod_1769605484_feat.png',
    'Key Holder'      => 'prod_1769605883_feat.jpeg',
    'Laundry Hamper'  => 'prod_1769662551_feat.jpeg',
    'Magazine Holder' => 'prod_1769664796_feat.jpeg',
    'Pen Holder'      => 'prod_1769668209_feat.png',
    'Photo Frame'     => 'prod_1769673099_feat.jpeg',
    'Remote Holder'   => 'prod_1769673326_feat.jpeg',
    'Sq. Tissue Box'  => 'prod_1769679071_feat.png',
    'Storage Basket'  => 'prod_1769679897_feat.png',
    'Table Clock'     => 'prod_1769686204_feat.png',
    'Tissue Box'      => 'prod_1769687334_feat.png',
    'Towel Tray'      => 'prod_1770282874_feat.jpeg',
    'Tray Large'      => 'prod_1770295066_feat.jpeg',
    'Tray Small'      => 'prod_1770295149_feat.jpeg',
    'Vase'            => 'prod_1770364000_feat.jpeg',
    'Wall Shelf'      => 'prod_1770364260_feat.jpeg',
    'Waste Bin'       => 'prod_1770368561_feat.jpeg',
];

$fallback_models = [
    ['amk' => 'AMK 1501', 'name' => 'Flooting Model', 'products' => ['Laundry Hamper', 'Waste Bin', 'Remote Holder', 'Key Holder', 'Photo Frame', 'Candle Holder', 'Storage Basket', 'Wall Shelf', 'Desk Organizer', 'Table Clock']],
    ['amk' => 'AMK 1502', 'name' => 'Suitcase Model', 'products' => ['Laundry Hamper', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Remote Holder', 'Key Holder', 'Photo Frame', 'Candle Holder']],
    ['amk' => 'AMK 1503', 'name' => 'Dual Tone',      'products' => ['Waste Bin', 'Sq. Tissue Box', 'Tissue Box', 'Pen Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Key Holder', 'Coaster', 'Storage Basket']],
    ['amk' => 'AMK 1504', 'name' => 'Stud Model',     'products' => ['Laundry Hamper', 'Waste Bin', 'Basket', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Pen Holder', 'Coaster']],
    ['amk' => 'AMK 1505', 'name' => 'Flap Model',     'products' => ['Laundry Hamper', 'Waste Bin', 'Sq. Tissue Box', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Key Holder', 'Photo Frame']],
    ['amk' => 'AMK 1506', 'name' => 'Caller Model',   'products' => ['Laundry Hamper', 'Waste Bin', 'Coaster', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Remote Holder', 'Belt']],
    ['amk' => 'AMK 1507', 'name' => 'Flower Model',   'products' => ['Laundry Hamper', 'Waste Bin', 'Basket', 'Tissue Box', 'Candle Holder', 'Photo Frame', 'Key Holder', 'Vase', 'Wall Shelf', 'Storage Basket']],
    ['amk' => 'AMK 1508', 'name' => 'Classic',        'products' => ['Laundry Hamper', 'Waste Bin', 'Coaster', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Key Holder', 'Desk Organizer']],
    ['amk' => 'AMK 1509', 'name' => 'Modern',         'products' => ['Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Remote Holder', 'Key Holder', 'Desk Organizer', 'Photo Frame', 'Table Clock']],
    ['amk' => 'AMK 1510', 'name' => 'Essential',      'products' => ['Laundry Hamper', 'Waste Bin', 'Coaster', 'Tissue Box', 'Key Holder', 'Photo Frame', 'Candle Holder', 'Storage Basket', 'Wall Shelf', 'Desk Organizer']],
    ['amk' => 'AMK 1511', 'name' => 'Double Stud',    'products' => ['Tissue Box', 'Laundry Hamper', 'Waste Bin', 'Coaster', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Remote Holder']],
    ['amk' => 'AMK 1512', 'name' => 'Cane',           'products' => ['Tissue Box', 'Laundry Hamper', 'Waste Bin', 'Coaster', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Remote Holder']],
    ['amk' => 'AMK 1513', 'name' => 'Weave',          'products' => ['Tissue Box', 'Laundry Hamper', 'Waste Bin', 'Coaster', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Remote Holder']],
    ['amk' => '',          'name' => 'Caller Stud',    'products' => ['Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Remote Holder', 'Key Holder', 'Photo Frame', 'Coaster']],
    ['amk' => 'AMK 1514', 'name' => 'Double Stud',    'products' => ['Laundry Hamper', 'Waste Bin', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Key Holder', 'Candle Holder']],
];

// ─── Decide which data to render ────────────────────────────────
$use_db = !empty($db_collections);
$all_models = $use_db ? $db_collections : $fallback_models;

foreach ($all_models as $midx => $m):
    $products = $m['products'];
    $total = count($products);
    $pages = max(1, ceil($total / $per_page));
    $model_label = trim(($m['amk'] ?? '') ? ($m['amk'] . ' · ' . $m['name']) : $m['name']);

    // ─── Hero image ──────────────────────────────────────────────
    if ($use_db && !empty($m['hero_image'])) {
        $hero_img = $link_prefix . $m['hero_image'];
    } else {
        $hero_file = $hero_images_fallback[$midx % count($hero_images_fallback)];
        $hero_img = $assets_path . 'images/' . (in_array($hero_file, ['collection-1.jpeg','style-1.png','style-2.png','banner-2.png','banner-3.png']) ? 'collection/' : 'products/') . $hero_file;
    }

    // ─── Shop Collection URL ─────────────────────────────────────
    $shop_url = '#';
    if ($use_db && !empty($m['main_slug'])) {
        $shop_url = $link_prefix . 'product/' . $m['main_slug'];
    }
?>

<section class="fs-showcase" data-autoplay="4000" aria-labelledby="fs-h-<?php echo $midx; ?>">
    <div class="fs-container">
        <div class="fs-layout">

            <!-- ─── Left: Hero ─── -->
            <div class="fs-hero">
                <div class="fs-hero-img">
                    <img
                        src="<?php echo $hero_img; ?>"
                        alt="<?php echo $model_label; ?>"
                        loading="lazy"
                        decoding="async">
                </div>
                <div class="fs-hero-overlay"></div>
                <div class="fs-hero-body">
                    <span class="fs-hero-sub">Amadika</span>
                    <h2 id="fs-h-<?php echo $midx; ?>" class="fs-hero-title"><?php echo $m['name'] ?: 'Collection'; ?></h2>
                    <p class="fs-hero-desc"><?php echo ($m['amk'] ?? ''); ?> · <?php echo $total; ?> products</p>
                    <a href="<?php echo $shop_url; ?>" class="fs-hero-btn" aria-label="Shop <?php echo $m['name']; ?>">
                        Shop Collection
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
            </div>

            <!-- ─── Right: Carousel ─── -->
            <div class="fs-right">
                <div class="fs-right-header">
                    <h3 class="fs-right-title"><?php echo $m['name'] ?: 'Collection'; ?> Products</h3>
                    <span class="fs-right-count"><?php echo $total; ?> items</span>
                </div>

                <div class="fs-carousel">
                    <div class="fs-c-track" role="list">
                        <?php for ($i = 0; $i < $pages; $i++): ?>
                        <div class="fs-c-slide" role="group" aria-label="Page <?php echo $i + 1; ?> of <?php echo $pages; ?>">
                            <?php $chunk = array_slice($products, $i * $per_page, $per_page); ?>
                            <?php foreach ($chunk as $prod):
                                if ($use_db):
                                    // DB product (associative array)
                                    $prod_name  = $prod['name'];
                                    $prod_slug  = $prod['slug'];
                                    $prod_img   = !empty($prod['featured_image']) ? $link_prefix . $prod['featured_image'] : $assets_path . 'images/products/prod_1769666277_feat.png';
                                    $prod_url   = $link_prefix . 'product/' . $prod_slug;
                                    $mrp        = floatval($prod['mrp'] ?? 0);
                                    $sale       = floatval($prod['sale_price'] ?? 0);
                                    $disc       = $mrp > 0 ? round((1 - $sale / $mrp) * 100) : 0;
                                    $rating_val = round(3.5 + (crc32($prod_name) % 100) / 100 * 1.5, 1);
                                    $reviews    = 10 + (crc32($prod_name) % 491);
                                else:
                                    // Fallback: product name string
                                    $prod_name  = $prod;
                                    $prod_slug  = '';
                                    $img_key    = isset($prod_img_fallback[$prod]) ? $prod_img_fallback[$prod] : 'prod_1769666277_feat.png';
                                    $prod_img   = $assets_path . 'images/products/' . $img_key;
                                    $prod_url   = '#';
                                    $seed       = crc32($prod);
                                    mt_srand($seed);
                                    $sale       = mt_rand(299, 2499);
                                    $mrp        = $sale + mt_rand(100, 1200);
                                    $disc       = round((1 - $sale / $mrp) * 100);
                                    $rating_val = round(3.5 + (($seed & 0xFF) / 255) * 1.5, 1);
                                    $reviews    = 10 + ($seed % 491);
                                endif;

                                $full  = floor($rating_val);
                                $frac  = $rating_val - $full;
                                $stars = str_repeat('★', $full);
                                if ($frac >= 0.5) $stars .= '★';
                            ?>
                            <a href="<?php echo $prod_url; ?>" class="fs-card" aria-label="<?php echo $prod_name; ?>">
                                <span class="fs-card-badge"><?php echo $disc; ?>% off</span>
                                <span class="fs-card-img">
                                    <img
                                        src="<?php echo $prod_img; ?>"
                                        alt="<?php echo $prod_name; ?>"
                                        loading="lazy"
                                        decoding="async">
                                </span>
                                <span class="fs-card-body">
                                    <span class="fs-card-rating">
                                        <span class="fs-card-stars"><?php echo $stars; ?></span>
                                        <span class="fs-card-rev">(<?php echo $reviews; ?>)</span>
                                    </span>
                                    <span class="fs-card-name"><?php echo $prod_name; ?></span>
                                    <span class="fs-card-price">
                                        <span class="fs-card-sale">₹<?php echo number_format($sale); ?></span>
                                        <span class="fs-card-reg">₹<?php echo number_format($mrp); ?></span>
                                    </span>
                                </span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <?php if ($pages > 1): ?>
                <div class="fs-dots" role="tablist" aria-label="Slides">
                    <?php for ($i = 0; $i < $pages; $i++): ?>
                    <button class="fs-dot<?php echo $i === 0 ? ' is-active' : ''; ?>" type="button" role="tab" aria-label="Page <?php echo $i + 1; ?>" data-slide="<?php echo $i; ?>"></button>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<?php endforeach; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.fs-showcase[data-autoplay]').forEach(function(section) {
        var track = section.querySelector('.fs-c-track');
        var slides = section.querySelectorAll('.fs-c-slide');
        var dots = section.querySelectorAll('.fs-dot');
        var total = slides.length;
        if (total < 2) return;

        var current = 0;
        var interval = parseInt(section.getAttribute('data-autoplay'), 10);
        var timer = null;

        function goTo(index) {
            if (index < 0) index = total - 1;
            if (index >= total) index = 0;
            current = index;
            track.style.transform = 'translateX(-' + (current * 100) + '%)';
            dots.forEach(function(d, i) {
                d.classList.toggle('is-active', i === current);
            });
        }

        function next() { goTo(current + 1); }

        function start() {
            stop();
            timer = setInterval(next, interval);
        }

        function stop() {
            if (timer) { clearInterval(timer); timer = null; }
        }

        dots.forEach(function(dot) {
            dot.addEventListener('click', function() {
                goTo(parseInt(this.getAttribute('data-slide'), 10));
                start();
            });
        });

        section.addEventListener('mouseenter', stop);
        section.addEventListener('mouseleave', start);

        start();
    });
});
</script>

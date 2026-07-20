<link rel="stylesheet" href="<?php echo $assets_path; ?>css/fitness-showcase.css">

<?php
$hero_img = $assets_path . 'images/collection/collection-1.jpeg';

// ─── Usage: set $show_model_index to 0-14 to pick a model ───
if (!isset($show_model_index)) $show_model_index = 0;

$models = [
    ['amk' => 'AMK 1501', 'name' => 'Flooting Model',  'products' => ['Laundry Hamper', 'Waste Bin', 'Remote Holder']],
    ['amk' => 'AMK 1502', 'name' => 'Suitcase Model',  'products' => ['Laundry Hamper', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Remote Holder']],
    ['amk' => 'AMK 1503', 'name' => 'Dual Tone',       'products' => ['Waste Bin', 'Sq. Tissue Box', 'Tissue Box', 'Pen Holder', 'Towel Tray', 'Tray Small', 'Tray Large']],
    ['amk' => 'AMK 1504', 'name' => 'Stud Model',      'products' => ['Laundry Hamper', 'Waste Bin', 'Basket', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Pen Holder']],
    ['amk' => 'AMK 1505', 'name' => 'Flap Model',      'products' => ['Laundry Hamper', 'Waste Bin', 'Sq. Tissue Box', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large']],
    ['amk' => 'AMK 1506', 'name' => 'Caller Model',    'products' => ['Laundry Hamper', 'Waste Bin', 'Coaster', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Remote Holder', 'Belt']],
    ['amk' => 'AMK 1507', 'name' => 'Flower Model',    'products' => ['Laundry Hamper', 'Waste Bin', 'Basket', 'Tissue Box']],
    ['amk' => 'AMK 1508', 'name' => 'Classic',          'products' => ['Laundry Hamper', 'Waste Bin', 'Coaster', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large']],
    ['amk' => 'AMK 1509', 'name' => 'Modern',          'products' => ['Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Remote Holder']],
    ['amk' => 'AMK 1510', 'name' => 'Essential',        'products' => ['Laundry Hamper', 'Waste Bin', 'Coaster', 'Tissue Box']],
    ['amk' => 'AMK 1511', 'name' => 'Double Stud',     'products' => ['Tissue Box', 'Laundry Hamper', 'Waste Bin', 'Coaster', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Remote Holder']],
    ['amk' => 'AMK 1512', 'name' => 'Cane',            'products' => ['Tissue Box', 'Laundry Hamper', 'Waste Bin', 'Coaster', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Remote Holder']],
    ['amk' => 'AMK 1513', 'name' => 'Weave',           'products' => ['Tissue Box', 'Laundry Hamper', 'Waste Bin', 'Coaster', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Remote Holder']],
    ['amk' => '',          'name' => 'Caller Stud',     'products' => ['Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Remote Holder']],
    ['amk' => 'AMK 1514', 'name' => 'Double Stud',     'products' => ['Laundry Hamper', 'Waste Bin', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket']],
];

$m = $models[$show_model_index] ?? $models[0];
$model_label = trim($m['amk'] ? $m['amk'] . ' · ' . $m['name'] : $m['name']);
?>

<section class="fs-showcase" aria-labelledby="fs-heading">
    <div class="fs-container">
        <div class="fs-layout">

            <!-- ─── Left: Hero / Model Image ─── -->
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
                    <h2 id="fs-heading" class="fs-hero-title"><?php echo $m['name'] ?: 'Collection'; ?></h2>
                    <p class="fs-hero-desc"><?php echo $m['amk']; ?> · <?php echo count($m['products']); ?> products</p>
                    <a href="#" class="fs-hero-btn" aria-label="View <?php echo $m['name']; ?>">
                        View Collection
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
            </div>

            <!-- ─── Right: Product Cards ─── -->
            <div class="fs-right">
                <div class="fs-right-header">
                    <h3 class="fs-right-title"><?php echo $m['name'] ?: 'Collection'; ?> Products</h3>
                    <span class="fs-right-count"><?php echo count($m['products']); ?> items</span>
                </div>
                <div class="fs-grid" role="list">
                    <?php foreach ($m['products'] as $prod): ?>
                    <a href="#" class="fs-card" role="listitem" aria-label="<?php echo $prod; ?>">
                        <span class="fs-card-img">
                            <img
                                src="https://picsum.photos/seed/<?php echo urlencode(strtolower(str_replace([' ', '(', ')'], '-', $prod))); ?>/120/120"
                                alt="<?php echo $prod; ?>"
                                loading="lazy"
                                decoding="async">
                        </span>
                        <span class="fs-card-body">
                            <span class="fs-card-name"><?php echo $prod; ?></span>
                        </span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var grid = document.querySelector('.fs-grid');
    if (!grid) return;
    var isDown = false, startX = 0, scrollLeft = 0;
    grid.addEventListener('mousedown', function(e) {
        isDown = true;
        startX = e.pageX - grid.offsetLeft;
        scrollLeft = grid.scrollLeft;
        grid.style.cursor = 'grabbing';
    });
    grid.addEventListener('mouseleave', function() { isDown = false; grid.style.cursor = 'grab'; });
    grid.addEventListener('mouseup', function() { isDown = false; grid.style.cursor = 'grab'; });
    grid.addEventListener('mousemove', function(e) {
        if (!isDown) return;
        e.preventDefault();
        grid.scrollLeft = scrollLeft - (e.pageX - grid.offsetLeft - startX) * 1.5;
    });
});
</script>

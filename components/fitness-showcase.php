<link rel="stylesheet" href="<?php echo $assets_path; ?>css/fitness-showcase.css">

<?php
$hero_img = $assets_path . 'images/collection/collection-1.jpeg';

$collections = [
    ['amk' => 'AMK 1501', 'name' => 'Flooting Model',  'products' => ['Laundry Hamper', 'Waste Bin', 'Remote Holder']],
    ['amk' => 'AMK 1502', 'name' => 'Suitcase Model',  'products' => ['Laundry Hamper', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Remote Holder']],
    ['amk' => 'AMK 1503', 'name' => '',                'products' => ['Waste Bin', 'Sq. Tissue Box', 'Tissue Box', 'Pen Holder', 'Towel Tray', 'Tray Small', 'Tray Large']],
    ['amk' => 'AMK 1504', 'name' => 'Stud Model',      'products' => ['Laundry Hamper', 'Waste Bin', 'Basket', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Pen Holder']],
    ['amk' => 'AMK 1505', 'name' => 'Flap Model',      'products' => ['Laundry Hamper', 'Waste Bin', 'Sq. Tissue Box', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large']],
    ['amk' => 'AMK 1506', 'name' => 'Caller Model',    'products' => ['Laundry Hamper', 'Waste Bin', 'Coaster', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Remote Holder', 'Belt']],
    ['amk' => 'AMK 1507', 'name' => 'Flower Model',    'products' => ['Laundry Hamper', 'Waste Bin', 'Basket', 'Tissue Box']],
    ['amk' => 'AMK 1508', 'name' => '',                'products' => ['Laundry Hamper', 'Waste Bin', 'Coaster', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large']],
    ['amk' => 'AMK 1509', 'name' => 'Modern',          'products' => ['Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Remote Holder']],
    ['amk' => 'AMK 1510', 'name' => '',                'products' => ['Laundry Hamper', 'Waste Bin', 'Coaster', 'Tissue Box']],
    ['amk' => 'AMK 1511', 'name' => 'Double Stud',     'products' => ['Tissue Box', 'Laundry Hamper', 'Waste Bin', 'Coaster', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Remote Holder']],
    ['amk' => 'AMK 1512', 'name' => 'Cane',            'products' => ['Tissue Box', 'Laundry Hamper', 'Waste Bin', 'Coaster', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Remote Holder']],
    ['amk' => 'AMK 1513', 'name' => 'Weave',           'products' => ['Tissue Box', 'Laundry Hamper', 'Waste Bin', 'Coaster', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Remote Holder']],
    ['amk' => '',          'name' => 'Caller Stud',     'products' => ['Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket', 'Remote Holder']],
    ['amk' => 'AMK 1514', 'name' => 'Double Stud',     'products' => ['Laundry Hamper', 'Waste Bin', 'Tissue Box', 'Magazine Holder', 'Towel Tray', 'Tray Small', 'Tray Large', 'Basket']],
];
?>

<section class="fs-showcase" aria-labelledby="fs-heading">
    <div class="fs-container">
        <div class="fs-layout">

            <!-- ─── Left: Hero / Model Image ─── -->
            <div class="fs-hero">
                <div class="fs-hero-img">
                    <img
                        src="<?php echo $hero_img; ?>"
                        alt="Amadika home decor collection"
                        loading="lazy"
                        decoding="async">
                </div>
                <div class="fs-hero-overlay"></div>
                <div class="fs-hero-body">
                    <span class="fs-hero-sub">Amadika</span>
                    <h2 id="fs-heading" class="fs-hero-title">Home Decor<br>Collections</h2>
                    <p class="fs-hero-desc"><?php echo count($collections); ?> models · Crafted for every space</p>
                    <a href="#" class="fs-hero-btn" aria-label="Browse all collections">
                        Browse All
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
            </div>

            <!-- ─── Right: Collection Cards ─── -->
            <div class="fs-right">
                <div class="fs-right-header">
                    <h3 class="fs-right-title">All Models</h3>
                    <span class="fs-right-count"><?php echo count($collections); ?> items</span>
                </div>
                <div class="fs-grid" role="list">
                    <?php foreach ($collections as $c): ?>
                    <a href="#" class="fs-card" role="listitem" aria-label="<?php echo trim($c['amk'] . ' ' . $c['name']); ?>">
                        <span class="fs-card-top">
                            <span class="fs-card-amk"><?php echo $c['amk'] ?: '—'; ?></span>
                            <?php if ($c['name']): ?>
                            <span class="fs-card-model"><?php echo $c['name']; ?></span>
                            <?php endif; ?>
                        </span>
                        <span class="fs-card-tags">
                            <?php foreach (array_slice($c['products'], 0, 4) as $prod): ?>
                            <span class="fs-tag"><?php echo $prod; ?></span>
                            <?php endforeach; ?>
                            <?php if (count($c['products']) > 4): ?>
                            <span class="fs-tag fs-tag--more">+<?php echo count($c['products']) - 4; ?></span>
                            <?php endif; ?>
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

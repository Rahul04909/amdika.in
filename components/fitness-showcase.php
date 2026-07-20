<link rel="stylesheet" href="<?php echo $assets_path; ?>css/fitness-showcase.css">

<?php
$products = [
    ['name' => 'Premium Yoga Mat',        'price' => '$49.99',  'badge' => 'New'],
    ['name' => 'Adjustable Dumbbells',    'price' => '$129.99', 'badge' => 'Sale'],
    ['name' => 'Resistance Bands Set',    'price' => '$24.99',  'badge' => ''],
    ['name' => 'Pro Gym Bag',             'price' => '$59.99',  'badge' => ''],
    ['name' => 'Wireless Sport Earbuds',  'price' => '$79.99',  'badge' => 'New'],
    ['name' => 'Insulated Shaker Bottle', 'price' => '$19.99',  'badge' => ''],
    ['name' => 'Gym Training Gloves',     'price' => '$34.99',  'badge' => 'Sale'],
    ['name' => 'Speed Jump Rope',         'price' => '$14.99',  'badge' => ''],
    ['name' => 'Compression Tights',      'price' => '$44.99',  'badge' => ''],
    ['name' => 'Fitness Tracker Watch',   'price' => '$199.99', 'badge' => 'New'],
];
$hero_img = $assets_path . 'images/collection/collection-1.jpeg';
?>

<section class="fs-showcase" aria-labelledby="fs-heading">

    <div class="fs-container">
        <div class="fs-layout">

            <!-- ─── Left: Large Hero / Model Image ─── -->
            <div class="fs-hero">
                <div class="fs-hero-img">
                    <img
                        src="<?php echo $hero_img; ?>"
                        alt="Fitness model showcasing activewear collection"
                        loading="lazy"
                        decoding="async">
                    <div class="fs-hero-overlay"></div>
                </div>
                <div class="fs-hero-body">
                    <span class="fs-hero-sub">NEW ARRIVALS</span>
                    <h2 id="fs-heading" class="fs-hero-title">Fitness Collection</h2>
                    <p class="fs-hero-desc">Elevate every rep, set &amp; stride with gear engineered to perform.</p>
                    <a href="#" class="fs-hero-btn" aria-label="Explore the full collection">
                        Explore Collection
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
            </div>

            <!-- ─── Right: Product Cards Grid ─── -->
            <div class="fs-right">

                <div class="fs-right-header">
                    <h3 class="fs-right-title">Shop Top Picks</h3>
                    <a href="#" class="fs-right-link">View All &rarr;</a>
                </div>

                <div class="fs-grid" role="list">

                    <?php foreach ($products as $p): ?>
                    <a href="#" class="fs-card" role="listitem" aria-label="<?php echo $p['name']; ?>">
                        <span class="fs-card-img">
                            <img
                                src="https://picsum.photos/seed/<?php echo urlencode(strtolower(str_replace(' ', '-', $p['name']))); ?>/300/300"
                                alt="<?php echo $p['name']; ?>"
                                loading="lazy"
                                decoding="async">
                        </span>
                        <?php if ($p['badge']): ?>
                            <span class="fs-badge fs-badge--<?php echo strtolower($p['badge']); ?>"><?php echo $p['badge']; ?></span>
                        <?php endif; ?>
                        <span class="fs-card-body">
                            <span class="fs-card-name"><?php echo $p['name']; ?></span>
                            <span class="fs-card-price"><?php echo $p['price']; ?></span>
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
    grid.style.cursor = 'grab';

    grid.addEventListener('mousedown', function(e) {
        isDown = true;
        startX = e.pageX - grid.offsetLeft;
        scrollLeft = grid.scrollLeft;
        grid.style.cursor = 'grabbing';
        grid.style.userSelect = 'none';
    });

    grid.addEventListener('mouseleave', function() {
        isDown = false;
        grid.style.cursor = 'grab';
        grid.style.userSelect = '';
    });

    grid.addEventListener('mouseup', function() {
        isDown = false;
        grid.style.cursor = 'grab';
        grid.style.userSelect = '';
    });

    grid.addEventListener('mousemove', function(e) {
        if (!isDown) return;
        e.preventDefault();
        var x = e.pageX - grid.offsetLeft;
        grid.scrollLeft = scrollLeft - (x - startX) * 1.5;
    });
});
</script>

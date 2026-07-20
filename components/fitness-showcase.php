<link rel="stylesheet" href="<?php echo $assets_path; ?>css/fitness-showcase.css">

<?php
$hero_img = $assets_path . 'images/collection/collection-1.jpeg';

$all_models = [
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

$per_page = 4;

foreach ($all_models as $midx => $m):
    $products = $m['products'];
    $total = count($products);
    $pages = max(1, ceil($total / $per_page));
    $model_label = trim($m['amk'] ? $m['amk'] . ' · ' . $m['name'] : $m['name']);
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
                    <p class="fs-hero-desc"><?php echo $m['amk']; ?> · <?php echo $total; ?> products</p>
                    <a href="#" class="fs-hero-btn" aria-label="View <?php echo $m['name']; ?>">
                        View Collection
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
                            <?php foreach ($chunk as $prod): ?>
                            <a href="#" class="fs-card" aria-label="<?php echo $prod; ?>">
                                <span class="fs-card-img">
                                    <img
                                        src="https://picsum.photos/seed/<?php echo urlencode(strtolower(str_replace([' ', '(', ')'], '-', $prod))); ?>/300/300"
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

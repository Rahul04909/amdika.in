<link rel="stylesheet" href="<?php echo $assets_path; ?>css/fitness-showcase.css">

<section class="fs-showcase" aria-labelledby="fs-heading">
    <h2 id="fs-heading" class="fs-heading">Fitness Collection</h2>
    <div class="fs-container">
        <div class="fs-layout">

            <!-- Left: Large Promotional Image -->
            <a href="#" class="fs-image-link" aria-label="Explore fitness collection">
                <div class="fs-image-wrap">
                    <img
                        src="../assets/images/collection/collection-1.jpeg"
                        alt="Fitness Collection Banner"
                        loading="lazy"
                        decoding="async"
                        class="fs-image">
                </div>
            </a>

            <!-- Right: Cards & CTA -->
            <div class="fs-right">

                <div class="fs-track">

                    <!-- Card 1 -->
                    <a href="#" class="fs-card" aria-label="Sporty Tracks">
                        <div class="fs-card-img">
                            <img
                                src="https://adn-static1.nykaa.com/nykdesignstudio-images/pub/media/catalog/product/b/4/b4e747cAW24HSSB2715_2.jpg?rnd=20200526195200&tr=w-512"
                                alt="Sporty Tracks"
                                loading="lazy"
                                decoding="async">
                        </div>
                        <div class="fs-card-overlay"></div>
                        <h3 class="fs-card-title">Sporty Tracks</h3>
                    </a>

                    <!-- Card 2 -->
                    <a href="#" class="fs-card" aria-label="Athletic Shoes">
                        <div class="fs-card-img">
                            <img
                                src="https://adn-static1.nykaa.com/nykdesignstudio-images/pub/media/catalog/product/b/4/b4e747cAW24HSSB2715_2.jpg?rnd=20200526195200&tr=w-512"
                                alt="Athletic Shoes"
                                loading="lazy"
                                decoding="async">
                        </div>
                        <div class="fs-card-overlay"></div>
                        <h3 class="fs-card-title">Athletic Shoes</h3>
                    </a>

                    <!-- Card 3 -->
                    <a href="#" class="fs-card" aria-label="Accessories and Equipment">
                        <div class="fs-card-img">
                            <img
                                src="https://adn-static1.nykaa.com/nykdesignstudio-images/pub/media/catalog/product/b/4/b4e747cAW24HSSB2715_2.jpg?rnd=20200526195200&tr=w-512"
                                alt="Accessories and Equipment"
                                loading="lazy"
                                decoding="async">
                        </div>
                        <div class="fs-card-overlay"></div>
                        <h3 class="fs-card-title">Accessories &amp; Equipment</h3>
                    </a>

                    <!-- Card 4 -->
                    <a href="#" class="fs-card" aria-label="Workout T shirts">
                        <div class="fs-card-img">
                            <img
                                src="https://adn-static1.nykaa.com/nykdesignstudio-images/pub/media/catalog/product/b/4/b4e747cAW24HSSB2715_2.jpg?rnd=20200526195200&tr=w-512"
                                alt="Workout T shirts"
                                loading="lazy"
                                decoding="async">
                        </div>
                        <div class="fs-card-overlay"></div>
                        <h3 class="fs-card-title">Workout T-shirts</h3>
                    </a>

                </div>

                <!-- CTA Block -->
                <div class="fs-cta">
                    <div class="fs-cta-content">
                        <h3 class="fs-cta-title">One-stop destination fitness needs!</h3>
                        <a href="#" class="fs-cta-btn" aria-label="Shop Now">Shop Now &rarr;</a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var track = document.querySelector('.fs-track');
    if (!track) return;

    var isDown = false;
    var startX = 0;
    var scrollLeft = 0;

    track.addEventListener('mousedown', function(e) {
        isDown = true;
        startX = e.pageX - track.offsetLeft;
        scrollLeft = track.scrollLeft;
        track.style.cursor = 'grabbing';
    });

    track.addEventListener('mouseleave', function() {
        isDown = false;
        track.style.cursor = 'grab';
    });

    track.addEventListener('mouseup', function() {
        isDown = false;
        track.style.cursor = 'grab';
    });

    track.addEventListener('mousemove', function(e) {
        if (!isDown) return;
        e.preventDefault();
        var x = e.pageX - track.offsetLeft;
        var walk = (x - startX) * 1.5;
        track.scrollLeft = scrollLeft - walk;
    });
});
</script>

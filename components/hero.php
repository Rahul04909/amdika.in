<?php require_once __DIR__ . '/../includes/image_helper.php'; ?>
<style>
    /* --- Clean Luxury Hero Carousel (Pure Full-Width Slides) --- */
    .hero-section {
        position: relative;
        overflow: hidden;
        margin-top: 0;
        width: 100%;
        max-width: 100%;
        background-color: #12100e;
    }

    .hero-carousel {
        width: 100%;
        position: relative;
    }

    .hero-carousel .carousel-inner {
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    .hero-carousel .carousel-item {
        position: relative;
        background-color: #12100e;
        width: 100%;
    }

    .hero-carousel .carousel-item a {
        display: block;
        width: 100%;
        line-height: 0;
        text-decoration: none;
    }

    .hero-carousel .carousel-item picture {
        display: block;
        width: 100%;
        line-height: 0;
    }

    .hero-carousel .carousel-item img {
        width: 100%;
        max-width: 100%;
        height: auto;
        display: block;
        transition: transform 6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .hero-carousel .carousel-item.active img {
        transform: scale(1);
    }

    /* Controls */
    .hero-carousel .carousel-control-prev,
    .hero-carousel .carousel-control-next {
        width: 48px;
        height: 48px;
        background: rgba(18, 14, 12, 0.45);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        margin: 0 24px;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 5;
    }

    .hero-section:hover .carousel-control-prev,
    .hero-section:hover .carousel-control-next {
        opacity: 1;
    }
    
    .hero-carousel .carousel-control-prev:hover,
    .hero-carousel .carousel-control-next:hover {
        background: #c59b27;
        border-color: #c59b27;
        color: #1a1614;
    }

    /* Indicators */
    .hero-carousel .carousel-indicators {
        bottom: 24px;
        margin-bottom: 0;
        z-index: 5;
        gap: 6px;
    }

    .hero-carousel .carousel-indicators [data-bs-target] {
        width: 28px;
        height: 3px;
        border-radius: 2px;
        background-color: rgba(255, 255, 255, 0.45);
        border: none;
        transition: all 0.3s ease;
        margin: 0;
    }

    .hero-carousel .carousel-indicators .active {
        width: 48px;
        background-color: #c59b27;
        opacity: 1;
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .hero-carousel .carousel-control-prev,
        .hero-carousel .carousel-control-next {
            display: none;
        }

        .hero-carousel .carousel-indicators {
            bottom: 12px;
            gap: 4px;
        }

        .hero-carousel .carousel-indicators [data-bs-target] {
            width: 18px;
            height: 2.5px;
        }

        .hero-carousel .carousel-indicators .active {
            width: 32px;
        }
    }
</style>

<section class="hero-section mb-0">
    <div id="heroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" data-bs-touch="true">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active" data-bs-interval="5000">
                <?php
                $heroSrc1 = 'assets/images/hero/luxary-hero-banner-1.jpg';
                $desktopHero1 = (isset($link_prefix) ? $link_prefix : '') . $heroSrc1;
                $mobileHero1 = $desktopHero1;
                ?>
                <a href="products.php" title="Amadika Premium Leather Collection">
                    <picture>
                        <source media="(max-width: 768px)" srcset="<?php echo $mobileHero1; ?>">
                        <img src="<?php echo $desktopHero1; ?>" alt="Amadika Premium Leather Collection" width="1024" height="572" fetchpriority="high" loading="eager">
                    </picture>
                </a>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item" data-bs-interval="5000">
                <?php
                $heroSrc2 = 'assets/images/hero/banner-2.png';
                $desktopHero2 = (isset($link_prefix) ? $link_prefix : '') . $heroSrc2;
                $mobileHero2 = $desktopHero2;
                ?>
                <a href="products.php" title="Amadika Executive Leather Collection">
                    <picture>
                        <source media="(max-width: 768px)" srcset="<?php echo $mobileHero2; ?>">
                        <img src="<?php echo $desktopHero2; ?>" alt="Amadika Executive Leather Collection" loading="lazy">
                    </picture>
                </a>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item" data-bs-interval="5000">
                <?php
                $heroSrc3 = 'assets/images/hero/banner-3.png';
                $desktopHero3 = (isset($link_prefix) ? $link_prefix : '') . $heroSrc3;
                $mobileHero3 = $desktopHero3;
                ?>
                <a href="products.php" title="Amadika Handcrafted Leather Store">
                    <picture>
                        <source media="(max-width: 768px)" srcset="<?php echo $mobileHero3; ?>">
                        <img src="<?php echo $desktopHero3; ?>" alt="Amadika Handcrafted Leather Store" loading="lazy">
                    </picture>
                </a>
            </div>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Previous">
            <i class="fa-solid fa-chevron-left" style="font-size: 15px;"></i>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Next">
            <i class="fa-solid fa-chevron-right" style="font-size: 15px;"></i>
        </button>
    </div>
</section>
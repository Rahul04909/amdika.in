<?php require_once __DIR__ . '/../includes/image_helper.php'; ?>
<style>
    /* --- Modern Hero Carousel --- */
    .hero-section {
        position: relative;
        overflow: hidden;
        margin-top: 0;
    }

    .carousel-item {
        background-color: #f8f9fa;
        max-height: 650px;
    }

    .carousel-item img {
        width: 100%;
        height: auto;
        max-height: 650px;
        display: block;
        object-fit: cover;
        object-position: center top;
    }

    /* Custom Navigation */
    .hero-carousel .carousel-control-prev,
    .hero-carousel .carousel-control-next {
        width: 50px;
        height: 50px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        margin: 0 20px;
        opacity: 0;
        transition: all 0.3s;
    }

    .hero-section:hover .carousel-control-prev,
    .hero-section:hover .carousel-control-next {
        opacity: 1;
    }

    .carousel-indicators [data-bs-target] {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #fff;
        opacity: 0.5;
        margin: 0 5px;
    }

    .carousel-indicators .active {
        opacity: 1;
        background-color: var(--accent-gold, #d4a017);
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .carousel-item {
            max-height: none;
            min-height: 200px;
        }

        .carousel-item img {
            object-fit: contain;
            height: auto;
            max-height: none;
        }

        .hero-carousel .carousel-control-prev,
        .hero-carousel .carousel-control-next {
            display: none;
        }
    }
</style>

<section class="hero-section mb-3">
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active" data-bs-interval="5000">
                <?php
                $heroSrc1 = 'assets/images/hero/new-hero.png';
                $desktopHero1 = get_resized_image($heroSrc1, 1920, 700);
                $mobileHero1 = get_resized_image($heroSrc1, 800, 450, 'contain');
                ?>
                <picture>
                    <source media="(max-width: 768px)" srcset="<?php echo $mobileHero1; ?>">
                    <img src="<?php echo $desktopHero1; ?>" alt="Amadika Premium Collection">
                </picture>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item" data-bs-interval="5000">
                <?php
                $heroSrc2 = 'assets/images/hero/banner-2.png';
                $desktopHero2 = get_resized_image($heroSrc2, 1920, 700);
                $mobileHero2 = get_resized_image($heroSrc2, 800, 450, 'contain');
                ?>
                <picture>
                    <source media="(max-width: 768px)" srcset="<?php echo $mobileHero2; ?>">
                    <img src="<?php echo $desktopHero2; ?>" alt="Luxury Storefront">
                </picture>
            </div>
            <!-- Slide 3 -->
            <div class="carousel-item" data-bs-interval="5000">
                <?php
                $heroSrc3 = 'assets/images/hero/banner-3.png';
                $desktopHero3 = get_resized_image($heroSrc3, 1920, 700);
                $mobileHero3 = get_resized_image($heroSrc3, 800, 450, 'contain');
                ?>
                <picture>
                    <source media="(max-width: 768px)" srcset="<?php echo $mobileHero3; ?>">
                    <img src="<?php echo $desktopHero3; ?>" alt="Luxury Storefront">
                </picture>
            </div>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>
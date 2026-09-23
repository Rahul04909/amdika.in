<?php require_once __DIR__ . '/../includes/image_helper.php'; ?>
<style>
    /* --- Hidesign Inspired Editorial Hero Carousel --- */
    .hero-section {
        position: relative;
        overflow: hidden;
        margin-top: 0;
        background-color: #12100e;
    }

    .hero-carousel .carousel-item {
        position: relative;
        height: 640px;
        background-color: #12100e;
    }

    .hero-carousel .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        transform: scale(1.02);
        transition: transform 7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .hero-carousel .carousel-item.active img {
        transform: scale(1);
    }

    /* Editorial Scrim Overlay */
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            90deg,
            rgba(18, 14, 12, 0.8) 0%,
            rgba(18, 14, 12, 0.45) 45%,
            rgba(18, 14, 12, 0.1) 80%,
            rgba(18, 14, 12, 0.3) 100%
        );
        z-index: 2;
        pointer-events: none;
    }

    /* Editorial Caption */
    .hero-editorial-wrap {
        position: absolute;
        inset: 0;
        z-index: 3;
        display: flex;
        align-items: center;
    }

    .hero-editorial-content {
        max-width: 620px;
        padding: 0 60px;
        color: #ffffff;
    }

    .hero-kicker {
        display: inline-block;
        font-family: 'Outfit', sans-serif;
        font-size: 11.5px;
        font-weight: 500;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: #e5b94c;
        margin-bottom: 14px;
        position: relative;
    }

    .hero-kicker::before {
        content: '';
        display: inline-block;
        width: 24px;
        height: 1px;
        background-color: #e5b94c;
        vertical-align: middle;
        margin-right: 10px;
    }

    .hero-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 46px;
        font-weight: 600;
        line-height: 1.18;
        color: #fcfbf8;
        margin-bottom: 16px;
        letter-spacing: -0.5px;
    }

    .hero-sub {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 300;
        line-height: 1.6;
        color: rgba(252, 251, 248, 0.85);
        margin-bottom: 28px;
        max-width: 500px;
    }

    .hero-cta-group {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .hero-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 13px 30px;
        background-color: #c59b27;
        color: #1a1614 !important;
        font-family: 'Outfit', sans-serif;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 2.2px;
        text-transform: uppercase;
        text-decoration: none !important;
        border-radius: 2px;
        transition: all 0.3s ease;
        border: 1px solid #c59b27;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .hero-cta-btn:hover {
        background-color: #ffffff;
        border-color: #ffffff;
        color: #181513 !important;
        transform: translateY(-2px);
    }

    .hero-cta-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 13px 26px;
        background-color: transparent;
        color: #fcfbf8 !important;
        font-family: 'Outfit', sans-serif;
        font-size: 11.5px;
        font-weight: 500;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-decoration: none !important;
        border-radius: 2px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(4px);
        transition: all 0.3s ease;
    }

    .hero-cta-btn-outline:hover {
        background-color: rgba(255, 255, 255, 0.15);
        border-color: #ffffff;
        color: #ffffff !important;
    }

    /* Custom Navigation */
    .hero-carousel .carousel-control-prev,
    .hero-carousel .carousel-control-next {
        width: 48px;
        height: 48px;
        background: rgba(18, 14, 12, 0.4);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        margin: 0 30px;
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

    .hero-carousel .carousel-indicators {
        bottom: 25px;
        margin-bottom: 0;
        z-index: 5;
    }

    .hero-carousel .carousel-indicators [data-bs-target] {
        width: 30px;
        height: 3px;
        border-radius: 2px;
        background-color: rgba(255, 255, 255, 0.35);
        border: none;
        transition: all 0.3s ease;
        margin: 0 5px;
    }

    .hero-carousel .carousel-indicators .active {
        width: 50px;
        background-color: #c59b27;
        opacity: 1;
    }

    /* Mobile Optimizations */
    @media (max-width: 991px) {
        .hero-carousel .carousel-item {
            height: 520px;
        }
        .hero-editorial-content {
            padding: 0 35px;
            max-width: 520px;
        }
        .hero-title {
            font-size: 36px;
        }
    }

    @media (max-width: 768px) {
        .hero-carousel .carousel-item {
            height: 460px;
        }
        .hero-overlay {
            background: linear-gradient(
                180deg,
                rgba(18, 14, 12, 0.3) 0%,
                rgba(18, 14, 12, 0.75) 55%,
                rgba(18, 14, 12, 0.92) 100%
            );
        }
        .hero-editorial-wrap {
            align-items: flex-end;
            padding-bottom: 50px;
        }
        .hero-editorial-content {
            padding: 0 20px;
            max-width: 100%;
            text-align: left;
        }
        .hero-kicker {
            font-size: 10px;
            letter-spacing: 2.5px;
            margin-bottom: 8px;
        }
        .hero-title {
            font-size: 26px;
            line-height: 1.25;
            margin-bottom: 10px;
        }
        .hero-sub {
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 18px;
            max-width: 100%;
        }
        .hero-cta-btn, .hero-cta-btn-outline {
            padding: 11px 20px;
            font-size: 10.5px;
            letter-spacing: 1.5px;
        }
        .hero-carousel .carousel-control-prev,
        .hero-carousel .carousel-control-next {
            display: none;
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
            <div class="carousel-item active" data-bs-interval="6000">
                <?php
                $heroSrc1 = 'assets/images/hero/new-hero.png';
                $desktopHero1 = get_resized_image($heroSrc1, 1920, 800, 'cover');
                $mobileHero1 = get_resized_image($heroSrc1, 900, 650, 'cover');
                ?>
                <picture>
                    <source media="(max-width: 768px)" srcset="<?php echo $mobileHero1; ?>">
                    <img src="<?php echo $desktopHero1; ?>" alt="Amadika Vegetable Tanned Leather Goods">
                </picture>
                <div class="hero-overlay"></div>
                <div class="hero-editorial-wrap">
                    <div class="container-fluid px-md-5">
                        <div class="hero-editorial-content">
                            <span class="hero-kicker">HANDCRAFTED HERITAGE</span>
                            <h1 class="hero-title">The Art of Pure Vegetable-Tanned Leather</h1>
                            <p class="hero-sub">Sculpted by master craftsmen with solid sand-cast brass and timeless silhouettes made to develop a rich, personal patina.</p>
                            <div class="hero-cta-group">
                                <a href="products.php" class="hero-cta-btn">
                                    <span>Explore Collection</span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                                <a href="#artisanalStory" class="hero-cta-btn-outline">
                                    <span>Our Philosophy</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item" data-bs-interval="6000">
                <?php
                $heroSrc2 = 'assets/images/hero/banner-2.png';
                $desktopHero2 = get_resized_image($heroSrc2, 1920, 800, 'cover');
                $mobileHero2 = get_resized_image($heroSrc2, 900, 650, 'cover');
                ?>
                <picture>
                    <source media="(max-width: 768px)" srcset="<?php echo $mobileHero2; ?>">
                    <img src="<?php echo $desktopHero2; ?>" alt="Amadika Executive Desk Collection">
                </picture>
                <div class="hero-overlay"></div>
                <div class="hero-editorial-wrap">
                    <div class="container-fluid px-md-5">
                        <div class="hero-editorial-content">
                            <span class="hero-kicker">EXECUTIVE LIVING</span>
                            <h2 class="hero-title">The Modern Desk &amp; Office Sanctuary</h2>
                            <p class="hero-sub">Transform your work environment with handcrafted organizers, desk pads, and bespoke valet trays in full-grain leather.</p>
                            <div class="hero-cta-group">
                                <a href="products.php?category=desk-organizers" class="hero-cta-btn">
                                    <span>Shop Work Edit</span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                                <a href="products.php" class="hero-cta-btn-outline">
                                    <span>All Products</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item" data-bs-interval="6000">
                <?php
                $heroSrc3 = 'assets/images/hero/banner-3.png';
                $desktopHero3 = get_resized_image($heroSrc3, 1920, 800, 'cover');
                $mobileHero3 = get_resized_image($heroSrc3, 900, 650, 'cover');
                ?>
                <picture>
                    <source media="(max-width: 768px)" srcset="<?php echo $mobileHero3; ?>">
                    <img src="<?php echo $desktopHero3; ?>" alt="Amadika Bespoke Gifting">
                </picture>
                <div class="hero-overlay"></div>
                <div class="hero-editorial-wrap">
                    <div class="container-fluid px-md-5">
                        <div class="hero-editorial-content">
                            <span class="hero-kicker">HEIRLOOM LUXURY</span>
                            <h2 class="hero-title">Bespoke Corporate &amp; Personal Gifting</h2>
                            <p class="hero-sub">Distinctive gift boxes and monogrammed pieces created to make an unforgettable impression.</p>
                            <div class="hero-cta-group">
                                <a href="products.php" class="hero-cta-btn">
                                    <span>Explore Gifting</span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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
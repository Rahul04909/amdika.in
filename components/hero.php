<?php require_once __DIR__ . '/../includes/image_helper.php'; ?>
<style>
    /* --- Hero Section --- */
    .hero-section {
        position: relative;
        overflow: hidden;
        margin-top: 0;
    }

    .hero-banner-item {
        width: 100%;
        background-color: #f8f9fa;
        position: relative;
        overflow: hidden;
        height: auto;
        max-height: 520px; /* Increased for better visibility */
    }

    .hero-banner-item img {
        width: 100%;
        height: auto;
        max-height: 520px;
        display: block;
        object-fit: cover;
        object-position: center;
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .hero-banner-item {
            height: auto;
            max-height: 400px;
        }

        .hero-banner-item img {
            max-height: 400px;
        }

        .hero-section {
            margin-bottom: 1rem;
        }
    }
</style>

<section class="hero-section mb-3">
    <div class="hero-banner-item">
        <?php
        $heroSrc = 'assets/images/hero/new-hero.png';
        $desktopHero = get_resized_image($heroSrc, 1920, 550); // Increased height for resizing
        $mobileHero = get_resized_image($heroSrc, 800, 600);   
        ?>
        <picture>
            <source media="(max-width: 768px)" srcset="<?php echo $mobileHero; ?>">
            <img src="<?php echo $desktopHero; ?>" alt="Hero Banner">
        </picture>
    </div>
</section>
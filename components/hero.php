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
        max-height: 650px; /* Increased for desktop */
    }

    .hero-banner-item img {
        width: 100%;
        height: auto;
        max-height: 650px;
        display: block;
        object-fit: cover;
        object-position: center top;
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .hero-banner-item {
            height: 300px; /* Fixed professional height for mobile */
            max-height: none;
        }

        .hero-banner-item img {
            height: 100%;
            object-fit: cover; /* Cover for a clean, non-distorted look */
            object-position: center top;
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
        $desktopHero = get_resized_image($heroSrc, 1920, 700); 
        $mobileHero = get_resized_image($heroSrc, 800, 400, 'cover');   
        ?>
        <picture>
            <source media="(max-width: 768px)" srcset="<?php echo $mobileHero; ?>">
            <img src="<?php echo $desktopHero; ?>" alt="Hero Banner">
        </picture>
    </div>
</section>
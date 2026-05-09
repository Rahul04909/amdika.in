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
        background-color: #f0f0f0;
        position: relative;
        overflow: hidden;
        height: 420px;
        /* Consistent height for desktop */
    }

    .hero-banner-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 20%;
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .hero-banner-item {
            height: 350px;
            /* Slightly taller for mobile to show more vertical content */
        }

        .hero-banner-item img {
            object-fit: cover;
        }

        .hero-section {
            margin-bottom: 1rem;
        }
    }
</style>

<section class="hero-section mb-3">
    <div class="hero-banner-item">
        <?php
        $heroSrc = 'assets/images/hero/hero-1.png';
        $desktopHero = get_resized_image($heroSrc, 1920, 420);
        $mobileHero = get_resized_image($heroSrc, 800, 500);
        ?>
        <picture>
            <source media="(max-width: 768px)" srcset="<?php echo $mobileHero; ?>">
            <img src="<?php echo $desktopHero; ?>" alt="Hero Banner">
        </picture>
    </div>
</section>
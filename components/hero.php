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
        height: auto; /* Allow image to define height */
        max-height: 450px; /* Cap desktop height */
    }

    .hero-banner-item img {
        width: 100%;
        height: auto;
        max-height: 450px;
        display: block;
        object-fit: cover; /* Cover ensures no gaps, but works with auto-height to minimize cropping */
        object-position: center;
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .hero-banner-item {
            height: auto;
            max-height: 350px;
        }

        .hero-banner-item img {
            max-height: 350px;
        }

        .hero-section {
            margin-bottom: 1rem;
        }
    }
</style>

<section class="hero-section mb-3">
    <div class="hero-banner-item">
        <?php 
            $heroSrc = 'assets/images/hero/hero-amadika.png';
            $desktopHero = get_resized_image($heroSrc, 1920, 500); // 1920x500 for better wide coverage
            $mobileHero = get_resized_image($heroSrc, 800, 600);   // 800x600 for mobile
        ?>
        <picture>
            <source media="(max-width: 768px)" srcset="<?php echo $mobileHero; ?>">
            <img src="<?php echo $desktopHero; ?>" alt="Hero Banner">
        </picture>
    </div>
</section>
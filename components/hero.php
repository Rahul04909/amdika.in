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
    }

    .hero-banner-item img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: contain; /* Ensure full image is visible */
    }

    /* Mobile Optimizations - Adjusting spacing if needed */
    @media (max-width: 991px) {
        .hero-section {
            margin-bottom: 1rem;
        }
    }
</style>

<section class="hero-section mb-3">
    <div class="hero-banner-item">
        <img src="assets/images/hero/hero-banner-amadika.png" alt="Hero Banner">
    </div>
</section>
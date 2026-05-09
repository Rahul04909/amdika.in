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
        height: 420px; /* Balanced height for desktop */
    }

    .hero-banner-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 20%; /* Adjusted to better fit banner content */
    }

    /* Mobile: Keep the auto-fit behavior since it looks good on mobile */
    @media (max-width: 768px) {
        .hero-banner-item {
            height: auto;
        }
        .hero-banner-item img {
            height: auto;
            object-fit: contain;
        }
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
<style>
/* --- Hero Section --- */
.hero-section {
    position: relative;
    overflow: hidden;
    margin-top: 0;
}

.hero-banner-item {
    height: 400px; /* Default Desktop Height */
    background-color: #f0f0f0;
    position: relative;
    width: 100%;
}

.hero-banner-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

/* Mobile Optimizations */
@media (max-width: 991px) {
    .hero-banner-item {
        height: 300px;
    }
}

@media (max-width: 576px) {
    .hero-banner-item {
        height: 180px;
    }
}
</style>

<section class="hero-section mb-3">
    <div class="hero-banner-item">
        <img src="assets/images/hero/hero.jpeg" alt="Hero Banner">
    </div>
</section>

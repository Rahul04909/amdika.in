<style>
/* --- Professional Promo Banner (Flipkart Style) --- */
.promo-banner-wrapper {
    background-color: #f1f3f6; /* Subtle grey background like Flipkart */
    padding: 10px 0;
}

.promo-banner-container {
    width: 100%;
    padding: 0 40px;
}

.promo-banner-box {
    display: block;
    width: 100%;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 2px 4px 0 rgba(0,0,0,0.08);
    transition: box-shadow 0.3s;
}

.promo-banner-box:hover {
    box-shadow: 0 4px 12px 0 rgba(0,0,0,0.12);
}

.promo-banner-img {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.5s;
}

.promo-banner-box:hover .promo-banner-img {
    transform: scale(1.01);
}

@media (max-width: 991px) {
    .promo-banner-container {
        padding: 0 10px;
    }
}
</style>

<div class="promo-banner-wrapper">
    <div class="promo-banner-container">
        <a href="products.php" class="promo-banner-box">
            <?php 
                require_once __DIR__ . '/../includes/image_helper.php';
                // Using image helper to ensure optimized loading, though for banners 'cover' or direct is often used
                $banner_path = 'assets/images/banners/bags-banner.png';
            ?>
            <img src="<?php echo $banner_path; ?>" alt="Promotional Banner" class="promo-banner-img">
        </a>
    </div>
</div>

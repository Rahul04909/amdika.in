<style>
/* --- Edge-to-Edge Promo Banner --- */
.promo-banner-wrapper {
    background-color: transparent;
    padding: 0;
    margin: 0;
    width: 100%;
}

.promo-banner-container {
    width: 100%;
    padding: 0;
    margin: 0;
}

.promo-banner-box {
    display: block;
    width: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

.promo-banner-img {
    width: 100%;
    height: auto;
    display: block;
}

@media (max-width: 991px) {
    .promo-banner-container {
        padding: 0;
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
            <img src="<?php echo $link_prefix . $banner_path; ?>" alt="Promotional Banner" class="promo-banner-img">
        </a>
    </div>
</div>

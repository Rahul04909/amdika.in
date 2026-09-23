<style>
/* --- Hidesign Editorial Mid-Page Banner --- */
.promo-banner-wrapper {
    background-color: #ffffff;
    padding: 20px 0 60px;
    margin: 0;
    width: 100%;
}

.promo-banner-container {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 30px;
}

.promo-banner-box {
    display: block;
    width: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
    border-radius: 4px;
    box-shadow: 0 10px 30px rgba(28, 25, 23, 0.08);
    position: relative;
}

.promo-banner-img {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.promo-banner-box:hover .promo-banner-img {
    transform: scale(1.02);
}

@media (max-width: 768px) {
    .promo-banner-wrapper {
        padding: 10px 0 40px;
    }
    .promo-banner-container {
        padding: 0 15px;
    }
}
</style>

<div class="promo-banner-wrapper">
    <div class="promo-banner-container">
        <a href="products.php" class="promo-banner-box">
            <?php 
                require_once __DIR__ . '/../includes/image_helper.php';
                $banner_path = 'assets/images/banners/bags-banner.png';
            ?>
            <img src="<?php echo $link_prefix . $banner_path; ?>" alt="Amadika Handcrafted Leather Goods Collection" class="promo-banner-img" loading="lazy">
        </a>
    </div>
</div>

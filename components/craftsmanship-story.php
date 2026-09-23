<?php
require_once __DIR__ . '/../includes/image_helper.php';
$story_img = 'assets/images/collection/style-1.png';
if (!file_exists(__DIR__ . '/../' . $story_img)) {
    $story_img = 'assets/images/banners/bags-banner.png';
}
$desktop_story = get_resized_image($story_img, 900, 750, 'cover');
$mobile_story = get_resized_image($story_img, 600, 450, 'cover');
?>

<style>
/* =============================================================
   Artisanal Craftsmanship & Philosophy — Hidesign Split Feature
   ============================================================= */

.craftsmanship-section {
    background-color: #161311;
    color: #f7f4ee;
    padding: 90px 0;
    position: relative;
    overflow: hidden;
}

.craftsmanship-container {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 40px;
}

.craftsmanship-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

/* Image Visual */
.craftsmanship-visual {
    position: relative;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
}

.craftsmanship-visual img {
    width: 100%;
    height: 100%;
    min-height: 480px;
    max-height: 560px;
    object-fit: cover;
    display: block;
    transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.craftsmanship-visual:hover img {
    transform: scale(1.04);
}

.craftsmanship-badge-float {
    position: absolute;
    bottom: 24px;
    left: 24px;
    background: rgba(22, 19, 17, 0.85);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(197, 155, 39, 0.4);
    padding: 14px 20px;
    border-radius: 2px;
}

.craftsmanship-badge-float span {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #c59b27;
}

.craftsmanship-badge-float strong {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 16px;
    color: #ffffff;
    font-weight: 600;
}

/* Story Content */
.craftsmanship-content {
    padding: 10px 10px 10px 20px;
}

.craftsmanship-kicker {
    display: inline-block;
    font-family: 'Outfit', sans-serif;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: #c59b27;
    margin-bottom: 12px;
}

.craftsmanship-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 38px;
    font-weight: 600;
    line-height: 1.25;
    color: #fcfbf8;
    margin-bottom: 20px;
}

.craftsmanship-desc {
    font-family: 'Outfit', sans-serif;
    font-size: 15px;
    font-weight: 300;
    line-height: 1.7;
    color: #cfc6ba;
    margin-bottom: 30px;
}

/* Feature Pillars */
.craftsmanship-pillars {
    display: grid;
    grid-template-columns: 1fr;
    gap: 18px;
    margin-bottom: 36px;
}

.craft-pillar-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.craft-pillar-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(197, 155, 39, 0.12);
    border: 1px solid rgba(197, 155, 39, 0.3);
    color: #e5b94c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    margin-top: 2px;
}

.craft-pillar-text h4 {
    font-family: 'Outfit', sans-serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #ffffff;
    margin: 0 0 4px;
}

.craft-pillar-text p {
    font-family: 'Outfit', sans-serif;
    font-size: 13px;
    font-weight: 300;
    color: #a89f91;
    margin: 0;
    line-height: 1.5;
}

.craft-cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 14px 32px;
    background-color: #c59b27;
    color: #1a1614 !important;
    font-family: 'Outfit', sans-serif;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    text-decoration: none !important;
    border-radius: 2px;
    transition: all 0.3s ease;
}

.craft-cta-btn:hover {
    background-color: #ffffff;
    color: #1a1614 !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(255, 255, 255, 0.15);
}

@media (max-width: 991px) {
    .craftsmanship-section {
        padding: 60px 0;
    }
    .craftsmanship-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    .craftsmanship-content {
        padding: 0;
    }
    .craftsmanship-title {
        font-size: 30px;
    }
    .craftsmanship-visual img {
        min-height: 380px;
        max-height: 420px;
    }
}

@media (max-width: 576px) {
    .craftsmanship-container {
        padding: 0 20px;
    }
    .craftsmanship-title {
        font-size: 24px;
    }
    .craftsmanship-desc {
        font-size: 13.5px;
    }
    .craftsmanship-visual img {
        min-height: 280px;
        max-height: 320px;
    }
    .craftsmanship-badge-float {
        bottom: 15px;
        left: 15px;
        padding: 10px 14px;
    }
}
</style>

<section id="artisanalStory" class="craftsmanship-section">
    <div class="craftsmanship-container">
        <div class="craftsmanship-grid">
            <!-- Visual -->
            <div class="craftsmanship-visual">
                <picture>
                    <source media="(max-width: 768px)" srcset="<?php echo $mobile_story; ?>">
                    <img src="<?php echo $desktop_story; ?>" alt="Amadika Vegetable Tanned Leather Craftsmanship" loading="lazy">
                </picture>
                <div class="craftsmanship-badge-float">
                    <span>THE AMADIKA STANDARD</span>
                    <strong>Vegetable Tanned &bull; Solid Brass</strong>
                </div>
            </div>

            <!-- Content -->
            <div class="craftsmanship-content">
                <span class="craftsmanship-kicker">ARTISANAL PHILOSOPHY</span>
                <h2 class="craftsmanship-title">The Art of Pure Vegetable-Tanned Leather</h2>
                <p class="craftsmanship-desc">
                    At Amadika, we honor the ancient heritage of natural vegetable tanning. We use organic tree barks and vegetable tannins to curate full-grain hides that breathe, evolve, and develop an extraordinary personal patina with every passing year. We firmly reject synthetic finishes in favor of pure, tactile luxury.
                </p>

                <div class="craftsmanship-pillars">
                    <div class="craft-pillar-item">
                        <div class="craft-pillar-icon">
                            <i class="fa-solid fa-leaf"></i>
                        </div>
                        <div class="craft-pillar-text">
                            <h4>100% Natural Vegetable Tanning</h4>
                            <p>Naturally treated with organic bark extracts; eco-conscious, skin-friendly, and distinctively fragrant.</p>
                        </div>
                    </div>

                    <div class="craft-pillar-item">
                        <div class="craft-pillar-icon">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <div class="craft-pillar-text">
                            <h4>Solid Sand-Cast Brass Hardware</h4>
                            <p>Every buckle and rivet is individually hand-cast in pure brass—engineered to never rust or peel.</p>
                        </div>
                    </div>

                    <div class="craft-pillar-item">
                        <div class="craft-pillar-icon">
                            <i class="fa-solid fa-hand-sparkles"></i>
                        </div>
                        <div class="craft-pillar-text">
                            <h4>Generational Master Craftsmanship</h4>
                            <p>Cut, skived, and hand-finished by seasoned artisans with decades of inherited leatherwork expertise.</p>
                        </div>
                    </div>
                </div>

                <a href="products.php" class="craft-cta-btn">
                    <span>Explore Handcrafted Collection</span>
                    <i class="fa-solid fa-arrow-right-long"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<?php
/**
 * Collection Banners Component
 * Displays top 6 product categories as full-bleed image cards
 * in a luxury 2-row × 3-column grid — inspired by premium e-commerce layouts.
 *
 * Row 1: 3 wide landscape cards (equal width)
 * Row 2: 3 wide landscape cards (equal width)
 */

if (!isset($conn)) {
    require_once __DIR__ . '/../database/db_config.php';
}
if (!function_exists('get_resized_image')) {
    require_once __DIR__ . '/../includes/image_helper.php';
}

// Fetch up to 6 categories ordered by created_at DESC (most recent / featured first)
$cb_sql  = "SELECT * FROM product_categories WHERE image IS NOT NULL AND image != '' ORDER BY created_at DESC LIMIT 6";
$cb_res  = $conn->query($cb_sql);
$cb_cats = [];
if ($cb_res && $cb_res->num_rows > 0) {
    while ($r = $cb_res->fetch_assoc()) {
        $cb_cats[] = $r;
    }
}

// If fewer than 6 results, pad with NULL so the grid stays balanced
while (count($cb_cats) < 6) {
    $cb_cats[] = null;
}
?>

<style>
/* =========================================================
   Collection Banners — 2-Row Grid
   ========================================================= */
.cb-section {
    background: #f8f6f2;
    padding: 56px 0 64px;
}

/* Section heading */
.cb-heading-wrap {
    text-align: center;
    margin-bottom: 40px;
}
.cb-heading-wrap .cb-eyebrow {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: #C89B2C;
    margin-bottom: 10px;
}
.cb-heading-wrap h2 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(24px, 3vw, 36px);
    font-weight: 700;
    font-style: italic;
    color: #1a1208;
    margin: 0;
    line-height: 1.2;
}
.cb-heading-wrap h2 span {
    color: #C89B2C;
}

/* Outer container */
.cb-grid-container {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Two rows, each containing 3 cards */
.cb-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 16px;
}
.cb-row:last-child {
    margin-bottom: 0;
}

/* Individual card */
.cb-card {
    position: relative;
    display: block;
    overflow: hidden;
    border-radius: 4px;
    text-decoration: none !important;
    cursor: pointer;
    aspect-ratio: 4 / 3;        /* consistent card ratio */
    background: #2a2118;        /* dark fallback */
}

/* Image */
.cb-card__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    will-change: transform;
}
.cb-card:hover .cb-card__img {
    transform: scale(1.07);
}

/* Dark gradient overlay — bottom-heavy so text stays legible */
.cb-card__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to top,
        rgba(10, 8, 5, 0.78) 0%,
        rgba(10, 8, 5, 0.30) 45%,
        rgba(10, 8, 5, 0.04) 100%
    );
    transition: background 0.4s ease;
}
.cb-card:hover .cb-card__overlay {
    background: linear-gradient(
        to top,
        rgba(10, 8, 5, 0.85) 0%,
        rgba(10, 8, 5, 0.40) 50%,
        rgba(10, 8, 5, 0.08) 100%
    );
}

/* Text content anchored to bottom-left */
.cb-card__body {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 28px 28px 30px;
    z-index: 2;
}

.cb-card__title {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: clamp(13px, 1.5vw, 16px);
    font-weight: 700;
    letter-spacing: 3.5px;
    text-transform: uppercase;
    color: #ffffff;
    margin-bottom: 16px;
    line-height: 1;
    text-shadow: 0 1px 8px rgba(0,0,0,0.5);
}

/* "View Collection" pill button */
.cb-card__btn {
    display: inline-block;
    background: rgba(255, 255, 255, 0.94);
    color: #1a1208;
    font-family: 'Outfit', sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    padding: 9px 22px;
    border: none;
    border-radius: 2px;
    text-decoration: none !important;
    transition: background 0.3s ease, color 0.3s ease, transform 0.3s ease;
    transform: translateY(6px);
    opacity: 0.92;
}
.cb-card:hover .cb-card__btn {
    background: #C89B2C;
    color: #fff;
    transform: translateY(0);
    opacity: 1;
}

/* Skeleton / placeholder card when no image */
.cb-card--empty {
    background: linear-gradient(135deg, #2a2118 0%, #3d2f1e 100%);
    cursor: default;
}
.cb-card--empty .cb-card__overlay {
    background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 100%);
}

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 991px) {
    .cb-row {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    .cb-row:last-child {
        grid-template-columns: repeat(2, 1fr); /* keep even on tablet */
    }
    .cb-section {
        padding: 40px 0 48px;
    }
}

@media (max-width: 576px) {
    .cb-row {
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .cb-row:last-child {
        grid-template-columns: 1fr 1fr;
    }
    .cb-card__body {
        padding: 16px 14px 18px;
    }
    .cb-card__title {
        font-size: 10px;
        letter-spacing: 2px;
        margin-bottom: 10px;
    }
    .cb-card__btn {
        font-size: 8px;
        padding: 7px 14px;
        letter-spacing: 1.5px;
    }
    .cb-section {
        padding: 30px 0 36px;
    }
    .cb-grid-container {
        padding: 0 10px;
    }
}
</style>

<section class="cb-section">
    <!-- Section Heading -->
    <div class="cb-heading-wrap">
        <span class="cb-eyebrow">Curated Selections</span>
        <h2>Shop By <span>Collection</span></h2>
    </div>

    <div class="cb-grid-container">

        <!-- ROW 1 — First 3 categories -->
        <div class="cb-row">
            <?php for ($i = 0; $i <= 2; $i++):
                $cat = $cb_cats[$i] ?? null;
                if ($cat):
                    $cat_name  = htmlspecialchars($cat['name']);
                    $cat_slug  = htmlspecialchars($cat['slug']);
                    $img_src   = get_resized_image($cat['image'], 800, 600, 'cover');
                    $href      = $link_prefix . 'products.php?category=' . $cat_slug;
                ?>
                <a href="<?php echo $href; ?>" class="cb-card" aria-label="Shop <?php echo $cat_name; ?>">
                    <img src="<?php echo $img_src; ?>"
                         alt="<?php echo $cat_name; ?>"
                         class="cb-card__img"
                         loading="lazy">
                    <div class="cb-card__overlay"></div>
                    <div class="cb-card__body">
                        <span class="cb-card__title"><?php echo $cat_name; ?></span>
                        <span class="cb-card__btn">View Collection</span>
                    </div>
                </a>
                <?php else: ?>
                <!-- Empty placeholder (fewer than 3 categories) -->
                <div class="cb-card cb-card--empty">
                    <div class="cb-card__overlay"></div>
                    <div class="cb-card__body">
                        <span class="cb-card__title" style="color:rgba(255,255,255,0.3);">Coming Soon</span>
                    </div>
                </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <!-- END ROW 1 -->

        <!-- ROW 2 — Next 3 categories -->
        <div class="cb-row">
            <?php for ($i = 3; $i <= 5; $i++):
                $cat = $cb_cats[$i] ?? null;
                if ($cat):
                    $cat_name  = htmlspecialchars($cat['name']);
                    $cat_slug  = htmlspecialchars($cat['slug']);
                    $img_src   = get_resized_image($cat['image'], 800, 600, 'cover');
                    $href      = $link_prefix . 'products.php?category=' . $cat_slug;
                ?>
                <a href="<?php echo $href; ?>" class="cb-card" aria-label="Shop <?php echo $cat_name; ?>">
                    <img src="<?php echo $img_src; ?>"
                         alt="<?php echo $cat_name; ?>"
                         class="cb-card__img"
                         loading="lazy">
                    <div class="cb-card__overlay"></div>
                    <div class="cb-card__body">
                        <span class="cb-card__title"><?php echo $cat_name; ?></span>
                        <span class="cb-card__btn">View Collection</span>
                    </div>
                </a>
                <?php else: ?>
                <!-- Empty placeholder -->
                <div class="cb-card cb-card--empty">
                    <div class="cb-card__overlay"></div>
                    <div class="cb-card__body">
                        <span class="cb-card__title" style="color:rgba(255,255,255,0.3);">Coming Soon</span>
                    </div>
                </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <!-- END ROW 2 -->

    </div><!-- /.cb-grid-container -->
</section>

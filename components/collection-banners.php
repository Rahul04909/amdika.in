<?php
/**
 * Collection Banners Component — Amadika
 * Full-width stacked banners (banner-1, banner-2, banner-3)
 * Each banner is fully clickable, animated, and mobile-responsive.
 * Images: assets/images/collection/style-1.png (banner-1),
 *         assets/images/collection/banner-2.png (banner-2),
 *         assets/images/collection/banner-3.png (banner-3)
 */

if (!isset($conn)) {
    require_once __DIR__ . '/../database/db_config.php';
}

// Base path for links (works with or without $link_prefix set from parent)
$lp = isset($link_prefix) ? $link_prefix : '';

// Fetch first 3 categories with slugs for banner links
$cb_cats_map = [];
try {
    $cb_sql = "SELECT name, slug FROM product_categories ORDER BY created_at DESC LIMIT 3";
    $cb_res  = $conn->query($cb_sql);
    if ($cb_res && $cb_res->num_rows > 0) {
        while ($r = $cb_res->fetch_assoc()) {
            $cb_cats_map[] = $r;
        }
    }
} catch (Exception $e) {
    // Silently fall back to generic links
}

// Define banner data — static assets + dynamic category links
$banners = [
    [
        'img'      => $lp . 'assets/images/collection/style-1.png',
        'eyebrow'  => 'New Arrivals',
        'title'    => 'The Signature Collection',
        'subtitle' => 'Handcrafted canvas & leather goods for the modern connoisseur',
        'cta'      => 'Explore Now',
        'href'     => !empty($cb_cats_map[0]) ? $lp . 'products.php?category=' . htmlspecialchars($cb_cats_map[0]['slug']) : $lp . 'products.php',
        'align'    => 'left',   // text alignment on desktop
        'overlay'  => 'right',  // overlay gradient direction
        'badge'    => null,
    ],
    [
        'img'      => $lp . 'assets/images/collection/banner-2.png',
        'eyebrow'  => 'Featured',
        'title'    => 'Desk Essentials',
        'subtitle' => 'Elevate your workspace with handcrafted leather stationery & organisers',
        'cta'      => 'Shop The Range',
        'href'     => !empty($cb_cats_map[1]) ? $lp . 'products.php?category=' . htmlspecialchars($cb_cats_map[1]['slug']) : $lp . 'products.php',
        'align'    => 'right',
        'overlay'  => 'left',
        'badge'    => 'Best Seller',
    ],
    [
        'img'      => $lp . 'assets/images/collection/banner-3.png',
        'eyebrow'  => 'Heritage Series',
        'title'    => 'The Trunk Story',
        'subtitle' => 'Timeless trunks & travel cases — built for generations',
        'cta'      => 'Discover More',
        'href'     => !empty($cb_cats_map[2]) ? $lp . 'products.php?category=' . htmlspecialchars($cb_cats_map[2]['slug']) : $lp . 'products.php',
        'align'    => 'center',
        'overlay'  => 'center',
        'badge'    => null,
    ],
];
?>

<style>
/* ================================================================
   AMADIKA — Collection Banners (Full-Width Stacked)
   ================================================================ */
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400;1,600&family=Outfit:wght@300;400;500;600;700&display=swap');

.amb-section {
    background: #FAF8F5;
    padding: 0;
    margin: 0;
}

/* ── Section Header ────────────────────────────────────────────── */
.amb-section-header {
    text-align: center;
    padding: 60px 20px 44px;
    background: #FAF8F5;
}
.amb-section-header .amb-eyebrow-line {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-bottom: 14px;
}
.amb-section-header .amb-eyebrow-line::before,
.amb-section-header .amb-eyebrow-line::after {
    content: '';
    display: block;
    height: 1px;
    width: 60px;
    background: #C89B2C;
    opacity: 0.7;
}
.amb-section-header .amb-eyebrow {
    font-family: 'Outfit', sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: #C89B2C;
}
.amb-section-header h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(30px, 4vw, 52px);
    font-weight: 400;
    font-style: italic;
    color: #1C1410;
    margin: 0 0 12px;
    line-height: 1.15;
    letter-spacing: -0.5px;
}
.amb-section-header h2 em {
    font-style: normal;
    color: #C89B2C;
}
.amb-section-header .amb-section-desc {
    font-family: 'Outfit', sans-serif;
    font-size: 15px;
    font-weight: 300;
    color: #6B5C4A;
    max-width: 480px;
    margin: 0 auto;
    line-height: 1.7;
}

/* ── Banner Stack Wrapper ──────────────────────────────────────── */
.amb-banner-stack {
    display: flex;
    flex-direction: column;
    gap: 0;
}

/* ── Individual Full-Width Banner ──────────────────────────────── */
.amb-banner {
    position: relative;
    display: block;
    width: 100%;
    overflow: hidden;
    text-decoration: none !important;
    cursor: pointer;
    /* Full-width cinematic height */
    height: clamp(340px, 50vw, 620px);
    background: #2a2118;
}
.amb-banner:focus-visible {
    outline: 3px solid #C89B2C;
    outline-offset: 3px;
}

/* ── Banner Image ──────────────────────────────────────────────── */
.amb-banner__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.9s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    will-change: transform;
    display: block;
}
.amb-banner:hover .amb-banner__img {
    transform: scale(1.05);
}

/* ── Gradient Overlays ─────────────────────────────────────────── */
.amb-banner__overlay {
    position: absolute;
    inset: 0;
    pointer-events: none;
    transition: opacity 0.5s ease;
}
/* Left-aligned text → dark gradient from left */
.amb-banner[data-align="left"] .amb-banner__overlay {
    background: linear-gradient(
        100deg,
        rgba(15, 10, 5, 0.82) 0%,
        rgba(15, 10, 5, 0.50) 40%,
        rgba(15, 10, 5, 0.10) 70%,
        rgba(15, 10, 5, 0.00) 100%
    );
}
/* Right-aligned text → dark gradient from right */
.amb-banner[data-align="right"] .amb-banner__overlay {
    background: linear-gradient(
        -100deg,
        rgba(15, 10, 5, 0.82) 0%,
        rgba(15, 10, 5, 0.50) 40%,
        rgba(15, 10, 5, 0.10) 70%,
        rgba(15, 10, 5, 0.00) 100%
    );
}
/* Center-aligned text → dark vignette */
.amb-banner[data-align="center"] .amb-banner__overlay {
    background: radial-gradient(
        ellipse at center,
        rgba(15, 10, 5, 0.00) 20%,
        rgba(15, 10, 5, 0.60) 80%
    ),
    linear-gradient(
        to top,
        rgba(15, 10, 5, 0.75) 0%,
        rgba(15, 10, 5, 0.20) 50%,
        rgba(15, 10, 5, 0.45) 100%
    );
}
.amb-banner:hover .amb-banner__overlay {
    opacity: 0.9;
}

/* ── Content Block ─────────────────────────────────────────────── */
.amb-banner__content {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    z-index: 2;
    padding: 40px clamp(24px, 6vw, 100px);
}
/* Left alignment */
.amb-banner[data-align="left"] .amb-banner__content {
    justify-content: flex-start;
    text-align: left;
}
/* Right alignment */
.amb-banner[data-align="right"] .amb-banner__content {
    justify-content: flex-end;
    text-align: right;
}
/* Center alignment */
.amb-banner[data-align="center"] .amb-banner__content {
    justify-content: center;
    text-align: center;
}

/* ── Text Inner Box ────────────────────────────────────────────── */
.amb-banner__inner {
    max-width: 520px;
    animation-fill-mode: both;
}

/* Eyebrow tag */
.amb-banner__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-family: 'Outfit', sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: #C89B2C;
    margin-bottom: 16px;
}
.amb-banner__eyebrow::before {
    content: '';
    display: block;
    width: 28px;
    height: 1px;
    background: #C89B2C;
}
.amb-banner[data-align="right"] .amb-banner__eyebrow {
    flex-direction: row-reverse;
}
.amb-banner[data-align="center"] .amb-banner__eyebrow::before {
    display: none;
}

/* Banner title */
.amb-banner__title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(28px, 4.5vw, 68px);
    font-weight: 600;
    font-style: italic;
    color: #FFFFFF;
    line-height: 1.1;
    margin: 0 0 16px;
    letter-spacing: -0.5px;
    text-shadow: 0 2px 20px rgba(0,0,0,0.4);
    transition: transform 0.4s ease;
}
.amb-banner:hover .amb-banner__title {
    transform: translateY(-3px);
}

/* Subtitle */
.amb-banner__subtitle {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(12px, 1.4vw, 16px);
    font-weight: 300;
    color: rgba(255, 245, 230, 0.88);
    line-height: 1.65;
    margin: 0 0 28px;
    max-width: 400px;
    text-shadow: 0 1px 8px rgba(0,0,0,0.5);
}
.amb-banner[data-align="right"] .amb-banner__subtitle,
.amb-banner[data-align="center"] .amb-banner__subtitle {
    margin-left: auto;
    margin-right: auto;
}

/* CTA Button */
.amb-banner__cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-family: 'Outfit', sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #1C1410;
    background: rgba(255, 245, 220, 0.96);
    padding: 14px 32px;
    border: none;
    border-radius: 2px;
    text-decoration: none !important;
    transition: background 0.35s ease, color 0.35s ease, gap 0.35s ease, transform 0.35s ease, box-shadow 0.35s ease;
    box-shadow: 0 4px 20px rgba(0,0,0,0.25);
}
.amb-banner__cta .amb-cta-arrow {
    display: inline-block;
    transition: transform 0.35s ease;
    font-style: normal;
}
.amb-banner:hover .amb-banner__cta {
    background: #C89B2C;
    color: #ffffff;
    gap: 14px;
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(200, 155, 44, 0.35);
}
.amb-banner:hover .amb-cta-arrow {
    transform: translateX(4px);
}

/* ── Badge (optional top-corner pill) ────────────────────────── */
.amb-banner__badge {
    position: absolute;
    top: 28px;
    right: 28px;
    background: #C89B2C;
    color: #fff;
    font-family: 'Outfit', sans-serif;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    padding: 6px 16px;
    border-radius: 2px;
    z-index: 3;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

/* ── Banner Number Label ───────────────────────────────────────── */
.amb-banner__number {
    position: absolute;
    bottom: 28px;
    right: 28px;
    font-family: 'Cormorant Garamond', serif;
    font-size: 80px;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.05);
    line-height: 1;
    pointer-events: none;
    user-select: none;
    z-index: 1;
    transition: color 0.5s ease;
}
.amb-banner:hover .amb-banner__number {
    color: rgba(200, 155, 44, 0.08);
}

/* ── Divider between banners ───────────────────────────────────── */
.amb-banner-divider {
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #FAF8F5 0%, #C89B2C 30%, #8B6914 50%, #C89B2C 70%, #FAF8F5 100%);
    opacity: 0.6;
}

/* ── Bottom CTA Strip ──────────────────────────────────────────── */
.amb-bottom-strip {
    background: #1C1410;
    padding: 36px 24px;
    text-align: center;
}
.amb-bottom-strip p {
    font-family: 'Outfit', sans-serif;
    font-size: 13px;
    font-weight: 300;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: rgba(255, 245, 230, 0.6);
    margin: 0 0 20px;
}
.amb-bottom-strip a.amb-shop-all {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    font-family: 'Outfit', sans-serif;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #C89B2C;
    text-decoration: none;
    border-bottom: 1px solid rgba(200, 155, 44, 0.4);
    padding-bottom: 4px;
    transition: gap 0.3s ease, border-color 0.3s ease, color 0.3s ease;
}
.amb-bottom-strip a.amb-shop-all:hover {
    color: #E5B84A;
    gap: 18px;
    border-color: #E5B84A;
}

/* ================================================================
   RESPONSIVE
   ================================================================ */

/* Tablet */
@media (max-width: 991px) {
    .amb-section-header {
        padding: 44px 20px 32px;
    }
    .amb-banner {
        height: clamp(280px, 55vw, 480px);
    }
    .amb-banner__content {
        padding: 30px clamp(20px, 5vw, 60px);
    }
    /* Force left-align on tablet for readability */
    .amb-banner[data-align="right"] .amb-banner__content {
        justify-content: flex-start;
        text-align: left;
    }
    .amb-banner[data-align="right"] .amb-banner__overlay {
        background: linear-gradient(
            100deg,
            rgba(15, 10, 5, 0.82) 0%,
            rgba(15, 10, 5, 0.50) 40%,
            rgba(15, 10, 5, 0.10) 70%,
            rgba(15, 10, 5, 0.00) 100%
        );
    }
    .amb-banner[data-align="right"] .amb-banner__eyebrow {
        flex-direction: row;
    }
    .amb-banner[data-align="right"] .amb-banner__subtitle {
        margin-left: 0;
        margin-right: 0;
    }
}

/* Mobile */
@media (max-width: 576px) {
    .amb-section-header {
        padding: 36px 16px 28px;
    }
    .amb-section-header .amb-eyebrow-line::before,
    .amb-section-header .amb-eyebrow-line::after {
        width: 36px;
    }
    .amb-banner {
        height: clamp(240px, 70vw, 380px);
    }
    .amb-banner__content {
        padding: 20px 20px;
        align-items: flex-end;
        padding-bottom: 30px;
    }
    /* All banners bottom-left on mobile */
    .amb-banner[data-align="left"] .amb-banner__content,
    .amb-banner[data-align="right"] .amb-banner__content,
    .amb-banner[data-align="center"] .amb-banner__content {
        justify-content: flex-start;
        text-align: left;
        align-items: flex-end;
    }
    /* All overlays: bottom-up on mobile */
    .amb-banner[data-align="left"] .amb-banner__overlay,
    .amb-banner[data-align="right"] .amb-banner__overlay,
    .amb-banner[data-align="center"] .amb-banner__overlay {
        background: linear-gradient(
            to top,
            rgba(15, 10, 5, 0.88) 0%,
            rgba(15, 10, 5, 0.45) 40%,
            rgba(15, 10, 5, 0.05) 80%,
            rgba(15, 10, 5, 0.00) 100%
        );
    }
    .amb-banner__eyebrow {
        flex-direction: row !important;
        margin-bottom: 10px;
    }
    .amb-banner__title {
        font-size: clamp(22px, 7vw, 34px);
        margin-bottom: 10px;
    }
    .amb-banner__subtitle {
        display: none; /* hide subtitle on very small screens for clarity */
    }
    .amb-banner__cta {
        font-size: 9px;
        padding: 11px 22px;
        letter-spacing: 2px;
    }
    .amb-banner__badge {
        top: 14px;
        right: 14px;
        font-size: 7px;
        padding: 5px 11px;
    }
    .amb-banner__number {
        font-size: 50px;
        bottom: 14px;
        right: 14px;
    }
    .amb-banner__inner {
        max-width: 100%;
    }
    .amb-bottom-strip {
        padding: 28px 16px;
    }
    .amb-banner[data-align="right"] .amb-banner__subtitle {
        margin-left: 0;
    }
    .amb-banner[data-align="center"] .amb-banner__subtitle {
        margin-left: 0;
    }
}

/* Touch device — remove hover scale for performance */
@media (hover: none) {
    .amb-banner:hover .amb-banner__img {
        transform: scale(1);
    }
    .amb-banner:active .amb-banner__img {
        transform: scale(1.02);
    }
    .amb-banner:active .amb-banner__cta {
        background: #C89B2C;
        color: #fff;
    }
}
</style>

<section class="amb-section" id="collection-banners" aria-label="Shop by Collection">

    <!-- Section Header -->
    <header class="amb-section-header">
        <div class="amb-eyebrow-line">
            <span class="amb-eyebrow">Curated for You</span>
        </div>
        <h2>Shop By <em>Collection</em></h2>
        <p class="amb-section-desc">Explore our thoughtfully curated collections — each piece a testament to timeless craftsmanship.</p>
    </header>

    <!-- Banner Stack -->
    <div class="amb-banner-stack">

        <?php foreach ($banners as $idx => $b): ?>

        <!-- Banner <?php echo $idx + 1; ?> -->
        <a href="<?php echo htmlspecialchars($b['href']); ?>"
           class="amb-banner"
           data-align="<?php echo $b['align']; ?>"
           aria-label="<?php echo htmlspecialchars($b['title']); ?> — <?php echo htmlspecialchars($b['cta']); ?>"
           id="collection-banner-<?php echo $idx + 1; ?>">

            <!-- Product Image -->
            <img src="<?php echo htmlspecialchars($b['img']); ?>"
                 alt="<?php echo htmlspecialchars($b['title']); ?>"
                 class="amb-banner__img"
                 loading="<?php echo $idx === 0 ? 'eager' : 'lazy'; ?>"
                 decoding="async">

            <!-- Directional Overlay -->
            <div class="amb-banner__overlay" aria-hidden="true"></div>

            <?php if (!empty($b['badge'])): ?>
            <!-- Best Seller / Badge -->
            <span class="amb-banner__badge"><?php echo htmlspecialchars($b['badge']); ?></span>
            <?php endif; ?>

            <!-- Big Background Number -->
            <span class="amb-banner__number" aria-hidden="true">0<?php echo $idx + 1; ?></span>

            <!-- Text Content -->
            <div class="amb-banner__content">
                <div class="amb-banner__inner">
                    <span class="amb-banner__eyebrow">
                        <?php echo htmlspecialchars($b['eyebrow']); ?>
                    </span>
                    <h3 class="amb-banner__title">
                        <?php echo htmlspecialchars($b['title']); ?>
                    </h3>
                    <p class="amb-banner__subtitle">
                        <?php echo htmlspecialchars($b['subtitle']); ?>
                    </p>
                    <span class="amb-banner__cta">
                        <?php echo htmlspecialchars($b['cta']); ?>
                        <span class="amb-cta-arrow" aria-hidden="true">→</span>
                    </span>
                </div>
            </div>

        </a><!-- /.amb-banner -->

        <?php if ($idx < count($banners) - 1): ?>
        <!-- Gold Divider -->
        <div class="amb-banner-divider" aria-hidden="true"></div>
        <?php endif; ?>

        <?php endforeach; ?>

    </div><!-- /.amb-banner-stack -->

    <!-- Bottom Shop All CTA -->
    <div class="amb-bottom-strip">
        <p>Discover the full Amadika universe</p>
        <a href="<?php echo $lp; ?>products.php" class="amb-shop-all" aria-label="Shop all products">
            View All Collections
            <span aria-hidden="true">→</span>
        </a>
    </div>

</section>

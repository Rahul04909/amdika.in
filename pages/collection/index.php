<?php
/**
 * Collection Page — Amadika
 * Full-width stacked clickable banners, no overlay text.
 * Fully mobile responsive.
 */
$page_title       = "Collections — Amadika | Premium Leather Goods";
$page_description = "Explore the Amadika Collection — premium handcrafted leather home accessories, desk organisers, trunk boxes and more.";
include '../../includes/header.php';
?>

<style>
/* ============================================================
   COLLECTION PAGE — Full-Width Stacked Banners
   ============================================================ */

/* ── Page hero strip ──────────────────────────────────────── */
.col-page-hero {
    background: #1C1410;
    padding: 42px 20px 36px;
    text-align: center;
    border-bottom: 1px solid rgba(200,155,44,0.18);
}
.col-page-hero .hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    font-family: 'Outfit', sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 5px;
    text-transform: uppercase;
    color: #C89B2C;
    margin-bottom: 14px;
}
.col-page-hero .hero-eyebrow::before,
.col-page-hero .hero-eyebrow::after {
    content: '';
    display: block;
    width: 40px;
    height: 1px;
    background: #C89B2C;
    opacity: 0.6;
}
.col-page-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(28px, 5vw, 52px);
    font-weight: 700;
    font-style: italic;
    color: #F5EDD8;
    margin: 0 0 12px;
    line-height: 1.2;
}
.col-page-hero h1 span {
    color: #C89B2C;
}
.col-page-hero p {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(13px, 1.6vw, 16px);
    font-weight: 300;
    color: rgba(245,237,216,0.65);
    max-width: 500px;
    margin: 0 auto;
    line-height: 1.7;
}

/* ── Breadcrumb ───────────────────────────────────────────── */
.col-breadcrumb {
    background: #FAF8F4;
    border-bottom: 1px solid #EEEBE4;
    padding: 12px 20px;
}
.col-breadcrumb ol {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    max-width: 1320px;
    margin: 0 auto;
}
.col-breadcrumb li {
    font-family: 'Outfit', sans-serif;
    font-size: 11px;
    font-weight: 500;
    color: #9C8A6A;
    letter-spacing: 0.5px;
}
.col-breadcrumb li + li::before {
    content: '/';
    margin-right: 6px;
    color: #C89B2C;
    opacity: 0.6;
}
.col-breadcrumb a {
    color: #6B5C4A;
    text-decoration: none;
    transition: color 0.2s;
}
.col-breadcrumb a:hover { color: #C89B2C; }
.col-breadcrumb li.active { color: #1C1410; font-weight: 600; }

/* ── Banner Stack ─────────────────────────────────────────── */
.col-banner-wrap {
    background: #FAF8F4;
    padding: 40px 0 56px;
}

.col-banner-stack {
    display: flex;
    flex-direction: column;
    gap: 16px;
    max-width: 100%;
    padding: 0 20px;
    margin: 0 auto;
}

/* ── Individual Banner ────────────────────────────────────── */
.col-banner {
    display: block;
    width: 100%;
    overflow: hidden;
    border-radius: 6px;
    text-decoration: none !important;
    cursor: pointer;
    /* Pure image — no text overlay */
    position: relative;
    background: #E8E0D2;
    /* Maintain a cinematic aspect ratio */
    aspect-ratio: 16 / 6;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    transition: box-shadow 0.4s ease, transform 0.4s ease;
}
.col-banner:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,0.14);
    transform: translateY(-3px);
}
.col-banner:focus-visible {
    outline: 3px solid #C89B2C;
    outline-offset: 4px;
}

/* ── Banner Image ─────────────────────────────────────────── */
.col-banner__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    will-change: transform;
}
.col-banner:hover .col-banner__img {
    transform: scale(1.04);
}

/* ── Thin label strip below each banner ─────────────────── */
.col-banner-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 4px 0;
}
.col-banner-label .lbl-name {
    font-family: 'Outfit', sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #4A3B2C;
}
.col-banner-label .lbl-cta {
    font-family: 'Outfit', sans-serif;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #C89B2C;
    text-decoration: none;
    border-bottom: 1px solid rgba(200,155,44,0.4);
    padding-bottom: 2px;
    transition: color 0.25s, border-color 0.25s;
}
.col-banner-label .lbl-cta:hover {
    color: #1C1410;
    border-color: #1C1410;
}

/* ── Bottom strip: Shop All ───────────────────────────────── */
.col-shop-all-strip {
    margin-top: 44px;
    text-align: center;
}
.col-shop-all-strip a.shop-all-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    font-family: 'Outfit', sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 3.5px;
    text-transform: uppercase;
    color: #1C1410;
    background: transparent;
    border: 1.5px solid #1C1410;
    padding: 15px 44px;
    border-radius: 2px;
    text-decoration: none !important;
    transition: background 0.35s ease, color 0.35s ease, border-color 0.35s ease, gap 0.35s ease;
}
.col-shop-all-strip a.shop-all-btn:hover {
    background: #1C1410;
    color: #F5EDD8;
    border-color: #1C1410;
    gap: 18px;
}
.col-shop-all-strip a.shop-all-btn .btn-arrow {
    font-style: normal;
    transition: transform 0.35s ease;
}
.col-shop-all-strip a.shop-all-btn:hover .btn-arrow {
    transform: translateX(4px);
}

/* ============================================================
   RESPONSIVE
   ============================================================ */

/* Tablet */
@media (max-width: 1024px) {
    .col-banner {
        aspect-ratio: 16 / 7;
    }
    .col-banner-stack {
        gap: 14px;
        padding: 0 16px;
    }
}

/* Mobile large */
@media (max-width: 768px) {
    .col-page-hero {
        padding: 32px 16px 28px;
    }
    .col-banner {
        aspect-ratio: 4 / 3;   /* taller on mobile so image is visible */
        border-radius: 4px;
    }
    .col-banner-wrap {
        padding: 28px 0 40px;
    }
    .col-banner-stack {
        gap: 12px;
        padding: 0 12px;
    }
    .col-banner-label {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
        padding-top: 8px;
    }
    .col-shop-all-strip {
        margin-top: 32px;
    }
    .col-shop-all-strip a.shop-all-btn {
        padding: 13px 32px;
        font-size: 10px;
    }
}

/* Mobile small */
@media (max-width: 480px) {
    .col-banner {
        aspect-ratio: 3 / 3.5;
        border-radius: 4px;
    }
    .col-banner:hover {
        transform: none; /* disable lift on small touch screens */
    }
    .col-banner-stack {
        gap: 10px;
        padding: 0 10px;
    }
    .col-breadcrumb { padding: 10px 16px; }
}

/* Touch — disable hover zoom for performance */
@media (hover: none) {
    .col-banner:hover .col-banner__img {
        transform: scale(1);
    }
    .col-banner:active {
        opacity: 0.94;
    }
    .col-banner:hover {
        transform: none;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    }
}
</style>

<!-- Page Hero Strip -->
<section class="col-page-hero">
    <div class="hero-eyebrow">Our Collections</div>
    <h1>The <span>Amadika</span> Collection</h1>
    <p>Handcrafted leather &amp; canvas goods — made to last a lifetime.</p>
</section>

<!-- Breadcrumb -->
<nav class="col-breadcrumb" aria-label="Breadcrumb">
    <ol>
        <li><a href="<?php echo $link_prefix; ?>index.php">Home</a></li>
        <li class="active">Collections</li>
    </ol>
</nav>

<!-- Banner Stack -->
<div class="col-banner-wrap">
    <div class="col-banner-stack">

        <?php
        // Define the 3 banners — all use style-1.png, linked to products
        $col_banners = [
            [
                'label' => 'The Signature Collection',
                'href'  => $link_prefix . 'products.php',
                'alt'   => 'Amadika Signature Collection — handcrafted leather and canvas goods',
            ],
            [
                'label' => 'Desk &amp; Workspace',
                'href'  => $link_prefix . 'products.php',
                'alt'   => 'Amadika Desk Collection — premium leather desk organisers and stationery',
            ],
            [
                'label' => 'Heritage Trunks',
                'href'  => $link_prefix . 'products.php',
                'alt'   => 'Amadika Heritage Trunk Collection — canvas trunk boxes and travel cases',
            ],
        ];

        // Resolve banner image path
        $banner_img = $assets_path . 'images/collection/style-1.png';

        foreach ($col_banners as $idx => $bann):
        ?>

        <!-- Banner <?php echo $idx + 1; ?> -->
        <div>
            <a href="<?php echo htmlspecialchars($bann['href']); ?>"
               class="col-banner"
               id="col-banner-<?php echo $idx + 1; ?>"
               aria-label="<?php echo strip_tags($bann['label']); ?> — View Collection">

                <img src="<?php echo htmlspecialchars($banner_img); ?>"
                     alt="<?php echo htmlspecialchars($bann['alt']); ?>"
                     class="col-banner__img"
                     loading="<?php echo $idx === 0 ? 'eager' : 'lazy'; ?>"
                     decoding="async">

            </a>

            <!-- Below-banner label row -->
            <div class="col-banner-label">
                <span class="lbl-name"><?php echo $bann['label']; ?></span>
                <a href="<?php echo htmlspecialchars($bann['href']); ?>" class="lbl-cta" tabindex="-1" aria-hidden="true">
                    Shop Now →
                </a>
            </div>
        </div>

        <?php endforeach; ?>

        <!-- Shop All CTA -->
        <div class="col-shop-all-strip">
            <a href="<?php echo $link_prefix; ?>products.php" class="shop-all-btn" id="col-shop-all">
                View All Products
                <span class="btn-arrow" aria-hidden="true">→</span>
            </a>
        </div>

    </div><!-- /.col-banner-stack -->
</div><!-- /.col-banner-wrap -->

<?php include '../../includes/footer.php'; ?>

<style>
/* =============================================================
   Shop By Category — Hidesign Artisanal Circular Bubbles
   ============================================================= */

.sbc-section {
    background-color: #fcfbf8;
    padding: 60px 0 65px;
    position: relative;
    border-bottom: 1px solid #f0ede8;
}

/* ── Heading ─────────────────────────────────────────────── */
.sbc-heading {
    text-align: center;
    margin-bottom: 45px;
}

.sbc-eyebrow {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: #9a6431;
    margin-bottom: 8px;
}

.sbc-heading h2 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 32px;
    font-weight: 600;
    color: #1a1614;
    margin: 0 0 12px;
    line-height: 1.2;
}

.sbc-heading .sbc-divider {
    width: 48px;
    height: 2px;
    background-color: #c59b27;
    margin: 0 auto;
}

/* ── Slider track ────────────────────────────────────────── */
.sbc-track-wrap {
    position: relative;
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 20px;
}

.sbc-track {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
    padding: 12px 10px 18px;
}

.sbc-track::-webkit-scrollbar {
    display: none;
}

/* ── Individual bubble item ──────────────────────────────── */
.sbc-item {
    flex: 0 0 125px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none !important;
    cursor: pointer;
    transition: transform 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.sbc-item:hover {
    transform: translateY(-6px);
}

/* ── Circle image wrapper ────────────────────────────────── */
.sbc-circle {
    width: 98px;
    height: 98px;
    border-radius: 50%;
    overflow: hidden;
    background: #ffffff;
    flex-shrink: 0;
    margin-bottom: 14px;
    position: relative;
    border: 2px solid #eae5dd;
    box-shadow: 0 4px 14px rgba(28, 25, 23, 0.04);
    transition: all 0.35s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3px;
}

.sbc-circle-inner {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
    background: #f7f5f1;
}

.sbc-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    display: block;
}

.sbc-item:hover .sbc-circle {
    border-color: #c59b27;
    box-shadow: 0 8px 24px rgba(197, 155, 39, 0.22);
}

.sbc-item:hover .sbc-circle img {
    transform: scale(1.1);
}

/* ── Label ───────────────────────────────────────────────── */
.sbc-label {
    font-family: 'Outfit', sans-serif;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 1.8px;
    text-transform: uppercase;
    color: #1a1614;
    text-align: center;
    line-height: 1.35;
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    transition: color 0.25s ease;
}

.sbc-item:hover .sbc-label {
    color: #9a6431;
}

/* ── Arrow nav buttons ───────────────────────────────────── */
.sbc-nav-btn {
    position: absolute;
    top: 48%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    background: #ffffff;
    border: 1px solid #eae5dd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1a1614;
    font-size: 13px;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.sbc-nav-btn:hover {
    background: #c59b27;
    border-color: #c59b27;
    color: #1a1614;
}

.sbc-nav-prev { left: -6px; }
.sbc-nav-next { right: -6px; }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 991px) {
    .sbc-section {
        padding: 48px 0 52px;
    }
    .sbc-heading h2 {
        font-size: 27px;
    }
    .sbc-circle {
        width: 86px;
        height: 86px;
    }
    .sbc-item {
        flex: 0 0 110px;
    }
}

@media (max-width: 768px) {
    .sbc-section {
        padding: 38px 0 42px;
    }
    .sbc-heading {
        margin-bottom: 28px;
    }
    .sbc-eyebrow {
        font-size: 10px;
        letter-spacing: 2.5px;
    }
    .sbc-heading h2 {
        font-size: 22px;
    }
    .sbc-circle {
        width: 76px;
        height: 76px;
        margin-bottom: 10px;
    }
    .sbc-item {
        flex: 0 0 92px;
    }
    .sbc-label {
        font-size: 9.5px;
        letter-spacing: 1px;
    }
    .sbc-track-wrap {
        padding: 0 12px;
    }
    .sbc-nav-btn {
        display: none;
    }
}
</style>

<section class="sbc-section">
    <!-- Heading -->
    <div class="sbc-heading">
        <span class="sbc-eyebrow">Discover By Form &amp; Function</span>
        <h2>Shop By Category</h2>
        <div class="sbc-divider"></div>
    </div>

    <!-- Slider -->
    <div class="sbc-track-wrap">
        <!-- Prev Arrow -->
        <button id="sbcPrev" class="sbc-nav-btn sbc-nav-prev" aria-label="Scroll left">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div id="sbcTrack" class="sbc-track">
            <?php
            require_once __DIR__ . '/../database/db_config.php';
            require_once __DIR__ . '/../includes/image_helper.php';

            $sbc_sql = "SELECT * FROM product_categories ORDER BY created_at ASC";
            $sbc_result = $conn->query($sbc_sql);

            if ($sbc_result && $sbc_result->num_rows > 0):
                while ($cat = $sbc_result->fetch_assoc()):
                    $cat_name = htmlspecialchars($cat['name']);
                    $cat_slug = htmlspecialchars($cat['slug']);
                    $img_path = !empty($cat['image']) ? $cat['image'] : 'assets/images/demo-data/product.jpg';
                    $circle_img = get_resized_image($img_path, 220, 220, 'cover');
            ?>
                <a href="<?php echo $link_prefix; ?>products.php?category=<?php echo $cat_slug; ?>"
                   class="sbc-item"
                   title="<?php echo $cat_name; ?>">
                    <div class="sbc-circle">
                        <div class="sbc-circle-inner">
                            <img src="<?php echo $circle_img; ?>"
                                 alt="<?php echo $cat_name; ?>"
                                 loading="lazy">
                        </div>
                    </div>
                    <span class="sbc-label"><?php echo $cat_name; ?></span>
                </a>
            <?php
                endwhile;
            endif;
            ?>
        </div>

        <!-- Next Arrow -->
        <button id="sbcNext" class="sbc-nav-btn sbc-nav-next" aria-label="Scroll right">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>
</section>

<script>
(function () {
    const track = document.getElementById('sbcTrack');
    const prev  = document.getElementById('sbcPrev');
    const next  = document.getElementById('sbcNext');
    if (!track) return;

    const SCROLL_AMT = 360;

    prev && prev.addEventListener('click', () => {
        track.scrollBy({ left: -SCROLL_AMT, behavior: 'smooth' });
    });
    next && next.addEventListener('click', () => {
        track.scrollBy({ left: SCROLL_AMT, behavior: 'smooth' });
    });
})();
</script>
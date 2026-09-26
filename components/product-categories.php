<style>
/* =============================================================
   Shop Categories — Modern Editorial Portrait Cards
   Matching Reference: "Looking For Something Specific? Shop Categories"
   ============================================================= */

.shop-categories-section {
    background-color: #ffffff;
    padding: 60px 0 70px;
    position: relative;
    overflow: hidden;
    border-bottom: 1px solid #f2eee8;
}

.sc-container {
    max-width: 1440px;
    margin: 0 auto;
    padding: 0 40px;
    position: relative;
}

/* ── Section Heading ───────────────────────────────────────── */
.sc-heading-wrap {
    text-align: center;
    margin-bottom: 38px;
}

.sc-heading {
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 30px;
    font-weight: 500;
    color: #111111;
    margin: 0;
    letter-spacing: -0.2px;
    line-height: 1.25;
}

/* ── Track Wrapper & Slider ────────────────────────────────── */
.sc-track-wrapper {
    position: relative;
    width: 100%;
}

.sc-track {
    display: flex;
    align-items: flex-start;
    gap: 22px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
    padding: 8px 4px 16px;
}

.sc-track::-webkit-scrollbar {
    display: none;
}

/* ── Category Card Item ────────────────────────────────────── */
.sc-card {
    flex: 0 0 205px;
    display: flex;
    flex-direction: column;
    text-decoration: none !important;
    cursor: pointer;
    background: transparent;
    transition: transform 0.35s ease;
}

.sc-card:hover {
    transform: translateY(-4px);
}

/* ── Image Container (Portrait Aspect Ratio) ───────────────── */
.sc-image-box {
    width: 100%;
    aspect-ratio: 3 / 4;
    overflow: hidden;
    background: #f4f0ea;
    position: relative;
    border-radius: 2px;
}

.sc-image-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
    transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.sc-card:hover .sc-image-box img {
    transform: scale(1.05);
}

/* ── Label (Left Aligned Under Image) ───────────────────────── */
.sc-label {
    margin-top: 14px;
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: #111111;
    text-align: left;
    line-height: 1.35;
    transition: color 0.25s ease;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-left: 1px;
}

.sc-card:hover .sc-label {
    color: #9a6431;
}

/* ── Nav Arrow Buttons ─────────────────────────────────────── */
.sc-nav-btn {
    position: absolute;
    top: calc(50% - 20px);
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 50%;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #111111;
    font-size: 15px;
    cursor: pointer;
    z-index: 10;
    transition: all 0.25s ease;
    opacity: 0;
}

.sc-track-wrapper:hover .sc-nav-btn {
    opacity: 1;
}

.sc-nav-btn:hover {
    background: #111111;
    border-color: #111111;
    color: #ffffff;
    transform: translateY(-50%) scale(1.06);
}

.sc-nav-prev { left: -18px; }
.sc-nav-next { right: -18px; }

/* ── Responsive Styling ────────────────────────────────────── */
@media (max-width: 1200px) {
    .sc-card {
        flex: 0 0 185px;
    }
    .sc-heading {
        font-size: 26px;
    }
}

@media (max-width: 991px) {
    .shop-categories-section {
        padding: 48px 0 54px;
    }
    .sc-container {
        padding: 0 24px;
    }
    .sc-card {
        flex: 0 0 170px;
    }
    .sc-heading {
        font-size: 23px;
        margin-bottom: 28px;
    }
    .sc-label {
        font-size: 14px;
        margin-top: 12px;
    }
}

@media (max-width: 768px) {
    .shop-categories-section {
        padding: 38px 0 44px;
    }
    .sc-container {
        padding: 0 16px;
    }
    .sc-heading-wrap {
        margin-bottom: 22px;
    }
    .sc-heading {
        font-size: 19px;
        line-height: 1.3;
        padding: 0 8px;
    }
    .sc-track {
        gap: 14px;
        padding: 4px 4px 12px;
        scroll-snap-type: x mandatory;
    }
    .sc-card {
        flex: 0 0 148px;
        scroll-snap-align: start;
    }
    .sc-image-box {
        aspect-ratio: 3 / 4;
        border-radius: 2px;
    }
    .sc-label {
        font-size: 13px;
        font-weight: 600;
        margin-top: 10px;
    }
    .sc-nav-btn {
        display: none !important;
    }
}

@media (max-width: 480px) {
    .sc-card {
        flex: 0 0 140px;
    }
    .sc-heading {
        font-size: 18px;
    }
}
</style>

<section class="shop-categories-section">
    <div class="sc-container">
        <!-- Heading matching reference -->
        <div class="sc-heading-wrap">
            <h2 class="sc-heading">Looking For Something Specific? Shop Categories</h2>
        </div>

        <!-- Slider Track Wrap -->
        <div class="sc-track-wrapper">
            <!-- Prev Arrow -->
            <button id="scPrev" class="sc-nav-btn sc-nav-prev" aria-label="Scroll categories left">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div id="scTrack" class="sc-track">
                <?php
                require_once __DIR__ . '/../database/db_config.php';
                require_once __DIR__ . '/../includes/image_helper.php';

                $sc_sql = "SELECT * FROM product_categories ORDER BY created_at ASC";
                $sc_result = $conn->query($sc_sql);

                if ($sc_result && $sc_result->num_rows > 0):
                    while ($cat = $sc_result->fetch_assoc()):
                        $cat_name = htmlspecialchars($cat['name']);
                        $cat_slug = htmlspecialchars($cat['slug']);
                        $cid      = intval($cat['id']);

                        // Resolve high-resolution category image with robust fallback
                        $img_path = '';
                        if (!empty($cat['image']) && file_exists(__DIR__ . '/../' . $cat['image'])) {
                            $img_path = $cat['image'];
                        } else {
                            // Check latest product in this category
                            $p_sql = "SELECT featured_image FROM products WHERE category_id = $cid AND featured_image != '' AND featured_image IS NOT NULL ORDER BY id DESC LIMIT 1";
                            $p_res = $conn->query($p_sql);
                            if ($p_res && $p_row = $p_res->fetch_assoc()) {
                                if (!empty($p_row['featured_image']) && file_exists(__DIR__ . '/../' . $p_row['featured_image'])) {
                                    $img_path = $p_row['featured_image'];
                                }
                            }
                        }

                        if (empty($img_path)) {
                            $img_path = 'assets/images/demo-data/product.jpg';
                        }

                        // Generate 3:4 portrait image
                        $portrait_img = get_resized_image($img_path, 420, 560, 'cover');
                ?>
                    <a href="<?php echo $link_prefix; ?>products.php?category=<?php echo $cat_slug; ?>"
                       class="sc-card"
                       title="<?php echo $cat_name; ?>">
                        <div class="sc-image-box">
                            <img src="<?php echo $portrait_img; ?>"
                                 alt="<?php echo $cat_name; ?>"
                                 loading="lazy">
                        </div>
                        <span class="sc-label"><?php echo $cat_name; ?></span>
                    </a>
                <?php
                    endwhile;
                endif;
                ?>
            </div>

            <!-- Next Arrow -->
            <button id="scNext" class="sc-nav-btn sc-nav-next" aria-label="Scroll categories right">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<script>
(function () {
    const track = document.getElementById('scTrack');
    const prev  = document.getElementById('scPrev');
    const next  = document.getElementById('scNext');
    if (!track) return;

    const SCROLL_AMT = 450;

    prev && prev.addEventListener('click', () => {
        track.scrollBy({ left: -SCROLL_AMT, behavior: 'smooth' });
    });
    next && next.addEventListener('click', () => {
        track.scrollBy({ left: SCROLL_AMT, behavior: 'smooth' });
    });
})();
</script>
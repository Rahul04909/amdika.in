<style>
/* =============================================================
   Shop By Category — Circular Bubbles (Reference-Match Build)
   ============================================================= */

.sbc-section {
    background-color: #ffffff;
    padding: 52px 0 56px;
}

/* ── Heading ─────────────────────────────────────────────── */
.sbc-heading {
    text-align: center;
    margin-bottom: 42px;
}
.sbc-heading h2 {
    font-family: 'Outfit', sans-serif;
    font-size: 13px;
    font-weight: 400;           /* thin/light weight */
    letter-spacing: 6px;
    text-transform: uppercase;
    color: #3a2e1e;
    margin: 0;
    line-height: 1;
}

/* ── Slider track ────────────────────────────────────────── */
.sbc-track-wrap {
    position: relative;
    overflow: hidden;           /* hide scrollbar visually */
}

.sbc-track {
    display: flex;
    align-items: flex-start;
    gap: 0;                     /* items manage their own spacing */
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
    padding: 8px 60px 12px;    /* side padding so first/last items aren't clipped */
}
.sbc-track::-webkit-scrollbar {
    display: none;
}

/* ── Individual bubble item ──────────────────────────────── */
.sbc-item {
    flex: 0 0 auto;
    width: 118px;               /* controls spacing between items */
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0;
    text-decoration: none !important;
    cursor: pointer;
    transition: transform 0.3s ease;
}
.sbc-item:hover {
    transform: translateY(-4px);
}

/* ── Circle image wrapper ────────────────────────────────── */
.sbc-circle {
    width: 86px;
    height: 86px;
    border-radius: 50%;
    overflow: hidden;
    background: #e8e4de;        /* neutral fallback */
    flex-shrink: 0;
    margin-bottom: 14px;
    position: relative;
    /* No border, no box-shadow — exactly like reference */
}

.sbc-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.5s ease;
    display: block;
}
.sbc-item:hover .sbc-circle img {
    transform: scale(1.1);
}

/* ── Label ───────────────────────────────────────────────── */
.sbc-label {
    font-family: 'Outfit', sans-serif;
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 1.8px;
    text-transform: uppercase;
    color: #3a2e1e;
    text-align: center;
    line-height: 1.3;
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    transition: color 0.25s ease;
}

/* First item label gets the accent highlight (like "NEW ARRIVAL" in ref) */
.sbc-item:first-child .sbc-label,
.sbc-item.sbc-accent .sbc-label {
    color: #C89B2C;             /* brand gold accent */
}

/* Hover: all labels go accent gold */
.sbc-item:hover .sbc-label {
    color: #C89B2C;
}

/* ── Arrow nav buttons ───────────────────────────────────── */
.sbc-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    background: #ffffff;
    border: 1px solid #e5e0d8;
    border-radius: 50%;
    display: none;              /* shown on hover of wrapper */
    align-items: center;
    justify-content: center;
    color: #3a2e1e;
    font-size: 14px;
    cursor: pointer;
    z-index: 10;
    transition: background 0.2s, border-color 0.2s, color 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    line-height: 1;
}
.sbc-nav-btn:hover {
    background: #C89B2C;
    border-color: #C89B2C;
    color: #fff;
}
.sbc-nav-prev { left: 16px; }
.sbc-nav-next { right: 16px; }

.sbc-track-wrap:hover .sbc-nav-btn {
    display: flex;
}

/* ── Responsive ──────────────────────────────────────────── */
@media (min-width: 1200px) {
    .sbc-circle {
        width: 100px;
        height: 100px;
    }
    .sbc-item {
        width: 138px;
    }
    .sbc-label {
        font-size: 10.5px;
        letter-spacing: 2px;
    }
}

@media (max-width: 768px) {
    .sbc-section {
        padding: 36px 0 40px;
    }
    .sbc-heading h2 {
        font-size: 11px;
        letter-spacing: 4px;
    }
    .sbc-heading {
        margin-bottom: 30px;
    }
    .sbc-circle {
        width: 72px;
        height: 72px;
    }
    .sbc-item {
        width: 96px;
    }
    .sbc-label {
        font-size: 9px;
        letter-spacing: 1.2px;
    }
    .sbc-track {
        padding: 6px 20px 10px;
    }
}

@media (max-width: 480px) {
    .sbc-circle {
        width: 62px;
        height: 62px;
    }
    .sbc-item {
        width: 80px;
    }
    .sbc-label {
        font-size: 8px;
        letter-spacing: 0.8px;
    }
    .sbc-track {
        gap: 0;
        padding: 4px 12px 8px;
    }
}
</style>

<section class="sbc-section">
    <!-- Heading -->
    <div class="sbc-heading">
        <h2>Shop By Category</h2>
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

            $sbc_sql    = "SELECT * FROM product_categories ORDER BY created_at ASC";
            $sbc_result = $conn->query($sbc_sql);
            $sbc_index  = 0;

            if ($sbc_result && $sbc_result->num_rows > 0):
                while ($cat = $sbc_result->fetch_assoc()):
                    $cat_name = htmlspecialchars($cat['name']);
                    $cat_slug = htmlspecialchars($cat['slug']);
                    $img_path = !empty($cat['image']) ? $cat['image'] : 'assets/images/demo-data/product.jpg';
                    $circle_img = get_resized_image($img_path, 220, 220, 'cover');

                    // First item gets the accent class to mirror the reference highlight
                    $accent_class = ($sbc_index === 0) ? ' sbc-accent' : '';
                    $sbc_index++;
            ?>
                <a href="<?php echo $link_prefix; ?>products.php?category=<?php echo $cat_slug; ?>"
                   class="sbc-item<?php echo $accent_class; ?>"
                   title="<?php echo $cat_name; ?>">
                    <div class="sbc-circle">
                        <img src="<?php echo $circle_img; ?>"
                             alt="<?php echo $cat_name; ?>"
                             loading="lazy">
                    </div>
                    <span class="sbc-label"><?php echo $cat_name; ?></span>
                </a>
            <?php
                endwhile;
            endif;
            ?>
        </div><!-- /#sbcTrack -->

        <!-- Next Arrow -->
        <button id="sbcNext" class="sbc-nav-btn sbc-nav-next" aria-label="Scroll right">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

    </div><!-- /.sbc-track-wrap -->
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
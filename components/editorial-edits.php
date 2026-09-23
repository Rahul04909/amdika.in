<?php
require_once __DIR__ . '/../includes/image_helper.php';

$edit_img_1 = 'assets/images/hero/office.png';
$edit_img_2 = 'assets/images/corporate_gift_set.png';

$resized_edit_1 = get_resized_image($edit_img_1, 900, 600, 'cover');
$resized_edit_2 = get_resized_image($edit_img_2, 900, 600, 'cover');
?>

<style>
/* =============================================================
   Curated Editorial Edits — Hidesign Dual Visual Collections
   ============================================================= */

.editorial-edits-section {
    background-color: #fcfbf8;
    padding: 70px 0 80px;
    border-top: 1px solid #f0ede8;
    border-bottom: 1px solid #f0ede8;
}

.editorial-edits-container {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 30px;
}

.edits-header {
    text-align: center;
    margin-bottom: 45px;
}

.edits-kicker {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: #9a6431;
    margin-bottom: 8px;
}

.edits-header h2 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 34px;
    font-weight: 600;
    color: #1a1614;
    margin: 0 0 12px;
}

.edits-divider {
    width: 48px;
    height: 2px;
    background-color: #c59b27;
    margin: 0 auto;
}

.edits-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.edit-tile {
    position: relative;
    border-radius: 4px;
    overflow: hidden;
    height: 480px;
    display: block;
    text-decoration: none !important;
    box-shadow: 0 10px 30px rgba(28, 25, 23, 0.08);
}

.edit-tile-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.edit-tile:hover .edit-tile-img {
    transform: scale(1.06);
}

.edit-tile-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        180deg,
        rgba(18, 14, 12, 0.15) 0%,
        rgba(18, 14, 12, 0.5) 50%,
        rgba(18, 14, 12, 0.9) 100%
    );
    transition: background 0.4s ease;
}

.edit-tile:hover .edit-tile-overlay {
    background: linear-gradient(
        180deg,
        rgba(18, 14, 12, 0.1) 0%,
        rgba(18, 14, 12, 0.45) 45%,
        rgba(18, 14, 12, 0.94) 100%
    );
}

.edit-tile-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 35px;
    color: #ffffff;
    z-index: 2;
}

.edit-tile-tag {
    display: inline-block;
    font-family: 'Outfit', sans-serif;
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #e5b94c;
    margin-bottom: 8px;
}

.edit-tile-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 28px;
    font-weight: 600;
    color: #fcfbf8;
    margin-bottom: 8px;
    line-height: 1.25;
}

.edit-tile-desc {
    font-family: 'Outfit', sans-serif;
    font-size: 13.5px;
    font-weight: 300;
    color: #ded7cd;
    margin-bottom: 18px;
    line-height: 1.5;
    max-width: 440px;
}

.edit-tile-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Outfit', sans-serif;
    font-size: 11.5px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #ffffff;
    border-bottom: 1px solid #c59b27;
    padding-bottom: 4px;
    transition: all 0.3s ease;
}

.edit-tile:hover .edit-tile-cta {
    color: #e5b94c;
    gap: 12px;
}

@media (max-width: 991px) {
    .editorial-edits-section {
        padding: 50px 0 60px;
    }
    .edit-tile {
        height: 400px;
    }
    .edit-tile-content {
        padding: 25px;
    }
    .edit-tile-title {
        font-size: 24px;
    }
    .edits-header h2 {
        font-size: 28px;
    }
}

@media (max-width: 768px) {
    .edits-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    .editorial-edits-container {
        padding: 0 15px;
    }
    .edit-tile {
        height: 340px;
    }
    .edit-tile-title {
        font-size: 22px;
    }
    .edit-tile-desc {
        font-size: 12.5px;
        margin-bottom: 14px;
    }
}
</style>

<section class="editorial-edits-section">
    <div class="editorial-edits-container">
        <div class="edits-header">
            <span class="edits-kicker">CURATED EDITS</span>
            <h2>Artisanal Living Spaces</h2>
            <div class="edits-divider"></div>
        </div>

        <div class="edits-grid">
            <!-- Edit 1: Office & Desk Sanctuary -->
            <a href="products.php" class="edit-tile">
                <img src="<?php echo $resized_edit_1; ?>" alt="Executive Desk Sanctuary" class="edit-tile-img" loading="lazy">
                <div class="edit-tile-overlay"></div>
                <div class="edit-tile-content">
                    <span class="edit-tile-tag">THE DESK SANCTUARY</span>
                    <h3 class="edit-tile-title">Executive Workspaces</h3>
                    <p class="edit-tile-desc">Elevate your daily rhythm with vegetable-tanned desk mats, stationery boxes, and valet trays.</p>
                    <span class="edit-tile-cta">
                        <span>Discover Desk Edit</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </span>
                </div>
            </a>

            <!-- Edit 2: Bespoke & Corporate Gifting -->
            <a href="products.php" class="edit-tile">
                <img src="<?php echo $resized_edit_2; ?>" alt="Bespoke Corporate Gifting" class="edit-tile-img" loading="lazy">
                <div class="edit-tile-overlay"></div>
                <div class="edit-tile-content">
                    <span class="edit-tile-tag">BESPOKE GIFTING</span>
                    <h3 class="edit-tile-title">Heirloom Gift Sets</h3>
                    <p class="edit-tile-desc">Curated leather keepsakes and bespoke monogrammed corporate suites created to be cherished.</p>
                    <span class="edit-tile-cta">
                        <span>Explore Gifting Suite</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>

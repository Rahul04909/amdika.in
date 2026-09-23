<style>
/* =============================================================
   Brand Value Pillars — Hidesign Heritage & Trust Marks
   ============================================================= */

.brand-pillars-section {
    background-color: #f7f5f0;
    padding: 60px 0;
    border-top: 1px solid #ebe6df;
    border-bottom: 1px solid #ebe6df;
}

.brand-pillars-container {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 30px;
}

.pillars-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 32px;
}

.pillar-card {
    text-align: center;
    padding: 24px 16px;
    border-radius: 4px;
    transition: transform 0.35s ease;
}

.pillar-card:hover {
    transform: translateY(-4px);
}

.pillar-icon-box {
    width: 62px;
    height: 62px;
    margin: 0 auto 18px;
    border-radius: 50%;
    background-color: #ffffff;
    border: 1px solid #e2dbcf;
    color: #9a6431;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    box-shadow: 0 4px 14px rgba(28, 25, 23, 0.05);
    transition: all 0.3s ease;
}

.pillar-card:hover .pillar-icon-box {
    background-color: #c59b27;
    border-color: #c59b27;
    color: #1a1614;
    box-shadow: 0 8px 22px rgba(197, 155, 39, 0.25);
}

.pillar-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 17px;
    font-weight: 600;
    color: #1a1614;
    margin: 0 0 8px;
    letter-spacing: -0.2px;
}

.pillar-desc {
    font-family: 'Outfit', sans-serif;
    font-size: 13px;
    font-weight: 300;
    color: #6b635b;
    line-height: 1.55;
    margin: 0;
}

@media (max-width: 991px) {
    .pillars-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    .brand-pillars-section {
        padding: 50px 0;
    }
}

@media (max-width: 576px) {
    .pillars-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    .brand-pillars-container {
        padding: 0 20px;
    }
    .pillar-card {
        padding: 16px 10px;
    }
    .pillar-icon-box {
        width: 54px;
        height: 54px;
        font-size: 19px;
        margin-bottom: 14px;
    }
    .pillar-title {
        font-size: 16px;
    }
    .pillar-desc {
        font-size: 12.5px;
    }
}
</style>

<section class="brand-pillars-section">
    <div class="brand-pillars-container">
        <div class="pillars-grid">
            <!-- Pillar 1 -->
            <div class="pillar-card">
                <div class="pillar-icon-box">
                    <i class="fa-solid fa-leaf"></i>
                </div>
                <h3 class="pillar-title">100% Vegetable Tanned</h3>
                <p class="pillar-desc">Naturally cured using organic bark extracts. No synthetic polymers; pure leather that ages gracefully.</p>
            </div>

            <!-- Pillar 2 -->
            <div class="pillar-card">
                <div class="pillar-icon-box">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="pillar-title">Solid Sand-Cast Brass</h3>
                <p class="pillar-desc">Heirloom metal hardware individually cast by hand. Designed to never rust, flake, or corrode.</p>
            </div>

            <!-- Pillar 3 -->
            <div class="pillar-card">
                <div class="pillar-icon-box">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <h3 class="pillar-title">Master Artisanship</h3>
                <p class="pillar-desc">Hand-cut, skived, and saddle-stitched by skilled leather craftsmen preserving time-honored heritage.</p>
            </div>

            <!-- Pillar 4 -->
            <div class="pillar-card">
                <div class="pillar-icon-box">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h3 class="pillar-title">Complimentary Express</h3>
                <p class="pillar-desc">Insured express pan-India shipping on all qualifying orders with 7-day hassle-free replacement.</p>
            </div>
        </div>
    </div>
</section>

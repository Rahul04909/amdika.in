<style>
/* =============================================================
   Customer Voices & Testimonials — Hidesign Social Proof
   ============================================================= */

.testimonials-section {
    background-color: #fcfbf8;
    padding: 70px 0 80px;
    border-top: 1px solid #ebe6df;
}

.testimonials-container {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 30px;
}

.testimonials-header {
    text-align: center;
    margin-bottom: 50px;
}

.testimonials-kicker {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: #9a6431;
    margin-bottom: 8px;
}

.testimonials-header h2 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 34px;
    font-weight: 600;
    color: #1a1614;
    margin: 0 0 12px;
}

.testimonials-divider {
    width: 48px;
    height: 2px;
    background-color: #c59b27;
    margin: 0 auto;
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.testimonial-card {
    background: #ffffff;
    border: 1px solid #ebe6df;
    border-radius: 4px;
    padding: 32px 28px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 15px rgba(28, 25, 23, 0.03);
    transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
    position: relative;
}

.testimonial-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 36px rgba(28, 25, 23, 0.08);
    border-color: #c59b27;
}

.testimonial-stars {
    color: #c59b27;
    font-size: 13px;
    margin-bottom: 16px;
    display: flex;
    gap: 4px;
}

.testimonial-quote {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 15px;
    font-style: italic;
    line-height: 1.65;
    color: #332d29;
    margin-bottom: 24px;
    flex-grow: 1;
}

.testimonial-author {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 16px;
    border-top: 1px solid #f2eee8;
}

.author-info h4 {
    font-family: 'Outfit', sans-serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.5px;
    color: #1a1614;
    margin: 0 0 2px;
}

.author-info span {
    font-family: 'Outfit', sans-serif;
    font-size: 11.5px;
    color: #8c827a;
}

.verified-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: 'Outfit', sans-serif;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #2e7d32;
    background: #e8f5e9;
    padding: 3px 8px;
    border-radius: 2px;
}

@media (max-width: 991px) {
    .testimonials-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    .testimonials-section {
        padding: 50px 0 60px;
    }
    .testimonials-header h2 {
        font-size: 28px;
    }
}

@media (max-width: 576px) {
    .testimonials-container {
        padding: 0 15px;
    }
    .testimonial-card {
        padding: 24px 20px;
    }
    .testimonials-header h2 {
        font-size: 23px;
    }
}
</style>

<section class="testimonials-section">
    <div class="testimonials-container">
        <div class="testimonials-header">
            <span class="testimonials-kicker">PATRON STORIES</span>
            <h2>Loved For Generations</h2>
            <div class="testimonials-divider"></div>
        </div>

        <div class="testimonials-grid">
            <!-- Review 1 -->
            <div class="testimonial-card">
                <div class="testimonial-stars">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <p class="testimonial-quote">
                    “The aroma of authentic vegetable-tanned leather is unmistakable. The stitching is impeccable, and after 6 months of daily use, the leather has developed a glorious deep patina. Worth every single rupee.”
                </p>
                <div class="testimonial-author">
                    <div class="author-info">
                        <h4>Vikramaditya S.</h4>
                        <span>Mumbai &bull; Patina Lover</span>
                    </div>
                    <span class="verified-badge">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Verified</span>
                    </span>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="testimonial-card">
                <div class="testimonial-stars">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <p class="testimonial-quote">
                    “The solid sand-cast brass hardware feels substantial and heavy—not the hollow plated metal found in ordinary stores. My desk organizer set transformed my study into an executive sanctuary.”
                </p>
                <div class="testimonial-author">
                    <div class="author-info">
                        <h4>Ananya R.</h4>
                        <span>Bengaluru &bull; Architect</span>
                    </div>
                    <span class="verified-badge">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Verified</span>
                    </span>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="testimonial-card">
                <div class="testimonial-stars">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <p class="testimonial-quote">
                    “We curated custom monogrammed corporate gift suites for our executive board. The presentation, magnetic keepsake boxes, and grain texture exceeded our highest expectations.”
                </p>
                <div class="testimonial-author">
                    <div class="author-info">
                        <h4>Rohit M.</h4>
                        <span>New Delhi &bull; Managing Director</span>
                    </div>
                    <span class="verified-badge">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Verified</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

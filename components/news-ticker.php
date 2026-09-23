<style>
    /* --- Luxury Seamless Ticker (Dribbble Inspired) --- */
    .news-ticker-container {
        background: linear-gradient(90deg, #0b0f19 0%, #111827 50%, #0b0f19 100%);
        color: #f8fafc;
        overflow: hidden;
        padding: 11px 0;
        border-top: 1px solid rgba(197, 155, 39, 0.25);
        border-bottom: 1px solid rgba(197, 155, 39, 0.25);
        position: relative;
        display: flex;
        align-items: center;
        user-select: none;
    }

    .ticker-scroll-wrapper {
        display: flex;
        flex-wrap: nowrap;
        white-space: nowrap;
        animation: ticker-animation 35s linear infinite;
    }

    /* Pause on hover */
    .news-ticker-container:hover .ticker-scroll-wrapper {
        animation-play-state: paused;
    }

    .ticker-item {
        display: inline-flex;
        align-items: center;
        padding: 0 35px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.6px;
        color: #e2e8f0;
    }

    .ticker-item strong {
        color: #fcd34d;
        font-weight: 700;
        margin-left: 4px;
    }

    .ticker-star {
        color: #c59b27;
        font-size: 10px;
        margin-left: 35px;
        opacity: 0.8;
    }

    @keyframes ticker-animation {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .news-ticker-container {
            padding: 9px 0;
        }

        .ticker-item {
            font-size: 11px;
            padding: 0 20px;
        }

        .ticker-star {
            margin-left: 20px;
        }
    }
</style>

<div class="news-ticker-container">
    <div class="ticker-scroll-wrapper">
        <?php
        $ticker_messages = [
            "Handcrafted In Pure Full-Grain Leather &bull; <strong>100% Authentic Quality</strong>",
            "Complimentary Express Pan-India Delivery On Orders Above <strong>₹9,999</strong>",
            "Explore Luxury Office Desk Organizers &amp; <strong>Valet Trays</strong>",
            "Bespoke Corporate Gifting &bull; <strong>Custom Engraving Available</strong>",
            "Cash On Delivery (COD) Available &bull; <strong>7-Day Easy Replacements</strong>"
        ];

        // Loop twice for seamless infinite scroll
        for ($j = 0; $j < 2; $j++):
            foreach ($ticker_messages as $message): ?>
                <div class="ticker-item">
                    <span><?php echo $message; ?></span>
                    <i class="fas fa-gem ticker-star"></i>
                </div>
            <?php endforeach;
        endfor; ?>
    </div>
</div>
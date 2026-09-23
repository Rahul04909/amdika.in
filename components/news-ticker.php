<style>
    /* --- Hidesign Inspired Artisanal Ticker --- */
    .news-ticker-container {
        background: #181513;
        color: #f5f2eb;
        overflow: hidden;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
        padding: 10px 0;
        border-top: 1px solid rgba(197, 155, 39, 0.2);
        border-bottom: 1px solid rgba(197, 155, 39, 0.2);
        position: relative;
        display: flex;
        align-items: center;
        user-select: none;
    }

    .ticker-scroll-wrapper {
        display: flex;
        flex-wrap: nowrap;
        white-space: nowrap;
        animation: ticker-animation 38s linear infinite;
    }

    /* Pause on hover */
    .news-ticker-container:hover .ticker-scroll-wrapper {
        animation-play-state: paused;
    }

    .ticker-item {
        display: inline-flex;
        align-items: center;
        padding: 0 35px;
        font-family: 'Outfit', sans-serif;
        font-size: 11.5px;
        font-weight: 500;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #ded7cd;
    }

    .ticker-item strong {
        color: #e5b94c;
        font-weight: 600;
        margin-left: 4px;
    }

    .ticker-star {
        color: #c59b27;
        font-size: 8px;
        margin-left: 35px;
        opacity: 0.75;
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
            padding: 8px 0;
        }

        .ticker-item {
            font-size: 10px;
            letter-spacing: 1.2px;
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
            "100% Vegetable-Tanned Full Grain Leather &bull; <strong>Master Artisanal Craft</strong>",
            "Complimentary Express Pan-India Delivery On Orders Above <strong>₹9,999</strong>",
            "Solid Sand-Cast Brass Hardware &bull; <strong>Heirloom Durability</strong>",
            "Bespoke Corporate Gifting &amp; Monogramming &bull; <strong>Luxury Suites</strong>",
            "Natural Patina That Ages Beautifully Over Time &bull; <strong>Slow Luxury</strong>"
        ];

        // Loop twice for seamless infinite scroll
        for ($j = 0; $j < 2; $j++):
            foreach ($ticker_messages as $message): ?>
                <div class="ticker-item">
                    <span><?php echo $message; ?></span>
                    <i class="fa-solid fa-diamond ticker-star"></i>
                </div>
            <?php endforeach;
        endfor; ?>
    </div>
</div>
<style>
    /* --- Modern Infinite News Ticker --- */
    .news-ticker-container {
        background-color: #FFC107;
        /* Brand Yellow */
        color: #000;
        overflow: hidden;
        padding: 10px 0;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        display: flex;
        user-select: none;
    }

    .ticker-scroll {
        display: flex;
        flex-shrink: 0;
        min-width: 100%;
        align-items: center;
        justify-content: space-around;
        animation: scroll-left 40s linear infinite;
    }

    .ticker-item {
        display: flex;
        align-items: center;
        flex-shrink: 0;
        padding: 0 40px;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .ticker-dot {
        width: 5px;
        height: 5px;
        background-color: #000;
        border-radius: 50%;
        margin-left: 40px;
        flex-shrink: 0;
        opacity: 0.6;
    }

    @keyframes scroll-left {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(-100%);
        }
    }

    /* Pause on hover */
    .news-ticker-container:hover .ticker-scroll {
        animation-play-state: paused;
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .news-ticker-container {
            padding: 8px 0;
        }

        .ticker-item {
            font-size: 12px;
            padding: 0 20px;
        }

        .ticker-dot {
            margin-left: 20px;
        }

        .ticker-scroll {
            animation-duration: 25s;
            /* Slightly faster for shorter distance */
        }
    }
</style>

<div class="news-ticker-container">
    <div class="ticker-scroll">
        <?php for ($i = 0; $i < 6; $i++): ?>
            <div class="ticker-item">
                Welcome To Amadika Online Store
                <div class="ticker-dot"></div>
            </div>
        <?php endfor; ?>
    </div>
    <!-- Duplicate for seamless loop -->
    <div class="ticker-scroll" aria-hidden="true">
        <?php for ($i = 0; $i < 6; $i++): ?>
            <div class="ticker-item">
                Grab The Best Deals on Leather Waste Bin Collection
                <div class="ticker-dot"></div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Duplicate for seamless loop -->
    <div class="ticker-scroll" aria-hidden="true">
        <?php for ($i = 0; $i < 6; $i++): ?>
            <div class="ticker-item">
                Explore the top quality & premium portable mini bar's
                <div class="ticker-dot"></div>
            </div>
        <?php endfor; ?>
    </div>
</div>
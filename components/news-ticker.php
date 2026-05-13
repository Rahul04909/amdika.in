<style>
    /* --- Professional Seamless News Ticker --- */
    .news-ticker-container {
        background-color: #FFC107; /* Brand Yellow */
        color: #000;
        overflow: hidden;
        padding: 12px 0;
        border-top: 1px solid rgba(0,0,0,0.1);
        border-bottom: 1px solid rgba(0,0,0,0.1);
        position: relative;
        display: flex;
        align-items: center;
        user-select: none;
    }

    .ticker-scroll-wrapper {
        display: flex;
        flex-wrap: nowrap;
        white-space: nowrap;
        animation: ticker-animation 40s linear infinite;
    }

    /* Pause on hover */
    .news-ticker-container:hover .ticker-scroll-wrapper {
        animation-play-state: paused;
    }

    .ticker-item {
        display: inline-flex;
        align-items: center;
        padding: 0 40px;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .ticker-dot {
        width: 6px;
        height: 6px;
        background: #000;
        border-radius: 50%;
        margin-left: 40px;
        opacity: 0.3;
    }

    @keyframes ticker-animation {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); } /* Scroll exactly half to loop seamlessly */
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .news-ticker-container {
            padding: 10px 0;
        }
        .ticker-item {
            font-size: 11px;
            padding: 0 25px;
        }
        .ticker-dot {
            margin-left: 25px;
        }
    }
</style>

<div class="news-ticker-container">
    <div class="ticker-scroll-wrapper">
        <?php 
        // Define messages once
        $ticker_messages = [
            "Welcome To Amadika Online Store",
            "Grab The Best Deals on Leather Waste Bin Collection",
            "Explore the top quality & premium portable mini bar's",
            "Free Shipping on orders above ₹1999",
            "New Arrivals: Check out our Summer Collection"
        ];
        
        // Loop twice for a seamless infinite effect
        for ($j = 0; $j < 2; $j++): 
            foreach ($ticker_messages as $message): ?>
                <div class="ticker-item">
                    <?php echo htmlspecialchars($message); ?>
                    <div class="ticker-dot"></div>
                </div>
            <?php endforeach;
        endfor; ?>
    </div>
</div>
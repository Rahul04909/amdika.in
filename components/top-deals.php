<style>
/* --- Top Deals Component --- */
.top-deals-section {
    font-family: 'Poppins', sans-serif;
}

/* Matching styles from Product Categories */
.container-custom-rounded {
    border-radius: 12px;
}

.border-gold {
    border: 1px solid rgba(212, 160, 23, 0.2);
}

.top-deals-title {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
}

.top-deals-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 4px;
    background-color: var(--accent-gold, #D4A017); /* Match theme gold */
    border-radius: 2px;
}

.deals-scroll-container {
    display: flex;
    overflow-x: auto;
    gap: 20px;
    padding: 20px 5px;
    scrollbar-width: none;
    -ms-overflow-style: none;
    scroll-behavior: smooth;
}

.deals-scroll-container::-webkit-scrollbar {
    display: none;
}

.deal-card {
    flex: 0 0 250px;
    height: 380px;
    background-color: #fff;
    border-radius: 125px 125px 0 0;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e0e0e0;
    display: flex;
    flex-direction: column;
}

.deal-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.deal-image-wrapper {
    width: 100%;
    height: 75%;
    position: relative;
    border-radius: 125px 125px 0 0;
    overflow: hidden;
    background-color: #f4f4f4;
}

.deal-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.deal-card:hover .deal-image {
    transform: scale(1.05);
}

.deal-content {
    position: absolute;
    bottom: 45px;
    width: 100%;
    text-align: center;
    background: linear-gradient(to top, rgba(0,0,0,0.4), transparent);
    padding-bottom: 10px;
    z-index: 2;
}

.deal-title {
    color: #fff;
    font-size: 1.2rem;
    font-weight: 500;
    text-shadow: 0 2px 4px rgba(0,0,0,0.6);
    margin: 0;
}

.deal-price-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #c0392b;
    color: white;
    text-align: center;
    padding: 10px 0;
    font-size: 1.1rem;
    font-weight: 700;
    z-index: 3;
}

.deal-price-bar small {
    font-size: 0.8rem;
    font-weight: 400;
    margin-right: 3px;
    opacity: 0.9;
}

.deal-card.orange-border {
    border: 2px solid var(--accent-gold, #D4A017); /* Use theme gold if available */
}

@media (min-width: 992px) {
    .deals-scroll-container {
        justify-content: center;
        flex-wrap: wrap;
        overflow-x: visible;
    }
    
    .deal-card {
        flex: 0 0 calc(20% - 20px);
        max-width: 250px;
        margin-bottom: 20px;
    }
}

@media (max-width: 768px) {
    .deal-card {
        flex: 0 0 200px;
        height: 320px;
    }
    
    .top-deals-title {
        font-size: 1.5rem;
    }
}
</style>

<section class="top-deals-section mt-4 mb-5">
    <div class="container container-custom-rounded bg-white p-4 shadow-sm border-gold position-relative">
        <div class="text-center mb-4">
            <h2 class="top-deals-title">Furniture Deals</h2>
        </div>

        <div class="deals-scroll-container pb-1">
            <!-- Card 1: Sofas -->
            <a href="#" class="deal-card text-decoration-none orange-border">
                <div class="deal-image-wrapper">
                    <!-- Using placeholder or path provided by user if available. Using placeholder for now. -->
                    <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Sofas" class="deal-image">
                    <div class="deal-content">
                        <h3 class="deal-title">Sofas</h3>
                    </div>
                </div>
                <div class="deal-price-bar">
                    <small>from</small> ₹9,999
                </div>
            </a>

            <!-- Card 2: Recliner -->
            <a href="#" class="deal-card text-decoration-none orange-border">
                <div class="deal-image-wrapper">
                    <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Recliner" class="deal-image">
                    <div class="deal-content">
                        <h3 class="deal-title">Recliner</h3>
                    </div>
                </div>
                <div class="deal-price-bar">
                    <small>from</small> ₹14,999
                </div>
            </a>

            <!-- Card 3: Study Desks -->
            <a href="#" class="deal-card text-decoration-none orange-border">
                <div class="deal-image-wrapper">
                    <img src="https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Study Desks" class="deal-image">
                    <div class="deal-content">
                        <h3 class="deal-title">Study Desks</h3>
                    </div>
                </div>
                <div class="deal-price-bar">
                    <small>from</small> ₹2,999
                </div>
            </a>

            <!-- Card 4: Centre Tables -->
            <a href="#" class="deal-card text-decoration-none orange-border">
                <div class="deal-image-wrapper">
                    <img src="https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Centre Tables" class="deal-image">
                    <div class="deal-content">
                        <h3 class="deal-title">Centre Tables</h3>
                    </div>
                </div>
                <div class="deal-price-bar">
                    <small>from</small> ₹1,999
                </div>
            </a>

            <!-- Card 5: Shoe Racks -->
            <a href="#" class="deal-card text-decoration-none orange-border">
                <div class="deal-image-wrapper">
                    <img src="https://images.unsplash.com/photo-1595428774223-ef52624120d2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Shoe Racks" class="deal-image">
                    <div class="deal-content">
                        <h3 class="deal-title">Shoe Racks</h3>
                    </div>
                </div>
                <div class="deal-price-bar">
                    <small>Upto</small> 50% off
                </div>
            </a>
        </div>
    </div>
</section>

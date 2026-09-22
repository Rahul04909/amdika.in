<?php
// Page Setup
$page_title = "Our Boutiques & Showrooms | Amadika";
$page_description = "Step into the world of Amadika. Visit our premium handcrafted leather experience showrooms in Faridabad, Greater Kailash Delhi, and Galleria Gurugram.";
$page_keywords = "amadika stores, leather store near me, amadika showroom, luxury leather showroom, store locator, store finder";
include '../../includes/header.php'; 
?>

<!-- Add Animate.css for entrance animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    /* --- Premium Luxury Theme Variables & Styles --- */
    :root {
        --lux-gold: #C89B2C;
        --lux-dark: #111827;
        --lux-cream: #FCFBF8;
        --lux-border: rgba(200, 155, 44, 0.2);
    }

    body {
        background-color: var(--lux-cream);
        color: #374151;
    }

    /* Elegant Hero with requested bags-banner.png */
    .stores-hero-premium {
        position: relative;
        background: linear-gradient(180deg, rgba(17, 24, 39, 0.8) 0%, rgba(17, 24, 39, 0.9) 100%), 
                    url('../../assets/images/banners/bags-banner.png') center/cover no-repeat;
        height: 380px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 2px solid var(--lux-gold);
        overflow: hidden;
    }

    .stores-hero-premium::before {
        content: '';
        position: absolute;
        width: 150%;
        height: 100%;
        background: radial-gradient(circle, rgba(200, 155, 44, 0.15) 0%, transparent 70%);
        top: -20%;
        left: -25%;
        z-index: 1;
        pointer-events: none;
    }

    /* Glassmorphic Search & Filter Container */
    .search-glass-panel {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(200, 155, 44, 0.2);
        border-radius: 24px;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.04);
    }

    .premium-search-input:focus {
        border-color: var(--lux-gold) !important;
        box-shadow: 0 0 0 3px rgba(200, 155, 44, 0.15) !important;
    }

    /* Luxury City Pills */
    .city-pill {
        background-color: #ffffff;
        color: #4b5563;
        border: 1px solid rgba(229, 231, 235, 0.8);
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .city-pill:hover, .city-pill.active {
        background-color: var(--lux-dark);
        color: #ffffff !important;
        border-color: var(--lux-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(17, 24, 39, 0.15);
    }

    .city-pill.active {
        background-color: var(--lux-gold);
        border-color: var(--lux-gold);
        box-shadow: 0 4px 12px rgba(200, 155, 44, 0.25);
    }

    /* Exquisite Boutique Grid Cards */
    .boutique-card-premium {
        background: #ffffff;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 24px;
        padding: 30px 24px;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: justify;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .boutique-card-premium::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, transparent, var(--lux-gold), transparent);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .boutique-card-premium:hover {
        transform: translateY(-8px);
        border-color: var(--lux-gold);
        box-shadow: 0 25px 50px -15px rgba(200, 155, 44, 0.15), 0 1px 5px rgba(0, 0, 0, 0.02);
    }

    .boutique-card-premium:hover::before {
        transform: scaleX(1);
    }

    /* Gold Service Badges */
    .service-badge {
        background-color: rgba(200, 155, 44, 0.06);
        color: #926f1c;
        border: 1px solid rgba(200, 155, 44, 0.12);
        font-size: 10px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 8px;
        letter-spacing: 0.02em;
    }

    /* Section divider line */
    .gold-divider {
        width: 60px;
        height: 2px;
        background-color: var(--lux-gold);
        margin: 15px auto;
    }

    /* Spacing fixes for bottom CTA */
    .vip-cta-section {
        margin-top: 20px;
        margin-bottom: 80px; /* Generous bottom spacing to isolate from footer */
        border-radius: 28px;
        border: 1px solid rgba(200, 155, 44, 0.25);
    }
</style>

<!-- Hero Banner (High-end Luxury Presentation) -->
<section class="stores-hero-premium text-white">
    <div class="container px-4 text-center z-10">
        <span class="text-[11px] font-extrabold uppercase tracking-widest text-luxGold mb-2 block animate__animated animate__fadeInDown">Amadika Boutiques</span>
        <h1 class="font-serif italic text-4xl lg:text-6xl font-bold tracking-wide mb-3 animate__animated animate__fadeInUp">Showrooms & Outlets</h1>
        <div class="gold-divider animate__animated animate__zoomIn"></div>
        <p class="text-xs lg:text-sm text-gray-300 font-light max-w-xl mx-auto tracking-wide leading-relaxed animate__animated animate__fadeInUp animate__delay-1s">
            Step in to experience premium handcrafted leather accessories. Consult our design experts, explore tailored corporate suites, and request customized initial hot-stamping.
        </p>
    </div>
</section>

<!-- Store Finder Main Interface -->
<section class="py-12 lg:py-24 bg-[#FCFBF8]">
    <div class="container max-w-7xl mx-auto px-4">
        
        <!-- Search & Quick Filters Header -->
        <div class="search-glass-panel p-6 lg:p-8 mb-16 max-w-4xl mx-auto text-center border-0">
            <h2 class="text-2xl lg:text-3xl font-serif font-semibold text-darkLux mb-2">Locate an Amadika Store</h2>
            <p class="text-xs text-gray-500 mb-6">Search by city name, state, or pincode to check operating timings and location details</p>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Premium Search Input Group -->
                    <div class="relative flex items-center bg-white border border-gray-200/80 rounded-2xl shadow-sm focus-within:border-luxGold focus-within:ring-2 focus-within:ring-luxGold/10 transition-all duration-300 overflow-hidden h-14 mb-6 px-4">
                        <i data-lucide="search" class="w-5 h-5 text-luxGold mr-3 flex-shrink-0"></i>
                        <input type="text" 
                               id="storeSearchInput" 
                               class="bg-transparent border-0 text-sm w-full text-gray-700 focus:outline-none placeholder-gray-400 font-medium" 
                               placeholder="Type city, state, or pincode (e.g. Faridabad, GK 1 Delhi, 122009)..." 
                               autocomplete="off">
                        <button id="clearSearchBtn" class="hidden text-gray-400 hover:text-darkLux focus:outline-none bg-transparent border-0 p-1">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Quick Location Tag Selectors -->
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
                        <span class="text-[10px] font-extrabold tracking-wider text-gray-400 uppercase me-2">Explore Regions:</span>
                        <button onclick="setQuickSearch('')" class="city-pill active">All Showrooms</button>
                        <button onclick="setQuickSearch('Faridabad')" class="city-pill">Faridabad</button>
                        <button onclick="setQuickSearch('Delhi')" class="city-pill">New Delhi</button>
                        <button onclick="setQuickSearch('Gurugram')" class="city-pill">Gurugram</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3-Column Boutiques Grid -->
        <div class="row row-cols-1 row-cols-md-3 g-4" id="boutiquesGrid">
            
            <!-- Column 1: Faridabad Flagship -->
            <div class="col store-grid-col" 
                 id="col-faridabad"
                 data-city="Faridabad" 
                 data-state="Haryana" 
                 data-pincode="121003">
                <div class="boutique-card-premium">
                    <div class="flex-grow">
                        <span class="bg-luxGold text-white text-[9px] font-extrabold tracking-widest uppercase px-3 py-1 rounded-md mb-4 inline-block shadow-sm">Experience Center & Office</span>
                        
                        <h3 class="font-serif italic text-2xl font-bold text-darkLux mb-3">Amadika Flagship</h3>
                        
                        <p class="text-xs text-gray-500 leading-relaxed mb-4 flex items-start gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-luxGold flex-shrink-0 mt-0.5"></i>
                            <span class="font-medium text-gray-600">A-14, DLF Industrial Area Phase 1, NHPC Crossing, Faridabad, Haryana - 121003</span>
                        </p>
                        
                        <div class="border-t border-gray-100 pt-4 mb-4 space-y-3">
                            <div class="text-xs text-gray-600">
                                <div class="font-bold text-darkLux mb-1 flex items-center gap-1.5">
                                    <i data-lucide="clock" class="w-4 h-4 text-luxGold"></i> Timings & Schedule
                                </div>
                                <p class="mb-1">Mon - Sat: 10:00 AM - 08:30 PM</p>
                                <p class="text-red-500 font-bold mb-0 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Sunday: Closed</p>
                            </div>
                            <div class="text-xs text-gray-600">
                                <div class="font-bold text-darkLux mb-1 flex items-center gap-1.5">
                                    <i data-lucide="phone" class="w-4 h-4 text-luxGold"></i> Direct Contact
                                </div>
                                <p class="mb-1 font-semibold">+91 84476 16924</p>
                                <p class="mb-0 text-gray-400">support@amadika.in</p>
                            </div>
                        </div>

                        <!-- Services Tags -->
                        <div class="flex flex-wrap gap-1.5 border-t border-gray-100/50 pt-3.5 mb-5">
                            <span class="service-badge flex items-center gap-1"><i data-lucide="package" class="w-3 h-3"></i> Full Showcase</span>
                            <span class="service-badge flex items-center gap-1"><i data-lucide="sparkles" class="w-3 h-3"></i> Hot-Stamping</span>
                            <span class="service-badge flex items-center gap-1"><i data-lucide="gift" class="w-3 h-3"></i> VIP Lounge</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-2 gap-2 mt-auto">
                        <a href="https://maps.google.com/?q=A-14,+DLF+Industrial+Area+Phase+1,+NHPC+Crossing,+Faridabad,+Haryana+-+121003" 
                           target="_blank" 
                           class="bg-darkLux hover:bg-luxGold text-white text-xs font-bold py-3 text-center rounded-xl transition-all duration-300 text-decoration-none flex items-center justify-center gap-1.5 shadow-sm">
                            <i data-lucide="navigation" class="w-4 h-4"></i> Directions
                        </a>
                        <a href="tel:+918447616924" 
                           class="bg-gray-100 hover:bg-gray-200 text-darkLux text-xs font-bold py-3 text-center rounded-xl transition-all duration-300 text-decoration-none flex items-center justify-center gap-1.5">
                            <i data-lucide="phone" class="w-4 h-4"></i> Call Store
                        </a>
                    </div>
                </div>
            </div>

            <!-- Column 2: Greater Kailash Delhi -->
            <div class="col store-grid-col" 
                 id="col-delhi"
                 data-city="Delhi" 
                 data-state="Delhi" 
                 data-pincode="110048">
                <div class="boutique-card-premium">
                    <div class="flex-grow">
                        <span class="bg-luxGold text-white text-[9px] font-extrabold tracking-widest uppercase px-3 py-1 rounded-md mb-4 inline-block shadow-sm">Luxury Boutique</span>
                        
                        <h3 class="font-serif italic text-2xl font-bold text-darkLux mb-3">Amadika Boutique - GK 1</h3>
                        
                        <p class="text-xs text-gray-500 leading-relaxed mb-4 flex items-start gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-luxGold flex-shrink-0 mt-0.5"></i>
                            <span class="font-medium text-gray-600">Shop 12, Ground Floor, M-Block Market, Greater Kailash 1, New Delhi - 110048</span>
                        </p>
                        
                        <div class="border-t border-gray-100 pt-4 mb-4 space-y-3">
                            <div class="text-xs text-gray-600">
                                <div class="font-bold text-darkLux mb-1 flex items-center gap-1.5">
                                    <i data-lucide="clock" class="w-4 h-4 text-luxGold"></i> Timings & Schedule
                                </div>
                                <p class="mb-1">Mon - Sun: 11:00 AM - 09:00 PM</p>
                                <p class="text-green-600 font-bold mb-0 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Open Everyday</p>
                            </div>
                            <div class="text-xs text-gray-600">
                                <div class="font-bold text-darkLux mb-1 flex items-center gap-1.5">
                                    <i data-lucide="phone" class="w-4 h-4 text-luxGold"></i> Direct Contact
                                </div>
                                <p class="mb-1 font-semibold">+91 84476 16924</p>
                                <p class="mb-0 text-gray-400">store.gk@amadika.in</p>
                            </div>
                        </div>

                        <!-- Services Tags -->
                        <div class="flex flex-wrap gap-1.5 border-t border-gray-100/50 pt-3.5 mb-5">
                            <span class="service-badge flex items-center gap-1"><i data-lucide="home" class="w-3 h-3"></i> Home Decor</span>
                            <span class="service-badge flex items-center gap-1"><i data-lucide="user-check" class="w-3 h-3"></i> Stylist Consult</span>
                            <span class="service-badge flex items-center gap-1"><i data-lucide="refresh-cw" class="w-3 h-3"></i> Easy Returns</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-2 gap-2 mt-auto">
                        <a href="https://maps.google.com/?q=Shop+12,+Ground+Floor,+M-Block+Market,+Greater+Kailash+1,+New+Delhi+-+110048" 
                           target="_blank" 
                           class="bg-darkLux hover:bg-luxGold text-white text-xs font-bold py-3 text-center rounded-xl transition-all duration-300 text-decoration-none flex items-center justify-center gap-1.5 shadow-sm">
                            <i data-lucide="navigation" class="w-4 h-4"></i> Directions
                        </a>
                        <a href="tel:+918447616924" 
                           class="bg-gray-100 hover:bg-gray-200 text-darkLux text-xs font-bold py-3 text-center rounded-xl transition-all duration-300 text-decoration-none flex items-center justify-center gap-1.5">
                            <i data-lucide="phone" class="w-4 h-4"></i> Call Store
                        </a>
                    </div>
                </div>
            </div>

            <!-- Column 3: Gurugram Galleria -->
            <div class="col store-grid-col" 
                 id="col-gurugram"
                 data-city="Gurugram Gurgaon" 
                 data-state="Haryana" 
                 data-pincode="122009">
                <div class="boutique-card-premium">
                    <div class="flex-grow">
                        <span class="bg-luxGold text-white text-[9px] font-extrabold tracking-widest uppercase px-3 py-1 rounded-md mb-4 inline-block shadow-sm">Premium Studio</span>
                        
                        <h3 class="font-serif italic text-2xl font-bold text-darkLux mb-3">Amadika Studio</h3>
                        
                        <p class="text-xs text-gray-500 leading-relaxed mb-4 flex items-start gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-luxGold flex-shrink-0 mt-0.5"></i>
                            <span class="font-medium text-gray-600">Unit SF-32, Galleria Market, DLF Phase 4, Gurugram, Haryana - 122009</span>
                        </p>
                        
                        <div class="border-t border-gray-100 pt-4 mb-4 space-y-3">
                            <div class="text-xs text-gray-600">
                                <div class="font-bold text-darkLux mb-1 flex items-center gap-1.5">
                                    <i data-lucide="clock" class="w-4 h-4 text-luxGold"></i> Timings & Schedule
                                </div>
                                <p class="mb-1">Mon - Sun: 11:00 AM - 09:30 PM</p>
                                <p class="text-green-600 font-bold mb-0 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Open Everyday</p>
                            </div>
                            <div class="text-xs text-gray-600">
                                <div class="font-bold text-darkLux mb-1 flex items-center gap-1.5">
                                    <i data-lucide="phone" class="w-4 h-4 text-luxGold"></i> Direct Contact
                                </div>
                                <p class="mb-1 font-semibold">+91 84476 16924</p>
                                <p class="mb-0 text-gray-400">store.ggn@amadika.in</p>
                            </div>
                        </div>

                        <!-- Services Tags -->
                        <div class="flex flex-wrap gap-1.5 border-t border-gray-100/50 pt-3.5 mb-5">
                            <span class="service-badge flex items-center gap-1"><i data-lucide="briefcase" class="w-3 h-3"></i> Desk Organizers</span>
                            <span class="service-badge flex items-center gap-1"><i data-lucide="tool" class="w-3 h-3"></i> Leather Care</span>
                            <span class="service-badge flex items-center gap-1"><i data-lucide="shopping-cart" class="w-3 h-3"></i> Reserve & Pickup</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-2 gap-2 mt-auto">
                        <a href="https://maps.google.com/?q=Unit+SF-32,+Galleria+Market,+DLF+Phase+4,+Gurugram,+Haryana+-+122009" 
                           target="_blank" 
                           class="bg-darkLux hover:bg-luxGold text-white text-xs font-bold py-3 text-center rounded-xl transition-all duration-300 text-decoration-none flex items-center justify-center gap-1.5 shadow-sm">
                            <i data-lucide="navigation" class="w-4 h-4"></i> Directions
                        </a>
                        <a href="tel:+918447616924" 
                           class="bg-gray-100 hover:bg-gray-200 text-darkLux text-xs font-bold py-3 text-center rounded-xl transition-all duration-300 text-decoration-none flex items-center justify-center gap-1.5">
                            <i data-lucide="phone" class="w-4 h-4"></i> Call Store
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Fallback: No stores match search -->
        <div id="noStoresFallback" class="hidden bg-white border border-gray-200/80 rounded-2xl p-10 text-center max-w-xl mx-auto shadow-luxury mt-8">
            <div class="w-16 h-16 bg-luxGold/10 text-luxGold rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="shopping-bag" class="w-8 h-8"></i>
            </div>
            <h4 class="font-serif italic text-xl font-bold text-darkLux mb-2">No Boutiques Found</h4>
            <p class="text-xs text-gray-500 leading-relaxed mb-6 max-w-sm mx-auto">
                We don't have a physical retail boutique in this area yet. However, we deliver our handcrafted luxury accessories worldwide with complimentary shipping on orders above ₹9,999!
            </p>
            <a href="../../products.php" class="inline-flex items-center gap-1.5 bg-luxGold hover:bg-darkLux text-white text-xs font-extrabold uppercase px-6 py-3.5 rounded-xl transition-all duration-300 text-decoration-none shadow-sm">
                Browse Online Catalog
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

    </div>
</section>

<!-- Luxury In-Store Services Showcase -->
<section class="py-20 bg-white border-t border-gray-150/40">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="text-center max-w-xl mx-auto mb-16">
            <span class="text-luxGold text-[11px] font-extrabold tracking-widest uppercase mb-2 block">Client Privileges</span>
            <h2 class="text-3xl lg:text-4xl font-serif font-bold text-darkLux">Our Bespoke In-Store Services</h2>
            <div class="gold-divider"></div>
            <p class="text-xs text-gray-500">Discover premium custom care designed for the leather connoisseur at our retail showrooms.</p>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-4 animate__animated animate__fadeInUp">
                <div class="bg-[#FCFBF8] border border-gray-150/50 rounded-2xl p-6 text-center h-full transition-all duration-300 hover:shadow-sm">
                    <div class="w-12 h-12 bg-luxGold/15 text-luxGold rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="sparkles" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-darkLux mb-2">Bespoke Monogramming</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-0">
                        Add a personal signature to your leather goods. We offer complimentary hot-stamping and custom initial engraving services in-store.
                    </p>
                </div>
            </div>
            
            <div class="col-12 col-md-4 animate__animated animate__fadeInUp animate__delay-1s">
                <div class="bg-[#FCFBF8] border border-gray-150/50 rounded-2xl p-6 text-center h-full transition-all duration-300 hover:shadow-sm">
                    <div class="w-12 h-12 bg-luxGold/15 text-luxGold rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="user-check" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-darkLux mb-2">Private Client Consultation</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-0">
                        Book a personal shopping or home decor styling session. Our consultants will help select leather accessories matching your space.
                    </p>
                </div>
            </div>

            <div class="col-12 col-md-4 animate__animated animate__fadeInUp animate__delay-2s">
                <div class="bg-[#FCFBF8] border border-gray-150/50 rounded-2xl p-6 text-center h-full transition-all duration-300 hover:shadow-sm">
                    <div class="w-12 h-12 bg-luxGold/15 text-luxGold rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="shield" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-darkLux mb-2">Lifetime Leather Care</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-0">
                        Bring your Amadika leather items to any boutique for complimentary deep cleaning, rejuvenation, conditioning, and custom polish.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- VIP Appointment Booking CTA - Spacing fixed (margin bottom added) to isolate from footer -->
<section class="py-16 bg-[#111827] text-white relative overflow-hidden">
    <div class="container max-w-5xl mx-auto px-4">
        <div class="vip-cta-section py-16 px-6 lg:px-12 text-center relative overflow-hidden bg-gradient-to-b from-darkLux to-gray-950">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_var(--tw-gradient-stops))] from-luxGold/10 via-transparent to-transparent opacity-60"></div>
            
            <div class="z-10 relative">
                <span class="text-luxGold text-[11px] font-extrabold tracking-widest uppercase mb-2 block">VIP Boutique Pass</span>
                <h2 class="font-serif italic text-3xl lg:text-4xl font-bold mb-3">Book A Showroom Styling Appointment</h2>
                <p class="text-xs text-gray-300 max-w-lg mx-auto leading-relaxed mb-8 font-light">
                    Skip the queue and book a dedicated stylist session. Get custom monogramming prioritizations and personal corporate lounge accesses.
                </p>
                <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-3">
                    <a href="https://wa.me/918447616924?text=Hi%20Amadika,%20I%20would%20like%20to%20book%20a%20private%20styling%20appointment%20at%20your%20showroom." 
                       target="_blank" 
                       class="bg-luxGold hover:bg-white hover:text-darkLux text-white text-xs font-bold px-6 py-3.5 rounded-xl transition-all duration-300 text-decoration-none shadow-md flex items-center justify-center gap-2 border-0">
                        <i class="fa-brands fa-whatsapp text-sm"></i> BOOK VIA WHATSAPP
                    </a>
                    <a href="tel:+918447616924" class="text-gray-300 hover:text-luxGold text-xs font-bold py-3.5 px-4 rounded-xl transition-all duration-300 text-decoration-none flex items-center gap-1.5">
                        <i data-lucide="phone" class="w-4 h-4"></i> CALL RESERVATIONS
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // On Load Initial Setup
    document.addEventListener('DOMContentLoaded', () => {
        // --- CRITICAL: INITIALIZE LUCIDE ICONS ON THIS PAGE ---
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Setup GSAP load animations (slide in store cards)
        if (typeof gsap !== 'undefined') {
            gsap.from(".boutique-card-premium", {
                duration: 0.8,
                y: 35,
                opacity: 0,
                stagger: 0.15,
                ease: "power2.out"
            });
        }

        // Setup real-time keyup filter search
        const searchInput = document.getElementById('storeSearchInput');
        const clearBtn = document.getElementById('clearSearchBtn');

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            filterStores(query);
            
            if (query.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        });

        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.classList.add('hidden');
            filterStores('');
            searchInput.focus();
        });
    });

    // City tag filtering helper
    function setQuickSearch(cityName) {
        // Update quick cities pill active class
        document.querySelectorAll('.city-pill').forEach(pill => {
            pill.classList.remove('active');
            if (cityName === '' && pill.textContent.includes('All')) {
                pill.classList.add('active');
            } else if (pill.textContent.toLowerCase() === cityName.toLowerCase()) {
                pill.classList.add('active');
            }
        });

        const searchInput = document.getElementById('storeSearchInput');
        searchInput.value = cityName;
        
        const clearBtn = document.getElementById('clearSearchBtn');
        if (cityName !== '') {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        filterStores(cityName.toLowerCase());
    }

    // Main filter matcher logic for 3-column layout
    function filterStores(query) {
        const columns = document.querySelectorAll('.store-grid-col');
        let matchedCount = 0;

        columns.forEach(col => {
            const cityName = col.getAttribute('data-city').toLowerCase();
            const stateName = col.getAttribute('data-state').toLowerCase();
            const pincode = col.getAttribute('data-pincode');
            const colContent = col.innerText.toLowerCase();

            // Match query in any metadata attributes or general text content
            if (cityName.includes(query) || stateName.includes(query) || pincode.includes(query) || colContent.includes(query)) {
                col.style.display = 'block';
                matchedCount++;
            } else {
                col.style.display = 'none';
            }
        });

        const fallback = document.getElementById('noStoresFallback');
        const grid = document.getElementById('boutiquesGrid');
        
        if (matchedCount === 0) {
            fallback.classList.remove('hidden');
            grid.style.display = 'none';
        } else {
            fallback.classList.add('hidden');
            grid.style.display = 'flex';
        }

        // Re-parse Lucide Icons just in case search changes rendering states
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>

<?php include '../../includes/footer.php'; ?>

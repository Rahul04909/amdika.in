<?php
// Page Setup
$page_title = "Our Boutiques & Store Finder | Amadika";
$page_description = "Step into the world of Amadika. Visit our premium handcrafted leather experience centers in Faridabad, Greater Kailash Delhi, and Galleria Gurugram.";
$page_keywords = "amadika stores, leather store near me, amadika showroom, luxury leather showroom, store locator, store finder";
include '../../includes/header.php'; 
?>

<!-- Add Animate.css for quick scroll/hover micro-animations -->
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

    /* Elegant Hero with Parallax Feel */
    .stores-hero-premium {
        position: relative;
        background: linear-gradient(180deg, rgba(17, 24, 39, 0.8) 0%, rgba(17, 24, 39, 0.9) 100%), 
                    url('../../assets/images/banners/banner-1.png') center/cover no-repeat;
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

    /* Exquisite Store Cards */
    .boutique-card {
        background: #ffffff;
        border: 1px solid rgba(229, 231, 235, 0.7);
        border-radius: 20px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .boutique-card::before {
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

    .boutique-card:hover {
        transform: translateY(-6px);
        border-color: var(--lux-gold);
        box-shadow: 0 25px 50px -12px rgba(200, 155, 44, 0.12), 0 1px 5px rgba(0, 0, 0, 0.02);
    }

    .boutique-card:hover::before {
        transform: scaleX(1);
    }

    .boutique-card.selected {
        border-color: var(--lux-gold);
        box-shadow: 0 0 0 2px var(--lux-gold), 0 20px 40px -10px rgba(200, 155, 44, 0.18);
    }

    .boutique-card.selected::before {
        transform: scaleX(1);
        background: var(--lux-gold);
    }

    /* Gold Service Badges */
    .service-badge {
        background-color: rgba(200, 155, 44, 0.06);
        color: #926f1c;
        border: 1px solid rgba(200, 155, 44, 0.15);
        font-size: 10px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 8px;
        letter-spacing: 0.02em;
    }

    /* Sepia/Beige-Toned Map Viewport */
    .map-frame-container {
        border: 1px solid var(--lux-border);
        background-color: #ffffff;
        border-radius: 24px;
        padding: 10px;
        box-shadow: var(--shadow-luxury);
        transition: all 0.3s ease;
    }

    .map-frame-container:hover {
        border-color: var(--lux-gold);
        box-shadow: var(--shadow-luxury-hover);
    }

    .luxury-map-iframe {
        border-radius: 16px;
        overflow: hidden;
        border: 0;
        /* Custom sepia/warm gold tone styling for map matching luxury brand palette */
        filter: sepia(0.2) contrast(1.05) brightness(0.96) hue-rotate(15deg);
        transition: all 0.5s ease;
    }

    .luxury-map-iframe:hover {
        filter: none; /* Turns back to normal full color when hovered */
    }

    /* Mobile Views */
    @media (max-width: 767px) {
        .stores-hero-premium {
            height: 280px;
        }
        .map-sticky-wrapper {
            position: relative !important;
            top: 0 !important;
        }
    }

    /* Sticky container on desktop */
    .map-sticky-wrapper {
        position: sticky;
        top: 90px;
        z-index: 10;
    }

    /* Section divider line */
    .gold-divider {
        width: 60px;
        height: 2px;
        background-color: var(--lux-gold);
        margin: 15px auto;
    }
</style>

<!-- Hero Banner (High-end Luxury Presentation) -->
<section class="stores-hero-premium text-white">
    <div class="container px-4 text-center z-10">
        <span class="text-[11px] font-extrabold uppercase tracking-widest text-luxGold mb-2 block animate__animated animate__fadeInDown">Amadika Retail</span>
        <h1 class="font-serif italic text-4xl lg:text-6xl font-bold tracking-wide mb-3 animate__animated animate__fadeInUp">Boutiques & Showrooms</h1>
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
        <div class="search-glass-panel p-6 lg:p-8 mb-12 max-w-4xl mx-auto text-center border-0">
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

        <!-- Mobile Layout Toggle Tabs -->
        <div class="flex border border-gray-200/60 rounded-2xl overflow-hidden mb-8 md:hidden shadow-sm">
            <button id="btnListView" class="view-toggle-btn active" onclick="toggleMobileView('list')">
                <i class="fa-solid fa-list-ul me-1.5"></i> List View
            </button>
            <button id="btnMapView" class="view-toggle-btn" onclick="toggleMobileView('map')">
                <i class="fa-solid fa-map-location-dot me-1.5"></i> Interactive Map
            </button>
        </div>

        <!-- Main Display Grid -->
        <div class="row g-4 lg:g-5">
            <!-- Left Panel: Boutique Listings -->
            <div id="listingsCol" class="col-12 col-md-6 col-lg-7 space-y-6">
                
                <!-- Card 1: Faridabad Flagship -->
                <div class="boutique-card" 
                     id="store-faridabad"
                     data-city="Faridabad" 
                     data-state="Haryana" 
                     data-pincode="121003"
                     onclick="selectStore('faridabad', 'https://maps.google.com/maps?q=28.460806,77.309394&z=15&output=embed', 'https://maps.google.com/?q=A-14,+DLF+Industrial+Area+Phase+1,+NHPC+Crossing,+Faridabad,+Haryana+-+121003')">
                    
                    <span class="bg-luxGold text-white text-[9px] font-extrabold tracking-widest uppercase px-3 py-1 rounded-md mb-4 inline-block shadow-sm">Experience Center & Office</span>
                    
                    <h3 class="font-serif italic text-2xl font-bold text-darkLux mb-2">Amadika Flagship & Office</h3>
                    
                    <p class="text-xs text-gray-500 leading-relaxed mb-4 flex items-start gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-luxGold flex-shrink-0 mt-0.5"></i>
                        <span class="font-medium text-gray-600">A-14, DLF Industrial Area Phase 1, NHPC Crossing, Faridabad, Haryana - 121003</span>
                    </p>
                    
                    <div class="row g-3 border-t border-gray-100 pt-4 mb-4">
                        <div class="col-12 col-sm-6 text-xs text-gray-600">
                            <div class="font-bold text-darkLux mb-1.5 flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-4 h-4 text-luxGold"></i> Timings & Schedule
                            </div>
                            <p class="mb-1">Mon - Sat: 10:00 AM - 08:30 PM</p>
                            <p class="text-red-500 font-bold mb-0 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Sunday: Closed</p>
                        </div>
                        <div class="col-12 col-sm-6 text-xs text-gray-600">
                            <div class="font-bold text-darkLux mb-1.5 flex items-center gap-1.5">
                                <i data-lucide="phone" class="w-4 h-4 text-luxGold"></i> Direct Contacts
                            </div>
                            <p class="mb-1 font-semibold">+91 84476 16924</p>
                            <p class="mb-0 text-gray-400">support@amadika.in</p>
                        </div>
                    </div>

                    <!-- Services Tags -->
                    <div class="flex flex-wrap gap-1.5 border-t border-gray-100/50 pt-3.5 mb-4">
                        <span class="service-badge flex items-center gap-1"><i data-lucide="package" class="w-3 h-3"></i> Full Catalog Display</span>
                        <span class="service-badge flex items-center gap-1"><i data-lucide="sparkles" class="w-3 h-3"></i> Initial Hot-Stamping</span>
                        <span class="service-badge flex items-center gap-1"><i data-lucide="gift" class="w-3 h-3"></i> Corporate Lounge</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button class="bg-darkLux hover:bg-luxGold text-white text-xs font-bold py-3 px-5 rounded-xl transition-all duration-300 border-0 flex items-center gap-2 shadow-sm" onclick="event.stopPropagation(); selectStore('faridabad', 'https://maps.google.com/maps?q=28.460806,77.309394&z=15&output=embed', 'https://maps.google.com/?q=A-14,+DLF+Industrial+Area+Phase+1,+NHPC+Crossing,+Faridabad,+Haryana+-+121003')">
                            <i data-lucide="map" class="w-4 h-4"></i> View on Map
                        </button>
                        <a href="https://maps.google.com/?q=A-14,+DLF+Industrial+Area+Phase+1,+NHPC+Crossing,+Faridabad,+Haryana+-+121003" 
                           target="_blank" 
                           class="bg-gray-100 hover:bg-gray-200 text-darkLux text-xs font-bold py-3 px-4 rounded-xl transition-all duration-300 text-decoration-none flex items-center gap-1.5"
                           onclick="event.stopPropagation();">
                            <i data-lucide="navigation" class="w-4 h-4"></i> Get Directions
                        </a>
                    </div>
                </div>

                <!-- Card 2: Greater Kailash Delhi -->
                <div class="boutique-card" 
                     id="store-delhi"
                     data-city="Delhi" 
                     data-state="Delhi" 
                     data-pincode="110048"
                     onclick="selectStore('delhi', 'https://maps.google.com/maps?q=28.5583,77.2343&z=15&output=embed', 'https://maps.google.com/?q=Shop+12,+Ground+Floor,+M-Block+Market,+Greater+Kailash+1,+New+Delhi+-+110048')">
                    
                    <span class="bg-luxGold text-white text-[9px] font-extrabold tracking-widest uppercase px-3 py-1 rounded-md mb-4 inline-block shadow-sm">Luxury Boutique</span>
                    
                    <h3 class="font-serif italic text-2xl font-bold text-darkLux mb-2">Amadika Boutique - GK 1</h3>
                    
                    <p class="text-xs text-gray-500 leading-relaxed mb-4 flex items-start gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-luxGold flex-shrink-0 mt-0.5"></i>
                        <span class="font-medium text-gray-600">Shop 12, Ground Floor, M-Block Market, Greater Kailash 1, New Delhi - 110048</span>
                    </p>
                    
                    <div class="row g-3 border-t border-gray-100 pt-4 mb-4">
                        <div class="col-12 col-sm-6 text-xs text-gray-600">
                            <div class="font-bold text-darkLux mb-1.5 flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-4 h-4 text-luxGold"></i> Timings & Schedule
                            </div>
                            <p class="mb-1">Mon - Sun: 11:00 AM - 09:00 PM</p>
                            <p class="text-green-600 font-bold mb-0 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Open Everyday</p>
                        </div>
                        <div class="col-12 col-sm-6 text-xs text-gray-600">
                            <div class="font-bold text-darkLux mb-1.5 flex items-center gap-1.5">
                                <i data-lucide="phone" class="w-4 h-4 text-luxGold"></i> Direct Contacts
                            </div>
                            <p class="mb-1 font-semibold">+91 84476 16924</p>
                            <p class="mb-0 text-gray-400">store.gk@amadika.in</p>
                        </div>
                    </div>

                    <!-- Services Tags -->
                    <div class="flex flex-wrap gap-1.5 border-t border-gray-100/50 pt-3.5 mb-4">
                        <span class="service-badge flex items-center gap-1"><i data-lucide="home" class="w-3 h-3"></i> Decor Showcase</span>
                        <span class="service-badge flex items-center gap-1"><i data-lucide="user-check" class="w-3 h-3"></i> Private Stylist Consulting</span>
                        <span class="service-badge flex items-center gap-1"><i data-lucide="refresh-cw" class="w-3 h-3"></i> Quick Boutique Return</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button class="bg-darkLux hover:bg-luxGold text-white text-xs font-bold py-3 px-5 rounded-xl transition-all duration-300 border-0 flex items-center gap-2 shadow-sm" onclick="event.stopPropagation(); selectStore('delhi', 'https://maps.google.com/maps?q=28.5583,77.2343&z=15&output=embed', 'https://maps.google.com/?q=Shop+12,+Ground+Floor,+M-Block+Market,+Greater+Kailash+1,+New+Delhi+-+110048')">
                            <i data-lucide="map" class="w-4 h-4"></i> View on Map
                        </button>
                        <a href="https://maps.google.com/?q=Shop+12,+Ground+Floor,+M-Block+Market,+Greater+Kailash+1,+New+Delhi+-+110048" 
                           target="_blank" 
                           class="bg-gray-100 hover:bg-gray-200 text-darkLux text-xs font-bold py-3 px-4 rounded-xl transition-all duration-300 text-decoration-none flex items-center gap-1.5"
                           onclick="event.stopPropagation();">
                            <i data-lucide="navigation" class="w-4 h-4"></i> Get Directions
                        </a>
                    </div>
                </div>

                <!-- Card 3: Gurugram Galleria -->
                <div class="boutique-card" 
                     id="store-gurugram"
                     data-city="Gurugram Gurgaon" 
                     data-state="Haryana" 
                     data-pincode="122009"
                     onclick="selectStore('gurugram', 'https://maps.google.com/maps?q=28.4671,77.0817&z=15&output=embed', 'https://maps.google.com/?q=Unit+SF-32,+Galleria+Market,+DLF+Phase+4,+Gurugram,+Haryana+-+122009')">
                    
                    <span class="bg-luxGold text-white text-[9px] font-extrabold tracking-widest uppercase px-3 py-1 rounded-md mb-4 inline-block shadow-sm">Premium Studio</span>
                    
                    <h3 class="font-serif italic text-2xl font-bold text-darkLux mb-2">Amadika Premium Studio</h3>
                    
                    <p class="text-xs text-gray-500 leading-relaxed mb-4 flex items-start gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-luxGold flex-shrink-0 mt-0.5"></i>
                        <span class="font-medium text-gray-600">Unit SF-32, Galleria Market, DLF Phase 4, Gurugram, Haryana - 122009</span>
                    </p>
                    
                    <div class="row g-3 border-t border-gray-100 pt-4 mb-4">
                        <div class="col-12 col-sm-6 text-xs text-gray-600">
                            <div class="font-bold text-darkLux mb-1.5 flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-4 h-4 text-luxGold"></i> Timings & Schedule
                            </div>
                            <p class="mb-1">Mon - Sun: 11:00 AM - 09:30 PM</p>
                            <p class="text-green-600 font-bold mb-0 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Open Everyday</p>
                        </div>
                        <div class="col-12 col-sm-6 text-xs text-gray-600">
                            <div class="font-bold text-darkLux mb-1.5 flex items-center gap-1.5">
                                <i data-lucide="phone" class="w-4 h-4 text-luxGold"></i> Direct Contacts
                            </div>
                            <p class="mb-1 font-semibold">+91 84476 16924</p>
                            <p class="mb-0 text-gray-400">store.ggn@amadika.in</p>
                        </div>
                    </div>

                    <!-- Services Tags -->
                    <div class="flex flex-wrap gap-1.5 border-t border-gray-100/50 pt-3.5 mb-4">
                        <span class="service-badge flex items-center gap-1"><i data-lucide="briefcase" class="w-3 h-3"></i> Desk Organizer suites</span>
                        <span class="service-badge flex items-center gap-1"><i data-lucide="tool" class="w-3 h-3"></i> Leather Polish Service</span>
                        <span class="service-badge flex items-center gap-1"><i data-lucide="shopping-cart" class="w-3 h-3"></i> Boutique Pickup</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button class="bg-darkLux hover:bg-luxGold text-white text-xs font-bold py-3 px-5 rounded-xl transition-all duration-300 border-0 flex items-center gap-2 shadow-sm" onclick="event.stopPropagation(); selectStore('gurugram', 'https://maps.google.com/maps?q=28.4671,77.0817&z=15&output=embed', 'https://maps.google.com/?q=Unit+SF-32,+Galleria+Market,+DLF+Phase+4,+Gurugram,+Haryana+-+122009')">
                            <i data-lucide="map" class="w-4 h-4"></i> View on Map
                        </button>
                        <a href="https://maps.google.com/?q=Unit+SF-32,+Galleria+Market,+DLF+Phase+4,+Gurugram,+Haryana+-+122009" 
                           target="_blank" 
                           class="bg-gray-100 hover:bg-gray-200 text-darkLux text-xs font-bold py-3 px-4 rounded-xl transition-all duration-300 text-decoration-none flex items-center gap-1.5"
                           onclick="event.stopPropagation();">
                            <i data-lucide="navigation" class="w-4 h-4"></i> Get Directions
                        </a>
                    </div>
                </div>

                <!-- Fallback: No stores match search -->
                <div id="noStoresFallback" class="hidden bg-white border border-gray-200/80 rounded-2xl p-8 text-center shadow-luxury">
                    <div class="w-16 h-16 bg-luxGold/10 text-luxGold rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="shopping-bag" class="w-8 h-8"></i>
                    </div>
                    <h4 class="font-serif italic text-xl font-bold text-darkLux mb-2">No Boutiques Found</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-6 max-w-sm mx-auto">
                        We don't have a physical retail boutique in this area yet. However, we deliver our handcrafted luxury accessories worldwide with complimentary shipping on orders above ₹9,999!
                    </p>
                    <a href="../../products.php" class="inline-flex items-center gap-1.5 bg-luxGold hover:bg-darkLux text-white text-xs font-extrabold uppercase px-6 py-3.5 rounded-xl transition-all duration-300 text-decoration-none shadow-sm">
                        Browse Online Collections
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Right Panel: Sticky Map Viewport -->
            <div id="mapCol" class="col-12 col-md-6 col-lg-5">
                <div class="map-sticky-wrapper">
                    <div class="map-frame-container">
                        <!-- Skeleton loader while swapping src -->
                        <div id="mapLoader" class="map-loader rounded-2xl">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Syncing Map...</span>
                            </div>
                        </div>
                        <iframe id="storeMapIframe" 
                                class="luxury-map-iframe"
                                src="https://maps.google.com/maps?q=28.460806,77.309394&z=15&output=embed" 
                                width="100%" 
                                height="460" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                    <!-- Dynamic Navigation Bar below map -->
                    <div class="mt-4 bg-[#111827] text-white rounded-2xl p-4 flex items-center justify-between shadow-md border border-gray-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-luxGold/20 text-luxGold rounded-full flex items-center justify-center">
                                <i data-lucide="navigation-2" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h6 class="text-xs font-bold mb-0.5 text-white">Navigate with GPS</h6>
                                <p class="text-[10px] text-gray-400 mb-0">Open exact route in Apple or Google Maps</p>
                            </div>
                        </div>
                        <a id="gpsDirectionsBtn" 
                           href="https://maps.google.com/?q=A-14,+DLF+Industrial+Area+Phase+1,+NHPC+Crossing,+Faridabad,+Haryana+-+121003" 
                           target="_blank" 
                           class="bg-luxGold hover:bg-white hover:text-darkLux text-white text-[11px] font-extrabold px-4 py-2.5 rounded-xl transition-all duration-300 text-decoration-none shadow-sm">
                            LAUNCH NAVIGATION
                        </a>
                    </div>
                </div>
            </div>
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
            <div class="col-12 col-md-4">
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
            
            <div class="col-12 col-md-4">
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

            <div class="col-12 col-md-4">
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

<!-- VIP Appointment Booking Call to Action (High-End UX Detail) -->
<section class="py-16 bg-[#111827] text-white border-t border-luxGold/30 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-luxGold/10 via-transparent to-transparent opacity-60"></div>
    <div class="container max-w-5xl mx-auto px-4 text-center z-10 relative">
        <span class="text-luxGold text-[11px] font-extrabold tracking-widest uppercase mb-2 block">VIP Boutique Pass</span>
        <h2 class="font-serif italic text-3xl lg:text-4xl font-bold mb-3">Book A Showroom Styling Appointment</h2>
        <p class="text-xs text-gray-300 max-w-lg mx-auto leading-relaxed mb-6 font-light">
            Skip the queue and book a dedicated stylist session. Get custom monogramming prioritizations and personal corporate lounge accesses.
        </p>
        <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-3">
            <a href="https://wa.me/918447616924?text=Hi%20Amadika,%20I%20would%20like%20to%20book%20a%20private%20styling%20appointment%20at%20your%20showroom." 
               target="_blank" 
               class="bg-luxGold hover:bg-white hover:text-darkLux text-white text-xs font-bold px-6 py-3.5 rounded-xl transition-all duration-300 text-decoration-none shadow-md flex items-center gap-2">
                <i class="fa-brands fa-whatsapp text-sm"></i> BOOK VIA WHATSAPP
            </a>
            <a href="tel:+918447616924" class="text-gray-300 hover:text-luxGold text-xs font-bold py-3.5 px-4 rounded-xl transition-all duration-300 text-decoration-none flex items-center gap-1.5">
                <i data-lucide="phone" class="w-4 h-4"></i> CALL RESERVATIONS
            </a>
        </div>
    </div>
</section>

<!-- JSON-LD Local Business SEO Schemas (Dynamic indexing for search engines) -->
<script type="application/ld+json">
[
  {
    "@context": "https://schema.org",
    "@type": "Store",
    "name": "Amadika Flagship & Office",
    "image": "https://amadika.in/assets/images/amdika-logo.png",
    "@id": "https://amadika.in/pages/our-stores/#faridabad",
    "url": "https://amadika.in/pages/our-stores/",
    "telephone": "+918447616924",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "A-14, DLF Industrial Area Phase 1, NHPC Crossing",
      "addressLocality": "Faridabad",
      "addressRegion": "Haryana",
      "postalCode": "121003",
      "addressCountry": "IN"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": 28.460806,
      "longitude": 77.309394
    },
    "openingHoursSpecification": {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
      "opens": "10:00",
      "closes": "20:30"
    }
  },
  {
    "@context": "https://schema.org",
    "@type": "Store",
    "name": "Amadika Boutique - GK 1",
    "image": "https://amadika.in/assets/images/amdika-logo.png",
    "@id": "https://amadika.in/pages/our-stores/#delhi",
    "url": "https://amadika.in/pages/our-stores/",
    "telephone": "+918447616924",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Shop 12, Ground Floor, M-Block Market, Greater Kailash 1",
      "addressLocality": "New Delhi",
      "addressRegion": "Delhi",
      "postalCode": "110048",
      "addressCountry": "IN"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": 28.5583,
      "longitude": 77.2343
    },
    "openingHoursSpecification": {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
      "opens": "11:00",
      "closes": "21:00"
    }
  },
  {
    "@context": "https://schema.org",
    "@type": "Store",
    "name": "Amadika Premium Studio - Gurugram",
    "image": "https://amadika.in/assets/images/amdika-logo.png",
    "@id": "https://amadika.in/pages/our-stores/#gurugram",
    "url": "https://amadika.in/pages/our-stores/",
    "telephone": "+918447616924",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Unit SF-32, Galleria Market, DLF Phase 4",
      "addressLocality": "Gurugram",
      "addressRegion": "Haryana",
      "postalCode": "122009",
      "addressCountry": "IN"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": 28.4671,
      "longitude": 77.0817
    },
    "openingHoursSpecification": {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
      "opens": "11:00",
      "closes": "21:30"
    }
  }
]
</script>

<script>
    // Selected store track variable
    let activeStoreId = 'faridabad';

    // On Load Initial Setup
    document.addEventListener('DOMContentLoaded', () => {
        // Mark first store card as active/selected
        document.getElementById('store-faridabad').classList.add('selected');
        
        // --- CRITICAL: INITIALIZE LUCIDE ICONS ON THIS PAGE ---
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Setup GSAP load animations (slide in store cards)
        if (typeof gsap !== 'undefined') {
            gsap.from(".boutique-card", {
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

    // Function to swap active store on the list and load target iframe map
    function selectStore(storeId, mapUrl, directionsUrl) {
        // Toggle selected styling classes
        document.querySelectorAll('.boutique-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        const selectedCard = document.getElementById(`store-${storeId}`);
        if (selectedCard) {
            selectedCard.classList.add('selected');
        }

        activeStoreId = storeId;

        // Show smooth loader on map
        const loader = document.getElementById('mapLoader');
        const iframe = document.getElementById('storeMapIframe');
        
        loader.classList.add('loading');
        
        // Change Iframe source
        iframe.src = mapUrl;
        
        // Update GPS direction button target
        document.getElementById('gpsDirectionsBtn').href = directionsUrl;

        // Wait for iframe load to clear spinner loader
        iframe.onload = () => {
            loader.classList.remove('loading');
        };

        // Scroll map into view on mobile
        if (window.innerWidth < 768) {
            toggleMobileView('map');
            document.getElementById('mapCol').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

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

    // Main filter matcher logic
    function filterStores(query) {
        const cards = document.querySelectorAll('.boutique-card');
        let matchedCount = 0;
        let firstMatchedStore = null;

        cards.forEach(card => {
            const cityName = card.getAttribute('data-city').toLowerCase();
            const stateName = card.getAttribute('data-state').toLowerCase();
            const pincode = card.getAttribute('data-pincode');
            const cardContent = card.innerText.toLowerCase();

            // Match query in any metadata attributes or general text content
            if (cityName.includes(query) || stateName.includes(query) || pincode.includes(query) || cardContent.includes(query)) {
                card.style.display = 'block';
                matchedCount++;
                if (!firstMatchedStore) {
                    firstMatchedStore = card;
                }
            } else {
                card.style.display = 'none';
            }
        });

        const fallback = document.getElementById('noStoresFallback');
        if (matchedCount === 0) {
            fallback.classList.remove('hidden');
            document.getElementById('mapCol').style.display = 'none';
        } else {
            fallback.classList.add('hidden');
            document.getElementById('mapCol').style.display = 'block';
            
            // Auto focus/select first matched store if current selected one is hidden
            const currentSelected = document.getElementById(`store-${activeStoreId}`);
            if (currentSelected && currentSelected.style.display === 'none') {
                // Trigger click on first matched card
                firstMatchedStore.click();
            }
        }

        // Re-parse Lucide Icons just in case search changes rendering states
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Toggle between list view and map view on mobile layout
    function toggleMobileView(viewType) {
        const listBtn = document.getElementById('btnListView');
        const mapBtn = document.getElementById('btnMapView');
        const listCol = document.getElementById('listingsCol');
        const mapCol = document.getElementById('mapCol');

        if (viewType === 'list') {
            listBtn.classList.add('active');
            mapBtn.classList.remove('active');
            listCol.style.display = 'block';
            mapCol.style.display = 'none';
        } else {
            mapBtn.classList.add('active');
            listBtn.classList.remove('active');
            mapCol.style.display = 'block';
            listCol.style.display = 'none';
            
            // Trigger redraw/load of map iframe to resolve rendering issues in hidden elements
            const iframe = document.getElementById('storeMapIframe');
            iframe.src = iframe.src;
        }
    }

    // Handle resize adjustments to prevent sticky states from breaking layout views
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            document.getElementById('listingsCol').style.display = 'block';
            document.getElementById('mapCol').style.display = 'block';
        } else {
            // Restore active state view defaults on small screens
            const isMapActive = document.getElementById('btnMapView').classList.contains('active');
            toggleMobileView(isMapActive ? 'map' : 'list');
        }
    });
</script>

<?php include '../../includes/footer.php'; ?>

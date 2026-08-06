<?php
// Page Setup
$page_title = "Our Stores | Store Finder - Amadika";
$page_description = "Find Amadika premium leather stores near you. Visit our flagship experience centers in Faridabad, Greater Kailash Delhi, and Galleria Gurugram to experience handcrafted leather luxury.";
$page_keywords = "amadika stores, leather store near me, amadika faridabad, amadika delhi, amadika gurugram, premium leather showroom, store locator, store finder";
include '../../includes/header.php'; 
?>

<style>
    /* --- Premium Store Finder Styles --- */
    .stores-hero {
        background: linear-gradient(rgba(17, 24, 39, 0.75), rgba(17, 24, 39, 0.85)), url('../../assets/images/banners/banner-1.png') center/cover no-repeat;
        height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
    }
    
    .search-pill {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .search-pill:hover, .search-pill.active {
        background-color: #C89B2C;
        color: #ffffff !important;
        border-color: #C89B2C;
    }

    .store-card {
        border: 1px solid rgba(229, 231, 235, 0.6);
        background: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .store-card:hover {
        transform: translateY(-4px);
        border-color: #C89B2C;
        box-shadow: 0 20px 40px -15px rgba(200, 155, 44, 0.15), 0 1px 5px rgba(0, 0, 0, 0.05);
    }

    .store-card.selected {
        border-color: #C89B2C;
        box-shadow: 0 0 0 2px #C89B2C, 0 10px 30px -10px rgba(200, 155, 44, 0.2);
    }

    .map-sticky-container {
        position: sticky;
        top: 80px;
        z-index: 10;
    }

    /* Map skeleton loading state */
    .map-wrapper {
        position: relative;
        background: #f3f4f6;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(229, 231, 235, 0.8);
    }

    .map-loader {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 5;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .map-loader.loading {
        opacity: 1;
        pointer-events: auto;
    }

    /* Tab styles for mobile */
    .view-toggle-btn {
        flex: 1;
        text-align: center;
        padding: 12px;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        border: none;
        background: #f3f4f6;
        color: #4b5563;
        transition: all 0.2s ease;
    }

    .view-toggle-btn.active {
        background: #111827;
        color: #ffffff;
    }
</style>

<!-- Hero Section -->
<section class="stores-hero">
    <div class="container px-4">
        <h1 class="font-serif italic text-4xl lg:text-5xl font-semibold mb-3 tracking-wide" data-aos="fade-up">Our Boutiques & Stores</h1>
        <p class="text-sm lg:text-base text-gray-300 font-light max-w-xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Step into the world of Amadika. Explore our handcrafted collections, experience fine leather textures, and enjoy personalized bespoke services.
        </p>
    </div>
</section>

<!-- Main Store Finder Workspace -->
<section class="py-12 lg:py-20 bg-[#FCFBF8]">
    <div class="container max-w-7xl mx-auto px-4">
        
        <!-- Search & Filter Controls -->
        <div class="mb-10 text-center max-w-2xl mx-auto">
            <h2 class="text-2xl lg:text-3xl font-serif font-bold text-darkLux mb-4">Find a Store Near You</h2>
            
            <!-- Search bar with clear button -->
            <div class="relative flex items-center bg-white border border-gray-200 rounded-full shadow-luxury focus-within:border-luxGold focus-within:ring-2 focus-within:ring-luxGold/15 transition-all duration-300 overflow-hidden h-12 mb-6 px-4">
                <i data-lucide="search" class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0"></i>
                <input type="text" 
                       id="storeSearchInput" 
                       class="bg-transparent border-0 text-sm w-full text-gray-700 focus:outline-none placeholder-gray-400" 
                       placeholder="Enter city, state, or pincode (e.g. Delhi, 121003)..." 
                       autocomplete="off">
                <button id="clearSearchBtn" class="hidden text-gray-400 hover:text-darkLux focus:outline-none bg-transparent border-0 p-1">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Quick Filter Cities -->
            <div class="flex flex-wrap justify-center gap-2">
                <span class="text-xs text-gray-400 font-bold tracking-wider uppercase flex items-center mr-2">Quick Cities:</span>
                <button onclick="setQuickSearch('')" class="search-pill active text-xs font-semibold px-4 py-1.5 rounded-full border border-gray-200 bg-white text-gray-600 focus:outline-none">All Stores</button>
                <button onclick="setQuickSearch('Faridabad')" class="search-pill text-xs font-semibold px-4 py-1.5 rounded-full border border-gray-200 bg-white text-gray-600 focus:outline-none">Faridabad</button>
                <button onclick="setQuickSearch('Delhi')" class="search-pill text-xs font-semibold px-4 py-1.5 rounded-full border border-gray-200 bg-white text-gray-600 focus:outline-none">New Delhi</button>
                <button onclick="setQuickSearch('Gurugram')" class="search-pill text-xs font-semibold px-4 py-1.5 rounded-full border border-gray-200 bg-white text-gray-600 focus:outline-none">Gurugram</button>
            </div>
        </div>

        <!-- Mobile View Toggle (Sticky below controls on small screens) -->
        <div class="flex border border-gray-200 rounded-xl overflow-hidden mb-6 md:hidden shadow-sm">
            <button id="btnListView" class="view-toggle-btn active" onclick="toggleMobileView('list')">
                <i class="fa-solid fa-list me-1"></i> List View
            </button>
            <button id="btnMapView" class="view-toggle-btn" onclick="toggleMobileView('map')">
                <i class="fa-solid fa-map-location-dot me-1"></i> Map View
            </button>
        </div>

        <!-- Stores Layout Grid -->
        <div class="row g-4 lg:g-5">
            <!-- Left Side: Listings -->
            <div id="listingsCol" class="col-12 col-md-6 col-lg-7 space-y-6">
                <!-- Store 1: Faridabad Flagship -->
                <div class="store-card rounded-2xl p-5 lg:p-6 cursor-pointer" 
                     id="store-faridabad"
                     data-city="Faridabad" 
                     data-state="Haryana" 
                     data-pincode="121003"
                     onclick="selectStore('faridabad', 'https://maps.google.com/maps?q=28.460806,77.309394&z=15&output=embed', 'https://maps.google.com/?q=A-14,+DLF+Industrial+Area+Phase+1,+NHPC+Crossing,+Faridabad,+Haryana+-+121003')">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <span class="bg-luxGold/10 text-luxGold text-[10px] font-bold tracking-widest uppercase px-3 py-1 rounded-full mb-3 inline-block">Flagship Experience Center</span>
                            <h3 class="text-xl font-bold text-darkLux mb-2">Amadika Flagship & Office</h3>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4 flex items-start gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-luxGold flex-shrink-0 mt-0.5"></i>
                                <span>A-14, DLF Industrial Area Phase 1, NHPC Crossing, Faridabad, Haryana - 121003</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 border-t border-gray-100 pt-4 mb-4">
                        <div class="text-xs text-gray-600">
                            <div class="font-bold text-darkLux mb-1 flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-luxGold"></i> Timings
                            </div>
                            <p class="mb-0">Mon - Sat: 10:00 AM - 08:30 PM</p>
                            <p class="text-red-500 font-semibold mb-0">Sunday: Closed</p>
                        </div>
                        <div class="text-xs text-gray-600">
                            <div class="font-bold text-darkLux mb-1 flex items-center gap-1.5">
                                <i data-lucide="phone" class="w-3.5 h-3.5 text-luxGold"></i> Contact Info
                            </div>
                            <p class="mb-0">+91 84476 16924</p>
                            <p class="mb-0">support@amadika.in</p>
                        </div>
                    </div>

                    <!-- Highlight services -->
                    <div class="flex flex-wrap gap-2 border-t border-gray-50 pt-3 mb-4">
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2.5 py-1 rounded-lg font-bold flex items-center gap-1">
                            <i data-lucide="award" class="w-3 h-3 text-luxGold"></i> Full Catalog Showcase
                        </span>
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2.5 py-1 rounded-lg font-bold flex items-center gap-1">
                            <i data-lucide="sparkles" class="w-3 h-3 text-luxGold"></i> Custom Monogramming
                        </span>
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2.5 py-1 rounded-lg font-bold flex items-center gap-1">
                            <i data-lucide="gift" class="w-3 h-3 text-luxGold"></i> Corporate Gifting Lounge
                        </span>
                    </div>

                    <div class="flex gap-2">
                        <button class="bg-darkLux hover:bg-luxGold text-white text-xs font-bold py-2.5 px-4 rounded-xl transition-all duration-300 border-0 flex items-center gap-1.5 shadow-sm" onclick="event.stopPropagation(); selectStore('faridabad', 'https://maps.google.com/maps?q=28.460806,77.309394&z=15&output=embed', 'https://maps.google.com/?q=A-14,+DLF+Industrial+Area+Phase+1,+NHPC+Crossing,+Faridabad,+Haryana+-+121003')">
                            <i data-lucide="map" class="w-4 h-4"></i> View on Map
                        </button>
                        <a href="https://maps.google.com/?q=A-14,+DLF+Industrial+Area+Phase+1,+NHPC+Crossing,+Faridabad,+Haryana+-+121003" 
                           target="_blank" 
                           class="bg-gray-100 hover:bg-gray-200 text-darkLux text-xs font-bold py-2.5 px-4 rounded-xl transition-all duration-300 text-decoration-none flex items-center gap-1.5"
                           onclick="event.stopPropagation();">
                            <i data-lucide="navigation" class="w-4 h-4"></i> Get Directions
                        </a>
                    </div>
                </div>

                <!-- Store 2: Greater Kailash New Delhi -->
                <div class="store-card rounded-2xl p-5 lg:p-6 cursor-pointer" 
                     id="store-delhi"
                     data-city="Delhi" 
                     data-state="Delhi" 
                     data-pincode="110048"
                     onclick="selectStore('delhi', 'https://maps.google.com/maps?q=28.5583,77.2343&z=15&output=embed', 'https://maps.google.com/?q=Shop+12,+Ground+Floor,+M-Block+Market,+Greater+Kailash+1,+New+Delhi+-+110048')">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <span class="bg-luxGold/10 text-luxGold text-[10px] font-bold tracking-widest uppercase px-3 py-1 rounded-full mb-3 inline-block">Luxury Boutique</span>
                            <h3 class="text-xl font-bold text-darkLux mb-2">Amadika Boutique - GK 1</h3>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4 flex items-start gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-luxGold flex-shrink-0 mt-0.5"></i>
                                <span>Shop 12, Ground Floor, M-Block Market, Greater Kailash 1, New Delhi - 110048</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 border-t border-gray-100 pt-4 mb-4">
                        <div class="text-xs text-gray-600">
                            <div class="font-bold text-darkLux mb-1 flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-luxGold"></i> Timings
                            </div>
                            <p class="mb-0">Mon - Sun: 11:00 AM - 09:00 PM</p>
                            <p class="text-green-600 font-semibold mb-0">Open Daily</p>
                        </div>
                        <div class="text-xs text-gray-600">
                            <div class="font-bold text-darkLux mb-1 flex items-center gap-1.5">
                                <i data-lucide="phone" class="w-3.5 h-3.5 text-luxGold"></i> Contact Info
                            </div>
                            <p class="mb-0">+91 84476 16924</p>
                            <p class="mb-0">store.gk@amadika.in</p>
                        </div>
                    </div>

                    <!-- Highlight services -->
                    <div class="flex flex-wrap gap-2 border-t border-gray-50 pt-3 mb-4">
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2.5 py-1 rounded-lg font-bold flex items-center gap-1">
                            <i data-lucide="home" class="w-3 h-3 text-luxGold"></i> Home Decor Showcase
                        </span>
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2.5 py-1 rounded-lg font-bold flex items-center gap-1">
                            <i data-lucide="user-check" class="w-3 h-3 text-luxGold"></i> Personal Stylist Consultations
                        </span>
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2.5 py-1 rounded-lg font-bold flex items-center gap-1">
                            <i data-lucide="refresh-cw" class="w-3 h-3 text-luxGold"></i> Easy In-store Returns
                        </span>
                    </div>

                    <div class="flex gap-2">
                        <button class="bg-darkLux hover:bg-luxGold text-white text-xs font-bold py-2.5 px-4 rounded-xl transition-all duration-300 border-0 flex items-center gap-1.5 shadow-sm" onclick="event.stopPropagation(); selectStore('delhi', 'https://maps.google.com/maps?q=28.5583,77.2343&z=15&output=embed', 'https://maps.google.com/?q=Shop+12,+Ground+Floor,+M-Block+Market,+Greater+Kailash+1,+New+Delhi+-+110048')">
                            <i data-lucide="map" class="w-4 h-4"></i> View on Map
                        </button>
                        <a href="https://maps.google.com/?q=Shop+12,+Ground+Floor,+M-Block+Market,+Greater+Kailash+1,+New+Delhi+-+110048" 
                           target="_blank" 
                           class="bg-gray-100 hover:bg-gray-200 text-darkLux text-xs font-bold py-2.5 px-4 rounded-xl transition-all duration-300 text-decoration-none flex items-center gap-1.5"
                           onclick="event.stopPropagation();">
                            <i data-lucide="navigation" class="w-4 h-4"></i> Get Directions
                        </a>
                    </div>
                </div>

                <!-- Store 3: Gurugram Galleria -->
                <div class="store-card rounded-2xl p-5 lg:p-6 cursor-pointer" 
                     id="store-gurugram"
                     data-city="Gurugram Gurgaon" 
                     data-state="Haryana" 
                     data-pincode="122009"
                     onclick="selectStore('gurugram', 'https://maps.google.com/maps?q=28.4671,77.0817&z=15&output=embed', 'https://maps.google.com/?q=Unit+SF-32,+Galleria+Market,+DLF+Phase+4,+Gurugram,+Haryana+-+122009')">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <span class="bg-luxGold/10 text-luxGold text-[10px] font-bold tracking-widest uppercase px-3 py-1 rounded-full mb-3 inline-block">Premium Studio</span>
                            <h3 class="text-xl font-bold text-darkLux mb-2">Amadika Premium Studio</h3>
                            <p class="text-xs text-gray-500 leading-relaxed mb-4 flex items-start gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-luxGold flex-shrink-0 mt-0.5"></i>
                                <span>Unit SF-32, Galleria Market, DLF Phase 4, Gurugram, Haryana - 122009</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 border-t border-gray-100 pt-4 mb-4">
                        <div class="text-xs text-gray-600">
                            <div class="font-bold text-darkLux mb-1 flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-luxGold"></i> Timings
                            </div>
                            <p class="mb-0">Mon - Sun: 11:00 AM - 09:30 PM</p>
                            <p class="text-green-600 font-semibold mb-0">Open Daily</p>
                        </div>
                        <div class="text-xs text-gray-600">
                            <div class="font-bold text-darkLux mb-1 flex items-center gap-1.5">
                                <i data-lucide="phone" class="w-3.5 h-3.5 text-luxGold"></i> Contact Info
                            </div>
                            <p class="mb-0">+91 84476 16924</p>
                            <p class="mb-0">store.ggn@amadika.in</p>
                        </div>
                    </div>

                    <!-- Highlight services -->
                    <div class="flex flex-wrap gap-2 border-t border-gray-50 pt-3 mb-4">
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2.5 py-1 rounded-lg font-bold flex items-center gap-1">
                            <i data-lucide="briefcase" class="w-3 h-3 text-luxGold"></i> Valet & Organizer Suites
                        </span>
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2.5 py-1 rounded-lg font-bold flex items-center gap-1">
                            <i data-lucide="tool" class="w-3 h-3 text-luxGold"></i> Complimentary Leather Care & Polish
                        </span>
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2.5 py-1 rounded-lg font-bold flex items-center gap-1">
                            <i data-lucide="shopping-cart" class="w-3 h-3 text-luxGold"></i> Reserve & Pickup
                        </span>
                    </div>

                    <div class="flex gap-2">
                        <button class="bg-darkLux hover:bg-luxGold text-white text-xs font-bold py-2.5 px-4 rounded-xl transition-all duration-300 border-0 flex items-center gap-1.5 shadow-sm" onclick="event.stopPropagation(); selectStore('gurugram', 'https://maps.google.com/maps?q=28.4671,77.0817&z=15&output=embed', 'https://maps.google.com/?q=Unit+SF-32,+Galleria+Market,+DLF+Phase+4,+Gurugram,+Haryana+-+122009')">
                            <i data-lucide="map" class="w-4 h-4"></i> View on Map
                        </button>
                        <a href="https://maps.google.com/?q=Unit+SF-32,+Galleria+Market,+DLF+Phase+4,+Gurugram,+Haryana+-+122009" 
                           target="_blank" 
                           class="bg-gray-100 hover:bg-gray-200 text-darkLux text-xs font-bold py-2.5 px-4 rounded-xl transition-all duration-300 text-decoration-none flex items-center gap-1.5"
                           onclick="event.stopPropagation();">
                            <i data-lucide="navigation" class="w-4 h-4"></i> Get Directions
                        </a>
                    </div>
                </div>

                <!-- Fallback: No stores match search -->
                <div id="noStoresFallback" class="hidden bg-white border border-gray-200 rounded-2xl p-8 text-center shadow-sm">
                    <div class="w-16 h-16 bg-luxGold/10 text-luxGold rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="shopping-bag" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-lg font-bold text-darkLux mb-2">No Stores Found</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-6 max-w-sm mx-auto">
                        We don't have a physical retail store in this area yet. However, we deliver our handcrafted luxury accessories worldwide with complementary shipping on orders above ₹9,999!
                    </p>
                    <a href="../../products.php" class="inline-flex items-center gap-1.5 bg-luxGold hover:bg-darkLux text-white text-xs font-extrabold uppercase px-6 py-3 rounded-xl transition-all duration-300 text-decoration-none shadow-sm">
                        Shop Our Online Catalog
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Right Side: Sticky Map Embed -->
            <div id="mapCol" class="col-12 col-md-6 col-lg-5">
                <div class="map-sticky-container">
                    <div class="map-wrapper">
                        <!-- Skeleton loader while swapping src -->
                        <div id="mapLoader" class="map-loader">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Loading Map...</span>
                            </div>
                        </div>
                        <iframe id="storeMapIframe" 
                                src="https://maps.google.com/maps?q=28.460806,77.309394&z=15&output=embed" 
                                width="100%" 
                                height="480" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                    <!-- Get directions shortcut below map -->
                    <div class="mt-4 bg-darkLux text-white rounded-2xl p-4 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-luxGold/20 text-luxGold rounded-full flex items-center justify-center">
                                <i data-lucide="navigation-2" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h6 class="text-xs font-bold mb-0.5 text-white">Navigating with GPS?</h6>
                                <p class="text-[10px] text-gray-300 mb-0">Open current store route in maps app.</p>
                            </div>
                        </div>
                        <a id="gpsDirectionsBtn" 
                           href="https://maps.google.com/?q=A-14,+DLF+Industrial+Area+Phase+1,+NHPC+Crossing,+Faridabad,+Haryana+-+121003" 
                           target="_blank" 
                           class="bg-luxGold hover:bg-white hover:text-darkLux text-white text-[11px] font-extrabold px-4 py-2.5 rounded-xl transition-all duration-300 text-decoration-none">
                            ROUTE ME
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- In-store Premium Services Showcase -->
<section class="py-16 bg-white border-t border-gray-100">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="text-center max-w-xl mx-auto mb-12">
            <h5 class="text-luxGold text-[11px] font-bold tracking-widest uppercase mb-2">Signature Experience</h5>
            <h2 class="text-3xl font-serif font-bold text-darkLux">In-Store Clients Privileges</h2>
            <p class="text-xs text-gray-500 mt-2">Discover premium complimentary customer care designed for the leather connoisseur at our retail boutiques.</p>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="bg-[#FCFBF8] border border-gray-150/60 rounded-2xl p-6 text-center h-full transition-transform hover:-translate-y-1 duration-300">
                    <div class="w-12 h-12 bg-luxGold/10 text-luxGold rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="sparkles" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-darkLux mb-2">Bespoke Monogramming</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-0">
                        Add a personal touch to your leather items. We offer complimentary hot-stamping & initial embossing services while you wait.
                    </p>
                </div>
            </div>
            
            <div class="col-12 col-md-4">
                <div class="bg-[#FCFBF8] border border-gray-150/60 rounded-2xl p-6 text-center h-full transition-transform hover:-translate-y-1 duration-300">
                    <div class="w-12 h-12 bg-luxGold/10 text-luxGold rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="user-check" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-darkLux mb-2">Private Client Consultations</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-0">
                        Book a personal styling or decor session. Our expert consultants will help select custom leather products tailored for your space.
                    </p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="bg-[#FCFBF8] border border-gray-150/60 rounded-2xl p-6 text-center h-full transition-transform hover:-translate-y-1 duration-300">
                    <div class="w-12 h-12 bg-luxGold/10 text-luxGold rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="shield" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-bold text-darkLux mb-2">Lifetime Leather Care</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-0">
                        Bring your Amadika leather bags, folders, or trays to any boutique for complimentary deep cleaning, conditioning, and polishing services.
                    </p>
                </div>
            </div>
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
        
        // Setup GSAP load animations (slide in store cards)
        if (typeof gsap !== 'undefined') {
            gsap.from(".store-card", {
                duration: 0.8,
                y: 30,
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
        document.querySelectorAll('.store-card').forEach(card => {
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
        document.querySelectorAll('.search-pill').forEach(pill => {
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
        const cards = document.querySelectorAll('.store-card');
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

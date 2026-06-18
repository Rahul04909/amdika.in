<?php
require_once 'database/db_config.php';
$page_title = "Corporate Gifting | Amadika Premium Leather";
$page_description = "Premium corporate leather gifting by Amadika. Debossed leather sets, custom notebooks, desk mats, and curated luxury gift hampers.";
include 'includes/header.php';
?>

<style>
    /* Premium Dribbble-Inspired Showcase styling */
    .b2b-bg {
        background-color: #FCFBF8;
        background-image: radial-gradient(circle at 80% 20%, rgba(200, 155, 44, 0.05) 0%, transparent 50%);
    }
    
    .gold-accent {
        color: #C89B2C !important;
    }
    
    .bg-gold-accent {
        background-color: #C89B2C !important;
    }
    
    .border-gold-accent {
        border-color: #C89B2C !important;
    }
    
    .bg-dark-slate {
        background-color: #111827 !important;
    }
    
    .text-dark-slate {
        color: #111827 !important;
    }

    /* Cards */
    .luxury-card {
        background: #ffffff;
        border: 1px solid #E5E7EB;
        border-radius: 20px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 24px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .luxury-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -15px rgba(200, 155, 44, 0.15);
        border-color: #C89B2C;
    }

    /* Tab buttons */
    .showcase-tab {
        background: #ffffff;
        border: 1px solid #E5E7EB;
        color: #4B5563;
        font-weight: 700;
        font-size: 13px;
        padding: 10px 24px;
        border-radius: 9999px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .showcase-tab.active, .showcase-tab:hover {
        background: #C89B2C;
        color: #ffffff;
        border-color: #C89B2C;
    }

    /* Timeline */
    .timeline-line {
        position: relative;
    }
    .timeline-line::before {
        content: '';
        position: absolute;
        left: 24px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: repeating-linear-gradient(to bottom, #E5E7EB 0px, #E5E7EB 4px, transparent 4px, transparent 8px);
        z-index: 1;
    }
    .timeline-node {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #111827;
        border: 2px solid #C89B2C;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Playfair Display', serif;
        font-weight: bold;
        color: #C89B2C;
        z-index: 2;
        box-shadow: 0 0 15px rgba(200, 155, 44, 0.2);
        flex-shrink: 0;
    }
    
    @media (max-width: 768px) {
        .timeline-line::before {
            left: 16px;
        }
        .timeline-node {
            width: 34px;
            height: 34px;
            font-size: 12px;
        }
    }

    /* Range slider custom styling */
    .slider-input {
        -webkit-appearance: none;
        width: 100%;
        height: 6px;
        background: #E5E7EB;
        border-radius: 9999px;
        outline: none;
    }
    .slider-input::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #C89B2C;
        border: 2px solid #111827;
        cursor: pointer;
        transition: transform 0.1s ease;
    }
    .slider-input::-webkit-slider-thumb:hover {
        transform: scale(1.25);
    }

    /* Form Inputs */
    .luxury-input {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid #374151;
        border-radius: 12px;
        padding: 12px 16px;
        color: #ffffff;
        width: 100%;
        transition: all 0.3s ease;
    }
    .luxury-input:focus {
        border-color: #C89B2C;
        box-shadow: 0 0 0 2px rgba(200, 155, 44, 0.15);
        outline: none;
    }
    .luxury-input::placeholder {
        color: #6B7280;
    }
    select.luxury-input option {
        background-color: #111827 !important;
        color: #ffffff !important;
    }
    
    /* Occasions badges */
    .occasion-badge {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid #374151;
        color: #F3F4F6;
        font-size: 12px;
        font-weight: 600;
        padding: 8px 20px;
        border-radius: 9999px;
        transition: all 0.3s ease;
        cursor: default;
    }
    .occasion-badge:hover {
        border-color: #C89B2C;
        color: #111827;
        background-color: #C89B2C;
    }

    /* Visibility and Contrast Adjustments */
    .b2b-bg .text-luxGold,
    .b2b-bg span.badge,
    .b2b-bg span.rounded-pill,
    .text-luxGold {
        color: #C89B2C !important;
    }

    .b2b-bg .text-muted {
        color: #4B5563 !important; /* Premium dark neutral gray for optimal readability on off-white */
    }
    
    .bg-dark-slate .text-muted,
    #enquiry .text-muted {
        color: #9CA3AF !important; /* Premium light gray for high visibility on dark backgrounds */
    }
    
    .bg-dark-slate .text-secondary,
    #enquiry .text-secondary {
        color: #D1D5DB !important; /* Premium soft gray for high visibility labels on dark backgrounds */
    }
    
    /* Luxury Form Inputs */
    .luxury-input {
        background: rgba(255, 255, 255, 0.07) !important;
        border: 1px solid #4B5563 !important;
        color: #ffffff !important;
    }
    
    .luxury-input:focus {
        border-color: #C89B2C !important;
        box-shadow: 0 0 0 3px rgba(200, 155, 44, 0.25) !important;
    }
    
    /* Interactive Button Transitions */
    .btn-luxury-dark {
        background-color: #111827 !important;
        color: #ffffff !important;
        font-weight: 700;
        border: 1px solid #111827 !important;
        transition: all 0.3s ease-in-out;
    }
    .btn-luxury-dark:hover {
        background-color: #C89B2C !important;
        color: #ffffff !important;
        border-color: #C89B2C !important;
        box-shadow: 0 10px 20px rgba(200, 155, 44, 0.2);
    }
    
    .btn-luxury-outline {
        background-color: transparent !important;
        border: 1px solid #111827 !important;
        color: #111827 !important;
        font-weight: 700;
        transition: all 0.3s ease-in-out;
    }
    .btn-luxury-outline:hover {
        background-color: #111827 !important;
        color: #ffffff !important;
        box-shadow: 0 10px 20px rgba(17, 24, 39, 0.1);
    }
    
    .btn-luxury-submit {
        background-color: #C89B2C !important;
        color: #ffffff !important;
        font-weight: 700;
        border: 1px solid #C89B2C !important;
        transition: all 0.3s ease-in-out;
    }
    .btn-luxury-submit:hover {
        background-color: transparent !important;
        color: #C89B2C !important;
        border-color: #C89B2C !important;
        box-shadow: 0 10px 20px rgba(200, 155, 44, 0.2);
    }
</style>

<div class="b2b-bg min-h-screen pb-5">
    <!-- Dribbble Hero Section -->
    <section class="position-relative py-5 py-lg-7 overflow-hidden">
        <div class="container max-w-7xl mx-auto px-4 position-relative z-3">
            <div class="row align-items-center g-5">
                <!-- Left: Hero Text -->
                <div class="col-lg-7">
                    <span class="d-inline-flex align-items-center gap-2 bg-opacity-10 text-luxGold text-[10px] font-bold tracking-[0.25em] uppercase px-4 py-2 rounded-pill mb-4" style="background-color: rgba(200, 155, 44, 0.1);">
                        <i data-lucide="award" class="w-4 h-4 text-luxGold"></i> B2B Luxury Curation
                    </span>
                    
                    <h1 class="font-serif display-4 fw-bold text-dark-slate mb-3" style="line-height: 1.1;">
                        Elevating corporate <br><span class="italic font-light text-luxGold" style="font-family: 'Playfair Display', serif; font-style: italic;">relationships.</span>
                    </h1>
                    
                    <div class="w-16 h-[2px] bg-gold-accent my-4" style="background-color: #C89B2C; width: 60px; height: 2px;"></div>
                    
                    <p class="font-sans text-muted mb-5 leading-relaxed max-w-xl" style="font-size: 16px; font-weight: 300;">
                        Handcrafted premium leather accessories tailored for leadership milestones, key clients, and premium organizational gifts. We build statement pieces designed to make an impact.
                    </p>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#calculator" class="btn btn-luxury-dark px-4 py-3 rounded-3 text-[11px] font-bold tracking-widest uppercase transition-all shadow text-decoration-none">
                            Estimate Budget <i class="fa-solid fa-calculator ms-1"></i>
                        </a>
                        <a href="#enquiry" class="btn btn-luxury-outline px-4 py-3 rounded-3 text-[11px] font-bold tracking-widest uppercase transition-all text-decoration-none">
                            Request Catalogue
                        </a>
                    </div>
                </div>

                <!-- Right: High-fidelity image mockup -->
                <div class="col-lg-5 position-relative">
                    <div class="position-absolute bg-gold-accent bg-opacity-10 rounded-5 w-100 h-100" style="background-color: rgba(200, 155, 44, 0.04); transform: rotate(3deg); top: -10px; left: -10px; z-index: 1; border-radius: 24px;"></div>
                    
                    <div class="card border-0 rounded-4 overflow-hidden shadow-lg position-relative z-2 bg-white" style="border-radius: 24px; border: 1px solid #E5E7EB !important;">
                        <span class="position-absolute top-0 start-0 m-4 bg-dark-slate text-white text-[9px] font-bold tracking-widest uppercase px-3 py-1.5 rounded-pill shadow-sm z-3" style="background: rgba(17, 24, 39, 0.9);">
                            Exclusive Curation
                        </span>
                        
                        <div class="w-100 overflow-hidden bg-light" style="height: 380px;">
                            <img src="<?php echo $link_prefix; ?>assets/images/corporate_gift_set.png" 
                                 alt="Amadika Signature Leather Gift Box" 
                                 class="w-100 h-100 object-fit-cover">
                        </div>
                        
                        <div class="card-body p-4 text-center">
                            <h4 class="font-serif fw-bold mb-1 text-dark">The Signature Executive Set</h4>
                            <p class="text-[10px] text-luxGold fw-bold uppercase tracking-wider mb-0">Notebook, Desk Mat & Valet Combo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- B2B Estimator Widget -->
    <section class="py-5" id="calculator">
        <div class="container max-w-5xl mx-auto px-4">
            <div class="card border-0 shadow-lg overflow-hidden rounded-4" style="border-radius: 24px; border: 1px solid #E5E7EB !important;">
                <div class="row g-0">
                    <!-- Calculator Inputs -->
                    <div class="col-lg-7 p-4 p-md-5 bg-white">
                        <span class="text-[10px] font-bold tracking-[0.2em] text-luxGold uppercase d-block mb-1">INTERACTIVE CALCULATOR</span>
                        <h3 class="font-serif fw-bold text-dark mb-2">Estimate Pricing</h3>
                        <p class="text-xs text-muted mb-5">Adjust order variables to estimate unit rates and discount structures immediately.</p>
                        
                        <!-- Range slider for quantity -->
                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-baseline mb-2">
                                <label class="text-[10px] fw-bold text-secondary uppercase tracking-widest">Order Quantity</label>
                                <span class="font-serif fs-5 fw-bold text-dark" id="calcQtyVal">100 items</span>
                            </div>
                            <input type="range" id="calcQty" min="25" max="500" step="5" value="100" class="slider-input" oninput="updateB2BEstimate()">
                            <div class="d-flex justify-content-between text-[9px] text-muted fw-bold uppercase mt-2">
                                <span>25 items</span>
                                <span>250 items</span>
                                <span>500+ items</span>
                            </div>
                        </div>

                        <!-- Tier buttons -->
                        <div>
                            <label class="text-[10px] fw-bold text-secondary uppercase tracking-widest d-block mb-3">Product Quality Tier</label>
                            <div class="row g-3">
                                <div class="col-4">
                                    <button onclick="selectTier('classic', 1499)" id="tier-classic" class="btn w-100 border rounded-3 py-3 text-center transition-all bg-light">
                                        <span class="d-block text-[11px] fw-bold text-dark uppercase tracking-wider mb-1">Classic</span>
                                        <span class="text-[10px] text-muted d-block font-sans">From ₹1,499</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button onclick="selectTier('elite', 2499)" id="tier-elite" class="btn w-100 border rounded-3 py-3 text-center transition-all bg-light">
                                        <span class="d-block text-[11px] fw-bold text-dark uppercase tracking-wider mb-1">Elite</span>
                                        <span class="text-[10px] text-muted d-block font-sans">From ₹2,499</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button onclick="selectTier('signature', 4999)" id="tier-signature" class="btn w-100 border rounded-3 py-3 text-center transition-all bg-light">
                                        <span class="d-block text-[11px] fw-bold text-dark uppercase tracking-wider mb-1">Signature</span>
                                        <span class="text-[10px] text-muted d-block font-sans">From ₹4,999</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calculator Output Card -->
                    <div class="col-lg-5 p-4 p-md-5 bg-dark-slate text-white d-flex flex-column justify-content-between position-relative" style="background-color: #111827;">
                        <div class="position-absolute bg-gold-accent opacity-10 w-100 h-100 top-0 start-0" style="background: radial-gradient(circle at bottom right, rgba(200, 155, 44, 0.3) 0%, transparent 60%); pointer-events: none;"></div>
                        
                        <div class="position-relative z-2">
                            <span class="text-luxGold text-[9px] font-bold tracking-[0.2em] uppercase d-block mb-4">ESTIMATION SUMMARY</span>
                            
                            <div class="mb-4">
                                <span class="text-xs text-secondary d-block mb-1">Estimated Budget Range</span>
                                <h2 class="font-serif fw-bold text-white mb-2" id="estimatedTotal">₹2,24,910 - ₹2,49,900</h2>
                                <p class="text-[10px] text-muted mb-0">Includes debossed branding setup fee.</p>
                            </div>

                            <div class="border-top border-secondary border-opacity-25 pt-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary text-xs">Unit Rate (approx):</span>
                                    <span id="estUnitVal" class="fw-bold text-white text-xs">₹2,499 / item</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-secondary text-xs">Volume Discount:</span>
                                    <span id="discountVal" class="fw-bold text-xs text-success">10% Off Included</span>
                                </div>
                            </div>
                        </div>

                        <div class="position-relative z-2 mt-5">
                            <button onclick="lockEstimateAndScroll()" class="btn btn-luxury-submit w-100 py-3 rounded-3 text-[10px] font-bold tracking-widest uppercase shadow border-0">
                                Lock in Estimate & Inquire <i class="fa-solid fa-arrow-down ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Showcase Tabs Catalog -->
    <section class="py-5" id="collection-tabs">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="text-center mb-5">
                <span class="text-[10px] font-bold tracking-[0.3em] text-luxGold uppercase d-block mb-2">EXQUISITE COLLECTIONS</span>
                <h2 class="font-serif fw-bold text-dark-slate mb-3">Product Catalogues</h2>
                <div class="w-12 h-[2px] bg-gold-accent mx-auto" style="width: 50px; height: 2px;"></div>
            </div>

            <!-- Tabs buttons -->
            <div class="d-flex flex-wrap gap-2 justify-content-center mb-5">
                <button onclick="switchCategory('desk', this)" class="btn showcase-tab active">
                    Desk Organization
                </button>
                <button onclick="switchCategory('travel', this)" class="btn showcase-tab">
                    Travel & Tech
                </button>
                <button onclick="switchCategory('boxes', this)" class="btn showcase-tab">
                    Luxury Gift Boxes
                </button>
                <button onclick="switchCategory('monogram', this)" class="btn showcase-tab">
                    Monogrammed Accents
                </button>
            </div>

            <!-- Tabs Panels -->
            <div id="tab-panel-container">
                <!-- Desk Panel (Active) -->
                <div id="panel-desk" class="tab-panel row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="luxury-card">
                            <div>
                                <span class="badge bg-success bg-opacity-10 text-success text-[8px] font-bold uppercase px-3 py-1 rounded-pill mb-3" style="background-color: rgba(56, 142, 60, 0.1);">Best Seller</span>
                                <h4 class="font-serif fw-bold text-dark mb-2">Artisan Desk Mats</h4>
                                <p class="text-muted leading-relaxed mb-4" style="font-size: 13px; font-weight: 300;">Premium leather mats offering smooth precision tracking, suede backing protectors, and subtle edge-stitched borders.</p>
                            </div>
                            <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted text-[11px]">Custom Debossing</span>
                                <i data-lucide="layout-grid" class="w-4 h-4 text-luxGold"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="luxury-card">
                            <div>
                                <span class="badge bg-opacity-10 text-luxGold text-[8px] font-bold uppercase px-3 py-1 rounded-pill mb-3" style="background-color: rgba(200, 155, 44, 0.1);">Handcrafted</span>
                                <h4 class="font-serif fw-bold text-dark mb-2">Organiser Cups</h4>
                                <p class="text-muted leading-relaxed mb-4" style="font-size: 13px; font-weight: 300;">Structured cylindrical leather containers featuring rigid wall plates and velvet protective inner dividers.</p>
                            </div>
                            <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted text-[11px]">Genuine Leather</span>
                                <i data-lucide="package" class="w-4 h-4 text-luxGold"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="luxury-card">
                            <div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary text-[8px] font-bold uppercase px-3 py-1 rounded-pill mb-3" style="background-color: rgba(108, 117, 125, 0.1);">Modern Office</span>
                                <h4 class="font-serif fw-bold text-dark mb-2">Folding Valet Trays</h4>
                                <p class="text-muted leading-relaxed mb-4" style="font-size: 13px; font-weight: 300;">Flat-folding organizers perfect for storing keys, monogram card cases, and pins on hotel bedside tables.</p>
                            </div>
                            <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted text-[11px]">Metal Corner Snaps</span>
                                <i data-lucide="box" class="w-4 h-4 text-luxGold"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Travel Panel (Hidden) -->
                <div id="panel-travel" class="tab-panel d-none row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="luxury-card">
                            <div>
                                <span class="badge bg-opacity-10 text-luxGold text-[8px] font-bold uppercase px-3 py-1 rounded-pill mb-3" style="background-color: rgba(200, 155, 44, 0.1);">Premium Tech</span>
                                <h4 class="font-serif fw-bold text-dark mb-2">Laptop Satchels</h4>
                                <p class="text-muted leading-relaxed mb-4" style="font-size: 13px; font-weight: 300;">Full-grain leather satchels featuring padded compartments, brass fittings, and detachable shoulder straps.</p>
                            </div>
                            <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted text-[11px]">Fits up to 16" Laptops</span>
                                <i data-lucide="briefcase" class="w-4 h-4 text-luxGold"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="luxury-card">
                            <div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary text-[8px] font-bold uppercase px-3 py-1 rounded-pill mb-3" style="background-color: rgba(108, 117, 125, 0.1);">RFID Protection</span>
                                <h4 class="font-serif fw-bold text-dark mb-2">Passport Sleeves</h4>
                                <p class="text-muted leading-relaxed mb-4" style="font-size: 13px; font-weight: 300;">Premium travel wallets featuring RFID blocking layers, custom ticket dividers, and secure slots.</p>
                            </div>
                            <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted text-[11px]">RFID Lined Interior</span>
                                <i data-lucide="wallet" class="w-4 h-4 text-luxGold"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="luxury-card">
                            <div>
                                <span class="badge bg-success bg-opacity-10 text-success text-[8px] font-bold uppercase px-3 py-1 rounded-pill mb-3" style="background-color: rgba(56, 142, 60, 0.1);">Travel Token</span>
                                <h4 class="font-serif fw-bold text-dark mb-2">Leather Luggage Tags</h4>
                                <p class="text-muted leading-relaxed mb-4" style="font-size: 13px; font-weight: 300;">Classy secure travel tags built with high-grade leather straps and confidentiality address flaps.</p>
                            </div>
                            <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted text-[11px]">Debossed Logo Custom</span>
                                <i data-lucide="tag" class="w-4 h-4 text-luxGold"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boxes Panel (Hidden) -->
                <div id="panel-boxes" class="tab-panel d-none row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="luxury-card">
                            <div>
                                <span class="badge bg-opacity-10 text-luxGold text-[8px] font-bold uppercase px-3 py-1 rounded-pill mb-3" style="background-color: rgba(200, 155, 44, 0.1);">Executive Case</span>
                                <h4 class="font-serif fw-bold text-dark mb-2">Single Watch Rolls</h4>
                                <p class="text-muted leading-relaxed mb-4" style="font-size: 13px; font-weight: 300;">Watch storage roll designed with rigid safety walls, sliding watch dividers, and microfiber inner velvet.</p>
                            </div>
                            <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted text-[11px]">Microfiber Velvet</span>
                                <i data-lucide="clock" class="w-4 h-4 text-luxGold"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="luxury-card">
                            <div>
                                <span class="badge bg-success bg-opacity-10 text-success text-[8px] font-bold uppercase px-3 py-1 rounded-pill mb-3" style="background-color: rgba(56, 142, 60, 0.1);">Milestone Gift</span>
                                <h4 class="font-serif fw-bold text-dark mb-2">Corporate Hamper Box</h4>
                                <p class="text-muted leading-relaxed mb-4" style="font-size: 13px; font-weight: 300;">The photorealistic curated desk planner box containing our premium notebook, pen holder, and key loop.</p>
                            </div>
                            <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted text-[11px]">Custom Debossed Lid</span>
                                <i data-lucide="gem" class="w-4 h-4 text-luxGold"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="luxury-card">
                            <div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary text-[8px] font-bold uppercase px-3 py-1 rounded-pill mb-3" style="background-color: rgba(108, 117, 125, 0.1);">Celeb Kit</span>
                                <h4 class="font-serif fw-bold text-dark mb-2">Leather Wine Box cases</h4>
                                <p class="text-muted leading-relaxed mb-4" style="font-size: 13px; font-weight: 300;">Premium vintage carrying box containing divider panels, secure brass latches, and handle holds.</p>
                            </div>
                            <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted text-[11px]">Brass Locks & Accents</span>
                                <i data-lucide="wine" class="w-4 h-4 text-luxGold"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monogram Panel (Hidden) -->
                <div id="panel-monogram" class="tab-panel d-none row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="luxury-card">
                            <div>
                                <span class="badge bg-opacity-10 text-luxGold text-[8px] font-bold uppercase px-3 py-1 rounded-pill mb-3" style="background-color: rgba(200, 155, 44, 0.1);">Refillable</span>
                                <h4 class="font-serif fw-bold text-dark mb-2">Notebook Sleeves</h4>
                                <p class="text-muted leading-relaxed mb-4" style="font-size: 13px; font-weight: 300;">Refillable A5 journals debossed with hot-stamped gold foil monograms or clean standard blind impressions.</p>
                            </div>
                            <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted text-[11px]">Gold Foil Embossed</span>
                                <i data-lucide="book-open" class="w-4 h-4 text-luxGold"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="luxury-card">
                            <div>
                                <span class="badge bg-success bg-opacity-10 text-success text-[8px] font-bold uppercase px-3 py-1 rounded-pill mb-3" style="background-color: rgba(56, 142, 60, 0.1);">Welcome Pack</span>
                                <h4 class="font-serif fw-bold text-dark mb-2">Luxury Leather Keyloops</h4>
                                <p class="text-muted leading-relaxed mb-4" style="font-size: 13px; font-weight: 300;">Hand-crafted thick leather loops secured by polished titanium loops. Ideal entry token item.</p>
                            </div>
                            <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted text-[11px]">Monogrammed Loops</span>
                                <i data-lucide="key" class="w-4 h-4 text-luxGold"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="luxury-card">
                            <div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary text-[8px] font-bold uppercase px-3 py-1 rounded-pill mb-3" style="background-color: rgba(108, 117, 125, 0.1);">Desk Accessory</span>
                                <h4 class="font-serif fw-bold text-dark mb-2">Leather Coasters</h4>
                                <p class="text-muted leading-relaxed mb-4" style="font-size: 13px; font-weight: 300;">Luxury coaster disc packages stacked in matching leather holder containers with gold stitch outlines.</p>
                            </div>
                            <div class="border-top border-light pt-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted text-[11px]">Coaster Set of 4/6</span>
                                <i data-lucide="layers" class="w-4 h-4 text-luxGold"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Professional Timeline -->
    <section class="py-5 bg-white" id="experience-timeline">
        <div class="container max-w-4xl mx-auto px-4">
            <div class="text-center mb-5">
                <span class="text-[10px] font-bold tracking-[0.3em] text-luxGold uppercase d-block mb-2">B2B TIMELINE</span>
                <h2 class="font-serif fw-bold text-dark-slate mb-3">Fulfillment Process</h2>
                <div class="w-12 h-[2px] bg-gold-accent mx-auto" style="width: 50px; height: 2px;"></div>
            </div>

            <div class="timeline-line py-4">
                <!-- Step 1 -->
                <div class="d-flex align-items-start gap-4 mb-4 relative z-3">
                    <div class="timeline-node">01</div>
                    <div class="bg-light border border-light p-4 rounded-4 shadow-sm flex-grow-1" style="border-radius: 16px;">
                        <h4 class="font-serif fw-bold text-dark mb-1" style="font-size: 15px;">Creative Briefing</h4>
                        <p class="text-muted mb-0" style="font-size: 12px; font-weight: 300;">Submit your initial B2B targets. Our accounts consultant clarifies layout patterns, target monogramming logos, and bulk limits within 24 hours.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="d-flex align-items-start gap-4 mb-4 relative z-3">
                    <div class="timeline-node">02</div>
                    <div class="bg-light border border-light p-4 rounded-4 shadow-sm flex-grow-1" style="border-radius: 16px;">
                        <h4 class="font-serif fw-bold text-dark mb-1" style="font-size: 15px;">Visual Deck Proposals</h4>
                        <p class="text-muted mb-0" style="font-size: 12px; font-weight: 300;">Review customized photorealistic renders of your products bearing your debossed company logo alongside tailored volume price breakdowns.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="d-flex align-items-start gap-4 mb-4 relative z-3">
                    <div class="timeline-node">03</div>
                    <div class="bg-light border border-light p-4 rounded-4 shadow-sm flex-grow-1" style="border-radius: 16px;">
                        <h4 class="font-serif fw-bold text-dark mb-1" style="font-size: 15px;">Sample Manufacturing</h4>
                        <p class="text-muted mb-0" style="font-size: 12px; font-weight: 300;">We produce and dispatch physical sample products of your selection for tactile evaluation, ensuring exact deboss depth alignment.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="d-flex align-items-start gap-4 relative z-3">
                    <div class="timeline-node">04</div>
                    <div class="bg-light border border-light p-4 rounded-4 shadow-sm flex-grow-1" style="border-radius: 16px;">
                        <h4 class="font-serif fw-bold text-dark mb-1" style="font-size: 15px;">Delivery & Logistics</h4>
                        <p class="text-muted mb-0" style="font-size: 12px; font-weight: 300;">Approved specifications move to full B2B production. Every item is packed in signature rigid boxes and delivered pan-India.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Occasions Badges -->
    <section class="py-5" style="background-color: #111827;">
        <div class="container max-w-7xl mx-auto px-4 text-center">
            <span class="text-luxGold text-[9px] font-bold tracking-[0.3em] uppercase d-block mb-3">B2B EVENTS</span>
            <h3 class="font-serif fw-bold text-white mb-4">Gifting curations for every milestone event.</h3>
            
            <div class="d-flex flex-wrap gap-2 justify-content-center max-w-4xl mx-auto">
                <span class="occasion-badge">Diwali Gifts</span>
                <span class="occasion-badge">Employee Onboarding Welcomes</span>
                <span class="occasion-badge">Key Client Tokens</span>
                <span class="occasion-badge">Milestone Celebrations</span>
                <span class="occasion-badge">Board Member Recognitions</span>
                <span class="occasion-badge">New Year & Festivals</span>
                <span class="occasion-badge">Bespoke Monograms</span>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5 bg-white">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="text-center mb-5">
                <span class="text-[10px] font-bold tracking-[0.3em] text-luxGold uppercase d-block mb-2">B2B TESTIMONIALS</span>
                <h2 class="font-serif fw-bold text-dark-slate mb-3">Client Feedback</h2>
                <div class="w-12 h-[2px] bg-gold-accent mx-auto" style="width: 50px; height: 2px;"></div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="bg-light border rounded-4 p-4 d-flex flex-column justify-content-between h-100" style="border-radius: 16px;">
                        <div>
                            <span class="font-serif display-4 text-luxGold text-opacity-25 d-block mb-2" style="color: rgba(200,155,44,0.15); line-height: 0.5;">“</span>
                            <p class="font-serif italic text-muted leading-relaxed mb-4" style="font-size: 13px;">"Amadika elevated our Diwali gifting campaign. Several key clients reached out to personally praise the premium leather quality — a response we hadn't seen in years."</p>
                        </div>
                        <div>
                            <div class="border-top border-light pt-3">
                                <span class="text-[9px] font-bold tracking-widest text-luxGold uppercase d-block">Director</span>
                                <span class="text-[10px] text-muted font-sans block">Real Estate Enterprise, NCR</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-light border rounded-4 p-4 d-flex flex-column justify-content-between h-100" style="border-radius: 16px;">
                        <div>
                            <span class="font-serif display-4 text-luxGold text-opacity-25 d-block mb-2" style="color: rgba(200,155,44,0.15); line-height: 0.5;">“</span>
                            <p class="font-serif italic text-muted leading-relaxed mb-4" style="font-size: 13px;">"We customized 150 desk set pairings for our board and senior leadership. The debossing, packaging details, and logistics were handled impeccably."</p>
                        </div>
                        <div>
                            <div class="border-top border-light pt-3">
                                <span class="text-[9px] font-bold tracking-widest text-luxGold uppercase d-block">VP of Human Resources</span>
                                <span class="text-[10px] text-muted font-sans block">Financial Advisory Firm, Mumbai</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-light border rounded-4 p-4 d-flex flex-column justify-content-between h-100" style="border-radius: 16px;">
                        <div>
                            <span class="font-serif display-4 text-luxGold text-opacity-25 d-block mb-2" style="color: rgba(200,155,44,0.15); line-height: 0.5;">“</span>
                            <p class="font-serif italic text-muted leading-relaxed mb-4" style="font-size: 13px;">"Their corporate desk team was outstanding. The product suggestions were highly tailored, and alignment was fast. Truly professional from start to delivery."</p>
                        </div>
                        <div>
                            <div class="border-top border-light pt-3">
                                <span class="text-[9px] font-bold tracking-widest text-luxGold uppercase d-block">Brand Manager</span>
                                <span class="text-[10px] text-muted font-sans block">Automotive Group, Pune</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enquiry Form -->
    <section class="py-5 bg-dark-slate text-white position-relative overflow-hidden" id="enquiry" style="background-color: #111827;">
        <div class="position-absolute bg-gold-accent opacity-10 w-100 h-100 top-0 start-0" style="background: radial-gradient(circle at center, rgba(200, 155, 44, 0.2) 0%, transparent 60%); pointer-events: none;"></div>
        
        <div class="container max-w-7xl mx-auto px-4 position-relative z-2">
            <div class="text-center mb-5">
                <span class="text-[10px] font-bold tracking-[0.3em] text-luxGold uppercase d-block mb-2">B2B QUOTE REQUEST</span>
                <h2 class="font-serif fw-bold">Request a Proposal</h2>
                <div class="w-12 h-[2px] bg-gold-accent mx-auto" style="width: 50px; height: 2px; background-color: #C89B2C;"></div>
            </div>

            <div class="row align-items-stretch g-5">
                <!-- Left Side: Image Showcase -->
                <div class="col-lg-5 col-12 mb-4 mb-lg-0">
                    <div class="h-100 rounded-4 overflow-hidden position-relative border border-secondary border-opacity-25" style="border-radius: 24px; min-height: 380px;">
                        <!-- Absolute background overlay -->
                        <div class="position-absolute w-100 h-100" style="background: linear-gradient(180deg, rgba(17,24,39,0.1) 0%, rgba(17,24,39,0.8) 100%); z-index: 2;"></div>
                        <!-- Photorealistic showcase image -->
                        <img src="<?php echo $link_prefix; ?>assets/images/corporate_gift_set.png" 
                             alt="Luxury Gifting Proposals" 
                             class="w-100 h-100 object-fit-cover position-absolute" style="z-index: 1;">
                        
                        <!-- Floating caption inside the image -->
                        <div class="position-absolute bottom-0 start-0 p-5 text-white z-3">
                            <span class="text-luxGold text-[10px] font-bold tracking-[0.2em] uppercase d-block mb-2">Tailored Elegance</span>
                            <h3 class="font-serif fw-bold text-white mb-2">Amadika Signature</h3>
                            <p class="text-muted text-xs leading-relaxed mb-0">Every gift curated and monogrammed to perfection. Request our corporate catalog containing 100+ exclusive leather selections.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Proposal Form -->
                <div class="col-lg-7 col-12">
                    <div class="card border-0 rounded-4 p-4 p-md-5 bg-opacity-50 h-100" style="background: rgba(31, 41, 55, 0.4); border: 1px solid rgba(255, 255, 255, 0.05) !important; border-radius: 24px;">
                        <form onsubmit="handleCorporateSubmit(event)" class="row g-4">
                            <div class="col-md-6">
                                <label class="text-[10px] fw-bold text-secondary uppercase tracking-wider mb-2 d-block">Your Name</label>
                                <input type="text" id="name" placeholder="Anurag Singh" required class="luxury-input">
                            </div>
                            <div class="col-md-6">
                                <label class="text-[10px] fw-bold text-secondary uppercase tracking-wider mb-2 d-block">Company Name</label>
                                <input type="text" id="company" placeholder="Your Organisation" required class="luxury-input">
                            </div>
                            <div class="col-md-6">
                                <label class="text-[10px] fw-bold text-secondary uppercase tracking-wider mb-2 d-block">Email Address</label>
                                <input type="email" id="email" placeholder="you@company.com" required class="luxury-input">
                            </div>
                            <div class="col-md-6">
                                <label class="text-[10px] fw-bold text-secondary uppercase tracking-wider mb-2 d-block">Phone Number</label>
                                <input type="tel" id="phone" placeholder="+91 98765 43210" class="luxury-input">
                            </div>
                            <div class="col-md-6">
                                <label class="text-[10px] fw-bold text-secondary uppercase tracking-wider mb-2 d-block">Quantity Range</label>
                                <div class="position-relative">
                                    <select id="quantity" class="luxury-input appearance-none cursor-pointer">
                                        <option value="" disabled selected>Select range</option>
                                        <option>25 – 50 items</option>
                                        <option>51 – 100 items</option>
                                        <option>101 – 250 items</option>
                                        <option>251 – 500 items</option>
                                        <option>500+ items</option>
                                    </select>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-muted position-absolute end-0 top-50 translate-middle-y me-3 pointer-events-none"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-[10px] fw-bold text-secondary uppercase tracking-wider mb-2 d-block">Occasion</label>
                                <div class="position-relative">
                                    <select id="occasion" class="luxury-input appearance-none cursor-pointer">
                                        <option value="" disabled selected>Select occasion</option>
                                        <option>Diwali Gifting</option>
                                        <option>Employee Recognition</option>
                                        <option>Client Appreciation</option>
                                        <option>New Year & Festivals</option>
                                        <option>Corporate Milestones</option>
                                        <option>Other</option>
                                    </select>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-muted position-absolute end-0 top-50 translate-middle-y me-3 pointer-events-none"></i>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="text-[10px] fw-bold text-secondary uppercase tracking-wider mb-2 d-block">Additional details</label>
                                <textarea id="message" placeholder="Provide any budget targets, preferred colors, logo monogram details, or custom packaging preferences..." class="luxury-input" style="min-height: 120px; resize: vertical;"></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-luxury-submit w-100 py-3 rounded-3 text-[10px] font-bold tracking-widest uppercase border-0">
                                    Submit Quote Inquiry
                                </button>
                            </div>
                        </form>
                        <p class="text-[10px] text-center text-muted mt-4 mb-0 tracking-wide uppercase">Our corporate relationships team will get back to you within 24 hours.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Estimator variables
    let selectedTierName = 'classic';
    let baseUnitPrice = 1499;

    function selectTier(tier, basePrice) {
        selectedTierName = tier;
        baseUnitPrice = basePrice;
        
        // Reset classes
        document.querySelectorAll('[id^="tier-"]').forEach(btn => {
            btn.classList.remove('border-gold-accent', 'text-luxGold');
            btn.classList.add('bg-light');
            btn.style.backgroundColor = '';
            btn.style.color = '';
        });
        
        // Set active style
        const activeBtn = document.getElementById(`tier-${tier}`);
        activeBtn.classList.remove('bg-light');
        activeBtn.classList.add('border-gold-accent');
        activeBtn.style.color = '#C89B2C';
        activeBtn.style.backgroundColor = 'rgba(200, 155, 44, 0.05)';
        
        updateB2BEstimate();
    }

    function updateB2BEstimate() {
        const qtyVal = parseInt(document.getElementById('calcQty').value);
        document.getElementById('calcQtyVal').innerText = `${qtyVal} items`;

        // Calculate discount
        let discountPct = 0;
        if (qtyVal >= 50 && qtyVal < 100) {
            discountPct = 5;
        } else if (qtyVal >= 100 && qtyVal < 250) {
            discountPct = 10;
        } else if (qtyVal >= 250 && qtyVal < 500) {
            discountPct = 15;
        } else if (qtyVal >= 500) {
            discountPct = 20;
        }

        const discountedUnitPrice = Math.round(baseUnitPrice * (1 - discountPct / 100));
        const totalMin = discountedUnitPrice * qtyVal;
        const totalMax = Math.round(totalMin * 1.1);

        document.getElementById('estUnitVal').innerText = `₹${discountedUnitPrice.toLocaleString()} / item`;
        
        const discEl = document.getElementById('discountVal');
        if (discountPct > 0) {
            discEl.innerText = `${discountPct}% Volume Discount Applied`;
            discEl.className = "fw-bold text-xs text-success";
        } else {
            discEl.innerText = "Standard Rate (No discount)";
            discEl.className = "fw-bold text-xs text-secondary";
        }

        document.getElementById('estimatedTotal').innerText = `₹${totalMin.toLocaleString()} - ₹${totalMax.toLocaleString()}`;
    }

    function lockEstimateAndScroll() {
        const qtyVal = document.getElementById('calcQty').value;
        const qtySelect = document.getElementById('quantity');
        
        if (qtyVal <= 50) {
            qtySelect.value = "25 – 50 items";
        } else if (qtyVal > 50 && qtyVal <= 100) {
            qtySelect.value = "51 – 100 items";
        } else if (qtyVal > 100 && qtyVal <= 250) {
            qtySelect.value = "101 – 250 items";
        } else if (qtyVal > 250 && qtyVal <= 500) {
            qtySelect.value = "251 – 500 items";
        } else {
            qtySelect.value = "500+ items";
        }

        const detailMessage = document.getElementById('message');
        detailMessage.value = `Locked Estimate: Selected ${selectedTierName.toUpperCase()} Collection for approx. ${qtyVal} items. Please provide custom branding instructions.`;

        const targetElement = document.getElementById('enquiry');
        targetElement.scrollIntoView({ behavior: 'smooth' });

        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'info',
                title: 'Calculator selections synced to B2B form.'
            });
        }
    }

    function switchCategory(catId, btn) {
        document.querySelectorAll('#collection-tabs .showcase-tab').forEach(b => {
            b.classList.remove('active');
        });
        btn.classList.add('active');

        document.querySelectorAll('#tab-panel-container .tab-panel').forEach(panel => {
            panel.classList.add('d-none');
        });

        const targetPanel = document.getElementById(`panel-${catId}`);
        targetPanel.classList.remove('d-none');

        // Animation
        gsap.fromTo(targetPanel.children, 
            { opacity: 0, y: 15 }, 
            { opacity: 1, y: 0, stagger: 0.08, duration: 0.4, ease: 'power2.out' }
        );
    }

    function handleCorporateSubmit(e) {
        e.preventDefault();
        
        const name = document.getElementById('name').value;
        const company = document.getElementById('company').value;
        const email = document.getElementById('email').value;

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Enquiry Received',
                text: `Thank you, ${name}. Our Corporate Gifting Advisor will connect with you at ${email} shortly to discuss options for ${company}.`,
                confirmButtonColor: '#C89B2C',
                customClass: {
                    popup: 'rounded-4 border shadow-lg',
                    confirmButton: 'btn bg-gold-accent hover:bg-white text-white px-4 py-2.5 text-xs font-bold uppercase rounded-3'
                }
            });
        } else {
            alert(`Thank you, ${name}. We have received your B2B enquiry for ${company} and will get back to you shortly.`);
        }

        e.target.reset();
    }

    document.addEventListener('DOMContentLoaded', () => {
        selectTier('classic', 1499);
        // Force-trigger Lucide icon generation inside late-loaded page content template
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>

<?php include 'includes/footer.php'; ?>

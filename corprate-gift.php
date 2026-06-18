<?php
require_once 'database/db_config.php';
$page_title = "Corporate Gifting | Amadika Premium Leather";
$page_description = "Handcrafted premium leather corporate gifts, desk organizers, laptop satchels, and gift sets by Amadika. Customized corporate gifting with monogramming.";
include 'includes/header.php';
?>

<style>
    /* Corporate Gifting page specific styles */
    .gift-card-hover:hover {
        border-color: #C89B2C !important;
        transform: translateY(-4px);
    }
    .trust-border {
        border-color: rgba(200, 155, 44, 0.15);
    }
    .step-number-glow {
        text-shadow: 0 0 15px rgba(200, 155, 44, 0.2);
    }
</style>

<div class="bg-[#FCFBF8] text-gray-800 min-h-screen">
    <!-- HERO SECTION -->
    <section class="relative py-20 lg:py-32 overflow-hidden">
        <!-- Delicate abstract mesh background -->
        <div class="absolute inset-0 pointer-events-none opacity-20 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-luxGold/20 via-transparent to-transparent"></div>
        <div class="absolute right-0 top-1/2 -translate-y-1/2 font-serif text-[40vw] lg:text-[450px] font-bold text-gray-100/40 select-none pointer-events-none leading-none">A</div>
        
        <div class="container max-w-7xl mx-auto px-6 relative z-10">
            <div class="max-w-2xl">
                <span class="text-[10px] font-extrabold tracking-[0.4em] text-luxGold uppercase block mb-4">AMADIKA CORPORATE COLLABORATIONS</span>
                <h1 class="font-serif text-5xl lg:text-7xl font-bold text-darkLux leading-tight mb-2">Gift with</h1>
                <div class="font-serif italic text-3xl lg:text-4xl text-luxGold mb-6">intention.</div>
                <div class="w-20 h-[2px] bg-gradient-to-r from-luxGold to-transparent mb-8"></div>
                <p class="font-serif italic text-lg lg:text-xl text-gray-500 font-light leading-relaxed mb-8 max-w-lg">
                    Handcrafted premium leather accessories that carry your brand's prestige long after the moment of giving. Amadika designs solutions that are memorable, functional, and impeccably made.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#enquiry" class="bg-darkLux hover:bg-luxGold text-white text-[11px] font-bold tracking-widest uppercase px-8 py-3.5 rounded-lg transition-all duration-300 shadow-md text-decoration-none">Request Proposal</a>
                    <a href="#gifts" class="border border-luxGold text-luxGold hover:bg-luxGold hover:text-white text-[11px] font-bold tracking-widest uppercase px-8 py-3.5 rounded-lg transition-all duration-300 text-decoration-none">Explore Curation</a>
                </div>
            </div>
        </div>
    </section>

    <!-- TRUST STRIP -->
    <div class="bg-darkLux border-y border-gray-800 py-10">
        <div class="container max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-4 text-center divide-x-0 divide-y lg:divide-y-0 lg:divide-x divide-gray-850">
                <div class="px-4 py-2 lg:py-0">
                    <span class="font-serif text-3xl font-bold text-luxGold block mb-1">100+</span>
                    <span class="text-[9px] font-extrabold tracking-widest text-gray-400 uppercase block">Curated SKUs</span>
                </div>
                <div class="px-4 py-2 lg:py-0 border-gray-800">
                    <span class="font-serif text-3xl font-bold text-luxGold block mb-1">Pan-India</span>
                    <span class="text-[9px] font-extrabold tracking-widest text-gray-400 uppercase block">Express Delivery</span>
                </div>
                <div class="px-4 py-2 lg:py-0 border-gray-800">
                    <span class="font-serif text-3xl font-bold text-luxGold block mb-1">Custom</span>
                    <span class="text-[9px] font-extrabold tracking-widest text-gray-400 uppercase block">Logo Monogramming</span>
                </div>
                <div class="px-4 py-2 lg:py-0 border-gray-800">
                    <span class="font-serif text-3xl font-bold text-luxGold block mb-1">B2B</span>
                    <span class="text-[9px] font-extrabold tracking-widest text-gray-400 uppercase block">Account Support</span>
                </div>
                <div class="px-4 py-2 lg:py-0 border-gray-800">
                    <span class="font-serif text-3xl font-bold text-luxGold block mb-1">100%</span>
                    <span class="text-[9px] font-extrabold tracking-widest text-gray-400 uppercase block">Premium Leather</span>
                </div>
            </div>
        </div>
    </div>

    <!-- GIFT CATEGORIES -->
    <section class="py-20 lg:py-28" id="gifts">
        <div class="container max-w-7xl mx-auto px-6">
            <span class="text-[10px] font-extrabold tracking-[0.4em] text-luxGold uppercase block text-center mb-3">THE CORPORATE COLLECTION</span>
            <h2 class="font-serif text-4xl lg:text-5xl font-bold text-center text-darkLux mb-4">Every gift, <span class="italic text-luxGold">extraordinary.</span></h2>
            <div class="w-16 h-[2px] bg-luxGold mx-auto mb-16"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-luxury hover:shadow-luxuryHover hover:-translate-y-1 transition-all duration-300 p-8 border border-gray-100 flex flex-col justify-between group">
                    <div>
                       <div class="w-12 h-12 rounded-lg bg-luxGold/10 flex items-center justify-center text-luxGold mb-6 group-hover:bg-luxGold group-hover:text-white transition-colors duration-300">
                           <i data-lucide="layout-grid" class="w-6 h-6"></i>
                       </div>
                       <h3 class="font-serif text-lg font-bold text-gray-800 mb-3">Executive Desk Sets</h3>
                       <p class="font-serif italic text-sm text-gray-500 leading-relaxed">Leather-crafted desk organisers, pen holders, document trays, and mouse pads. The complete workspace, reimagined.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-luxury hover:shadow-luxuryHover hover:-translate-y-1 transition-all duration-300 p-8 border border-gray-100 flex flex-col justify-between group">
                    <div>
                       <div class="w-12 h-12 rounded-lg bg-luxGold/10 flex items-center justify-center text-luxGold mb-6 group-hover:bg-luxGold group-hover:text-white transition-colors duration-300">
                           <i data-lucide="briefcase" class="w-6 h-6"></i>
                       </div>
                       <h3 class="font-serif text-lg font-bold text-gray-800 mb-3">Leather Satchels & Sleeves</h3>
                       <p class="font-serif italic text-sm text-gray-500 leading-relaxed">Structured laptop satchels and padded tech sleeves. Sleek minimalist lines crafted from high-grade leather.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-luxury hover:shadow-luxuryHover hover:-translate-y-1 transition-all duration-300 p-8 border border-gray-100 flex flex-col justify-between group">
                    <div>
                       <div class="w-12 h-12 rounded-lg bg-luxGold/10 flex items-center justify-center text-luxGold mb-6 group-hover:bg-luxGold group-hover:text-white transition-colors duration-300">
                           <i data-lucide="box" class="w-6 h-6"></i>
                       </div>
                       <h3 class="font-serif text-lg font-bold text-gray-800 mb-3">Valet Trays & Coasters</h3>
                       <p class="font-serif italic text-sm text-gray-500 leading-relaxed">Artisan leather valet organizers, coaster sets, and boxes that add functional luxury to homes and offices.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-luxury hover:shadow-luxuryHover hover:-translate-y-1 transition-all duration-300 p-8 border border-gray-100 flex flex-col justify-between group">
                    <div>
                       <div class="w-12 h-12 rounded-lg bg-luxGold/10 flex items-center justify-center text-luxGold mb-6 group-hover:bg-luxGold group-hover:text-white transition-colors duration-300">
                           <i data-lucide="wine" class="w-6 h-6"></i>
                       </div>
                       <h3 class="font-serif text-lg font-bold text-gray-800 mb-3">Premium Bar Accessories</h3>
                       <p class="font-serif italic text-sm text-gray-500 leading-relaxed">Handcrafted leather-wrapped cocktail sets and mini bar boxes for celebrating milestones in style.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-luxury hover:shadow-luxuryHover hover:-translate-y-1 transition-all duration-300 p-8 border border-gray-100 flex flex-col justify-between group">
                    <div>
                       <div class="w-12 h-12 rounded-lg bg-luxGold/10 flex items-center justify-center text-luxGold mb-6 group-hover:bg-luxGold group-hover:text-white transition-colors duration-300">
                           <i data-lucide="gem" class="w-6 h-6"></i>
                       </div>
                       <h3 class="font-serif text-lg font-bold text-gray-800 mb-3">Watch & Jewellery Cases</h3>
                       <p class="font-serif italic text-sm text-gray-500 leading-relaxed">Secure, elegant leather cases lined with velvet to organize watches and jewelry. A highly personal gift.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-luxury hover:shadow-luxuryHover hover:-translate-y-1 transition-all duration-300 p-8 border border-gray-100 flex flex-col justify-between group">
                    <div>
                       <div class="w-12 h-12 rounded-lg bg-luxGold/10 flex items-center justify-center text-luxGold mb-6 group-hover:bg-luxGold group-hover:text-white transition-colors duration-300">
                           <i data-lucide="sparkles" class="w-6 h-6"></i>
                       </div>
                       <h3 class="font-serif text-lg font-bold text-gray-800 mb-3">Custom Curated Sets</h3>
                       <p class="font-serif italic text-sm text-gray-500 leading-relaxed">Bespoke combination packages tailored to your brand identity, budget tier, and event schedule.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- THE EXPERIENCE (PROCESS) -->
    <section class="bg-white py-20 lg:py-28" id="process">
        <div class="container max-w-7xl mx-auto px-6">
            <span class="text-[10px] font-extrabold tracking-[0.4em] text-luxGold uppercase block text-center mb-3">THE EXPERIENCE</span>
            <h2 class="font-serif text-4xl lg:text-5xl font-bold text-center text-darkLux mb-4">Simple. <span class="italic text-luxGold">Seamless.</span> Premium.</h2>
            <div class="w-16 h-[2px] bg-luxGold mx-auto mb-16"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="border border-gray-100 bg-gray-50/50 p-8 rounded-2xl relative overflow-hidden group">
                    <div class="absolute right-4 top-2 font-serif text-6xl font-bold text-gray-200/50 group-hover:text-luxGold/10 transition-colors duration-300">01</div>
                    <h4 class="font-serif text-base font-bold text-gray-800 mb-3 mt-4">Share Your Brief</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Provide your event occasion, quantity, timeline, and branding desires. Our curators will map out the ideal proposals.</p>
                </div>
                <div class="border border-gray-100 bg-gray-50/50 p-8 rounded-2xl relative overflow-hidden group">
                    <div class="absolute right-4 top-2 font-serif text-6xl font-bold text-gray-200/50 group-hover:text-luxGold/10 transition-colors duration-300">02</div>
                    <h4 class="font-serif text-base font-bold text-gray-800 mb-3 mt-4">Receive a Proposal</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Within 24-48 hours, receive a catalog proposal with curated products, visualization drafts, and volume pricing.</p>
                </div>
                <div class="border border-gray-100 bg-gray-50/50 p-8 rounded-2xl relative overflow-hidden group">
                    <div class="absolute right-4 top-2 font-serif text-6xl font-bold text-gray-200/50 group-hover:text-luxGold/10 transition-colors duration-300">03</div>
                    <h4 class="font-serif text-base font-bold text-gray-800 mb-3 mt-4">Customize & Sample</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Finalize color preferences, leather types, and verify standard debossed or hot-foiled corporate branding mockups.</p>
                </div>
                <div class="border border-gray-100 bg-gray-50/50 p-8 rounded-2xl relative overflow-hidden group">
                    <div class="absolute right-4 top-2 font-serif text-6xl font-bold text-gray-200/50 group-hover:text-luxGold/10 transition-colors duration-300">04</div>
                    <h4 class="font-serif text-base font-bold text-gray-800 mb-3 mt-4">Luxury Fulfillment</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Every item is packed in signature premium packaging and dispatched on time via direct secure transport channels.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- OCCASIONS -->
    <section class="py-20" id="occasions">
        <div class="container max-w-7xl mx-auto px-6">
            <span class="text-[10px] font-extrabold tracking-[0.4em] text-luxGold uppercase block text-center mb-3">CURATED FOR ALL EVENTS</span>
            <h2 class="font-serif text-4xl font-bold text-center text-darkLux mb-4">Every <span class="italic text-luxGold">celebration.</span></h2>
            <div class="w-16 h-[2px] bg-luxGold mx-auto mb-12"></div>
            
            <div class="flex flex-wrap gap-3 justify-center max-w-4xl mx-auto">
                <span class="border border-gray-200 hover:border-luxGold hover:text-luxGold bg-white text-gray-600 text-xs font-semibold px-6 py-2.5 rounded-full transition-all duration-300 cursor-default">Diwali Gifting</span>
                <span class="border border-gray-200 hover:border-luxGold hover:text-luxGold bg-white text-gray-600 text-xs font-semibold px-6 py-2.5 rounded-full transition-all duration-300 cursor-default">Employee Welcomes</span>
                <span class="border border-gray-200 hover:border-luxGold hover:text-luxGold bg-white text-gray-600 text-xs font-semibold px-6 py-2.5 rounded-full transition-all duration-300 cursor-default">Client Appreciations</span>
                <span class="border border-gray-200 hover:border-luxGold hover:text-luxGold bg-white text-gray-600 text-xs font-semibold px-6 py-2.5 rounded-full transition-all duration-300 cursor-default">Leadership Milestones</span>
                <span class="border border-gray-200 hover:border-luxGold hover:text-luxGold bg-white text-gray-600 text-xs font-semibold px-6 py-2.5 rounded-full transition-all duration-300 cursor-default">Onboarding Kits</span>
                <span class="border border-gray-200 hover:border-luxGold hover:text-luxGold bg-white text-gray-600 text-xs font-semibold px-6 py-2.5 rounded-full transition-all duration-300 cursor-default">Conference Souvenirs</span>
                <span class="border border-gray-200 hover:border-luxGold hover:text-luxGold bg-white text-gray-600 text-xs font-semibold px-6 py-2.5 rounded-full transition-all duration-300 cursor-default">Board-Member Recognitions</span>
                <span class="border border-gray-200 hover:border-luxGold hover:text-luxGold bg-white text-gray-600 text-xs font-semibold px-6 py-2.5 rounded-full transition-all duration-300 cursor-default">New Year & Festivals</span>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="bg-white py-20 lg:py-28">
        <div class="container max-w-7xl mx-auto px-6">
            <span class="text-[10px] font-extrabold tracking-[0.4em] text-luxGold uppercase block text-center mb-3">B2B TESTIMONIALS</span>
            <h2 class="font-serif text-4xl lg:text-5xl font-bold text-center text-darkLux mb-4">Gifting with <span class="italic text-luxGold">prestige.</span></h2>
            <div class="w-16 h-[2px] bg-luxGold mx-auto mb-16"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8 flex flex-col justify-between shadow-sm">
                    <div>
                        <span class="font-serif text-5xl text-luxGold/20 leading-none block mb-2">“</span>
                        <p class="font-serif italic text-sm text-gray-600 leading-relaxed mb-6">"Amadika elevated our Diwali gifting campaign. Several key clients reached out to personally praise the premium leather quality — a response we hadn't seen in years."</p>
                    </div>
                    <span class="text-[10px] font-extrabold tracking-wider text-luxGold uppercase block">Director &mdash; Real Estate Enterprise</span>
                </div>
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8 flex flex-col justify-between shadow-sm">
                    <div>
                        <span class="font-serif text-5xl text-luxGold/20 leading-none block mb-2">“</span>
                        <p class="font-serif italic text-sm text-gray-600 leading-relaxed mb-6">"We customized 150 desk set pairings for our board and senior leadership. The debossing, packaging details, and logistics were handled impeccably."</p>
                    </div>
                    <span class="text-[10px] font-extrabold tracking-wider text-luxGold uppercase block">VP of HR &mdash; Financial Advisory Firm</span>
                </div>
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8 flex flex-col justify-between shadow-sm">
                    <div>
                        <span class="font-serif text-5xl text-luxGold/20 leading-none block mb-2">“</span>
                        <p class="font-serif italic text-sm text-gray-600 leading-relaxed mb-6">"Their corporate desk team was outstanding. The product suggestions were highly tailored, and alignment was fast. Truly professional from start to delivery."</p>
                    </div>
                    <span class="text-[10px] font-extrabold tracking-wider text-luxGold uppercase block">Brand Manager &mdash; Premium Automotive</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ENQUIRY FORM SECTION -->
    <section class="bg-[#111827] text-white py-20 lg:py-28 relative overflow-hidden" id="enquiry">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-luxGold/30 via-transparent to-transparent pointer-events-none"></div>
        
        <div class="container max-w-4xl mx-auto px-6 relative z-10">
            <span class="text-[10px] font-extrabold tracking-[0.4em] text-luxGold uppercase block text-center mb-3">GET IN TOUCH</span>
            <h2 class="font-serif text-4xl lg:text-5xl font-bold text-center text-white mb-4">Request a <span class="italic text-luxGold">proposal.</span></h2>
            <div class="w-16 h-[2px] bg-luxGold mx-auto mb-16"></div>
            
            <div class="bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl p-8 lg:p-12 shadow-2xl">
                <form onsubmit="handleCorporateSubmit(event)" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Your Name</label>
                            <input type="text" id="name" placeholder="Anurag Singh" required class="bg-gray-950/70 border border-gray-800 focus:border-luxGold text-sm pl-4 pr-4 py-3 rounded-xl focus:outline-none transition-all duration-305 text-white">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Company Name</label>
                            <input type="text" id="company" placeholder="Your Organisation" required class="bg-gray-950/70 border border-gray-800 focus:border-luxGold text-sm pl-4 pr-4 py-3 rounded-xl focus:outline-none transition-all duration-305 text-white">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Email Address</label>
                            <input type="email" id="email" placeholder="you@company.com" required class="bg-gray-950/70 border border-gray-800 focus:border-luxGold text-sm pl-4 pr-4 py-3 rounded-xl focus:outline-none transition-all duration-305 text-white">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Phone Number</label>
                            <input type="tel" id="phone" placeholder="+91 98765 43210" class="bg-gray-950/70 border border-gray-800 focus:border-luxGold text-sm pl-4 pr-4 py-3 rounded-xl focus:outline-none transition-all duration-305 text-white">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Quantity Required</label>
                            <div class="relative">
                                <select id="quantity" class="bg-gray-950/70 border border-gray-800 focus:border-luxGold text-sm pl-4 pr-8 py-3 rounded-xl focus:outline-none transition-all duration-305 text-white cursor-pointer w-full appearance-none">
                                    <option value="" disabled selected class="bg-gray-900 text-gray-400">Select range</option>
                                    <option class="bg-gray-900 text-white">25 – 50 items</option>
                                    <option class="bg-gray-900 text-white">51 – 100 items</option>
                                    <option class="bg-gray-900 text-white">101 – 250 items</option>
                                    <option class="bg-gray-900 text-white">251 – 500 items</option>
                                    <option class="bg-gray-900 text-white">500+ items</option>
                                </select>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Occasion</label>
                            <div class="relative">
                                <select id="occasion" class="bg-gray-950/70 border border-gray-800 focus:border-luxGold text-sm pl-4 pr-8 py-3 rounded-xl focus:outline-none transition-all duration-305 text-white cursor-pointer w-full appearance-none">
                                    <option value="" disabled selected class="bg-gray-900 text-gray-400">Select occasion</option>
                                    <option class="bg-gray-900 text-white">Diwali Gifting</option>
                                    <option class="bg-gray-900 text-white">Employee Recognition</option>
                                    <option class="bg-gray-900 text-white">Client Appreciation</option>
                                    <option class="bg-gray-900 text-white">New Year & Festivals</option>
                                    <option class="bg-gray-900 text-white">Corporate Milestones</option>
                                    <option class="bg-gray-900 text-white">Other</option>
                                </select>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Additional details</label>
                        <textarea id="message" placeholder="Provide any budget targets, preferred colors, logo monogram details, or custom packaging preferences..." class="bg-gray-950/70 border border-gray-800 focus:border-luxGold text-sm pl-4 pr-4 py-3 rounded-xl focus:outline-none transition-all duration-305 text-white min-h-[120px] resize-y"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-luxGold hover:bg-white hover:text-darkLux text-white text-xs font-bold tracking-widest uppercase py-4 rounded-xl transition-all duration-300 shadow-md border-0">Submit Gifting Enquiry</button>
                </form>
                <p class="text-[10px] text-center text-gray-500 mt-6 tracking-wide uppercase">Our corporate relationships team will get back to you within 24 hours.</p>
            </div>
        </div>
    </section>
</div>

<script>
    function handleCorporateSubmit(e) {
        e.preventDefault();
        
        // Fetch inputs
        const name = document.getElementById('name').value;
        const company = document.getElementById('company').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const quantity = document.getElementById('quantity').value;
        const occasion = document.getElementById('occasion').value;
        const message = document.getElementById('message').value;

        // Display a high-end luxury SweetAlert confirmation
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Enquiry Submitted',
                text: `Thank you, ${name}. Our Corporate Gifting Advisor will connect with you at ${email} shortly to discuss options for ${company}.`,
                confirmButtonColor: '#C89B2C',
                customClass: {
                    popup: 'rounded-2xl border border-gray-100 shadow-luxury',
                    confirmButton: 'px-5 py-2.5 text-xs font-bold uppercase rounded-lg tracking-wider'
                }
            });
        } else {
            alert(`Thank you, ${name}. We have received your B2B enquiry for ${company} and will get back to you shortly.`);
        }

        // Reset form
        e.target.reset();
    }

    // Micro-interactions with scroll reveal
    document.addEventListener('DOMContentLoaded', () => {
        // Scroll Reveal Observer
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.style.opacity = '1';
                    e.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.05 });

        document.querySelectorAll('.gifts-section, .gifts-grid > div, .steps > div, .testimonial-card').forEach((el) => {
            // Add inline styling for transition
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
            revealObserver.observe(el);
        });
    });
</script>

<?php include 'includes/footer.php'; ?>

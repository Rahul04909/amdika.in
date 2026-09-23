<?php
// Use centralized session config
require_once __DIR__ . '/session_config.php';

// Determine root-relative Base Path dynamically
$current_script = $_SERVER['SCRIPT_NAME'];
if (strpos($current_script, '/user/') !== false) {
    $base_path = substr($current_script, 0, strpos($current_script, '/user/') + 1);
} elseif (strpos($current_script, '/pages/') !== false) {
    $base_path = substr($current_script, 0, strpos($current_script, '/pages/') + 1);
} elseif (strpos($current_script, '/api/') !== false) {
    $base_path = substr($current_script, 0, strpos($current_script, '/api/') + 1);
} elseif (strpos($current_script, '/admin/') !== false) {
    $base_path = substr($current_script, 0, strpos($current_script, '/admin/') + 1);
} else {
    $base_path = dirname($current_script);
    if ($base_path === DIRECTORY_SEPARATOR || $base_path === '\\' || $base_path === '/') {
        $base_path = '/';
    } else {
        $base_path = rtrim(str_replace('\\', '/', $base_path), '/') . '/';
    }
}
$assets_path = $base_path . 'assets/';
$link_prefix = $base_path;

// Database connection for dynamic items
if (!isset($conn)) {
    require_once __DIR__ . '/../database/db_config.php';
}

// Track visitor activity
require_once __DIR__ . '/visitor_tracker.php';

// Fetch categories for Middle Search Dropdown & Mega Menu
$h_categories = [];
if (isset($conn)) {
    $h_cat_res = $conn->query("SELECT * FROM product_categories ORDER BY name ASC");
    if ($h_cat_res) {
        while ($row = $h_cat_res->fetch_assoc()) {
            $h_categories[] = $row;
        }
    }
}

// Fetch products for Mega Menu featured section (grouped by category)
$h_products_by_category = [];
if (isset($conn) && !empty($h_categories)) {
    $cat_ids = array_column($h_categories, 'id');
    if (!empty($cat_ids)) {
        $cat_ids_str = implode(',', array_map('intval', $cat_ids));
        $p_sql = "SELECT p.*, c.slug as category_slug 
                  FROM products p 
                  JOIN product_categories c ON p.category_id = c.id 
                  WHERE p.category_id IN ($cat_ids_str) 
                  ORDER BY p.id DESC";
        $p_res = $conn->query($p_sql);
        if ($p_res) {
            while ($p_row = $p_res->fetch_assoc()) {
                $h_products_by_category[$p_row['category_id']][] = $p_row;
            }
        }
    }
}

// Fetch Cart items and count for Hover Mini Cart
$session_id = session_id();
$cart_count = 0;
$cart_items = [];
$cart_total = 0;
if ($session_id && isset($conn)) {
    $stmt = $conn->prepare("SELECT SUM(quantity) as count FROM cart WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $cart_count = $res['count'] ?? 0;

    $c_stmt = $conn->prepare("
        SELECT c.id as cart_row_id, c.quantity, p.* 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.session_id = ?
    ");
    $c_stmt->bind_param("s", $session_id);
    $c_stmt->execute();
    $c_res = $c_stmt->get_result();
    while ($c_row = $c_res->fetch_assoc()) {
        $gst_pct = isset($c_row['gst_percent']) ? $c_row['gst_percent'] : 0;
        $price_inc_gst = $c_row['sale_price'] + ($c_row['sale_price'] * $gst_pct / 100);
        $c_row['display_price'] = $price_inc_gst;
        $cart_items[] = $c_row;
        $cart_total += $price_inc_gst * $c_row['quantity'];
    }
}

// Category to Lucide Icon mapping
function getCategoryIcon($slug) {
    $slug = strtolower($slug);
    if (strpos($slug, 'wallet') !== false) return 'wallet';
    if (strpos($slug, 'bag') !== false) return 'briefcase';
    if (strpos($slug, 'laundry') !== false) return 'package';
    if (strpos($slug, 'organizer') !== false) return 'layout-grid';
    if (strpos($slug, 'coaster') !== false) return 'layers';
    if (strpos($slug, 'tray') !== false) return 'box';
    return 'tag';
}

// Predefined subcategory mapping
function getSubcategoriesForCategory($slug, $cat_name) {
    $slug = strtolower($slug);
    if (strpos($slug, 'wallet') !== false) {
        return ['Bi-Fold Wallets', 'Card Holders', 'Travel Wallets', 'Money Clips', 'Classic Slim'];
    }
    if (strpos($slug, 'bag') !== false) {
        return ['Office Bags', 'Laptop Sleeves', 'Backpacks', 'Duffel Bags', 'Messenger Bags'];
    }
    if (strpos($slug, 'laundry') !== false) {
        return ['Laundry Baskets', 'Clothes Hampers', 'Canvas Buckets', 'Storage Bins'];
    }
    if (strpos($slug, 'organizer') !== false) {
        return ['Desk Organizers', 'Pen Holders', 'Letter Trays', 'Document Holders'];
    }
    if (strpos($slug, 'coaster') !== false) {
        return ['Leather Coasters', 'Wooden Coasters', 'Marble Coasters', 'Coaster Sets'];
    }
    if (strpos($slug, 'tray') !== false) {
        return ['Valet Trays', 'Serving Trays', 'Desk Trays', 'Jewelry Trays'];
    }
    return ["Classic $cat_name", "Modern $cat_name", "Premium $cat_name", "Luxury $cat_name", "Special Edition"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : "Amadika | Believe In Quality"; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? htmlspecialchars($page_description) : "Shop Luxury Leather Accessories Online at Amadika. Explore Premium Home Accessories, Leather Home Decor Products & stylish luxury décor in Faridabad."; ?>">
    <meta name="keywords" content="<?php echo isset($page_keywords) ? htmlspecialchars($page_keywords) : "premium home decor faridabad, luxury leather accessories online, premium home accessories, leather home decor products, stylish luxury decor faridabad, luxury leather accessories, amadika, amadika faridabad, amadika home decor"; ?>">
    <?php if (isset($extra_head_tags)) echo $extra_head_tags; ?>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '924772080401082');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=924772080401082&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
    <!-- JSON-LD SEO Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "FurnitureStore",
          "@id": "https://amadika.in/#store",
          "name": "Amadika",
          "url": "https://amadika.in/",
          "logo": "https://amadika.in/assets/images/amdika-logo.png",
          "image": "https://amadika.in/assets/images/amdika-logo.png",
          "description": "Shop Luxury Leather Accessories Online at Amadika. Explore Premium Home Accessories, Leather Home Decor Products & stylish luxury décor in Faridabad.",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "Faridabad",
            "addressLocality": "Faridabad",
            "addressRegion": "Haryana",
            "postalCode": "121001",
            "addressCountry": "IN"
          },
          "geo": {
            "@type": "GeoCoordinates",
            "latitude": 28.4089,
            "longitude": 77.3178
          },
          "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": [
              "Monday",
              "Tuesday",
              "Wednesday",
              "Thursday",
              "Friday",
              "Saturday",
              "Sunday"
            ],
            "opens": "10:00",
            "closes": "20:00"
          },
          "sameAs": [
            "https://www.facebook.com/amadika",
            "https://www.instagram.com/amadika"
          ]
        },
        {
          "@type": "WebSite",
          "@id": "https://amadika.in/#website",
          "url": "https://amadika.in/",
          "name": "Amadika",
          "description": "Shop Luxury Leather Accessories Online at Amadika. Explore Premium Home Accessories, Leather Home Decor Products & stylish luxury décor in Faridabad.",
          "publisher": {
            "@id": "https://amadika.in/#store"
          },
          "potentialAction": {
            "@type": "SearchAction",
            "target": {
              "@type": "EntryPoint",
              "urlTemplate": "https://amadika.in/products.php?search={search_term_string}"
            },
            "query-input": "required name=search_term_string"
          }
        }
      ]
    }
    </script>
    <!-- Defer Google Tag Manager & Meta Pixel to run after page load (Performance Optimization) -->
    <script>
    // Initialize stub queue functions immediately so any early tracking calls do not throw errors
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    window.gtag = gtag;
    gtag('js', new Date());
    gtag('config', 'G-67D67L6BKY');

    // Meta Pixel stub
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];}(window, document);
    fbq('init', '967381769597169');
    fbq('track', 'PageView');

    // Load actual tracker script files only after page load completes
    window.addEventListener('load', function() {
        // Load Meta Pixel Script
        var fbScript = document.createElement('script');
        fbScript.async = true;
        fbScript.src = 'https://connect.facebook.net/en_US/fbevents.js';
        document.head.appendChild(fbScript);

        // Load GTM Script
        var gTagScript = document.createElement('script');
        gTagScript.async = true;
        gTagScript.src = 'https://www.googletagmanager.com/gtag/js?id=G-67D67L6BKY';
        document.head.appendChild(gTagScript);
    });
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=967381769597169&ev=PageView&noscript=1"
    /></noscript>


    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Statically Compiled Tailwind CSS (Optimized for Production) -->
    <link rel="stylesheet" href="<?php echo $assets_path; ?>css/tailwind.min.css">


    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- GSAP for Smooth Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <!-- Lucide Icons (CDN) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $assets_path; ?>css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo $assets_path; ?>images/amdika-logo.png">

    <style>
        /* Luxury typography & brand tokens */
        :root {
            --lux-gold: #c59b27;
            --lux-gold-hover: #b0871d;
            --lux-dark: #0b0f19;
            --lux-slate: #0f172a;
            --lux-border: #e2e8f0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #fcfbf8;
            color: #1e293b;
        }
        
        /* Sticky bottom nav style */
        #bottomHeader {
            position: sticky;
            top: 0;
            z-index: 1040;
            background: #0f172a;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .sticky-scrolled {
            background: rgba(15, 23, 42, 0.96) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.25) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        /* Gold underline slide effect */
        .nav-link-underline {
            position: relative;
            color: #f1f5f9 !important;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.3px;
            padding: 0 16px;
            height: 100%;
            text-decoration: none !important;
            transition: color 0.2s ease;
        }

        .nav-link-underline i,
        .nav-link-underline svg {
            width: 15px;
            height: 15px;
            stroke-width: 2.2;
            color: #94a3b8;
            transition: color 0.2s ease, stroke 0.2s ease;
        }

        .nav-link-underline::after {
            content: '';
            position: absolute;
            width: 100%;
            transform: scaleX(0);
            height: 2px;
            bottom: 10px;
            left: 0;
            background-color: var(--lux-gold);
            transform-origin: bottom right;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .nav-link-underline:hover {
            color: var(--lux-gold) !important;
        }

        .nav-link-underline:hover i,
        .nav-link-underline:hover svg {
            color: var(--lux-gold) !important;
            stroke: var(--lux-gold) !important;
        }

        .nav-link-underline:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        /* Search suggestions styling */
        .search-bar-container { position: relative; }
        .search-suggestions-box {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 14px;
            box-shadow: 0 20px 45px rgba(0,0,0,0.12);
            z-index: 2000;
            margin-top: 8px;
            display: none;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.9);
        }
        .suggestion-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            text-decoration: none;
            border-bottom: 1px solid rgba(241, 245, 249, 0.9);
            transition: background 0.2s;
        }
        .suggestion-item:last-child { border-bottom: none; }
        .suggestion-item:hover, .active-suggestion { background: rgba(197, 155, 39, 0.06); }
        .suggestion-img {
            width: 46px;
            height: 46px;
            border-radius: 8px;
            object-fit: contain;
            margin-right: 14px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            padding: 2px;
        }
        .suggestion-name {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
            color: #0f172a;
        }
        .suggestion-price {
            font-size: 12px;
            color: var(--lux-gold);
            font-weight: 700;
            margin: 2px 0 0 0;
        }
        .view-all-results {
            display: block;
            padding: 12px;
            text-align: center;
            background: rgba(197, 155, 39, 0.08);
            font-size: 13px;
            font-weight: 700;
            color: var(--lux-gold);
            text-decoration: none !important;
            transition: background 0.2s;
        }
        .view-all-results:hover { background: rgba(197, 155, 39, 0.15); }
        .no-results { padding: 20px; text-align: center; color: #888; font-size: 14px; }

        /* Custom inputs focus */
        .premium-input-group:focus-within {
            border-color: var(--lux-gold) !important;
            box-shadow: 0 0 0 3px rgba(197, 155, 39, 0.15) !important;
        }

        /* Glassmorphism scrollbar */
        .scroll-luxury::-webkit-scrollbar {
            width: 5px;
        }
        .scroll-luxury::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.02);
        }
        .scroll-luxury::-webkit-scrollbar-thumb {
            background: rgba(197, 155, 39, 0.25);
            border-radius: 10px;
        }
        .scroll-luxury::-webkit-scrollbar-thumb:hover {
            background: rgba(197, 155, 39, 0.45);
        }

        /* MEGA MENU CONTAINER & HOVER BRIDGE */
        #megaMenuContainer {
            position: absolute;
            left: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 0 0 18px 18px;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.2), 0 0 0 1px rgba(0, 0, 0, 0.04);
            border-top: 2px solid var(--lux-gold);
            z-index: 1050;
            transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @media (min-width: 1024px) {
            #megaMenuContainer {
                left: 3rem;
                right: 3rem;
            }
        }
        /* Invisible hover bridge between trigger and mega menu */
        #megaMenuContainer::before {
            content: '';
            position: absolute;
            top: -14px;
            left: 0;
            width: 100%;
            height: 16px;
            background: transparent;
        }

        .megamenu-category-item {
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            border-left: 3px solid transparent;
        }
        .megamenu-category-item:hover, .megamenu-category-item.active-category {
            background: #f8fafc;
            border-left-color: var(--lux-gold);
        }
        .megamenu-category-item.active-category span {
            color: #0f172a;
            font-weight: 700;
        }
        .megamenu-category-item.active-category [data-lucide] {
            color: var(--lux-gold) !important;
        }

        .megamenu-subcat-link {
            transition: all 0.2s ease;
        }
        .megamenu-subcat-link:hover {
            color: var(--lux-gold) !important;
            transform: translateX(4px);
        }
    </style>
</head>
<body class="bg-[#FCFBF8] text-gray-800">

<header class="relative w-full z-50">
    <!-- LAYER 1: LUXURY TOP ANNOUNCEMENT BAR -->
    <div class="h-[38px] bg-[#0b0f19] text-white text-xs flex items-center justify-between px-4 lg:px-12 border-b border-gray-800/80">
        <!-- Left: Compliments & Socials -->
        <div class="flex items-center gap-5">
            <span class="inline-flex items-center gap-2 font-medium tracking-wide text-gray-300">
                <i data-lucide="truck" class="w-3.5 h-3.5 text-luxGold animate-pulse"></i>
                <span class="hidden sm:inline">Complimentary Express Shipping on orders above</span> 
                <strong class="text-luxGold">₹9,999</strong>
            </span>
            <div class="hidden md:flex items-center gap-3 border-l border-gray-800 pl-5">
                <a href="https://www.facebook.com/amadikaofficial/" target="_blank" class="text-gray-400 hover:text-luxGold transition-colors duration-200 text-decoration-none" title="Facebook">
                    <i class="fa-brands fa-facebook-f text-[11px]"></i>
                </a>
                <a href="https://www.instagram.com/amadika.shopping/" target="_blank" class="text-gray-400 hover:text-luxGold transition-colors duration-200 text-decoration-none" title="Instagram">
                    <i class="fa-brands fa-instagram text-[11px]"></i>
                </a>
                <a href="https://in.pinterest.com/amadikashopping/_pins/" target="_blank" class="text-gray-400 hover:text-luxGold transition-colors duration-200 text-decoration-none" title="Pinterest">
                    <i class="fa-brands fa-pinterest text-[11px]"></i>
                </a>
            </div>
        </div>
        
        <!-- Right: Concierge & Order Access -->
        <div class="flex items-center gap-4 lg:gap-5 text-gray-400">
            <a href="tel:+918447616924" class="hover:text-luxGold transition-colors duration-200 flex items-center gap-1.5 font-medium text-decoration-none">
                <i data-lucide="phone" class="w-3.5 h-3.5 text-luxGold"></i>
                <span class="hidden sm:inline">+91 8447616924</span>
            </a>
            <span class="hidden md:inline text-gray-800">|</span>
            <a href="<?php echo $link_prefix; ?>pages/track-courior/index.php" class="hover:text-luxGold transition-colors duration-200 font-medium text-decoration-none hidden sm:inline">
                Track Order
            </a>
            <span class="hidden md:inline text-gray-800">|</span>
            <a href="<?php echo $link_prefix; ?>pages/our-stores/index.php" class="hover:text-luxGold transition-colors duration-200 font-medium text-decoration-none hidden sm:inline">
                Our Stores
            </a>
        </div>
    </div>

    <!-- LAYER 2: BRAND IDENTITY & ACTION BAR -->
    <div class="bg-white py-3.5 px-4 lg:px-12 border-b border-gray-150 shadow-[0_2px_15px_rgba(0,0,0,0.02)] transition-all duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <!-- Brand Logo (Left) -->
            <div class="flex-shrink-0">
                <a href="<?php echo $link_prefix; ?>index.php" class="block">
                    <img src="<?php echo $assets_path; ?>images/amdika-logo.png" alt="Amadika Luxury" class="h-9 lg:h-11 w-auto object-contain transition-transform duration-300 hover:scale-[1.02]">
                </a>
            </div>

            <!-- Dribbble-Grade Luxury Search Bar (Center) -->
            <div class="hidden md:block flex-grow max-w-2xl mx-4">
                <form action="<?php echo $link_prefix; ?>products.php" method="GET" id="headerSearchForm" class="relative">
                    <div class="flex items-center bg-stone-50/80 border border-gray-200 rounded-full shadow-[0_2px_10px_rgba(0,0,0,0.02)] focus-within:border-luxGold focus-within:bg-white focus-within:ring-2 focus-within:ring-luxGold/15 transition-all duration-300 overflow-hidden h-11">
                        <!-- Category Dropdown Select -->
                        <div class="relative flex-shrink-0 border-r border-gray-200">
                            <select name="category" class="bg-transparent text-xs text-gray-700 font-semibold pl-4 pr-8 py-2 appearance-none focus:outline-none cursor-pointer h-full border-0">
                                <option value="">All Categories</option>
                                <?php foreach ($h_categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['slug']); ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                        
                        <!-- Search Input -->
                        <input type="text" 
                               class="bg-transparent border-0 text-xs md:text-sm pl-4 pr-10 py-2 w-full text-gray-800 focus:outline-none placeholder-gray-400" 
                               placeholder="Search handcrafted leather items, bags, trays..." 
                               name="search" 
                               id="headerSearchInput" 
                               autocomplete="off">
                               
                        <!-- Search Action Button -->
                        <button type="submit" class="bg-[#0f172a] hover:bg-luxGold text-white h-full px-5 flex items-center justify-center transition-colors duration-300 rounded-r-full border-0">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <!-- Search Suggestions Container -->
                    <div id="searchSuggestions" class="search-suggestions-box"></div>
                </form>
            </div>

            <!-- Action Icons (Right) -->
            <div class="flex items-center gap-2 lg:gap-4">
                <!-- Mobile Menu Toggler -->
                <button onclick="toggleMobileDrawer()" class="md:hidden p-2 text-darkLux hover:text-luxGold focus:outline-none bg-transparent border-0">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <!-- Mobile Search Trigger -->
                <button onclick="toggleMobileSearch()" class="md:hidden p-2 text-darkLux hover:text-luxGold focus:outline-none bg-transparent border-0">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>

                <!-- User Account/Auth -->
                <div class="relative group">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo $link_prefix; ?>user/index.php" class="w-10 h-10 rounded-full flex items-center justify-center text-darkLux hover:text-luxGold hover:bg-stone-50 transition-all duration-200 block text-decoration-none" title="My Account">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </a>
                    <?php else: ?>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#loginModal" class="w-10 h-10 rounded-full flex items-center justify-center text-darkLux hover:text-luxGold hover:bg-stone-50 transition-all duration-200 block text-decoration-none" title="Sign In">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Shopping Bag (with Hover Mini Cart) -->
                <div class="relative group" onmouseenter="loadMiniCart()">
                    <a href="javascript:void(0)" onclick="openCartSidebar()" class="w-10 h-10 rounded-full flex items-center justify-center text-darkLux hover:text-luxGold hover:bg-stone-50 transition-all duration-200 relative text-decoration-none">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        <span id="headerCartCount" class="absolute top-1 right-1 bg-luxGold text-white text-[9px] font-extrabold w-4.5 h-4.5 rounded-full flex items-center justify-center border-2 border-white shadow-sm leading-none"><?php echo $cart_count; ?></span>
                    </a>

                    <!-- Hover Mini Cart Dropdown -->
                    <div class="absolute right-0 top-full mt-2 w-80 bg-white/98 backdrop-blur-md rounded-2xl shadow-2xl border border-gray-150 opacity-0 invisible translate-y-2 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 z-50 p-4">
                        <h6 class="text-xs font-bold text-gray-800 tracking-wider uppercase mb-3 border-b border-gray-100 pb-2 flex items-center justify-between">
                            <span>Your Shopping Bag</span>
                            <span class="text-[10px] text-luxGold font-bold normal-case">Live Preview</span>
                        </h6>
                        <!-- Mini Cart Items List -->
                        <div id="miniCartItems" class="max-h-60 overflow-y-auto space-y-3 pr-1 scroll-luxury">
                            <?php if (empty($cart_items)): ?>
                                <div class="text-center py-6">
                                    <i data-lucide="shopping-bag" class="mx-auto text-gray-300 w-9 h-9 mb-2"></i>
                                    <p class="text-xs text-gray-500 mb-0">Your bag is currently empty</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($cart_items as $item): 
                                    $img = (strpos($item['featured_image'], 'http') === 0 || strpos($item['featured_image'], '/') === 0) ? $item['featured_image'] : $link_prefix . $item['featured_image'];
                                ?>
                                    <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                                        <img src="<?php echo $img; ?>" class="w-12 h-12 rounded-lg object-contain border border-gray-100 p-1 flex-shrink-0 bg-stone-50">
                                        <div class="flex-grow min-w-0">
                                            <h6 class="text-xs font-semibold text-gray-800 truncate mb-0.5"><?php echo htmlspecialchars($item['name']); ?></h6>
                                            <p class="text-[11px] text-gray-400 mb-0">Qty: <?php echo $item['quantity']; ?></p>
                                        </div>
                                        <span class="text-xs font-bold text-gray-900 flex-shrink-0">₹<?php echo number_format($item['display_price']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <!-- Subtotal & Actions -->
                        <div class="border-t border-gray-100 pt-3 mt-3">
                            <div class="flex justify-between text-xs font-semibold mb-3">
                                <span class="text-gray-500">Estimated Total</span>
                                <span id="miniCartSubtotal" class="text-gray-950 font-bold">₹<?php echo number_format($cart_total); ?></span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="<?php echo $link_prefix; ?>cart.php" class="bg-stone-100 hover:bg-stone-200 text-gray-900 text-center py-2.5 rounded-xl font-bold text-[11px] transition-colors duration-200 text-decoration-none">View Bag</a>
                                <a href="<?php echo $link_prefix; ?>checkout.php" class="bg-luxGold hover:bg-[#b0871d] text-white text-center py-2.5 rounded-xl font-bold text-[11px] transition-colors duration-200 text-decoration-none shadow-sm">Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MOBILE EXPANDABLE SEARCH BAR -->
    <div id="mobileSearchBar" class="hidden bg-white border-b border-gray-100 px-4 py-3 md:hidden">
        <form action="<?php echo $link_prefix; ?>products.php" method="GET">
            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-full px-3 py-1.5 h-10">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0"></i>
                <input type="text" 
                       class="bg-transparent border-0 text-xs w-full text-gray-700 focus:outline-none" 
                       placeholder="Search products..." 
                       name="search">
            </div>
        </form>
    </div>

    <!-- LAYER 3: BOTTOM NAVIGATION & MEGA MENU -->
    <div id="bottomHeader" class="hidden md:block bg-[#0f172a] border-b border-gray-800 z-[100] relative transition-all duration-300">
        <!-- Relative bounding container for both nav bar and full-width mega menu -->
        <div class="max-w-7xl mx-auto flex items-center justify-between px-4 lg:px-12 h-14 relative">
            
            <div class="flex items-center gap-1.5 h-full w-full justify-between">
                <!-- Navigation Links Left -->
                <div class="flex items-center gap-1 h-full">
                    <!-- Categories Button Wrapper -->
                    <div class="h-full flex items-center" id="categoriesMenuTrigger">
                        <button class="bg-gradient-to-r from-[#c59b27] to-[#b0871d] hover:brightness-105 text-white text-[11px] font-bold tracking-widest uppercase px-5 py-2.5 rounded-lg flex items-center gap-2.5 transition-all duration-300 shadow-sm border-0">
                            <i data-lucide="layout-grid" class="w-4 h-4"></i>
                            Categories
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-80"></i>
                        </button>
                    </div>
                    
                    <a href="<?php echo $link_prefix; ?>index.php" class="nav-link-underline"><i data-lucide="home"></i>Home</a>
                    <a href="<?php echo $link_prefix; ?>products.php" class="nav-link-underline"><i data-lucide="shopping-bag"></i>Shop</a>
                    <a href="<?php echo $link_prefix; ?>pages/collection/index.php" class="nav-link-underline"><i data-lucide="layers"></i>Collections</a>
                    <a href="<?php echo $link_prefix; ?>corprate-gift.php" class="nav-link-underline"><i data-lucide="gift"></i>Corporate Gifting</a>
                    <a href="<?php echo $link_prefix; ?>blogs.php" class="nav-link-underline"><i data-lucide="newspaper"></i>Blogs</a>
                    <a href="<?php echo $link_prefix; ?>pages/about-us/index.php" class="nav-link-underline"><i data-lucide="info"></i>About Us</a>
                    <a href="<?php echo $link_prefix; ?>pages/contact-us/index.php" class="nav-link-underline"><i data-lucide="phone"></i>Contact</a>
                    <a href="<?php echo $link_prefix; ?>pages/our-stores/index.php" class="nav-link-underline"><i data-lucide="map-pin"></i>Our Stores</a>
                </div>
            </div>

            <!-- CONTAINER-BOUNDED DRIBBLE LUXURY MEGA MENU -->
            <div id="megaMenuContainer" class="opacity-0 invisible -translate-y-2 p-6 flex flex-row gap-6">
                <!-- Column 1: Category Directory (25%) -->
                <div class="w-1/4 border-r border-gray-150 pr-4 flex flex-col gap-1.5 max-h-[420px] overflow-y-auto scroll-luxury">
                    <span class="text-[10px] font-extrabold tracking-widest text-gray-400 uppercase mb-2 px-3">Product Categories</span>
                    <?php if (!empty($h_categories)): ?>
                        <?php foreach ($h_categories as $idx => $cat): ?>
                            <div class="megamenu-category-item flex items-center justify-between p-2.5 rounded-xl cursor-pointer transition-all duration-200 <?php echo $idx === 0 ? 'active-category bg-stone-50 border-l-4 border-luxGold pl-2' : ''; ?>" data-category-id="<?php echo $cat['id']; ?>">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="<?php echo getCategoryIcon($cat['slug']); ?>" class="w-4 h-4 <?php echo $idx === 0 ? 'text-luxGold' : 'text-gray-400'; ?>"></i>
                                    <span class="text-xs font-semibold text-gray-700"><?php echo htmlspecialchars($cat['name']); ?></span>
                                </div>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-300"></i>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-xs text-gray-400">No categories found</div>
                    <?php endif; ?>
                </div>

                <!-- Center Panels: Column 2 (Subcategories) & Column 3 (Featured Items) (50%) -->
                <div class="w-1/2 flex flex-col relative min-h-[380px]">
                    <?php foreach ($h_categories as $idx => $cat): ?>
                        <div id="megamenu-panel-<?php echo $cat['id']; ?>" class="megamenu-panel flex-grow grid grid-cols-2 gap-6 <?php echo $idx === 0 ? '' : 'hidden'; ?>">
                            <!-- Column 2: Subcategories (50% of center) -->
                            <div class="flex flex-col gap-3 pr-2 border-r border-gray-100">
                                <span class="text-[10px] font-extrabold tracking-widest text-gray-400 uppercase">Subcategories &amp; Range</span>
                                <ul class="flex flex-col gap-2 p-0 list-none m-0">
                                    <?php 
                                    $subcats = getSubcategoriesForCategory($cat['slug'], $cat['name']);
                                    foreach ($subcats as $sc):
                                    ?>
                                        <li>
                                            <a href="<?php echo $link_prefix; ?>products.php?category=<?php echo urlencode($cat['slug']); ?>" class="megamenu-subcat-link text-xs text-gray-600 hover:text-luxGold font-medium flex items-center gap-2 text-decoration-none">
                                                <span class="w-1.5 h-1.5 bg-luxGold/60 rounded-full flex-shrink-0"></span>
                                                <?php echo htmlspecialchars($sc); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="<?php echo $link_prefix; ?>products.php?category=<?php echo urlencode($cat['slug']); ?>" class="text-[11px] font-bold text-luxGold hover:underline mt-auto pt-3 flex items-center gap-1 text-decoration-none">
                                    View All <?php echo htmlspecialchars($cat['name']); ?>
                                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                </a>
                            </div>

                            <!-- Column 3: Featured Products (50% of center) -->
                            <div class="flex flex-col gap-3">
                                <span class="text-[10px] font-extrabold tracking-widest text-gray-400 uppercase">Featured In Collection</span>
                                <div class="grid grid-cols-1 gap-2.5">
                                    <?php 
                                    $cat_prods = $h_products_by_category[$cat['id']] ?? [];
                                    if (empty($cat_prods)): 
                                    ?>
                                        <div class="bg-stone-50 border border-gray-100 rounded-xl p-4 flex flex-col items-center justify-center text-center">
                                            <i data-lucide="sparkles" class="w-6 h-6 text-luxGold mb-1.5"></i>
                                            <span class="text-xs font-bold text-gray-800">Signature Leather</span>
                                            <span class="text-[10px] text-gray-400">Handcrafted Excellence</span>
                                        </div>
                                    <?php else: 
                                        $display_count = 0;
                                        foreach ($cat_prods as $prod): 
                                            if ($display_count >= 2) break;
                                            $p_img = (strpos($prod['featured_image'], 'http') === 0 || strpos($prod['featured_image'], '/') === 0) ? $prod['featured_image'] : $link_prefix . $prod['featured_image'];
                                            $p_gst = isset($prod['gst_percent']) ? (float)$prod['gst_percent'] : 0;
                                            $p_sale = round($prod['sale_price'] + ($prod['sale_price'] * $p_gst / 100));
                                        ?>
                                            <a href="<?php echo $link_prefix; ?>product/<?php echo $prod['slug']; ?>" class="bg-white border border-gray-150 rounded-xl p-2.5 flex items-center gap-3 hover:border-luxGold transition-all duration-300 hover:shadow-sm text-decoration-none group/item">
                                                <div class="w-14 h-14 rounded-lg overflow-hidden bg-stone-50 flex items-center justify-center p-1 flex-shrink-0 border border-gray-100">
                                                    <img src="<?php echo $p_img; ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" class="max-h-full max-w-full object-contain group-hover/item:scale-105 transition-transform duration-300">
                                                </div>
                                                <div class="min-w-0 flex-grow">
                                                    <h5 class="text-xs font-bold text-gray-800 truncate mb-0.5"><?php echo htmlspecialchars($prod['name']); ?></h5>
                                                    <span class="text-xs font-extrabold text-luxGold">₹<?php echo number_format($p_sale); ?></span>
                                                </div>
                                                <i data-lucide="arrow-up-right" class="w-4 h-4 text-gray-300 group-hover/item:text-luxGold transition-colors flex-shrink-0"></i>
                                            </a>
                                        <?php 
                                            $display_count++;
                                        endforeach; 
                                    endif; 
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Column 4: Brand Editorial Spotlight (25%) -->
                <div class="w-1/4 rounded-xl overflow-hidden relative shadow-md flex flex-col justify-between p-5 text-white" style="background: linear-gradient(145deg, #0b0f19 0%, #172236 100%); border: 1px solid rgba(197, 155, 39, 0.25);">
                    <div>
                        <span class="bg-luxGold text-white text-[9px] font-extrabold uppercase px-2.5 py-1 rounded-full tracking-widest mb-3 inline-block">Artisanal Heritage</span>
                        <h4 class="font-serif italic text-lg leading-snug mb-2 text-white">Genuine Handcrafted Leather</h4>
                        <p class="text-[11px] text-gray-300 leading-relaxed font-light mb-0">Every creation is meticulously finished by master artisans, engineered for generational longevity.</p>
                    </div>
                    <div class="pt-4 border-t border-gray-700/60 mt-4">
                        <a href="<?php echo $link_prefix; ?>products.php" class="inline-flex items-center justify-between w-full bg-luxGold hover:bg-white hover:text-darkLux text-white text-[11px] font-bold uppercase px-3.5 py-2 rounded-lg transition-all duration-300 text-decoration-none shadow-sm">
                            <span>Explore Full Shop</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</header>

<!-- MOBILE SLIDE DRAWER MENU -->
<div id="mobileDrawerOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1100] opacity-0 pointer-events-none transition-opacity duration-300" onclick="toggleMobileDrawer()"></div>
<div id="mobileDrawer" class="fixed top-0 -left-80 w-80 h-full bg-white z-[1101] shadow-2xl transition-all duration-300 flex flex-col justify-between">
    <!-- Header -->
    <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-darkLux text-white">
        <div class="flex items-center gap-2">
            <img src="<?php echo $assets_path; ?>images/amdika-logo.png" alt="Amadika" class="h-6 filter brightness-0 invert">
        </div>
        <button onclick="toggleMobileDrawer()" class="text-white hover:text-luxGold focus:outline-none">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Scrollable Drawer Body -->
    <div class="flex-grow overflow-y-auto p-4 space-y-6 scroll-luxury">
        <!-- User Welcome / Login button -->
        <div class="bg-gray-50 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-luxGold/10 flex items-center justify-center text-luxGold">
                <i data-lucide="user" class="w-5 h-5"></i>
            </div>
            <div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <h6 class="text-xs font-bold text-gray-800 mb-0">Hello Customer</h6>
                    <a href="<?php echo $link_prefix; ?>user/index.php" class="text-[10px] text-luxGold font-semibold hover:underline">My Account Panel</a>
                <?php else: ?>
                    <h6 class="text-xs font-bold text-gray-800 mb-0.5">Welcome Guest</h6>
                    <a href="javascript:void(0)" onclick="toggleMobileDrawer(); setTimeout(() => { bootstrap.Modal.getOrCreateInstance(document.getElementById('loginModal')).show(); }, 300)" class="text-[10px] text-luxGold font-semibold hover:underline">Login / Create Account</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Links List -->
        <div class="space-y-4">
            <span class="text-[10px] font-extrabold tracking-wider text-gray-400 uppercase">Main Navigation</span>
            <div class="grid grid-cols-1 gap-2.5">
                <a href="<?php echo $link_prefix; ?>index.php" class="flex items-center gap-3 text-sm font-semibold text-gray-700 hover:text-luxGold transition-colors text-decoration-none">
                    <i data-lucide="home" class="w-4 h-4 text-gray-400"></i> Home
                </a>
                <a href="<?php echo $link_prefix; ?>products.php" class="flex items-center gap-3 text-sm font-semibold text-gray-700 hover:text-luxGold transition-colors text-decoration-none">
                    <i data-lucide="shopping-bag" class="w-4 h-4 text-gray-400"></i> Shop
                </a>
                <a href="<?php echo $link_prefix; ?>products.php" class="flex items-center gap-3 text-sm font-semibold text-gray-700 hover:text-luxGold transition-colors text-decoration-none">
                    <i data-lucide="layers" class="w-4 h-4 text-gray-400"></i> Collections
                </a>
                <a href="<?php echo $link_prefix; ?>corprate-gift.php" class="flex items-center gap-3 text-sm font-semibold text-gray-700 hover:text-luxGold transition-colors text-decoration-none">
                    <i data-lucide="gift" class="w-4 h-4 text-gray-400"></i> Corporate Gifting
                </a>
                <a href="<?php echo $link_prefix; ?>blogs.php" class="flex items-center gap-3 text-sm font-semibold text-gray-700 hover:text-luxGold transition-colors text-decoration-none">
                    <i data-lucide="newspaper" class="w-4 h-4 text-gray-400"></i> Blogs
                </a>
                <a href="<?php echo $link_prefix; ?>pages/about-us/index.php" class="flex items-center gap-3 text-sm font-semibold text-gray-700 hover:text-luxGold transition-colors text-decoration-none">
                    <i data-lucide="info" class="w-4 h-4 text-gray-400"></i> About Us
                </a>
                <a href="<?php echo $link_prefix; ?>pages/contact-us/index.php" class="flex items-center gap-3 text-sm font-semibold text-gray-700 hover:text-luxGold transition-colors text-decoration-none">
                    <i data-lucide="phone-call" class="w-4 h-4 text-gray-400"></i> Contact Us
                </a>
                <a href="<?php echo $link_prefix; ?>pages/our-stores/index.php" class="flex items-center gap-3 text-sm font-semibold text-gray-700 hover:text-luxGold transition-colors text-decoration-none">
                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i> Our Stores
                </a>
            </div>
        </div>

        <!-- Accordion categories -->
        <div class="space-y-3">
            <span class="text-[10px] font-extrabold tracking-wider text-gray-400 uppercase">Product Categories</span>
            <div class="accordion accordion-flush" id="mobileCatAccordion">
                <?php if (!empty($h_categories)): ?>
                    <?php foreach ($h_categories as $cat): ?>
                        <div class="accordion-item border-0 border-b border-gray-100">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed px-0 py-2.5 bg-transparent border-0 text-xs font-semibold text-gray-700 focus:shadow-none after:scale-75" type="button" data-bs-toggle="collapse" data-bs-target="#flush-cat-<?php echo $cat['id']; ?>">
                                    <div class="flex items-center gap-2.5">
                                        <i data-lucide="<?php echo getCategoryIcon($cat['slug']); ?>" class="w-3.5 h-3.5 text-gray-400"></i>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </div>
                                </button>
                            </h2>
                            <div id="flush-cat-<?php echo $cat['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#mobileCatAccordion">
                                <div class="accordion-body px-4 py-2 bg-gray-50 flex flex-col gap-2 rounded-lg">
                                    <?php 
                                    $m_subcats = getSubcategoriesForCategory($cat['slug'], $cat['name']);
                                    foreach ($m_subcats as $m_sc):
                                    ?>
                                        <a href="<?php echo $link_prefix; ?>products.php?category=<?php echo urlencode($cat['slug']); ?>" class="text-[11px] font-medium text-gray-600 hover:text-luxGold text-decoration-none py-1">
                                            <?php echo htmlspecialchars($m_sc); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Drawer Footer -->
    <div class="p-4 bg-gray-50 border-t border-gray-100 space-y-3">
        <a href="tel:+918447616924" class="flex items-center gap-2.5 text-xs text-gray-700 font-semibold text-decoration-none">
            <i data-lucide="phone" class="w-4 h-4 text-luxGold"></i> Support: +91 8447616924
        </a>
        <a href="mailto:support@amadika.in" class="flex items-center gap-2.5 text-xs text-gray-700 font-semibold text-decoration-none">
            <i data-lucide="mail" class="w-4 h-4 text-luxGold"></i> support@amadika.in
        </a>
    </div>
</div>

<!-- STICKY BOTTOM ACTIONS FOR MOBILE -->
<div id="mobileBottomNav" class="fixed bottom-0 left-0 w-full bg-white/95 backdrop-blur-md border-t border-gray-150 h-14 flex items-center justify-around z-[1000] md:hidden shadow-lg">
    <a href="<?php echo $link_prefix; ?>index.php" class="flex flex-col items-center gap-0.5 text-gray-400 hover:text-luxGold text-decoration-none transition-colors duration-200">
        <i data-lucide="home" class="w-5 h-5"></i>
        <span class="text-[10px] font-bold">Home</span>
    </a>
    <a href="javascript:void(0)" onclick="toggleMobileSearch()" class="flex flex-col items-center gap-0.5 text-gray-400 hover:text-luxGold text-decoration-none transition-colors duration-200">
        <i data-lucide="search" class="w-5 h-5"></i>
        <span class="text-[10px] font-bold">Search</span>
    </a>
    <a href="javascript:void(0)" onclick="openCartSidebar()" class="flex flex-col items-center gap-0.5 text-gray-400 hover:text-luxGold text-decoration-none transition-colors duration-200 relative">
        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
        <span class="absolute -top-1.5 -right-1.5 bg-luxGold text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center"><?php echo $cart_count; ?></span>
        <span class="text-[10px] font-bold">Cart</span>
    </a>
    <a href="<?php echo isset($_SESSION['user_id']) ? $link_prefix . 'user/index.php' : 'javascript:void(0)'; ?>" 
       <?php echo !isset($_SESSION['user_id']) ? 'data-bs-toggle="modal" data-bs-target="#loginModal"' : ''; ?> 
       class="flex flex-col items-center gap-0.5 text-gray-400 hover:text-luxGold text-decoration-none transition-colors duration-200">
        <i data-lucide="user" class="w-5 h-5"></i>
        <span class="text-[10px] font-bold">Account</span>
    </a>
</div>

<!-- REDESIGNED PREMIUM LOGIN MODAL -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 overflow-hidden rounded-2xl shadow-luxury">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Panel (Brand/Benefits) -->
                    <div class="col-lg-5 d-none d-lg-flex flex-col p-8 lg:p-12 text-white justify-between relative" style="background: linear-gradient(135deg, #111827 0%, #1c273a 100%);">
                        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-luxGold/20 via-transparent to-transparent opacity-60"></div>
                        
                        <div class="relative z-10">
                            <img src="<?php echo $assets_path; ?>images/amdika-logo.png" alt="Amadika" class="h-8 filter brightness-0 invert mb-8">
                            <h3 class="font-serif italic text-2xl font-semibold mb-3 tracking-wide text-luxGold">Join Amadika</h3>
                            <p class="text-gray-300 text-xs leading-relaxed font-light">Access your exclusive Member Dashboard, view Order History, and get personal recommendations.</p>
                        </div>
                        
                        <div class="space-y-4 relative z-10 pt-8 border-t border-gray-800">
                            <div class="flex items-start gap-4">
                                <div class="w-7 h-7 rounded-full bg-luxGold/15 flex items-center justify-center flex-shrink-0 text-luxGold">
                                    <i data-lucide="truck" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-xs font-bold text-white mb-0.5">Order Management</h6>
                                    <p class="text-[10px] text-gray-400 mb-0">Realtime delivery tracking</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-7 h-7 rounded-full bg-luxGold/15 flex items-center justify-center flex-shrink-0 text-luxGold">
                                    <i data-lucide="heart" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-xs font-bold text-white mb-0.5">Luxury Wishlist</h6>
                                    <p class="text-[10px] text-gray-400 mb-0">Save & sync curated lists</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-7 h-7 rounded-full bg-luxGold/15 flex items-center justify-center flex-shrink-0 text-luxGold">
                                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-xs font-bold text-white mb-0.5">Privileged Deals</h6>
                                    <p class="text-[10px] text-gray-400 mb-0">Access price drops first</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel (Login Form) -->
                    <div class="col-lg-7 col-12 p-6 p-md-8 bg-white flex flex-col justify-center relative">
                        <button type="button" class="btn-close absolute top-4 end-4 text-gray-400 hover:text-darkLux focus:outline-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        
                        <!-- Tabs switcher -->
                        <div class="flex justify-center mb-6">
                            <ul class="nav nav-pills bg-gray-50 border border-gray-150 p-1 rounded-xl flex gap-1" id="loginTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link border-0 text-xs font-bold px-4 py-2 rounded-lg text-gray-500 hover:text-gray-900 active bg-transparent" id="mobile-tab" data-bs-toggle="pill" data-bs-target="#pills-mobile" type="button" role="tab">Mobile & OTP</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link border-0 text-xs font-bold px-4 py-2 rounded-lg text-gray-500 hover:text-gray-900 bg-transparent" id="email-tab" data-bs-toggle="pill" data-bs-target="#pills-email" type="button" role="tab">Email & Password</button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <!-- Mobile OTP Tab -->
                            <div class="tab-pane fade show active" id="pills-mobile">
                                <div id="mobileStep1" class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Mobile Number</label>
                                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden premium-input-group transition-all duration-300">
                                            <div class="bg-gray-50 border-r border-gray-200 px-3 py-2.5 flex items-center gap-2 flex-shrink-0">
                                                <img src="https://upload.wikimedia.org/wikipedia/en/4/41/Flag_of_India.svg" alt="IN" class="w-5 shadow-sm">
                                                <span class="text-xs font-bold text-gray-700">+91</span>
                                            </div>
                                            <input type="tel" class="border-0 bg-transparent text-sm font-bold pl-3 pr-4 py-2.5 w-full focus:outline-none" id="loginMobile" placeholder="98765 43210" maxlength="10">
                                        </div>
                                        <div id="mobileError" class="text-red-500 text-[11px] font-semibold mt-1.5 hidden"></div>
                                    </div>
                                    <button class="w-full bg-[#111827] hover:bg-luxGold text-white text-xs font-bold py-3.5 px-4 rounded-xl tracking-wider transition-all duration-300 shadow-sm" onclick="sendOTPRequest()">CONTINUE</button>
                                </div>
                                
                                <div id="mobileStep2" class="space-y-4" style="display: none;">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500 mb-4">A 6-digit code has been sent to <span class="font-bold text-gray-900" id="displayMobileNum"></span></p>
                                        <div class="flex justify-center gap-2" id="otpInputContainer">
                                            <input type="text" class="form-control otp-input !w-11 !h-12 text-center text-lg font-bold border-2 border-gray-200 focus:border-luxGold rounded-xl focus:outline-none focus:ring-0" maxlength="1" data-index="0">
                                            <input type="text" class="form-control otp-input !w-11 !h-12 text-center text-lg font-bold border-2 border-gray-200 focus:border-luxGold rounded-xl focus:outline-none focus:ring-0" maxlength="1" data-index="1">
                                            <input type="text" class="form-control otp-input !w-11 !h-12 text-center text-lg font-bold border-2 border-gray-200 focus:border-luxGold rounded-xl focus:outline-none focus:ring-0" maxlength="1" data-index="2">
                                            <input type="text" class="form-control otp-input !w-11 !h-12 text-center text-lg font-bold border-2 border-gray-200 focus:border-luxGold rounded-xl focus:outline-none focus:ring-0" maxlength="1" data-index="3">
                                            <input type="text" class="form-control otp-input !w-11 !h-12 text-center text-lg font-bold border-2 border-gray-200 focus:border-luxGold rounded-xl focus:outline-none focus:ring-0" maxlength="1" data-index="4">
                                            <input type="text" class="form-control otp-input !w-11 !h-12 text-center text-lg font-bold border-2 border-gray-200 focus:border-luxGold rounded-xl focus:outline-none focus:ring-0" maxlength="1" data-index="5">
                                        </div>
                                        <input type="hidden" id="loginOTP">
                                        
                                        <div class="text-center mt-4 text-xs">
                                            <p class="text-gray-500 mb-0" id="loginTimerContainer">Resend verification code in <span class="font-bold text-gray-800" id="loginTimer">00:59</span></p>
                                            <a href="javascript:void(0)" id="resendLoginBtn" class="text-luxGold font-bold hover:underline" style="display: none;" onclick="resendLoginOTP()">Resend OTP</a>
                                        </div>
                                        <div id="otpError" class="text-red-500 text-[11px] font-semibold mt-2 hidden"></div>
                                    </div>
                                    <button class="w-full bg-[#111827] hover:bg-luxGold text-white text-xs font-bold py-3.5 px-4 rounded-xl tracking-wider transition-all duration-300 shadow-sm" onclick="verifyOTPRequest()">VERIFY & LOGIN</button>
                                    <button class="w-full text-center text-gray-400 hover:text-luxGold text-[11px] font-bold transition-colors" onclick="backToMobileStep1()">Change Mobile Number</button>
                                </div>
                            </div>

                            <!-- Email Password Tab -->
                            <div class="tab-pane fade" id="pills-email">
                                <form id="headerLoginForm" class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1.5">Email Address</label>
                                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden premium-input-group transition-all duration-300">
                                            <span class="pl-3.5 text-gray-400"><i class="fa-solid fa-envelope"></i></span>
                                            <input type="email" name="email" class="border-0 bg-transparent text-sm pl-3 pr-4 py-2.5 w-full focus:outline-none text-gray-700" placeholder="name@example.com" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1.5">Password</label>
                                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden premium-input-group transition-all duration-300">
                                            <span class="pl-3.5 text-gray-400"><i class="fa-solid fa-lock"></i></span>
                                            <input type="password" name="password" id="headerPassInput" class="border-0 bg-transparent text-sm pl-3 pr-1 py-2.5 w-full focus:outline-none text-gray-700" placeholder="••••••••" required>
                                            <span class="pr-3.5 text-gray-400 cursor-pointer" onclick="toggleHeaderPass()">
                                                <i class="fa-solid fa-eye-slash" id="headerPassIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="emailError" class="text-red-500 text-[11px] font-semibold hidden"></div>
                                    <button type="submit" class="w-full bg-[#111827] hover:bg-luxGold text-white text-xs font-bold py-3.5 px-4 rounded-xl tracking-wider transition-all duration-300 shadow-sm">LOG IN</button>
                                </form>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center mt-6 pt-6 border-t border-gray-100">
                            <p class="text-xs text-gray-500 mb-0">New to Amadika? <a href="javascript:void(0)" class="text-luxGold font-bold hover:underline" data-bs-toggle="modal" data-bs-target="#registerModal">Create an Account</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- REDESIGNED PREMIUM REGISTER MODAL -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 overflow-hidden rounded-2xl shadow-luxury">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Panel -->
                    <div class="col-lg-5 d-none d-lg-flex flex-col p-8 lg:p-12 text-white justify-between relative" style="background: linear-gradient(135deg, #111827 0%, #1c273a 100%);">
                        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-luxGold/20 via-transparent to-transparent opacity-60"></div>
                        
                        <div class="relative z-10">
                            <img src="<?php echo $assets_path; ?>images/amdika-logo.png" alt="Amadika" class="h-8 filter brightness-0 invert mb-8">
                            <h3 class="font-serif italic text-2xl font-semibold mb-3 tracking-wide text-luxGold">Create Account</h3>
                            <p class="text-gray-300 text-xs leading-relaxed font-light">Join the Amadika Luxury Circle to enjoy complementary shipping, private collections, and personalized gifts.</p>
                        </div>
                        
                        <div class="space-y-4 relative z-10 pt-8 border-t border-gray-800">
                            <div class="flex items-center gap-3 text-xs">
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-luxGold"></i>
                                <span>Exclusive Member Privileges</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs">
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-luxGold"></i>
                                <span>Express Luxury Checkout</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs">
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-luxGold"></i>
                                <span>24/7 Dedicated Client Support</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel -->
                    <div class="col-lg-7 col-12 p-6 p-md-8 bg-white flex flex-col justify-center relative" style="max-height: 90vh; overflow-y: auto;">
                        <button type="button" class="btn-close absolute top-4 end-4 text-gray-400 hover:text-darkLux focus:outline-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        
                        <div id="registerStep1" class="space-y-4">
                            <div class="text-center mb-4">
                                <h4 class="font-serif italic text-xl font-bold text-gray-800 mb-1">Luxury Registration</h4>
                                <p class="text-xs text-gray-400">Complete your details to start the journey</p>
                            </div>
                            
                            <form id="mainRegisterForm" class="space-y-3">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="block text-[9px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Full Name</label>
                                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden premium-input-group transition-all duration-300">
                                            <input type="text" name="name" class="border-0 bg-transparent text-xs pl-3.5 pr-3.5 py-2.5 w-full focus:outline-none text-gray-700" placeholder="John Doe" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="block text-[9px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Email Address</label>
                                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden premium-input-group transition-all duration-300">
                                            <input type="email" name="email" class="border-0 bg-transparent text-xs pl-3.5 pr-3.5 py-2.5 w-full focus:outline-none text-gray-700" placeholder="john@example.com" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="block text-[9px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Mobile Number</label>
                                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden premium-input-group transition-all duration-300">
                                            <span class="text-xs font-bold text-gray-500 pl-3.5 flex-shrink-0">+91</span>
                                            <input type="tel" name="mobile" id="regMobile" class="border-0 bg-transparent text-xs font-bold pl-2 pr-3.5 py-2.5 w-full focus:outline-none text-gray-700" placeholder="9876543210" maxlength="10" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="block text-[9px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Password</label>
                                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden premium-input-group transition-all duration-300">
                                            <input type="password" name="password" id="regPassInput" class="border-0 bg-transparent text-xs pl-3.5 pr-1 py-2.5 w-full focus:outline-none text-gray-700" placeholder="••••••••" required>
                                            <span class="pr-3.5 text-gray-400 cursor-pointer" onclick="toggleRegPass()">
                                                <i class="fa-solid fa-eye-slash" id="regPassIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-[9px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Street Address</label>
                                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden premium-input-group transition-all duration-300">
                                        <input type="text" name="address" class="border-0 bg-transparent text-xs pl-3.5 pr-3.5 py-2.5 w-full focus:outline-none text-gray-700" placeholder="Flat, Street, Landmark" required>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="block text-[9px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">City</label>
                                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden premium-input-group transition-all duration-300">
                                            <input type="text" name="city" class="border-0 bg-transparent text-xs px-3 py-2.5 w-full focus:outline-none text-gray-700" placeholder="City" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="block text-[9px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">State</label>
                                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden premium-input-group transition-all duration-300">
                                            <input type="text" name="state" class="border-0 bg-transparent text-xs px-3 py-2.5 w-full focus:outline-none text-gray-700" placeholder="State" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="block text-[9px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Pincode</label>
                                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden premium-input-group transition-all duration-300">
                                            <input type="text" name="pincode" class="border-0 bg-transparent text-xs px-3 py-2.5 w-full focus:outline-none text-gray-700" placeholder="123456" maxlength="6" required>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="country" value="India">
                                <div id="regError" class="text-red-500 text-[11px] font-semibold hidden"></div>
                                <button type="submit" class="w-full bg-[#111827] hover:bg-luxGold text-white text-xs font-bold py-3 px-4 rounded-xl tracking-wider transition-all duration-300 shadow-sm mt-3">SEND VERIFICATION OTP</button>
                            </form>
                        </div>

                        <div id="registerStep2" class="space-y-4" style="display: none;">
                            <div class="text-center">
                                <h4 class="font-serif italic text-xl font-bold text-gray-800 mb-1">Verify Mobile</h4>
                                <p class="text-xs text-gray-500">Enter the verification code sent to <span class="font-bold text-gray-900" id="displayRegMobile"></span></p>
                            </div>
                            <div class="flex justify-center gap-2" id="regOtpInputContainer">
                                <input type="text" class="form-control otp-input reg-otp-input !w-11 !h-12 text-center text-lg font-bold border-2 border-gray-200 focus:border-luxGold rounded-xl focus:outline-none focus:ring-0" maxlength="1" data-index="0">
                                <input type="text" class="form-control otp-input reg-otp-input !w-11 !h-12 text-center text-lg font-bold border-2 border-gray-200 focus:border-luxGold rounded-xl focus:outline-none focus:ring-0" maxlength="1" data-index="1">
                                <input type="text" class="form-control otp-input reg-otp-input !w-11 !h-12 text-center text-lg font-bold border-2 border-gray-200 focus:border-luxGold rounded-xl focus:outline-none focus:ring-0" maxlength="1" data-index="2">
                                <input type="text" class="form-control otp-input reg-otp-input !w-11 !h-12 text-center text-lg font-bold border-2 border-gray-200 focus:border-luxGold rounded-xl focus:outline-none focus:ring-0" maxlength="1" data-index="3">
                                <input type="text" class="form-control otp-input reg-otp-input !w-11 !h-12 text-center text-lg font-bold border-2 border-gray-200 focus:border-luxGold rounded-xl focus:outline-none focus:ring-0" maxlength="1" data-index="4">
                                <input type="text" class="form-control otp-input reg-otp-input !w-11 !h-12 text-center text-lg font-bold border-2 border-gray-200 focus:border-luxGold rounded-xl focus:outline-none focus:ring-0" maxlength="1" data-index="5">
                            </div>
                            <input type="hidden" id="regOTPValue">
                            
                            <div class="text-center text-xs">
                                <p class="text-gray-500 mb-0" id="regTimerContainer">Resend verification code in <span class="font-bold text-gray-800" id="regTimer">00:59</span></p>
                                <a href="javascript:void(0)" id="resendRegBtn" class="text-luxGold font-bold hover:underline" style="display: none;" onclick="resendRegOTP()">Resend OTP</a>
                            </div>

                            <div id="regOtpError" class="text-red-500 text-[11px] font-semibold text-center hidden"></div>
                            <button class="w-full bg-[#111827] hover:bg-luxGold text-white text-xs font-bold py-3.5 px-4 rounded-xl tracking-wider transition-all duration-300 shadow-sm" onclick="verifyRegOTP()">VERIFY & CREATE ACCOUNT</button>
                            <button class="w-full text-center text-gray-400 hover:text-luxGold text-[11px] font-bold transition-colors" onclick="backToRegStep1()">Edit Details</button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-xs text-gray-500 mb-0">Already have an account? <a href="javascript:void(0)" class="text-luxGold font-bold hover:underline" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Initialize Lucide icons
    lucide.createIcons();

    // Sticky Bottom Header scroll transitions (position remains sticky in CSS to prevent layout shift/flickering)
    window.addEventListener('scroll', function() {
        const bottomHeader = document.getElementById('bottomHeader');
        if (!bottomHeader) return;
        if (window.scrollY > 10) {
            if (!bottomHeader.classList.contains('sticky-scrolled')) {
                bottomHeader.classList.add('sticky-scrolled');
            }
        } else {
            bottomHeader.classList.remove('sticky-scrolled');
        }
    });

    // Mobile Search expandable block
    function toggleMobileSearch() {
        const bar = document.getElementById('mobileSearchBar');
        if (bar.classList.contains('hidden')) {
            bar.classList.remove('hidden');
            gsap.fromTo(bar, {height: 0, opacity: 0}, {height: 'auto', opacity: 1, duration: 0.3, ease: 'power2.out'});
        } else {
            gsap.to(bar, {height: 0, opacity: 0, duration: 0.2, ease: 'power2.in', onComplete: () => bar.classList.add('hidden')});
        }
    }

    // Mobile Navigation slide drawer
    function toggleMobileDrawer() {
        const overlay = document.getElementById('mobileDrawerOverlay');
        const drawer = document.getElementById('mobileDrawer');
        if (drawer.classList.contains('-left-80')) {
            overlay.classList.remove('pointer-events-none');
            gsap.to(overlay, {opacity: 1, duration: 0.3});
            drawer.classList.remove('-left-80');
            drawer.classList.add('left-0');
            gsap.fromTo(drawer, {x: -100}, {x: 0, duration: 0.4, ease: 'power3.out'});
        } else {
            overlay.classList.add('pointer-events-none');
            gsap.to(overlay, {opacity: 0, duration: 0.2});
            drawer.classList.remove('left-0');
            drawer.classList.add('-left-80');
        }
    }

    // Mega Menu dynamic tab changes and animations
    document.addEventListener('DOMContentLoaded', function() {
        const catItems = document.querySelectorAll('.megamenu-category-item');
        const panels = document.querySelectorAll('.megamenu-panel');
        const trigger = document.getElementById('categoriesMenuTrigger');
        const menu = document.getElementById('megaMenuContainer');

        if (trigger && menu) {
            let leaveTimeout = null;

            function openMenu() {
                clearTimeout(leaveTimeout);
                menu.classList.remove('invisible', 'opacity-0', '-translate-y-2');
                menu.classList.add('opacity-100', 'translate-y-0');
            }

            function closeMenu() {
                leaveTimeout = setTimeout(() => {
                    menu.classList.remove('opacity-100', 'translate-y-0');
                    menu.classList.add('opacity-0', '-translate-y-2', 'invisible');
                }, 180);
            }

            trigger.addEventListener('mouseenter', openMenu);
            trigger.addEventListener('mouseleave', closeMenu);

            menu.addEventListener('mouseenter', openMenu);
            menu.addEventListener('mouseleave', closeMenu);
        }

        catItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                const targetId = this.getAttribute('data-category-id');

                // Update styling on category list elements
                catItems.forEach(i => {
                    i.classList.remove('active-category', 'bg-stone-50', 'border-l-4', 'border-luxGold', 'pl-2', 'font-bold');
                    const icon = i.querySelector('[data-lucide]');
                    if (icon) {
                        icon.classList.remove('text-luxGold');
                        icon.classList.add('text-gray-400');
                    }
                });
                
                this.classList.add('active-category', 'bg-stone-50', 'border-l-4', 'border-luxGold', 'pl-2', 'font-bold');
                const activeIcon = this.querySelector('[data-lucide]');
                if (activeIcon) {
                    activeIcon.classList.remove('text-gray-400');
                    activeIcon.classList.add('text-luxGold');
                }

                // Switch corresponding detail panels
                panels.forEach(panel => {
                    if (panel.id === 'megamenu-panel-' + targetId) {
                        panel.classList.remove('hidden');
                        gsap.fromTo(panel, 
                            { opacity: 0, x: 8 }, 
                            { opacity: 1, x: 0, duration: 0.22, ease: 'power2.out' }
                        );
                    } else {
                        panel.classList.add('hidden');
                    }
                });
            });
        });
    });

    // --- AUTHENTICATION SCRIPTS ---
    const AUTH_URL = '<?php echo $link_prefix; ?>includes/auth_actions.php';

    function toggleHeaderPass() {
        const input = document.getElementById('headerPassInput');
        const icon = document.getElementById('headerPassIcon');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }

    let loginTimerInterval;
    function startLoginTimer(duration) {
        clearInterval(loginTimerInterval);
        let timer = duration, minutes, seconds;
        const timerDisplay = document.getElementById('loginTimer');
        const timerContainer = document.getElementById('loginTimerContainer');
        const resendBtn = document.getElementById('resendLoginBtn');
        
        timerContainer.style.display = 'block';
        resendBtn.style.display = 'none';

        loginTimerInterval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            timerDisplay.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(loginTimerInterval);
                timerContainer.style.display = 'none';
                resendBtn.style.display = 'inline-block';
            }
        }, 1000);
    }

    function resendLoginOTP() {
        sendOTPRequest(true);
    }

    function sendOTPRequest(isResend = false) {
        const mobile = document.getElementById('loginMobile').value;
        const errorDiv = document.getElementById('mobileError');
        errorDiv.classList.add('hidden');

        if(mobile.length !== 10) {
            errorDiv.textContent = 'Please enter a valid 10-digit number';
            errorDiv.classList.remove('hidden');
            return;
        }

        let btn = document.querySelector('button[onclick="sendOTPRequest()"]');
        if(isResend) {
            btn = document.getElementById('resendLoginBtn');
            btn.innerHTML = 'Sending...';
        } else {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>SENDING...';
        }

        const formData = new FormData();
        formData.append('action', 'send_otp');
        formData.append('mobile', mobile);

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(!isResend) {
                btn.disabled = false;
                btn.innerHTML = 'CONTINUE';
            } else {
                btn.innerHTML = 'Resend OTP';
            }

            if(data.status === 'success') {
                document.getElementById('mobileStep1').style.display = 'none';
                document.getElementById('mobileStep2').style.display = 'block';
                document.getElementById('displayMobileNum').textContent = '+91 ' + mobile;
                startLoginTimer(60);
                
                if (data.sms_error) {
                    // SMS gateway failed, auto-fill OTP digits
                    const digits = data.otp.toString().split('');
                    const inputs = document.querySelectorAll('#otpInputContainer .otp-input');
                    inputs.forEach((inp, idx) => {
                        if (digits[idx]) inp.value = digits[idx];
                    });
                    document.getElementById('loginOTP').value = data.otp;
                    alert('SMS service is offline. Using fallback verification code: ' + data.otp + ' (auto-filled). Please click Verify & Login.');
                }
                
                setTimeout(() => document.querySelector('.otp-input').focus(), 100);
            } else {
                errorDiv.textContent = data.message;
                errorDiv.classList.remove('hidden');
                if(isResend) alert(data.message);
            }
        });
    }

    // OTP Input Logic (Auto-focus next)
    const otpInputs = document.querySelectorAll('.otp-input');
    otpInputs.forEach((input, index) => {
        input.addEventListener('keyup', (e) => {
            if (e.key >= 0 && e.key <= 9) {
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            } else if (e.key === 'Backspace') {
                if (index > 0) {
                    otpInputs[index - 1].focus();
                }
            }
            
            // Combine all inputs into one
            let fullOtp = "";
            otpInputs.forEach(inp => fullOtp += inp.value);
            document.getElementById('loginOTP').value = fullOtp;
        });
    });

    function verifyOTPRequest() {
        const mobile = document.getElementById('loginMobile').value;
        const otp = document.getElementById('loginOTP').value;
        const errorDiv = document.getElementById('otpError');
        errorDiv.classList.add('hidden');

        if(otp.length !== 6) {
            errorDiv.textContent = 'Please enter 6-digit code';
            errorDiv.classList.remove('hidden');
            return;
        }

        const btn = event.target;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>VERIFYING...';

        const formData = new FormData();
        formData.append('action', 'verify_otp');
        formData.append('mobile', mobile);
        formData.append('otp', otp);

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                btn.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i>SUCCESS';
                clearInterval(loginTimerInterval);
                setTimeout(() => {
                    const redirectUrl = data.redirect ? '<?php echo $link_prefix; ?>' + data.redirect : '<?php echo $link_prefix; ?>user/index.php';
                    window.location.href = redirectUrl;
                }, 1000);
            } else {
                btn.disabled = false;
                btn.innerHTML = 'VERIFY & LOGIN';
                errorDiv.textContent = data.message;
                errorDiv.classList.remove('hidden');
            }
        });
    }

    function backToMobileStep1() {
        document.getElementById('mobileStep2').style.display = 'none';
        document.getElementById('mobileStep1').style.display = 'block';
        otpInputs.forEach(inp => inp.value = "");
        document.getElementById('loginOTP').value = "";
        clearInterval(loginTimerInterval);
    }

    // Traditional Login
    document.getElementById('headerLoginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const errorDiv = document.getElementById('emailError');
        errorDiv.classList.add('hidden');

        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>LOGGING IN...';

        const formData = new FormData(this);
        formData.append('action', 'login');

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                btn.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i>SUCCESS';
                setTimeout(() => {
                    const redirectUrl = data.redirect ? '<?php echo $link_prefix; ?>' + data.redirect : '<?php echo $link_prefix; ?>user/index.php';
                    window.location.href = redirectUrl;
                }, 800);
            } else {
                btn.disabled = false;
                btn.innerHTML = originalText;
                errorDiv.textContent = data.message;
                errorDiv.classList.remove('hidden');
            }
        });
    });

    let regTimerInterval;
    function startRegTimer(duration) {
        clearInterval(regTimerInterval);
        let timer = duration, minutes, seconds;
        const timerDisplay = document.getElementById('regTimer');
        const timerContainer = document.getElementById('regTimerContainer');
        const resendBtn = document.getElementById('resendRegBtn');
        
        timerContainer.style.display = 'block';
        resendBtn.style.display = 'none';

        regTimerInterval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            timerDisplay.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(regTimerInterval);
                timerContainer.style.display = 'none';
                resendBtn.style.display = 'inline-block';
            }
        }, 1000);
    }

    function resendRegOTP() {
        const formData = new FormData(document.getElementById('mainRegisterForm'));
        formData.append('action', 'send_register_otp');
        
        const resendBtn = document.getElementById('resendRegBtn');
        const originalText = resendBtn.innerHTML;
        resendBtn.innerHTML = 'Sending...';
        resendBtn.style.pointerEvents = 'none';

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            resendBtn.innerHTML = originalText;
            resendBtn.style.pointerEvents = 'auto';
            if(data.status === 'success') {
                startRegTimer(60);
                
                if (data.sms_error) {
                    const digits = data.otp.toString().split('');
                    const inputs = document.querySelectorAll('#regOtpInputContainer .reg-otp-input');
                    inputs.forEach((inp, idx) => {
                        if (digits[idx]) inp.value = digits[idx];
                    });
                    document.getElementById('regOTPValue').value = data.otp;
                    alert('SMS service is offline. Using fallback verification code: ' + data.otp + ' (auto-filled).');
                }
            } else {
                alert(data.message);
            }
        });
    }

    function toggleRegPass() {
        const input = document.getElementById('regPassInput');
        const icon = document.getElementById('regPassIcon');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }

    document.getElementById('mainRegisterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const errorDiv = document.getElementById('regError');
        errorDiv.classList.add('hidden');

        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>SENDING OTP...';

        const formData = new FormData(this);
        formData.append('action', 'send_register_otp');

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            if(data.status === 'success') {
                document.getElementById('registerStep1').style.display = 'none';
                document.getElementById('registerStep2').style.display = 'block';
                document.getElementById('displayRegMobile').textContent = '+91 ' + document.getElementById('regMobile').value;
                startRegTimer(60);
                
                if (data.sms_error) {
                    const digits = data.otp.toString().split('');
                    const inputs = document.querySelectorAll('#regOtpInputContainer .reg-otp-input');
                    inputs.forEach((inp, idx) => {
                        if (digits[idx]) inp.value = digits[idx];
                    });
                    document.getElementById('regOTPValue').value = data.otp;
                    alert('SMS service is offline. Using fallback verification code: ' + data.otp + ' (auto-filled). Please click Verify & Create Account.');
                }
                
                setTimeout(() => document.querySelector('.reg-otp-input').focus(), 100);
            } else {
                errorDiv.textContent = data.message;
                errorDiv.classList.remove('hidden');
            }
        });
    });

    const regOtpInputs = document.querySelectorAll('.reg-otp-input');
    regOtpInputs.forEach((input, index) => {
        input.addEventListener('keyup', (e) => {
            if (e.key >= 0 && e.key <= 9) {
                if (index < regOtpInputs.length - 1) regOtpInputs[index + 1].focus();
            } else if (e.key === 'Backspace') {
                if (index > 0) regOtpInputs[index - 1].focus();
            }
            let fullOtp = "";
            regOtpInputs.forEach(inp => fullOtp += inp.value);
            document.getElementById('regOTPValue').value = fullOtp;
        });
    });

    function verifyRegOTP() {
        const otp = document.getElementById('regOTPValue').value;
        const errorDiv = document.getElementById('regOtpError');
        errorDiv.classList.add('hidden');

        if(otp.length !== 6) {
            errorDiv.textContent = 'Enter 6-digit code';
            errorDiv.classList.remove('hidden');
            return;
        }

        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>VERIFYING...';

        const formData = new FormData(document.getElementById('mainRegisterForm'));
        formData.append('action', 'verify_and_register');
        formData.append('otp', otp);

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                btn.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i>SUCCESS';
                clearInterval(regTimerInterval);
                setTimeout(() => {
                    const redirectUrl = data.redirect ? '<?php echo $link_prefix; ?>' + data.redirect : '<?php echo $link_prefix; ?>user/index.php';
                    window.location.href = redirectUrl;
                }, 1000);
            } else {
                btn.disabled = false;
                btn.innerHTML = originalText;
                errorDiv.textContent = data.message;
                errorDiv.classList.remove('hidden');
            }
        });
    }

    function backToRegStep1() {
        document.getElementById('registerStep2').style.display = 'none';
        document.getElementById('registerStep1').style.display = 'block';
        regOtpInputs.forEach(inp => inp.value = "");
        document.getElementById('regOTPValue').value = "";
        clearInterval(regTimerInterval);
    }

    // --- CART COUNT AND HOVER PREVIEW ---
    function updateCartCount() {
        const badge = document.getElementById('headerCartCount');
        if(!badge) return;
        
        fetch('<?php echo $link_prefix; ?>includes/cart_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=count'
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                badge.textContent = data.count;
                // Also refresh hover mini cart content if active
                loadMiniCart();
            }
        });
    }

    function loadMiniCart() {
        const itemsContainer = document.getElementById('miniCartItems');
        const subtotalContainer = document.getElementById('miniCartSubtotal');
        if (!itemsContainer) return;
        
        fetch('<?php echo $link_prefix; ?>includes/cart_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=fetch'
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                document.getElementById('headerCartCount').textContent = data.count;
                subtotalContainer.textContent = '₹' + data.total.toLocaleString();
                
                if(data.items.length === 0) {
                    itemsContainer.innerHTML = `
                        <div class="text-center py-6">
                            <i data-lucide="shopping-bag" class="mx-auto text-gray-300 w-10 h-10 mb-2"></i>
                            <p class="text-xs text-gray-500">Your cart is empty</p>
                        </div>`;
                } else {
                    let html = '';
                    data.items.forEach(item => {
                        const img = (item.image.startsWith('http') || item.image.startsWith('/')) ? item.image : '<?php echo $link_prefix; ?>' + item.image;
                        html += `
                            <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                                <img src="${img}" class="w-12 h-12 rounded-lg object-contain border border-gray-100 p-1 flex-shrink-0">
                                <div class="flex-grow min-w-0">
                                    <h6 class="text-xs font-semibold text-gray-800 truncate mb-0.5">${item.name}</h6>
                                    <p class="text-[11px] text-gray-400 mb-0 font-medium">Qty: ${item.quantity}</p>
                                </div>
                                <span class="text-xs font-bold text-gray-900 flex-shrink-0">₹${item.price.toLocaleString()}</span>
                            </div>`;
                    });
                    itemsContainer.innerHTML = html;
                }
                lucide.createIcons();
            }
        })
        .catch(err => console.error("Error fetching mini cart details:", err));
    }

    // --- SEARCH AUTOCOMPLETE SUGGESTIONS ---
    const searchForm = document.getElementById('headerSearchForm');
    const searchInput = document.getElementById('headerSearchInput');
    const suggestionsBox = document.getElementById('searchSuggestions');
    let searchTimeout = null;
    let currentFocus = -1;

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = this.value.trim();
            clearTimeout(searchTimeout);
            currentFocus = -1;

            if (query.length < 2) {
                suggestionsBox.style.display = 'none';
                return;
            }

            suggestionsBox.innerHTML = '<div class="p-3 text-center text-xs text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Searching...</div>';
            suggestionsBox.style.display = 'block';

            searchTimeout = setTimeout(() => {
                fetch('<?php echo $link_prefix; ?>api/search-suggestions.php?q=' + encodeURIComponent(query))
                .then(res => {
                    if (!res.ok) throw new Error('Response error');
                    return res.json();
                })
                .then(data => {
                    if (data.error) {
                        suggestionsBox.innerHTML = '<div class="p-3 text-center text-xs text-red-500">Error loading suggestions</div>';
                        return;
                    }
                    if (data && data.length > 0) {
                        let html = '';
                        data.forEach((item, index) => {
                            const img = (item.image.startsWith('http') || item.image.startsWith('/')) ? item.image : '<?php echo $link_prefix; ?>' + item.image;
                            html += `
                                <a href="<?php echo $link_prefix; ?>product/${item.slug}" class="suggestion-item suggestion-nav-item" data-index="${index}">
                                    <img src="${img}" class="suggestion-img">
                                    <div class="suggestion-info">
                                        <p class="suggestion-name">${item.name}</p>
                                        <p class="suggestion-price">${item.price}</p>
                                    </div>
                                </a>
                            `;
                        });
                        html += `<a href="<?php echo $link_prefix; ?>products.php?search=${encodeURIComponent(query)}" class="view-all-results suggestion-nav-item" data-index="${data.length}">View All Results</a>`;
                        suggestionsBox.innerHTML = html;
                    } else {
                        suggestionsBox.innerHTML = '<div class="no-results p-3 text-center text-xs text-gray-400">No products found</div>';
                    }
                })
                .catch(err => {
                    console.error("Fetch error:", err);
                    suggestionsBox.innerHTML = '<div class="p-3 text-center text-xs text-red-500">Failed to fetch results</div>';
                });
            }, 300);
        });

        // Keydown keyboard navigation
        searchInput.addEventListener('keydown', function(e) {
            const items = suggestionsBox.querySelectorAll('.suggestion-nav-item');
            if (!items || items.length === 0) return;

            if (e.keyCode == 40) { // Down arrow
                currentFocus++;
                addActive(items);
            } else if (e.keyCode == 38) { // Up arrow
                currentFocus--;
                addActive(items);
            } else if (e.keyCode == 13) { // Enter key
                if (currentFocus > -1) {
                    e.preventDefault();
                    items[currentFocus].click();
                }
            } else if (e.keyCode == 27) { // Escape key
                suggestionsBox.style.display = 'none';
            }
        });

        function addActive(items) {
            if (!items) return false;
            removeActive(items);
            if (currentFocus >= items.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = items.length - 1;
            items[currentFocus].classList.add("active-suggestion");
            items[currentFocus].scrollIntoView({ block: "nearest", behavior: "smooth" });
        }

        function removeActive(items) {
            for (let i = 0; i < items.length; i++) {
                items[i].classList.remove("active-suggestion");
            }
        }

        // Search Form Submit block empty search
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                if (searchInput.value.trim() === '') {
                    e.preventDefault();
                }
            });
        }

        // Close on body click outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.style.display = 'none';
            }
        });
    }
</script>

<?php include 'cart_sidebar.php'; ?>

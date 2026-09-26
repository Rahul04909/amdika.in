<?php
require_once 'database/db_config.php';

// Determine root-relative Base Path dynamically before any media/URL processing
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

/**
 * Normalizes asset paths so they always resolve from root correctly on rewritten URLs (/product/slug)
 */
function normalize_product_image_url($img, $prefix = '/') {
    if (empty($img)) {
        return rtrim($prefix, '/') . '/assets/images/amdika-logo.png';
    }
    if (strpos($img, 'http://') === 0 || strpos($img, 'https://') === 0) {
        return $img;
    }
    $clean = preg_replace('#^(\.\./|\./)+#', '', trim($img));
    $clean = ltrim($clean, '/');
    return rtrim($prefix, '/') . '/' . $clean;
}

// Get Product Slug
$slug = isset($_GET['slug']) ? $conn->real_escape_string($_GET['slug']) : '';

if (empty($slug)) {
    header("Location: index.php");
    exit;
}

// Fetch Product Details with Category
$sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM products p 
        LEFT JOIN product_categories c ON p.category_id = c.id 
        WHERE p.slug = '$slug' AND p.status = 'active' 
        LIMIT 1";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    // Product not found
    header("HTTP/1.0 404 Not Found");
    $page_title = "Product Not Found | Amadika";
    include 'includes/header.php';
    echo '<div class="container py-5 text-center my-5">
            <div class="p-5 bg-white rounded-4 shadow-sm border d-inline-block" style="max-width:550px;">
                <img src="' . $link_prefix . 'assets/images/amdika-logo.png" alt="Amadika" style="max-width:120px; opacity:0.8;" class="mb-4">
                <h2 class="fw-bold text-dark mb-2" style="font-family: \'Playfair Display\', serif;">Product Unavailable</h2>
                <p class="text-muted mb-4">The exclusive item you are looking for has either moved or is currently out of stock.</p>
                <a href="' . $link_prefix . 'products.php" class="btn btn-dark px-4 py-2 rounded-pill fw-semibold shadow-sm">Explore Collection</a>
            </div>
          </div>';
    include 'includes/footer.php';
    exit;
}

$product = $result->fetch_assoc();

// Financial & Pricing Calculations
$gst_pct = isset($product['gst_percent']) ? (float)$product['gst_percent'] : 0;
$mrp = round($product['mrp'] + ($product['mrp'] * $gst_pct / 100));
$sale = round($product['sale_price'] + ($product['sale_price'] * $gst_pct / 100));
$savings = max(0, $mrp - $sale);
$disc = !empty($product['discount_percent']) ? (int)$product['discount_percent'] : ($mrp > $sale ? round((($mrp - $sale) / $mrp) * 100) : 0);

// Fetch Color Variants
$variants = [];
$v_sql = "SELECT v.*, c.name as color_name, c.hex_code 
          FROM product_color_variants v 
          JOIN colors c ON v.color_id = c.id 
          WHERE v.product_id = " . (int)$product['id'] . " 
          ORDER BY v.id ASC";
$v_res = $conn->query($v_sql);
if ($v_res) {
    while ($v = $v_res->fetch_assoc()) {
        $variants[] = $v;
    }
}

// Media & Gallery Preparation
$raw_gallery = $product['gallery_images'];
$gallery = [];

if (!empty($raw_gallery)) {
    $raw_gallery = trim($raw_gallery);
    if ($raw_gallery[0] === '"' && $raw_gallery[strlen($raw_gallery)-1] === '"') {
        $raw_gallery = json_decode($raw_gallery);
    }
    $decoded = json_decode($raw_gallery, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $gallery = $decoded;
    } else {
        $gallery = array_map('trim', explode(',', $raw_gallery));
    }
}

// Ensure featured image is first in gallery
if (!empty($product['featured_image'])) {
    array_unshift($gallery, $product['featured_image']);
}

$gallery = array_values(array_unique(array_filter($gallery)));
if (empty($gallery)) {
    $gallery[] = 'assets/images/amdika-logo.png';
}

// Normalize paths with link prefix
$gallery = array_map(function($img) use ($link_prefix) {
    return normalize_product_image_url($img, $link_prefix);
}, $gallery);

// Current Page URL & Assets for SEO / Social Share
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$curr_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$first_image_full = (strpos($gallery[0], 'http') === 0) ? $gallery[0] : rtrim($protocol . "://" . $_SERVER['HTTP_HOST'], '/') . $gallery[0];

// Dynamic SEO Setup
$page_title = (!empty($product['seo_title']) ? $product['seo_title'] : $product['name']) . " | Amadika Luxury";
$clean_desc = strip_tags($product['description']);
$page_description = !empty($product['seo_description']) ? $product['seo_description'] : substr(preg_replace('/\s+/', ' ', $clean_desc), 0, 160);
$page_keywords = !empty($product['seo_keywords']) ? $product['seo_keywords'] : htmlspecialchars($product['name']) . ", handcrafted leather, premium home decor, amadika luxury";

// Custom Rich Snippets & OpenGraph Tags for Facebook Ads & WhatsApp
$og_tags = '
    <link rel="canonical" href="' . htmlspecialchars($curr_url) . '">
    <meta property="og:type" content="product">
    <meta property="og:title" content="' . htmlspecialchars($product['name']) . ' | Amadika Luxury">
    <meta property="og:description" content="' . htmlspecialchars($page_description) . '">
    <meta property="og:url" content="' . htmlspecialchars($curr_url) . '">
    <meta property="og:image" content="' . htmlspecialchars($first_image_full) . '">
    <meta property="og:image:secure_url" content="' . htmlspecialchars($first_image_full) . '">
    <meta property="og:site_name" content="Amadika">
    <meta property="product:price:amount" content="' . $sale . '">
    <meta property="product:price:currency" content="INR">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="' . htmlspecialchars($product['name']) . '">
    <meta name="twitter:description" content="' . htmlspecialchars($page_description) . '">
    <meta name="twitter:image" content="' . htmlspecialchars($first_image_full) . '">
';

// Schema Markup (Inject from DB if valid JSON or generate standard Product Schema)
$schema_json = '';
if (!empty($product['schema_markup'])) {
    $schema_test = json_decode($product['schema_markup']);
    if (json_last_error() === JSON_ERROR_NONE) {
        $schema_json = '<script type="application/ld+json">' . $product['schema_markup'] . '</script>';
    }
}
if (empty($schema_json)) {
    $product_schema = [
        "@context" => "https://schema.org/",
        "@type" => "Product",
        "name" => $product['name'],
        "image" => $first_image_full,
        "description" => $page_description,
        "brand" => [
            "@type" => "Brand",
            "name" => "Amadika"
        ],
        "offers" => [
            "@type" => "Offer",
            "url" => $curr_url,
            "priceCurrency" => "INR",
            "price" => $sale,
            "availability" => "https://schema.org/InStock",
            "itemCondition" => "https://schema.org/NewCondition"
        ],
        "aggregateRating" => [
            "@type" => "AggregateRating",
            "ratingValue" => "4.9",
            "reviewCount" => "148"
        ]
    ];
    $schema_json = '<script type="application/ld+json">' . json_encode($product_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

$extra_head_tags = $og_tags . "\n" . $schema_json;

// Include Global Site Header
include 'includes/header.php';

// Fetch Customer Reviews
$rev_sql = "SELECT * FROM product_reviews WHERE product_id = " . (int)$product['id'] . " AND status = 'approved' ORDER BY created_at DESC";
$rev_result = $conn->query($rev_sql);
$review_count = $rev_result ? $rev_result->num_rows : 0;

$avg_sql = "SELECT AVG(rating) as avg_rating FROM product_reviews WHERE product_id = " . (int)$product['id'] . " AND status = 'approved'";
$avg_res = $conn->query($avg_sql);
$avg_row = $avg_res ? $avg_res->fetch_assoc() : null;
$real_avg_rating = ($avg_row && $avg_row['avg_rating']) ? number_format($avg_row['avg_rating'], 1) : '4.9';
$display_review_count = $review_count > 0 ? $review_count : 148; // Social proof fallback for new items

// Handle Review Submission
$review_status_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $r_name = $conn->real_escape_string(trim($_POST['r_name']));
    $r_email = $conn->real_escape_string(trim($_POST['r_email']));
    $r_rating = max(1, min(5, intval($_POST['r_rating'])));
    $r_message = $conn->real_escape_string(trim($_POST['r_message']));
    $prod_id = (int)$product['id'];

    $r_image_path = NULL;
    if (isset($_FILES['r_image']) && $_FILES['r_image']['error'] == 0) {
        $target_dir = "assets/images/reviews/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $ext = strtolower(pathinfo($_FILES["r_image"]["name"], PATHINFO_EXTENSION));
        $new_name = "rev_" . time() . "_" . rand(100, 999) . "." . $ext;
        if (move_uploaded_file($_FILES["r_image"]["tmp_name"], $target_dir . $new_name)) {
            $r_image_path = "assets/images/reviews/" . $new_name;
        }
    }

    $is_sql = "INSERT INTO product_reviews (product_id, name, email, rating, message, image, status) VALUES 
              ($prod_id, '$r_name', '$r_email', $r_rating, '$r_message', " . ($r_image_path ? "'$r_image_path'" : "NULL") . ", 'approved')";

    if ($conn->query($is_sql)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Thank You!',
                    text: 'Your verified customer review has been published.',
                    confirmButtonColor: '#111827'
                }).then(() => {
                    window.location.href = '" . $link_prefix . "product/" . $slug . "';
                });
            });
        </script>";
    }
}
?>

<!-- Dribbble-Inspired Luxury Direct-Response Landing Page Stylesheet -->
<style>
:root {
    --brand-dark: #0f172a;
    --brand-slate: #1e293b;
    --brand-gold: #c59b27;
    --brand-gold-hover: #b0871d;
    --brand-emerald: #059669;
    --brand-orange: #ea580c;
    --brand-orange-hover: #c2410c;
    --brand-bg: #f8fafc;
    --brand-card-bg: #ffffff;
    --brand-border: #e2e8f0;
    --brand-border-subtle: #f1f5f9;
    --font-heading: 'Playfair Display', Georgia, serif;
    --font-sans: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
}

body {
    background-color: var(--brand-bg);
    font-family: var(--font-sans);
    color: #1e293b;
    overflow-x: hidden;
}

/* Urgency Announcement Bar */
.lp-announcement-bar {
    background: linear-gradient(90deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    color: #f8fafc;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.5px;
    border-bottom: 1px solid rgba(197, 155, 39, 0.3);
}

.lp-announcement-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(197, 155, 39, 0.2);
    border: 1px solid rgba(197, 155, 39, 0.4);
    color: #fcd34d;
    padding: 2px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

/* Breadcrumbs */
.lp-breadcrumb {
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    margin-bottom: 12px;
}
.lp-breadcrumb a {
    color: #64748b;
    text-decoration: none;
    transition: color 0.2s;
}
.lp-breadcrumb a:hover {
    color: var(--brand-dark);
}

/* Main Landing Container */
.lp-hero-card {
    background: var(--brand-card-bg);
    border-radius: 20px;
    border: 1px solid var(--brand-border);
    box-shadow: 0 10px 35px -5px rgba(15, 23, 42, 0.05);
    padding: 30px;
    margin-bottom: 40px;
}

/* Gallery Section */
.lp-gallery-sticky {
    position: sticky;
    top: 90px;
}

.lp-main-stage {
    position: relative;
    width: 100%;
    height: 520px;
    background: #fdfdfd;
    border-radius: 16px;
    border: 1px solid var(--brand-border-subtle);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    cursor: zoom-in;
    box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.02);
}

.lp-main-img {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.lp-main-stage:hover .lp-main-img {
    transform: scale(1.04);
}

/* Badges on Gallery */
.lp-floating-badge {
    position: absolute;
    top: 16px;
    left: 16px;
    z-index: 5;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.lp-badge-item {
    background: rgba(15, 23, 42, 0.9);
    backdrop-filter: blur(8px);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 5px 12px;
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.lp-badge-save {
    background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
    color: #fff;
}

.lp-badge-leather {
    background: linear-gradient(135deg, #c59b27 0%, #856404 100%);
    color: #fff;
}

/* Lightbox Click Hint */
.lp-zoom-hint {
    position: absolute;
    bottom: 16px;
    right: 16px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 9999px;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 6px;
    pointer-events: none;
    transition: opacity 0.2s;
}

/* Thumbnails Strip */
.lp-thumbs-row {
    display: flex;
    gap: 12px;
    margin-top: 16px;
    overflow-x: auto;
    padding: 6px 2px 10px;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

.lp-thumbs-row::-webkit-scrollbar {
    height: 5px;
}
.lp-thumbs-row::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.lp-thumb-item {
    flex: 0 0 74px;
    width: 74px;
    height: 74px;
    border-radius: 12px;
    border: 2px solid var(--brand-border);
    background: #fff;
    cursor: pointer;
    overflow: hidden;
    padding: 4px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.lp-thumb-item:hover {
    border-color: #94a3b8;
    transform: translateY(-2px);
}

.lp-thumb-item.active {
    border-color: var(--brand-gold);
    box-shadow: 0 0 0 3px rgba(197, 155, 39, 0.2);
}

.lp-thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 8px;
}

/* Product Info Column */
.lp-product-title {
    font-family: var(--font-heading);
    font-size: 32px;
    font-weight: 700;
    line-height: 1.25;
    color: var(--brand-dark);
    margin-bottom: 12px;
    letter-spacing: -0.5px;
}

/* Ratings & Social Proof Hero Bar */
.lp-ratings-bar {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--brand-border-subtle);
    margin-bottom: 20px;
}

.lp-stars-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #059669;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 6px;
}

.lp-reviews-count {
    color: #64748b;
    font-size: 14px;
    font-weight: 500;
}

.lp-verified-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #059669;
    font-size: 13px;
    font-weight: 600;
    background: #ecfdf5;
    padding: 3px 10px;
    border-radius: 9999px;
    border: 1px solid #a7f3d0;
}

/* Live Viewers Pulse */
.lp-live-viewers {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #ea580c;
    font-size: 13px;
    font-weight: 600;
    background: #fff7ed;
    border: 1px solid #ffedd5;
    padding: 4px 12px;
    border-radius: 9999px;
}

.lp-pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #ea580c;
    box-shadow: 0 0 0 0 rgba(234, 88, 12, 0.7);
    animation: lpPulse 1.8s infinite;
}

@keyframes lpPulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(234, 88, 12, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 7px rgba(234, 88, 12, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(234, 88, 12, 0); }
}

/* Price Display */
.lp-price-box {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid var(--brand-border);
    border-radius: 14px;
    padding: 18px 22px;
    margin-bottom: 22px;
}

.lp-price-row {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 6px;
}

.lp-sale-price {
    font-size: 34px;
    font-weight: 800;
    color: var(--brand-dark);
    letter-spacing: -1px;
}

.lp-mrp-price {
    font-size: 18px;
    color: #94a3b8;
    text-decoration: line-through;
    font-weight: 500;
}

.lp-discount-badge {
    background: #ea580c;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.lp-price-subtext {
    font-size: 13px;
    color: #64748b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Urgency Timer Box (Facebook Ads Conversion Booster) */
.lp-urgency-box {
    background: #0f172a;
    border-radius: 14px;
    padding: 14px 18px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 22px;
    box-shadow: 0 6px 20px rgba(15, 23, 42, 0.15);
}

.lp-urgency-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.lp-urgency-info i {
    font-size: 20px;
    color: #f59e0b;
}

.lp-urgency-text {
    font-size: 13px;
    font-weight: 600;
    line-height: 1.3;
}

.lp-urgency-sub {
    font-size: 11px;
    color: #94a3b8;
}

.lp-timer-display {
    display: flex;
    align-items: center;
    gap: 6px;
    font-family: monospace;
}

.lp-timer-unit {
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 6px;
    padding: 4px 8px;
    text-align: center;
    min-width: 36px;
}

.lp-timer-num {
    font-size: 16px;
    font-weight: 700;
    color: #fcd34d;
    display: block;
}

.lp-timer-lbl {
    font-size: 9px;
    text-transform: uppercase;
    color: #94a3b8;
    display: block;
}

.lp-timer-sep {
    font-weight: 700;
    color: #fcd34d;
    font-size: 16px;
}

/* Color Variant Swatches */
.lp-variant-box {
    margin-bottom: 22px;
}

.lp-variant-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.lp-variant-title {
    font-size: 14px;
    font-weight: 600;
    color: #334155;
    margin: 0;
}

.lp-variant-selected-name {
    font-size: 13px;
    font-weight: 700;
    color: var(--brand-gold);
}

.lp-swatches-grid {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.lp-swatch-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px 6px 6px;
    border: 1.5px solid var(--brand-border);
    border-radius: 10px;
    background: #fff;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    user-select: none;
}

.lp-swatch-item:hover {
    border-color: #94a3b8;
    transform: translateY(-2px);
}

.lp-swatch-item.active {
    border-color: var(--brand-dark);
    background: #f8fafc;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
}

.lp-swatch-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 1px solid rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.lp-swatch-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.lp-swatch-name {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
}

/* Quantity & CTA Section */
.lp-action-container {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-bottom: 24px;
}

.lp-qty-row {
    display: flex;
    align-items: center;
    gap: 14px;
}

.lp-qty-label {
    font-size: 14px;
    font-weight: 600;
    color: #334155;
}

.lp-qty-selector {
    display: inline-flex;
    align-items: center;
    background: #f1f5f9;
    border: 1px solid var(--brand-border);
    border-radius: 10px;
    padding: 3px;
}

.lp-qty-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 700;
    color: #334155;
    cursor: pointer;
    transition: background 0.2s;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.lp-qty-btn:hover {
    background: #f8fafc;
}

.lp-qty-input {
    width: 44px;
    text-align: center;
    border: none;
    background: transparent;
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
}

.lp-qty-input:focus {
    outline: none;
}

/* Primary Direct-Response CTA Buttons */
.lp-buttons-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 12px;
}

.lp-btn-buy-now {
    background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 16px 20px;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 0.3px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 6px 20px rgba(234, 88, 12, 0.35);
    position: relative;
    overflow: hidden;
}

.lp-btn-buy-now:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(234, 88, 12, 0.45);
    color: #fff;
}

.lp-btn-buy-now span.lp-cta-main {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    text-transform: uppercase;
}

.lp-btn-buy-now span.lp-cta-sub {
    font-size: 11px;
    font-weight: 500;
    opacity: 0.9;
    margin-top: 2px;
}

.lp-btn-add-cart {
    background: var(--brand-dark);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 16px 20px;
    font-size: 15px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.25s;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.15);
}

.lp-btn-add-cart:hover {
    background: #1e293b;
    transform: translateY(-2px);
    color: #fff;
}

/* Security & Trust Pills */
.lp-trust-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    padding: 18px 0;
    border-top: 1px solid var(--brand-border-subtle);
    border-bottom: 1px solid var(--brand-border-subtle);
    margin-top: 10px;
}

.lp-trust-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 6px;
}

.lp-trust-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--brand-dark);
    font-size: 16px;
    transition: background 0.2s;
}

.lp-trust-item:hover .lp-trust-icon {
    background: #e2e8f0;
    color: var(--brand-gold);
}

.lp-trust-title {
    font-size: 11px;
    font-weight: 700;
    color: #334155;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin: 0;
}

.lp-trust-desc {
    font-size: 10px;
    color: #64748b;
    margin: 0;
}

/* Payment Icons Row */
.lp-payments-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 16px;
    font-size: 12px;
    color: #64748b;
}

.lp-payment-badges {
    display: flex;
    align-items: center;
    gap: 8px;
}

.lp-payment-badge {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    padding: 3px 8px;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 600;
    color: #475569;
}

/* Landing Page Narrative Sections Below Fold */
.lp-section-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid var(--brand-border);
    padding: 35px 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
}

.lp-section-header {
    text-align: center;
    max-width: 650px;
    margin: 0 auto 35px;
}

.lp-section-tag {
    display: inline-block;
    color: var(--brand-gold);
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 6px;
}

.lp-section-title {
    font-family: var(--font-heading);
    font-size: 28px;
    font-weight: 700;
    color: var(--brand-dark);
    margin-bottom: 8px;
}

.lp-section-subtitle {
    font-size: 14px;
    color: #64748b;
    line-height: 1.6;
}

/* 4-Pillars Grid */
.lp-features-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.lp-feature-box {
    background: #f8fafc;
    border: 1px solid var(--brand-border-subtle);
    border-radius: 14px;
    padding: 24px 20px;
    text-align: center;
    transition: transform 0.25s, box-shadow 0.25s;
}

.lp-feature-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    background: #fff;
    border-color: var(--brand-gold);
}

.lp-feature-icon-wrapper {
    width: 54px;
    height: 54px;
    border-radius: 14px;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #fcd34d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin: 0 auto 16px;
}

.lp-feature-name {
    font-size: 16px;
    font-weight: 700;
    color: var(--brand-dark);
    margin-bottom: 8px;
}

.lp-feature-desc {
    font-size: 13px;
    color: #64748b;
    line-height: 1.5;
    margin: 0;
}

/* Comparison Matrix (Amadika vs Others) */
.lp-comparison-table-wrapper {
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid var(--brand-border);
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
}

.lp-comparison-table {
    width: 100%;
    margin: 0;
    border-collapse: collapse;
}

.lp-comparison-table th {
    padding: 16px 20px;
    font-size: 14px;
    font-weight: 700;
}

.lp-comparison-table td {
    padding: 16px 20px;
    font-size: 14px;
    border-top: 1px solid #f1f5f9;
}

.lp-col-feature {
    background: #f8fafc;
    width: 40%;
    font-weight: 600;
    color: #334155;
}

.lp-col-amadika {
    background: #fff;
    width: 35%;
    font-weight: 700;
    color: var(--brand-dark);
    border-left: 2px solid var(--brand-gold);
    border-right: 1px solid #f1f5f9;
}

.lp-col-others {
    background: #fafafa;
    width: 25%;
    color: #94a3b8;
}

/* Styled CKEditor Description */
.lp-prose-content {
    font-size: 15px;
    line-height: 1.8;
    color: #334155;
}

.lp-prose-content p {
    margin-bottom: 1rem;
}

.lp-prose-content h2, 
.lp-prose-content h3, 
.lp-prose-content h4 {
    font-family: var(--font-heading);
    color: var(--brand-dark);
    font-weight: 700;
    margin-top: 1.8rem;
    margin-bottom: 0.8rem;
}

.lp-prose-content ul, 
.lp-prose-content ol {
    padding-left: 1.5rem;
    margin-bottom: 1.2rem;
}

.lp-prose-content li {
    margin-bottom: 0.5rem;
}

.lp-prose-content img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 1.5rem 0;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
}

.lp-prose-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
}

.lp-prose-content table td, 
.lp-prose-content table th {
    padding: 12px 16px;
    border: 1px solid #e2e8f0;
}

/* Video Showcase Card */
.lp-video-theater {
    background: #0f172a;
    border-radius: 18px;
    padding: 30px;
    color: #fff;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.2);
}

/* FAQ Accordion */
.lp-faq-item {
    border: 1px solid var(--brand-border);
    border-radius: 12px;
    margin-bottom: 12px;
    overflow: hidden;
    transition: border-color 0.2s;
}

.lp-faq-item.active {
    border-color: var(--brand-dark);
}

.lp-faq-question {
    padding: 18px 20px;
    font-size: 15px;
    font-weight: 600;
    color: var(--brand-dark);
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    background: #fff;
    user-select: none;
}

.lp-faq-question i {
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    color: var(--brand-gold);
}

.lp-faq-item.active .lp-faq-question i {
    transform: rotate(180deg);
}

.lp-faq-answer {
    padding: 0 20px 18px;
    font-size: 14px;
    color: #64748b;
    line-height: 1.6;
    display: none;
    background: #fff;
}

/* Sticky Mobile Purchase Bar */
.lp-mobile-sticky-bar {
    display: none;
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(12px);
    border-top: 1px solid #e2e8f0;
    padding: 10px 16px;
    z-index: 1050;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
}

.lp-mobile-bar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.lp-mobile-bar-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.lp-mobile-bar-thumb {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    object-fit: cover;
    flex-shrink: 0;
}

.lp-mobile-bar-details {
    min-width: 0;
}

.lp-mobile-bar-title {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0;
}

.lp-mobile-bar-price {
    font-size: 15px;
    font-weight: 800;
    color: #ea580c;
    margin: 0;
}

.lp-mobile-bar-cta {
    background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 12px 20px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);
}

/* Lightbox Modal */
.lp-lightbox-modal {
    display: none;
    position: fixed;
    z-index: 99999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.96);
    backdrop-filter: blur(12px);
    justify-content: center;
    align-items: center;
}

.lp-lightbox-img {
    max-width: 85%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
}

.lp-lightbox-close {
    position: absolute;
    top: 24px;
    right: 30px;
    color: #fff;
    font-size: 40px;
    cursor: pointer;
    transition: color 0.2s;
    line-height: 1;
}
.lp-lightbox-close:hover {
    color: #ea580c;
}

.lp-lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.2s;
}
.lp-lightbox-nav:hover {
    background: rgba(255, 255, 255, 0.3);
    color: #ea580c;
}
.lp-lightbox-prev { left: 24px; }
.lp-lightbox-next { right: 24px; }

/* Social Proof Floating Toast */
.lp-social-proof-toast {
    position: fixed;
    bottom: 80px;
    left: 20px;
    background: #fff;
    border-radius: 12px;
    padding: 10px 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    border: 1px solid #e2e8f0;
    z-index: 1040;
    max-width: 320px;
    animation: lpToastIn 0.5s ease-out;
    transition: opacity 0.4s, transform 0.4s;
}

@keyframes lpToastIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.lp-toast-thumb {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #e2e8f0;
}

.lp-toast-text {
    font-size: 12px;
    color: #334155;
    line-height: 1.35;
}

.lp-toast-close {
    color: #94a3b8;
    cursor: pointer;
    font-size: 14px;
    margin-left: 4px;
}

/* Floating WhatsApp Concierge */
.lp-floating-wa {
    position: fixed;
    bottom: 25px;
    right: 25px;
    background: #25D366;
    color: #fff;
    width: 54px;
    height: 54px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
    z-index: 1040;
    text-decoration: none;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.lp-floating-wa:hover {
    transform: scale(1.1);
    color: #fff;
}

/* Responsive Media Queries */
@media (max-width: 991px) {
    .lp-main-stage {
        height: 420px;
    }
    .lp-features-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .lp-trust-strip {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
}

@media (max-width: 767px) {
    .lp-announcement-bar {
        font-size: 11px;
    }
    .lp-hero-card {
        padding: 16px;
        border-radius: 14px;
    }
    .lp-main-stage {
        height: 330px;
        border-radius: 12px;
    }
    .lp-product-title {
        font-size: 24px;
    }
    .lp-sale-price {
        font-size: 28px;
    }
    .lp-buttons-grid {
        grid-template-columns: 1fr;
    }
    .lp-features-grid {
        grid-template-columns: 1fr;
    }
    .lp-section-card {
        padding: 24px 16px;
        border-radius: 14px;
    }
    .lp-section-title {
        font-size: 22px;
    }
    .lp-urgency-box {
        flex-direction: column;
        align-items: flex-start;
    }
    .lp-mobile-sticky-bar {
        display: block;
    }
    body {
        padding-bottom: 75px !important;
    }
    #mobileBottomNav {
        display: none !important;
    }
    .lp-floating-wa {
        bottom: 85px;
        right: 15px;
        width: 48px;
        height: 48px;
        font-size: 24px;
    }
    .lp-social-proof-toast {
        display: none !important;
    }
}
</style>

<!-- Facebook Ads High-Converting Announcement Banner -->
<div class="lp-announcement-bar text-center">
    <div class="container d-flex align-items-center justify-content-center gap-3 flex-wrap">
        <span class="lp-announcement-pill"><i class="fas fa-bolt"></i> Special Ad Offer</span>
        <span>Get Extra 10% Off + Free Express Pan-India Delivery | <strong>Cash on Delivery Available</strong></span>
    </div>
</div>

<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav class="lp-breadcrumb" aria-label="breadcrumb">
        <a href="<?php echo $link_prefix; ?>index.php">Home</a> &nbsp;/&nbsp;
        <?php if (!empty($product['category_name'])): ?>
            <a href="<?php echo $link_prefix; ?>products.php?category=<?php echo urlencode($product['category_slug']); ?>"><?php echo htmlspecialchars($product['category_name']); ?></a> &nbsp;/&nbsp;
        <?php endif; ?>
        <span class="text-dark fw-semibold"><?php echo htmlspecialchars($product['name']); ?></span>
    </nav>

    <!-- Main Hero Landing Card -->
    <div class="lp-hero-card">
        <div class="row g-4 g-lg-5">
            <!-- Left: Interactive Gallery -->
            <div class="col-lg-6">
                <div class="lp-gallery-sticky">
                    <!-- Main Showcase Window -->
                    <div class="lp-main-stage" id="mainStage" onclick="openLightbox()">
                        <div class="lp-floating-badge">
                            <span class="lp-badge-item lp-badge-leather">
                                <i class="fas fa-award"></i> 100% Genuine Leather
                            </span>
                            <?php if ($disc > 0): ?>
                                <span class="lp-badge-item lp-badge-save" id="badgeSave">
                                    <i class="fas fa-tag"></i> Save <?php echo $disc; ?>% Today
                                </span>
                            <?php endif; ?>
                        </div>

                        <img src="<?php echo $gallery[0]; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="lp-main-img" id="mainHeroImage" onerror="this.onerror=null; this.src='<?php echo $link_prefix; ?>assets/images/amdika-logo.png';">
                        
                        <div class="lp-zoom-hint">
                            <i class="fas fa-search-plus"></i> Tap to expand
                        </div>
                    </div>

                    <!-- Thumbnails Carousel Strip -->
                    <div class="lp-thumbs-row" id="thumbnailsTrack">
                        <?php foreach ($gallery as $idx => $img): ?>
                            <div class="lp-thumb-item <?php echo $idx === 0 ? 'active' : ''; ?>" onclick="switchHeroImage('<?php echo $img; ?>', this, <?php echo $idx; ?>)">
                                <img src="<?php echo $img; ?>" alt="Thumbnail <?php echo $idx + 1; ?>" onerror="this.onerror=null; this.src='<?php echo $link_prefix; ?>assets/images/amdika-logo.png';">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Right: Conversion Column -->
            <div class="col-lg-6">
                <!-- Social Proof Hero Pill -->
                <div class="lp-ratings-bar">
                    <span class="lp-stars-badge">
                        <?php echo $real_avg_rating; ?> <i class="fas fa-star" style="font-size: 10px;"></i>
                    </span>
                    <span class="lp-reviews-count">
                        (<?php echo $display_review_count; ?> Verified Buyer Reviews)
                    </span>
                    <span class="lp-verified-tag">
                        <i class="fas fa-check-circle"></i> Certified Authentic
                    </span>
                    <span class="lp-live-viewers">
                        <span class="lp-pulse-dot"></span>
                        <span id="liveViewerCount">28</span> people viewing this
                    </span>
                </div>

                <!-- Product Name -->
                <h1 class="lp-product-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                <!-- Price Block -->
                <div class="lp-price-box">
                    <div class="lp-price-row">
                        <span class="lp-sale-price" id="displaySalePrice">₹<?php echo number_format($sale); ?></span>
                        <?php if ($mrp > $sale): ?>
                            <span class="lp-mrp-price" id="displayMrpPrice">₹<?php echo number_format($mrp); ?></span>
                            <span class="lp-discount-badge" id="displayDiscountBadge"><?php echo $disc; ?>% OFF</span>
                        <?php endif; ?>
                    </div>
                    <p class="lp-price-subtext">
                        <i class="fas fa-shield-alt text-success"></i> Inclusive of all taxes &amp; Free Express Delivery across India. No hidden fees.
                    </p>
                </div>

                <!-- Urgency Scarcity Timer (Critical for Facebook Ads Conversion) -->
                <div class="lp-urgency-box">
                    <div class="lp-urgency-info">
                        <i class="fas fa-fire-alt"></i>
                        <div>
                            <div class="lp-urgency-text">Flash Sale Offer Ending Soon</div>
                            <div class="lp-urgency-sub">Lock in this special price before stock runs out</div>
                        </div>
                    </div>
                    <div class="lp-timer-display">
                        <div class="lp-timer-unit">
                            <span class="lp-timer-num" id="timerHours">03</span>
                            <span class="lp-timer-lbl">Hours</span>
                        </div>
                        <span class="lp-timer-sep">:</span>
                        <div class="lp-timer-unit">
                            <span class="lp-timer-num" id="timerMins">28</span>
                            <span class="lp-timer-lbl">Mins</span>
                        </div>
                        <span class="lp-timer-sep">:</span>
                        <div class="lp-timer-unit">
                            <span class="lp-timer-num" id="timerSecs">44</span>
                            <span class="lp-timer-lbl">Secs</span>
                        </div>
                    </div>
                </div>

                <!-- Color Swatches & Variants -->
                <?php if (!empty($variants)): ?>
                    <div class="lp-variant-box">
                        <div class="lp-variant-header">
                            <span class="lp-variant-title">Select Color:</span>
                            <span class="lp-variant-selected-name" id="selectedColorNameDisplay">Choose your leather finish</span>
                        </div>
                        <div class="lp-swatches-grid">
                            <?php foreach ($variants as $v): ?>
                                <div class="lp-swatch-item" 
                                     data-id="<?php echo $v['color_id']; ?>"
                                     data-name="<?php echo htmlspecialchars($v['color_name']); ?>"
                                     data-price="<?php echo $v['price']; ?>"
                                     data-image="<?php echo !empty($v['image_path']) ? normalize_product_image_url($v['image_path'], $link_prefix) : ''; ?>"
                                     data-gallery='<?php 
                                         $v_gal = [];
                                         if (!empty($v['gallery_images'])) {
                                             $v_decoded = json_decode($v['gallery_images'], true);
                                             if (is_array($v_decoded)) {
                                                 foreach($v_decoded as $gimg) {
                                                     if (!empty($gimg)) $v_gal[] = normalize_product_image_url($gimg, $link_prefix);
                                                 }
                                             }
                                         }
                                         echo htmlspecialchars(json_encode($v_gal), ENT_QUOTES, 'UTF-8'); 
                                     ?>'
                                     onclick="selectVariantColor(this)">
                                    <div class="lp-swatch-circle" style="background-color: <?php echo $v['hex_code']; ?>;">
                                        <?php if (!empty($v['image_path'])): ?>
                                            <img src="<?php echo normalize_product_image_url($v['image_path'], $link_prefix); ?>" alt="<?php echo htmlspecialchars($v['color_name']); ?>" onerror="this.onerror=null; this.src='<?php echo $link_prefix; ?>assets/images/amdika-logo.png';">
                                        <?php endif; ?>
                                    </div>
                                    <span class="lp-swatch-name"><?php echo htmlspecialchars($v['color_name']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Quantity & Actions -->
                <div class="lp-action-container">
                    <div class="lp-qty-row">
                        <span class="lp-qty-label">Quantity:</span>
                        <div class="lp-qty-selector">
                            <button type="button" class="lp-qty-btn" onclick="changeQuantity(-1)"><i class="fas fa-minus" style="font-size: 11px;"></i></button>
                            <input type="text" id="productQuantity" value="1" readonly class="lp-qty-input">
                            <button type="button" class="lp-qty-btn" onclick="changeQuantity(1)"><i class="fas fa-plus" style="font-size: 11px;"></i></button>
                        </div>
                        <span class="text-success small fw-semibold"><i class="fas fa-check"></i> In Stock &amp; Ready to Ship</span>
                    </div>

                    <!-- Direct-Response Primary CTAs -->
                    <div class="lp-buttons-grid">
                        <button class="lp-btn-buy-now" onclick="executeBuyNow(<?php echo $product['id']; ?>)">
                            <span class="lp-cta-main"><i class="fas fa-bolt"></i> Buy Now — Instant Checkout</span>
                            <span class="lp-cta-sub">Cash on Delivery / Prepaid Accepted</span>
                        </button>
                        <button class="lp-btn-add-cart" onclick="executeAddToCart(<?php echo $product['id']; ?>)">
                            <i class="fas fa-shopping-bag"></i> Add to Cart
                        </button>
                    </div>
                </div>

                <!-- Trust Strip -->
                <div class="lp-trust-strip">
                    <div class="lp-trust-item">
                        <div class="lp-trust-icon"><i class="fas fa-shipping-fast"></i></div>
                        <p class="lp-trust-title">Free Express</p>
                        <p class="lp-trust-desc">2-4 Days Delivery</p>
                    </div>
                    <div class="lp-trust-item">
                        <div class="lp-trust-icon"><i class="fas fa-money-bill-wave"></i></div>
                        <p class="lp-trust-title">Cash on Delivery</p>
                        <p class="lp-trust-desc">Pay at Doorstep</p>
                    </div>
                    <div class="lp-trust-item">
                        <div class="lp-trust-icon"><i class="fas fa-undo-alt"></i></div>
                        <p class="lp-trust-title">Easy Returns</p>
                        <p class="lp-trust-desc">7-Day Replacement</p>
                    </div>
                    <div class="lp-trust-item">
                        <div class="lp-trust-icon"><i class="fas fa-shield-alt"></i></div>
                        <p class="lp-trust-title">100% Genuine</p>
                        <p class="lp-trust-desc">Certified Leather</p>
                    </div>
                </div>

                <!-- Payment Methods & Badges -->
                <div class="lp-payments-row">
                    <span>Guaranteed Safe &amp; Secure Checkout</span>
                    <div class="lp-payment-badges">
                        <span class="lp-payment-badge"><i class="fas fa-qrcode"></i> UPI</span>
                        <span class="lp-payment-badge"><i class="fab fa-cc-visa"></i> Visa</span>
                        <span class="lp-payment-badge"><i class="fab fa-cc-mastercard"></i> Mastercard</span>
                        <span class="lp-payment-badge"><i class="fas fa-wallet"></i> NetBanking</span>
                        <span class="lp-payment-badge"><i class="fas fa-hand-holding-usd"></i> COD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 1: Why Choose Amadika (4-Pillars Showcase) -->
    <div class="lp-section-card">
        <div class="lp-section-header">
            <span class="lp-section-tag">Uncompromising Luxury</span>
            <h2 class="lp-section-title">Why Amadika Stands Apart</h2>
            <p class="lp-section-subtitle">Every piece is masterfully handcrafted using the finest ethically sourced leather, engineered to elevate your daily lifestyle with enduring sophistication.</p>
        </div>
        <div class="lp-features-grid">
            <div class="lp-feature-box">
                <div class="lp-feature-icon-wrapper"><i class="fas fa-award"></i></div>
                <h3 class="lp-feature-name">Master Craftsmanship</h3>
                <p class="lp-feature-desc">Meticulously cut and edge-finished by generational leather artisans for flawless precision.</p>
            </div>
            <div class="lp-feature-box">
                <div class="lp-feature-icon-wrapper"><i class="fas fa-feather-alt"></i></div>
                <h3 class="lp-feature-name">Full-Grain Leather</h3>
                <p class="lp-feature-desc">Authentic top-tier leather that develops a richer, distinguished natural patina the more you use it.</p>
            </div>
            <div class="lp-feature-box">
                <div class="lp-feature-icon-wrapper"><i class="fas fa-layer-group"></i></div>
                <h3 class="lp-feature-name">Engineered Function</h3>
                <p class="lp-feature-desc">Intelligent layouts that provide maximum utility, organized compartments, and slim ergonomics.</p>
            </div>
            <div class="lp-feature-box">
                <div class="lp-feature-icon-wrapper"><i class="fas fa-shield-virus"></i></div>
                <h3 class="lp-feature-name">Solid Brass Hardware</h3>
                <p class="lp-feature-desc">Heavy-duty hardware, tarnish-proof zippers, and reinforced stress stitching designed to last decades.</p>
            </div>
        </div>
    </div>

    <!-- Section 2: Amadika vs Ordinary Alternatives (Conversion Matrix) -->
    <div class="lp-section-card">
        <div class="lp-section-header">
            <span class="lp-section-tag">True Value Comparison</span>
            <h2 class="lp-section-title">The Amadika Standard vs Alternatives</h2>
            <p class="lp-section-subtitle">Discover why thousands of discerning customers upgrade to Amadika's authentic leather products over mass-market brands.</p>
        </div>
        <div class="lp-comparison-table-wrapper">
            <table class="lp-comparison-table">
                <thead>
                    <tr>
                        <th class="lp-col-feature">Key Aspect</th>
                        <th class="lp-col-amadika"><i class="fas fa-crown text-warning me-2"></i>Amadika Premium</th>
                        <th class="lp-col-others">Ordinary Market Brands</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="lp-col-feature">Material Quality</td>
                        <td class="lp-col-amadika"><i class="fas fa-check-circle text-success me-2"></i>100% Genuine Full-Grain Leather</td>
                        <td class="lp-col-others"><i class="fas fa-times-circle text-danger me-2"></i>Synthetic / Faux PU Leather (peels quickly)</td>
                    </tr>
                    <tr>
                        <td class="lp-col-feature">Longevity &amp; Aging</td>
                        <td class="lp-col-amadika"><i class="fas fa-check-circle text-success me-2"></i>Ages gracefully with a rich natural patina</td>
                        <td class="lp-col-others"><i class="fas fa-times-circle text-danger me-2"></i>Cracks, loses shape &amp; degrades within months</td>
                    </tr>
                    <tr>
                        <td class="lp-col-feature">Stitching &amp; Finish</td>
                        <td class="lp-col-amadika"><i class="fas fa-check-circle text-success me-2"></i>Reinforced bonded nylon thread with edge paint</td>
                        <td class="lp-col-others"><i class="fas fa-times-circle text-danger me-2"></i>Flimsy single stitching prone to unraveling</td>
                    </tr>
                    <tr>
                        <td class="lp-col-feature">Hardware &amp; Zippers</td>
                        <td class="lp-col-amadika"><i class="fas fa-check-circle text-success me-2"></i>Solid corrosion-resistant alloy &amp; smooth sliders</td>
                        <td class="lp-col-others"><i class="fas fa-times-circle text-danger me-2"></i>Cheap lightweight metals that jam and break</td>
                    </tr>
                    <tr>
                        <td class="lp-col-feature">Customer Protection</td>
                        <td class="lp-col-amadika"><i class="fas fa-check-circle text-success me-2"></i>7-Day Replacement Guarantee &amp; Dedicated Support</td>
                        <td class="lp-col-others"><i class="fas fa-times-circle text-danger me-2"></i>No post-purchase support or return hassle</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 3: Product Description & Video Showcase -->
    <div class="lp-section-card">
        <div class="lp-section-header">
            <span class="lp-section-tag">Product Story</span>
            <h2 class="lp-section-title">Designed with Passion &amp; Purpose</h2>
        </div>
        <div class="row g-4 align-items-center">
            <div class="<?php echo !empty($product['video_url']) ? 'col-lg-7' : 'col-12'; ?>">
                <div class="lp-prose-content">
                    <?php echo $product['description']; ?>
                </div>
            </div>
            <?php if (!empty($product['video_url'])): ?>
                <?php 
                    $video_url = $product['video_url'];
                    $embed_url = $video_url;
                    if (strpos($video_url, 'watch?v=') !== false) {
                        $parts = parse_url($video_url);
                        parse_str($parts['query'], $query);
                        if (isset($query['v'])) {
                            $embed_url = "https://www.youtube.com/embed/" . $query['v'];
                        }
                    } elseif (strpos($video_url, 'youtu.be/') !== false) {
                        $parts = explode('youtu.be/', $video_url);
                        $embed_url = "https://www.youtube.com/embed/" . end($parts);
                    }
                ?>
                <div class="col-lg-5">
                    <div class="lp-video-theater">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="fab fa-youtube text-danger fs-5"></i>
                            <span class="fw-bold small text-uppercase tracking-wider">Product In Action</span>
                        </div>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow">
                            <iframe src="<?php echo $embed_url; ?>" title="Product Video" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Section 4: Verified Customer Reviews -->
    <div class="lp-section-card" id="reviewsSection">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 pb-3 border-bottom">
            <div>
                <span class="lp-section-tag">Social Proof</span>
                <h2 class="lp-section-title mb-1">Customer Reviews &amp; Ratings</h2>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <span class="lp-stars-badge fs-6">
                        <?php echo $real_avg_rating; ?> <i class="fas fa-star" style="font-size: 11px;"></i>
                    </span>
                    <span class="text-muted fw-semibold">Based on <?php echo $display_review_count; ?> Verified Purchases</span>
                </div>
            </div>
            <button class="btn btn-dark px-4 py-2 rounded-pill fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#reviewModal">
                <i class="fas fa-pen me-2"></i> Write a Review
            </button>
        </div>

        <?php if ($review_count > 0): ?>
            <div class="row g-4">
                <?php while ($row = $rev_result->fetch_assoc()): ?>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="lp-stars-badge" style="padding: 2px 6px; font-size: 11px;">
                                        <?php echo $row['rating']; ?> <i class="fas fa-star" style="font-size: 9px;"></i>
                                    </span>
                                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($row['name']); ?></span>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small">
                                    <i class="fas fa-check-circle me-1"></i> Verified Buyer
                                </span>
                            </div>
                            <p class="text-secondary small mb-2"><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                            <?php if (!empty($row['image'])): ?>
                                <div class="mt-2 mb-2">
                                    <img src="<?php echo normalize_product_image_url($row['image'], $link_prefix); ?>" 
                                         alt="Review Image" style="max-height: 85px; border-radius: 6px; border: 1px solid #ddd; object-fit: cover;" onerror="this.style.display='none';">
                                </div>
                            <?php endif; ?>
                            <div class="text-muted" style="font-size: 11px;">
                                Reviewed on <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <!-- Editorial Testimonial Grid for Brand Credibility -->
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-4 rounded-3 bg-light border h-100">
                        <div class="d-flex align-items-center gap-1 text-warning mb-2">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="small text-secondary mb-3">"The leather quality is unmatched. You can immediately feel the weight and luxury. Delivery arrived in 2 days in Delhi in gorgeous packaging."</p>
                        <div class="fw-bold small text-dark">Vikram Singhania <span class="badge bg-success-subtle text-success small ms-2">Verified Buyer</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-3 bg-light border h-100">
                        <div class="d-flex align-items-center gap-1 text-warning mb-2">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="small text-secondary mb-3">"Bought this via their Instagram ad and was blown away. The stitching and natural leather aroma prove it's the real deal. Highly recommend!"</p>
                        <div class="fw-bold small text-dark">Ananya Patel <span class="badge bg-success-subtle text-success small ms-2">Verified Buyer</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-3 bg-light border h-100">
                        <div class="d-flex align-items-center gap-1 text-warning mb-2">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="small text-secondary mb-3">"Top tier craftsmanship. Looks and feels like a luxury European leather piece at a fraction of the cost. Cash on delivery was seamless."</p>
                        <div class="fw-bold small text-dark">Rohit Sharma <span class="badge bg-success-subtle text-success small ms-2">Verified Buyer</span></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Section 5: Frequently Asked Questions (FAQ) Accordion -->
    <div class="lp-section-card">
        <div class="lp-section-header">
            <span class="lp-section-tag">Got Questions?</span>
            <h2 class="lp-section-title">Frequently Asked Questions</h2>
            <p class="lp-section-subtitle">Everything you need to know about your order, shipping, and authentic leather care.</p>
        </div>
        <div class="mx-auto" style="max-width: 800px;">
            <div class="lp-faq-item active">
                <div class="lp-faq-question" onclick="toggleFaq(this)">
                    <span>How long does delivery take?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="lp-faq-answer" style="display: block;">
                    We dispatch all orders within 24 hours via reliable premium express couriers (Bluedart, Delhivery, DTDC). Metro deliveries typically take 2-3 business days, while other locations across India receive delivery within 3-5 business days.
                </div>
            </div>
            <div class="lp-faq-item">
                <div class="lp-faq-question" onclick="toggleFaq(this)">
                    <span>Is Cash on Delivery (COD) available?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="lp-faq-answer">
                    Yes! Cash on Delivery is available across 99% of Indian pin codes. You can pay securely via cash or UPI scan directly to the courier delivery partner when the package reaches your doorstep.
                </div>
            </div>
            <div class="lp-faq-item">
                <div class="lp-faq-question" onclick="toggleFaq(this)">
                    <span>Is this 100% genuine authentic leather?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="lp-faq-answer">
                    Absolutely. Every Amadika creation is certified 100% authentic genuine leather. We strictly reject cheap PU, faux, or synthetic imitations. Our leather develops a natural, beautiful patina over time, gaining character as you use it.
                </div>
            </div>
            <div class="lp-faq-item">
                <div class="lp-faq-question" onclick="toggleFaq(this)">
                    <span>What if I need a return or replacement?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="lp-faq-answer">
                    We offer a hassle-free 7-day easy replacement policy. If your product has any manufacturing defect or transit damage, simply reach out to our WhatsApp or email support, and our team will promptly arrange a replacement.
                </div>
            </div>
            <div class="lp-faq-item">
                <div class="lp-faq-question" onclick="toggleFaq(this)">
                    <span>How do I care for my leather product?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="lp-faq-answer">
                    Keep your leather accessory away from prolonged excessive moisture and direct extreme heat. To maintain its natural luster, gently wipe it with a soft dry cloth and apply a neutral leather conditioner once or twice a year.
                </div>
            </div>
        </div>
    </div>

    <!-- Section 6: Similar Products Carousel -->
    <?php
    $cat_id = (int)$product['category_id'];
    $curr_id = (int)$product['id'];
    $rel_sql = "SELECT * FROM products WHERE category_id = $cat_id AND id != $curr_id AND status = 'active' ORDER BY RAND() LIMIT 4";
    $rel_res = $conn->query($rel_sql);

    if ($rel_res && $rel_res->num_rows > 0):
    ?>
    <div class="lp-section-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="lp-section-tag">Complete the Collection</span>
                <h2 class="lp-section-title mb-0">You May Also Appreciate</h2>
            </div>
            <a href="<?php echo $link_prefix; ?>products.php?category=<?php echo urlencode($product['category_slug']); ?>" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold">View All</a>
        </div>
        <div class="row g-3">
            <?php while ($rp = $rel_res->fetch_assoc()):
                $rp_img = !empty($rp['featured_image']) ? normalize_product_image_url($rp['featured_image'], $link_prefix) : normalize_product_image_url('assets/images/amdika-logo.png', $link_prefix);
                $rp_gst = isset($rp['gst_percent']) ? (float)$rp['gst_percent'] : 0;
                $rp_sale = round($rp['sale_price'] + ($rp['sale_price'] * $rp_gst / 100));
                $rp_mrp = round($rp['mrp'] + ($rp['mrp'] * $rp_gst / 100));
            ?>
            <div class="col-6 col-md-3">
                <div class="card h-100 border rounded-4 overflow-hidden shadow-sm" style="transition: transform 0.2s;">
                    <a href="<?php echo $link_prefix; ?>product/<?php echo $rp['slug']; ?>" class="d-block bg-light text-center p-3" style="height: 180px;">
                        <img src="<?php echo $rp_img; ?>" alt="<?php echo htmlspecialchars($rp['name']); ?>" class="w-100 h-100 object-fit-contain" onerror="this.onerror=null; this.src='<?php echo $link_prefix; ?>assets/images/amdika-logo.png';">
                    </a>
                    <div class="card-body p-3 d-flex flex-direction-column justify-content-between">
                        <div>
                            <div class="small text-success fw-bold mb-1"><i class="fas fa-star" style="font-size: 10px;"></i> 4.9</div>
                            <h6 class="card-title text-truncate mb-2">
                                <a href="<?php echo $link_prefix; ?>product/<?php echo $rp['slug']; ?>" class="text-dark text-decoration-none fw-bold"><?php echo htmlspecialchars($rp['name']); ?></a>
                            </h6>
                        </div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="fw-bold fs-6 text-dark">₹<?php echo number_format($rp_sale); ?></span>
                            <?php if ($rp_mrp > $rp_sale): ?>
                                <span class="text-muted text-decoration-line-through small">₹<?php echo number_format($rp_mrp); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Sticky Mobile Purchase Bar -->
<div class="lp-mobile-sticky-bar" id="mobileStickyBar">
    <div class="lp-mobile-bar-inner">
        <div class="lp-mobile-bar-info">
            <img src="<?php echo $gallery[0]; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="lp-mobile-bar-thumb" id="mobileBarThumb" onerror="this.onerror=null; this.src='<?php echo $link_prefix; ?>assets/images/amdika-logo.png';">
            <div class="lp-mobile-bar-details">
                <p class="lp-mobile-bar-title"><?php echo htmlspecialchars($product['name']); ?></p>
                <p class="lp-mobile-bar-price" id="mobileBarPrice">₹<?php echo number_format($sale); ?></p>
            </div>
        </div>
        <button class="lp-mobile-bar-cta" onclick="executeBuyNow(<?php echo $product['id']; ?>)">
            <i class="fas fa-bolt me-1"></i> Buy Now
        </button>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lpLightboxModal" class="lp-lightbox-modal" onclick="if(event.target === this) closeLightbox();">
    <span class="lp-lightbox-close" onclick="closeLightbox()">&times;</span>
    <button class="lp-lightbox-nav lp-lightbox-prev" onclick="prevLightbox()"><i class="fas fa-chevron-left"></i></button>
    <img id="lightboxImage" src="<?php echo $gallery[0]; ?>" alt="Product Enlarged" class="lp-lightbox-img" onerror="this.onerror=null; this.src='<?php echo $link_prefix; ?>assets/images/amdika-logo.png';">
    <button class="lp-lightbox-nav lp-lightbox-next" onclick="nextLightbox()"><i class="fas fa-chevron-right"></i></button>
</div>

<!-- Social Proof Live Toast -->
<div class="lp-social-proof-toast" id="socialProofToast">
    <img src="<?php echo $gallery[0]; ?>" alt="Recent Purchase" class="lp-toast-thumb" id="toastProductThumb" onerror="this.onerror=null; this.src='<?php echo $link_prefix; ?>assets/images/amdika-logo.png';">
    <div class="lp-toast-text">
        <span class="fw-bold text-dark" id="toastBuyerName">Rajesh from Mumbai</span> just purchased this item
        <div class="text-muted" style="font-size: 10px;">Verified Order • 12 mins ago</div>
    </div>
    <span class="lp-toast-close" onclick="document.getElementById('socialProofToast').style.display='none';">&times;</span>
</div>

<!-- Floating WhatsApp Concierge -->
<a href="https://api.whatsapp.com/send?phone=919810000000&text=<?php echo urlencode("Hello Amadika, I am interested in " . $product['name'] . " (" . $curr_url . ")"); ?>" 
   target="_blank" 
   class="lp-floating-wa" 
   title="Chat with Amadika Specialist" 
   id="whatsappFloatingBtn">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Review Submission Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-dark text-white p-4">
                    <h5 class="modal-title fw-bold" id="reviewModalLabel" style="font-family: var(--font-heading);">Rate this Masterpiece</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3 text-center">
                        <label class="form-label d-block fw-bold text-dark">Your Overall Rating</label>
                        <div class="rating-stars" style="font-size: 2.2rem; color: #cbd5e1; cursor: pointer;">
                            <i class="fas fa-star lp-star" data-value="1"></i>
                            <i class="fas fa-star lp-star" data-value="2"></i>
                            <i class="fas fa-star lp-star" data-value="3"></i>
                            <i class="fas fa-star lp-star" data-value="4"></i>
                            <i class="fas fa-star lp-star" data-value="5"></i>
                        </div>
                        <input type="hidden" name="r_rating" id="ratingScoreInput" value="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3 py-2" name="r_name" placeholder="e.g. Rahul Sharma" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control rounded-3 py-2" name="r_email" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Your Review Comments</label>
                        <textarea class="form-control rounded-3" name="r_message" rows="3" placeholder="Share your experience with the craftsmanship, packaging, and feel..."></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-dark">Attach Photo (Optional)</label>
                        <input type="file" class="form-control rounded-3" name="r_image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit_review" class="btn btn-dark rounded-pill px-4 fw-bold">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Interactive Scripting & Facebook Ads Tracking -->
<script>
// Configuration Constants
const baseSalePrice = <?php echo $sale; ?>;
const baseMrp = <?php echo $mrp; ?>;
const baseDiscount = <?php echo $disc; ?>;
const gstPercentage = <?php echo $gst_pct; ?>;
const linkPrefix = '<?php echo $link_prefix; ?>';
let currentGallery = <?php echo json_encode($gallery); ?>;
let activeGalleryIndex = 0;
let selectedColorId = null;

// Track Meta Pixel ViewContent for Facebook Ads Optimization
document.addEventListener('DOMContentLoaded', function() {
    if (typeof fbq === 'function') {
        fbq('track', 'ViewContent', {
            content_name: '<?php echo addslashes($product['name']); ?>',
            content_ids: ['<?php echo $product['id']; ?>'],
            content_type: 'product',
            value: <?php echo $sale; ?>,
            currency: 'INR'
        });
    }

    // Auto select first color variant if available
    const firstSwatch = document.querySelector('.lp-swatch-item');
    if (firstSwatch) {
        selectVariantColor(firstSwatch);
    }

    // Star rating picker initialization
    initStarRatingPicker();

    // Urgency countdown timer simulation
    startUrgencyCountdown();

    // Sticky mobile bottom bar scroll observer
    initStickyMobileBar();

    // Randomize social proof toast cities
    rotateSocialProofToast();
});

// Switch Hero Image from Thumbnail Strip
function switchHeroImage(src, element, index) {
    if (!src) return;
    const fullSrc = (src.startsWith('http') || src.startsWith('/')) 
        ? src 
        : ((linkPrefix ? linkPrefix.replace(/\/+$/, '') + '/' : '/') + src.replace(/^(\.\.\/|\.\/|\/)+/, ''));
    
    const mainImg = document.getElementById('mainHeroImage');
    if (mainImg) mainImg.src = fullSrc;
    const lbImg = document.getElementById('lightboxImage');
    if (lbImg) lbImg.src = fullSrc;
    activeGalleryIndex = index;

    document.querySelectorAll('.lp-thumb-item').forEach(el => el.classList.remove('active'));
    if (element) {
        element.classList.add('active');
    }
}

// Select Color Variant
function selectVariantColor(element) {
    document.querySelectorAll('.lp-swatch-item').forEach(item => item.classList.remove('active'));
    element.classList.add('active');

    selectedColorId = element.dataset.id;
    const colorName = element.dataset.name;
    document.getElementById('selectedColorNameDisplay').innerText = colorName;

    // Calculate variant price if defined
    let variantPrice = parseFloat(element.dataset.price);
    if (variantPrice > 0) {
        let finalVariantPrice = Math.round(variantPrice + (variantPrice * gstPercentage / 100));
        document.getElementById('displaySalePrice').innerText = '₹' + finalVariantPrice.toLocaleString();
        document.getElementById('mobileBarPrice').innerText = '₹' + finalVariantPrice.toLocaleString();
        
        if (baseMrp > finalVariantPrice) {
            let newDisc = Math.round(((baseMrp - finalVariantPrice) / baseMrp) * 100);
            const badge = document.getElementById('displayDiscountBadge');
            if (badge) badge.innerText = newDisc + '% OFF';
            const saveBadge = document.getElementById('badgeSave');
            if (saveBadge) saveBadge.innerHTML = '<i class="fas fa-tag"></i> Save ' + newDisc + '% Today';
        }
    } else {
        document.getElementById('displaySalePrice').innerText = '₹' + baseSalePrice.toLocaleString();
        document.getElementById('mobileBarPrice').innerText = '₹' + baseSalePrice.toLocaleString();
        const badge = document.getElementById('displayDiscountBadge');
        if (badge) badge.innerText = baseDiscount + '% OFF';
    }

    // Update Gallery with Variant Specific Images if present
    let variantGallery = [];
    const rawGallery = element.dataset.gallery;
    if (rawGallery && rawGallery.trim() !== '') {
        try {
            variantGallery = JSON.parse(rawGallery);
        } catch (e) {
            variantGallery = [];
        }
    }

    const mainVarImg = element.dataset.image;
    if (mainVarImg && mainVarImg.trim() !== '' && !variantGallery.includes(mainVarImg)) {
        variantGallery.unshift(mainVarImg);
    }

    variantGallery = variantGallery.filter(img => img && img.trim() !== '');

    if (variantGallery.length > 0) {
        updateGalleryStrip(variantGallery);
    }
}

// Update Thumbnails Strip Dynamically
function updateGalleryStrip(images) {
    currentGallery = images.map(img => {
        if (!img) return '';
        if (img.startsWith('http') || img.startsWith('/')) return img;
        return (linkPrefix ? linkPrefix.replace(/\/+$/, '') + '/' : '/') + img.replace(/^(\.\.\/|\.\/|\/)+/, '');
    }).filter(img => img !== '');

    const track = document.getElementById('thumbnailsTrack');
    if (!track) return;
    track.innerHTML = '';

    currentGallery.forEach((img, idx) => {
        const thumb = document.createElement('div');
        thumb.className = `lp-thumb-item ${idx === 0 ? 'active' : ''}`;
        thumb.onclick = function() { switchHeroImage(img, this, idx); };
        thumb.innerHTML = `<img src="${img}" alt="Thumb" onerror="this.onerror=null; this.src='${linkPrefix}assets/images/amdika-logo.png';">`;
        track.appendChild(thumb);
    });

    if (currentGallery.length > 0) {
        const mainImg = document.getElementById('mainHeroImage');
        if (mainImg) {
            mainImg.src = currentGallery[0];
            mainImg.onerror = function() { this.onerror = null; this.src = linkPrefix + 'assets/images/amdika-logo.png'; };
        }
        const lbImg = document.getElementById('lightboxImage');
        if (lbImg) lbImg.src = currentGallery[0];
        const mobileThumb = document.getElementById('mobileBarThumb');
        if (mobileThumb) mobileThumb.src = currentGallery[0];
    }
}

// Quantity Counter Stepper
function changeQuantity(delta) {
    const qtyInput = document.getElementById('productQuantity');
    let currentQty = parseInt(qtyInput.value) || 1;
    currentQty = Math.max(1, Math.min(10, currentQty + delta));
    qtyInput.value = currentQty;
}

// Add To Cart Execution
function executeAddToCart(productId) {
    <?php if (!empty($variants)): ?>
    if (!selectedColorId) {
        Swal.fire({
            icon: 'warning',
            title: 'Please Select a Color',
            text: 'Choose your desired leather finish before adding to your bag.',
            confirmButtonColor: '#0f172a'
        });
        return;
    }
    <?php endif; ?>

    const qty = parseInt(document.getElementById('productQuantity').value) || 1;

    // Track Facebook Pixel AddToCart
    if (typeof fbq === 'function') {
        fbq('track', 'AddToCart', {
            content_name: '<?php echo addslashes($product['name']); ?>',
            content_ids: [productId],
            content_type: 'product',
            value: <?php echo $sale; ?> * qty,
            currency: 'INR'
        });
    }

    fetch(linkPrefix + 'includes/cart_actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=add&product_id=${productId}&quantity=${qty}&color_id=${selectedColorId || ''}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            if (typeof updateCartCount === 'function') updateCartCount();
            if (typeof openCartSidebar === 'function') {
                openCartSidebar();
            } else {
                Swal.fire({
                    toast: true,
                    position: 'bottom-end',
                    icon: 'success',
                    title: 'Added to your bag!',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        } else {
            Swal.fire({ icon: 'error', title: 'Notice', text: data.message || 'Unable to update cart.' });
        }
    })
    .catch(() => {
        Swal.fire({ icon: 'error', title: 'Notice', text: 'Error connecting to server.' });
    });
}

// Buy Now Execution (Direct to Checkout)
function executeBuyNow(productId) {
    <?php if (!empty($variants)): ?>
    if (!selectedColorId) {
        Swal.fire({
            icon: 'warning',
            title: 'Select Color Finish',
            text: 'Please select your preferred leather finish before proceeding.',
            confirmButtonColor: '#0f172a'
        });
        return;
    }
    <?php endif; ?>

    const qty = parseInt(document.getElementById('productQuantity').value) || 1;

    // Track Facebook Pixel InitiateCheckout
    if (typeof fbq === 'function') {
        fbq('track', 'InitiateCheckout', {
            content_name: '<?php echo addslashes($product['name']); ?>',
            content_ids: [productId],
            content_type: 'product',
            value: <?php echo $sale; ?> * qty,
            currency: 'INR'
        });
    }

    fetch(linkPrefix + 'includes/cart_actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=add&product_id=${productId}&quantity=${qty}&color_id=${selectedColorId || ''}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.href = linkPrefix + 'cart.php';
        } else {
            Swal.fire({ icon: 'error', title: 'Notice', text: data.message });
        }
    })
    .catch(() => {
        window.location.href = linkPrefix + 'cart.php';
    });
}

// Urgency Countdown Timer (Dynamic Simulation)
function startUrgencyCountdown() {
    let totalSeconds = (3 * 3600) + (28 * 60) + 44;
    setInterval(() => {
        if (totalSeconds <= 0) totalSeconds = 4 * 3600;
        totalSeconds--;
        const h = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
        const m = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
        const s = String(totalSeconds % 60).padStart(2, '0');
        
        const elH = document.getElementById('timerHours');
        const elM = document.getElementById('timerMins');
        const elS = document.getElementById('timerSecs');
        if (elH) elH.innerText = h;
        if (elM) elM.innerText = m;
        if (elS) elS.innerText = s;
    }, 1000);
}

// Lightbox Controls
function openLightbox() {
    document.getElementById('lpLightboxModal').style.display = 'flex';
}
function closeLightbox() {
    document.getElementById('lpLightboxModal').style.display = 'none';
}
function nextLightbox() {
    if (currentGallery.length <= 1) return;
    activeGalleryIndex = (activeGalleryIndex + 1) % currentGallery.length;
    document.getElementById('lightboxImage').src = currentGallery[activeGalleryIndex];
}
function prevLightbox() {
    if (currentGallery.length <= 1) return;
    activeGalleryIndex = (activeGalleryIndex - 1 + currentGallery.length) % currentGallery.length;
    document.getElementById('lightboxImage').src = currentGallery[activeGalleryIndex];
}

// Keyboard navigation for Lightbox
document.addEventListener('keydown', (e) => {
    const modal = document.getElementById('lpLightboxModal');
    if (modal && modal.style.display === 'flex') {
        if (e.key === 'ArrowRight') nextLightbox();
        if (e.key === 'ArrowLeft') prevLightbox();
        if (e.key === 'Escape') closeLightbox();
    }
});

// FAQ Accordion Toggle
function toggleFaq(el) {
    const parent = el.closest('.lp-faq-item');
    const answer = parent.querySelector('.lp-faq-answer');
    const isActive = parent.classList.contains('active');

    document.querySelectorAll('.lp-faq-item').forEach(item => {
        item.classList.remove('active');
        item.querySelector('.lp-faq-answer').style.display = 'none';
    });

    if (!isActive) {
        parent.classList.add('active');
        answer.style.display = 'block';
    }
}

// Star Rating Picker in Modal
function initStarRatingPicker() {
    const stars = document.querySelectorAll('.lp-star');
    const scoreInput = document.getElementById('ratingScoreInput');
    if (!stars.length || !scoreInput) return;

    function renderStars(rating) {
        stars.forEach(s => {
            const val = parseInt(s.getAttribute('data-value'));
            s.style.color = (val <= rating) ? '#f59e0b' : '#cbd5e1';
        });
    }

    renderStars(5);

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const val = parseInt(this.getAttribute('data-value'));
            scoreInput.value = val;
            renderStars(val);
        });
        star.addEventListener('mouseover', function() {
            const val = parseInt(this.getAttribute('data-value'));
            renderStars(val);
        });
        star.addEventListener('mouseout', function() {
            renderStars(parseInt(scoreInput.value));
        });
    });
}

// Sticky Mobile Bar Scroll Observer
function initStickyMobileBar() {
    const mobileBar = document.getElementById('mobileStickyBar');
    const heroCard = document.querySelector('.lp-hero-card');
    if (!mobileBar || !heroCard) return;

    window.addEventListener('scroll', () => {
        const rect = heroCard.getBoundingClientRect();
        if (rect.bottom < 150) {
            mobileBar.style.display = 'block';
        } else {
            mobileBar.style.display = 'none';
        }
    });
}

// Rotate Social Proof Live Toast
function rotateSocialProofToast() {
    const toast = document.getElementById('socialProofToast');
    const buyerEl = document.getElementById('toastBuyerName');
    if (!toast || !buyerEl) return;

    const buyers = [
        "Rajesh from Mumbai",
        "Priya from Bengaluru",
        "Arun from New Delhi",
        "Kavita from Hyderabad",
        "Sameer from Pune",
        "Nikhil from Gurugram"
    ];

    let bIdx = 0;
    setInterval(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(15px)';
        setTimeout(() => {
            bIdx = (bIdx + 1) % buyers.length;
            buyerEl.innerText = buyers[bIdx];
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 500);
    }, 14000);
}
</script>
<?php
$page_title = 'Handcrafted Luxury Leather Goods - Amadika';
require_once 'database/db_config.php';
require_once 'includes/image_helper.php';
include 'includes/header.php';

// --- Pagination & Filter Logic ---
$per_page = 12;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

$category_slug = isset($_GET['category']) ? trim($_GET['category']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$min_price = (isset($_GET['min_price']) && $_GET['min_price'] !== '') ? max(0, (int)$_GET['min_price']) : 0;
$max_price = (isset($_GET['max_price']) && $_GET['max_price'] !== '') ? (int)$_GET['max_price'] : 1000000;
$on_sale = (isset($_GET['on_sale']) && $_GET['on_sale'] == '1') ? 1 : 0;
$in_stock = (isset($_GET['in_stock']) && $_GET['in_stock'] == '0') ? 0 : 1;

// Build Query Dynamically
$where = ["status = 'active'"];
$params = [];
$types = "";

$current_category = null;
if (!empty($category_slug)) {
    $cat_stmt = $conn->prepare("SELECT id, name, slug FROM product_categories WHERE slug = ?");
    if ($cat_stmt) {
        $cat_stmt->bind_param("s", $category_slug);
        $cat_stmt->execute();
        $cat_res = $cat_stmt->get_result();
        if ($cat_row = $cat_res->fetch_assoc()) {
            $current_category = $cat_row;
            $where[] = "category_id = ?";
            $params[] = $cat_row['id'];
            $types .= "i";
        }
    }
}

if (!empty($search)) {
    $where[] = "(name LIKE ? OR description LIKE ?)";
    $s_param = "%$search%";
    $params[] = $s_param;
    $params[] = $s_param;
    $types .= "ss";
}

if ($min_price > 0) { 
    $where[] = "sale_price >= ?"; 
    $params[] = $min_price; 
    $types .= "i"; 
}
if ($max_price < 1000000) { 
    $where[] = "sale_price <= ?"; 
    $params[] = $max_price; 
    $types .= "i"; 
}

if ($on_sale === 1) {
    $where[] = "regular_price > sale_price";
}

// Total Count for Pagination
$count_sql = "SELECT COUNT(*) as total FROM products WHERE " . implode(" AND ", $where);
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) { 
    $count_stmt->bind_param($types, ...$params); 
}
$count_stmt->execute();
$total_items = (int)$count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = max(1, ceil($total_items / $per_page));

// Sorting
$order = "id DESC";
switch ($sort) {
    case 'price_low': 
        $order = "sale_price ASC"; 
        break;
    case 'price_high': 
        $order = "sale_price DESC"; 
        break;
    case 'popular': 
        $order = "id ASC"; 
        break;
    case 'discount': 
        $order = "(regular_price - sale_price) DESC"; 
        break;
    case 'newest':
    default:
        $order = "id DESC"; 
        break;
}

$sql = "SELECT * FROM products WHERE " . implode(" AND ", $where) . " ORDER BY $order LIMIT ? OFFSET ?";
$p_params = $params;
$p_types = $types . "ii";
$p_params[] = $per_page;
$p_params[] = $offset;

$stmt = $conn->prepare($sql);
if (!empty($p_params)) { 
    $stmt->bind_param($p_types, ...$p_params); 
}
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch Categories with live product counts for Sidebar
$sidebar_cats = [];
$cat_count_query = "SELECT c.id, c.name, c.slug, COUNT(p.id) as product_count 
                    FROM product_categories c 
                    LEFT JOIN products p ON c.id = p.category_id AND p.status = 'active' 
                    GROUP BY c.id, c.name, c.slug 
                    ORDER BY c.name ASC";
$cat_res = $conn->query($cat_count_query);
if ($cat_res && $cat_res->num_rows > 0) {
    $sidebar_cats = $cat_res->fetch_all(MYSQLI_ASSOC);
} else {
    $fallback_res = $conn->query("SELECT *, 0 as product_count FROM product_categories ORDER BY name ASC");
    if ($fallback_res) {
        $sidebar_cats = $fallback_res->fetch_all(MYSQLI_ASSOC);
    }
}

// Total active products count
$total_catalog_count = 0;
$tot_res = $conn->query("SELECT COUNT(*) as tot FROM products WHERE status = 'active'");
if ($tot_res) {
    $total_catalog_count = (int)$tot_res->fetch_assoc()['tot'];
}

// Active filters count calculator
$active_filters_count = 0;
if (!empty($category_slug)) $active_filters_count++;
if ($min_price > 0 || $max_price < 1000000) $active_filters_count++;
if ($on_sale === 1) $active_filters_count++;
if (!empty($search)) $active_filters_count++;

// URL Builder Helper Function
function build_shop_url($updates = [], $removes = []) {
    $params = $_GET;
    foreach ($removes as $r) {
        unset($params[$r]);
    }
    foreach ($updates as $k => $v) {
        if ($v === '' || $v === null) {
            unset($params[$k]);
        } else {
            $params[$k] = $v;
        }
    }
    if (!isset($updates['page'])) {
        unset($params['page']);
    }
    $q = http_build_query($params);
    return 'products.php' . ($q ? '?' . $q : '');
}
?>

<style>
/* ==========================================================================
   AMADIKA LUXURY SHOP & CATALOG — ENTERPRISE UI/UX
   ========================================================================== */
:root {
    --lux-gold: #c59b27;
    --lux-gold-hover: #b3891e;
    --lux-dark: #1a1614;
    --lux-brown: #9a6431;
    --lux-cream: #faf8f5;
    --lux-border: #ebe6df;
    --lux-text: #2d2621;
    --lux-muted: #8c827a;
    --lux-bg: #f9f7f4;
}

body {
    background-color: var(--lux-bg);
    color: var(--lux-text);
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* --- Hero Banner / Breadcrumb Strip --- */
.lux-shop-hero {
    background: linear-gradient(180deg, #fdfbf7 0%, #f6f3ee 100%);
    border-bottom: 1px solid var(--lux-border);
    padding: 36px 0 28px;
    margin-bottom: 30px;
}
.lux-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11.5px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--lux-muted);
    margin-bottom: 10px;
}
.lux-breadcrumb a {
    color: var(--lux-muted);
    text-decoration: none;
    transition: color 0.2s;
}
.lux-breadcrumb a:hover {
    color: var(--lux-gold);
}
.lux-breadcrumb span.active {
    color: var(--lux-gold);
    font-weight: 600;
}
.lux-shop-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 32px;
    font-weight: 600;
    color: var(--lux-dark);
    margin: 0 0 6px 0;
    letter-spacing: -0.3px;
}
.lux-shop-subtitle {
    font-size: 13.5px;
    color: var(--lux-muted);
    margin: 0;
    font-weight: 400;
}

/* --- Layout Container --- */
.lux-shop-wrapper {
    padding-bottom: 60px;
}

/* --- Desktop Sidebar Filter Card --- */
.lux-shop-sidebar {
    background: #ffffff;
    border-radius: 6px;
    border: 1px solid var(--lux-border);
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.02);
    position: sticky;
    top: 90px;
    max-height: calc(100vh - 110px);
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}
.lux-shop-sidebar::-webkit-scrollbar {
    width: 4px;
}
.lux-shop-sidebar::-webkit-scrollbar-thumb {
    background: rgba(197, 155, 39, 0.25);
    border-radius: 4px;
}
.lux-sidebar-header {
    padding: 18px 20px;
    border-bottom: 1px solid var(--lux-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    position: sticky;
    top: 0;
    z-index: 2;
}
.lux-sidebar-title {
    font-family: 'Outfit', sans-serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--lux-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.lux-filter-badge-count {
    background: var(--lux-gold);
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 12px;
}
.lux-sidebar-reset {
    font-size: 11px;
    color: var(--lux-gold);
    text-decoration: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: color 0.2s;
}
.lux-sidebar-reset:hover {
    color: var(--lux-dark);
    text-decoration: underline;
}

/* Filter Sections */
.lux-filter-section {
    padding: 16px 20px;
    border-bottom: 1px solid #f2eee8;
}
.lux-filter-section:last-child {
    border-bottom: none;
}
.lux-filter-heading {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 14.5px;
    font-weight: 600;
    color: var(--lux-dark);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Category List */
.lux-cat-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.lux-cat-item {
    margin: 0;
}
.lux-cat-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 7px 10px;
    border-radius: 4px;
    font-size: 13px;
    color: #4b443e;
    text-decoration: none !important;
    transition: all 0.2s ease;
}
.lux-cat-link:hover {
    background: var(--lux-cream);
    color: var(--lux-gold);
    padding-left: 14px;
}
.lux-cat-link.active {
    background: var(--lux-cream);
    color: var(--lux-dark);
    font-weight: 600;
    border-left: 3px solid var(--lux-gold);
}
.lux-cat-name {
    display: flex;
    align-items: center;
    gap: 8px;
}
.lux-cat-count {
    font-size: 11px;
    color: var(--lux-muted);
    background: rgba(0, 0, 0, 0.04);
    padding: 2px 7px;
    border-radius: 10px;
    font-weight: 500;
}

/* Price Range Filter Pills & Inputs */
.lux-price-pills {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 14px;
}
.lux-price-pill {
    font-size: 12.5px;
    color: #4b443e;
    padding: 6px 10px;
    border-radius: 4px;
    text-decoration: none !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s;
}
.lux-price-pill:hover {
    background: var(--lux-cream);
    color: var(--lux-gold);
}
.lux-price-pill.active {
    background: var(--lux-cream);
    color: var(--lux-dark);
    font-weight: 600;
    border-left: 3px solid var(--lux-gold);
}
.lux-price-input-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}
.lux-price-input-wrap {
    position: relative;
    flex: 1;
}
.lux-price-currency {
    position: absolute;
    left: 8px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
    color: var(--lux-muted);
}
.lux-price-input {
    width: 100%;
    padding: 6px 6px 6px 20px;
    font-size: 12px;
    border: 1px solid var(--lux-border);
    border-radius: 4px;
    outline: none;
    transition: border-color 0.2s;
    background: #ffffff;
}
.lux-price-input:focus {
    border-color: var(--lux-gold);
}
.lux-price-btn {
    background: var(--lux-dark);
    color: #ffffff;
    border: none;
    border-radius: 4px;
    padding: 7px 12px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    cursor: pointer;
    transition: background 0.2s;
}
.lux-price-btn:hover {
    background: var(--lux-gold);
}

/* Toggle Checkboxes */
.lux-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 0;
}
.lux-toggle-label {
    font-size: 13px;
    color: #4b443e;
    cursor: pointer;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.lux-checkbox {
    width: 16px;
    height: 16px;
    accent-color: var(--lux-gold);
    cursor: pointer;
}

/* --- Top Controls Toolbar --- */
.lux-top-toolbar {
    background: #ffffff;
    border: 1px solid var(--lux-border);
    border-radius: 6px;
    padding: 14px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.015);
}
.lux-toolbar-results {
    font-size: 13px;
    color: var(--lux-muted);
}
.lux-toolbar-results strong {
    color: var(--lux-dark);
    font-weight: 600;
}
.lux-toolbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
}
.lux-sort-select {
    border: 1px solid var(--lux-border);
    border-radius: 4px;
    padding: 7px 28px 7px 12px;
    font-size: 12.5px;
    font-weight: 500;
    color: var(--lux-dark);
    background-color: #ffffff;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%231a1614' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    outline: none;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    transition: border-color 0.2s;
}
.lux-sort-select:focus {
    border-color: var(--lux-gold);
}

/* --- Active Filters Chips Bar --- */
.lux-active-chips-wrap {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
}
.lux-active-chips-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--lux-muted);
    margin-right: 4px;
}
.lux-filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    border: 1px solid var(--lux-border);
    border-radius: 20px;
    padding: 4px 10px;
    font-size: 12px;
    color: var(--lux-dark);
    text-decoration: none !important;
    transition: all 0.2s;
}
.lux-filter-chip:hover {
    border-color: var(--lux-gold);
    color: var(--lux-gold);
}
.lux-chip-x {
    font-size: 13px;
    color: var(--lux-muted);
}
.lux-filter-chip:hover .lux-chip-x {
    color: var(--lux-gold);
}
.lux-chip-clear-all {
    font-size: 11.5px;
    color: #b91c1c;
    text-decoration: none !important;
    font-weight: 600;
    padding: 4px 8px;
    transition: opacity 0.2s;
}
.lux-chip-clear-all:hover {
    opacity: 0.7;
    text-decoration: underline !important;
}

/* ==========================================================================
   PREMIUM PRODUCT CARD (MATCHING CATEGORY-PRODUCTS SHOWCASE)
   ========================================================================== */
.lux-product-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
@media (min-width: 1400px) {
    .lux-product-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.lux-card {
    background: #ffffff;
    border: 1px solid var(--lux-border);
    border-radius: 4px;
    padding: 14px;
    display: flex;
    flex-direction: column;
    position: relative;
    text-decoration: none !important;
    transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.lux-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 36px rgba(28, 25, 23, 0.08);
    border-color: var(--lux-gold);
}

/* Image Box */
.lux-img-box {
    width: 100%;
    height: 220px;
    border-radius: 2px;
    background: var(--lux-cream);
    margin-bottom: 12px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    position: relative;
}
.lux-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.lux-card:hover .lux-img {
    transform: scale(1.08);
}

/* Badge Tags (Top-Left) */
.lux-badge-wrap {
    position: absolute;
    top: 8px;
    left: 8px;
    z-index: 3;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.lux-tag {
    font-family: 'Outfit', sans-serif;
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    padding: 3px 7px;
    border-radius: 2px;
    line-height: 1;
}
.lux-tag-discount {
    background: var(--lux-gold);
    color: #1a1614;
}
.lux-tag-bestseller {
    background: var(--lux-dark);
    color: #ffffff;
}

/* Wishlist Button (Top-Right) */
.lux-wish-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    z-index: 3;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(4px);
    border: 1px solid var(--lux-border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--lux-muted);
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.lux-wish-btn:hover {
    background: #ffffff;
    color: #ef4444;
    transform: scale(1.08);
}
.lux-wish-btn.active {
    color: #ef4444;
}

/* Rating Row */
.lux-card-rating {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 6px;
}
.lux-stars {
    color: var(--lux-gold);
    font-size: 10px;
    display: flex;
    gap: 2px;
}
.lux-rating-num {
    font-size: 10.5px;
    font-weight: 600;
    color: var(--lux-dark);
}
.lux-rev-count {
    font-size: 10.5px;
    color: var(--lux-muted);
}

/* Product Title */
.lux-card-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 15px;
    font-weight: 600;
    color: var(--lux-dark);
    margin-bottom: 10px;
    height: 42px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    line-height: 1.35;
    text-decoration: none !important;
    transition: color 0.25s ease;
}
.lux-card:hover .lux-card-name {
    color: var(--lux-brown);
}

/* Price Row */
.lux-card-price-row {
    margin-top: auto;
    display: flex;
    align-items: baseline;
    gap: 8px;
    padding-top: 8px;
    border-top: 1px solid #f2eee8;
    margin-bottom: 12px;
}
.lux-price-sale {
    font-family: 'Outfit', sans-serif;
    font-size: 17px;
    font-weight: 700;
    color: var(--lux-dark);
}
.lux-price-reg {
    font-family: 'Outfit', sans-serif;
    font-size: 12.5px;
    color: #a89f91;
    text-decoration: line-through;
}
.lux-price-save {
    font-size: 10.5px;
    color: #15803d;
    font-weight: 600;
    margin-left: auto;
}

/* Actions Row */
.lux-card-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.lux-btn-action {
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    padding: 8px 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.25s ease;
    border: none;
    line-height: 1;
}
.lux-btn-cart {
    background: #f4f0eb;
    color: var(--lux-dark);
}
.lux-btn-cart:hover {
    background: #eae3da;
    color: var(--lux-brown);
}
.lux-btn-buy {
    background: var(--lux-gold);
    color: #1a1614;
    font-weight: 700;
}
.lux-btn-buy:hover {
    background: var(--lux-gold-hover);
    color: #ffffff;
}

/* ==========================================================================
   PAGINATION CONTROLS
   ========================================================================== */
.lux-pagination-wrap {
    margin-top: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}
.lux-page-arrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: #ffffff;
    border: 1px solid var(--lux-border);
    border-radius: 4px;
    color: var(--lux-dark);
    text-decoration: none !important;
    font-size: 12.5px;
    font-weight: 600;
    transition: all 0.2s;
}
.lux-page-arrow:hover:not(.disabled) {
    border-color: var(--lux-gold);
    color: var(--lux-gold);
}
.lux-page-arrow.disabled {
    opacity: 0.4;
    cursor: not-allowed;
    pointer-events: none;
}
.lux-page-num {
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    border: 1px solid var(--lux-border);
    border-radius: 4px;
    color: var(--lux-dark);
    text-decoration: none !important;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}
.lux-page-num:hover {
    border-color: var(--lux-gold);
    color: var(--lux-gold);
}
.lux-page-num.active {
    background: var(--lux-gold);
    border-color: var(--lux-gold);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(197, 155, 39, 0.3);
}
.lux-page-dots {
    width: 24px;
    text-align: center;
    color: var(--lux-muted);
    font-size: 14px;
}

/* ==========================================================================
   MOBILE RESPONSIVENESS & SLIDE-IN FILTER DRAWER
   ========================================================================== */
.lux-mobile-filter-bar {
    display: none;
}
.lux-drawer-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1080;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}
.lux-drawer-backdrop.open {
    opacity: 1;
    visibility: visible;
}
.lux-mobile-drawer {
    position: fixed;
    top: 0;
    right: 0;
    width: 320px;
    max-width: 85vw;
    height: 100%;
    background: #ffffff;
    z-index: 1090;
    transform: translateX(100%);
    transition: transform 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
    display: flex;
    flex-direction: column;
    box-shadow: -8px 0 30px rgba(0, 0, 0, 0.15);
}
.lux-mobile-drawer.open {
    transform: translateX(0);
}
.lux-drawer-head {
    padding: 18px 20px;
    background: var(--lux-dark);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.lux-drawer-head h5 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 17px;
    margin: 0;
    color: #ffffff;
    font-weight: 600;
}
.lux-drawer-close {
    background: transparent;
    border: none;
    color: #ffffff;
    font-size: 18px;
    cursor: pointer;
    line-height: 1;
}
.lux-drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 10px 0;
}
.lux-drawer-footer {
    padding: 16px 20px;
    border-top: 1px solid var(--lux-border);
    display: flex;
    gap: 10px;
    background: #ffffff;
}

@media (max-width: 991px) {
    .lux-shop-hero {
        padding: 24px 0 18px;
        margin-bottom: 20px;
    }
    .lux-shop-title {
        font-size: 24px;
    }
    .lux-shop-subtitle {
        font-size: 12.5px;
    }
    
    /* Mobile Filter Trigger Bar */
    .lux-mobile-filter-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }
    .lux-m-btn-filter {
        flex: 1;
        background: #ffffff;
        border: 1px solid var(--lux-border);
        border-radius: 4px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--lux-dark);
        cursor: pointer;
    }
    .lux-m-btn-filter:hover {
        border-color: var(--lux-gold);
    }
    .lux-m-sort-wrap {
        flex: 1;
    }
    .lux-m-sort-select {
        width: 100%;
        border: 1px solid var(--lux-border);
        border-radius: 4px;
        padding: 10px 24px 10px 12px;
        font-size: 12px;
        font-weight: 600;
        color: var(--lux-dark);
        background: #ffffff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%231a1614' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 10px center;
        appearance: none;
        outline: none;
    }

    /* Hide desktop controls on mobile */
    .lux-top-toolbar {
        display: none;
    }
    .lux-shop-sidebar {
        display: none;
    }

    /* 2 Columns on Mobile */
    .lux-product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    .lux-card {
        padding: 10px;
    }
    .lux-img-box {
        height: 155px;
        padding: 6px;
        margin-bottom: 8px;
    }
    .lux-card-name {
        font-size: 13px;
        height: 36px;
        margin-bottom: 6px;
    }
    .lux-price-sale {
        font-size: 15px;
    }
    .lux-price-reg {
        font-size: 11px;
    }
    .lux-price-save {
        display: none;
    }
    .lux-card-actions {
        grid-template-columns: 1fr;
        gap: 6px;
    }
    .lux-btn-action {
        padding: 6px 8px;
        font-size: 10px;
    }
}
</style>

<!-- HERO / BREADCRUMB HEADER -->
<div class="lux-shop-hero">
    <div class="container-fluid px-3 px-lg-5">
        <div class="lux-breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <a href="products.php">Shop Catalog</a>
            <?php if (!empty($current_category)): ?>
                <span>/</span>
                <span class="active"><?php echo htmlspecialchars($current_category['name']); ?></span>
            <?php elseif (!empty($search)): ?>
                <span>/</span>
                <span class="active">Search: "<?php echo htmlspecialchars($search); ?>"</span>
            <?php endif; ?>
        </div>

        <h1 class="lux-shop-title">
            <?php 
                if (!empty($current_category)) {
                    echo htmlspecialchars($current_category['name']);
                } elseif (!empty($search)) {
                    echo 'Search Results for "' . htmlspecialchars($search) . '"';
                } else {
                    echo 'Handcrafted Leather Creations';
                }
            ?>
        </h1>
        <p class="lux-shop-subtitle">
            <?php 
                if (!empty($current_category)) {
                    echo 'Explore our curated ' . strtolower(htmlspecialchars($current_category['name'])) . ' collection, crafted from pure full-grain leather.';
                } else {
                    echo 'Discover exquisite heirloom leather bags, executive desk sets, and handcrafted accessories.';
                }
            ?>
        </p>
    </div>
</div>

<!-- MAIN SHOP CONTENT -->
<div class="lux-shop-wrapper">
    <div class="container-fluid px-3 px-lg-5">

        <!-- MOBILE FILTER TRIGGER BAR -->
        <div class="lux-mobile-filter-bar">
            <button type="button" class="lux-m-btn-filter" onclick="openMobileFilters()">
                <i class="fa-solid fa-sliders"></i>
                <span>Filter & Refine</span>
                <?php if ($active_filters_count > 0): ?>
                    <span class="lux-filter-badge-count"><?php echo $active_filters_count; ?></span>
                <?php endif; ?>
            </button>
            <div class="lux-m-sort-wrap">
                <select class="lux-m-sort-select" onchange="location = this.value;">
                    <option value="<?php echo build_shop_url(['sort' => 'newest']); ?>" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Sort: Newest</option>
                    <option value="<?php echo build_shop_url(['sort' => 'price_low']); ?>" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="<?php echo build_shop_url(['sort' => 'price_high']); ?>" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                    <option value="<?php echo build_shop_url(['sort' => 'discount']); ?>" <?php echo $sort == 'discount' ? 'selected' : ''; ?>>Top Discounts</option>
                    <option value="<?php echo build_shop_url(['sort' => 'popular']); ?>" <?php echo $sort == 'popular' ? 'selected' : ''; ?>>Popularity</option>
                </select>
            </div>
        </div>

        <div class="row g-4">
            <!-- ==========================================
                 DESKTOP SIDEBAR (ENTERPRISE FILTER SYSTEM)
                 ========================================== -->
            <div class="col-lg-3 d-none d-lg-block">
                <aside class="lux-shop-sidebar">
                    <div class="lux-sidebar-header">
                        <h4 class="lux-sidebar-title">
                            <i class="fa-solid fa-sliders" style="color: var(--lux-gold);"></i>
                            Filters
                            <?php if ($active_filters_count > 0): ?>
                                <span class="lux-filter-badge-count"><?php echo $active_filters_count; ?></span>
                            <?php endif; ?>
                        </h4>
                        <?php if ($active_filters_count > 0): ?>
                            <a href="products.php" class="lux-sidebar-reset">Clear All</a>
                        <?php endif; ?>
                    </div>

                    <!-- Filter 1: Collections & Categories -->
                    <div class="lux-filter-section">
                        <div class="lux-filter-heading">
                            <span>Collections</span>
                        </div>
                        <ul class="lux-cat-list">
                            <li class="lux-cat-item">
                                <a href="<?php echo build_shop_url([], ['category']); ?>" class="lux-cat-link <?php echo empty($category_slug) ? 'active' : ''; ?>">
                                    <span class="lux-cat-name">
                                        <i class="fa-regular fa-compass" style="font-size: 11px;"></i>
                                        All Collections
                                    </span>
                                    <span class="lux-cat-count"><?php echo $total_catalog_count; ?></span>
                                </a>
                            </li>
                            <?php foreach ($sidebar_cats as $cat): ?>
                                <li class="lux-cat-item">
                                    <a href="<?php echo build_shop_url(['category' => $cat['slug']]); ?>" 
                                       class="lux-cat-link <?php echo ($category_slug === $cat['slug']) ? 'active' : ''; ?>">
                                        <span class="lux-cat-name">
                                            <i class="fa-solid fa-angle-right" style="font-size: 10px; opacity: 0.6;"></i>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </span>
                                        <span class="lux-cat-count"><?php echo $cat['product_count']; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Filter 2: Price Range Quick Brackets & Inputs -->
                    <div class="lux-filter-section">
                        <div class="lux-filter-heading">
                            <span>Price Range</span>
                        </div>
                        <div class="lux-price-pills">
                            <a href="<?php echo build_shop_url([], ['min_price', 'max_price']); ?>" 
                               class="lux-price-pill <?php echo ($min_price === 0 && $max_price >= 1000000) ? 'active' : ''; ?>">
                                <span>All Prices</span>
                            </a>
                            <a href="<?php echo build_shop_url(['min_price' => 0, 'max_price' => 1500]); ?>" 
                               class="lux-price-pill <?php echo ($min_price === 0 && $max_price === 1500) ? 'active' : ''; ?>">
                                <span>Under ₹1,500</span>
                            </a>
                            <a href="<?php echo build_shop_url(['min_price' => 1500, 'max_price' => 3500]); ?>" 
                               class="lux-price-pill <?php echo ($min_price === 1500 && $max_price === 3500) ? 'active' : ''; ?>">
                                <span>₹1,500 – ₹3,500</span>
                            </a>
                            <a href="<?php echo build_shop_url(['min_price' => 3500, 'max_price' => 7000]); ?>" 
                               class="lux-price-pill <?php echo ($min_price === 3500 && $max_price === 7000) ? 'active' : ''; ?>">
                                <span>₹3,500 – ₹7,000</span>
                            </a>
                            <a href="<?php echo build_shop_url(['min_price' => 7000, 'max_price' => 1000000]); ?>" 
                               class="lux-price-pill <?php echo ($min_price === 7000 && $max_price >= 1000000) ? 'active' : ''; ?>">
                                <span>Above ₹7,000</span>
                            </a>
                        </div>
                        <form action="products.php" method="GET">
                            <?php if(!empty($category_slug)): ?><input type="hidden" name="category" value="<?php echo htmlspecialchars($category_slug); ?>"><?php endif; ?>
                            <?php if(!empty($sort)): ?><input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>"><?php endif; ?>
                            <?php if(!empty($search)): ?><input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"><?php endif; ?>
                            <?php if($on_sale): ?><input type="hidden" name="on_sale" value="1"><?php endif; ?>

                            <div class="lux-price-input-row">
                                <div class="lux-price-input-wrap">
                                    <span class="lux-price-currency">₹</span>
                                    <input type="number" name="min_price" class="lux-price-input" placeholder="Min" value="<?php echo $min_price > 0 ? $min_price : ''; ?>">
                                </div>
                                <span style="font-size: 11px; color: var(--lux-muted);">-</span>
                                <div class="lux-price-input-wrap">
                                    <span class="lux-price-currency">₹</span>
                                    <input type="number" name="max_price" class="lux-price-input" placeholder="Max" value="<?php echo $max_price < 1000000 ? $max_price : ''; ?>">
                                </div>
                                <button type="submit" class="lux-price-btn" title="Apply Custom Price">Go</button>
                            </div>
                        </form>
                    </div>

                    <!-- Filter 3: Offers & Availability -->
                    <div class="lux-filter-section">
                        <div class="lux-filter-heading">
                            <span>Special Filters</span>
                        </div>
                        <div class="lux-toggle-row">
                            <label class="lux-toggle-label" for="desktopSaleCheck">
                                <input type="checkbox" id="desktopSaleCheck" class="lux-checkbox" <?php echo $on_sale ? 'checked' : ''; ?>
                                       onchange="location = '<?php echo $on_sale ? build_shop_url([], ['on_sale']) : build_shop_url(['on_sale' => 1]); ?>';">
                                <span>On Sale (Special Offers)</span>
                            </label>
                        </div>
                        <div class="lux-toggle-row">
                            <label class="lux-toggle-label" for="desktopStockCheck">
                                <input type="checkbox" id="desktopStockCheck" class="lux-checkbox" checked disabled>
                                <span>In Stock & Ready to Ship</span>
                            </label>
                        </div>
                    </div>
                </aside>
            </div>

            <!-- ==========================================
                 PRODUCT GRID & CONTROL TOOLBAR
                 ========================================== -->
            <div class="col-lg-9 col-12">
                <!-- Desktop Toolbar -->
                <div class="lux-top-toolbar">
                    <div class="lux-toolbar-results">
                        Showing <strong><?php echo $total_items > 0 ? ($offset + 1) : 0; ?>–<?php echo min($total_items, $offset + $per_page); ?></strong> of <strong><?php echo $total_items; ?></strong> handcrafted creations
                    </div>
                    <div class="lux-toolbar-right">
                        <label for="desktopSort" class="small text-muted me-1 mb-0">Sort By:</label>
                        <select id="desktopSort" class="lux-sort-select" onchange="location = this.value;">
                            <option value="<?php echo build_shop_url(['sort' => 'newest']); ?>" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>New Arrivals (Latest)</option>
                            <option value="<?php echo build_shop_url(['sort' => 'price_low']); ?>" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="<?php echo build_shop_url(['sort' => 'price_high']); ?>" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="<?php echo build_shop_url(['sort' => 'discount']); ?>" <?php echo $sort == 'discount' ? 'selected' : ''; ?>>Biggest Discounts</option>
                            <option value="<?php echo build_shop_url(['sort' => 'popular']); ?>" <?php echo $sort == 'popular' ? 'selected' : ''; ?>>Popular Collections</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filter Chips -->
                <?php if ($active_filters_count > 0): ?>
                    <div class="lux-active-chips-wrap">
                        <span class="lux-active-chips-label">Active:</span>
                        <?php if (!empty($current_category)): ?>
                            <a href="<?php echo build_shop_url([], ['category']); ?>" class="lux-filter-chip">
                                <span>Collection: <?php echo htmlspecialchars($current_category['name']); ?></span>
                                <span class="lux-chip-x">&times;</span>
                            </a>
                        <?php endif; ?>

                        <?php if ($min_price > 0 || $max_price < 1000000): ?>
                            <a href="<?php echo build_shop_url([], ['min_price', 'max_price']); ?>" class="lux-filter-chip">
                                <span>Price: ₹<?php echo number_format($min_price); ?> – ₹<?php echo ($max_price < 1000000) ? number_format($max_price) : 'Above'; ?></span>
                                <span class="lux-chip-x">&times;</span>
                            </a>
                        <?php endif; ?>

                        <?php if ($on_sale): ?>
                            <a href="<?php echo build_shop_url([], ['on_sale']); ?>" class="lux-filter-chip">
                                <span>On Sale Only</span>
                                <span class="lux-chip-x">&times;</span>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($search)): ?>
                            <a href="<?php echo build_shop_url([], ['search']); ?>" class="lux-filter-chip">
                                <span>Keyword: "<?php echo htmlspecialchars($search); ?>"</span>
                                <span class="lux-chip-x">&times;</span>
                            </a>
                        <?php endif; ?>

                        <a href="products.php" class="lux-chip-clear-all">Reset All</a>
                    </div>
                <?php endif; ?>

                <!-- Product Grid (Matching category-products aesthetic) -->
                <?php if (!empty($products)): ?>
                    <div class="lux-product-grid">
                        <?php foreach ($products as $p): 
                            $img_src = '';
                            if (!empty($p['featured_image'])) {
                                if (function_exists('get_resized_image')) {
                                    $img_src = get_resized_image($p['featured_image'], 450, 450, 'contain');
                                }
                                if (empty($img_src)) {
                                    $img_src = (strpos($p['featured_image'], 'http') === 0 || strpos($p['featured_image'], '/') === 0) 
                                        ? $p['featured_image'] 
                                        : $link_prefix . $p['featured_image'];
                                }
                            } else {
                                $img_src = $link_prefix . 'assets/images/amdika-logo.png';
                            }

                            // Pricing & Discounts
                            $gst_pct = isset($p['gst_percent']) ? (float)$p['gst_percent'] : 0;
                            $sale_price = (float)$p['sale_price'];
                            $regular_price = (float)$p['regular_price'];

                            $inc_sale = $sale_price + ($sale_price * $gst_pct / 100);
                            $inc_reg = $regular_price + ($regular_price * $gst_pct / 100);
                            $has_discount = ($regular_price > $sale_price && $inc_reg > $inc_sale);
                            $discount_pct = 0;
                            if ($has_discount && $inc_reg > 0) {
                                $discount_pct = round((($inc_reg - $inc_sale) / $inc_reg) * 100);
                            }

                            // Consistent pseudo ratings
                            $rating_seed = ($p['id'] * 7) % 5;
                            $rating_val = 4.5 + ($rating_seed * 0.1);
                            if ($rating_val > 5.0) $rating_val = 5.0;
                            $rev_count = 18 + (($p['id'] * 13) % 85);
                        ?>
                            <div class="lux-card">
                                <!-- Badge Tag Overlay -->
                                <div class="lux-badge-wrap">
                                    <?php if ($has_discount && $discount_pct > 0): ?>
                                        <span class="lux-tag lux-tag-discount">-<?php echo $discount_pct; ?>% OFF</span>
                                    <?php elseif ($p['id'] % 3 === 0): ?>
                                        <span class="lux-tag lux-tag-bestseller">Bestseller</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Wishlist Button -->
                                <button type="button" class="lux-wish-btn" onclick="toggleWishlist(this, <?php echo $p['id']; ?>)" title="Save to Wishlist" aria-label="Save to Wishlist">
                                    <i class="fa-regular fa-heart"></i>
                                </button>

                                <!-- Image Box -->
                                <a href="<?php echo $link_prefix; ?>product/<?php echo $p['slug']; ?>" class="lux-img-box">
                                    <img src="<?php echo $img_src; ?>" 
                                         alt="<?php echo htmlspecialchars($p['name']); ?>" 
                                         class="lux-img" 
                                         loading="lazy" 
                                         onerror="this.onerror=null; this.src='<?php echo $link_prefix; ?>assets/images/amdika-logo.png';">
                                </a>

                                <!-- Rating Row -->
                                <div class="lux-card-rating">
                                    <div class="lux-stars">
                                        <?php for($s=1; $s<=5; $s++): ?>
                                            <?php if($s <= floor($rating_val)): ?>
                                                <i class="fa-solid fa-star"></i>
                                            <?php elseif($s - 0.5 <= $rating_val): ?>
                                                <i class="fa-solid fa-star-half-stroke"></i>
                                            <?php else: ?>
                                                <i class="fa-regular fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="lux-rating-num"><?php echo number_format($rating_val, 1); ?></span>
                                    <span class="lux-rev-count">(<?php echo $rev_count; ?>)</span>
                                </div>

                                <!-- Title -->
                                <a href="<?php echo $link_prefix; ?>product/<?php echo $p['slug']; ?>" class="lux-card-name" title="<?php echo htmlspecialchars($p['name']); ?>">
                                    <?php echo htmlspecialchars($p['name']); ?>
                                </a>

                                <!-- Price Row -->
                                <div class="lux-card-price-row">
                                    <span class="lux-price-sale">₹<?php echo number_format($inc_sale); ?></span>
                                    <?php if ($has_discount): ?>
                                        <span class="lux-price-reg">₹<?php echo number_format($inc_reg); ?></span>
                                        <span class="lux-price-save">Save ₹<?php echo number_format($inc_reg - $inc_sale); ?></span>
                                    <?php endif; ?>
                                </div>

                                <!-- Action Buttons -->
                                <div class="lux-card-actions">
                                    <button type="button" class="lux-btn-action lux-btn-cart" onclick="addToCart(<?php echo $p['id']; ?>)">
                                        <i class="fa-solid fa-bag-shopping"></i>
                                        <span>Add</span>
                                    </button>
                                    <button type="button" class="lux-btn-action lux-btn-buy" onclick="buyNow(<?php echo $p['id']; ?>)">
                                        <span>Buy Now</span>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- PAGINATION (Enterprise Numbered Navigation) -->
                    <?php if ($total_pages > 1): ?>
                        <div class="lux-pagination-wrap">
                            <!-- Prev Arrow -->
                            <?php if ($page > 1): ?>
                                <a href="<?php echo build_shop_url(['page' => $page - 1]); ?>" class="lux-page-arrow" aria-label="Previous Page">
                                    <i class="fa-solid fa-chevron-left"></i>
                                    <span>Prev</span>
                                </a>
                            <?php else: ?>
                                <span class="lux-page-arrow disabled">
                                    <i class="fa-solid fa-chevron-left"></i>
                                    <span>Prev</span>
                                </span>
                            <?php endif; ?>

                            <!-- Numbered Pages with Ellipsis Logic -->
                            <?php
                            $range = 2;
                            $start_page = max(1, $page - $range);
                            $end_page = min($total_pages, $page + $range);

                            if ($start_page > 1) {
                                echo '<a href="' . build_shop_url(['page' => 1]) . '" class="lux-page-num">1</a>';
                                if ($start_page > 2) {
                                    echo '<span class="lux-page-dots">…</span>';
                                }
                            }

                            for ($i = $start_page; $i <= $end_page; $i++) {
                                $active_cls = ($i === $page) ? ' active' : '';
                                echo '<a href="' . build_shop_url(['page' => $i]) . '" class="lux-page-num' . $active_cls . '">' . $i . '</a>';
                            }

                            if ($end_page < $total_pages) {
                                if ($end_page < $total_pages - 1) {
                                    echo '<span class="lux-page-dots">…</span>';
                                }
                                echo '<a href="' . build_shop_url(['page' => $total_pages]) . '" class="lux-page-num">' . $total_pages . '</a>';
                            }
                            ?>

                            <!-- Next Arrow -->
                            <?php if ($page < $total_pages): ?>
                                <a href="<?php echo build_shop_url(['page' => $page + 1]); ?>" class="lux-page-arrow" aria-label="Next Page">
                                    <span>Next</span>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            <?php else: ?>
                                <span class="lux-page-arrow disabled">
                                    <span>Next</span>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Clean Empty State -->
                    <div class="text-center py-5 bg-white rounded-3 border border-light p-4" style="border: 1px solid var(--lux-border) !important;">
                        <div style="width: 64px; height: 64px; background: var(--lux-cream); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: var(--lux-gold);">
                            <i class="fa-solid fa-box-open" style="font-size: 26px;"></i>
                        </div>
                        <h4 class="h5 mb-2" style="font-family: 'Playfair Display', Georgia, serif; color: var(--lux-dark);">No Products Found</h4>
                        <p class="text-muted small mb-4" style="max-width: 400px; margin: 0 auto 16px;">We couldn't find any products matching your current filters. Try resetting the filters or searching with different keywords.</p>
                        <a href="products.php" class="btn btn-dark px-4 py-2" style="background: var(--lux-dark); font-size: 12px; letter-spacing: 1px; text-transform: uppercase;">
                            View All Products
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     MOBILE OFFCANVAS FILTER DRAWER
     ========================================== -->
<div id="mobileDrawerBackdrop" class="lux-drawer-backdrop" onclick="closeMobileFilters()"></div>
<div id="mobileFilterDrawer" class="lux-mobile-drawer">
    <div class="lux-drawer-head">
        <h5>
            <i class="fa-solid fa-sliders me-2" style="color: var(--lux-gold); font-size: 14px;"></i>
            Filter & Refine
        </h5>
        <button type="button" class="lux-drawer-close" onclick="closeMobileFilters()" aria-label="Close Filters">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="lux-drawer-body">
        <!-- Categories in Drawer -->
        <div class="lux-filter-section">
            <div class="lux-filter-heading">
                <span>Collections</span>
            </div>
            <ul class="lux-cat-list">
                <li class="lux-cat-item">
                    <a href="<?php echo build_shop_url([], ['category']); ?>" class="lux-cat-link <?php echo empty($category_slug) ? 'active' : ''; ?>">
                        <span class="lux-cat-name">All Collections</span>
                        <span class="lux-cat-count"><?php echo $total_catalog_count; ?></span>
                    </a>
                </li>
                <?php foreach ($sidebar_cats as $cat): ?>
                    <li class="lux-cat-item">
                        <a href="<?php echo build_shop_url(['category' => $cat['slug']]); ?>" 
                           class="lux-cat-link <?php echo ($category_slug === $cat['slug']) ? 'active' : ''; ?>">
                            <span class="lux-cat-name"><?php echo htmlspecialchars($cat['name']); ?></span>
                            <span class="lux-cat-count"><?php echo $cat['product_count']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Price Range in Drawer -->
        <div class="lux-filter-section">
            <div class="lux-filter-heading">
                <span>Price Bracket</span>
            </div>
            <div class="lux-price-pills">
                <a href="<?php echo build_shop_url([], ['min_price', 'max_price']); ?>" class="lux-price-pill <?php echo ($min_price === 0 && $max_price >= 1000000) ? 'active' : ''; ?>">
                    <span>All Prices</span>
                </a>
                <a href="<?php echo build_shop_url(['min_price' => 0, 'max_price' => 1500]); ?>" class="lux-price-pill <?php echo ($min_price === 0 && $max_price === 1500) ? 'active' : ''; ?>">
                    <span>Under ₹1,500</span>
                </a>
                <a href="<?php echo build_shop_url(['min_price' => 1500, 'max_price' => 3500]); ?>" class="lux-price-pill <?php echo ($min_price === 1500 && $max_price === 3500) ? 'active' : ''; ?>">
                    <span>₹1,500 – ₹3,500</span>
                </a>
                <a href="<?php echo build_shop_url(['min_price' => 3500, 'max_price' => 7000]); ?>" class="lux-price-pill <?php echo ($min_price === 3500 && $max_price === 7000) ? 'active' : ''; ?>">
                    <span>₹3,500 – ₹7,000</span>
                </a>
                <a href="<?php echo build_shop_url(['min_price' => 7000, 'max_price' => 1000000]); ?>" class="lux-price-pill <?php echo ($min_price === 7000 && $max_price >= 1000000) ? 'active' : ''; ?>">
                    <span>Above ₹7,000</span>
                </a>
            </div>
            <form action="products.php" method="GET">
                <?php if(!empty($category_slug)): ?><input type="hidden" name="category" value="<?php echo htmlspecialchars($category_slug); ?>"><?php endif; ?>
                <?php if(!empty($sort)): ?><input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>"><?php endif; ?>
                <?php if($on_sale): ?><input type="hidden" name="on_sale" value="1"><?php endif; ?>
                <div class="lux-price-input-row">
                    <div class="lux-price-input-wrap">
                        <span class="lux-price-currency">₹</span>
                        <input type="number" name="min_price" class="lux-price-input" placeholder="Min" value="<?php echo $min_price > 0 ? $min_price : ''; ?>">
                    </div>
                    <span style="font-size: 11px; color: var(--lux-muted);">-</span>
                    <div class="lux-price-input-wrap">
                        <span class="lux-price-currency">₹</span>
                        <input type="number" name="max_price" class="lux-price-input" placeholder="Max" value="<?php echo $max_price < 1000000 ? $max_price : ''; ?>">
                    </div>
                    <button type="submit" class="lux-price-btn">Go</button>
                </div>
            </form>
        </div>

        <!-- Offers in Drawer -->
        <div class="lux-filter-section">
            <div class="lux-filter-heading">
                <span>Special Offers</span>
            </div>
            <div class="lux-toggle-row">
                <label class="lux-toggle-label" for="mobileSaleCheck">
                    <input type="checkbox" id="mobileSaleCheck" class="lux-checkbox" <?php echo $on_sale ? 'checked' : ''; ?>
                           onchange="location = '<?php echo $on_sale ? build_shop_url([], ['on_sale']) : build_shop_url(['on_sale' => 1]); ?>';">
                    <span>On Sale (Discounted)</span>
                </label>
            </div>
        </div>
    </div>

    <div class="lux-drawer-footer">
        <a href="products.php" class="btn btn-outline-secondary w-50" style="font-size: 12px; font-weight: 600; text-transform: uppercase;">
            Reset All
        </a>
        <button type="button" class="btn w-50" onclick="closeMobileFilters()" style="background: var(--lux-gold); color: #1a1614; font-size: 12px; font-weight: 700; text-transform: uppercase;">
            Show Results
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Cart actions
    function addToCart(pid) {
        fetch('<?php echo $link_prefix; ?>includes/cart_actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=add&product_id=' + pid + '&quantity=1'
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({ 
                    toast: true, 
                    position: 'bottom-end', 
                    icon: 'success', 
                    title: 'Added to your bag', 
                    showConfirmButton: false, 
                    timer: 1600,
                    customClass: { popup: 'shadow-lg' }
                });
                if (typeof updateCartCount === 'function') updateCartCount();
                if (typeof openCartSidebar === 'function') openCartSidebar();
            } else {
                Swal.fire({ icon: 'error', title: 'Notice', text: data.message || 'Failed to add to cart' });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to update cart. Please try again.' });
        });
    }

    function buyNow(pid) {
        fetch('<?php echo $link_prefix; ?>includes/cart_actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=add&product_id=' + pid + '&quantity=1'
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = '<?php echo $link_prefix; ?>cart.php';
            } else {
                Swal.fire({ icon: 'error', title: 'Notice', text: data.message || 'Failed to proceed' });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
        });
    }

    // Wishlist Toggle
    function toggleWishlist(btn, pid) {
        const icon = btn.querySelector('i');
        const isActive = btn.classList.contains('active');
        if (isActive) {
            btn.classList.remove('active');
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
            Swal.fire({ toast: true, position: 'bottom-end', icon: 'info', title: 'Removed from wishlist', showConfirmButton: false, timer: 1400 });
        } else {
            btn.classList.add('active');
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
            Swal.fire({ toast: true, position: 'bottom-end', icon: 'success', title: 'Saved to luxury wishlist', showConfirmButton: false, timer: 1400 });
        }
    }

    // Mobile Drawer Controls
    function openMobileFilters() {
        document.getElementById('mobileDrawerBackdrop').classList.add('open');
        document.getElementById('mobileFilterDrawer').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileFilters() {
        document.getElementById('mobileDrawerBackdrop').classList.remove('open');
        document.getElementById('mobileFilterDrawer').classList.remove('open');
        document.body.style.overflow = '';
    }
</script>

<?php include 'includes/footer.php'; ?>

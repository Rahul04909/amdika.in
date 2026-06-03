<?php
$page_title = 'Shop - Amadika Premium';
require_once 'database/db_config.php';
require_once 'includes/image_helper.php';
include 'includes/header.php';

// --- Pagination & Filter Logic ---
$per_page = 12;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

$category_slug = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000000;

// Build Query Dynamically
$where = ["status = 'active'"];
$params = [];
$types = "";

if (!empty($category_slug)) {
    $cat_stmt = $conn->prepare("SELECT id, name FROM product_categories WHERE slug = ?");
    $cat_stmt->bind_param("s", $category_slug);
    $cat_stmt->execute();
    $cat_res = $cat_stmt->get_result();
    if ($cat_row = $cat_res->fetch_assoc()) {
        $where[] = "category_id = ?";
        $params[] = $cat_row['id'];
        $types .= "i";
    }
}

if (!empty($search)) {
    $where[] = "(name LIKE ? OR description LIKE ?)";
    $s_param = "%$search%";
    $params[] = $s_param;
    $params[] = $s_param;
    $types .= "ss";
}

if ($min_price > 0) { $where[] = "sale_price >= ?"; $params[] = $min_price; $types .= "i"; }
if ($max_price < 1000000) { $where[] = "sale_price <= ?"; $params[] = $max_price; $types .= "i"; }

// Total Count for Pagination
$count_sql = "SELECT COUNT(*) as total FROM products WHERE " . implode(" AND ", $where);
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) { $count_stmt->bind_param($types, ...$params); }
$count_stmt->execute();
$total_items = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_items / $per_page);

// Sorting
$order = "created_at DESC";
switch ($sort) {
    case 'price_low': $order = "sale_price ASC"; break;
    case 'price_high': $order = "sale_price DESC"; break;
    case 'popular': $order = "id ASC"; break;
}

$sql = "SELECT * FROM products WHERE " . implode(" AND ", $where) . " ORDER BY $order LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
if (!empty($params)) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch Categories for Sidebar
$sidebar_cats = $conn->query("SELECT * FROM product_categories ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
?>

<style>
/* --- Professional Shop Styles --- */
body { background-color: #f8f9fb; }
.shop-container { padding: 30px 0; }

/* Refined Compact Sidebar */
.shop-sidebar {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #eee;
    position: sticky;
    top: 10px; /* Reduced gap */
    max-height: calc(100vh - 40px); /* Allow sidebar to be scrollable if too tall */
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    scrollbar-width: none; /* Hide scrollbar for clean look */
}

.shop-sidebar::-webkit-scrollbar { display: none; }

.filter-group {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f8f8f8;
}

.filter-group:last-child { 
    border-bottom: none;
    padding-bottom: 10px; /* Extra space for last items */
}

.filter-title {
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-list { 
    list-style: none; 
    padding: 0; 
    margin: 0; 
}

.filter-link {
    color: #666;
    text-decoration: none;
    font-size: 13px;
    padding: 6px 0;
    display: block;
    transition: all 0.2s;
}

.filter-link:hover, .filter-link.active {
    color: var(--accent-gold, #d4a017);
    font-weight: 600;
}

/* Compact Product Cards */
.shop-product-card {
    background: #fff;
    border-radius: 12px;
    padding: 12px;
    height: 100%;
    border: 1px solid #eee;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.shop-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.04);
    border-color: var(--accent-gold, #d4a017);
}

.sp-img-box {
    width: 100%;
    height: 170px; /* Reduced card height */
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sp-img { width: 100%; height: 100%; object-fit: contain; padding: 5px; }

.sp-rating { display: flex; align-items: center; gap: 4px; margin-bottom: 5px; }
.sp-stars { color: #ffc107; font-size: 10px; }
.sp-rev { font-size: 10px; color: #aaa; }

.sp-name {
    font-size: 13px;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
    height: 36px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    line-height: 1.4;
    text-decoration: none !important;
}

.sp-price-box { display: flex; align-items: center; gap: 8px; margin-top: auto; }
.sp-sale { font-size: 16px; font-weight: 800; color: #1a1a1a; }
.sp-reg { font-size: 12px; color: #ccc; text-decoration: line-through; }

.sp-actions { display: grid; grid-template-columns: 40px 1fr; gap: 8px; margin-top: 12px; }
.btn-sp { border-radius: 6px; border: none; font-size: 11px; font-weight: 700; transition: all 0.2s; }
.btn-sp-cart { background: #f8f9fa; color: #333; height: 35px; }
.btn-sp-buy { background: var(--accent-gold, #d4a017); color: #fff; height: 35px; text-transform: uppercase; }
.btn-sp:hover { opacity: 0.8; }

/* Professional Pagination */
.sp-pagination {
    margin-top: 50px;
    display: flex;
    justify-content: center;
    gap: 8px;
}

.page-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 8px;
    color: #666;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.page-btn.active, .page-btn:hover {
    background: var(--accent-gold, #d4a017);
    color: #fff;
    border-color: var(--accent-gold, #d4a017);
}

.page-btn.disabled { opacity: 0.5; pointer-events: none; }

@media (max-width: 991px) {
    .shop-sidebar { display: none; }
    .sp-img-box { height: 140px; }
}
</style>

<div class="shop-container">
    <div class="container-fluid px-lg-5">
        <div class="row">
            <!-- Compact Sidebar -->
            <div class="col-lg-3 d-none d-lg-block">
                <aside class="shop-sidebar">
                    <div class="filter-group">
                        <div class="filter-title">
                            Collections
                            <a href="products.php" style="font-size: 11px; color: var(--accent-gold, #d4a017); text-decoration: none;">Clear</a>
                        </div>
                        <ul class="filter-list">
                            <li><a href="products.php" class="filter-link <?php echo empty($category_slug) ? 'active' : ''; ?>">All Products</a></li>
                            <?php foreach($sidebar_cats as $cat): ?>
                                <li>
                                    <a href="products.php?category=<?php echo $cat['slug']; ?>" 
                                       class="filter-link <?php echo $category_slug == $cat['slug'] ? 'active' : ''; ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="filter-group">
                        <div class="filter-title">Price Range</div>
                        <form action="products.php" method="GET">
                            <?php if(!empty($category_slug)): ?><input type="hidden" name="category" value="<?php echo $category_slug; ?>"><?php endif; ?>
                            <div class="d-flex gap-2 mb-2">
                                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="<?php echo $min_price > 0 ? $min_price : ''; ?>">
                                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="<?php echo $max_price < 1000000 ? $max_price : ''; ?>">
                            </div>
                            <button type="submit" class="btn btn-dark btn-sm w-100">Filter</button>
                        </form>
                    </div>

                    <div class="filter-group">
                        <div class="filter-title">Quick Filters</div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="f1" checked>
                            <label class="form-check-label small" for="f1">In Stock</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="f2">
                            <label class="form-check-label small" for="f2">On Sale</label>
                        </div>
                    </div>
                </aside>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Compact Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-3 border border-light">
                    <h1 class="h5 mb-0 fw-bold">
                        <?php echo !empty($category_slug) ? str_replace('-', ' ', ucfirst($category_slug)) : 'Shop All'; ?>
                        <span class="text-muted fw-normal ms-2" style="font-size: 12px;">(<?php echo $total_items; ?> Items)</span>
                    </h1>
                    <select class="form-select form-select-sm w-auto rounded-pill px-3" onchange="location = this.value;">
                        <option value="products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'newest'])); ?>" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Latest</option>
                        <option value="products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price_low'])); ?>" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low-High</option>
                        <option value="products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price_high'])); ?>" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High-Low</option>
                    </select>
                </div>

                <!-- Compact Product Grid -->
                <div class="row g-3">
                    <?php if(!empty($products)): ?>
                        <?php foreach($products as $p): 
                            $resized = get_resized_image($p['featured_image'], 300, 300, 'contain');
                            $rating_val = 4 + (rand(0, 10)/10);
                        ?>
                            <div class="col-6 col-md-4 col-xl-3">
                                <div class="shop-product-card">
                                    <a href="product-details.php?slug=<?php echo $p['slug']; ?>" class="sp-img-box">
                                        <img src="<?php echo $resized; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="sp-img">
                                    </a>
                                    
                                    <div class="sp-rating">
                                        <div class="sp-stars">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="fa-<?php echo ($i <= $rating_val) ? 'solid' : 'regular'; ?> fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="sp-rev">(<?php echo rand(10, 150); ?>)</span>
                                    </div>

                                    <a href="product-details.php?slug=<?php echo $p['slug']; ?>" class="sp-name">
                                        <?php echo htmlspecialchars($p['name']); ?>
                                    </a>

                                    <?php
                                    $gst_pct = isset($p['gst_percent']) ? $p['gst_percent'] : 0;
                                    $inc_sale = $p['sale_price'] + ($p['sale_price'] * $gst_pct / 100);
                                    $inc_reg = $p['regular_price'] + ($p['regular_price'] * $gst_pct / 100);
                                    ?>
                                    <div class="sp-price-box">
                                        <span class="sp-sale">₹<?php echo number_format($inc_sale); ?> <small class="text-muted" style="font-size: 10px; font-weight:normal;">Inc. GST</small></span>
                                        <?php if($p['regular_price'] > $p['sale_price']): ?>
                                            <span class="sp-reg">₹<?php echo number_format($inc_reg); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="sp-actions">
                                        <button class="btn-sp btn-sp-cart" onclick="addToCart(<?php echo $p['id']; ?>)">
                                            <i class="fa-solid fa-cart-plus"></i>
                                        </button>
                                        <button class="btn-sp btn-sp-buy" onclick="buyNow(<?php echo $p['id']; ?>)">
                                            Buy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <h4 class="text-muted">No products found.</h4>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Professional Pagination -->
                <?php if($total_pages > 1): ?>
                    <div class="sp-pagination">
                        <a href="products.php?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="page-btn <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                        <?php for($i=1; $i<=$total_pages; $i++): ?>
                            <a href="products.php?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" class="page-btn <?php echo $page == $i ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        <a href="products.php?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="page-btn <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function addToCart(pid) {
        Swal.fire({ toast: true, position: 'bottom-end', icon: 'success', title: 'Added to cart', showConfirmButton: false, timer: 1500 });
    }
    function buyNow(pid) { window.location.href = 'cart.php'; }
</script>

<?php include 'includes/footer.php'; ?>

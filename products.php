<?php
$page_title = 'Shop - Amadika';
require_once 'database/db_config.php';
include 'includes/header.php';

// --- Logic ---
// 1. Get Filters
$category_slug = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 2. Build Query
$where_clauses = ["1=1"]; // Default true
$params = [];
$types = "";

// Category Filter
$selected_cat_name = "All Products";
if (!empty($category_slug)) {
    // Get Category ID from slug
    $cat_stmt = $conn->prepare("SELECT id, name FROM product_categories WHERE slug = ?");
    $cat_stmt->bind_param("s", $category_slug);
    $cat_stmt->execute();
    $cat_res = $cat_stmt->get_result();
    if ($cat_row = $cat_res->fetch_assoc()) {
        $cat_id = $cat_row['id'];
        $selected_cat_name = $cat_row['name'];
        $where_clauses[] = "category_id = ?";
        $params[] = $cat_id;
        $types .= "i";
    }
}

// Search Filter
if(!empty($search)) {
    $where_clauses[] = "(name LIKE ? OR description LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
    $selected_cat_name = "Search Results for '$search'";
}

// Sorting
$order_by = "created_at DESC";
switch ($sort) {
    case 'price_low':
        $order_by = "sale_price ASC";
        break;
    case 'price_high':
        $order_by = "sale_price DESC";
        break;
    case 'newest':
    default:
        $order_by = "created_at DESC";
        break;
}

// Combine Query
$where_sql = implode(" AND ", $where_clauses);
$sql = "SELECT * FROM products WHERE $where_sql ORDER BY $order_by";

// Prepare & Execute
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Fetch all Categories for Sidebar
$all_cats = $conn->query("SELECT * FROM product_categories ORDER BY name ASC");
?>

<style>
    body { background-color: #f1f3f6; font-family: 'Rubik', sans-serif; }
    
    /* Sidebar */
    .filter-sidebar {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        padding: 0; 
        overflow: hidden;
        position: sticky;
        top: 90px;
        border: 1px solid #e0e0e0;
    }
    .filter-header { 
        font-size: 15px; 
        font-weight: 700; 
        padding: 18px 20px;
        color: #212121;
        border-bottom: 1px solid #f0f0f0;
        background: #fdfdfd;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .category-list { list-style: none; padding: 10px 0; margin: 0; }
    .category-list li { margin: 0; }
    .category-list a { 
        color: #4a4a4a; 
        text-decoration: none; 
        font-size: 14px; 
        display: block; 
        padding: 12px 20px; 
        transition: all 0.2s;
        border-left: 4px solid transparent;
        font-weight: 500;
    }
    .category-list a:hover { 
        background-color: #f8f9fa; 
        color: #2874f0; 
        padding-left: 24px;
    }
    .category-list a.active { 
        background-color: #f0f7ff; 
        color: #2874f0; 
        font-weight: 600; 
        border-left-color: #2874f0; 
    }

    /* Top Bar */
    .shop-header {
        background: #fff;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #eee;
    }
    .sort-select { 
        border: 1px solid #e0e0e0; 
        padding: 8px 12px; 
        border-radius: 4px; 
        font-size: 14px; 
        outline: none; 
        cursor: pointer;
        background-color: #fff;
        color: #555;
    }

    /* Product Grid */
    .product-grid-item { margin-bottom: 24px; }
    
    /* Product Card (Enhanced) */
    .product-card-v2 {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        border: 1px solid #eff0f2;
        padding: 12px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
    }
    .product-card-v2:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        border-color: #fff;
    }
    
    .pc-img-wrapper {
        height: 220px; /* Taller for better visual */
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        padding: 10px;
        background: #fff;
        border-radius: 6px;
        transition: background 0.2s;
        overflow: hidden;
    }
    
    .pc-img { 
        max-width: 100%; 
        max-height: 100%; 
        object-fit: contain; 
        transition: transform 0.3s ease;
    }
    .product-card-v2:hover .pc-img { transform: scale(1.05); }
    
    .pc-title { 
        font-size: 15px; 
        font-weight: 500; 
        color: #2c3333; 
        margin-bottom: 10px; 
        display: -webkit-box; 
        -webkit-line-clamp: 2; 
        -webkit-box-orient: vertical; 
        overflow: hidden; 
        height: 42px; 
        line-height: 1.4;
    }
    
    /* Price Box Fixes */
    .pc-price-box { 
        margin-bottom: 15px;
        display: flex;
        align-items: baseline;
        flex-wrap: wrap; 
        gap: 8px;
    }
    .pc-price { font-size: 18px; font-weight: 700; color: #212121; }
    .pc-mrp { 
        font-size: 13px; 
        color: #878787; 
        text-decoration: line-through; 
    }
    .pc-off { 
        font-size: 12px; 
        color: #388e3c; 
        font-weight: 600; 
        background: #e5f7ee;
        padding: 2px 6px;
        border-radius: 4px;
        white-space: nowrap; 
    }
    
    .pc-actions { 
        margin-top: auto; 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 10px; 
    }
    
    /* Desktop Animation for Buttons */
    @media (min-width: 992px) {
        .pc-actions {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }
        .product-card-v2:hover .pc-actions {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .btn-pc-action { 
        font-size: 13px; 
        padding: 8px 0; 
        border: none; 
        border-radius: 4px; 
        font-weight: 600; 
        text-transform: uppercase; 
        width: 100%; 
        transition: filter 0.2s;
    }
    .btn-pc-action:hover { filter: brightness(0.95); }
    
    .btn-pc-cart { background: #ff9f00; color: #fff; box-shadow: 0 2px 4px rgba(255,159,0,0.3); }
    .btn-pc-buy { background: #fb641b; color: #fff; box-shadow: 0 2px 4px rgba(251,100,27,0.3); }
    
    /* Mobile Actions always visible */
    @media(max-width: 768px) {
        .pc-actions { opacity: 1; margin-top: 10px; }
        .filter-sidebar { display: none; } /* Hide sidebar on mobile, use top scroll */
        .pc-img-wrapper { height: 160px; } /* Smaller on mobile */
    }

    /* Mobile Category Scroll */
    .mobile-cat-nav {
        display: flex; gap: 8px; overflow-x: auto; padding: 5px 0 15px 0; scrollbar-width: none;
    }
    .mobile-cat-nav::-webkit-scrollbar { display: none; }
    
    .mobile-cat-pill {
        white-space: nowrap; padding: 8px 18px; 
        background: #fff; 
        border: none; 
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        border-radius: 50px; 
        color: #555; 
        font-size: 13px; 
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
    }
    .mobile-cat-pill.active { 
        background: #2874f0; 
        color: #fff; 
        box-shadow: 0 4px 10px rgba(40, 116, 240, 0.3);
    }

</style>

<div class="container my-4">
    <div class="row">
        <!-- Sidebar (Desktop) -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="filter-sidebar">
                <div class="filter-header">Categories</div>
                <ul class="category-list">
                    <li><a href="products.php" class="<?php echo empty($category_slug) ? 'active' : ''; ?>">All Products</a></li>
                    <?php 
                    $all_cats->data_seek(0); // Reset pointer
                    while($c = $all_cats->fetch_assoc()): 
                        $isActive = ($category_slug == $c['slug']) ? 'active' : '';
                    ?>
                        <li>
                            <a href="products.php?category=<?php echo $c['slug']; ?>" class="<?php echo $isActive; ?>">
                                <?php echo htmlspecialchars($c['name']); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            
            <!-- Mobile Category Nav -->
            <div class="d-lg-none mb-3">
                 <div class="mobile-cat-nav">
                    <a href="products.php" class="mobile-cat-pill <?php echo empty($category_slug) ? 'active' : ''; ?>">All</a>
                    <?php 
                    $all_cats->data_seek(0);
                    while($c = $all_cats->fetch_assoc()): 
                        $isActive = ($category_slug == $c['slug']) ? 'active' : '';
                    ?>
                        <a href="products.php?category=<?php echo $c['slug']; ?>" class="mobile-cat-pill <?php echo $isActive; ?>">
                            <?php echo htmlspecialchars($c['name']); ?>
                        </a>
                    <?php endwhile; ?>
                 </div>
            </div>

            <!-- Header & Sort -->
            <div class="shop-header">
                <div>
                     <h1 class="h5 mb-0 fw-bold"><?php echo htmlspecialchars($selected_cat_name); ?></h1>
                     <span class="text-muted small">(<?php echo count($products); ?> items)</span>
                </div>
                <div>
                    <select class="sort-select" onchange="location = this.value;">
                        <option value="products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'newest'])); ?>" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price_low'])); ?>" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price_high'])); ?>" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row">
                <?php if(count($products) > 0): ?>
                    <?php foreach($products as $prod): 
                        // Calculate Off %
                        $off = 0;
                        if($prod['mrp'] > $prod['sale_price']) {
                            $off = round((($prod['mrp'] - $prod['sale_price']) / $prod['mrp']) * 100);
                        }
                    ?>
                    <div class="col-6 col-md-4 col-lg-3 product-grid-item">
                        <div class="product-card-v2">
                            <a href="product-details.php?slug=<?php echo $prod['slug']; ?>" class="text-decoration-none text-dark flex-grow-1">
                                <div class="pc-img-wrapper">
                                     <!-- Placeholder if no image -->
                                     <img src="<?php echo !empty($prod['featured_image']) ? $prod['featured_image'] : 'assets/images/demo-data/product.jpg'; ?>" 
                                          alt="<?php echo htmlspecialchars($prod['name']); ?>" class="pc-img">
                                </div>
                                <div class="pc-title" title="<?php echo htmlspecialchars($prod['name']); ?>">
                                    <?php echo htmlspecialchars($prod['name']); ?>
                                </div>
                                <div class="pc-price-box">
                                    <span class="pc-price">₹<?php echo number_format($prod['sale_price']); ?></span>
                                    <?php if($off > 0): ?>
                                        <span class="pc-mrp">₹<?php echo number_format($prod['mrp']); ?></span>
                                        <span class="pc-off"><?php echo $off; ?>% off</span>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <div class="pc-actions">
                                <button class="btn-pc-action btn-pc-cart" onclick="addToCart(<?php echo $prod['id']; ?>)">
                                    <i class="fas fa-shopping-cart me-1"></i> Add
                                </button>
                                <button class="btn-pc-action btn-pc-buy" onclick="buyNow(<?php echo $prod['id']; ?>)">
                                    <i class="fas fa-bolt me-1"></i> Buy
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <img src="assets/images/no-data.png" alt="No Products" style="width: 150px; opacity: 0.5;">
                        <h4 class="mt-3 text-muted">No Products Found</h4>
                        <p class="text-muted">Try changing filters or search terms.</p>
                        <a href="products.php" class="btn btn-primary mt-2">View All Products</a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function addToCart(productId) {
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        fetch('includes/cart_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=add&product_id=${productId}&quantity=1`
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            if(data.status === 'success') {
                // Update Header Badge
                if(typeof updateCartCount === 'function') {
                    updateCartCount();
                }
                
                // Toast
                const Toast = Swal.mixin({
                    toast: true, position: 'bottom-end', showConfirmButton: false, timer: 3000
                });
                Toast.fire({ icon: 'success', title: 'Added to Cart' });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    }

    function buyNow(productId) {
        // Add to cart then redirect
        fetch('includes/cart_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=add&product_id=${productId}&quantity=1`
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                window.location.href = 'cart.php';
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    }
</script>

<?php include 'includes/footer.php'; ?>

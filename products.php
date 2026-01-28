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
    body { background-color: #f1f3f6; }
    
    /* Sidebar */
    .filter-sidebar {
        background: #fff;
        border-radius: 4px; /* Consistent with other design */
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
        padding: 16px;
        position: sticky;
        top: 90px;
    }
    .filter-header { font-size: 16px; font-weight: 600; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #f0f0f0; }
    .category-list { list-style: none; padding: 0; margin: 0; }
    .category-list li { margin-bottom: 10px; }
    .category-list a { 
        color: #212121; text-decoration: none; font-size: 14px; display: block; padding: 5px 0; transition: 0.2s;
    }
    .category-list a:hover, .category-list a.active { color: #2874f0; font-weight: 500; padding-left: 5px; }

    /* Top Bar */
    .shop-header {
        background: #fff;
        padding: 15px;
        border-radius: 4px;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .sort-select { border: 1px solid #e0e0e0; padding: 5px 10px; border-radius: 2px; font-size: 14px; outline: none; }

    /* Product Grid */
    .product-grid-item { margin-bottom: 20px; }
    
    /* Product Card (Matching Best Deals style but for Grid) */
    .product-card-v2 {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
        padding: 16px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s, box-shadow 0.2s;
        text-decoration: none;
        color: inherit;
        position: relative;
    }
    .product-card-v2:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        color: inherit;
    }
    .pc-img-wrapper {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    .pc-img { max-width: 100%; max-height: 100%; object-fit: contain; }
    
    .pc-title { font-size: 14px; font-weight: 500; color: #212121; margin-bottom: 5px; 
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 40px; }
    
    .pc-price-box { margin-bottom: 10px; }
    .pc-price { font-size: 18px; font-weight: 600; color: #212121; }
    .pc-mrp { font-size: 14px; color: #878787; text-decoration: line-through; margin-left: 8px; }
    .pc-off { font-size: 13px; color: #388e3c; font-weight: 500; margin-left: 8px; }
    
    .pc-actions { margin-top: auto; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; opacity: 0; transition: 0.2s; }
    .product-card-v2:hover .pc-actions { opacity: 1; }
    
    .btn-pc-action { font-size: 13px; padding: 8px 0; border: none; border-radius: 2px; font-weight: 600; text-transform: uppercase; width: 100%; }
    .btn-pc-cart { background: #ff9f00; color: #fff; }
    .btn-pc-buy { background: #fb641b; color: #fff; }
    
    /* Mobile Actions always visible */
    @media(max-width: 768px) {
        .pc-actions { opacity: 1; margin-top: 10px; }
        .filter-sidebar { display: none; } /* Hide sidebar on mobile, use top scroll */
    }

    /* Mobile Category Scroll (Top) */
    .mobile-cat-nav {
        display: flex; gap: 10px; overflow-x: auto; padding: 10px 0; scrollbar-width: none;
    }
    .mobile-cat-pill {
        white-space: nowrap; padding: 6px 16px; background: #fff; border: 1px solid #e0e0e0; 
        border-radius: 50px; color: #212121; font-size: 13px; text-decoration: none;
    }
    .mobile-cat-pill.active { background: #2874f0; color: #fff; border-color: #2874f0; }

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
                            <a href="product-details.php?id=<?php echo $prod['id']; ?>" class="text-decoration-none text-dark flex-grow-1">
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

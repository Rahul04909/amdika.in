<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
        // Fetch images to delete files
        $del_sql = "SELECT featured_image, gallery_images FROM products WHERE id = $id";
        $del_res = $conn->query($del_sql);
        if($del_res->num_rows > 0){
            $del_row = $del_res->fetch_assoc();
            if(!empty($del_row['featured_image']) && file_exists("../../" . $del_row['featured_image'])) {
                unlink("../../" . $del_row['featured_image']);
            }
            $gallery = json_decode($del_row['gallery_images'], true);
            if(is_array($gallery)){
                foreach($gallery as $g_img){
                    if(file_exists("../../" . $g_img)) unlink("../../" . $g_img);
                }
            }
        }
        
        // Fetch and unlink variant images
        $var_imgs = $conn->query("SELECT image_path FROM product_color_variants WHERE product_id = $id");
        while ($v_img = $var_imgs->fetch_assoc()) {
            if (!empty($v_img['image_path']) && file_exists("../../" . $v_img['image_path'])) {
                unlink("../../" . $v_img['image_path']);
            }
        }
    
    // 1. Log the product details before deletion
    $log_stmt = $conn->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN product_categories c ON p.category_id = c.id WHERE p.id = ?");
    $log_stmt->bind_param("i", $id);
    $log_stmt->execute();
    $prod_to_log = $log_stmt->get_result()->fetch_assoc();

    if ($prod_to_log) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $snapshot = json_encode($prod_to_log);
        $prod_name = $prod_to_log['name'];
        $cat_name = $prod_to_log['category_name'] ?? 'Uncategorized';
        
        $insert_log = $conn->prepare("INSERT INTO product_deletion_log (product_id, product_name, category_name, deleted_by_ip, full_snapshot) VALUES (?, ?, ?, ?, ?)");
        $insert_log->bind_param("issss", $id, $prod_name, $cat_name, $ip, $snapshot);
        $insert_log->execute();
    }

    $sql = "DELETE FROM products WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: manage-products.php?success=deleted");
    } else {
        header("Location: manage-products.php?error=delete_failed");
    }
    exit;
}

// --- Filters ---
$search       = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_id  = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';

$where_clauses = [];
$params = [];
$types = "";

if (!empty($search)) {
    $where_clauses[] = "(p.name LIKE ? OR p.slug LIKE ?)";
    $s = "%$search%";
    $params[] = $s;
    $params[] = $s;
    $types .= "ss";
}

if (!empty($category_id)) {
    $where_clauses[] = "p.category_id = ?";
    $params[] = (int)$category_id;
    $types .= "i";
}

if (!empty($status_filter)) {
    $where_clauses[] = "p.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

$where_sql = "";
if (!empty($where_clauses)) {
    $where_sql = " WHERE " . implode(" AND ", $where_clauses);
}

// --- Pagination ---
$per_page    = 20;
$page        = max(1, intval($_GET['page'] ?? 1));
$offset      = ($page - 1) * $per_page;

// Count total matching records
$count_sql = "SELECT COUNT(*) FROM products p LEFT JOIN product_categories c ON p.category_id = c.id $where_sql";
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_rows  = (int)$count_stmt->get_result()->fetch_row()[0];
$total_pages = max(1, (int)ceil($total_rows / $per_page));

// Fetch current page of products
$data_sql = "SELECT p.*, c.name as category_name 
             FROM products p 
             LEFT JOIN product_categories c ON p.category_id = c.id 
             $where_sql
             ORDER BY p.created_at DESC 
             LIMIT ? OFFSET ?";

$all_params = array_merge($params, [$per_page, $offset]);
$all_types  = $types . "ii";

$stmt = $conn->prepare($data_sql);
$stmt->bind_param($all_types, ...$all_params);
$stmt->execute();
$result = $stmt->get_result();

// Build query-string fragment to preserve filters in pagination links
$filter_qs = http_build_query(array_filter([
    'search'      => $search,
    'category_id' => $category_id,
    'status'      => $status_filter,
], 'strlen'));

// Fetch categories for the filter dropdown
$cat_result = $conn->query("SELECT id, name FROM product_categories ORDER BY name ASC");

$page_title = 'Manage Products';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Amadika Admin</title>
    <!-- Assets -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:;">
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/images/amdika-logo.png">

    <style>
        body { font-family: 'Rubik', sans-serif; background-color: #f5f7fa; }
        .wrapper { display: flex; }
        #page-content-wrapper { width: 100%; padding: 0; }
        
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; }
        .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.02); }
        .action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; transition: all 0.2s; }
        .btn-edit { background: rgba(212, 160, 23, 0.1); color: #D4A017; }
        .btn-edit:hover { background: #D4A017; color: #fff; }
        .btn-delete { background: rgba(211, 47, 47, 0.1); color: #D32F2F; }
        .btn-delete:hover { background: #D32F2F; color: #fff; }
        .prod-img-preview { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
        .badge-discount { background-color: #e3fcf7; color: #00b894; font-weight: 600; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>

            <div class="container-fluid px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary mb-0">Products <?php if (!empty($search)) echo "- Search: " . htmlspecialchars($search); ?></h2>
                    <div>
                        <?php if (!empty($search)): ?>
                            <a href="manage-products.php" class="btn btn-outline-secondary me-2"><i class="fas fa-times me-2"></i>Clear Search</a>
                        <?php endif; ?>
                        <a href="add-product.php" class="btn btn-danger"><i class="fas fa-plus me-2"></i>Add New Product</a>
                    </div>
                </div>

                <?php if(isset($_GET['success']) && $_GET['success'] == 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Product deleted successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Filter Bar -->
                <div class="card card-custom p-3 mb-4">
                    <form method="GET" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or slug..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1">Category</label>
                            <select name="category_id" class="form-select form-select-sm">
                                <option value="">All Categories</option>
                                <?php while($cat = $cat_result->fetch_assoc()): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo $category_id == $cat['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted mb-1">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All</option>
                                <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-dark btn-sm me-1"><i class="fas fa-filter me-1"></i>Filter</button>
                            <a href="manage-products.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a>
                        </div>
                    </form>
                </div>

                <div class="card card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted">
                            Showing 
                            <?php echo $total_rows ? ($offset + 1) : 0; ?>
                            to 
                            <?php echo min($offset + $per_page, $total_rows); ?>
                            of 
                            <?php echo $total_rows; ?> products
                        </small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="80">Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th class="text-end" width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <?php $img = !empty($row['featured_image']) ? '../../' . $row['featured_image'] : 'https://via.placeholder.com/50'; ?>
                                                <img src="<?php echo $img; ?>" alt="Prod" class="prod-img-preview">
                                            </td>
                                            <td>
                                                <div class="fw-medium text-dark"><?php echo htmlspecialchars($row['name']); ?></div>
                                                <small class="text-muted">Slug: <?php echo htmlspecialchars($row['slug']); ?></small>
                                            </td>
                                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary"><?php echo htmlspecialchars($row['category_name'] ?? 'Uncategorized'); ?></span></td>
                                            <td>
                                                <div class="fw-bold mb-1">₹<?php echo number_format($row['sale_price'], 2); ?></div>
                                                <?php if($row['discount_percent'] > 0): ?>
                                                    <small class="text-decoration-line-through text-muted me-2">₹<?php echo number_format($row['mrp'], 2); ?></small>
                                                    <span class="badge-discount"><?php echo $row['discount_percent']; ?>% OFF</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($row['status'] == 'active'): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit me-2" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="#" class="action-btn btn-delete" title="Delete" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-box-open fs-1 d-block mb-3 opacity-25"></i>
                                            No products found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">Page <?php echo $page; ?> of <?php echo $total_pages; ?></small>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=1<?php echo $filter_qs ? '&'.$filter_qs : ''; ?>">&laquo;</a>
                                </li>
                                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $filter_qs ? '&'.$filter_qs : ''; ?>">&lsaquo;</a>
                                </li>
                                <?php
                                $start_page = max(1, $page - 2);
                                $end_page   = min($total_pages, $page + 2);
                                if ($start_page > 1): ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo $filter_qs ? '&'.$filter_qs : ''; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <?php if ($end_page < $total_pages): ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $filter_qs ? '&'.$filter_qs : ''; ?>">&rsaquo;</a>
                                </li>
                                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $total_pages; ?><?php echo $filter_qs ? '&'.$filter_qs : ''; ?>">&raquo;</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the product permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?delete=' + id;
                }
            })
        }
    </script>
</body>
</html>

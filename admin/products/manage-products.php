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
    
    $sql = "DELETE FROM products WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: manage-products.php?success=deleted");
    } else {
        header("Location: manage-products.php?error=delete_failed");
    }
    exit;
}

// Fetch Products with Category Name
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN product_categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);

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
                    <h2 class="h3 fw-bold text-secondary mb-0">Products</h2>
                    <a href="add-product.php" class="btn btn-danger"><i class="fas fa-plus me-2"></i>Add New Product</a>
                </div>

                <?php if(isset($_GET['success']) && $_GET['success'] == 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Product deleted successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card card-custom p-4">
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

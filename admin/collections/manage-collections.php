<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $del_res = $conn->query("SELECT hero_image FROM collections WHERE id = $id");
    if ($del_res && $del_res->num_rows > 0) {
        $del_row = $del_res->fetch_assoc();
        if (!empty($del_row['hero_image']) && file_exists("../../" . $del_row['hero_image'])) {
            unlink("../../" . $del_row['hero_image']);
        }
    }

    if ($conn->query("DELETE FROM collections WHERE id = $id")) {
        header("Location: manage-collections.php?success=deleted");
    } else {
        header("Location: manage-collections.php?error=delete_failed");
    }
    exit;
}

$result = $conn->query("SELECT c.*, p.name as main_product_name, p.slug as main_product_slug FROM collections c LEFT JOIN products p ON c.main_product_id = p.id ORDER BY c.sort_order ASC, c.created_at DESC");

$page_title = 'Manage Collections';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Collections - Amadika Admin</title>
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
        .col-img-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
        .sort-badge { background: #e8f0fe; color: #1a73e8; font-size: 0.75rem; font-weight: 600; padding: 2px 10px; border-radius: 12px; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>

            <div class="container-fluid px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary mb-0">Collections</h2>
                    <a href="add-collection.php" class="btn btn-danger"><i class="fas fa-plus me-2"></i>Add New Collection</a>
                </div>

                <?php if (isset($_GET['success']) && $_GET['success'] == 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Collection deleted successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['success']) && $_GET['success'] == 'saved'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Collection saved successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card card-custom p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="80">Image</th>
                                    <th>Model Name</th>
                                    <th>AMK Code</th>
                                    <th>Main Product</th>
                                    <th>Products</th>
                                    <th>Sort</th>
                                    <th>Status</th>
                                    <th class="text-end" width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()):
                                        $prods = json_decode($row['selected_products'], true) ?? [];
                                        $prod_count = is_array($prods) ? count($prods) : 0;
                                    ?>
                                        <tr>
                                            <td>
                                                <?php $img = !empty($row['hero_image']) ? '../../' . $row['hero_image'] : '../../assets/images/collection/collection-1.jpeg'; ?>
                                                <img src="<?php echo $img; ?>" alt="Collection" class="col-img-preview">
                                            </td>
                                            <td>
                                                <div class="fw-medium text-dark"><?php echo htmlspecialchars($row['model_name']); ?></div>
                                            </td>
                                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary"><?php echo htmlspecialchars($row['amk_code'] ?: '-'); ?></span></td>
                                            <td>
                                                <?php if ($row['main_product_name']): ?>
                                                    <span class="text-success"><i class="fas fa-check-circle me-1"></i><?php echo htmlspecialchars($row['main_product_name']); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">Not set</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="badge bg-info bg-opacity-10 text-info"><?php echo $prod_count; ?> items</span></td>
                                            <td><span class="sort-badge"><?php echo $row['sort_order']; ?></span></td>
                                            <td>
                                                <?php if ($row['status'] == 'active'): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="edit-collection.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit me-2" title="Edit">
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
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="fas fa-layer-group fs-1 d-block mb-3 opacity-25"></i>
                                            No collections yet. <a href="add-collection.php" class="text-danger">Create your first collection</a>.
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

    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the collection permanently!",
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

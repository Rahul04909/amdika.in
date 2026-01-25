<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Optional: Delete image file from server if needed
    // $sql_img = "SELECT image, seo_featured_image FROM product_categories WHERE id = $id";
    // ... unlink logic ...
    
    $sql = "DELETE FROM product_categories WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: manage-category.php?success=deleted");
    } else {
        header("Location: manage-category.php?error=delete_failed");
    }
    exit;
}

// Fetch Categories
$sql = "SELECT * FROM product_categories ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Amadika Admin</title>
    
    <!-- Content Security Policy -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:;">

    <!-- Bootstrap 5 CSS (Local) -->
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- FontAwesome (Local) -->
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../assets/images/amdika-logo.png">

    <style>
        :root {
            --primary-color: #D32F2F;
            --secondary-color: #2D3436;
            --body-bg: #f5f7fa;
        }
        body {
            font-family: 'Rubik', sans-serif;
            background-color: var(--body-bg);
            overflow-x: hidden;
        }
        .wrapper { display: flex; overflow-x: hidden; }
        #page-content-wrapper { width: 100%; transition: margin-left 0.3s; }
        
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            background: #fff;
        }
        .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.02); }
        .action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; transition: all 0.2s; }
        .btn-edit { background: rgba(212, 160, 23, 0.1); color: #D4A017; }
        .btn-edit:hover { background: #D4A017; color: #fff; }
        .btn-delete { background: rgba(211, 47, 47, 0.1); color: #D32F2F; }
        .btn-delete:hover { background: #D32F2F; color: #fff; }
        .cat-img-preview { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <!-- Sidebar -->
        <?php include '../../admin/includes/sidebar.php'; ?>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Header -->
            <?php include '../../admin/includes/header.php'; ?>

            <div class="container-fluid px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary mb-0">Product Categories</h2>
                    <a href="add-category.php" class="btn btn-danger"><i class="fas fa-plus me-2"></i>Add New Category</a>
                </div>

                <!-- Alert Messages -->
                <?php if(isset($_GET['success']) && $_GET['success'] == 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Category deleted successfully!
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
                                    <th>Slug</th>
                                    <th class="d-none d-md-table-cell">Description</th>
                                    <th class="text-end" width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                    $img = !empty($row['image']) ? '../../' . $row['image'] : 'https://via.placeholder.com/50'; 
                                                ?>
                                                <img src="<?php echo $img; ?>" alt="Cat" class="cat-img-preview">
                                            </td>
                                            <td class="fw-medium"><?php echo htmlspecialchars($row['name']); ?></td>
                                            <td class="text-muted small"><?php echo htmlspecialchars($row['slug']); ?></td>
                                            <td class="d-none d-md-table-cell text-muted small">
                                                <?php 
                                                    $desc = strip_tags(html_entity_decode($row['description'])); 
                                                    echo strlen($desc) > 50 ? substr($desc, 0, 50) . '...' : $desc;
                                                ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="edit-category.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit me-2" title="Edit">
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
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-box-open fs-1 d-block mb-3 opacity-25"></i>
                                            No categories found. Start by adding one!
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

    <!-- Bootstrap Bundle JS (Local) -->
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 (CDN for now, or Local if preferred) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
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

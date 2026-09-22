<?php
require_once __DIR__ . '/../../admin/includes/auth.php';
require_once __DIR__ . '/../../database/db_config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Fetch image to delete the file
    $del_stmt = $conn->prepare("SELECT featured_image FROM blogs WHERE id = ?");
    $del_stmt->bind_param("i", $id);
    $del_stmt->execute();
    $del_res = $del_stmt->get_result();
    if ($del_res->num_rows > 0) {
        $row = $del_res->fetch_assoc();
        if (!empty($row['featured_image']) && file_exists("../../" . $row['featured_image'])) {
            unlink("../../" . $row['featured_image']);
        }
    }
    
    // Delete record from database
    $delete_stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
    $delete_stmt->bind_param("i", $id);
    
    if ($delete_stmt->execute()) {
        header("Location: manage-blogs.php?success=deleted");
    } else {
        header("Location: manage-blogs.php?error=delete_failed");
    }
    exit;
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_clause = "";
$params = [];
$types = "";

if (!empty($search)) {
    $where_clause = " WHERE title LIKE ? OR slug LIKE ? ";
    $s_param = "%$search%";
    $params[] = $s_param;
    $params[] = $s_param;
    $types .= "ss";
}

// Fetch Blogs
$sql = "SELECT * FROM blogs $where_clause ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$page_title = 'Manage Blogs';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Blogs - Amadika Admin</title>
    <!-- Assets -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https://via.placeholder.com;">
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
        .action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; transition: all 0.2s; text-decoration: none; }
        .btn-edit { background: rgba(212, 160, 23, 0.1); color: #D4A017; }
        .btn-edit:hover { background: #D4A017; color: #fff; }
        .btn-delete { background: rgba(211, 47, 47, 0.1); color: #D32F2F; }
        .btn-delete:hover { background: #D32F2F; color: #fff; }
        .blog-img-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>

            <div class="container-fluid px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary mb-0">Blogs <?php if (!empty($search)) echo "- Search: " . htmlspecialchars($search); ?></h2>
                    <div>
                        <?php if (!empty($search)): ?>
                            <a href="manage-blogs.php" class="btn btn-outline-secondary me-2"><i class="fas fa-times me-2"></i>Clear Search</a>
                        <?php endif; ?>
                        <a href="add-blog.php" class="btn btn-danger"><i class="fas fa-plus me-2"></i>Add New Blog</a>
                    </div>
                </div>

                <?php if(isset($_GET['success']) && $_GET['success'] == 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Blog post deleted successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['error']) && $_GET['error'] == 'delete_failed'): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Failed to delete blog post. Please try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card card-custom p-4">
                    <!-- Search Bar -->
                    <form method="GET" class="mb-4">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by title or slug..." value="<?php echo htmlspecialchars($search); ?>">
                                    <button class="btn btn-danger" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="90">Image</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th class="text-end" width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <?php $img = !empty($row['featured_image']) ? '../../' . $row['featured_image'] : 'https://via.placeholder.com/60'; ?>
                                                <img src="<?php echo $img; ?>" alt="Blog Image" class="blog-img-preview">
                                            </td>
                                            <td>
                                                <div class="fw-medium text-dark"><?php echo htmlspecialchars($row['title']); ?></div>
                                                <small class="text-muted">Slug: <?php echo htmlspecialchars($row['slug']); ?></small>
                                            </td>
                                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary"><?php echo htmlspecialchars($row['author'] ?: 'Admin'); ?></span></td>
                                            <td>
                                                <?php if($row['status'] == 'active'): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></small>
                                            </td>
                                            <td class="text-end">
                                                <a href="edit-blog.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit me-2" title="Edit">
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
                                            <i class="fas fa-newspaper fs-1 d-block mb-3 opacity-25"></i>
                                            No blog posts found.
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
                text: "This will delete the blog post permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?delete=' + id;
                }
            })
        }
    </script>
</body>
</html>

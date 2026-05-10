<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM category_promos WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: manage-category-promos.php?success=deleted");
    } else {
        header("Location: manage-category-promos.php?error=failed");
    }
    exit;
}

// Fetch Promos with Category Name
$sql = "SELECT cp.*, pc.name as category_name 
        FROM category_promos cp 
        JOIN product_categories pc ON cp.category_id = pc.id 
        ORDER BY cp.created_at DESC";
$result = $conn->query($sql);

$page_title = 'Category Promo Banners';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Category Promos - Amadika Admin</title>
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/images/amdika-logo.png">
    <style>
        :root { --primary-color: #D32F2F; --body-bg: #f5f7fa; }
        body { font-family: 'Rubik', sans-serif; background-color: var(--body-bg); }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; }
        .promo-img { width: 120px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
        .video-tag { background: #ff0000; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; }
        .image-tag { background: #2874f0; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; }
        .action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; transition: all 0.2s; text-decoration: none; }
        .btn-edit { background: rgba(212, 160, 23, 0.1); color: #D4A017; }
        .btn-delete { background: rgba(211, 47, 47, 0.1); color: #D32F2F; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper" style="width: 100%;">
            <?php include '../../admin/includes/header.php'; ?>
            <div class="container-fluid px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary mb-0">Category Promo Banners/Videos</h2>
                    <a href="add-category-promo.php" class="btn btn-danger"><i class="fas fa-plus me-2"></i>Add New Promo</a>
                </div>

                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">Promo action successful!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>

                <div class="card card-custom p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Media</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Media Path / URL</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result && $result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <?php if($row['type'] == 'image'): ?>
                                                    <img src="../../<?php echo $row['media_path']; ?>" class="promo-img">
                                                <?php else: ?>
                                                    <img src="../../<?php echo $row['thumbnail']; ?>" class="promo-img">
                                                <?php endif; ?>
                                            </td>
                                            <td class="fw-bold"><?php echo htmlspecialchars($row['category_name']); ?></td>
                                            <td>
                                                <span class="<?php echo $row['type'] == 'image' ? 'image-tag' : 'video-tag'; ?>">
                                                    <?php echo strtoupper($row['type']); ?>
                                                </span>
                                            </td>
                                            <td class="small text-muted"><?php echo htmlspecialchars($row['media_path']); ?></td>
                                            <td class="text-end">
                                                <a href="add-category-promo.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit me-2"><i class="fas fa-edit"></i></a>
                                                <a href="javascript:void(0)" onclick="confirmDelete(<?php echo $row['id']; ?>)" class="action-btn btn-delete"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center py-5">No promos found.</td></tr>
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
                title: 'Are you sure?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete!'
            }).then((result) => { if (result.isConfirmed) window.location.href = '?delete=' + id; });
        }
    </script>
</body>
</html>

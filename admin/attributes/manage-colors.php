<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM colors WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: manage-colors.php?success=deleted");
    } else {
        header("Location: manage-colors.php?error=delete_failed");
    }
    exit;
}

// Fetch Colors
$sql = "SELECT * FROM colors ORDER BY created_at DESC";
$result = $conn->query($sql);

$page_title = 'Product Colors';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Colors - Amadika Admin</title>
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/images/amdika-logo.png">
    <style>
        :root { --primary-color: #D32F2F; --body-bg: #f5f7fa; }
        body { font-family: 'Rubik', sans-serif; background-color: var(--body-bg); }
        .wrapper { display: flex; }
        #page-content-wrapper { width: 100%; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; }
        .color-preview { width: 30px; height: 30px; border-radius: 4px; border: 1px solid #ddd; display: inline-block; vertical-align: middle; }
        .action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; transition: all 0.2s; }
        .btn-edit { background: rgba(212, 160, 23, 0.1); color: #D4A017; }
        .btn-edit:hover { background: #D4A017; color: #fff; }
        .btn-delete { background: rgba(211, 47, 47, 0.1); color: #D32F2F; }
        .btn-delete:hover { background: #D32F2F; color: #fff; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>
            <div class="container-fluid px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary mb-0">Manage Colors</h2>
                    <a href="add-color.php" class="btn btn-danger"><i class="fas fa-plus me-2"></i>Add New Color</a>
                </div>

                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        Operation successful!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card card-custom p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="100">Preview</th>
                                    <th>Color Name</th>
                                    <th>Hex Code</th>
                                    <th class="text-end" width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><div class="color-preview" style="background-color: <?php echo $row['hex_code']; ?>;"></div></td>
                                            <td class="fw-medium"><?php echo htmlspecialchars($row['name']); ?></td>
                                            <td><code><?php echo htmlspecialchars($row['hex_code']); ?></code></td>
                                            <td class="text-end">
                                                <a href="edit-color.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit me-2"><i class="fas fa-edit"></i></a>
                                                <a href="#" class="action-btn btn-delete" onclick="confirmDelete(<?php echo $row['id']; ?>)"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center py-5">No colors found.</td></tr>
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
                text: "Delete this color attribute?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => { if (result.isConfirmed) window.location.href = '?delete=' + id; });
        }
    </script>
</body>
</html>

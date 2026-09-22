<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    // Optional: Check if can be deleted (e.g. if already used, maybe soft delete? User asked for delete button)
    // We will do hard delete for now as per simple requirements.
    $conn->query("DELETE FROM coupons WHERE id = $del_id");
    header("Location: manage-coupon-codes.php?msg=deleted");
    exit;
}

// Fetch Coupons
$sql = "SELECT * FROM coupons ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Coupons - Amadika Admin</title>
   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Assets -->
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/images/amdika-logo.png">

    <style>
        body { font-family: 'Rubik', sans-serif; background-color: #f5f7fa; }
        .wrapper { display: flex; }
        #page-content-wrapper { width: 100%; padding: 20px; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; padding: 0; overflow: hidden; }
        .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #e9ecef; font-weight: 600; color: #495057; }
        .status-badge { font-size: 0.85rem; padding: 5px 10px; border-radius: 20px; }
        .status-active { background-color: #d1e7dd; color: #0f5132; }
        .status-expired { background-color: #f8d7da; color: #842029; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
             <?php include '../../admin/includes/header.php'; ?>
             
             <div class="container-fluid mt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary">Manage Coupons</h2>
                    <a href="add-coupon-code.php" class="btn btn-danger"><i class="fas fa-plus me-2"></i> Add Coupon</a>
                </div>

                <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Coupon deleted successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card card-custom">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Sr. No.</th>
                                    <th>Coupon Code</th>
                                    <th>Discount</th>
                                    <th>Min Order Value</th>
                                    <th>Usage (Used/Total)</th>
                                    <th>Valid Till</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result->num_rows > 0): ?>
                                    <?php $i = 1; while($row = $result->fetch_assoc()): ?>
                                        <?php 
                                            // Determine Status Logic
                                            $today = date('Y-m-d');
                                            $is_expired = false;
                                            
                                            if ($row['valid_till'] < $today) {
                                                $is_expired = true;
                                            }
                                            if ($row['used_count'] >= $row['total_usage_limit']) {
                                                $is_expired = true;
                                            }
                                            
                                            $status_class = $is_expired ? 'status-expired' : 'status-active';
                                            $status_text = $is_expired ? 'Expired' : 'Active';
                                        ?>
                                        <tr>
                                            <td class="ps-4 text-muted"><?php echo $i++; ?></td>
                                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($row['code']); ?></td>
                                            <td><?php echo $row['discount_percent']; ?>%</td>
                                            <td>₹<?php echo number_format($row['min_order_value'], 2); ?></td>
                                            <td>
                                                <span class="fw-bold"><?php echo $row['used_count']; ?></span> / <?php echo $row['total_usage_limit']; ?>
                                            </td>
                                            <td><?php echo date('d M Y', strtotime($row['valid_till'])); ?></td>
                                            <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                            <td class="text-end pe-4">
                                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this coupon?');" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">No coupons found. Create some!</td>
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
</body>
</html>

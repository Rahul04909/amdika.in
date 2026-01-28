<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$page_title = 'Manage Orders';

// Pagination Setup
$limit = 20; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Get Total Orders Count
$total_sql = "SELECT COUNT(id) FROM orders";
$total_res = $conn->query($total_sql);
$total_rows = $total_res->fetch_row()[0];
$total_pages = ceil($total_rows / $limit);

// Fetch Orders
$sql = "SELECT o.*, u.name as user_name, u.email as user_email 
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC LIMIT $start, $limit";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Amadika Admin</title>
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
        
        /* Status Badges */
        .status-badge { padding: 5px 12px; border-radius: 20px; font-weight: 500; font-size: 0.85rem; }
        .status-paid { background-color: #e3fcf7; color: #00b894; }
        .status-pending { background-color: #fff3cd; color: #ffc107; }
        .status-failed { background-color: #ffe5e5; color: #ff4757; }

        .btn-receipt { background: rgba(13, 110, 253, 0.1); color: #0d6efd; border: none; padding: 6px 12px; border-radius: 6px; transition: all 0.2s; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn-receipt:hover { background: #0d6efd; color: #fff; }
        
        /* Pagination */
        .page-link { color: #333; border: none; margin: 0 5px; border-radius: 50% !important; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; }
        .page-item.active .page-link { background-color: #0d6efd; color: #fff; }
        .page-link:hover { background-color: #e9ecef; color: #0d6efd; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>

            <div class="container-fluid px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary mb-0">Customer Orders</h2>
                    <div class="text-muted">Total: <?php echo $total_rows; ?> Orders</div>
                </div>

                <div class="card card-custom p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Order No.</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result && $result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['order_no']); ?></div>
                                                <small class="text-muted">ID: <?php echo $row['id']; ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-medium text-dark"><?php echo htmlspecialchars($row['user_name'] ?? 'Guest'); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($row['user_email'] ?? '-'); ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-bold">₹<?php echo number_format($row['final_amount'], 2); ?></div>
                                                <?php if($row['discount_amount'] > 0): ?>
                                                    <small class="text-success text-xs">Dsct: -₹<?php echo number_format($row['discount_amount'], 2); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $statusClass = 'status-pending';
                                                    if(strtolower($row['payment_status']) == 'paid') $statusClass = 'status-paid';
                                                    elseif(strtolower($row['payment_status']) == 'failed') $statusClass = 'status-failed';
                                                ?>
                                                <span class="status-badge <?php echo $statusClass; ?>"><?php echo ucfirst($row['payment_status']); ?></span>
                                            </td>
                                            <td>
                                                <div class="text-secondary">
                                                    <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                                                </div>
                                                <small class="text-muted"><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                                            </td>
                                            <td class="text-end">
                                                <a href="print-receipt.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn-receipt" title="Print Invoice">
                                                    <i class="fas fa-print"></i> Generate Receipt
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-shopping-cart fs-1 d-block mb-3 opacity-25"></i>
                                            No orders found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if($total_pages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-end mb-0">
                            <?php if($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page-1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for($p=1; $p<=$total_pages; $p++): ?>
                                <li class="page-item <?php echo ($p == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $p; ?>"><?php echo $p; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page+1; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
</body>
</html>

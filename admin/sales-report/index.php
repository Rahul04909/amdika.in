<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$page_title = 'Sales Report';

// 1. Get Date Filters (Default to current month)
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');

$from_date_esc = $conn->real_escape_string($from_date);
$to_date_esc = $conn->real_escape_string($to_date);

// 2. Pagination Setup
$limit = 20; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// 3. Get Total Records Count for the filtered range
$count_sql = "SELECT COUNT(oi.id) 
              FROM order_items oi
              JOIN orders o ON oi.order_id = o.id
              WHERE o.payment_status = 'paid'
                AND DATE(o.created_at) >= '$from_date_esc'
                AND DATE(o.created_at) <= '$to_date_esc'";
$count_res = $conn->query($count_sql);
$total_rows = $count_res->fetch_row()[0];
$total_pages = ceil($total_rows / $limit);

// 4. Fetch Items for the table
$sql = "SELECT 
            o.order_no,
            o.created_at,
            o.address_details,
            u.name AS user_name,
            oi.product_name,
            oi.quantity,
            oi.price AS sales_price,
            oi.gst_percent,
            oi.gst_amount,
            oi.total_line_amount,
            p.mrp AS product_mrp
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        LEFT JOIN users u ON o.user_id = u.id
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE o.payment_status = 'paid'
          AND DATE(o.created_at) >= '$from_date_esc'
          AND DATE(o.created_at) <= '$to_date_esc'
        ORDER BY o.created_at DESC LIMIT $start, $limit";
$result = $conn->query($sql);

// 5. Fetch Summary Metrics for the filtered range
$summary_sql = "SELECT 
                    SUM(oi.quantity) as total_qty,
                    SUM(COALESCE(p.mrp, oi.price) * oi.quantity) as total_mrp_value,
                    SUM(oi.price * oi.quantity) as total_sales_value,
                    SUM(oi.gst_amount) as total_gst_value
                FROM order_items oi
                JOIN orders o ON oi.order_id = o.id
                LEFT JOIN products p ON oi.product_id = p.id
                WHERE o.payment_status = 'paid'
                  AND DATE(o.created_at) >= '$from_date_esc'
                  AND DATE(o.created_at) <= '$to_date_esc'";
$summary_res = $conn->query($summary_sql);
$summary = $summary_res->fetch_assoc();

$total_qty = $summary['total_qty'] ?? 0;
$total_mrp = $summary['total_mrp_value'] ?? 0.00;
$total_sales = $summary['total_sales_value'] ?? 0.00;
$total_gst = $summary['total_gst_value'] ?? 0.00;
$total_discount = $total_mrp - $total_sales;
$grand_total = $total_sales + $total_gst;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - Amadika Admin</title>
    <!-- Assets -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:;">
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/images/amdika-logo.png">

    <style>
        body { 
            font-family: 'Rubik', sans-serif; 
            background-color: #f5f7fa; 
            overflow-x: hidden; 
        }
        .wrapper { 
            display: flex; 
            overflow-x: hidden; 
            width: 100%; 
        }
        
        /* Layout Scrollbar Fixes */
        #page-content-wrapper {
            margin-left: 260px !important;
            width: calc(100% - 260px) !important;
            min-width: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        body.sb-collapsed #page-content-wrapper {
            margin-left: 70px !important;
            width: calc(100% - 70px) !important;
        }
        @media (max-width: 991px) {
            #page-content-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
        
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; }
        .kpi-card { border-radius: 12px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.04); }
        
        /* KPI Cards Styling */
        .kpi-label {
            font-size: 10px !important;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8c98a5 !important;
            white-space: nowrap !important;
        }
        .kpi-value {
            font-size: 1.45rem !important;
            font-weight: 700;
            color: #2d3436;
        }
        .kpi-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .kpi-icon i {
            font-size: 1.1rem !important;
        }
        .kpi-icon.icon-primary { background-color: rgba(79, 70, 229, 0.1); color: #4f46e5 !important; }
        .kpi-icon.icon-success { background-color: rgba(16, 185, 129, 0.1); color: #10b981 !important; }
        .kpi-icon.icon-warning { background-color: rgba(245, 158, 11, 0.1); color: #f59e0b !important; }
        .kpi-icon.icon-danger { background-color: rgba(239, 68, 68, 0.1); color: #ef4444 !important; }

        .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.02); }
        
        /* Professional Report Table Styles */
        .report-table {
            font-size: 11px !important;
            width: 100%;
            margin-bottom: 0;
        }
        .report-table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px !important;
            letter-spacing: 0.5px;
            background-color: #f8f9fa;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            white-space: nowrap !important;
            padding: 8px 10px !important;
        }
        .report-table td {
            white-space: nowrap !important;
            vertical-align: middle;
            padding: 6px 10px !important;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
        }
        .report-table td, 
        .report-table td div,
        .report-table td span,
        .report-table th {
            font-size: 11px !important;
        }
        .report-table td small {
            font-size: 9px !important;
            opacity: 0.8;
        }
        .customer-name-txt {
            max-width: 100px;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            display: block;
        }
        .product-name-txt {
            max-width: 160px;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            display: block;
        }
        
        /* Pagination */
        .page-link { color: #333; border: none; margin: 0 5px; border-radius: 50% !important; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; }
        .page-item.active .page-link { background-color: #D4A017; color: #fff; }
        .page-link:hover { background-color: #e9ecef; color: #D4A017; }
        
        .btn-gold { background-color: #D4A017; color: #fff; border: none; font-weight: 500; border-radius: 6px; transition: all 0.2s; }
        .btn-gold:hover { background-color: #c09012; color: #fff; }
        
        .btn-outline-gold { border: 1px solid #D4A017; color: #D4A017; background: transparent; font-weight: 500; border-radius: 6px; transition: all 0.2s; }
        .btn-outline-gold:hover { background-color: #D4A017; color: #fff; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>

            <div class="container-fluid px-4 py-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <div>
                        <h2 class="h3 fw-bold text-secondary mb-1">Sales Report</h2>
                        <p class="text-muted mb-0">Track store performance and generate spreadsheets.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="export.php?from_date=<?php echo urlencode($from_date); ?>&to_date=<?php echo urlencode($to_date); ?>" class="btn btn-gold px-4 py-2 d-inline-flex align-items-center gap-2">
                            <i class="fas fa-file-excel"></i> Export to Excel
                        </a>
                    </div>
                </div>

                <!-- Filters Card -->
                <div class="card card-custom p-4 mb-4">
                    <form method="GET" class="row align-items-end g-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold text-secondary">From Date</label>
                            <input type="date" name="from_date" class="form-control" value="<?php echo htmlspecialchars($from_date); ?>" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold text-secondary">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="<?php echo htmlspecialchars($to_date); ?>" required>
                        </div>
                        <div class="col-12 col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-outline-gold w-100 py-2">
                                <i class="fas fa-filter me-1"></i> Filter Report
                            </button>
                            <a href="index.php" class="btn btn-light border w-100 py-2 text-center text-decoration-none text-secondary">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- KPI Summary Dashboard Cards -->
                <div class="row g-3 mb-4">
                    <!-- Total Items Sold -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="card kpi-card bg-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="kpi-label mb-1 d-block">Items Sold</span>
                                    <h3 class="kpi-value mb-0"><?php echo number_format($total_qty); ?></h3>
                                </div>
                                <div class="kpi-icon icon-primary">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Total Sales (Excl. Tax) -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="card kpi-card bg-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="kpi-label mb-1 d-block">Net Revenue</span>
                                    <h3 class="kpi-value mb-0">₹<?php echo number_format($total_sales, 2); ?></h3>
                                </div>
                                <div class="kpi-icon icon-success">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Total GST Tax -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="card kpi-card bg-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="kpi-label mb-1 d-block">GST Collected</span>
                                    <h3 class="kpi-value mb-0">₹<?php echo number_format($total_gst, 2); ?></h3>
                                </div>
                                <div class="kpi-icon icon-warning">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Gross Revenue (Sales + GST) -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="card kpi-card bg-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="kpi-label mb-1 d-block">Gross Total</span>
                                    <h3 class="kpi-value mb-0">₹<?php echo number_format($grand_total, 2); ?></h3>
                                </div>
                                <div class="kpi-icon icon-danger">
                                    <i class="fas fa-rupee-sign"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Table Card -->
                <div class="card card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fs-5 text-secondary mb-0 fw-semibold">Transactions Log</h4>
                        <div class="text-muted text-xs">Page records: <?php echo $result ? $result->num_rows : 0; ?> / Total: <?php echo $total_rows; ?></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle report-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice No.</th>
                                    <th>Customer Name</th>
                                    <th>Product Name</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">MRP</th>
                                    <th class="text-end">Sales Price</th>
                                    <th class="text-end">Discount</th>
                                    <th class="text-end">GST</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result && $result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <?php
                                            $addr = json_decode($row['address_details'], true);
                                            $cust_name = $addr['name'] ?? $row['user_name'] ?? 'Guest';
                                            $mrp = ($row['product_mrp'] > 0) ? $row['product_mrp'] : $row['sales_price'];
                                            $qty = $row['quantity'];
                                            $sales_price = $row['sales_price'];
                                            $discount = ($mrp - $sales_price) * $qty;
                                            $gst = $row['gst_amount'];
                                            $line_total = $row['total_line_amount'] + $gst;
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="text-secondary"><?php echo date('d M Y', strtotime($row['created_at'])); ?></span>
                                            </td>
                                            <td class="fw-semibold text-primary"><?php echo htmlspecialchars($row['order_no']); ?></td>
                                            <td>
                                                <span class="customer-name-txt fw-medium text-dark" title="<?php echo htmlspecialchars($cust_name); ?>">
                                                    <?php echo htmlspecialchars($cust_name); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="product-name-txt text-secondary" title="<?php echo htmlspecialchars($row['product_name']); ?>">
                                                    <?php echo htmlspecialchars($row['product_name']); ?>
                                                </span>
                                            </td>
                                            <td class="text-center"><?php echo $qty; ?></td>
                                            <td class="text-end">₹<?php echo number_format($mrp, 2); ?></td>
                                            <td class="text-end">₹<?php echo number_format($sales_price, 2); ?></td>
                                            <td class="text-end text-success fw-medium">-₹<?php echo number_format($discount, 2); ?></td>
                                            <td class="text-end text-secondary">
                                                ₹<?php echo number_format($gst, 2); ?> <small class="text-muted">(<?php echo $row['gst_percent']; ?>%)</small>
                                            </td>
                                            <td class="text-end fw-bold">₹<?php echo number_format($line_total, 2); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center py-5 text-muted">
                                            <i class="fas fa-chart-line fs-1 d-block mb-3 opacity-25"></i>
                                            No sales transactions found for the selected date range.
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
                                    <a class="page-link" href="?from_date=<?php echo urlencode($from_date); ?>&to_date=<?php echo urlencode($to_date); ?>&page=<?php echo $page-1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for($p=1; $p<=$total_pages; $p++): ?>
                                <li class="page-item <?php echo ($p == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?from_date=<?php echo urlencode($from_date); ?>&to_date=<?php echo urlencode($to_date); ?>&page=<?php echo $p; ?>"><?php echo $p; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?from_date=<?php echo urlencode($from_date); ?>&to_date=<?php echo urlencode($to_date); ?>&page=<?php echo $page+1; ?>" aria-label="Next">
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

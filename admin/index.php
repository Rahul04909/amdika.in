<?php
require_once 'includes/auth.php';
require_once '../database/db_config.php';

// --- fetch stats ---
// 1. Total Users
$user_sql = "SELECT COUNT(id) FROM users";
$user_res = $conn->query($user_sql);
$total_users = $user_res->fetch_row()[0];

// 2. Total Orders
$order_sql = "SELECT COUNT(id) FROM orders";
$order_res = $conn->query($order_sql);
$total_orders = $order_res->fetch_row()[0];

// 3. Total Products
$prod_sql = "SELECT COUNT(id) FROM products";
$prod_res = $conn->query($prod_sql);
$total_products = $prod_res->fetch_row()[0];

// 4. Total Revenue
$rev_sql = "SELECT SUM(final_amount) FROM orders WHERE payment_status = 'paid'";
$rev_res = $conn->query($rev_sql);
$total_revenue = $rev_res->fetch_row()[0] ?? 0;

// 5. Total Coupons
$coupon_sql = "SELECT COUNT(id) FROM coupons";
$coupon_res = $conn->query($coupon_sql);
$total_coupons = $coupon_res->fetch_row()[0];

// 6. Total Categories
$cat_sql = "SELECT COUNT(id) FROM product_categories";
$cat_res = $conn->query($cat_sql);
$total_categories = $cat_res->fetch_row()[0];

// --- Fetch Recent Orders ---
$recent_sql = "SELECT o.*, u.name as customer_name 
               FROM orders o 
               LEFT JOIN users u ON o.user_id = u.id 
               ORDER BY o.created_at DESC LIMIT 5";
$recent_res = $conn->query($recent_sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Amadika Admin Dashboard</title>
    
    <!-- Content Security Policy: Strict, no external scripts -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:;">

    <!-- Bootstrap 5 CSS (Local) -->
    <link href="../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- FontAwesome (Local) -->
    <link rel="stylesheet" href="../assets/vendor/css/all.min.css" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/amdika-logo.png">

    <style>
        :root {
            --primary-color: #D32F2F;
            --accent-gold: #D4A017;
            --secondary-color: #2D3436;
            --body-bg: #f5f7fa;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            --white: #ffffff;
        }

        body {
            font-family: 'Rubik', sans-serif;
            background-color: var(--body-bg);
            overflow-x: hidden;
        }

        /* --- Layout Wrapper --- */
        .d-flex.wrapper {
            overflow-x: hidden;
        }
        
        #page-content-wrapper {
            width: 100%;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* --- Solid Color KPI Cards --- */
        .kpi-card-solid {
            border-radius: 12px;
            padding: 25px;
            height: 120px; /* Taller for the solid look */
            position: relative;
            overflow: hidden;
            color: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .kpi-card-solid:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        /* Content Z-Index to stay above watermark */
        .kpi-content-solid {
            position: relative;
            z-index: 2;
        }

        .kpi-value-solid {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 5px;
            display: block;
        }

        .kpi-label-solid {
            font-size: 0.95rem;
            font-weight: 500;
            opacity: 0.9;
        }

        /* Watermark Icon */
        .kpi-icon-watermark {
            position: absolute;
            right: -10px;
            bottom: -15px;
            font-size: 80px;
            opacity: 0.25;
            transform: rotate(15deg); /* Dynamic tilt */
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .kpi-card-solid:hover .kpi-icon-watermark {
            transform: rotate(0deg) scale(1.1);
        }

        /* Solid Gradients */
        .bg-gradient-blue   { background: linear-gradient(135deg, #42a5f5, #1976d2); }
        .bg-gradient-purple { background: linear-gradient(135deg, #ab47bc, #7b1fa2); }
        .bg-gradient-indigo { background: linear-gradient(135deg, #5c6bc0, #303f9f); }
        .bg-gradient-green  { background: linear-gradient(135deg, #66bb6a, #388e3c); }
        .bg-gradient-orange { background: linear-gradient(135deg, #ffa726, #f57c00); }
        .bg-gradient-red    { background: linear-gradient(135deg, #ef5350, #c62828); }
        
        /* Table Styles override */
        .table thead th {
            font-weight: 600;
            color: var(--secondary-color);
            border-bottom-width: 1px;
            background-color: #f8f9fa;
        }
        
        /* Status Badges */
        .status-badge { padding: 5px 12px; border-radius: 20px; font-weight: 500; font-size: 0.85rem; }
        .status-paid { background-color: #e3fcf7; color: #00b894; }
        .status-pending { background-color: #fff3cd; color: #ffc107; }
        .status-failed { background-color: #ffe5e5; color: #ff4757; }


        /* --- Scrollbar Customization --- */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #bbb; }
    </style>
</head>

<body>
    <div class="d-flex wrapper" id="wrapper">
        
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            
            <!-- Header -->
            <?php include 'includes/header.php'; ?>

            <!-- Main Content -->
            <div class="container-fluid px-4">
                
                <!-- 6-Column KPI Grid (Solid Design) -->
                <div class="row g-4 mt-4 mb-4">
                    
                    <!-- 1. Total Users -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-blue">
                            <i class="fas fa-users kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid"><?php echo number_format($total_users); ?></span>
                                <span class="kpi-label-solid">Total Users</span>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Total Orders -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-purple">
                            <i class="fas fa-shopping-bag kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid"><?php echo number_format($total_orders); ?></span>
                                <span class="kpi-label-solid">Total Orders</span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Total Products -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-indigo">
                            <i class="fas fa-box-open kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid"><?php echo number_format($total_products); ?></span>
                                <span class="kpi-label-solid">Total Products</span>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Total Revenue (Was Total Sales) -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-green">
                            <i class="fas fa-rupee-sign kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid">₹<?php echo number_format($total_revenue); ?></span>
                                <span class="kpi-label-solid">Total Sales</span>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Coupon Codes (Was Pending Orders) -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-orange">
                            <i class="fas fa-tags kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid"><?php echo number_format($total_coupons); ?></span>
                                <span class="kpi-label-solid">Coupon Codes</span>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Total Categories (Was Out of Stock) -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-red">
                            <i class="fas fa-layer-group kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid"><?php echo number_format($total_categories); ?></span>
                                <span class="kpi-label-solid">Categories</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Recent Orders Table -->
                <div class="row my-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                         <h3 class="fs-4 text-secondary mb-0">Recent Orders</h3>
                         <a href="orders/index.php" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                   
                    <div class="col">
                        <div class="table-responsive bg-white rounded shadow-sm p-4">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">Order No.</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($recent_res && $recent_res->num_rows > 0): ?>
                                        <?php while($row = $recent_res->fetch_assoc()): ?>
                                            <tr>
                                                <th scope="row" class="text-primary"><?php echo htmlspecialchars($row['order_no']); ?></th>
                                                <td><?php echo htmlspecialchars($row['customer_name'] ?? 'Guest'); ?></td>
                                                <td class="fw-bold">₹<?php echo number_format($row['final_amount'], 2); ?></td>
                                                <td><?php echo date('d M, Y', strtotime($row['created_at'])); ?></td>
                                                <td>
                                                    <?php 
                                                        $statusClass = 'status-pending';
                                                        if(strtolower($row['payment_status']) == 'paid') $statusClass = 'status-paid';
                                                        elseif(strtolower($row['payment_status']) == 'failed') $statusClass = 'status-failed';
                                                    ?>
                                                    <span class="status-badge <?php echo $statusClass; ?>"><?php echo ucfirst($row['payment_status']); ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="orders/print-receipt.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn btn-sm btn-light text-primary" title="Receipt">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">No orders found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap Bundle JS (Local) -->
    <script src="../assets/vendor/js/bootstrap.bundle.min.js"></script>
    
</body>

</html>

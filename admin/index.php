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

// 7. Live Visitors
$live_sql = "SELECT COUNT(DISTINCT session_id) FROM visitor_logs WHERE created_at >= NOW() - INTERVAL 5 MINUTE AND device_type != 'Bot'";
$live_res = $conn->query($live_sql);
$live_visitors = $live_res ? $live_res->fetch_row()[0] : 0;

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
            font-size: 1.45rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 5px;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .kpi-label-solid {
            font-size: 0.82rem;
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

        /* --- Live Visitor Tracker Styles --- */
        .pulsing-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #2ecc71;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
            animation: pulse 1.6s infinite;
            vertical-align: middle;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.9);
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(46, 204, 113, 0);
            }
            100% {
                transform: scale(0.9);
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0);
            }
        }

        .badge-live {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .bg-gradient-emerald {
            background: linear-gradient(135deg, #10b981, #047857);
        }
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

                    <!-- 6. Total Categories -->
                    <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                        <div class="kpi-card-solid bg-gradient-red">
                            <i class="fas fa-layer-group kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid"><?php echo number_format($total_categories); ?></span>
                                <span class="kpi-label-solid">Categories</span>
                            </div>
                        </div>
                    </div>

                    <!-- 7. Live Visitors - Full Width Row Below -->
                </div>
                
                <!-- Live Visitors Row -->
                <div class="row g-4 mb-4">
                    <!-- Live Visitors KPI Card -->
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="kpi-card-solid bg-gradient-emerald" style="height: auto; min-height: 120px;">
                            <i class="fas fa-wifi kpi-icon-watermark"></i>
                            <div class="kpi-content-solid">
                                <span class="kpi-value-solid d-flex align-items-center gap-2">
                                    <span class="pulsing-dot"></span>
                                    <span id="live-count"><?php echo number_format($live_visitors); ?></span>
                                </span>
                                <span class="kpi-label-solid">Live Visitors <small class="opacity-75">(last 5 min)</small></span>
                                <div class="mt-2">
                                    <small class="opacity-75" style="font-size: 0.7rem;">
                                        <i class="fas fa-sync-alt me-1"></i>Auto-refreshes every 8 seconds
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Visitors Activity Feed -->
                    <div class="col-12 col-md-8 col-lg-9">
                        <div class="bg-white rounded shadow-sm p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 fw-bold text-secondary fs-6 d-flex align-items-center gap-2">
                                    <span class="pulsing-dot"></span>
                                    Live Visitor Activity
                                </h5>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge-live">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>LIVE
                                    </span>
                                    <a href="visitor-logs.php" class="btn btn-sm btn-outline-secondary">Full Logs</a>
                                </div>
                            </div>
                            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                <table class="table table-hover align-middle mb-0" style="font-size: 0.82rem;">
                                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                        <tr>
                                            <th>IP Address</th>
                                            <th>Device</th>
                                            <th>Browser / OS</th>
                                            <th>Current Page</th>
                                            <th>Last Active</th>
                                        </tr>
                                    </thead>
                                    <tbody id="live-visitors-table-body">
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">
                                                <i class="fas fa-spinner fa-spin me-2"></i>Loading live visitors...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 d-none"><!-- placeholder row -->

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

    <!-- Live Visitors AJAX Polling Script -->
    <script>
    (function() {
        // Device icon helper
        function getDeviceIcon(deviceType) {
            switch((deviceType || '').toLowerCase()) {
                case 'mobile':   return '<i class="fas fa-mobile-alt text-info me-1"></i>';
                case 'tablet':   return '<i class="fas fa-tablet-alt text-warning me-1"></i>';
                case 'bot':      return '<i class="fas fa-robot text-secondary me-1"></i>';
                default:         return '<i class="fas fa-desktop text-primary me-1"></i>';
            }
        }

        // Browser icon helper
        function getBrowserIcon(browser) {
            const b = (browser || '').toLowerCase();
            if (b.includes('chrome'))  return '<i class="fab fa-chrome me-1" style="color:#4285F4;"></i>';
            if (b.includes('firefox')) return '<i class="fab fa-firefox me-1" style="color:#FF7139;"></i>';
            if (b.includes('safari'))  return '<i class="fab fa-safari me-1" style="color:#006CFF;"></i>';
            if (b.includes('edge'))    return '<i class="fab fa-edge me-1" style="color:#0078D7;"></i>';
            if (b.includes('opera'))   return '<i class="fab fa-opera me-1" style="color:#FF1B2D;"></i>';
            return '<i class="fas fa-globe me-1 text-muted"></i>';
        }

        function fetchLiveVisitors() {
            fetch('api/live_visitors.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.success) return;

                    // Update counter
                    const countEl = document.getElementById('live-count');
                    if (countEl) countEl.textContent = data.live_count;

                    // Build table rows
                    const tbody = document.getElementById('live-visitors-table-body');
                    if (!tbody) return;

                    if (!data.visitors || data.visitors.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    <i class="fas fa-eye-slash me-2"></i>No active visitors in the last 5 minutes.
                                </td>
                            </tr>`;
                        return;
                    }

                    let html = '';
                    data.visitors.forEach(v => {
                        const deviceIcon = getDeviceIcon(v.device_type);
                        const browserIcon = getBrowserIcon(v.browser);
                        
                        // Truncate long page URLs
                        let displayPage = v.page_url || '/';
                        if (displayPage.length > 40) displayPage = displayPage.substring(0, 37) + '...';

                        // Referrer display
                        let ref = v.referrer || 'Direct';
                        if (ref.length > 35) ref = ref.substring(0, 32) + '...';

                        // Bot label
                        const isBotBadge = v.device_type === 'Bot' ? '<span class="badge bg-secondary ms-1" style="font-size:0.65rem;">BOT</span>' : '';

                        html += `<tr>
                            <td>
                                <code style="font-size:0.78rem; background:#f8f9fa; padding:2px 5px; border-radius:3px;">${v.ip_address}</code>
                                ${isBotBadge}
                            </td>
                            <td>${deviceIcon}${v.device_type}</td>
                            <td>${browserIcon}<strong>${v.browser}</strong> &nbsp;<span class="text-muted" style="font-size:0.75rem;">${v.os}</span></td>
                            <td>
                                <span title="${v.page_url}" style="font-size:0.78rem;">${displayPage}</span>
                            </td>
                            <td><span class="text-success fw-semibold">${v.last_active}</span></td>
                        </tr>`;
                    });

                    // Fade out then in
                    tbody.style.opacity = '0';
                    tbody.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => {
                        tbody.innerHTML = html;
                        tbody.style.opacity = '1';
                    }, 250);
                })
                .catch(err => {
                    console.warn('Live visitors fetch error:', err);
                });
        }

        // Initial load immediately
        fetchLiveVisitors();

        // Poll every 8 seconds
        setInterval(fetchLiveVisitors, 8000);
    })();
    </script>
    
</body>

</html>

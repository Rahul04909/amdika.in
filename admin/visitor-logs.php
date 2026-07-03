<?php
require_once 'includes/auth.php';
require_once '../database/db_config.php';

// ── Pagination ──────────────────────────────────────────────────────────────
$per_page = 25;
$page     = max(1, intval($_GET['page'] ?? 1));
$offset   = ($page - 1) * $per_page;

// ── Filters ──────────────────────────────────────────────────────────────────
$filter_ip     = trim($_GET['ip']     ?? '');
$filter_device = trim($_GET['device'] ?? '');
$filter_date   = trim($_GET['date']   ?? '');   // e.g. 2025-07-03
$filter_page   = trim($_GET['purl']   ?? '');

// ── Build WHERE clause ───────────────────────────────────────────────────────
$where = [];
$params = [];
$types  = '';

if ($filter_ip !== '') {
    $where[]  = 'ip_address LIKE ?';
    $params[] = '%' . $filter_ip . '%';
    $types   .= 's';
}
if ($filter_device !== '' && $filter_device !== 'all') {
    $where[]  = 'device_type = ?';
    $params[] = $filter_device;
    $types   .= 's';
}
if ($filter_date !== '') {
    $where[]  = 'DATE(created_at) = ?';
    $params[] = $filter_date;
    $types   .= 's';
}
if ($filter_page !== '') {
    $where[]  = 'page_url LIKE ?';
    $params[] = '%' . $filter_page . '%';
    $types   .= 's';
}

$where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// ── Totals for this filter ───────────────────────────────────────────────────
$count_sql = "SELECT COUNT(*) FROM visitor_logs $where_sql";
if ($params) {
    $stmt = $conn->prepare($count_sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $total_rows = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
} else {
    $total_rows = $conn->query($count_sql)->fetch_row()[0];
}
$total_pages = max(1, ceil($total_rows / $per_page));

// ── Fetch paginated rows ─────────────────────────────────────────────────────
$rows_sql = "SELECT * FROM visitor_logs $where_sql ORDER BY created_at DESC LIMIT ? OFFSET ?";
$all_params = array_merge($params, [$per_page, $offset]);
$all_types  = $types . 'ii';
$stmt = $conn->prepare($rows_sql);
$stmt->bind_param($all_types, ...$all_params);
$stmt->execute();
$rows_res = $stmt->get_result();
$logs = [];
while ($r = $rows_res->fetch_assoc()) $logs[] = $r;
$stmt->close();

// ── Summary Stats ────────────────────────────────────────────────────────────
$stats = [];
$sums = [
    'total'     => "SELECT COUNT(*) FROM visitor_logs",
    'unique_ip' => "SELECT COUNT(DISTINCT ip_address) FROM visitor_logs",
    'today'     => "SELECT COUNT(*) FROM visitor_logs WHERE DATE(created_at) = CURDATE()",
    'this_week' => "SELECT COUNT(*) FROM visitor_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)",
];
foreach ($sums as $k => $q) $stats[$k] = $conn->query($q)->fetch_row()[0];

// Device breakdown
$dev_sql = "SELECT device_type, COUNT(*) as cnt FROM visitor_logs GROUP BY device_type ORDER BY cnt DESC";
$dev_res = $conn->query($dev_sql);
$device_breakdown = [];
while ($d = $dev_res->fetch_assoc()) $device_breakdown[] = $d;

// Browser breakdown
$br_sql = "SELECT browser, COUNT(*) as cnt FROM visitor_logs GROUP BY browser ORDER BY cnt DESC LIMIT 5";
$br_res = $conn->query($br_sql);
$browser_breakdown = [];
while ($b = $br_res->fetch_assoc()) $browser_breakdown[] = $b;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Visitor Logs – Amadika Admin</title>

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
            --white: #ffffff;
        }
        body { font-family: 'Rubik', sans-serif; background-color: var(--body-bg); overflow-x: hidden; }
        #page-content-wrapper { width: 100%; }

        /* KPI mini cards */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            border-left: 4px solid;
        }
        .stat-card.blue   { border-color: #1976d2; }
        .stat-card.green  { border-color: #388e3c; }
        .stat-card.orange { border-color: #f57c00; }
        .stat-card.purple { border-color: #7b1fa2; }

        .stat-value { font-size: 1.8rem; font-weight: 700; line-height: 1; }
        .stat-label { font-size: 0.82rem; font-weight: 500; color: #636e72; margin-top: 4px; }

        /* Bar charts (CSS only) */
        .mini-bar-wrap { display: flex; flex-direction: column; gap: 8px; }
        .mini-bar-row  { display: flex; align-items: center; gap: 10px; font-size: 0.8rem; }
        .mini-bar-label{ width: 80px; text-align: right; color: #636e72; flex-shrink: 0; }
        .mini-bar-bg   { flex: 1; background: #f1f3f5; border-radius: 20px; height: 8px; overflow: hidden; }
        .mini-bar-fill { height: 100%; border-radius: 20px; transition: width 0.5s ease; }
        .mini-bar-cnt  { width: 36px; text-align: right; font-weight: 600; color: #2d3436; }

        /* Table */
        .table thead th { font-weight: 600; color: var(--secondary-color); background-color: #f8f9fa; border-bottom-width: 1px; }
        .status-badge { padding: 3px 10px; border-radius: 20px; font-weight: 500; font-size: 0.78rem; }

        /* Pagination */
        .page-link { color: var(--secondary-color); }
        .page-item.active .page-link { background-color: var(--secondary-color); border-color: var(--secondary-color); color: #fff; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 7px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
    </style>
</head>

<body>
<div class="d-flex wrapper" id="wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <div id="page-content-wrapper">
        <?php include 'includes/header.php'; ?>

        <div class="container-fluid px-4 py-4">

            <!-- Page Title -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1 text-secondary">
                        <i class="fas fa-chart-line me-2"></i>Visitor Logs
                    </h4>
                    <p class="text-muted mb-0" style="font-size:0.88rem;">
                        Track every visit across your website with IP, device, browser and page details.
                    </p>
                </div>
                <a href="index.php" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>

            <!-- Summary Stats Row -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card blue">
                        <div class="stat-value text-primary"><?php echo number_format($stats['total']); ?></div>
                        <div class="stat-label"><i class="fas fa-mouse-pointer me-1"></i>Total Hits</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card green">
                        <div class="stat-value text-success"><?php echo number_format($stats['unique_ip']); ?></div>
                        <div class="stat-label"><i class="fas fa-fingerprint me-1"></i>Unique IPs</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card orange">
                        <div class="stat-value text-warning"><?php echo number_format($stats['today']); ?></div>
                        <div class="stat-label"><i class="fas fa-calendar-day me-1"></i>Today's Hits</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card purple">
                        <div class="stat-value text-purple" style="color:#7b1fa2;"><?php echo number_format($stats['this_week']); ?></div>
                        <div class="stat-label"><i class="fas fa-calendar-week me-1"></i>This Week</div>
                    </div>
                </div>
            </div>

            <!-- Breakdown Charts Row -->
            <div class="row g-3 mb-4">
                <!-- Device Breakdown -->
                <div class="col-12 col-md-6">
                    <div class="bg-white rounded shadow-sm p-4 h-100">
                        <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-mobile-alt me-2 text-info"></i>Device Breakdown</h6>
                        <div class="mini-bar-wrap">
                        <?php
                        $device_max = $device_breakdown ? max(array_column($device_breakdown, 'cnt')) : 1;
                        $device_colors = ['Desktop'=>'#1976d2','Mobile'=>'#43a047','Tablet'=>'#f57c00','Bot'=>'#7b1fa2'];
                        foreach ($device_breakdown as $dev):
                            $pct = ($device_max > 0) ? round(($dev['cnt'] / $device_max) * 100) : 0;
                            $color = $device_colors[$dev['device_type']] ?? '#636e72';
                        ?>
                        <div class="mini-bar-row">
                            <span class="mini-bar-label"><?php echo htmlspecialchars($dev['device_type']); ?></span>
                            <div class="mini-bar-bg">
                                <div class="mini-bar-fill" style="width:<?php echo $pct; ?>%; background:<?php echo $color; ?>;"></div>
                            </div>
                            <span class="mini-bar-cnt"><?php echo number_format($dev['cnt']); ?></span>
                        </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Browser Breakdown -->
                <div class="col-12 col-md-6">
                    <div class="bg-white rounded shadow-sm p-4 h-100">
                        <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-globe me-2 text-primary"></i>Top Browsers</h6>
                        <div class="mini-bar-wrap">
                        <?php
                        $browser_max = $browser_breakdown ? max(array_column($browser_breakdown, 'cnt')) : 1;
                        $browser_colors = ['Chrome'=>'#4285F4','Firefox'=>'#FF7139','Safari'=>'#006CFF','Edge'=>'#0078D7','Opera'=>'#FF1B2D'];
                        foreach ($browser_breakdown as $br):
                            $pct = ($browser_max > 0) ? round(($br['cnt'] / $browser_max) * 100) : 0;
                            $color = $browser_colors[$br['browser']] ?? '#636e72';
                        ?>
                        <div class="mini-bar-row">
                            <span class="mini-bar-label"><?php echo htmlspecialchars($br['browser']); ?></span>
                            <div class="mini-bar-bg">
                                <div class="mini-bar-fill" style="width:<?php echo $pct; ?>%; background:<?php echo $color; ?>;"></div>
                            </div>
                            <span class="mini-bar-cnt"><?php echo number_format($br['cnt']); ?></span>
                        </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded shadow-sm p-4 mb-4">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-semibold" style="font-size:0.82rem;">IP Address</label>
                        <input type="text" name="ip" class="form-control form-control-sm"
                            placeholder="e.g. 192.168." value="<?php echo htmlspecialchars($filter_ip); ?>">
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label fw-semibold" style="font-size:0.82rem;">Device Type</label>
                        <select name="device" class="form-select form-select-sm">
                            <option value="all" <?php if($filter_device === '') echo 'selected'; ?>>All Devices</option>
                            <option value="Desktop" <?php if($filter_device === 'Desktop') echo 'selected'; ?>>Desktop</option>
                            <option value="Mobile"  <?php if($filter_device === 'Mobile')  echo 'selected'; ?>>Mobile</option>
                            <option value="Tablet"  <?php if($filter_device === 'Tablet')  echo 'selected'; ?>>Tablet</option>
                            <option value="Bot"     <?php if($filter_device === 'Bot')     echo 'selected'; ?>>Bot</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label fw-semibold" style="font-size:0.82rem;">Date</label>
                        <input type="date" name="date" class="form-control form-control-sm"
                            value="<?php echo htmlspecialchars($filter_date); ?>">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-semibold" style="font-size:0.82rem;">Page URL (contains)</label>
                        <input type="text" name="purl" class="form-control form-control-sm"
                            placeholder="e.g. /products.php" value="<?php echo htmlspecialchars($filter_page); ?>">
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-dark w-100">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="visitor-logs.php" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Logs Table -->
            <div class="bg-white rounded shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-secondary mb-0">
                        Showing <strong><?php echo number_format($total_rows); ?></strong> log entries
                        <?php if ($filter_ip || $filter_device || $filter_date || $filter_page): ?>
                            <span class="badge bg-info ms-2" style="font-size:0.7rem;">Filtered</span>
                        <?php endif; ?>
                    </h6>
                    <small class="text-muted">Page <?php echo $page; ?> of <?php echo $total_pages; ?></small>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="font-size: 0.82rem;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>IP Address</th>
                                <th>Device</th>
                                <th>Browser</th>
                                <th>OS</th>
                                <th>Page Visited</th>
                                <th>Referrer</th>
                                <th>Date &amp; Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-database me-2"></i>No logs found matching your filters.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php
                            $device_icon = ['Desktop'=>'fa-desktop', 'Mobile'=>'fa-mobile-alt', 'Tablet'=>'fa-tablet-alt', 'Bot'=>'fa-robot'];
                            $device_col  = ['Desktop'=>'text-primary', 'Mobile'=>'text-info', 'Tablet'=>'text-warning', 'Bot'=>'text-secondary'];
                            $browser_col = ['Chrome'=>'#4285F4','Firefox'=>'#FF7139','Safari'=>'#006CFF','Edge'=>'#0078D7','Opera'=>'#FF1B2D'];
                            $counter = $offset + 1;
                            foreach ($logs as $log):
                                $icon  = $device_icon[$log['device_type']] ?? 'fa-globe';
                                $dcol  = $device_col[$log['device_type']] ?? 'text-muted';
                                $bcol  = $browser_col[$log['browser']] ?? '#636e72';
                                $page_display = $log['page_url'];
                                if (strlen($page_display) > 45) $page_display = substr($page_display, 0, 42) . '...';
                                $ref_display = $log['referrer'] ?: 'Direct';
                                if (strlen($ref_display) > 30) $ref_display = substr($ref_display, 0, 27) . '...';
                            ?>
                            <tr>
                                <td class="text-muted"><?php echo $counter++; ?></td>
                                <td>
                                    <code style="font-size:0.77rem; background:#f8f9fa; padding:2px 6px; border-radius:3px;">
                                        <?php echo htmlspecialchars($log['ip_address']); ?>
                                    </code>
                                </td>
                                <td>
                                    <i class="fas <?php echo $icon; ?> <?php echo $dcol; ?> me-1"></i>
                                    <?php echo htmlspecialchars($log['device_type']); ?>
                                </td>
                                <td>
                                    <span style="color:<?php echo $bcol; ?>; font-weight:600;">
                                        <?php echo htmlspecialchars($log['browser']); ?>
                                    </span>
                                </td>
                                <td class="text-muted"><?php echo htmlspecialchars($log['os']); ?></td>
                                <td>
                                    <span title="<?php echo htmlspecialchars($log['page_url']); ?>" style="cursor:help;">
                                        <?php echo htmlspecialchars($page_display); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted" title="<?php echo htmlspecialchars($log['referrer']); ?>" style="cursor:help;">
                                        <?php echo htmlspecialchars($ref_display); ?>
                                    </span>
                                </td>
                                <td class="text-muted">
                                    <?php echo date('d M Y, h:i A', strtotime($log['created_at'])); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav class="mt-3">
                    <ul class="pagination pagination-sm justify-content-center mb-0">
                        <?php
                        // Build base query string without page
                        $qp = array_filter(['ip'=>$filter_ip,'device'=>$filter_device,'date'=>$filter_date,'purl'=>$filter_page]);
                        $qs = $qp ? ('&' . http_build_query($qp)) : '';
                        ?>
                        <li class="page-item <?php if($page<=1) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo $page-1; ?><?php echo $qs; ?>">‹ Prev</a>
                        </li>
                        <?php
                        $start = max(1, $page - 2);
                        $end   = min($total_pages, $page + 2);
                        if ($start > 1): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif;
                        for ($i = $start; $i <= $end; $i++): ?>
                            <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo $qs; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor;
                        if ($end < $total_pages): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                        <li class="page-item <?php if($page>=$total_pages) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo $page+1; ?><?php echo $qs; ?>">Next ›</a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>

        </div><!-- /container-fluid -->
    </div><!-- /page-content-wrapper -->
</div><!-- /wrapper -->

<!-- Bootstrap Bundle JS (Local) -->
<script src="../assets/vendor/js/bootstrap.bundle.min.js"></script>
</body>
</html>

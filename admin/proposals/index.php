<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$page_title = 'B2B Corporate Proposals';

// 1. Handle POST Actions (AJAX for Status Change or Deletion)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if ($id > 0) {
        if ($action === 'update_status') {
            $status = isset($_POST['status']) ? trim($_POST['status']) : 'pending';
            $status_esc = $conn->real_escape_string($status);
            
            $update_sql = "UPDATE `corporate_gift_proposals` SET `status` = '$status_esc' WHERE `id` = $id";
            if ($conn->query($update_sql)) {
                echo json_encode(['status' => 'success', 'message' => 'Status updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update database: ' . $conn->error]);
            }
            exit;
        } elseif ($action === 'delete') {
            $delete_sql = "DELETE FROM `corporate_gift_proposals` WHERE `id` = $id";
            if ($conn->query($delete_sql)) {
                echo json_encode(['status' => 'success', 'message' => 'Proposal request deleted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete record: ' . $conn->error]);
            }
            exit;
        }
    }
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters.']);
    exit;
}

// 2. Search & Filter Parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';

$where_clauses = [];
if (!empty($search)) {
    $search_esc = $conn->real_escape_string($search);
    $where_clauses[] = "(`name` LIKE '%$search_esc%' OR `company` LIKE '%$search_esc%' OR `email` LIKE '%$search_esc%' OR `phone` LIKE '%$search_esc%')";
}
if (!empty($status_filter)) {
    $status_esc = $conn->real_escape_string($status_filter);
    $where_clauses[] = "`status` = '$status_esc'";
}

$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// 3. Pagination Configuration
$limit = 15; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$count_sql = "SELECT COUNT(*) FROM `corporate_gift_proposals` $where_sql";
$count_res = $conn->query($count_sql);
$total_rows = $count_res->fetch_row()[0];
$total_pages = ceil($total_rows / $limit);

// 4. Fetch Results
$sql = "SELECT * FROM `corporate_gift_proposals` $where_sql ORDER BY `created_at` DESC LIMIT $start, $limit";
$result = $conn->query($sql);

// 5. Gather Dashboard KPI Stats
$stats_sql = "SELECT `status`, COUNT(*) as cnt FROM `corporate_gift_proposals` GROUP BY `status`";
$stats_res = $conn->query($stats_sql);
$cnt_pending = 0;
$cnt_contacted = 0;
$cnt_completed = 0;
$cnt_total = 0;

while ($row = $stats_res->fetch_assoc()) {
    $cnt_total += $row['cnt'];
    if ($row['status'] === 'pending') {
        $cnt_pending = $row['cnt'];
    } elseif ($row['status'] === 'contacted') {
        $cnt_contacted = $row['cnt'];
    } elseif ($row['status'] === 'completed') {
        $cnt_completed = $row['cnt'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B2B Gift Proposals - Amadika Admin</title>
    <!-- Assets -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https://via.placeholder.com;">
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
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
        .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.02); }
        
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
            padding: 8px 10px !important;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
        }
        .report-table td, 
        .report-table td div,
        .report-table td span,
        .report-table th {
            font-size: 11px !important;
        }
        
        .truncate-text {
            max-width: 150px;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            display: block;
        }
        
        /* Status Badges */
        .status-badge {
            font-size: 9px !important;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 8px;
            border-radius: 4px;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        .status-pending { background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .status-contacted { background-color: rgba(54, 162, 235, 0.1); color: #36a2eb; }
        .status-completed { background-color: rgba(75, 192, 192, 0.1); color: #4bc0c0; }
        .status-archived { background-color: rgba(153, 102, 255, 0.1); color: #9966ff; }
        
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
                        <h2 class="h3 fw-bold text-secondary mb-1">Corporate Gift Proposals</h2>
                        <p class="text-muted mb-0">Review and manage premium B2B custom gifting inquiries.</p>
                    </div>
                </div>

                <!-- KPI Summary Dashboard Cards -->
                <div class="row g-3 mb-4">
                    <!-- Total Enquiries -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="card kpi-card bg-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="kpi-label mb-1 d-block">Total Requests</span>
                                    <h3 class="kpi-value mb-0"><?php echo $cnt_total; ?></h3>
                                </div>
                                <div class="kpi-icon icon-primary">
                                    <i class="fas fa-inbox"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pending Action -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="card kpi-card bg-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="kpi-label mb-1 d-block">Pending Review</span>
                                    <h3 class="kpi-value mb-0 text-warning"><?php echo $cnt_pending; ?></h3>
                                </div>
                                <div class="kpi-icon icon-warning">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Contacted Leads -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="card kpi-card bg-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="kpi-label mb-1 d-block">In Discussion</span>
                                    <h3 class="kpi-value mb-0 text-info"><?php echo $cnt_contacted; ?></h3>
                                </div>
                                <div class="kpi-icon icon-success">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Completed Deals -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="card kpi-card bg-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="kpi-label mb-1 d-block">Converted Deals</span>
                                    <h3 class="kpi-value mb-0 text-success"><?php echo $cnt_completed; ?></h3>
                                </div>
                                <div class="kpi-icon icon-danger">
                                    <i class="fas fa-check-double"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                <div class="card card-custom p-4 mb-4">
                    <form method="GET" class="row align-items-end g-3">
                        <div class="col-12 col-md-5">
                            <label class="form-label fw-semibold text-secondary">Search Proposals</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by name, company, email, phone..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold text-secondary">Filter by Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" <?php echo ($status_filter === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="contacted" <?php echo ($status_filter === 'contacted') ? 'selected' : ''; ?>>Contacted</option>
                                <option value="completed" <?php echo ($status_filter === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                <option value="archived" <?php echo ($status_filter === 'archived') ? 'selected' : ''; ?>>Archived</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-outline-gold w-100 py-2">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="index.php" class="btn btn-light border w-100 py-2 text-center text-decoration-none text-secondary">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- proposals Table Card -->
                <div class="card card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fs-5 text-secondary mb-0 fw-semibold">B2B Enquiries Log</h4>
                        <div class="text-muted text-xs">Page records: <?php echo $result ? $result->num_rows : 0; ?> / Total: <?php echo $total_rows; ?></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle report-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date Sent</th>
                                    <th>Contact Name</th>
                                    <th>Company</th>
                                    <th>Email / Phone</th>
                                    <th>Qty Range</th>
                                    <th>Occasion</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result && $result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <tr id="proposal-row-<?php echo $row['id']; ?>">
                                            <td><span class="fw-bold">#<?php echo $row['id']; ?></span></td>
                                            <td><span class="text-secondary"><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></span></td>
                                            <td><span class="fw-medium text-dark truncate-text" title="<?php echo htmlspecialchars($row['name']); ?>"><?php echo htmlspecialchars($row['name']); ?></span></td>
                                            <td><span class="fw-medium text-secondary truncate-text" title="<?php echo htmlspecialchars($row['company']); ?>"><?php echo htmlspecialchars($row['company']); ?></span></td>
                                            <td>
                                                <div class="fw-medium text-dark truncate-text" title="<?php echo htmlspecialchars($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></div>
                                                <div class="text-muted text-xs"><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></div>
                                            </td>
                                            <td><span class="badge bg-light text-dark border px-2 py-1"><?php echo htmlspecialchars($row['quantity'] ?? 'Not specified'); ?></span></td>
                                            <td><span class="text-secondary"><?php echo htmlspecialchars($row['occasion'] ?? 'Other'); ?></span></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $row['status']; ?>" id="badge-status-<?php echo $row['id']; ?>">
                                                    <?php echo htmlspecialchars($row['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button type="button" class="btn btn-sm btn-outline-primary px-2" 
                                                            onclick="viewDetails(this)"
                                                            data-id="<?php echo $row['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                                            data-company="<?php echo htmlspecialchars($row['company']); ?>"
                                                            data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                                            data-phone="<?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?>"
                                                            data-quantity="<?php echo htmlspecialchars($row['quantity'] ?? 'Not specified'); ?>"
                                                            data-occasion="<?php echo htmlspecialchars($row['occasion'] ?? 'Other'); ?>"
                                                            data-message="<?php echo htmlspecialchars($row['message'] ?? 'No additional details provided.'); ?>"
                                                            data-created="<?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?>"
                                                            data-status="<?php echo htmlspecialchars($row['status']); ?>">
                                                        <i class="far fa-eye"></i> Details
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger px-2" onclick="deleteProposal(<?php echo $row['id']; ?>)">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fs-1 d-block mb-3 opacity-25"></i>
                                            No corporate proposals found matching the filters.
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
                                    <a class="page-link" href="?search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&page=<?php echo $page-1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for($p=1; $p<=$total_pages; $p++): ?>
                                <li class="page-item <?php echo ($p == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&page=<?php echo $p; ?>"><?php echo $p; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&page=<?php echo $page+1; ?>" aria-label="Next">
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

    <!-- Detail Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom bg-light px-4" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="modal-title fw-bold text-secondary" id="detailsModalLabel">Proposal Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <span class="text-muted text-[10px] uppercase fw-semibold tracking-wider d-block mb-1">Customer Name</span>
                            <div class="fw-bold text-dark fs-6" id="modal-name">Anurag Singh</div>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted text-[10px] uppercase fw-semibold tracking-wider d-block mb-1">Company Name</span>
                            <div class="fw-bold text-secondary fs-6" id="modal-company">Your Organisation</div>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted text-[10px] uppercase fw-semibold tracking-wider d-block mb-1">Email Address</span>
                            <div><a href="" id="modal-email" class="text-primary text-decoration-none">you@company.com</a></div>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted text-[10px] uppercase fw-semibold tracking-wider d-block mb-1">Phone Number</span>
                            <div class="fw-semibold text-dark" id="modal-phone">+91 98765 43210</div>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted text-[10px] uppercase fw-semibold tracking-wider d-block mb-1">Quantity Range</span>
                            <span class="badge bg-light text-dark border px-2.5 py-1.5" id="modal-quantity">100 - 250 items</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted text-[10px] uppercase fw-semibold tracking-wider d-block mb-1">Occasion</span>
                            <div class="fw-semibold text-dark" id="modal-occasion">Diwali Gifting</div>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted text-[10px] uppercase fw-semibold tracking-wider d-block mb-1">Date Submitted</span>
                            <div class="text-secondary" id="modal-date">30 May 2026</div>
                        </div>
                    </div>
                    
                    <div class="mb-4 bg-light p-3 rounded-3">
                        <span class="text-muted text-[10px] uppercase fw-semibold tracking-wider d-block mb-2">Additional details / Custom Instructions</span>
                        <div class="text-secondary whitespace-pre-wrap font-sans" id="modal-message" style="font-size: 12px; line-height: 1.6; white-space: pre-wrap;">
                            Please provide details...
                        </div>
                    </div>

                    <div class="border-top pt-4">
                        <form id="statusUpdateForm" onsubmit="updateProposalStatus(event)" class="row align-items-center g-3">
                            <input type="hidden" id="modal-proposal-id">
                            <div class="col-md-6">
                                <label class="form-label text-muted text-[10px] uppercase fw-bold tracking-wider mb-1">Update Lead Status</label>
                                <select id="modal-status-select" class="form-select">
                                    <option value="pending">Pending</option>
                                    <option value="contacted">Contacted / In Discussion</option>
                                    <option value="completed">Completed / Converted</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end pt-3">
                                <button type="submit" class="btn btn-gold w-100 py-2">
                                    Update Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    
    <script>
        let detailsModalObj = null;

        document.addEventListener('DOMContentLoaded', () => {
            detailsModalObj = new bootstrap.Modal(document.getElementById('detailsModal'));
        });

        function viewDetails(btn) {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const company = btn.getAttribute('data-company');
            const email = btn.getAttribute('data-email');
            const phone = btn.getAttribute('data-phone');
            const quantity = btn.getAttribute('data-quantity');
            const occasion = btn.getAttribute('data-occasion');
            const message = btn.getAttribute('data-message');
            const created = btn.getAttribute('data-created');
            const status = btn.getAttribute('data-status');

            // Populate fields
            document.getElementById('modal-proposal-id').value = id;
            document.getElementById('modal-name').innerText = name;
            document.getElementById('modal-company').innerText = company;
            document.getElementById('modal-email').innerText = email;
            document.getElementById('modal-email').href = `mailto:${email}`;
            document.getElementById('modal-phone').innerText = phone;
            document.getElementById('modal-quantity').innerText = quantity;
            document.getElementById('modal-occasion').innerText = occasion;
            document.getElementById('modal-date').innerText = created;
            document.getElementById('modal-message').innerText = message;
            document.getElementById('modal-status-select').value = status;

            detailsModalObj.show();
        }

        function updateProposalStatus(e) {
            e.preventDefault();
            const id = document.getElementById('modal-proposal-id').value;
            const status = document.getElementById('modal-status-select').value;

            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('id', id);
            formData.append('status', status);

            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update Badge in table
                    const badge = document.getElementById(`badge-status-${id}`);
                    if (badge) {
                        badge.className = `status-badge status-${status}`;
                        badge.innerText = status;
                    }
                    
                    // Update trigger button attribute
                    const btn = document.querySelector(`button[data-id="${id}"]`);
                    if (btn) {
                        btn.setAttribute('data-status', status);
                    }

                    detailsModalObj.hide();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: 'Proposal status has been updated.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: data.message || 'Status update failed.'
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Connection error while updating status.'
                });
            });
        }

        function deleteProposal(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to permanently delete this proposal request?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('action', 'delete');
                    formData.append('id', id);

                    fetch('index.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const row = document.getElementById(`proposal-row-${id}`);
                            if (row) {
                                row.style.transition = 'all 0.5s ease';
                                row.style.opacity = '0';
                                setTimeout(() => {
                                    row.remove();
                                }, 500);
                            }
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Proposal has been deleted.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to delete proposal.'
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Connection error while deleting proposal.'
                        });
                    });
                }
            });
        }
    </script>
</body>
</html>

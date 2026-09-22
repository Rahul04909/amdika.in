<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$page_title = 'Manage Support Tickets';

// Pagination
$limit = 20; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Fetch Tickets with User Info
$sql = "SELECT t.*, u.name as user_name, u.email as user_email 
        FROM support_tickets t 
        LEFT JOIN users u ON t.user_id = u.id 
        ORDER BY t.created_at DESC LIMIT $start, $limit";
$result = $conn->query($sql);

// Count Total
$total_res = $conn->query("SELECT COUNT(id) FROM support_tickets");
$total_rows = $total_res->fetch_row()[0];
$total_pages = ceil($total_rows / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Tickets - Amadika Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Assets -->
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/images/amdika-logo.png">

    <style>
        body { font-family: 'Rubik', sans-serif; background-color: #f5f7fa; }
        .wrapper { display: flex; }
        #page-content-wrapper { width: 100%; padding: 0; }
        
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; padding: 0; overflow: hidden; }
        .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #e9ecef; font-weight: 600; color: #495057; padding: 15px; }
        .table tbody td { padding: 15px; vertical-align: middle; }
        
        .status-badge { font-size: 0.8rem; padding: 6px 12px; border-radius: 20px; font-weight: 500; }
        .status-open { background-color: #e3fcf7; color: #00b894; }
        .status-progress { background-color: #fff3cd; color: #ffc107; }
        .status-closed { background-color: #ffe5e5; color: #ff4757; }
        
        .ticket-id { font-weight: 600; color: #2F3A3F; }
        .user-meta { font-size: 13px; color: #777; }
        
        .btn-view {
            padding: 6px 15px;
            font-size: 13px;
            font-weight: 500;
            border-radius: 6px;
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border: none;
            text-decoration: none;
            transition: 0.2s;
        }
        .btn-view:hover { background: #0d6efd; color: #fff; }
        
        /* Pagination */
        .page-link { color: #333; border: none; margin: 0 5px; border-radius: 50% !important; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; }
        .page-item.active .page-link { background-color: #0d6efd; color: #fff; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
             <?php include '../../admin/includes/header.php'; ?>
             
             <div class="container-fluid px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h3 fw-bold text-secondary mb-0">Support Tickets</h2>
                        <p class="text-muted mb-0">Manage customer queries and issues</p>
                    </div>
                    <div class="badge bg-white text-secondary border p-2 px-3 rounded-pill shadow-sm">
                        Total Tickets: <strong><?php echo $total_rows; ?></strong>
                    </div>
                </div>

                <div class="card card-custom">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Ticket Info</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result && $result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <?php 
                                            $statusClass = 'status-open';
                                            if($row['status'] == 'Closed') $statusClass = 'status-closed';
                                            elseif($row['status'] == 'In Progress') $statusClass = 'status-progress';
                                        ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="ticket-id mb-1"><?php echo htmlspecialchars($row['ticket_no']); ?></div>
                                                <div class="text-secondary small fw-bold"><?php echo htmlspecialchars(substr($row['subject'], 0, 40)) . (strlen($row['subject']) > 40 ? '...' : ''); ?></div>
                                            </td>
                                            <td>
                                                <div class="fw-medium text-dark"><?php echo htmlspecialchars($row['user_name']); ?></div>
                                                <div class="user-meta"><?php echo htmlspecialchars($row['user_email']); ?></div>
                                            </td>
                                            <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $row['status']; ?></span></td>
                                            <td class="text-secondary small">
                                                <i class="far fa-clock me-1"></i> <?php echo date('d M, h:i A', strtotime($row['updated_at'])); ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="view-ticket.php?id=<?php echo $row['id']; ?>" class="btn-view">
                                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No tickets found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if($total_pages > 1): ?>
                    <div class="p-4 border-top">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end mb-0">
                                <?php for($p=1; $p<=$total_pages; $p++): ?>
                                    <li class="page-item <?php echo ($p == $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $p; ?>"><?php echo $p; ?></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
             </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
</body>
</html>

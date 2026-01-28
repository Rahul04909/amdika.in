<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$page_title = 'Manage Users';

// Pagination Setup
$limit = 20; // Users per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Get Total Users Count
$total_sql = "SELECT COUNT(id) FROM users";
$total_res = $conn->query($total_sql);
$total_rows = $total_res->fetch_row()[0];
$total_pages = ceil($total_rows / $limit);

// Fetch Users with Limit
$sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT $start, $limit";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Amadika Admin</title>
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
        .user-initial { width: 40px; height: 40px; background: #e3f2fd; color: #0d6efd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px; margin-right: 15px; }
        
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
                    <h2 class="h3 fw-bold text-secondary mb-0">Registered Users</h2>
                    <div class="text-muted">Total: <?php echo $total_rows; ?> Users</div>
                </div>

                <div class="card card-custom p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="80">Sr. No.</th>
                                    <th>User Details</th>
                                    <th>Contact Info</th>
                                    <th>Location</th>
                                    <th>Registered Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result && $result->num_rows > 0): ?>
                                    <?php 
                                    $i = $start + 1;
                                    while($row = $result->fetch_assoc()): 
                                    ?>
                                        <tr>
                                            <td><span class="fw-bold text-muted">#<?php echo $i++; ?></span></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-initial">
                                                        <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['name']); ?></div>
                                                        <small class="text-muted">ID: <?php echo $row['id']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-dark"><i class="fas fa-envelope text-muted me-2"></i><?php echo htmlspecialchars($row['email']); ?></div>
                                                <div class="text-muted mt-1 small"><i class="fas fa-phone-alt text-muted me-2"></i><?php echo htmlspecialchars($row['mobile']); ?></div>
                                            </td>
                                            <td>
                                                <div class="text-dark"><?php echo htmlspecialchars($row['city']); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($row['state']); ?>, <?php echo htmlspecialchars($row['country']); ?></small>
                                            </td>
                                            <td>
                                                <div class="text-secondary">
                                                    <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                                                </div>
                                                <small class="text-muted"><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-users fs-1 d-block mb-3 opacity-25"></i>
                                            No users registered yet.
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

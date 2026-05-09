<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

// Fetch Deletion Logs with Category Names
$sql = "SELECT * FROM product_deletion_log ORDER BY deleted_at DESC";
$result = $conn->query($sql);

$page_title = 'Product Deletion Audit Log';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Audit Logs - Amadika Admin</title>
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/images/amdika-logo.png">

    <style>
        :root {
            --excel-green: #107c41;
            --excel-light-green: #e9f5ee;
            --border-color: #e0e0e0;
        }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #333; }
        .wrapper { display: flex; }
        #page-content-wrapper { width: 100%; padding: 0; }
        
        .audit-card { 
            border: 1px solid var(--border-color); 
            border-radius: 8px; 
            background: #fff; 
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        
        .excel-table { margin-bottom: 0; font-size: 0.9rem; }
        .excel-table thead th { 
            background: #f3f3f3; 
            color: #555; 
            font-weight: 600; 
            border-bottom: 2px solid var(--border-color);
            padding: 12px 15px;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        .excel-table tbody td { 
            padding: 12px 15px; 
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        .excel-table tbody tr:hover { background-color: var(--excel-light-green); }
        
        .ip-address { 
            background: #f1f3f4; 
            color: #5f6368; 
            padding: 2px 8px; 
            border-radius: 4px; 
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.85rem;
        }
        .time-cell { color: #70757a; font-weight: 500; }
        .product-name { color: #1a73e8; font-weight: 600; }
        
        .btn-snapshot {
            color: var(--excel-green);
            background: var(--excel-light-green);
            border: 1px solid #c6e3d2;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-snapshot:hover {
            background: var(--excel-green);
            color: #fff;
        }
        
        .header-bar {
            background: var(--excel-green);
            color: white;
            padding: 20px 30px;
            margin: -24px -24px 24px -24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>

            <div class="container-fluid px-4 py-4">
                <div class="audit-card">
                    <div class="header-bar">
                        <div>
                            <h4 class="mb-0 fw-bold"><i class="fas fa-file-excel me-2"></i> Product Deletion Master Log</h4>
                            <p class="mb-0 small opacity-75">Full audit trail of all deleted inventory items.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-sm fw-bold" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>Print Report
                            </button>
                        </div>
                    </div>

                    <div class="p-4 pt-2">
                        <div class="table-responsive">
                            <table class="table excel-table">
                                <thead>
                                    <tr>
                                        <th width="100">Deletion ID</th>
                                        <th width="120">Product ID</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>IP Address</th>
                                        <th>Deletion Time</th>
                                        <th class="text-end">Full Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($result && $result->num_rows > 0): ?>
                                        <?php while($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td class="text-muted small">DEL-<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                                <td><span class="fw-bold">#<?php echo $row['product_id']; ?></span></td>
                                                <td><span class="product-name"><?php echo htmlspecialchars($row['product_name']); ?></span></td>
                                                <td><span class="badge rounded-pill bg-light text-secondary border"><?php echo htmlspecialchars($row['category_name']); ?></span></td>
                                                <td><span class="ip-address"><?php echo $row['deleted_by_ip']; ?></span></td>
                                                <td class="time-cell"><?php echo date('Y-m-d | h:i:s A', strtotime($row['deleted_at'])); ?></td>
                                                <td class="text-end">
                                                    <button class="btn-snapshot" onclick='viewSnapshot(<?php echo json_encode($row['full_snapshot']); ?>)'>
                                                        <i class="fas fa-search-plus me-1"></i>View JSON
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="opacity-50 mb-3"><i class="fas fa-clipboard-list fa-3x"></i></div>
                                                <p class="text-muted">No deletion logs found. Future deletions will appear here in real-time.</p>
                                            </td>
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

    <!-- Modal -->
    <div class="modal fade" id="snapshotModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Data Snapshot (Recovery Source)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <pre id="snapshotContent" class="mb-0 p-4 bg-light font-monospace" style="max-height: 500px; overflow-y: auto; font-size: 0.85rem;"></pre>
                </div>
                <div class="modal-footer bg-light">
                    <p class="small text-muted me-auto">Copy this data to manually restore the product if needed.</p>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script>
        const modal = new bootstrap.Modal(document.getElementById('snapshotModal'));
        function viewSnapshot(data) {
            try {
                const jsonObj = typeof data === 'string' ? JSON.parse(data) : data;
                document.getElementById('snapshotContent').textContent = JSON.stringify(jsonObj, null, 4);
                modal.show();
            } catch (e) {
                console.error(e);
                alert('Error displaying data');
            }
        }
    </script>
</body>
</html>

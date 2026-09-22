<?php
// Auth & Session
require_once '../includes/session_config.php';
// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$page_title = 'My Orders';
require_once '../database/db_config.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch Orders
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
    body { background-color: #f8f9fa; }
    .dashboard-container { padding: 40px 0; }
    
    .content-box {
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); /* Soft premium shadow */
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        padding: 30px;
        margin-bottom: 24px;
        min-height: 400px;
    }
    
    .section-header { 
        font-size: 20px; 
        font-weight: 600; 
        margin-bottom: 24px; 
        color: #2F3A3F;
        display: flex; 
        gap: 15px; 
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 15px;
    }
    
    /* Order Item Style */
    .order-card-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border: 1px solid #eee;
        border-radius: 8px;
        margin-bottom: 15px;
        background: #fff;
        transition: all 0.2s;
    }
    .order-card-row:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-color: #e0e0e0;
        transform: translateY(-2px);
    }
    
    .order-info {
        display: flex;
        gap: 30px;
        align-items: center;
    }
    
    .order-meta label { font-size: 11px; color: #878787; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px; }
    .order-meta span { font-size: 14px; font-weight: 600; color: #212121; }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-paid { background: #e3fcf7; color: #00b894; }
    .status-failed { background: #ffe5e5; color: #ff4757; }
    .status-pending { background: #fff3cd; color: #ffc107; }

    .btn-invoice {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-size: 14px;
        color: #2F6FED;
        background: #f0f7ff;
        border: 1px solid transparent;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: 0.2s;
    }
    .btn-invoice:hover {
        background: #2F6FED;
        color: #fff;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    .empty-state i { font-size: 48px; margin-bottom: 15px; opacity: 0.5; }
    
    @media (max-width: 768px) {
        .order-card-row { flex-direction: column; align-items: flex-start; gap: 15px; }
        .order-info { flex-wrap: wrap; gap: 15px; width: 100%; }
        .order-meta { width: 45%; }
        .btn-invoice { width: 100%; justify-content: center; }
    }
</style>

<div class="container dashboard-container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-12 mb-4">
            <?php include 'includes/sidebar.php'; ?>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9 col-md-12">
            <div class="content-box">
                <div class="section-header">
                    <span>My Orders</span>
                </div>
                
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <?php 
                            $statusClass = 'status-pending';
                            if(strtolower($row['payment_status']) == 'paid') $statusClass = 'status-paid';
                            elseif(strtolower($row['payment_status']) == 'failed') $statusClass = 'status-failed';
                        ?>
                        <div class="order-card-row">
                            <div class="order-info">
                                <div class="order-meta">
                                    <label>Order ID</label>
                                    <span><?php echo $row['order_no']; ?></span>
                                </div>
                                <div class="order-meta">
                                    <label>Date</label>
                                    <span><?php echo date('d M Y', strtotime($row['created_at'])); ?></span>
                                </div>
                                <div class="order-meta">
                                    <label>Total Amount</label>
                                    <span>₹<?php echo number_format($row['final_amount'], 2); ?></span>
                                </div>
                                <div class="order-meta">
                                    <label>Status</label>
                                    <span class="status-badge <?php echo $statusClass; ?> order-status"><?php echo ucfirst($row['payment_status']); ?></span>
                                </div>
                            </div>
                            
                            <a href="download-invoice.php?id=<?php echo $row['id']; ?>" class="btn-invoice" target="_blank">
                                <i class="fas fa-file-invoice"></i> Download Invoice
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag"></i>
                        <h4>No orders found</h4>
                        <p>It looks like you haven't placed any orders yet.</p>
                        <a href="../index.php" class="btn btn-primary mt-3" style="background:var(--accent-gold); border:none; color:#fff;">Start Shopping</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

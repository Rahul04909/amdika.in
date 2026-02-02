<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$ticket_id = intval($_GET['id']);

// Handle Status Update
if (isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    $conn->query("UPDATE support_tickets SET status = '$new_status', updated_at = NOW() WHERE id = $ticket_id");
    header("Location: view-ticket.php?id=$ticket_id&msg=updated");
    exit;
}

// Handle Reply
if (isset($_POST['send_reply'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $sender = 'Admin';
        $stmt = $conn->prepare("INSERT INTO support_replies (ticket_id, sender_type, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $ticket_id, $sender, $message);
        $stmt->execute();
        
        // Auto-update status to In Progress if currently Open
        $conn->query("UPDATE support_tickets SET status = 'In Progress', updated_at = NOW() WHERE id = $ticket_id AND status = 'Open'");
        
        header("Location: view-ticket.php?id=$ticket_id&msg=sent");
        exit;
    }
}

// Fetch Ticket
$sql = "SELECT t.*, u.name as user_name, u.email as user_email, u.mobile as user_mobile 
        FROM support_tickets t 
        LEFT JOIN users u ON t.user_id = u.id 
        WHERE t.id = $ticket_id";
$ticket = $conn->query($sql)->fetch_assoc();

if (!$ticket) die("Ticket not found");

// Fetch Replies
$replies = $conn->query("SELECT * FROM support_replies WHERE ticket_id = $ticket_id ORDER BY created_at ASC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket #<?php echo $ticket['ticket_no']; ?> - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Rubik', sans-serif; background-color: #f5f7fa; }
        .wrapper { display: flex; }
        #page-content-wrapper { width: 100%; padding: 0; }
        
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; padding: 0; overflow: hidden; }
        
        /* Chat Styles */
        .chat-container { background: #fdfdfd; padding: 30px; max-height: 500px; overflow-y: auto; border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0; }
        .message-row { display: flex; margin-bottom: 20px; }
        .message-row.admin { justify-content: flex-end; }
        .message-row.user { justify-content: flex-start; }
        
        .message-bubble { max-width: 75%; padding: 15px 20px; border-radius: 12px; font-size: 14px; position: relative; }
        
        .admin .message-bubble { background: #e3f2fd; color: #0d47a1; border-bottom-right-radius: 2px; }
        .user .message-bubble { background: #f5f5f5; color: #333; border-bottom-left-radius: 2px; border: 1px solid #eee; }
        
        .message-meta { font-size: 11px; margin-top: 5px; opacity: 0.7; }
        .admin .message-meta { text-align: right; }
        
        /* Sidebar Info */
        .user-info-card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
        .info-label { font-size: 12px; color: #888; text-transform: uppercase; font-weight: 500; margin-bottom: 4px; }
        .info-value { font-size: 14px; color: #333; font-weight: 500; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
             <?php include '../../admin/includes/header.php'; ?>
             
             <div class="container-fluid px-4 py-4">
                 
                <div class="mb-4">
                    <a href="index.php" class="text-decoration-none text-secondary fw-medium"><i class="fas fa-arrow-left me-1"></i> Back to Tickets</a>
                </div>

                <div class="row">
                    <!-- Chat Section -->
                    <div class="col-lg-8">
                        <div class="card card-custom mb-4">
                            <div class="card-header bg-white p-4 border-0">
                                <h4 class="mb-1 fw-bold text-secondary"><?php echo htmlspecialchars($ticket['subject']); ?></h4>
                                <div class="text-muted small">
                                    Ticket ID: <span class="text-dark fw-bold">#<?php echo $ticket['ticket_no']; ?></span> | 
                                    Created: <?php echo date('d M Y, h:i A', strtotime($ticket['created_at'])); ?>
                                </div>
                            </div>
                            
                            <div class="chat-container">
                                <?php while($reply = $replies->fetch_assoc()): ?>
                                    <div class="message-row <?php echo strtolower($reply['sender_type']); ?>">
                                        <div class="message-bubble">
                                            <div><?php echo nl2br(htmlspecialchars($reply['message'])); ?></div>
                                            <div class="message-meta">
                                                <?php echo $reply['sender_type']; ?> • <?php echo date('d M, h:i A', strtotime($reply['created_at'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            
                            <div class="card-body p-4 bg-light">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase">Reply to Customer</label>
                                        <textarea class="form-control" name="message" rows="4" placeholder="Type your reply..." required></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-check form-switch">
                                            <!-- Optional: Send Email Notification Toggle -->
                                        </div>
                                        <button type="submit" name="send_reply" class="btn btn-primary px-4"><i class="fas fa-paper-plane me-2"></i> Send Reply</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar Info -->
                    <div class="col-lg-4">
                        <div class="user-info-card mb-4">
                            <h5 class="fw-bold mb-4 text-secondary">Ticket Info</h5>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="info-label">Current Status</label>
                                    <select class="form-select form-select-sm" name="status">
                                        <option value="Open" <?php echo $ticket['status']=='Open'?'selected':''; ?>>Open</option>
                                        <option value="In Progress" <?php echo $ticket['status']=='In Progress'?'selected':''; ?>>In Progress</option>
                                        <option value="Closed" <?php echo $ticket['status']=='Closed'?'selected':''; ?>>Closed</option>
                                    </select>
                                </div>
                                <button type="submit" name="update_status" class="btn btn-sm btn-outline-secondary w-100">Update Status</button>
                            </form>
                            
                            <hr class="my-4">
                            
                            <h6 class="fw-bold mb-3 text-secondary">Customer Details</h6>
                            
                            <div class="info-label">Name</div>
                            <div class="info-value"><?php echo htmlspecialchars($ticket['user_name']); ?></div>
                            
                            <div class="info-label">Email</div>
                            <div class="info-value"><?php echo htmlspecialchars($ticket['user_email']); ?></div>
                            
                            <div class="info-label">Phone</div>
                            <div class="info-value"><?php echo htmlspecialchars($ticket['user_mobile']); ?></div>
                            
                        </div>
                    </div>
                </div>

             </div>
        </div>
    </div>
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
</body>
</html>

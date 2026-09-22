<?php
// Auth & Session
require_once '../includes/session_config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: support.php");
    exit;
}

$ticket_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

require_once '../database/db_config.php';
include '../includes/header.php';

// Fetch Ticket
$stmt = $conn->prepare("SELECT * FROM support_tickets WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $ticket_id, $user_id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

if (!$ticket) {
    echo "<div class='container py-5'><h3>Ticket not found or access denied.</h3></div>";
    include '../includes/footer.php';
    exit;
}

// Fetch Replies
$r_stmt = $conn->prepare("SELECT * FROM support_replies WHERE ticket_id = ? ORDER BY created_at ASC");
$r_stmt->bind_param("i", $ticket_id);
$r_stmt->execute();
$replies = $r_stmt->get_result();
?>

<style>
    body { background-color: #f8f9fa; }
    .dashboard-container { padding: 40px 0; }
    
    .content-box {
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        padding: 0; /* Full width header */
        overflow: hidden;
    }
    
    .ticket-header {
        padding: 20px 30px;
        border-bottom: 1px solid #eee;
        background: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .chat-container {
        padding: 30px;
        background: #fdfdfd;
        min-height: 400px;
        max-height: 600px;
        overflow-y: auto;
    }
    
    .message-row { display: flex; margin-bottom: 20px; }
    .message-row.user { justify-content: flex-end; }
    .message-row.admin { justify-content: flex-start; }
    
    .message-bubble {
        max-width: 70%;
        padding: 15px 20px;
        border-radius: 12px;
        position: relative;
        font-size: 14px;
        line-height: 1.5;
    }
    .user .message-bubble {
        background: #e3f2fd; /* Light Blue */
        color: #0d47a1;
        border-bottom-right-radius: 2px;
    }
    .admin .message-bubble {
        background: #f5f5f5; /* Light Gray */
        color: #333;
        border-bottom-left-radius: 2px;
        border: 1px solid #eee;
    }
    
    .message-meta {
        font-size: 11px;
        margin-top: 5px;
        opacity: 0.7;
        text-align: right;
    }
    .admin .message-meta { text-align: left; }
    
    .reply-box {
        padding: 20px 30px;
        border-top: 1px solid #eee;
        background: #fff;
    }
    
    .btn-reply {
        background: #2F6FED;
        color: #fff;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 600;
        margin-top: 10px;
        cursor: pointer;
    }
    .btn-reply:disabled { background: #ccc; cursor: not-allowed; }
</style>

<div class="container dashboard-container">
    <div class="row">
         <div class="col-lg-3 col-md-12 mb-4">
            <?php include 'includes/sidebar.php'; ?>
        </div>
        
        <div class="col-lg-9 col-md-12">
            <a href="support.php" class="text-decoration-none text-secondary mb-3 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Back to Tickets</a>
            
            <div class="content-box">
                <!-- Header -->
                <div class="ticket-header">
                    <div>
                        <h5 class="mb-1" style="color: #2F3A3F; font-weight: 600;"><?php echo htmlspecialchars($ticket['subject']); ?></h5>
                        <small class="text-muted">Ticket #<?php echo $ticket['ticket_no']; ?> | <?php echo date('d M Y', strtotime($ticket['created_at'])); ?></small>
                    </div>
                    <?php 
                         $statusClass = 'status-open';
                         if($ticket['status'] == 'Closed') $statusClass = 'status-closed';
                    ?>
                    <span class="badge <?php echo $statusClass == 'status-closed' ? 'bg-danger' : 'bg-success'; ?>"><?php echo $ticket['status']; ?></span>
                </div>
                
                <!-- Chat Area -->
                <div class="chat-container">
                    <?php while($reply = $replies->fetch_assoc()): ?>
                        <div class="message-row <?php echo strtolower($reply['sender_type']); ?>">
                            <div class="message-bubble">
                                <div><?php echo nl2br(htmlspecialchars($reply['message'])); ?></div>
                                <div class="message-meta">
                                    <?php echo $reply['sender_type'] == 'User' ? 'You' : 'Support Agent'; ?> • 
                                    <?php echo date('d M, h:i A', strtotime($reply['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <!-- Reply Form -->
                <?php if($ticket['status'] != 'Closed'): ?>
                <div class="reply-box">
                    <form id="replyForm">
                        <textarea class="form-control" name="message" rows="3" placeholder="Type your reply here..." required style="resize:none; border-color:#e0e0e0;"></textarea>
                        <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                        <div class="text-end">
                            <button type="submit" class="btn-reply">Send Reply</button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                    <div class="reply-box text-center text-muted bg-light">
                        <i class="fas fa-lock me-2"></i> This ticket is closed.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    document.getElementById('replyForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = this.querySelector('button');
        btn.disabled = true;
        btn.innerText = 'Sending...';

        const formData = new FormData(this);
        formData.append('action', 'reply_ticket');

        fetch('includes/support_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                window.location.reload();
            } else {
                alert(data.message);
                btn.disabled = false;
                btn.innerText = 'Send Reply';
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred.');
            btn.disabled = false;
            btn.innerText = 'Send Reply';
        });
    });
</script>

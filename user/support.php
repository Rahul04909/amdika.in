<?php
// Auth & Session
require_once '../includes/session_config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$page_title = 'Support Tickets';
require_once '../database/db_config.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch Tickets
$sql = "SELECT * FROM support_tickets WHERE user_id = ? ORDER BY created_at DESC";
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
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
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
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 15px;
    }

    .btn-create {
        background: #2F6FED;
        color: #fff;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-create:hover { background: #1e5ad6; transform: translateY(-1px); color: #fff; }

    /* Ticket Row */
    .ticket-item {
        display: flex;
        justify-content: space-between; /* Space between textual data and button/badge */
        align-items: center;
        padding: 20px;
        border: 1px solid #eee;
        border-radius: 8px;
        margin-bottom: 15px;
        transition: 0.2s;
        cursor: pointer;
    }
    .ticket-item:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-color: #e0e0e0;
        transform: translateY(-2px);
    }
    
    .ticket-info h5 { font-size: 16px; font-weight: 600; color: #212121; margin-bottom: 5px; }
    .ticket-meta { font-size: 13px; color: #777; }
    .ticket-no { font-weight: 600; color: #2F3A3F; margin-right: 10px; }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-open { background: #e3fcf7; color: #00b894; }
    .status-closed { background: #ffe5e5; color: #ff4757; }
    .status-progress { background: #fff3cd; color: #ffc107; }

    /* Modal Styles */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
    .modal-content {
        background: #fff;
        margin: 10% auto;
        padding: 30px;
        border-radius: 10px;
        width: 100%;
        max-width: 500px;
        position: relative;
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    
    .close-btn { position: absolute; right: 20px; top: 20px; font-size: 24px; cursor: pointer; color: #999; }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; }
    .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
    .form-control:focus { border-color: #2F6FED; outline: none; }
    
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
                    <span>My Support Tickets</span>
                    <button class="btn-create" onclick="openModal()"><i class="fas fa-plus me-2"></i> Raise New Ticket</button>
                </div>
                
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <?php 
                            $statusClass = 'status-open';
                            if($row['status'] == 'Closed') $statusClass = 'status-closed';
                            elseif($row['status'] == 'In Progress') $statusClass = 'status-progress';
                        ?>
                        <div class="ticket-item" onclick="window.location.href='view-ticket.php?id=<?php echo $row['id']; ?>'">
                            <div class="ticket-info">
                                <h5><?php echo htmlspecialchars($row['subject']); ?></h5>
                                <div class="ticket-meta">
                                    <span class="ticket-no">#<?php echo $row['ticket_no']; ?></span>
                                    <span><i class="far fa-clock me-1"></i> <?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></span>
                                </div>
                            </div>
                            <span class="status-badge <?php echo $statusClass; ?>"><?php echo $row['status']; ?></span>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-headset fs-1 mb-3 opacity-25"></i>
                        <h4>No tickets found</h4>
                        <p>Need help? Raise a new support ticket.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<!-- Create Ticket Modal -->
<div id="createTicketModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h4 class="mb-4" style="color:#2F3A3F;">Raise New Ticket</h4>
        
        <form id="createTicketForm">
            <div class="form-group">
                <label>Subject</label>
                <input type="text" class="form-control" name="subject" placeholder="Brief summary of the issue" required>
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea class="form-control" name="message" rows="5" placeholder="Describe your issue in detail..." required></textarea>
            </div>
            <button type="submit" class="btn-create w-100">Submit Ticket</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    const modal = document.getElementById('createTicketModal');
    
    function openModal() { modal.style.display = "block"; }
    function closeModal() { modal.style.display = "none"; }
    
    window.onclick = function(event) {
        if (event.target == modal) { closeModal(); }
    }

    document.getElementById('createTicketForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'create_ticket');

        fetch('includes/support_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                alert('Ticket Raised Successfully!');
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred.');
        });
    });
</script>

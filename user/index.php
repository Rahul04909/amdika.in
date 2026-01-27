<?php
// Auth & Session
require_once '../includes/session_config.php';
// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$page_title = 'My Profile';
require_once '../database/db_config.php';
include '../includes/header.php';

// Fetch Current User Details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<style>
    body { background-color: #f1f3f6; } /* Light gray bg */
    .dashboard-container { padding: 30px 0; }
    
    .content-box {
        background: #fff;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.15);
        border-radius: 2px;
        padding: 24px;
        margin-bottom: 20px;
    }
    
    .section-header { font-size: 18px; font-weight: 500; margin-bottom: 24px; color: #212121; display:flex; gap:10px; align-items:center;}
    
    .info-label { font-size: 14px; font-weight: 500; color: #212121; margin-bottom: 5px; }
    .info-value { font-size: 14px; color: #212121;  margin-bottom: 20px;}
    
    .edit-link { font-size: 14px; font-weight: 500; color: #2874f0; cursor: pointer; text-decoration: none; margin-left: auto; }
</style>

<div class="container dashboard-container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4 mb-4">
            <?php include 'includes/sidebar.php'; ?>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
            <div class="content-box">
                <div class="section-header">
                    Personal Information
                    <a href="#" class="edit-link">Edit</a>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="info-label">First Name</div>
                        <div class="bg-light p-2 rounded border"><?php echo htmlspecialchars($user['name']); ?></div>
                    </div>
                     <!-- Add Last Name if DB supports it, else just Name is fine -->
                </div>
                
                <div class="section-header mt-4">
                    Email Address
                    <a href="#" class="edit-link">Edit</a>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="bg-light p-2 rounded border"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>
                </div>

                <div class="section-header mt-4">
                    Mobile Number
                    <a href="#" class="edit-link">Edit</a>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="bg-light p-2 rounded border"><?php echo htmlspecialchars($user['mobile']); ?></div>
                    </div>
                </div>
                
                <div class="section-header mt-4">FAQs</div>
                <div class="text-muted" style="font-size:13px;">
                    <p class="fw-bold text-dark">What happens when I update my email address (or mobile number)?</p>
                    <p>Your login email id (or mobile number) changes, likewise. You'll receive all your account related communication on your updated email address (or mobile number).</p>
                    
                    <p class="fw-bold text-dark mt-3">When will my Flipkart account be updated with the new email address (or mobile number)?</p>
                    <p>It happens as soon as you confirm the verification code sent to your email (or mobile) and save the changes.</p>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

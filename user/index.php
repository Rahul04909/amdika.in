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
    body { background-color: #f8f9fa; }
    .dashboard-container { padding: 40px 0; }
    
    .content-box {
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); /* Soft premium shadow */
        border-radius: 8px; /* Rounded corners */
        border: 1px solid #f0f0f0;
        padding: 30px;
        margin-bottom: 24px;
        transition: transform 0.2s ease;
    }
    
    .section-header { 
        font-size: 20px; 
        font-weight: 600; 
        margin-bottom: 24px; 
        color: #2F3A3F; /* Charcoal */
        display: flex; 
        gap: 15px; 
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 15px;
    }
    
    .info-label { 
        font-size: 13px; 
        font-weight: 600; 
        color: #878787; 
        text-transform: uppercase;
        margin-bottom: 8px; 
    }
    .info-value-box { 
        font-size: 15px; 
        color: #212121;  
        background: #fdfdfd;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 12px 15px;
        font-weight: 500;
    }
    
    .edit-link { 
        font-size: 14px; 
        font-weight: 600; 
        color: #2F6FED; /* Royal Blue */
        cursor: pointer; 
        text-decoration: none; 
        margin-left: auto;
        padding: 6px 12px;
        border-radius: 4px;
        transition: 0.2s;
    }
    .edit-link:hover { background: #f0f7ff; }

    /* FAQs styling */
    .faq-title { font-weight: 600; color: #2F3A3F; margin-top: 15px; margin-bottom: 5px; }
    .faq-desc { font-size: 14px; color: #666; line-height: 1.6; }
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
                    <span>Personal Information</span>
                    <a href="#" class="edit-link">Edit</a>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="info-label">Full Name</div>
                        <div class="info-value-box"><?php echo htmlspecialchars($user['name']); ?></div>
                    </div>
                </div>
                
                <div class="section-header mt-2">
                    <span>Email Address</span>
                    <a href="#" class="edit-link">Edit</a>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="info-label">Email ID</div>
                        <div class="info-value-box"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>
                </div>

                <div class="section-header mt-2">
                    <span>Mobile Number</span>
                    <a href="#" class="edit-link">Edit</a>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="info-label">Mobile Number</div>
                        <div class="info-value-box"><?php echo htmlspecialchars($user['mobile']); ?></div>
                    </div>
                </div>
                
                <div class="mt-5 pt-3 border-top">
                    <h5 class="mb-3" style="color: #2F3A3F;">FAQs</h5>
                    <div class="faq-section">
                        <div class="faq-title">What happens when I update my email address (or mobile number)?</div>
                        <div class="faq-desc">Your login email id (or mobile number) changes, likewise. You'll receive all your account related communication on your updated email address (or mobile number).</div>
                        
                        <div class="faq-title">When will my Flipkart account be updated with the new email address?</div>
                        <div class="faq-desc">It happens as soon as you confirm the verification code sent to your email (or mobile) and save the changes.</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<?php
// Auth & Session
require_once '../includes/session_config.php';
// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
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
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        padding: 30px;
        margin-bottom: 24px;
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

    /* View Mode */
    .field-wrapper { margin-bottom: 30px; position: relative; }
    
    .view-mode { display: block; }
    .edit-mode { display: none; }

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
        min-height: 48px;
    }
    
    .edit-link { 
        font-size: 14px; 
        font-weight: 600; 
        color: #2F6FED; 
        cursor: pointer; 
        text-decoration: none; 
        margin-left: auto;
    }

    /* Edit Mode Inputs (Premium Style) */
    .form-control-custom {
        width: 100%;
        height: 48px;
        padding: 10px 15px;
        font-size: 15px;
        color: #333;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        transition: 0.3s;
        outline: none;
    }
    .form-control-custom:focus {
        border-color: #D9A11D; /* Gold */
        box-shadow: 0 4px 12px rgba(217, 161, 29, 0.1);
    }
    
    .btn-save {
        background: #2874f0; 
        color: #fff; 
        padding: 10px 24px; 
        border: none; 
        border-radius: 4px; 
        font-weight: 600; 
        font-size: 14px;
        cursor: pointer;
    }
    .btn-cancel {
        background: transparent;
        color: #878787;
        border: none;
        font-weight: 600;
        font-size: 14px;
        margin-left: 15px;
        cursor: pointer;
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
                    <span>Personal Information</span>
                </div>
                
                <!-- Full Name -->
                <div class="field-wrapper" id="wrap-name">
                     <!-- View -->
                    <div class="view-mode">
                        <div class="d-flex align-items-center mb-2">
                            <span class="info-label mb-0">Full Name</span>
                            <span class="edit-link" onclick="toggleEdit('name')">Edit</span>
                        </div>
                        <div class="info-value-box" id="disp-name"><?php echo htmlspecialchars($user['name']); ?></div>
                    </div>
                    <!-- Edit -->
                    <div class="edit-mode">
                         <div class="info-label">Full Name</div>
                         <div class="d-flex gap-3">
                             <input type="text" class="form-control-custom" id="input-name" value="<?php echo htmlspecialchars($user['name']); ?>">
                             <button class="btn-save" onclick="saveField('name')">SAVE</button>
                             <button class="btn-cancel" onclick="cancelEdit('name')">Cancel</button>
                         </div>
                    </div>
                </div>
                
                <!-- Email -->
                <div class="field-wrapper" id="wrap-email">
                     <!-- View -->
                    <div class="view-mode">
                        <div class="d-flex align-items-center mb-2">
                             <span class="info-label mb-0">Email Address</span>
                             <span class="edit-link" onclick="toggleEdit('email')">Edit</span>
                        </div>
                        <div class="info-value-box" id="disp-email"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>
                    <!-- Edit -->
                    <div class="edit-mode">
                         <div class="info-label">Email Address</div>
                         <div class="d-flex gap-3">
                             <input type="email" class="form-control-custom" id="input-email" value="<?php echo htmlspecialchars($user['email']); ?>">
                             <button class="btn-save" onclick="saveField('email')">SAVE</button>
                             <button class="btn-cancel" onclick="cancelEdit('email')">Cancel</button>
                         </div>
                    </div>
                </div>

                <!-- Mobile -->
                <div class="field-wrapper" id="wrap-mobile">
                     <!-- View -->
                    <div class="view-mode">
                        <div class="d-flex align-items-center mb-2">
                             <span class="info-label mb-0">Mobile Number</span>
                             <span class="edit-link" onclick="toggleEdit('mobile')">Edit</span>
                        </div>
                        <div class="info-value-box" id="disp-mobile"><?php echo htmlspecialchars($user['mobile']); ?></div>
                    </div>
                    <!-- Edit -->
                    <div class="edit-mode">
                         <div class="info-label">Mobile Number</div>
                         <div class="d-flex gap-3">
                             <input type="text" class="form-control-custom" id="input-mobile" value="<?php echo htmlspecialchars($user['mobile']); ?>" maxlength="10">
                             <button class="btn-save" onclick="saveField('mobile')">SAVE</button>
                             <button class="btn-cancel" onclick="cancelEdit('mobile')">Cancel</button>
                         </div>
                    </div>
                </div>
                
                <div class="mt-5 pt-3 border-top">
                    <h5 class="mb-3" style="color: #2F3A3F;">FAQs</h5>
                    <div class="faq-section">
                        <div class="faq-title">What happens when I update my email address (or mobile number)?</div>
                        <div class="faq-desc">Your login email id (or mobile number) changes, likewise. You'll receive all your account related communication on your updated email address (or mobile number).</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    function toggleEdit(field) {
        const wrapper = document.getElementById('wrap-' + field);
        wrapper.querySelector('.view-mode').style.display = 'none';
        wrapper.querySelector('.edit-mode').style.display = 'block';
    }

    function cancelEdit(field) {
        const wrapper = document.getElementById('wrap-' + field);
        wrapper.querySelector('.view-mode').style.display = 'block';
        wrapper.querySelector('.edit-mode').style.display = 'none';
        
        // Reset value
        const currentVal = document.getElementById('disp-' + field).innerText;
        document.getElementById('input-' + field).value = currentVal;
    }

    function saveField(field) {
        const input = document.getElementById('input-' + field);
        const value = input.value;
        
        if(!value) { alert('Value cannot be empty'); return; }

        const formData = new FormData();
        formData.append('action', 'update_info');
        formData.append('field', field);
        formData.append('value', value);

        fetch('includes/profile_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                document.getElementById('disp-' + field).innerText = value;
                cancelEdit(field);
                alert('Updated Successfully!');
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred.');
        });
    }
</script>

<?php
// Auth & Session
require_once '../includes/session_config.php';
// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$page_title = 'Change Password';
require_once '../database/db_config.php';
include '../includes/header.php';
?>

<style>
    body { background-color: #f8f9fa; }
    .dashboard-container { padding: 40px 0; }
    
    .content-box {
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); /* Soft premium shadow */
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        padding: 40px;
        margin-bottom: 24px;
        min-height: 400px;
        max-width: 800px; /* Limit width for form focus */
    }
    
    .section-header { 
        font-size: 20px; 
        font-weight: 600; 
        margin-bottom: 30px; 
        color: #2F3A3F;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    /* Premium Floating Label & Icon Input (Consistent with Register/Login) */
    .input-group-floating {
        position: relative;
        margin-bottom: 25px;
    }

    .form-icon {
        position: absolute;
        left: 16px;
        top: 15px;
        color: #999;
        font-size: 16px;
        z-index: 2;
        transition: color 0.3s;
    }
    
    .toggle-password {
        position: absolute;
        right: 16px;
        top: 15px;
        color: #999;
        cursor: pointer;
        z-index: 2;
        transition: color 0.3s;
    }
    .toggle-password:hover { color: #555; }

    .form-control-custom {
        width: 100%;
        height: 50px;
        padding: 18px 45px 5px 45px; /* Space for L/R icons */
        font-size: 15px;
        color: #333;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        background: #fff;
        transition: all 0.3s ease;
        outline: none;
    }
    
    .form-control-custom:focus {
        border-color: #D9A11D; /* Gold */
        box-shadow: 0 4px 12px rgba(217, 161, 29, 0.1);
    }
    .form-control-custom:focus ~ .form-icon { color: #D9A11D; }
    
    .floating-label {
        position: absolute;
        left: 45px;
        top: 14px;
        font-size: 14px;
        color: #888;
        pointer-events: none;
        transition: 0.2s ease all;
        background-color: transparent;
    }

    .form-control-custom:focus ~ .floating-label,
    .form-control-custom:not(:placeholder-shown) ~ .floating-label {
        top: 6px;
        font-size: 11px;
        color: #2F6FED; /* Royal Blue */
        font-weight: 600;
    }
    
    .btn-update {
        width: 100%;
        height: 50px;
        background: #2874f0; /* Use Royal Blue if desired, or Gold. Gold for primary actions is premium. */
        background: #D9A11D;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(217, 161, 29, 0.2);
    }
    .btn-update:hover {
        background: #c5901a;
        transform: translateY(-2px);
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
                <div class="section-header">Change Password</div>
                
                <form id="passwordForm" style="max-width: 500px;">
                    <!-- Current Password -->
                    <div class="input-group-floating">
                        <input type="password" class="form-control-custom" id="current_password" name="current_password" placeholder=" " required>
                        <i class="fas fa-lock form-icon"></i>
                        <label class="floating-label">Current Password</label>
                        <i class="fas fa-eye toggle-password" onclick="toggleVisibility('current_password', this)"></i>
                    </div>

                    <!-- New Password -->
                    <div class="input-group-floating">
                        <input type="password" class="form-control-custom" id="new_password" name="new_password" placeholder=" " required>
                        <i class="fas fa-key form-icon"></i>
                        <label class="floating-label">New Password</label>
                        <i class="fas fa-eye toggle-password" onclick="toggleVisibility('new_password', this)"></i>
                    </div>

                    <!-- Confirm Password -->
                    <div class="input-group-floating">
                        <input type="password" class="form-control-custom" id="confirm_password" name="confirm_password" placeholder=" " required>
                        <i class="fas fa-check-circle form-icon"></i>
                        <label class="floating-label">Confirm New Password</label>
                        <i class="fas fa-eye toggle-password" onclick="toggleVisibility('confirm_password', this)"></i>
                    </div>

                    <button type="submit" class="btn-update">Update Password</button>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    function toggleVisibility(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
            icon.style.color = "#D9A11D";
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
            icon.style.color = "#999";
        }
    }

    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'change_password');

        // BasicClient Side Validation
        if(formData.get('new_password') !== formData.get('confirm_password')) {
            alert('New passwords do not match');
            return;
        }

        fetch('includes/profile_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                alert('Password Changed Successfully!');
                this.reset();
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred. Please try again.');
        });
    });
</script>

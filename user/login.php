<?php
$page_title = 'Login - Amadika';
require_once '../database/db_config.php';
// Need header from root includes, so adjust path
include '../includes/header.php';
?>


<style>
    :root {
        --primary-charcoal: #2F3A3F;
        --accent-gold: #D9A11D;
        --accent-blue: #2F6FED;
        --text-dark: #333;
        --text-muted: #666;
        --border-color: #E6E6E6;
    }

    body { background-color: #fff; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    
    .login-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 15px;
    }

    .login-card {
        width: 100%;
        max-width: 1000px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        display: flex;
        overflow: hidden;
        min-height: 550px;
    }

    /* Left Info Panel */
    .login-info {
        width: 40%;
        background: linear-gradient(135deg, #2F6FED 0%, #1e5ad6 100%);
        color: #fff;
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
    }
    .login-info h2 { font-size: 32px; font-weight: 700; margin-bottom: 20px; line-height: 1.2; }
    .login-info p { font-size: 16px; opacity: 0.9; line-height: 1.6; margin-bottom: 30px; }
    .info-graphic { 
        max-width: 80%; 
        align-self: center; 
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2)); 
    }
    
    /* Right Form Panel */
    .login-form-section {
        width: 60%;
        padding: 60px 50px;
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .form-header { margin-bottom: 30px; }
    .form-header h3 { font-size: 26px; font-weight: 700; color: var(--primary-charcoal); margin-bottom: 8px; }
    .form-header p { color: var(--text-muted); font-size: 14px; }

    /* Floating Labels & Inputs */
    .input-group-floating {
        position: relative;
        margin-bottom: 24px;
    }
    
    .form-icon {
        position: absolute;
        left: 16px;
        top: 16px;
        color: #999;
        font-size: 16px;
        z-index: 2;
        transition: color 0.3s;
    }

    .form-control-custom {
        width: 100%;
        height: 50px;
        padding: 18px 15px 5px 45px;
        font-size: 15px;
        color: var(--text-dark);
        border: 1px solid var(--border-color);
        border-radius: 6px;
        background: #fff;
        transition: all 0.3s ease;
        outline: none;
    }
    
    .form-control-custom:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 4px 12px rgba(217, 161, 29, 0.15);
    }
    .form-control-custom:focus ~ .form-icon { color: var(--accent-gold); }

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
        color: var(--accent-blue);
        font-weight: 600;
    }

    /* Primary Button */
    .btn-login-premium {
        width: 100%;
        height: 52px;
        background: var(--accent-gold);
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        letter-spacing: 0.5px;
    }
    .btn-login-premium:hover {
        background: #C79218;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(217, 161, 29, 0.3);
    }
    .btn-login-premium:active { transform: translateY(0); }

    /* Links */
    .forgot-link {
        display: block;
        text-align: right;
        color: var(--accent-blue);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 20px;
    }
    .forgot-link:hover { text-decoration: underline; }

    .register-link-container { text-align: center; margin-top: 30px; font-size: 14px; color: var(--text-muted); }
    .register-link-container a { color: var(--accent-blue); font-weight: 600; text-decoration: none; margin-left: 5px; }
    .register-link-container a:hover { text-decoration: underline; }

    /* Mobile Responsive */
    @media (max-width: 991px) {
        .login-info { display: none; }
        .login-form-section { width: 100%; padding: 40px 20px; }
        .login-card { max-width: 500px; min-height: auto; }
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <!-- Left Info Panel -->
        <div class="login-info">
            <div>
                <h2>Welcome<br>Back!</h2>
                <p>Log in to access your wishlist, track orders, and experience faster checkout.</p>
                <img src="../assets/images/amadika-logo.png" class="info-graphic" alt="Amadika Logo" style="width: 150px; background: rgba(255,255,255,0.1); padding: 10px; border-radius: 8px;">
            </div>
            <div style="font-size: 14px; opacity: 0.8;">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> Amadika. All rights reserved.</p>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="login-form-section">
            <div class="form-header">
                <h3>Login to Account</h3>
                <p>Please enter your email and password to continue</p>
            </div>

            <form id="loginForm">
                <input type="hidden" name="redirect" value="<?php echo isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : ''; ?>">
                
                <!-- Email -->
                <div class="input-group-floating">
                    <input type="text" class="form-control-custom" name="email" id="email" placeholder=" " required>
                    <label class="floating-label">Email Address</label>
                    <i class="fas fa-envelope form-icon"></i>
                </div>

                <!-- Password -->
                <div class="input-group-floating mb-1">
                    <input type="password" class="form-control-custom" name="password" id="password" placeholder=" " required>
                    <label class="floating-label">Password</label>
                    <i class="fas fa-lock form-icon"></i>
                    <i class="fas fa-eye toggle-pass" onclick="toggleVisibility('password', this)" style="position:absolute; right:15px; top:16px; cursor:pointer; color:#999;"></i>
                </div>
                
                <a href="#" class="forgot-link">Forgot Password?</a>

                <button type="submit" class="btn-login-premium">
                    <span>Login</span>
                    <i class="fas fa-arrow-right ms-2" style="font-size:14px;"></i>
                </button>

                <div class="register-link-container">
                    New to Amadika? <a href="../register.php">Create an account</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    // Toggle Password Visibility
    function toggleVisibility(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
            icon.style.color = "var(--accent-gold)";
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
            icon.style.color = "#999";
        }
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'login');

        // Note: auth_actions.php is in includes/ folder, so path is ../includes/auth_actions.php
        fetch('../includes/auth_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                window.location.href = '../' + data.redirect;
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

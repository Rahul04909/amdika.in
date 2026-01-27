<?php
$page_title = 'Login - Amadika';
require_once '../database/db_config.php';
// Need header from root includes, so adjust path
include '../includes/header.php';
?>

<style>
    body { background-color: #f1f3f6; }
    .login-container {
        max-width: 800px;
        margin: 40px auto;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.15);
        display: flex;
        overflow: hidden;
        min-height: 450px;
    }
    
    .login-sidebar {
        width: 35%;
        background: #2874f0;
        padding: 40px 33px;
        color: #fff;
        display: none;
    }
    .login-sidebar h2 { font-size: 28px; font-weight: 500; margin-bottom: 20px; }
    .login-sidebar p { font-size: 18px; line-height: 150%; color: #dbdbdb; }
    .login-sidebar img { position: absolute; bottom: 40px; }
    
    .login-form-col {
        width: 100%;
        padding: 40px 35px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    @media(min-width: 768px) {
        .login-sidebar { display: block; position: relative; }
        .login-form-col { width: 65%; }
    }

    .form-group { margin-bottom: 20px; position: relative; }
    .form-control {
        border: none;
        border-bottom: 1px solid #e0e0e0;
        border-radius: 0;
        padding: 10px 0;
        font-size: 14px;
        color: #212121;
    }
    .form-control:focus {
        box-shadow: none;
        border-bottom: 1px solid #2874f0;
    }
    .form-label {
        font-size: 14px; color: #878787; position: absolute; 
        pointer-events: none; transition: 0.2s; top: 10px; left: 0;
    }
    
    /* Float Label */
    .form-control:focus ~ .form-label,
    .form-control:not(:placeholder-shown) ~ .form-label {
        top: -12px; font-size: 12px; color: #2874f0;
    }

    .btn-login {
        background: #fb641b; color: #fff; font-weight: 600;
        font-size: 15px; padding: 12px; width: 100%; border: none;
        border-radius: 2px; box-shadow: 0 1px 2px 0 rgba(0,0,0,.2);
        margin-top: 10px;
        text-transform: uppercase;
    }
    .btn-login:hover { background: #f55b10; }
    
    .or-divider {
        margin: 20px 0; text-align: center; color: #878787; font-size: 12px; position: relative;
    }
    
    .create-account-link {
        color: #2874f0; font-weight: 500; text-decoration: none; display: block; text-align: center; margin-top: 25px;
    }

    .forgot-link {
        display: block; text-align: right; color: #2874f0; font-size: 12px; font-weight: 500; margin-top: 5px; text-decoration: none;
    }

    .toggle-password {
        position: absolute; right: 0; top: 10px; cursor: pointer; color: #878787;
    }
</style>

<div class="container mb-5">
    <div class="login-container">
        <!-- Sidebar -->
        <div class="login-sidebar">
            <h2>Login</h2>
            <p>Get access to your Orders, Wishlist and Recommendations</p>
            <img src="https://static-assets-web.flixcart.com/fk-p-linchpin-web/fk-cp-zion/img/login_img_c4a81e.png" alt="Login Graphic" style="width: 200px;">
        </div>

        <!-- Form -->
        <div class="login-form-col">
            <h3 class="d-md-none mb-4">Login</h3>
            
            <form id="loginForm">
                <div class="form-group">
                    <input type="text" class="form-control" name="email" id="email" placeholder=" " required>
                    <label class="form-label">Email ID</label>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder=" " required>
                    <label class="form-label">Password</label>
                    <i class="fas fa-eye toggle-password" onclick="togglePass()"></i>
                    <a href="#" class="forgot-link">Forgot?</a>
                </div>

                <p class="text-muted" style="font-size: 11px;">By continuing, you agree to Amadika's Terms of Use and Privacy Policy.</p>

                <button type="submit" class="btn-login">Login</button>
                
                <a href="../register.php" class="create-account-link">New to Amadika? Create an account</a>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    function togglePass() {
        const input = document.getElementById('password');
        const icon = document.querySelector('.toggle-password');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'login');

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

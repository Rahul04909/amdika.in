<?php
$page_title = 'Create Account';
include 'includes/header.php';
?>

<style>
    body { background-color: #f1f3f6; }
    .register-container {
        max-width: 800px;
        margin: 40px auto;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.15);
        display: flex;
        overflow: hidden;
    }
    
    .register-sidebar {
        width: 35%;
        background: #2874f0;
        padding: 40px 33px;
        color: #fff;
        display: none; /* Hide on mobile by default */
    }
    .register-sidebar h2 { font-size: 28px; font-weight: 500; margin-bottom: 20px; }
    .register-sidebar p { font-size: 18px; line-height: 150%; color: #dbdbdb; }
    .register-sidebar img { position: absolute; bottom: 40px; }
    
    .register-form-col {
        width: 100%;
        padding: 40px 35px;
    }
    
    @media(min-width: 768px) {
        .register-sidebar { display: block; position: relative; }
        .register-form-col { width: 65%; }
    }

    .form-group { margin-bottom: 20px; position: relative; }
    .form-control {
        border: none;
        border-bottom: 1px solid #e0e0e0;
        border-radius: 0;
        padding: 10px 0;
        font-size: 14px;
    }
    .form-control:focus {
        box-shadow: none;
        border-bottom: 1px solid #2874f0;
    }
    .form-label {
        font-size: 14px; color: #878787; position: absolute; 
        pointer-events: none; transition: 0.2s; top: 10px; left: 0;
    }
    
    /* Float Label Effect */
    .form-control:focus ~ .form-label,
    .form-control:not(:placeholder-shown) ~ .form-label {
        top: -12px; font-size: 12px; color: #2874f0;
    }

    .btn-register {
        background: #fb641b; color: #fff; font-weight: 600;
        font-size: 15px; padding: 12px; width: 100%; border: none;
        border-radius: 2px; box-shadow: 0 1px 2px 0 rgba(0,0,0,.2);
        margin-top: 10px;
        text-transform: uppercase;
    }
    .btn-register:hover { background: #f55b10; }
    
    .existing-user { margin-top: 20px; text-align: center; }
    .existing-user a { color: #2874f0; text-decoration: none; font-weight: 500; }

    /* Password Strength */
    .password-wrapper { position: relative; }
    .toggle-password {
        position: absolute; right: 0; top: 10px; cursor: pointer; color: #878787;
    }
    
    .strength-meter { height: 4px; border-radius: 2px; background: #e0e0e0; margin-top: 5px; transition: all 0.3s; width: 0%;}
    .strength-text { font-size: 11px; margin-top: 4px; font-weight: 500; text-align: right;}
    
    .weak { background: #ff3333; width: 33%; }
    .medium { background: #ffcc00; width: 66%; }
    .strong { background: #33cc33; width: 100%; }

    .text-weak { color: #ff3333; }
    .text-medium { color: #ffcc00; }
    .text-strong { color: #33cc33; }
</style>

<div class="container mb-5">
    <div class="register-container">
        <!-- Sidebar -->
        <div class="register-sidebar">
            <h2>Sign up</h2>
            <p>Get access to your Orders, Wishlist and Recommendations</p>
            <img src="https://static-assets-web.flixcart.com/fk-p-linchpin-web/fk-cp-zion/img/login_img_c4a81e.png" alt="Login Graphic" style="width: 200px;">
        </div>

        <!-- Form -->
        <div class="register-form-col">
            <h3 class="d-md-none mb-4">Create Account</h3>
            
            <form id="registerForm">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder=" " required>
                    <label class="form-label">Full Name</label>
                </div>
                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <input type="email" class="form-control" name="email" id="email" placeholder=" " required>
                        <label class="form-label">Email Address</label>
                    </div>
                    <div class="col-md-6 form-group">
                        <input type="tel" class="form-control" name="mobile" id="mobile" placeholder=" " pattern="[0-9]{10}" title="10 digit mobile number" required>
                        <label class="form-label">Mobile Number</label>
                    </div>
                </div>

                <div class="form-group">
                    <textarea class="form-control" name="address" id="address" rows="1" placeholder=" " required></textarea>
                    <label class="form-label">Full Address</label>
                </div>

                <div class="row">
                    <div class="col-6 form-group">
                        <input type="text" class="form-control" name="city" id="city" placeholder=" " required>
                        <label class="form-label">City</label>
                    </div>
                    <div class="col-6 form-group">
                        <input type="text" class="form-control" name="pincode" id="pincode" placeholder=" " pattern="[0-9]{6}" required>
                        <label class="form-label">Pincode</label>
                    </div>
                    <div class="col-6 form-group">
                        <input type="text" class="form-control" name="state" id="state" placeholder=" " required>
                        <label class="form-label">State</label>
                    </div>
                    <div class="col-6 form-group">
                        <input type="text" class="form-control" name="country" id="country" placeholder=" " value="India" required>
                        <label class="form-label">Country</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <div class="password-wrapper">
                            <input type="password" class="form-control" name="password" id="password" placeholder=" " minlength="8" required>
                            <label class="form-label">Password (Min 8 chars)</label>
                            <i class="fas fa-eye toggle-password" onclick="togglePass('password', this)"></i>
                        </div>
                        <div class="strength-meter" id="strengthBar"></div>
                        <div class="strength-text" id="strengthText"></div>
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <div class="password-wrapper">
                            <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder=" " required>
                            <label class="form-label">Confirm Password</label>
                            <i class="fas fa-eye toggle-password" onclick="togglePass('cpassword', this)"></i>
                        </div>
                        <div class="strength-text text-danger" id="matchText"></div>
                    </div>
                </div>

                <button type="submit" class="btn-register">Continue</button>
                
                <div class="existing-user">
                    <a href="login.php" class="btn btn-outline-light text-primary shadow-sm py-2 px-4 w-100">Existing User? Log in</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    // Password Visible Toggle
    function togglePass(inputId, icon) {
        const input = document.getElementById(inputId);
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

    // Password Strength Logic
    const passInput = document.getElementById('password');
    const stressBar = document.getElementById('strengthBar');
    const stressText = document.getElementById('strengthText');
    const confirmInput = document.getElementById('cpassword');
    const matchText = document.getElementById('matchText');

    passInput.addEventListener('input', function() {
        const val = this.value;
        let score = 0;
        
        if (val.length >= 8) score++;
        if (val.match(/[a-z]/) && val.match(/[A-Z]/)) score++;
        if (val.match(/[0-9]/)) score++;
        if (val.match(/[^a-zA-Z0-9]/)) score++;

        stressBar.className = 'strength-meter';
        stressText.className = 'strength-text';
        
        if (val.length === 0) {
            stressBar.style.width = '0%';
            stressText.innerText = '';
        } else if (val.length < 8) {
            stressBar.classList.add('weak');
            stressText.classList.add('text-weak');
            stressText.innerText = 'Too Short (Min 8)';
        } else if (score < 3) {
            stressBar.classList.add('weak');
            stressText.classList.add('text-weak');
            stressText.innerText = 'Weak';
        } else if (score === 3) {
            stressBar.classList.add('medium');
            stressText.classList.add('text-medium');
            stressText.innerText = 'Medium';
        } else {
            stressBar.classList.add('strong');
            stressText.classList.add('text-strong');
            stressText.innerText = 'Strong';
        }
    });

    // Password Match Check
    confirmInput.addEventListener('input', function() {
        if (this.value !== passInput.value) {
            matchText.innerText = "Passwords do not match";
        } else {
            matchText.innerText = "";
        }
    });

    // Form Submission
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (passInput.value !== confirmInput.value) {
            alert('Passwords do not match!');
            return;
        }

        const formData = new FormData(this);
        formData.append('action', 'register');

        fetch('includes/auth_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                alert('Registration Successful! Redirecting to login...');
                window.location.href = 'login.php';
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

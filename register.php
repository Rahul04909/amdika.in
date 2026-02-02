<?php
$page_title = 'Create Account';
include 'includes/header.php';
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
    
    .register-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 15px;
    }

    .register-card {
        width: 100%;
        max-width: 1100px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08); /* Soft shadow */
        display: flex;
        overflow: hidden;
        min-height: 600px;
    }

    /* Left Info Panel */
    .register-info {
        width: 40%;
        background: linear-gradient(135deg, #2F6FED 0%, #1e5ad6 100%); /* Royal Blue Gradient */
        color: #fff;
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
    }
    .register-info h2 { font-size: 32px; font-weight: 700; margin-bottom: 20px; line-height: 1.2; }
    .register-info p { font-size: 16px; opacity: 0.9; line-height: 1.6; margin-bottom: 30px; }
    .info-graphic { 
        max-width: 80%; 
        align-self: center; 
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2)); 
    }
    
    /* Right Form Panel */
    .register-form-section {
        width: 60%;
        padding: 60px 50px;
        background: #fff;
    }

    .form-header { margin-bottom: 40px; }
    .form-header h3 { font-size: 24px; font-weight: 700; color: var(--primary-charcoal); margin-bottom: 8px; }
    .form-header p { color: var(--text-muted); font-size: 14px; }

    /* Floating Labels & Inputs */
    .input-group-floating {
        position: relative;
        margin-bottom: 24px;
    }
    
    .form-icon {
        position: absolute;
        left: 16px;
        top: 16px; /* Center vert in 48px height */
        color: #999;
        font-size: 16px;
        z-index: 2;
        transition: color 0.3s;
    }

    .form-control-custom {
        width: 100%;
        height: 50px;
        padding: 18px 15px 5px 45px; /* Top padding for label, Left for icon */
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
        box-shadow: 0 4px 12px rgba(217, 161, 29, 0.15); /* Subtle glow */
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

    /* Active State for Label */
    .form-control-custom:focus ~ .floating-label,
    .form-control-custom:not(:placeholder-shown) ~ .floating-label {
        top: 6px;
        font-size: 11px;
        color: var(--accent-blue);
        font-weight: 600;
    }

    /* Register Button */
    .btn-register-premium {
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
        margin-top: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
    }
    .btn-register-premium:hover {
        background: #C79218; /* Slight Darken */
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(217, 161, 29, 0.3);
    }
    .btn-register-premium:active { transform: translateY(0); }

    /* Login Link */
    .login-link-container { text-align: center; margin-top: 30px; font-size: 14px; color: var(--text-muted); }
    .login-link-container a { color: var(--accent-blue); font-weight: 600; text-decoration: none; margin-left: 5px; }
    .login-link-container a:hover { text-decoration: underline; }

    /* Password Strength */
    .strength-meter { height: 3px; border-radius: 2px; background: #eee; margin-top: 8px; overflow: hidden; display: flex; }
    .strength-fill { height: 100%; width: 0%; transition: width 0.3s, background 0.3s; }
    .strength-text { font-size: 11px; font-weight: 500; margin-top: 4px; text-align: right; min-height: 16px; }

    /* Mobile Responsive */
    @media (max-width: 991px) {
        .register-info { display: none; }
        .register-form-section { width: 100%; padding: 40px 20px; }
        .register-card { max-width: 600px; }
    }
</style>

<div class="register-wrapper">
    <div class="register-card">
        <!-- Left Info Panel -->
        <div class="register-info">
            <div>
                <h2>Join our<br>community</h2>
                <p>Sign up to unlock exclusive deals, track orders, and experience seamless shopping.</p>
                <ul class="list-unstyled mt-4" style="font-size: 14px; opacity: 0.9;">
                    <li class="mb-3"><i class="fas fa-check-circle me-2"></i> Fast & Secure Checkout</li>
                    <li class="mb-3"><i class="fas fa-check-circle me-2"></i> Exclusive Member Discounts</li>
                    <li class="mb-3"><i class="fas fa-check-circle me-2"></i> 24/7 Customer Support</li>
                </ul>
            </div>
            <!-- Illustration Placeholder -->
            <img src="assets/images/amadika-logo.png" class="info-graphic" alt="Shopping Illustration">
        </div>

        <!-- Right Form Panel -->
        <div class="register-form-section">
            <div class="form-header">
                <h3>Create Account</h3>
                <p>Enter your details below to create your account</p>
            </div>

            <form id="registerForm">
                <!-- Full Name -->
                <div class="input-group-floating">
                    <input type="text" class="form-control-custom" name="name" id="name" placeholder=" " required>
                    <label class="floating-label">Full Name</label>
                    <i class="fas fa-user form-icon"></i>
                </div>

                <div class="row g-3">
                    <!-- Email -->
                    <div class="col-md-6">
                        <div class="input-group-floating">
                            <input type="email" class="form-control-custom" name="email" id="email" placeholder=" " required>
                            <label class="floating-label">Email Address</label>
                            <i class="fas fa-envelope form-icon"></i>
                        </div>
                    </div>
                    <!-- Phone -->
                    <div class="col-md-6">
                        <div class="input-group-floating">
                            <input type="tel" class="form-control-custom" name="mobile" id="mobile" placeholder=" " pattern="[0-9]{10}" required>
                            <label class="floating-label">Mobile Number</label>
                            <i class="fas fa-phone-alt form-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="input-group-floating">
                    <input type="text" class="form-control-custom" name="address" id="address" placeholder=" " required>
                    <label class="floating-label">Full Address</label>
                    <i class="fas fa-map-marker-alt form-icon"></i>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                         <div class="input-group-floating mb-3">
                            <input type="text" class="form-control-custom" name="city" id="city" placeholder=" " required>
                            <label class="floating-label">City</label>
                            <i class="fas fa-city form-icon"></i>
                         </div>
                    </div>
                    <div class="col-6">
                         <div class="input-group-floating mb-3">
                            <input type="text" class="form-control-custom" name="pincode" id="pincode" placeholder=" " pattern="[0-9]{6}" required>
                            <label class="floating-label">Pincode</label>
                            <i class="fas fa-map-pin form-icon"></i>
                         </div>
                    </div>
                    <div class="col-6">
                         <div class="input-group-floating mb-3">
                            <input type="text" class="form-control-custom" name="country" id="country" value="India" placeholder=" " required>
                            <label class="floating-label">Country</label>
                            <i class="fas fa-globe form-icon"></i>
                         </div>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Password -->
                    <div class="col-md-6">
                        <div class="input-group-floating mb-1">
                            <input type="password" class="form-control-custom" name="password" id="password" placeholder=" " minlength="8" required>
                            <label class="floating-label">Password</label>
                            <i class="fas fa-lock form-icon"></i>
                            <i class="fas fa-eye toggle-pass" onclick="toggleVisibility('password', this)" style="position:absolute; right:15px; top:16px; cursor:pointer; color:#999;"></i>
                        </div>
                        <div class="strength-meter"><div class="strength-fill" id="strengthFill"></div></div>
                        <div class="strength-text" id="strengthText"></div>
                    </div>
                    <!-- Confirm Password -->
                    <div class="col-md-6">
                        <div class="input-group-floating mb-1">
                            <input type="password" class="form-control-custom" name="cpassword" id="cpassword" placeholder=" " required>
                            <label class="floating-label">Confirm Password</label>
                            <i class="fas fa-lock form-icon"></i>
                             <i class="fas fa-eye toggle-pass" onclick="toggleVisibility('cpassword', this)" style="position:absolute; right:15px; top:16px; cursor:pointer; color:#999;"></i>
                        </div>
                        <div class="strength-text text-danger" id="matchText"></div>
                    </div>
                </div>

                <button type="submit" class="btn-register-premium">
                    <span>Create Account</span>
                    <i class="fas fa-arrow-right ms-2" style="font-size:14px;"></i>
                </button>

                <div class="login-link-container">
                    Already have an account? <a href="user/login.php">Log in</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

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

    // Password Strength & Validation
    const passInput = document.getElementById('password');
    const fillBar = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    const confirmInput = document.getElementById('cpassword');
    const matchText = document.getElementById('matchText');

    passInput.addEventListener('input', function() {
        const val = this.value;
        let score = 0;
        
        if (val.length >= 8) score++;
        if (val.match(/[a-z]/) && val.match(/[A-Z]/)) score++;
        if (val.match(/[0-9]/)) score++;
        if (val.match(/[^a-zA-Z0-9]/)) score++;

        // Reset
        fillBar.style.width = '0%';
        fillBar.style.backgroundColor = '#eee';
        strengthText.innerText = '';

        if(val.length > 0) {
            if (val.length < 8) {
                fillBar.style.width = '30%';
                fillBar.style.backgroundColor = '#ff4d4d'; // Red
                strengthText.innerText = 'Too short';
                strengthText.style.color = '#ff4d4d';
            } else if (score < 3) {
                fillBar.style.width = '60%';
                fillBar.style.backgroundColor = '#ffc107'; // Yellow
                strengthText.innerText = 'Weak';
                strengthText.style.color = '#ffc107';
            } else {
                fillBar.style.width = '100%';
                fillBar.style.backgroundColor = '#28a745'; // Green
                strengthText.innerText = 'Strong';
                strengthText.style.color = '#28a745';
            }
        }
    });

    confirmInput.addEventListener('input', function() {
        if (this.value !== passInput.value) {
            matchText.innerText = "Passwords do match"; // Wait, "do match"? Logic check.
            matchText.innerText = "Passwords do not match";
        } else {
            matchText.innerText = "";
        }
    });

    // Form Submit
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (passInput.value !== confirmInput.value) {
            alert('Passwords do not match!');
            return;
        }

        const formData = new FormData(this);
        formData.append('action', 'register');

        // Note: register.php is in root, auth_actions is in includes/
        fetch('includes/auth_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                alert('Registration Successful! Redirecting...');
                window.location.href = 'user/login.php';
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error connecting to server.');
        });
    });
</script>

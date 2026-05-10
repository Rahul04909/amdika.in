<?php
// Use centralized session config
require_once __DIR__ . '/session_config.php';

// Determine Assets Path
$current_script = $_SERVER['SCRIPT_NAME'];
$assets_path = 'assets/'; // Default for root files
if (strpos($current_script, '/user/') !== false) {
    $assets_path = '../assets/';
} elseif (strpos($current_script, '/pages/') !== false) {
    $assets_path = '../../assets/';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amadika - Online Shopping</title>
    <!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '967381769597169');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=967381769597169&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $assets_path; ?>css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo $assets_path; ?>images/amdika-logo.png">
    <!-- Custom Header Button Styles -->
    <style>
        :root {
            --header-gold: #D4A017;
            --header-dark: #2F3A3F;
        }
        .btn-header-login {
            color: var(--header-dark);
            background: transparent;
            border: 1px solid var(--header-dark);
            border-radius: 4px; /* Slightly rounded */
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-header-login:hover {
            background: var(--header-dark);
            color: #fff;
        }
        .btn-header-register {
            background: var(--header-gold);
            color: #fff;
            border: 1px solid var(--header-gold);
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(212, 160, 23, 0.2);
        }
        .btn-header-register:hover {
            background: #b8860b;
            border-color: #b8860b;
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(212, 160, 23, 0.3);
        }
    </style>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- Header Section -->
<header>
    <!-- Main Header (Logo, Search, Actions) -->
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-lg-2 col-md-3 col-6">
                    <a href="<?php echo $assets_path == '../assets/' ? '../index.php' : 'index.php'; ?>" class="brand-logo">
                        <img src="<?php echo $assets_path; ?>images/amdika-logo.png" alt="Amadika" class="img-fluid">
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="col-lg-6 col-md-12 order-lg-2 order-3 mt-2 mt-lg-0">
                    <div class="d-flex align-items-center">
                        <div class="search-bar-container flex-grow-1 me-2">
                            <form action="#" method="GET">
                                <div class="search-input-group">
                                    <input type="text" class="search-input" placeholder="Search..." name="search">
                                    <button class="search-btn" type="button">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Mobile Menu Toggle -->
                        <button class="navbar-toggler d-lg-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                            <i class="fa-solid fa-bars fs-2 text-dark"></i>
                        </button>
                    </div>
                </div>

                <!-- Header Actions (Wishlist, Cart, Account) -->
                <div class="col-lg-4 col-md-9 col-6 order-lg-3 order-2 text-end">
                    <div class="header-actions">
                        <!-- Wishlist -->
                        <a href="#" class="action-icon">
                            <!--<i class="fa-regular fa-heart"></i>-->
                            <!--<span class="icon-badge">0</span>-->
                        </a>
                        
                        <!-- Cart -->
                        <?php 
                        // Database connection for cart count
                        if (!isset($conn)) {
                            require_once __DIR__ . '/../database/db_config.php';
                        }
                        
                        $session_id = session_id();
                        $cart_count = 0;
                        
                        if ($session_id) {
                            $stmt = $conn->prepare("SELECT SUM(quantity) as count FROM cart WHERE session_id = ?");
                            $stmt->bind_param("s", $session_id);
                            $stmt->execute();
                            $res = $stmt->get_result()->fetch_assoc();
                            $cart_count = $res['count'] ?? 0;
                        }
                        ?>
                        <a href="cart.php" class="action-icon">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="icon-badge" id="headerCartCount"><?php echo $cart_count; ?></span>
                        </a>

                        <!-- User Account / Auth -->
                        <?php 
                        // Calculate relative path for links based on assets_path
                        // assets_path is either 'assets/', '../assets/', or '../../assets/'
                        $link_prefix = ($assets_path === 'assets/') ? '' : 
                                      (($assets_path === '../assets/') ? '../' : '../../');
                        
                        if(isset($_SESSION['user_id'])): 
                        ?>
                            <a href="<?php echo $link_prefix; ?>user/index.php" class="action-icon ms-2" title="My Account">
                                <i class="fa-regular fa-user"></i>
                            </a>
                        <?php else: ?>
                            <div class="d-none d-lg-flex align-items-center ms-3 gap-2">
                                <a href="javascript:void(0)" class="btn btn-sm btn-header-login px-4" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                                <a href="<?php echo $link_prefix; ?>register.php" class="btn btn-sm btn-header-register px-4">Register</a>
                            </div>
                            <!-- Mobile Icon Fallback (User) -->
                             <a href="javascript:void(0)" class="action-icon ms-2 d-lg-none" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fa-regular fa-user"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Header (Navigation) -->
    <div class="bottom-header">
        <div class="container">
            <div class="row align-items-center g-0">
                <!-- Categories Button -->
                <div class="col-lg-3 col-md-4 d-none d-lg-block">
                    <div class="categories-dropdown-wrapper">
                        <div class="all-categories-btn">
                            <span><i class="fa-solid fa-bars me-2"></i> All Categories</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <ul class="categories-dropdown-menu">
                            <?php
                            // Fetch Categories
                            if(isset($conn)) {
                                $h_cat_sql = "SELECT * FROM product_categories ORDER BY name ASC LIMIT 10"; // Limit to keep menu sane
                                $h_cat_res = $conn->query($h_cat_sql);
                                if($h_cat_res && $h_cat_res->num_rows > 0) {
                                    while($h_row = $h_cat_res->fetch_assoc()) {
                                        echo '<li><a href="products.php?category='.urlencode($h_row['slug']).'">'.htmlspecialchars($h_row['name']).' <i class="fa-solid fa-chevron-right"></i></a></li>';
                                    }
                                    echo '<li><a href="products.php" class="text-center justify-content-center text-primary fw-bold" style="padding-left:20px;">View All Categories</a></li>';
                                } else {
                                     echo '<li><a href="products.php">No products Found</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <!-- Navbar Links -->
                <div class="col-lg-9 col-md-12">
                    <nav class="navbar navbar-expand-lg p-0">
                        <div class="container-fluid p-0">
                            <div class="collapse navbar-collapse" id="mainNavbar">
                                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between w-100">
                                    <!-- Menu Links -->
                                    <div class="main-nav">
                                        <a href="<?php echo $link_prefix; ?>index.php" class="nav-link ps-lg-0">Home</a>
                                        <a href="<?php echo $link_prefix; ?>products.php" class="nav-link">Shop</a>
                                        <a href="<?php echo $link_prefix; ?>pages/about-us/index.php" class="nav-link">About us</a>
                                        <a href="<?php echo $link_prefix; ?>pages/contact-us/index.php" class="nav-link">Contact</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
                
            </div>
        </div>
    </div>
</header>

<<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 overflow-hidden" style="border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Panel: Brand / Benefits (Hidden on mobile) -->
                    <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-5" style="background: linear-gradient(135deg, #2F3A3F 0%, #1a1f22 100%); color: #fff;">
                        <div>
                            <h2 class="fw-bold mb-3" style="color: var(--header-gold);">Login</h2>
                            <p class="text-white-50 lh-base">Get access to your Orders, Wishlist and Recommendations</p>
                        </div>
                        <div class="mt-auto">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3 d-flex align-items-center small">
                                    <i class="fa-solid fa-truck-fast me-3" style="color: var(--header-gold);"></i>
                                    Manage your orders & track status
                                </li>
                                <li class="mb-3 d-flex align-items-center small">
                                    <i class="fa-solid fa-heart me-3" style="color: var(--header-gold);"></i>
                                    Sync your wishlist across devices
                                </li>
                                <li class="d-flex align-items-center small">
                                    <i class="fa-solid fa-bell me-3" style="color: var(--header-gold);"></i>
                                    Get notified on best deals & price drops
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Right Panel: Login Form -->
                    <div class="col-lg-7 col-12 p-4 p-md-5 bg-white position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close"></button>
                        
                        <!-- Login Type Tabs -->
                        <div class="d-flex justify-content-center mb-5">
                            <ul class="nav nav-pills custom-login-tabs" id="loginTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="mobile-tab" data-bs-toggle="pill" data-bs-target="#pills-mobile" type="button" role="tab">Mobile & OTP</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="email-tab" data-bs-toggle="pill" data-bs-target="#pills-email" type="button" role="tab">Email & Pass</button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content mt-2">
                            <!-- Mobile Login Tab -->
                            <div class="tab-pane fade show active" id="pills-mobile">
                                <div id="mobileStep1">
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-uppercase tracking-wider text-muted mb-2" style="font-size: 11px;">Enter Mobile Number</label>
                                        <div class="input-group premium-input-group">
                                            <span class="input-group-text bg-transparent border-end-0">
                                                <img src="https://upload.wikimedia.org/wikipedia/en/4/41/Flag_of_India.svg" alt="IN" style="width: 22px;">
                                                <span class="ms-2 fw-bold text-dark small">+91</span>
                                            </span>
                                            <input type="tel" class="form-control border-start-0 fs-6 fw-bold" id="loginMobile" placeholder="98765 43210" maxlength="10">
                                        </div>
                                        <div id="mobileError" class="text-danger small mt-2 fw-medium" style="display: none; font-size: 12px;"></div>
                                    </div>
                                    <button class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow-sm hover-lift" onclick="sendOTPRequest()" style="background: #2F3A3F;">CONTINUE</button>
                                </div>
                                
                                <div id="mobileStep2" style="display: none;">
                                    <div class="mb-4 text-center">
                                        <p class="small text-muted mb-3">Verification code sent to <span class="fw-bold text-dark" id="displayMobileNum"></span></p>
                                        <div class="d-flex justify-content-center gap-2 mb-2" id="otpInputContainer">
                                            <input type="text" class="form-control otp-input" maxlength="1" data-index="0">
                                            <input type="text" class="form-control otp-input" maxlength="1" data-index="1">
                                            <input type="text" class="form-control otp-input" maxlength="1" data-index="2">
                                            <input type="text" class="form-control otp-input" maxlength="1" data-index="3">
                                            <input type="text" class="form-control otp-input" maxlength="1" data-index="4">
                                            <input type="text" class="form-control otp-input" maxlength="1" data-index="5">
                                        </div>
                                        <input type="hidden" id="loginOTP">
                                        <div id="otpError" class="text-danger small mt-2 fw-medium" style="display: none; font-size: 12px;"></div>
                                        <div class="mt-3">
                                            <p class="small text-muted">Didn't receive the code? <a href="javascript:void(0)" class="text-primary text-decoration-none fw-bold" onclick="sendOTPRequest()">Resend</a></p>
                                        </div>
                                    </div>
                                    <button class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow-sm hover-lift" onclick="verifyOTPRequest()" style="background: #2F3A3F;">VERIFY & LOGIN</button>
                                    <button class="btn btn-link btn-sm w-100 mt-3 text-muted text-decoration-none small" onclick="backToMobileStep1()">Not your number? <span class="text-primary fw-bold">Change</span></button>
                                </div>
                            </div>

                            <!-- Email Login Tab -->
                            <div class="tab-pane fade" id="pills-email">
                                <form id="headerLoginForm">
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-uppercase tracking-wider text-muted mb-2" style="font-size: 11px;">Email Address</label>
                                        <div class="input-group premium-input-group">
                                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                                            <input type="email" name="email" class="form-control border-start-0" placeholder="name@example.com" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-uppercase tracking-wider text-muted mb-2" style="font-size: 11px;">Password</label>
                                        <div class="input-group premium-input-group">
                                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                                            <input type="password" name="password" id="headerPassInput" class="form-control border-start-0 border-end-0" placeholder="••••••••" required>
                                            <span class="input-group-text bg-transparent border-start-0 cursor-pointer" onclick="toggleHeaderPass()">
                                                <i class="fa-solid fa-eye-slash text-muted" id="headerPassIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="emailError" class="text-danger small mb-4 fw-medium" style="display: none; font-size: 12px;"></div>
                                    <button type="submit" class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow-sm hover-lift" style="background: #2F3A3F;">LOGIN</button>
                                </form>
                            </div>
                        </div>

                        <div class="text-center mt-5 pt-3">
                            <p class="small text-muted mb-0">New to Amadika? <a href="<?php echo $link_prefix; ?>register.php" class="text-primary fw-bold text-decoration-none">Create an Account</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-login-tabs { background: #f4f7f6; padding: 5px; border-radius: 12px; border: 1px solid #eef2f1; }
    .custom-login-tabs .nav-link { 
        color: #6c757d; 
        font-weight: 600; 
        font-size: 13px; 
        padding: 10px 25px; 
        border-radius: 10px; 
        transition: 0.3s;
    }
    .custom-login-tabs .nav-link.active { 
        background: #fff !important; 
        color: #2F3A3F !important; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.08); 
    }

    .premium-input-group { border: 1px solid #e0e0e0; border-radius: 10px; overflow: hidden; transition: 0.3s; }
    .premium-input-group:focus-within { border-color: #2F3A3F; box-shadow: 0 0 0 4px rgba(47, 58, 63, 0.1); }
    .premium-input-group .form-control { border: none; padding: 12px 15px; background: transparent; }
    .premium-input-group .input-group-text { border: none; padding-left: 15px; }

    .otp-input {
        width: 45px;
        height: 55px;
        text-align: center;
        font-size: 20px;
        font-weight: 700;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        transition: 0.3s;
    }
    .otp-input:focus { border-color: #2F3A3F; box-shadow: 0 0 0 4px rgba(47, 58, 63, 0.1); outline: none; }

    .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important; transition: 0.3s; }
</style>

<style>
    #loginModal .nav-pills .nav-link { color: #666; border-radius: 8px; padding: 10px; }
    #loginModal .nav-pills .nav-link.active { background-color: var(--header-dark); color: #fff; }
    #loginModal .form-control:focus { box-shadow: none; border-color: var(--header-gold); }
    #loginModal .input-group-text { border-color: #dee2e6; }
</style>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const AUTH_URL = '<?php echo $link_prefix; ?>includes/auth_actions.php';

    function toggleHeaderPass() {
        const input = document.getElementById('headerPassInput');
        const icon = document.getElementById('headerPassIcon');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function sendOTPRequest() {
        const mobile = document.getElementById('loginMobile').value;
        const errorDiv = document.getElementById('mobileError');
        errorDiv.style.display = 'none';

        if(mobile.length !== 10) {
            errorDiv.textContent = 'Please enter a valid 10-digit number';
            errorDiv.style.display = 'block';
            return;
        }

        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>SENDING...';

        const formData = new FormData();
        formData.append('action', 'send_otp');
        formData.append('mobile', mobile);

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            if(data.status === 'success') {
                document.getElementById('mobileStep1').style.display = 'none';
                document.getElementById('mobileStep2').style.display = 'block';
                document.getElementById('displayMobileNum').textContent = '+91 ' + mobile;
                // Focus first OTP input
                setTimeout(() => document.querySelector('.otp-input').focus(), 100);
            } else {
                errorDiv.textContent = data.message;
                errorDiv.style.display = 'block';
            }
        });
    }

    // OTP Input Logic (Auto-focus next)
    const otpInputs = document.querySelectorAll('.otp-input');
    otpInputs.forEach((input, index) => {
        input.addEventListener('keyup', (e) => {
            if (e.key >= 0 && e.key <= 9) {
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            } else if (e.key === 'Backspace') {
                if (index > 0) {
                    otpInputs[index - 1].focus();
                }
            }
            
            // Combine all inputs into one
            let fullOtp = "";
            otpInputs.forEach(inp => fullOtp += inp.value);
            document.getElementById('loginOTP').value = fullOtp;
        });
    });

    function verifyOTPRequest() {
        const mobile = document.getElementById('loginMobile').value;
        const otp = document.getElementById('loginOTP').value;
        const errorDiv = document.getElementById('otpError');
        errorDiv.style.display = 'none';

        if(otp.length !== 6) {
            errorDiv.textContent = 'Please enter 6-digit code';
            errorDiv.style.display = 'block';
            return;
        }

        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>VERIFYING...';

        const formData = new FormData();
        formData.append('action', 'verify_otp');
        formData.append('mobile', mobile);
        formData.append('otp', otp);

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                btn.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i>SUCCESS';
                setTimeout(() => location.reload(), 800);
            } else {
                btn.disabled = false;
                btn.innerHTML = originalText;
                errorDiv.textContent = data.message;
                errorDiv.style.display = 'block';
            }
        });
    }

    function backToMobileStep1() {
        document.getElementById('mobileStep2').style.display = 'none';
        document.getElementById('mobileStep1').style.display = 'block';
        // Clear OTP inputs
        otpInputs.forEach(inp => inp.value = "");
        document.getElementById('loginOTP').value = "";
    }

    // Traditional Login
    document.getElementById('headerLoginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const errorDiv = document.getElementById('emailError');
        errorDiv.style.display = 'none';

        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>LOGGING IN...';

        const formData = new FormData(this);
        formData.append('action', 'login');

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                btn.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i>SUCCESS';
                setTimeout(() => location.reload(), 800);
            } else {
                btn.disabled = false;
                btn.innerHTML = originalText;
                errorDiv.textContent = data.message;
                errorDiv.style.display = 'block';
            }
        });
    });

    function updateCartCount() {
        const badge = document.getElementById('headerCartCount');
        if(!badge) return;
        
        fetch('<?php echo $link_prefix; ?>includes/cart_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=count'
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                badge.textContent = data.count;
            }
        });
    }
</script>

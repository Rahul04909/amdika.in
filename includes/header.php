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
        .main-header { padding: 20px 0; }
        .bottom-header { 
            padding: 10px 0; 
            background: #fff;
            border-top: 1px solid #f0f0f0;
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
    <div class="bottom-header border-top">
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

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden" style="border-radius: 15px;">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-12 p-4 p-md-5">
                        <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                        
                        <div class="text-center mb-4">
                            <h3 class="fw-bold mb-1" style="color: var(--header-dark);">Welcome to Amadika</h3>
                            <p class="text-muted small">Login to your account for a better experience</p>
                        </div>

                        <!-- Login Type Tabs -->
                        <ul class="nav nav-pills nav-justified mb-4" id="loginTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="mobile-tab" data-bs-toggle="pill" data-bs-target="#pills-mobile" type="button" role="tab" style="font-size: 14px; font-weight: 600;">Mobile & OTP</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="email-tab" data-bs-toggle="pill" data-bs-target="#pills-email" type="button" role="tab" style="font-size: 14px; font-weight: 600;">Email & Pass</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Mobile Login Tab -->
                            <div class="tab-pane fade show active" id="pills-mobile">
                                <div id="mobileStep1">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Mobile Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-muted">+91</span>
                                            <input type="tel" class="form-control bg-light border-start-0" id="loginMobile" placeholder="Enter 10 digit number" maxlength="10">
                                        </div>
                                    </div>
                                    <button class="btn btn-header-register w-100 py-2 mt-2" onclick="sendOTPRequest()">Send OTP</button>
                                </div>
                                <div id="mobileStep2" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Enter 6-Digit OTP</label>
                                        <input type="text" class="form-control bg-light text-center fw-bold fs-4" id="loginOTP" placeholder="0 0 0 0 0 0" maxlength="6">
                                        <div class="text-center mt-2">
                                            <a href="javascript:void(0)" class="small text-primary" onclick="sendOTPRequest()">Resend OTP</a>
                                        </div>
                                    </div>
                                    <button class="btn btn-header-register w-100 py-2 mt-2" onclick="verifyOTPRequest()">Verify & Login</button>
                                    <button class="btn btn-link btn-sm w-100 mt-2 text-muted" onclick="backToMobileStep1()">Change Number</button>
                                </div>
                            </div>

                            <!-- Email Login Tab -->
                            <div class="tab-pane fade" id="pills-email">
                                <form id="headerLoginForm">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Email Address</label>
                                        <input type="email" name="email" class="form-control bg-light" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Password</label>
                                        <input type="password" name="password" class="form-control bg-light" required>
                                    </div>
                                    <button type="submit" class="btn btn-header-register w-100 py-2 mt-2">Login Now</button>
                                </form>
                            </div>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="small text-muted mb-0">New to Amadika? <a href="<?php echo $link_prefix; ?>register.php" class="text-primary fw-bold">Create Account</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

    function sendOTPRequest() {
        const mobile = document.getElementById('loginMobile').value;
        if(mobile.length !== 10) {
            Swal.fire('Error', 'Please enter a valid 10-digit number', 'error');
            return;
        }

        const btn = event.target;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

        const formData = new FormData();
        formData.append('action', 'send_otp');
        formData.append('mobile', mobile);

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = 'Send OTP';
            if(data.status === 'success') {
                document.getElementById('mobileStep1').style.display = 'none';
                document.getElementById('mobileStep2').style.display = 'block';
                Swal.fire('Success', data.message, 'success');
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    }

    function verifyOTPRequest() {
        const mobile = document.getElementById('loginMobile').value;
        const otp = document.getElementById('loginOTP').value;
        if(otp.length !== 6) {
            Swal.fire('Error', 'Please enter 6-digit OTP', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'verify_otp');
        formData.append('mobile', mobile);
        formData.append('otp', otp);

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                location.reload();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    }

    function backToMobileStep1() {
        document.getElementById('mobileStep2').style.display = 'none';
        document.getElementById('mobileStep1').style.display = 'block';
    }

    // Traditional Login
    document.getElementById('headerLoginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'login');

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                location.reload();
            } else {
                Swal.fire('Error', data.message, 'error');
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

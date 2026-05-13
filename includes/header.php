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

        /* --- Search Suggestions CSS --- */
        .search-bar-container { position: relative; }
        .search-suggestions-box {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            z-index: 1000;
            margin-top: 5px;
            display: none;
            overflow: hidden;
            border: 1px solid #eee;
        }
        .suggestion-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            text-decoration: none !important;
            color: #333;
            border-bottom: 1px solid #f8f9fa;
            transition: background 0.2s;
        }
        .suggestion-item:last-child { border-bottom: none; }
        .suggestion-item:hover { background: #f8f9fb; }
        .suggestion-img {
            width: 45px;
            height: 45px;
            border-radius: 4px;
            object-fit: cover;
            margin-right: 12px;
            background: #f5f5f5;
        }
        .suggestion-info { flex: 1; min-width: 0; }
        .suggestion-name {
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #222;
        }
        .suggestion-price {
            font-size: 13px;
            color: var(--header-gold);
            font-weight: 700;
            margin: 2px 0 0 0;
        }
        .view-all-results {
            display: block;
            padding: 12px;
            text-align: center;
            background: #f8f9fb;
            font-size: 13px;
            font-weight: 700;
            color: #1a2b4e;
            text-decoration: none !important;
        }
        .view-all-results:hover { background: #eee; }
        .no-results { padding: 20px; text-align: center; color: #888; font-size: 14px; }
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
                            <form action="<?php echo $link_prefix; ?>products.php" method="GET" id="headerSearchForm">
                                <div class="search-input-group">
                                    <input type="text" class="search-input" placeholder="Search products..." name="search" id="headerSearchInput" autocomplete="off">
                                    <button class="search-btn" type="submit">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </form>
                            <div id="searchSuggestions" class="search-suggestions-box"></div>
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
                        <a href="javascript:void(0)" onclick="openCartSidebar()" class="action-icon">
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
                                <a href="javascript:void(0)" class="btn btn-sm btn-header-register px-4" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a>
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

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 overflow-hidden" style="border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Panel: Brand / Benefits -->
                    <div class="col-lg-5 d-none d-lg-flex flex-column p-4 p-xl-5" style="background: linear-gradient(135deg, #2F3A3F 0%, #1a1f22 100%); color: #fff;">
                        <div class="mb-4">
                            <img src="<?php echo $assets_path; ?>images/amdika-logo.png" alt="Amadika" style="height: 35px; filter: brightness(0) invert(1);">
                        </div>
                        <div class="mt-2">
                            <h3 class="fw-bold mb-2" style="color: var(--header-gold); font-size: 24px;">Login</h3>
                            <p class="text-white-50 small lh-sm mb-0">Get access to your Orders, Wishlist and Recommendations</p>
                        </div>
                        <div class="mt-auto pt-4">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3 d-flex align-items-center" style="font-size: 13px;">
                                    <i class="fa-solid fa-truck-fast me-3" style="color: var(--header-gold); width: 20px;"></i>
                                    <span>Manage your orders & track status</span>
                                </li>
                                <li class="mb-3 d-flex align-items-center" style="font-size: 13px;">
                                    <i class="fa-solid fa-heart me-3" style="color: var(--header-gold); width: 20px;"></i>
                                    <span>Sync your wishlist across devices</span>
                                </li>
                                <li class="d-flex align-items-center" style="font-size: 13px;">
                                    <i class="fa-solid fa-bell me-3" style="color: var(--header-gold); width: 20px;"></i>
                                    <span>Get notified on best deals & price drops</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Right Panel: Login Form -->
                    <div class="col-lg-7 col-12 p-4 p-md-5 bg-white position-relative d-flex flex-column justify-content-center">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                        
                        <!-- Login Type Tabs -->
                        <div class="d-flex justify-content-center mb-4">
                            <ul class="nav nav-pills custom-login-tabs" id="loginTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="mobile-tab" data-bs-toggle="pill" data-bs-target="#pills-mobile" type="button" role="tab">Mobile & OTP</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="email-tab" data-bs-toggle="pill" data-bs-target="#pills-email" type="button" role="tab">Email & Pass</button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <!-- Mobile Login Tab -->
                            <div class="tab-pane fade show active" id="pills-mobile">
                                <div id="mobileStep1">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-uppercase tracking-wider text-muted mb-2" style="font-size: 10px;">Enter Mobile Number</label>
                                        <div class="input-group premium-input-group">
                                            <span class="input-group-text bg-transparent border-end-0">
                                                <img src="https://upload.wikimedia.org/wikipedia/en/4/41/Flag_of_India.svg" alt="IN" style="width: 20px;">
                                                <span class="ms-2 fw-bold text-dark small">+91</span>
                                            </span>
                                            <input type="tel" class="form-control border-start-0 fs-6 fw-bold" id="loginMobile" placeholder="98765 43210" maxlength="10">
                                        </div>
                                        <div id="mobileError" class="text-danger small mt-2 fw-medium" style="display: none; font-size: 11px;"></div>
                                    </div>
                                    <button class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow-sm hover-lift" onclick="sendOTPRequest()" style="background: #2F3A3F; font-size: 13px; letter-spacing: 1px;">CONTINUE</button>
                                </div>
                                
                                <div id="mobileStep2" style="display: none;">
                                    <div class="mb-3 text-center">
                                        <p class="small text-muted mb-3">Code sent to <span class="fw-bold text-dark" id="displayMobileNum"></span></p>
                                        <div class="d-flex justify-content-center gap-2 mb-2" id="otpInputContainer">
                                            <input type="text" class="form-control otp-input" maxlength="1" data-index="0">
                                            <input type="text" class="form-control otp-input" maxlength="1" data-index="1">
                                            <input type="text" class="form-control otp-input" maxlength="1" data-index="2">
                                            <input type="text" class="form-control otp-input" maxlength="1" data-index="3">
                                            <input type="text" class="form-control otp-input" maxlength="1" data-index="4">
                                            <input type="text" class="form-control otp-input" maxlength="1" data-index="5">
                                        </div>
                                        <input type="hidden" id="loginOTP">
                                        
                                        <div class="text-center mb-3 mt-3">
                                            <p class="small text-muted mb-0" id="loginTimerContainer">Resend OTP in <span class="fw-bold text-dark" id="loginTimer">00:59</span></p>
                                            <a href="javascript:void(0)" id="resendLoginBtn" class="text-primary fw-bold small text-decoration-none" style="display: none;" onclick="resendLoginOTP()">Resend OTP</a>
                                        </div>

                                        <div id="otpError" class="text-danger small mt-2 fw-medium" style="display: none; font-size: 11px;"></div>
                                    </div>
                                    <button class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow-sm hover-lift" onclick="verifyOTPRequest()" style="background: #2F3A3F; font-size: 13px; letter-spacing: 1px;">VERIFY & LOGIN</button>
                                    <button class="btn btn-link btn-sm w-100 mt-2 text-muted text-decoration-none small" onclick="backToMobileStep1()">Not your number? <span class="text-primary fw-bold">Change</span></button>
                                </div>
                            </div>

                            <!-- Email Login Tab -->
                            <div class="tab-pane fade" id="pills-email">
                                <form id="headerLoginForm">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-uppercase tracking-wider text-muted mb-2" style="font-size: 10px;">Email Address</label>
                                        <div class="input-group premium-input-group">
                                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                                            <input type="email" name="email" class="form-control border-start-0" placeholder="name@example.com" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-uppercase tracking-wider text-muted mb-2" style="font-size: 10px;">Password</label>
                                        <div class="input-group premium-input-group">
                                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                                            <input type="password" name="password" id="headerPassInput" class="form-control border-start-0 border-end-0" placeholder="••••••••" required>
                                            <span class="input-group-text bg-transparent border-start-0 cursor-pointer" onclick="toggleHeaderPass()">
                                                <i class="fa-solid fa-eye-slash text-muted" id="headerPassIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="emailError" class="text-danger small mb-3 fw-medium" style="display: none; font-size: 11px;"></div>
                                    <button type="submit" class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow-sm hover-lift" style="background: #2F3A3F; font-size: 13px; letter-spacing: 1px;">LOGIN</button>
                                </form>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <p class="small text-muted mb-0" style="font-size: 12px;">New to Amadika? <a href="javascript:void(0)" class="text-primary fw-bold text-decoration-none" data-bs-toggle="modal" data-bs-target="#registerModal">Create an Account</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 overflow-hidden" style="border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Panel -->
                    <div class="col-lg-5 d-none d-lg-flex flex-column p-4 p-xl-5" style="background: linear-gradient(135deg, #2F3A3F 0%, #1a1f22 100%); color: #fff;">
                        <div class="mb-4">
                            <img src="<?php echo $assets_path; ?>images/amdika-logo.png" alt="Amadika" style="height: 35px; filter: brightness(0) invert(1);">
                        </div>
                        <div class="mt-2">
                            <h3 class="fw-bold mb-2" style="color: var(--header-gold); font-size: 24px;">Register</h3>
                            <p class="text-white-50 small lh-sm mb-4">Join our community to unlock exclusive deals and faster checkout.</p>
                            
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3 d-flex align-items-center" style="font-size: 13px;">
                                    <i class="fa-solid fa-circle-check me-3" style="color: var(--header-gold); width: 20px;"></i>
                                    <span>Exclusive Member Discounts</span>
                                </li>
                                <li class="mb-3 d-flex align-items-center" style="font-size: 13px;">
                                    <i class="fa-solid fa-circle-check me-3" style="color: var(--header-gold); width: 20px;"></i>
                                    <span>Fast & Secure Checkout</span>
                                </li>
                                <li class="d-flex align-items-center" style="font-size: 13px;">
                                    <i class="fa-solid fa-circle-check me-3" style="color: var(--header-gold); width: 20px;"></i>
                                    <span>24/7 Customer Support</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Right Panel -->
                    <div class="col-lg-7 col-12 p-4 p-md-5 bg-white position-relative d-flex flex-column justify-content-center" style="max-height: 90vh; overflow-y: auto;">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                        
                        <div id="registerStep1">
                            <div class="mb-3 text-center">
                                <h4 class="fw-bold text-dark mb-1">Create Account</h4>
                                <p class="small text-muted" style="font-size: 12px;">Complete all details to register</p>
                            </div>
                            <form id="mainRegisterForm">
                                <div class="row g-2">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small fw-bold text-uppercase text-muted mb-1" style="font-size: 9px;">Full Name</label>
                                        <div class="input-group premium-input-group">
                                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small fw-bold text-uppercase text-muted mb-1" style="font-size: 9px;">Email Address</label>
                                        <div class="input-group premium-input-group">
                                            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small fw-bold text-uppercase text-muted mb-1" style="font-size: 9px;">Mobile Number</label>
                                        <div class="input-group premium-input-group">
                                            <span class="input-group-text bg-transparent border-end-0 px-2">
                                                <span class="fw-bold text-dark small" style="font-size: 11px;">+91</span>
                                            </span>
                                            <input type="tel" name="mobile" id="regMobile" class="form-control border-start-0 fw-bold" placeholder="9876543210" maxlength="10" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small fw-bold text-uppercase text-muted mb-1" style="font-size: 9px;">Password</label>
                                        <div class="input-group premium-input-group">
                                            <input type="password" name="password" id="regPassInput" class="form-control border-end-0" placeholder="••••••••" required>
                                            <span class="input-group-text bg-transparent border-start-0 cursor-pointer px-2" onclick="toggleRegPass()">
                                                <i class="fa-solid fa-eye-slash text-muted small" id="regPassIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-uppercase text-muted mb-1" style="font-size: 9px;">Full Address</label>
                                    <div class="input-group premium-input-group">
                                        <input type="text" name="address" class="form-control" placeholder="Flat, Street, Area" required>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label small fw-bold text-uppercase text-muted mb-1" style="font-size: 9px;">City</label>
                                        <div class="input-group premium-input-group">
                                            <input type="text" name="city" class="form-control px-2" placeholder="City" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label small fw-bold text-uppercase text-muted mb-1" style="font-size: 9px;">State</label>
                                        <div class="input-group premium-input-group">
                                            <input type="text" name="state" class="form-control px-2" placeholder="State" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label small fw-bold text-uppercase text-muted mb-1" style="font-size: 9px;">Pincode</label>
                                        <div class="input-group premium-input-group">
                                            <input type="text" name="pincode" class="form-control px-2" placeholder="123456" maxlength="6" required>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="country" value="India">

                                <div id="regError" class="text-danger small mb-3 fw-medium" style="display: none; font-size: 11px;"></div>
                                <button type="submit" class="btn btn-dark w-100 py-2 fw-bold rounded-3 shadow-sm hover-lift" style="background: #2F3A3F; border: none; font-size: 13px; letter-spacing: 1px;">SEND OTP</button>
                            </form>
                        </div>

                        <div id="registerStep2" style="display: none;">
                            <div class="mb-4 text-center">
                                <h4 class="fw-bold text-dark mb-1">Verify Mobile</h4>
                                <p class="small text-muted">Enter the code sent to <span class="fw-bold text-dark" id="displayRegMobile"></span></p>
                            </div>
                            <div class="d-flex justify-content-center gap-2 mb-4" id="regOtpInputContainer">
                                <input type="text" class="form-control otp-input reg-otp-input" maxlength="1" data-index="0">
                                <input type="text" class="form-control otp-input reg-otp-input" maxlength="1" data-index="1">
                                <input type="text" class="form-control otp-input reg-otp-input" maxlength="1" data-index="2">
                                <input type="text" class="form-control otp-input reg-otp-input" maxlength="1" data-index="3">
                                <input type="text" class="form-control otp-input reg-otp-input" maxlength="1" data-index="4">
                                <input type="text" class="form-control otp-input reg-otp-input" maxlength="1" data-index="5">
                            </div>
                            <input type="hidden" id="regOTPValue">
                            
                            <div class="text-center mb-4">
                                <p class="small text-muted mb-0" id="regTimerContainer">Resend OTP in <span class="fw-bold text-dark" id="regTimer">00:59</span></p>
                                <a href="javascript:void(0)" id="resendRegBtn" class="text-primary fw-bold small text-decoration-none" style="display: none;" onclick="resendRegOTP()">Resend OTP</a>
                            </div>

                            <div id="regOtpError" class="text-danger small mt-n2 mb-4 text-center fw-medium" style="display: none; font-size: 11px;"></div>
                            <button class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow-sm hover-lift" onclick="verifyRegOTP()" style="background: #2F3A3F; border: none; font-size: 13px; letter-spacing: 1px;">VERIFY & REGISTER</button>
                            <button class="btn btn-link btn-sm w-100 mt-3 text-muted text-decoration-none small" onclick="backToRegStep1()">Edit Details</button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="small text-muted mb-0" style="font-size: 11px;">Already have an account? <a href="javascript:void(0)" class="text-primary fw-bold text-decoration-none" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></p>
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
    /* Blue focus for register modal */
    #registerModal .premium-input-group:focus-within { border-color: #2F6FED; box-shadow: 0 0 0 4px rgba(47, 111, 237, 0.1); }
    
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
    #registerModal .otp-input:focus { border-color: #2F6FED; box-shadow: 0 0 0 4px rgba(47, 111, 237, 0.1); }

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

    let loginTimerInterval;
    function startLoginTimer(duration) {
        clearInterval(loginTimerInterval);
        let timer = duration, minutes, seconds;
        const timerDisplay = document.getElementById('loginTimer');
        const timerContainer = document.getElementById('loginTimerContainer');
        const resendBtn = document.getElementById('resendLoginBtn');
        
        timerContainer.style.display = 'block';
        resendBtn.style.display = 'none';

        loginTimerInterval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            timerDisplay.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(loginTimerInterval);
                timerContainer.style.display = 'none';
                resendBtn.style.display = 'block';
            }
        }, 1000);
    }

    function resendLoginOTP() {
        sendOTPRequest(true); // Special flag for resend
    }

    function sendOTPRequest(isResend = false) {
        const mobile = document.getElementById('loginMobile').value;
        const errorDiv = document.getElementById('mobileError');
        errorDiv.style.display = 'none';

        if(mobile.length !== 10) {
            errorDiv.textContent = 'Please enter a valid 10-digit number';
            errorDiv.style.display = 'block';
            return;
        }

        let btn = document.querySelector('button[onclick="sendOTPRequest()"]');
        if(isResend) {
            btn = document.getElementById('resendLoginBtn');
            btn.innerHTML = 'Sending...';
        } else {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>SENDING...';
        }

        const formData = new FormData();
        formData.append('action', 'send_otp');
        formData.append('mobile', mobile);

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(!isResend) {
                btn.disabled = false;
                btn.innerHTML = 'CONTINUE';
            } else {
                btn.innerHTML = 'Resend OTP';
            }

            if(data.status === 'success') {
                document.getElementById('mobileStep1').style.display = 'none';
                document.getElementById('mobileStep2').style.display = 'block';
                document.getElementById('displayMobileNum').textContent = '+91 ' + mobile;
                startLoginTimer(60);
                setTimeout(() => document.querySelector('.otp-input').focus(), 100);
            } else {
                errorDiv.textContent = data.message;
                errorDiv.style.display = 'block';
                if(isResend) alert(data.message);
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
                clearInterval(loginTimerInterval);
                setTimeout(() => {
                    const redirectUrl = data.redirect ? '<?php echo $link_prefix; ?>' + data.redirect : '<?php echo $link_prefix; ?>user/index.php';
                    window.location.href = redirectUrl;
                }, 1000);
            } else {
                btn.disabled = false;
                btn.innerHTML = 'VERIFY & LOGIN';
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
        clearInterval(loginTimerInterval);
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
                setTimeout(() => {
                    const redirectUrl = data.redirect ? '<?php echo $link_prefix; ?>' + data.redirect : '<?php echo $link_prefix; ?>user/index.php';
                    window.location.href = redirectUrl;
                }, 800);
            } else {
                btn.disabled = false;
                btn.innerHTML = originalText;
                errorDiv.textContent = data.message;
                errorDiv.style.display = 'block';
            }
        });
    });

    let regTimerInterval;
    function startRegTimer(duration) {
        clearInterval(regTimerInterval);
        let timer = duration, minutes, seconds;
        const timerDisplay = document.getElementById('regTimer');
        const timerContainer = document.getElementById('regTimerContainer');
        const resendBtn = document.getElementById('resendRegBtn');
        
        timerContainer.style.display = 'block';
        resendBtn.style.display = 'none';

        regTimerInterval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            timerDisplay.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(regTimerInterval);
                timerContainer.style.display = 'none';
                resendBtn.style.display = 'block';
            }
        }, 1000);
    }

    function resendRegOTP() {
        const formData = new FormData(document.getElementById('mainRegisterForm'));
        formData.append('action', 'send_register_otp');
        
        const resendBtn = document.getElementById('resendRegBtn');
        const originalText = resendBtn.innerHTML;
        resendBtn.innerHTML = 'Sending...';
        resendBtn.style.pointerEvents = 'none';

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            resendBtn.innerHTML = originalText;
            resendBtn.style.pointerEvents = 'auto';
            if(data.status === 'success') {
                startRegTimer(60);
            } else {
                alert(data.message);
            }
        });
    }

    function toggleRegPass() {
        const input = document.getElementById('regPassInput');
        const icon = document.getElementById('regPassIcon');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }

    document.getElementById('mainRegisterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const errorDiv = document.getElementById('regError');
        errorDiv.style.display = 'none';

        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>SENDING OTP...';

        const formData = new FormData(this);
        formData.append('action', 'send_register_otp');

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            if(data.status === 'success') {
                document.getElementById('registerStep1').style.display = 'none';
                document.getElementById('registerStep2').style.display = 'block';
                document.getElementById('displayRegMobile').textContent = '+91 ' + document.getElementById('regMobile').value;
                startRegTimer(60);
                setTimeout(() => document.querySelector('.reg-otp-input').focus(), 100);
            } else {
                errorDiv.textContent = data.message;
                errorDiv.style.display = 'block';
            }
        });
    });

    const regOtpInputs = document.querySelectorAll('.reg-otp-input');
    regOtpInputs.forEach((input, index) => {
        input.addEventListener('keyup', (e) => {
            if (e.key >= 0 && e.key <= 9) {
                if (index < regOtpInputs.length - 1) regOtpInputs[index + 1].focus();
            } else if (e.key === 'Backspace') {
                if (index > 0) regOtpInputs[index - 1].focus();
            }
            let fullOtp = "";
            regOtpInputs.forEach(inp => fullOtp += inp.value);
            document.getElementById('regOTPValue').value = fullOtp;
        });
    });

    function verifyRegOTP() {
        const otp = document.getElementById('regOTPValue').value;
        const errorDiv = document.getElementById('regOtpError');
        errorDiv.style.display = 'none';

        if(otp.length !== 6) {
            errorDiv.textContent = 'Enter 6-digit code';
            errorDiv.style.display = 'block';
            return;
        }

        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>VERIFYING...';

        const formData = new FormData(document.getElementById('mainRegisterForm'));
        formData.append('action', 'verify_and_register');
        formData.append('otp', otp);

        fetch(AUTH_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                btn.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i>SUCCESS';
                clearInterval(regTimerInterval);
                setTimeout(() => {
                    const redirectUrl = data.redirect ? '<?php echo $link_prefix; ?>' + data.redirect : '<?php echo $link_prefix; ?>user/index.php';
                    window.location.href = redirectUrl;
                }, 1000);
            } else {
                btn.disabled = false;
                btn.innerHTML = originalText;
                errorDiv.textContent = data.message;
                errorDiv.style.display = 'block';
            }
        });
    }

    function backToRegStep1() {
        document.getElementById('registerStep2').style.display = 'none';
        document.getElementById('registerStep1').style.display = 'block';
        regOtpInputs.forEach(inp => inp.value = "");
        document.getElementById('regOTPValue').value = "";
        clearInterval(regTimerInterval);
    }

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

    // --- Search Suggestions Logic ---
    const searchInput = document.getElementById('headerSearchInput');
    const suggestionsBox = document.getElementById('searchSuggestions');
    let searchTimeout = null;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(searchTimeout);

            if (query.length < 2) {
                suggestionsBox.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch('<?php echo $link_prefix; ?>api/search-suggestions.php?q=' + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        let html = '';
                        data.forEach(item => {
                            html += `
                                <a href="<?php echo $link_prefix; ?>product-details.php?slug=${item.slug}" class="suggestion-item">
                                    <img src="<?php echo $link_prefix; ?>${item.image}" class="suggestion-img">
                                    <div class="suggestion-info">
                                        <p class="suggestion-name">${item.name}</p>
                                        <p class="suggestion-price">${item.price}</p>
                                    </div>
                                </a>
                            `;
                        });
                        html += `<a href="<?php echo $link_prefix; ?>products.php?search=${encodeURIComponent(query)}" class="view-all-results">View All Results</a>`;
                        suggestionsBox.innerHTML = html;
                        suggestionsBox.style.display = 'block';
                    } else {
                        suggestionsBox.innerHTML = '<div class="no-results">No products found</div>';
                        suggestionsBox.style.display = 'block';
                    }
                });
            }, 300);
        });

        // Close suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.style.display = 'none';
            }
        });
    }
</script>

<?php include 'cart_sidebar.php'; ?>

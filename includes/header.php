<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amadika - Online Shopping</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/amdika-logo.png">
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
                    <a href="index.php" class="brand-logo">
                        <img src="assets/images/amdika-logo.png" alt="Amadika" class="img-fluid">
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="col-lg-7 col-md-12 order-lg-2 order-3 mt-2 mt-lg-0">
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
                <div class="col-lg-3 col-md-9 col-6 order-lg-3 order-2 text-end">
                    <div class="header-actions">
                        <!-- Wishlist -->
                        <a href="#" class="action-icon">
                            <i class="fa-regular fa-heart"></i>
                            <span class="icon-badge">0</span>
                        </a>
                        
                        <!-- Cart -->
                        <?php 
                        $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
                        ?>
                        <a href="cart.php" class="action-icon">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="icon-badge" id="headerCartCount"><?php echo $cart_count; ?></span>
                        </a>

                        <!-- User Account -->
                        <a href="#" class="action-icon ms-2">
                            <i class="fa-regular fa-user"></i>
                        </a>
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
                    <div class="all-categories-btn">
                        <span><i class="fa-solid fa-bars me-2"></i> All Categories</span>
                        <i class="fa-solid fa-chevron-down"></i>
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
                                        <a href="#" class="nav-link ps-lg-0">Home</a>
                                        <a href="#" class="nav-link">Shop</a>
                                        <a href="#" class="nav-link">About us</a>
                                        <a href="#" class="nav-link">Contact</a>
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
<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateCartCount() {
        const badge = document.getElementById('headerCartCount');
        if(!badge) return;
        
        fetch('includes/cart_actions.php', {
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

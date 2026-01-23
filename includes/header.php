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
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Header Section -->
<header>
    <!-- Top Contact/Info Bar (Optional, can be added later if needed) -->

    <!-- Main Header (Logo, Search, User) -->
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-lg-3 col-md-3 col-6">
                    <a href="index.php" class="brand-logo">
                        <img src="assets/images/amdika-logo.png" alt="Amadika" class="img-fluid" style="max-height: 50px;">
                    </a>
                </div>

                <!-- Search Bar (Hidden on Mobile initially, or stacked) -->
                <div class="col-lg-6 col-md-12 order-lg-2 order-3 mt-3 mt-lg-0">
                    <div class="search-bar-container mx-auto">
                        <form action="#" method="GET">
                            <div class="search-input-group d-flex align-items-center">
                                <div class="input-icon px-3 text-muted">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <input type="text" class="search-input" placeholder="Search your item" name="search">
                            </div>
                        </form>
                    </div>
                </div>

                <!-- User Account Actions -->
                <div class="col-lg-3 col-md-9 col-6 order-lg-3 order-2 text-end">
                    <div class="header-actions d-flex justify-content-end align-items-center">
                        <a href="#" class="action-item">
                            <i class="fa-regular fa-user icon"></i>
                            <span class="d-none d-md-inline">LOGIN / REGISTER</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Header (Navigation) -->
    <div class="bottom-header">
        <div class="container">
            <div class="row align-items-center">
                <!-- Categories Button -->
                <div class="col-lg-3 col-md-6 col-6">
                    <a href="#" class="all-categories-btn rounded">
                        <span><i class="fa-solid fa-bars me-2"></i> All Categories</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </a>
                </div>

                <!-- Navbar Links (Toggleable) -->
                <div class="col-lg-9 col-md-6 col-6">
                    <div class="d-flex justify-content-end d-lg-none">
                        <button class="btn btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse d-lg-block" id="mainNavbar">
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between w-100">
                            <!-- Main Links -->
                            <nav class="main-nav d-flex flex-column flex-lg-row">
                                <a href="#" class="nav-link">Home</a>
                                <a href="#" class="nav-link">Blog</a>
                                <a href="#" class="nav-link">Contacts</a>
                                <a href="#" class="nav-link">About Us</a>
                                <a href="#" class="nav-link">Auction</a>
                            </nav>

                            <!-- Right Side Deals/Zone -->
                            <div class="right-nav-items d-flex flex-column flex-lg-row align-items-lg-center mt-3 mt-lg-0 pb-3 pb-lg-0">
                                <a href="#" class="nav-item-link">
                                    <i class="fa-solid fa-user-plus icon"></i> New User Zone
                                </a>
                                <a href="#" class="nav-item-link">
                                    <i class="fa-solid fa-store icon"></i> Daily Deals
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<br>
<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

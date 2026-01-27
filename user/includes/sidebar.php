<?php
require_once dirname(dirname(__DIR__)) . '/includes/session_config.php';
// Get current page name for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    .user-sidebar {
        background: #fff;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.15);
        border-radius: 2px;
        overflow: hidden;
    }
    
    .user-profile-brief {
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
    }
    .user-avatar-small {
        width: 50px; height: 50px;
        border-radius: 50%;
        background: #f0f0f0; /* Default if no image */
        object-fit: cover;
    }
    .hello-text { font-size: 12px; color: #878787; margin-bottom: 2px; }
    .user-name-bold { font-size: 16px; font-weight: 600; color: #212121; }

    .nav-section { border-bottom: 1px solid #f0f0f0; padding-bottom: 10px; }
    .nav-section:last-child { border-bottom: none; }
    
    .nav-header {
        padding: 15px 20px 5px;
        font-size: 12px; font-weight: 500; color: #878787; text-transform: uppercase;
        display: flex; align-items: center; gap: 10px;
    }
    .nav-link-item {
        display: block;
        padding: 10px 20px 10px 45px; /* Indent for items */
        font-size: 14px;
        color: #212121;
        text-decoration: none;
        transition: all 0.2s;
    }
    .nav-link-item:hover { background: #f5faff; color: #2874f0; }
    .nav-link-item.active { background: #f5faff; color: #2874f0; font-weight: 600; }
    
    .logout-btn {
         padding: 15px 20px;
         color: #878787;
         font-weight: 500;
         display: block;
         text-decoration: none;
         border-top: 1px solid #f0f0f0;
         transition: 0.2s;
    }
    .logout-btn:hover { color: #2874f0; }
</style>

<div class="user-sidebar">
    <!-- User Brief -->
    <div class="user-profile-brief">
        <img src="../assets/images/user-avtar.avif" class="user-avatar-small" alt="User">
        <div>
            <div class="hello-text">Hello,</div>
            <div class="user-name-bold"><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User'; ?></div>
        </div>
    </div>

    <!-- Orders -->
    <div class="nav-section">
        <div class="nav-header"><i class="fas fa-box text-primary"></i> MY ORDERS</div>
        <a href="orders.php" class="nav-link-item <?php echo $current_page == 'orders.php' ? 'active' : ''; ?>">View All Orders</a>
    </div>

    <!-- Account Settings -->
    <div class="nav-section">
        <div class="nav-header"><i class="fas fa-user text-primary"></i> ACCOUNT SETTINGS</div>
        <a href="index.php" class="nav-link-item <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">Profile Information</a>
        <a href="addresses.php" class="nav-link-item <?php echo $current_page == 'addresses.php' ? 'active' : ''; ?>">Manage Addresses</a>
        <a href="#" class="nav-link-item">PAN Card Information</a>
    </div>

    <!-- Payments -->
    <div class="nav-section">
        <div class="nav-header"><i class="fas fa-wallet text-primary"></i> PAYMENTS</div>
        <a href="#" class="nav-link-item">Gift Cards</a>
        <a href="#" class="nav-link-item">Saved UPI</a>
        <a href="#" class="nav-link-item">Saved Cards</a>
    </div>

    <!-- My Chat -->
    <div class="nav-section">
        <div class="nav-header"><i class="fas fa-comments text-primary"></i> MY CHAT</div>
        <a href="#" class="nav-link-item">Support Chat</a>
    </div>
    
    <!-- Logout -->
    <a href="logout.php" class="logout-btn"><i class="fas fa-power-off me-2"></i> Logout</a>
</div>

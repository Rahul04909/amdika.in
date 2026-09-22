<?php
require_once dirname(dirname(__DIR__)) . '/includes/session_config.php';
// Get current page name for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    .user-sidebar {
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }
    
    .user-profile-brief {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        background: linear-gradient(135deg, #2F3A3F 0%, #1e2529 100%); /* Charcoal Gradient */
        color: #fff;
    }
    .user-avatar-small {
        width: 48px; height: 48px;
        border-radius: 50%;
        background: #fff;
        object-fit: cover;
        border: 2px solid #D9A11D; /* Golden Border */
        padding: 2px;
    }
    .hello-text { font-size: 13px; opacity: 0.8; margin-bottom: 2px; }
    .user-name-bold { font-size: 16px; font-weight: 600; color: #fff; }

    .nav-section { padding: 10px 0; }
    
    .nav-link-item {
        display: flex;
        align-items: center;
        padding: 12px 24px;
        font-size: 15px;
        color: #2F3A3F; /* Primary Charcoal */
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        font-weight: 500;
    }
    
    .nav-link-item i { width: 24px; color: #999; transition: color 0.2s; }
    
    .nav-link-item:hover { 
        background: #f8f9fa; 
        color: #2F6FED; /* Royal Blue */
    }
    .nav-link-item:hover i { color: #2F6FED; }

    .nav-link-item.active { 
        background: #f0f7ff; 
        color: #2F6FED; 
        border-left-color: #D9A11D; /* Golden Accent */
        font-weight: 600;
    }
    .nav-link-item.active i { color: #2F6FED; }
    
    .logout-btn {
         padding: 14px 24px;
         color: #dc3545;
         font-weight: 500;
         display: flex;
         align-items: center;
         text-decoration: none;
         border-top: 1px solid #f0f0f0;
         transition: 0.2s;
         margin-top: 5px;
    }
    .logout-btn i { width: 24px; }
    .logout-btn:hover { background: #fff5f5; color: #c82333; }
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

    <!-- Navigation -->
    <div class="nav-section">
        <a href="orders.php" class="nav-link-item <?php echo $current_page == 'orders.php' ? 'active' : ''; ?>">
            <i class="fas fa-box"></i> My Orders
        </a>
        <a href="index.php" class="nav-link-item <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-circle"></i> My Profile
        </a>
        <a href="support.php" class="nav-link-item <?php echo $current_page == 'support.php' ? 'active' : ''; ?>">
            <i class="fas fa-headset"></i> Support Ticket
        </a>
        <a href="change-password.php" class="nav-link-item <?php echo $current_page == 'change-password.php' ? 'active' : ''; ?>">
            <i class="fas fa-key"></i> Change Password
        </a>
    </div>
    
    <!-- Logout -->
    <a href="logout.php" class="logout-btn"><i class="fas fa-power-off"></i> Logout</a>
</div>

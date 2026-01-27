<style>
    /* --- Admin Header Self-Contained Styles --- */
    :root {
        /* Fallback if sidebar vars not loaded */
        --header-bg: #2D3436;
        --header-text: #ecf0f1;
        --header-accent: #D4A017;
        --header-input-bg: rgba(255, 255, 255, 0.05);
        --header-border: rgba(255, 255, 255, 0.1);
    }

    .admin-header {
        background-color: var(--sb-bg, var(--header-bg));
        color: var(--sb-text, var(--header-text));
        height: 70px;
        padding: 0 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative; /* Static positioning */
        z-index: 1040; /* Above sidebar content but below overlay */
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
    }

    /* Left Section: Toggle & Title */
    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .menu-toggle-btn {
        background: transparent;
        border: none;
        color: var(--sb-text, var(--header-text));
        font-size: 1.4rem;
        cursor: pointer;
        padding: 5px;
        transition: color 0.3s ease, transform 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .menu-toggle-btn:hover {
        color: var(--sb-accent, var(--header-accent));
        transform: scale(1.1);
    }

    .page-title {
        font-size: 1.25rem;
        font-weight: 500;
        margin: 0;
        color: var(--sb-text, var(--header-text));
        opacity: 0.9;
        display: none;
    }

    @media (min-width: 768px) {
        .page-title {
            display: block;
        }
    }

    /* Right Section: Search, Notifs, Profile */
    .header-right {
        display: flex;
        align-items: center;
        gap: 25px;
    }

    /* Search Bar */
    .search-container {
        position: relative;
        display: none;
    }

    @media (min-width: 992px) {
        .search-container {
            display: block;
            width: 300px;
        }
    }

    .search-input {
        width: 100%;
        background: var(--header-input-bg);
        border: 1px solid var(--header-border);
        border-radius: 50px;
        padding: 8px 15px 8px 45px;
        color: var(--sb-text, var(--header-text));
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--sb-accent, var(--header-accent));
        box-shadow: 0 0 0 3px rgba(212, 160, 23, 0.15);
    }

    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.4);
        pointer-events: none;
        transition: color 0.3s;
    }

    .search-input:focus + .search-icon {
        color: var(--sb-accent, var(--header-accent));
    }

    /* Action Icons (Notifications) */
    .header-action-btn {
        position: relative;
        background: transparent;
        border: none;
        color: var(--sb-text, var(--header-text));
        font-size: 1.2rem;
        cursor: pointer;
        padding: 5px;
        transition: all 0.3s;
    }

    .header-action-btn:hover {
        color: var(--sb-accent, var(--header-accent));
        transform: translateY(-2px);
    }

    .notification-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
        background-color: #ff4757;
        border-radius: 50%;
        border: 2px solid var(--sb-bg, var(--header-bg));
    }

    /* Pulsating Animation for Badge */
    .notification-badge.pulse {
        animation: pulse-red 2s infinite;
    }

    @keyframes pulse-red {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(255, 71, 87, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 6px rgba(255, 71, 87, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(255, 71, 87, 0);
        }
    }

    /* User Profile Dropdown */
    .profile-dropdown {
        position: relative;
    }

    .profile-trigger {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        padding: 5px;
        border-radius: 30px;
        transition: background 0.3s;
    }

    .profile-trigger:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .profile-img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--sb-accent, var(--header-accent));
    }

    .profile-info {
        display: none;
        text-align: right;
    }

    @media (min-width: 768px) {
        .profile-info {
            display: block;
        }
    }

    .profile-name {
        display: block;
        font-size: 0.9rem;
        font-weight: 500;
        line-height: 1.2;
    }

    .profile-role {
        display: block;
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.6);
    }

    /* Custom Dropdown Menu */
    .custom-dropdown-menu {
        position: absolute;
        top: 120%;
        right: 0;
        width: 200px;
        background: var(--sb-bg, #2D3436);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        padding: 8px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        z-index: 1050;
    }

    .custom-dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item-custom {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 15px;
        color: #ecf0f1;
        text-decoration: none;
        font-size: 0.9rem;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .dropdown-item-custom:hover {
        background: var(--sb-accent, #D4A017);
        color: #fff;
        transform: translateX(3px);
    }

    .dropdown-item-custom i {
        width: 18px;
        text-align: center;
        opacity: 0.8;
    }

    .dropdown-divider-custom {
        height: 1px;
        background: rgba(255,255,255,0.1);
        margin: 6px 0;
    }
</style>

<?php
// Determine Base URL for Assets
// Heuristic: If we are deep in admin, we need to go up.
// Better: Assume assets is at webroot/assets if possible, or build path.
// $path_to_root: logic to find how many ../ needed.
$depth = substr_count(dirname($_SERVER['SCRIPT_NAME']), '/') - 1; // Adjust based on your server structure.
// Let's use a simpler approach: define a helper if not exists or use hardcoded relative based on known structure.

// Since user is editing manually to ../../ or ../, let's auto-detect.
$base_path = '../'; // Default for admin/index.php
if (strpos($_SERVER['REQUEST_URI'], '/admin/products/') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/coupon-codes/') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/smtp/') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/razorpay/') !== false) {
    $base_path = '../../';
}
// This is fragile but respects the user's current manual fix style without rewriting config.

$current_title = isset($page_title) ? $page_title : 'Dashboard';
?>
<header class="admin-header">
    <!-- Left: Toggle & Title -->
    <div class="header-left">
        <button id="menu-toggle" class="menu-toggle-btn" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <h2 class="page-title"><?php echo htmlspecialchars($current_title); ?></h2>
    </div>

    <!-- Right: Search, Notifications, Profile -->
    <div class="header-right">
        <!-- Search Bar -->
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search anything...">
            <i class="fas fa-search search-icon"></i>
        </div>

        <!-- Notifications -->
        <button class="header-action-btn" title="Notifications">
            <i class="far fa-bell"></i>
            <span class="notification-badge pulse"></span>
        </button>

        <!-- User Profile -->
        <div class="profile-dropdown" id="profileDropdownContainer">
            <div class="profile-trigger" id="profileTrigger">
                <img src="<?php echo $base_path; ?>/assets/images/user-avtar.avif" alt="Admin" class="profile-img" onerror="this.src='https://via.placeholder.com/40/D4A017/ffffff?text=A'">
                <div class="profile-info">
                    <span class="profile-name">Rahul</span>
                    <span class="profile-role">Super Admin</span>
                </div>
                <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem; opacity: 0.6;"></i>
            </div>

            <!-- Custom Dropdown -->
            <div class="custom-dropdown-menu" id="profileDropdown">
                <a href="#" class="dropdown-item-custom">
                    <i class="far fa-user"></i> My Profile
                </a>
                <a href="#" class="dropdown-item-custom">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <div class="dropdown-divider-custom"></div>
                <a href="../logout.php" class="dropdown-item-custom" style="color: #ff6b6b; hover: {background: #ff6b6b; color: #fff;} ">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search Focus Effects (Optional, CSS handles most)
        const searchInput = document.querySelector('.search-input');
        if(searchInput) {
            searchInput.addEventListener('focus', () => {
                // Could add logic to expand search bar or dim other elements
            });
        }

        // Profile Dropdown Toggle
        const profileTrigger = document.getElementById('profileTrigger');
        const profileDropdown = document.getElementById('profileDropdown');

        if (profileTrigger && profileDropdown) {
            // Toggle click
            profileTrigger.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });

            // Close on click outside
            document.addEventListener('click', (e) => {
                if (!profileDropdown.contains(e.target) && !profileTrigger.contains(e.target)) {
                    profileDropdown.classList.remove('show');
                }
            });
        }
    });
</script>

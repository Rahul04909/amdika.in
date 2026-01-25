<style>
/* --- Sidebar Local Styles --- */
:root {
    --sb-bg: #2D3436;
    --sb-text: #ecf0f1;
    --sb-active-bg: rgba(255,255,255,0.08);
    --sb-accent: #D4A017;
    --sb-hover-bg: rgba(255,255,255,0.04);
    --sb-width: 260px;
    --sb-width-collapsed: 70px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Base Sidebar */
#amd-sidebar {
    width: var(--sb-width);
    height: 100vh;
    background-color: var(--sb-bg);
    color: var(--sb-text);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 15px rgba(0,0,0,0.1);
    white-space: nowrap;
    overflow: hidden;
}

/* Collapsed State */
#amd-sidebar.collapsed {
    width: var(--sb-width-collapsed);
}

/* Logo Area */
.sb-logo-area {
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center; /* Center the logo */
    padding: 0 15px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    background: rgba(0,0,0,0.1); 
}

.sb-logo-img {
    max-height: 40px;
    max-width: 100%;
    transition: all 0.3s;
}

#amd-sidebar.collapsed .sb-logo-img {
    max-height: 25px; /* Smaller on collapse */
    opacity: 0.8;
}

/* Menu Content */
.sb-content {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 0; /* Remove top/bottom padding for flush borders */
}

/* Scrollbar styling for sidebar */
.sb-content::-webkit-scrollbar {
    width: 4px;
}
.sb-content::-webkit-scrollbar-track {
    background: transparent;
}
.sb-content::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.2);
    border-radius: 4px;
}

/* Menu Section Label */
.sb-label {
    font-size: 11px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.4);
    padding: 15px 20px 5px 20px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: opacity 0.2s;
}

#amd-sidebar.collapsed .sb-label {
    opacity: 0;
    display: none;
}

/* Menu Items */
.sb-item {
    position: relative;
    margin: 0; /* Stacked */
    border-bottom: 1px solid rgba(255, 255, 255, 0.05); /* Solid Border */
}

.sb-link {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    color: var(--sb-text);
    text-decoration: none !important;
    border-radius: 0; /* Square edges for solid border look */
    cursor: pointer;
    transition: background-color 0.2s, color 0.2s;
    border-left: 4px solid transparent; /* Left accent bar */
}

.sb-link:hover {
    background-color: var(--sb-hover-bg);
    color: var(--white);
}

.sb-link.active, .sb-link:not(.collapsed) {
    background-color: var(--sb-active-bg);
    color: var(--sb-accent);
    border-left-color: var(--sb-accent);
}

.sb-icon {
    font-size: 18px;
    min-width: 25px; /* Ensures alignment */
    text-align: center;
}

.sb-text {
    margin-left: 12px;
    font-size: 14px;
    font-weight: 500;
    opacity: 1;
    transition: opacity 0.2s;
}

#amd-sidebar.collapsed .sb-text {
    opacity: 0;
    display: none;
}

/* Dropdown Arrow */
.sb-arrow {
    margin-left: auto;
    font-size: 12px;
    transition: transform 0.3s;
    opacity: 0.7;
}

#amd-sidebar.collapsed .sb-arrow {
    display: none;
}

.sb-link.collapsed .sb-arrow {
    transform: rotate(0deg);
}
.sb-link:not(.collapsed) .sb-arrow {
    transform: rotate(90deg);
}

/* Submenu */
.sb-submenu {
    list-style: none;
    padding: 0;
    margin: 0;
    background-color: rgba(0,0,0,0.2); /* Darker background for submenu */
}

.sb-submenu .sb-link {
    padding: 10px 15px 10px 58px; /* Indent subitems */
    font-size: 13.5px;
    opacity: 0.9;
    border-bottom: none; /* No borders inside dropdown (optional) or keep? Let's remove for cleaner look */
    border-left: none; /* No accent bar for subitems */
}

.sb-submenu .sb-link:hover {
    opacity: 1;
    background-color: rgba(255,255,255,0.05);
    color: var(--sb-accent);
}

/* Active Indicator Line override */
.sb-link.active::before {
    display: none; /* Removed the old floating indicator in favor of border-left */
}

/* Footer Section */
.sb-footer {
    padding: 0;
    border-top: 1px solid rgba(255,255,255,0.1);
}

.sb-footer .sb-link {
    color: #ff6b6b;
}

/* Tooltip on Collapsed */
#amd-sidebar.collapsed .sb-item:hover {
    overflow: visible; /* Allow tooltip to show */
}

#amd-sidebar.collapsed .sb-link {
    justify-content: center;
    padding: 15px 0;
}

#amd-sidebar.collapsed .sb-icon {
    min-width: 0;
}

#amd-sidebar.collapsed .sb-item:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    left: 100%;
    top: 50%;
    transform: translateY(-50%);
    background: #000;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    margin-left: 0;
    z-index: 9999;
    opacity: 1;
    pointer-events: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Overlay for Mobile */
.sb-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.5);
    z-index: 1040;
    display: none;
    opacity: 0;
    transition: opacity 0.3s;
}

.sb-overlay.show {
    display: block;
    opacity: 1;
}

/* Responsive Logic CSS Hooks */
body.sb-expanded #page-content-wrapper {
    margin-left: var(--sb-width);
}
body.sb-collapsed #page-content-wrapper {
    margin-left: var(--sb-width-collapsed);
}

@media (max-width: 991px) {
    #amd-sidebar {
        transform: translateX(-100%); 
        width: var(--sb-width); 
    }
    
    #amd-sidebar.mobile-open {
        transform: translateX(0);
    }
    
    #page-content-wrapper {
        margin-left: 0 !important;
    }
    
    body.sb-collapsed #amd-sidebar {
        width: var(--sb-width); /* Don't shrink on mobile */
    }
}
</style>

<!-- Mobile Overlay -->
<div class="sb-overlay" id="sbOverlay"></div>

<?php
// Dynamic Path Logic
$base_path = '../';
if (strpos($_SERVER['REQUEST_URI'], '/admin/products/') !== false) {
    $base_path = '../../';
}

// Active State Logic
$current_page = basename($_SERVER['PHP_SELF']);
$is_manage_cat = ($current_page == 'manage-category.php');
$is_add_cat = ($current_page == 'add-category.php');
$is_products_group = ($is_manage_cat || $is_add_cat);
?>
<nav id="amd-sidebar">
    <div class="sb-logo-area">
        <img src="<?php echo $base_path; ?>assets/images/amdika-logo.png" alt="Amadika" class="sb-logo-img">
    </div>

    <div class="sb-content" id="sidebarAccordion">
        <div class="sb-label">Main</div>
        
        <!-- Dashboard Submenu -->
        <div class="sb-item" data-tooltip="Dashboard">
            <a href="#menuDashboard" class="sb-link collapsed" data-bs-toggle="collapse" data-bs-parent="#sidebarAccordion">
                <i class="fas fa-th-large sb-icon"></i>
                <span class="sb-text">Dashboard</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse" id="menuDashboard">
                <ul class="sb-submenu">
                    <li><a href="<?php echo $base_path; ?>admin/index.php" class="sb-link">Overview</a></li>
                    <li><a href="#" class="sb-link">Analytics</a></li>
                    <li><a href="#" class="sb-link">Real-time</a></li>
                </ul>
            </div>
        </div>

        <!-- Products Submenu -->
        <div class="sb-item" data-tooltip="Products">
            <a href="#menuProducts" class="sb-link <?php echo $is_products_group ? '' : 'collapsed'; ?>" data-bs-toggle="collapse" data-bs-parent="#sidebarAccordion" aria-expanded="<?php echo $is_products_group ? 'true' : 'false'; ?>">
                <i class="fas fa-box sb-icon"></i>
                <span class="sb-text">Products</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse <?php echo $is_products_group ? 'show' : ''; ?>" id="menuProducts">
                <ul class="sb-submenu">
                    <li><a href="#" class="sb-link">All Products</a></li>
                    <li><a href="#" class="sb-link">Add New</a></li>
                    <li><a href="<?php echo $base_path; ?>admin/products/manage-category.php" class="sb-link <?php echo $is_manage_cat ? 'active text-warning' : ''; ?>">Manage Categories</a></li>
                    <li><a href="<?php echo $base_path; ?>admin/products/add-category.php" class="sb-link <?php echo $is_add_cat ? 'active text-warning' : ''; ?>">Add Category</a></li>
                </ul>
            </div>
        </div>

        <!-- Orders Submenu -->
        <div class="sb-item" data-tooltip="Orders">
            <a href="#menuOrders" class="sb-link collapsed" data-bs-toggle="collapse" data-bs-parent="#sidebarAccordion">
                <i class="fas fa-shopping-cart sb-icon"></i>
                <span class="sb-text">Orders</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
             <div class="collapse" id="menuOrders">
                <ul class="sb-submenu">
                    <li><a href="#" class="sb-link">All Orders</a></li>
                    <li><a href="#" class="sb-link">Pending</a></li>
                    <li><a href="#" class="sb-link">Completed</a></li>
                    <li><a href="#" class="sb-link">Returns</a></li>
                </ul>
            </div>
        </div>

        <!-- Users Submenu -->
        <div class="sb-item" data-tooltip="Users">
            <a href="#menuUsers" class="sb-link collapsed" data-bs-toggle="collapse" data-bs-parent="#sidebarAccordion">
                <i class="fas fa-users sb-icon"></i>
                <span class="sb-text">Users</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse" id="menuUsers">
                <ul class="sb-submenu">
                    <li><a href="#" class="sb-link">All Users</a></li>
                    <li><a href="#" class="sb-link">Add New</a></li>
                    <li><a href="#" class="sb-link">Roles</a></li>
                </ul>
            </div>
        </div>

        <!-- Reports Submenu -->
        <div class="sb-item" data-tooltip="Reports">
            <a href="#menuReports" class="sb-link collapsed" data-bs-toggle="collapse" data-bs-parent="#sidebarAccordion">
                <i class="fas fa-chart-line sb-icon"></i>
                <span class="sb-text">Reports</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
             <div class="collapse" id="menuReports">
                <ul class="sb-submenu">
                    <li><a href="#" class="sb-link">Sales Report</a></li>
                    <li><a href="#" class="sb-link">User Traffic</a></li>
                    <li><a href="#" class="sb-link">Product Perf.</a></li>
                </ul>
            </div>
        </div>

        <div class="sb-label mt-3">System</div>

        <!-- Settings Submenu -->
        <div class="sb-item" data-tooltip="Settings">
            <a href="#menuSettings" class="sb-link collapsed" data-bs-toggle="collapse" data-bs-parent="#sidebarAccordion">
                <i class="fas fa-cog sb-icon"></i>
                <span class="sb-text">Settings</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
             <div class="collapse" id="menuSettings">
                <ul class="sb-submenu">
                    <li><a href="#" class="sb-link">General</a></li>
                    <li><a href="#" class="sb-link">Payment</a></li>
                    <li><a href="#" class="sb-link">Notifications</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="sb-footer">
        <div class="sb-item mb-0" data-tooltip="Logout">
            <a href="#" class="sb-link" style="color: #ff6b6b;">
                <i class="fas fa-sign-out-alt sb-icon"></i>
                <span class="sb-text">Logout</span>
            </a>
        </div>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("amd-sidebar");
    const toggleBtn = document.getElementById("menu-toggle");
    const overlay = document.getElementById("sbOverlay");
    const body = document.body;

    // Check LocalStorage for desktop state
    const savedState = localStorage.getItem("sidebar-state");
    if (window.innerWidth >= 992) {
        if (savedState === "collapsed") {
            sidebar.classList.add("collapsed");
            body.classList.add("sb-collapsed");
        } else {
            body.classList.add("sb-expanded");
        }
    }

    // Toggle Function
    if (toggleBtn) {
        toggleBtn.addEventListener("click", function (e) {
            e.preventDefault();
            if (window.innerWidth >= 992) {
                // Desktop: Collapse/Expand
                sidebar.classList.toggle("collapsed");
                if (sidebar.classList.contains("collapsed")) {
                    body.classList.remove("sb-expanded");
                    body.classList.add("sb-collapsed");
                    localStorage.setItem("sidebar-state", "collapsed");
                    // Close all submenus when collapsing
                    closeAllSubmenus();
                } else {
                    body.classList.remove("sb-collapsed");
                    body.classList.add("sb-expanded");
                    localStorage.setItem("sidebar-state", "expanded");
                }
            } else {
                // Mobile: Show/Hide
                sidebar.classList.toggle("mobile-open");
                overlay.classList.toggle("show");
            }
        });
    }

    // Overlay Click (Mobile)
    if (overlay) {
        overlay.addEventListener("click", function () {
            sidebar.classList.remove("mobile-open");
            overlay.classList.remove("show");
        });
    }

    // Helper: Close all submenus
    function closeAllSubmenus() {
        const submenus = document.querySelectorAll('.collapse.show');
        submenus.forEach(menu => {
            // Use Bootstrap API if available, or just remove class if relying on CSS
            // Since we use data-bs-toggle, simple removal might be desynced. 
            // Triggering click on the toggler is safer or using BS instance.
            // For simplicity in Vanilla without storing BS instances:
             const toggler = document.querySelector(`[href="#${menu.id}"]`);
             if(toggler) toggler.click(); 
        });
    }
});
</script>

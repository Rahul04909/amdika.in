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
    padding: 20px 0;
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
    padding: 10px 25px;
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
    margin: 4px 15px;
}

.sb-link {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    color: var(--sb-text);
    text-decoration: none !important;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.2s, color 0.2s;
}

.sb-link:hover {
    background-color: var(--sb-hover-bg);
    color: var(--white);
}

.sb-link.active {
    background-color: var(--sb-active-bg);
    color: var(--sb-accent);
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
    padding: 5px 0 5px 20px; /* Indent */
    margin: 0;
    display: none; /* Handled by bootstrap collapse or custom JS, we'll use custom for smoother control if needed, but standard BS5 collapse is generally safe. Let's stick to standard BS5 collapse for robustness */
}

.sb-submenu .sb-link {
    padding: 8px 15px 8px 30px;
    font-size: 13.5px;
    opacity: 0.8;
}

.sb-submenu .sb-link:hover {
    opacity: 1;
}

/* Active Indicator Line (Optional Design Touch) */
.sb-link.active::before {
    content: '';
    position: absolute;
    left: -15px; /* Outside the padding */
    top: 10%;
    height: 80%;
    width: 4px;
    background-color: var(--sb-accent);
    border-radius: 0 4px 4px 0;
    display: block;
}

/* Footer Section */
.sb-footer {
    padding: 20px;
    border-top: 1px solid rgba(255,255,255,0.05);
}

/* Tooltip on Collapsed (Custom implementation for simplicity) */
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
    margin-left: 10px;
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
        transform: translateX(-100%); /* Hidden by default on mobile */
        width: var(--sb-width); /* Full width when open on mobile usually, or standard width */
    }
    
    #amd-sidebar.mobile-open {
        transform: translateX(0);
    }
    
    #page-content-wrapper {
        margin-left: 0 !important;
    }
    
    body.sb-collapsed #amd-sidebar {
        width: var(--sb-width); /* Don't shrink on mobile, just hide */
    }
}
</style>

<!-- Mobile Overlay -->
<div class="sb-overlay" id="sbOverlay"></div>

<nav id="amd-sidebar">
    <div class="sb-logo-area">
        <img src="../assets/images/amdika-logo.png" alt="Amadika" class="sb-logo-img">
    </div>

    <div class="sb-content">
        <div class="sb-label">Main</div>
        
        <div class="sb-item" data-tooltip="Dashboard">
            <a href="index.php" class="sb-link active">
                <i class="fas fa-th-large sb-icon"></i>
                <span class="sb-text">Dashboard</span>
            </a>
        </div>

        <div class="sb-item" data-tooltip="Products">
            <a href="#menuProducts" class="sb-link collapsed" data-bs-toggle="collapse">
                <i class="fas fa-box sb-icon"></i>
                <span class="sb-text">Products</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse" id="menuProducts">
                <ul class="sb-submenu">
                    <li><a href="#" class="sb-link">All Products</a></li>
                    <li><a href="#" class="sb-link">Add New</a></li>
                    <li><a href="#" class="sb-link">Categories</a></li>
                </ul>
            </div>
        </div>

        <div class="sb-item" data-tooltip="Orders">
            <a href="#" class="sb-link">
                <i class="fas fa-shopping-cart sb-icon"></i>
                <span class="sb-text">Orders</span>
            </a>
        </div>

        <div class="sb-item" data-tooltip="Users">
            <a href="#" class="sb-link">
                <i class="fas fa-users sb-icon"></i>
                <span class="sb-text">Users</span>
            </a>
        </div>

        <div class="sb-item" data-tooltip="Reports">
            <a href="#" class="sb-link">
                <i class="fas fa-chart-line sb-icon"></i>
                <span class="sb-text">Reports</span>
            </a>
        </div>

        <div class="sb-label mt-3">System</div>

        <div class="sb-item" data-tooltip="Settings">
            <a href="#" class="sb-link">
                <i class="fas fa-cog sb-icon"></i>
                <span class="sb-text">Settings</span>
            </a>
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

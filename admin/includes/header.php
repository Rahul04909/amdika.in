<style>
/* --- Admin Navbar Styles --- */
.admin-navbar {
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); /* var(--card-shadow) */
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 999;
}

.menu-toggle {
    cursor: pointer;
    font-size: 24px;
    color: #2D3436; /* var(--secondary-color) */
    transition: color 0.2s;
}

.menu-toggle:hover {
    color: #D32F2F; /* var(--primary-color) */
}

.admin-search-group {
    position: relative;
    max-width: 400px;
    width: 100%;
}

.admin-search-input {
    border: 2px solid #eee;
    padding: 8px 15px;
    padding-right: 40px;
    border-radius: 50px;
    width: 100%;
    transition: border-color 0.2s;
}

.admin-search-input:focus {
    outline: none;
    border-color: #D4A017; /* var(--accent-gold) */
}

.admin-header-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}

.nav-icon-btn {
    position: relative;
    color: #555;
    font-size: 20px;
    cursor: pointer;
}

.nav-icon-btn .badge {
    position: absolute;
    top: -5px;
    right: -8px;
    background: #D32F2F; /* var(--primary-color) */
    font-size: 10px;
}

.user-profile-dropdown {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #D4A017; /* var(--accent-gold) */
}
</style>

<nav class="admin-navbar navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-align-left primary-text fs-4 me-3 menu-toggle" id="menu-toggle"></i>
        <h2 class="fs-2 m-0 text-secondary">Dashboard</h2>
    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
            <li class="nav-item">
                <div class="admin-search-group me-3 d-none d-lg-block">
                    <input type="text" class="admin-search-input" placeholder="Search...">
                </div>
            </li>
            
            <li class="nav-item">
                 <a class="nav-link nav-icon-btn" href="#">
                    <i class="fas fa-bell"></i>
                    <span class="badge rounded-pill bg-danger">3</span>
                 </a>
            </li>

            <li class="nav-item dropdown ms-3">
                <a class="nav-link dropdown-toggle user-profile-dropdown" href="#" id="navbarDropdown"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=D32F2F&color=fff" alt="Admin" class="user-avatar">
                    <span class="fw-bold text-secondary d-none d-md-block">Admin User</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

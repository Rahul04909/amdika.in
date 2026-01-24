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

<!-- Top Navigation Bar -->
<div class="topbar">
    <div class="topbar-left d-flex align-items-center">
        <button class="toggle-sidebar-btn btn btn-light rounded-circle me-3" title="Toggle Sidebar" style="width: 40px; height: 40px;">
            <i class="bi bi-list"></i>
        </button>
        <h5 class="m-0 fw-700"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h5>
    </div>
    
    <div class="topbar-right d-flex align-items-center gap-4">
        <div class="search-box d-none d-md-flex align-items-center position-relative">
            <i class="bi bi-search position-absolute text-muted" style="left: 12px;"></i>
            <input type="text" class="form-control bg-light border-0 ps-5 rounded-3" placeholder="Search data..." style="width: 250px;">
        </div>
        
        <div class="notifications-wrapper position-relative">
            <div class="notification-bell btn btn-light rounded-circle position-relative" data-bs-toggle="dropdown" style="width: 40px; height: 40px;">
                <i class="bi bi-bell"></i>
                <span class="notification-badge bg-danger position-absolute rounded-circle border border-white" style="width: 8px; height: 8px; top: 10px; right: 10px;"></span>
            </div>
            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-3 p-0 overflow-hidden" style="width: 300px;">
                <div class="p-3 border-bottom bg-light">
                    <h6 class="m-0 fw-bold">Notifications</h6>
                </div>
                <div class="notification-list" style="max-height: 300px; overflow-y: auto;">
                    <a class="dropdown-item p-3 border-bottom d-flex align-items-center" href="#">
                        <div class="icon bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                            <i class="bi bi-calendar-plus"></i>
                        </div>
                        <div class="content">
                            <p class="small mb-0 fw-600">New appointment request</p>
                            <span class="text-muted" style="font-size: 0.7rem;">2 minutes ago</span>
                        </div>
                    </a>
                    <a class="dropdown-item p-3 border-bottom d-flex align-items-center" href="#">
                        <div class="icon bg-success bg-opacity-10 text-success rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <div class="content">
                            <p class="small mb-0 fw-600">Payment received (৳1,200)</p>
                            <span class="text-muted" style="font-size: 0.7rem;">1 hour ago</span>
                        </div>
                    </a>
                </div>
                <a class="dropdown-item text-center p-2 bg-light small text-primary fw-600" href="#">View all</a>
            </div>
        </div>

        <div class="admin-profile-wrapper">
            <div class="admin-profile d-flex align-items-center gap-2 cursor-pointer" data-bs-toggle="dropdown">
                <div class="admin-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold" style="width: 40px; height: 40px;">
                    AD
                </div>
                <div class="admin-info d-none d-sm-block">
                    <h6 class="m-0 fw-bold" style="font-size: 0.85rem;">Administrator</h6>
                    <span class="text-muted" style="font-size: 0.75rem;">Super Admin</span>
                </div>
            </div>
            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-3 p-2" style="width: 200px;">
                <a class="dropdown-item rounded-3 mb-1" href="profile.php">
                    <i class="bi bi-person me-2"></i> My Profile
                </a>
                <a class="dropdown-item rounded-3 mb-1" href="settings.php">
                    <i class="bi bi-gear me-2"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item rounded-3 text-danger" href="logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .fw-700 { font-weight: 700; }
    .topbar i { font-size: 1.5rem !important; }
    .topbar .dropdown-item { padding: 0.6rem 1rem; transition: var(--transition); }
    .topbar .dropdown-item:hover { background-color: var(--light); color: var(--primary); }
</style>

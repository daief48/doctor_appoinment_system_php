<!-- Sidebar Navigation -->
<div class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon bg-primary text-white rounded-3 d-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px; font-size: 1.5rem;">
            <i class="bi bi-heart-pulse-fill"></i>
        </div>
        <h5>MediCare Admin</h5>
        <p class="small text-muted mb-0">Premium Health Care</p>
    </div>
    
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="index.php" class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="doctors.php" class="nav-link <?php echo ($current_page == 'doctors') ? 'active' : ''; ?>">
                <i class="bi bi-person-badge"></i>
                <span>Doctors</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="patients.php" class="nav-link <?php echo ($current_page == 'patients') ? 'active' : ''; ?>">
                <i class="bi bi-people-fill"></i>
                <span>Patients</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="appointments.php" class="nav-link <?php echo ($current_page == 'appointments') ? 'active' : ''; ?>">
                <i class="bi bi-calendar-check-fill"></i>
                <span>Appointments</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="payments.php" class="nav-link <?php echo ($current_page == 'payments') ? 'active' : ''; ?>">
                <i class="bi bi-wallet2"></i>
                <span>Payments</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="reports.php" class="nav-link <?php echo ($current_page == 'reports') ? 'active' : ''; ?>">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Reports</span>
            </a>
        </li>
        
        <div class="nav-divider my-4 border-bottom border-secondary border-opacity-10"></div>
        
        <li class="nav-item">
            <a href="settings.php" class="nav-link <?php echo ($current_page == 'settings') ? 'active' : ''; ?>">
                <i class="bi bi-gear-fill"></i>
                <span>Settings</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="logout.php" class="nav-link text-danger opacity-75">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

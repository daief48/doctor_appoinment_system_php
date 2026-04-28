<!-- Sidebar Navigation -->
<nav id="sidebar">
    <div class="sidebar-header text-center">
        <img src="../images/logo.png" alt="Logo" style="height: 60px; margin-bottom: 10px;">
        <h4>Medi Cares</h4>
    </div>

    <ul class="list-unstyled components">
        <li class="<?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">
            <a href="index.php" class="nav-link-btn"><i class="bi bi-grid"></i> Dashboard</a>
        </li>
        <li class="<?php echo ($current_page == 'appointments') ? 'active' : ''; ?>">
            <a href="appointments.php" class="nav-link-btn"><i class="bi bi-calendar-event"></i> Appointments</a>
        </li>
        <li class="<?php echo ($current_page == 'patients') ? 'active' : ''; ?>">
            <a href="patients.php" class="nav-link-btn"><i class="bi bi-people"></i> Patients</a>
        </li>
        <li class="<?php echo ($current_page == 'prescriptions') ? 'active' : ''; ?>">
            <a href="prescriptions.php" class="nav-link-btn"><i class="bi bi-file-medical"></i> Prescriptions</a>
        </li>
        <li class="<?php echo ($current_page == 'schedule') ? 'active' : ''; ?>">
            <a href="schedule.php" class="nav-link-btn"><i class="bi bi-clock-history"></i> Schedule</a>
        </li>
        <li class="<?php echo ($current_page == 'profile') ? 'active' : ''; ?>">
            <a href="profile.php" class="nav-link-btn"><i class="bi bi-person-badge"></i> Profile Setup</a>
        </li>
    </ul>
</nav>

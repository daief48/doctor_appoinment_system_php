<aside class="dashboard-sidebar">
    <div class="mb-4 px-2">
        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a href="dashboard.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                    <i class="fa fa-columns"></i> Dashboard
                </a>
            </li>
            <li class="sidebar-item">
                <a href="my-appointments.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'my-appointments.php') ? 'active' : ''; ?>">
                    <i class="fa fa-calendar-check-o"></i> Appointments
                </a>
            </li>
            <li class="sidebar-item">
                <a href="prescriptions.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'prescriptions.php') ? 'active' : ''; ?>">
                    <i class="fa fa-file-text-o"></i> Prescriptions
                </a>
            </li>
            <li class="sidebar-item">
                <a href="payments.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'payments.php') ? 'active' : ''; ?>">
                    <i class="fa fa-credit-card"></i> Billing & Payments
                </a>
            </li>
        </ul>
    </div>

    <div class="mb-4 px-2">
        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a href="profile.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
                    <i class="fa fa-user-circle-o"></i> My Profile
                </a>
            </li>
            <li class="sidebar-item">
                <a href="logout.php" class="sidebar-link text-danger-hover">
                    <i class="fa fa-sign-out"></i> Sign Out
                </a>
            </li>
        </ul>
    </div>

    <div class="mt-auto p-3">
        <div class="dashboard-card border-0 p-4" style="background: #ffffff; border-radius: 24px; box-shadow: 0 10px 25px rgba(79, 70, 229, 0.08);">
            <div class="stat-icon mb-3" style="background: #eef2ff; color: #4f46e5; width: 40px; height: 40px; border-radius: 12px; font-size: 16px;">
                <i class="fa fa-question-circle"></i>
            </div>
            <h6 class="fw-bold mb-2 text-slate-900">Need Help?</h6>
            <p class="small mb-4 text-slate-500" style="font-size: 12px; line-height: 1.6;">Our support experts are just a click away.</p>
            <a href="#" class="btn btn-sm w-100 fw-bold border-2" style="border-color: #eef2ff; color: #4f46e5; border-radius: 12px; transition: all 0.3s ease;">
                Get Support
            </a>
        </div>
    </div>
</aside>

<style>
.text-danger-hover:hover {
    background-color: rgba(239, 68, 68, 0.1) !important;
    color: #ef4444 !important;
}
</style>

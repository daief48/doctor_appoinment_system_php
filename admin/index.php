<?php 
$page_title = 'Dashboard';
$current_page = 'dashboard';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

// Fetch Stats
$total_doctors = $pdo->query("SELECT COUNT(*) FROM doctors")->fetchColumn();
$total_patients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$total_appointments = $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(amount) FROM payments WHERE status = 'completed'")->fetchColumn() ?: 0;

// Fetch Recent Appointments
$stmt = $pdo->query("SELECT a.*, p.name as patient_name, d.name as doctor_name FROM appointments a JOIN patients p ON a.patient_id = p.id JOIN doctors d ON a.doctor_id = d.id ORDER BY a.created_at DESC LIMIT 5");
$recent_appointments = $stmt->fetchAll();

// Dynamic Data for Charts (Simplified for this version)
$days = []; $appt_counts = [];
for($i=6; $i>=0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $days[] = date('D', strtotime($date));
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE DATE(created_at) = ?");
    $stmt->execute([$date]);
    $appt_counts[] = $stmt->fetchColumn();
}
?>

<div class="main-content">
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Dashboard Overview</h2>
            <p class="welcome-message">Welcome back! Here's what's happening today.</p>
        </div>
        <div class="date-display text-muted fw-500">
            <i class="bi bi-calendar3 me-2"></i><?php echo date('D, M d, Y'); ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card doctors">
            <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
            <div class="stat-label">Total Doctors</div>
            <div class="stat-value"><?php echo number_format($total_doctors); ?></div>
        </div>

        <div class="stat-card patients">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div class="stat-label">Total Patients</div>
            <div class="stat-value"><?php echo number_format($total_patients); ?></div>
        </div>

        <div class="stat-card appointments">
            <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
            <div class="stat-label">Appointments</div>
            <div class="stat-value"><?php echo number_format($total_appointments); ?></div>
        </div>

        <div class="stat-card revenue">
            <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">৳ <?php echo number_format($total_revenue); ?></div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Appointments Table -->
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Recent Appointments</h5>
                    <a href="appointments.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Patient</th>
                                    <th>Doctor</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recent_appointments as $appt): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3 bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 35px; height: 35px;">
                                                <?php echo substr($appt['patient_name'], 0, 1); ?>
                                            </div>
                                            <span class="fw-600"><?php echo htmlspecialchars($appt['patient_name']); ?></span>
                                        </div>
                                    </td>
                                    <td>Dr. <?php echo htmlspecialchars($appt['doctor_name']); ?></td>
                                    <td>
                                        <?php if($appt['status'] == 'approved'): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">Approved</span>
                                        <?php elseif($appt['status'] == 'pending' || $appt['status'] == 'awaiting_payment'): ?>
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Pending</span>
                                        <?php elseif($appt['status'] == 'completed'): ?>
                                            <span class="badge bg-info-subtle text-info border border-info-subtle">Completed</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4 text-muted small">
                                        <?php echo date('M d, Y', strtotime($appt['appointment_date'])); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($recent_appointments)): ?>
                                    <tr><td colspan="4" class="text-center py-4 text-muted">No recent appointments.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="doctors.php?action=add" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center">
                            <i class="bi bi-plus-circle me-2"></i> Add New Doctor
                        </a>
                        <a href="reports.php" class="btn btn-light btn-lg d-flex align-items-center justify-content-center border">
                            <i class="bi bi-bar-chart-line me-2"></i> Financial Reports
                        </a>
                        <a href="settings.php" class="btn btn-light btn-lg d-flex align-items-center justify-content-center border">
                            <i class="bi bi-gear me-2"></i> System Settings
                        </a>
                    </div>
                    <div class="mt-4 p-4 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-10">
                        <h6 class="text-primary fw-bold mb-2">Need Support?</h6>
                        <p class="small text-muted mb-0">If you encounter any issues, please contact the development team or check the system logs.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-600 { font-weight: 600; }
    .bg-success-subtle { background-color: #ecfdf5 !important; }
    .bg-warning-subtle { background-color: #fffbeb !important; }
    .bg-info-subtle { background-color: #f0f9ff !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php include 'includes/footer.php'; ?>

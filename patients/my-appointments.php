<?php 
$page_title = 'My Appointments';
include 'includes/header.php'; 
include 'includes/navbar.php'; 

if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT a.*, d.name as doctor_name, d.specialization 
                       FROM appointments a 
                       JOIN doctors d ON a.doctor_id = d.id 
                       WHERE a.patient_id = ? 
                       ORDER BY a.appointment_date DESC, a.appointment_time DESC");
$stmt->execute([$patient_id]);
$appointments = $stmt->fetchAll();
?>

<div class="dashboard-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="dashboard-main">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold mb-1">Appointment History</h2>
                    <p class="text-muted mb-0">View and manage your consultation schedule.</p>
                </div>
                <a href="book-appointment.php" class="btn-primary-modern">
                    <i class="fa fa-plus me-2"></i> New Appointment
                </a>
            </div>

            <div class="dashboard-card p-0 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Doctor Details</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">Schedule</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">Status</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">Message</th>
                                <th class="pe-4 py-3 text-end text-uppercase small fw-bold text-muted">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($appointments as $appt): ?>
                            <tr class="align-middle">
                                <td class="ps-4 py-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stat-icon icon-blue" style="width: 40px; height: 40px; font-size: 16px;">
                                            <i class="fa fa-user-md"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">Dr. <?php echo htmlspecialchars($appt['doctor_name']); ?></h6>
                                            <p class="text-muted small mb-0"><?php echo htmlspecialchars($appt['specialization']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <p class="fw-semibold mb-0"><?php echo date('d M, Y', strtotime($appt['appointment_date'])); ?></p>
                                    <p class="text-muted small mb-0"><?php echo date('h:i A', strtotime($appt['appointment_time'])); ?></p>
                                </td>
                                <td class="py-4">
                                    <span class="status-badge <?php 
                                        echo $appt['status'] == 'approved' ? 'badge-success' : 
                                            ($appt['status'] == 'pending' ? 'badge-warning' : 
                                            ($appt['status'] == 'completed' ? 'badge-info' : 'badge-danger')); 
                                    ?>">
                                        <?php echo $appt['status']; ?>
                                    </span>
                                </td>
                                <td class="py-4">
                                    <p class="text-muted small mb-0 text-truncate" style="max-width: 150px;">
                                        <?php echo htmlspecialchars($appt['message'] ?: '-'); ?>
                                    </p>
                                </td>
                                <td class="pe-4 py-4 text-end">
                                    <?php if($appt['status'] == 'completed'): 
                                        $p_stmt = $pdo->prepare("SELECT id FROM prescriptions WHERE appointment_id = ?");
                                        $p_stmt->execute([$appt['id']]);
                                        $p = $p_stmt->fetch();
                                        if($p): ?>
                                        <a href="prescriptions.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary fw-bold px-3">
                                            View Rx
                                        </a>
                                    <?php endif; endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($appointments)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <p class="text-muted mb-0">No appointments recorded yet.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>

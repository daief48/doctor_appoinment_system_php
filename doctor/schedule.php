<?php 
$page_title = 'My Schedule';
$current_page = 'schedule';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

// Fetch appointments for the next 7 days
$stmt = $pdo->prepare("SELECT a.*, p.name as patient_name 
                       FROM appointments a 
                       JOIN patients p ON a.patient_id = p.id 
                       WHERE a.doctor_id = ? AND a.appointment_date >= CURDATE() 
                       ORDER BY a.appointment_date ASC, a.appointment_time ASC");
$stmt->execute([$doctor_id]);
$schedule = $stmt->fetchAll();
?>

<div class="main-content-section active" style="display:block;">
    <h3 class="fw-bold mb-4">My Schedule</h3>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-calendar-check text-primary display-4 mb-3"></i>
                    <h5 class="fw-bold mb-3">Weekly View</h5>
                    <p class="text-muted small">Manage your weekly time slots and availability.</p>
                    <a href="availability.php" class="btn btn-primary w-100">Configure Availability</a>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="custom-table-card h-100">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                    <h5 class="fw-bold mb-0">Upcoming Bookings</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <?php if(empty($schedule)): ?>
                        <li class="list-group-item text-center py-4 text-muted">No bookings for the coming days.</li>
                    <?php endif; ?>
                    <?php foreach($schedule as $s): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h6 class="mb-0 fw-bold"><?php echo date('M d, Y', strtotime($s['appointment_date'])); ?></h6>
                            <small class="text-muted"><?php echo date('h:i A', strtotime($s['appointment_time'])); ?></small>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($s['patient_name']); ?></h6>
                            <span class="badge <?php echo $s['status'] == 'approved' ? 'bg-primary' : 'bg-warning text-dark'; ?> rounded-pill">
                                <?php echo ucfirst($s['status']); ?>
                            </span>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

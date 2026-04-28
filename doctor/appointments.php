<?php 
$page_title = 'Doctor - Appointments';
$current_page = 'appointments';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

// Fetch Doctor's Appointments
$stmt = $pdo->prepare("SELECT a.*, p.name as patient_name, p.dob, p.phone as patient_phone 
                       FROM appointments a 
                       JOIN patients p ON a.patient_id = p.id 
                       WHERE a.doctor_id = ? 
                       ORDER BY a.appointment_date DESC, a.appointment_time ASC");
$stmt->execute([$doctor_id]);
$appointments = $stmt->fetchAll();
?>

<div class="main-content-section active" style="display:block;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Appointments</h3>
    </div>

    <!-- Flash Messages -->
    <?php displayFlashMessage(); ?>

    <div class="custom-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Patient</th>
                        <th>Date & Time</th>
                        <th>Reason/Message</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($appointments as $appt): ?>
                    <tr>
                        <td>
                            <div class="fw-bold"><?php echo htmlspecialchars($appt['patient_name']); ?></div>
                            <small class="text-muted"><?php echo $appt['patient_phone']; ?></small>
                        </td>
                        <td>
                            <div><?php echo date('M d, Y', strtotime($appt['appointment_date'])); ?></div>
                            <small class="text-muted"><?php echo date('h:i A', strtotime($appt['appointment_time'])); ?></small>
                        </td>
                        <td><small><?php echo htmlspecialchars($appt['message'] ?: 'N/A'); ?></small></td>
                        <td>
                            <?php if($appt['status'] == 'approved'): ?>
                                <span class="badge bg-success-light text-success rounded-pill px-3">Approved</span>
                            <?php elseif($appt['status'] == 'pending'): ?>
                                <span class="badge bg-warning-light text-warning rounded-pill px-3">Pending</span>
                            <?php elseif($appt['status'] == 'completed'): ?>
                                <span class="badge bg-info-light text-info rounded-pill px-3">Completed</span>
                            <?php else: ?>
                                <span class="badge bg-danger-light text-danger rounded-pill px-3">Cancelled</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Action
                                </button>
                                <ul class="dropdown-menu shadow border-0">
                                    <?php if($appt['status'] == 'pending'): ?>
                                        <li><a class="dropdown-item text-success" href="api/doctor_actions.php?action=update_status&id=<?php echo $appt['id']; ?>&status=approved"><i class="bi bi-check2-circle me-2"></i> Approve</a></li>
                                    <?php endif; ?>
                                    <?php if($appt['status'] == 'approved'): ?>
                                        <li><a class="dropdown-item text-primary" href="write-prescription.php?appointment_id=<?php echo $appt['id']; ?>"><i class="bi bi-file-earmark-medical me-2"></i> Write Prescription</a></li>
                                        <li><a class="dropdown-item text-info" href="api/doctor_actions.php?action=update_status&id=<?php echo $appt['id']; ?>&status=completed"><i class="bi bi-check-all me-2"></i> Mark Completed</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item text-danger" href="api/doctor_actions.php?action=update_status&id=<?php echo $appt['id']; ?>&status=cancelled"><i class="bi bi-x-circle me-2"></i> Cancel</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($appointments)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">No appointments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

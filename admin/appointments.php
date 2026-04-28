<?php 
$page_title = 'Appointment Management';
$current_page = 'appointments';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

// Fetch Appointments
$query = "SELECT a.*, p.name as patient_name, d.name as doctor_name 
          FROM appointments a 
          JOIN patients p ON a.patient_id = p.id 
          JOIN doctors d ON a.doctor_id = d.id 
          ORDER BY a.appointment_date DESC, a.appointment_time DESC";
$stmt = $pdo->query($query);
$appointments = $stmt->fetchAll();

// Stats
$total_appts = count($appointments);
$pending_appts = count(array_filter($appointments, function($a) { return $a['status'] == 'pending' || $a['status'] == 'awaiting_payment'; }));
$today_appts = count(array_filter($appointments, function($a) { return $a['appointment_date'] == date('Y-m-d'); }));
$approved_appts = count(array_filter($appointments, function($a) { return $a['status'] == 'approved'; }));
?>

<!-- Main Content -->
<div class="main-content">
    <div class="page-header-card d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h2><i class="bi bi-calendar-check-fill me-2"></i>Appointment Management</h2>
            <p>View, approve, and manage all patient appointments.</p>
        </div>
        <div class="d-flex gap-3 flex-wrap">
            <div class="rounded-3 px-3 py-2 text-center" style="background: rgba(255,255,255,0.95);">
                <div class="fw-800" style="font-size: 1.5rem; color: #1e293b;"><?php echo $total_appts; ?></div>
                <div class="small" style="color: #64748b;">Total</div>
            </div>
            <div class="bg-warning bg-opacity-20 rounded-3 px-3 py-2 text-white text-center">
                <div class="fw-800" style="font-size: 1.5rem;"><?php echo $pending_appts; ?></div>
                <div class="small opacity-75">Pending</div>
            </div>
            <div class="bg-success bg-opacity-20 rounded-3 px-3 py-2 text-white text-center">
                <div class="fw-800" style="font-size: 1.5rem;"><?php echo $today_appts; ?></div>
                <div class="small opacity-75">Today</div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if(function_exists('displayFlashMessage')) displayFlashMessage(); ?>

    <!-- Search & Filter -->
    <div class="search-filter-section">
        <div class="flex-grow-1 position-relative">
            <i class="bi bi-search position-absolute text-muted" style="left: 14px; top: 50%; transform: translateY(-50%);"></i>
            <input type="text" class="form-control ps-5" id="appointmentSearch" placeholder="Search by patient or doctor name...">
        </div>
        <select class="form-select" id="statusFilter" style="width: auto; min-width: 160px;">
            <option value="">All Statuses</option>
            <option value="approved">Approved</option>
            <option value="pending">Pending</option>
            <option value="awaiting_payment">Awaiting Payment</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <input type="date" class="form-control" id="dateFilter" style="width: auto;">
        <button class="btn btn-outline-secondary" onclick="resetFilters()">
            <i class="bi bi-arrow-clockwise"></i> Reset
        </button>
    </div>

    <!-- Appointments Table -->
    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-table me-2 text-primary"></i>All Appointments</h5>
            <small class="text-muted"><?php echo $total_appts; ?> total records</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="appointmentsTable">
                    <thead>
                        <tr>
                            <th class="ps-4">Patient</th>
                            <th>Doctor</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($appointments as $appt): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary">
                                        <?php echo strtoupper(substr($appt['patient_name'], 0, 1)); ?>
                                    </div>
                                    <span class="fw-600"><?php echo htmlspecialchars($appt['patient_name']); ?></span>
                                </div>
                            </td>
                            <td>Dr. <?php echo htmlspecialchars($appt['doctor_name']); ?></td>
                            <td>
                                <div class="fw-600"><?php echo date('M d, Y', strtotime($appt['appointment_date'])); ?></div>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i><?php echo date('h:i A', strtotime($appt['appointment_time'])); ?></small>
                            </td>
                            <td>
                                <?php
                                $status = $appt['status'];
                                $badge = match($status) {
                                    'approved'        => 'bg-success-soft text-success',
                                    'completed'       => 'bg-info-soft text-info',
                                    'pending'         => 'bg-warning-soft text-warning',
                                    'awaiting_payment'=> 'bg-warning-soft text-warning',
                                    'cancelled'       => 'bg-danger-soft text-danger',
                                    default           => 'bg-secondary bg-opacity-10 text-secondary'
                                };
                                $icon = match($status) {
                                    'approved'        => 'bi-check-circle-fill',
                                    'completed'       => 'bi-flag-fill',
                                    'pending','awaiting_payment' => 'bi-hourglass-split',
                                    'cancelled'       => 'bi-x-circle-fill',
                                    default           => 'bi-question-circle'
                                };
                                ?>
                                <span class="badge <?php echo $badge; ?>">
                                    <i class="bi <?php echo $icon; ?> me-1"></i>
                                    <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <?php if($appt['status'] == 'pending' || $appt['status'] == 'awaiting_payment'): ?>
                                        <a href="api/admin_actions.php?action=update_appointment_status&id=<?php echo $appt['id']; ?>&status=approved" 
                                           class="btn btn-sm btn-success action-btn">
                                            <i class="bi bi-check2"></i> Approve
                                        </a>
                                        <a href="api/admin_actions.php?action=update_appointment_status&id=<?php echo $appt['id']; ?>&status=cancelled" 
                                           class="btn btn-sm btn-delete-modern action-btn"
                                           onclick="return confirm('Reject this appointment?')">
                                            <i class="bi bi-x"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if($appt['status'] == 'approved'): ?>
                                        <a href="api/admin_actions.php?action=update_appointment_status&id=<?php echo $appt['id']; ?>&status=completed" 
                                           class="btn btn-sm btn-outline-secondary action-btn">
                                            <i class="bi bi-flag"></i> Complete
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($appointments)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                                    <p class="mt-3 text-muted">No appointments found.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function resetFilters() {
    $('#appointmentSearch').val('');
    $('#statusFilter').val('');
    $('#dateFilter').val('');
    $('#appointmentsTable tbody tr').show();
}

$(document).ready(function() {
    function filterTable() {
        const search = $('#appointmentSearch').val().toLowerCase();
        const status = $('#statusFilter').val().toLowerCase();
        const date   = $('#dateFilter').val();
        $('#appointmentsTable tbody tr').each(function() {
            const text      = $(this).text().toLowerCase();
            const rowStatus = $(this).find('td:eq(3)').text().trim().toLowerCase();
            const rowDate   = $(this).find('td:eq(2)').text().trim();
            const matchSearch = text.includes(search);
            const matchStatus = status === '' || rowStatus.includes(status);
            const matchDate   = date === '' || rowDate.includes(date);
            $(this).toggle(matchSearch && matchStatus && matchDate);
        });
    }
    $('#appointmentSearch, #statusFilter, #dateFilter').on('input change', filterTable);
});
</script>

<?php include 'includes/footer.php'; ?>

<?php 
$page_title = 'Doctor Dashboard';
$current_page = 'dashboard';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

// Fetch Stats
$total_patients_stmt = $pdo->prepare("SELECT COUNT(DISTINCT patient_id) FROM appointments WHERE doctor_id = ?");
$total_patients_stmt->execute([$doctor_id]);
$total_patients = $total_patients_stmt->fetchColumn();

$today_appts_stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND appointment_date = CURDATE()");
$today_appts_stmt->execute([$doctor_id]);
$today_appts = $today_appts_stmt->fetchColumn();

$pending_appts_stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND status = 'pending'");
$pending_appts_stmt->execute([$doctor_id]);
$pending_appts = $pending_appts_stmt->fetchColumn();

// Fetch Today's Appointments
$appts_stmt = $pdo->prepare("SELECT a.*, p.name as patient_name, p.dob FROM appointments a JOIN patients p ON a.patient_id = p.id WHERE a.doctor_id = ? AND a.appointment_date = CURDATE() ORDER BY a.appointment_time ASC LIMIT 5");
$appts_stmt->execute([$doctor_id]);
$upcoming_appts = $appts_stmt->fetchAll();
?>

<!-- DASHBOARD SECTION -->
<div class="main-content-section active" style="display:block;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Overview</h3>
        <div class="d-flex gap-2">
            <a href="prescriptions.php" class="btn btn-outline-primary"><i class="bi bi-pen"></i> Write Prescription</a>
            <a href="schedule.php" class="btn btn-primary"><i class="bi bi-calendar"></i> View Schedule</a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div>
                    <p class="text-muted mb-1 fw-semibold">Total Patients</p>
                    <h3 class="fw-bold mb-0"><?php echo $total_patients; ?></h3>
                </div>
                <div class="stat-icon icon-blue"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div>
                    <p class="text-muted mb-1 fw-semibold">Today's Appts</p>
                    <h3 class="fw-bold mb-0"><?php echo $today_appts; ?></h3>
                </div>
                <div class="stat-icon icon-purple"><i class="bi bi-calendar-day-fill"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div>
                    <p class="text-muted mb-1 fw-semibold">Completed</p>
                    <h3 class="fw-bold mb-0">0</h3>
                </div>
                <div class="stat-icon icon-green"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div>
                    <p class="text-muted mb-1 fw-semibold">Pending Appts</p>
                    <h3 class="fw-bold mb-0 text-warning"><?php echo $pending_appts; ?></h3>
                </div>
                <div class="stat-icon icon-orange"><i class="bi bi-clock-fill"></i></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Chart Area -->
        <div class="col-lg-8">
            <div class="custom-table-card h-100">
                <h5 class="fw-bold mb-4">Patient Visit Activity</h5>
                <div style="height: 300px;">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Quick Schedule -->
        <div class="col-lg-4">
            <div class="custom-table-card h-100">
                <h5 class="fw-bold mb-4">Up Next Today</h5>
                <div class="d-flex flex-column gap-3">
                    <?php if(empty($upcoming_appts)): ?>
                        <p class="text-center text-muted">No appointments for today.</p>
                    <?php endif; ?>
                    <?php foreach($upcoming_appts as $appt): ?>
                    <div class="d-flex align-items-start border-bottom pb-3">
                        <div class="bg-light rounded p-2 text-center me-3" style="min-width: 60px;">
                            <h6 class="mb-0 fw-bold"><?php echo date('h:i', strtotime($appt['appointment_time'])); ?></h6>
                            <small class="text-muted"><?php echo date('A', strtotime($appt['appointment_time'])); ?></small>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($appt['patient_name']); ?></h6>
                            <p class="text-muted small mb-0"><?php echo $appt['message'] ?: 'General Checkup'; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <a href="appointments.php" class="btn btn-light w-100 mt-4 text-primary fw-semibold">View All</a>
            </div>
        </div>
    </div>
</div>

<?php 
// Fetch graph data (last 7 days counts)
$graph_data = [];
$labels = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('D', strtotime($date));
    
    $g_stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND appointment_date = ?");
    $g_stmt->execute([$doctor_id, $date]);
    $graph_data[] = $g_stmt->fetchColumn();
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('activityChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [{
                        label: 'Patient Visits',
                        data: <?php echo json_encode($graph_data); ?>,
                        borderColor: '#0b5ed7',
                        backgroundColor: 'rgba(11, 94, 215, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#0b5ed7'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f0f0f0' }, ticks: { stepSize: 1 } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>

<?php include 'includes/footer.php'; ?>

<?php
$page_title = 'Patient Dashboard';
include 'includes/header.php';
include 'includes/navbar.php';

if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

// Fetch Stats
$total_appts_stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE patient_id = ?");
$total_appts_stmt->execute([$patient_id]);
$total_appts = $total_appts_stmt->fetchColumn();

// Fetch Pending Appointments
$pending_appts_stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE patient_id = ? AND status = 'pending'");
$pending_appts_stmt->execute([$patient_id]);
$pending_appts = $pending_appts_stmt->fetchColumn();

// Fetch Latest Appointment
$latest_appt_stmt = $pdo->prepare("SELECT a.*, d.name as doctor_name, d.specialization, d.profile_image as doctor_image FROM appointments a JOIN doctors d ON a.doctor_id = d.id WHERE a.patient_id = ? ORDER BY a.appointment_date DESC, a.appointment_time DESC LIMIT 1");
$latest_appt_stmt->execute([$patient_id]);
$latest_appt = $latest_appt_stmt->fetch();
?>

<div class="dashboard-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="dashboard-main">
        <div class="container-fluid">
            <!-- Header Section -->
            <div class="row align-items-center mb-5">
                <div class="col-md-8">
                    <h2 class="fw-bold text-slate-900 mb-1">Welcome back,
                        <?php echo explode(' ', $patient['name'])[0]; ?>!</h2>
                    <p class="text-muted mb-0">Here's a summary of your medical status and upcoming appointments.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="book-appointment.php" class="btn-primary-modern">
                        <i class="fa fa-plus me-2"></i> Book Appointment
                    </a>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row g-4 mb-5 mt-5">
                <div class="col-md-6">
                    <div class="dashboard-card stat-card">
                        <div class="stat-icon icon-blue">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-medium mb-0">Total Appointments</p>
                            <h3 class="fw-bold mb-0"><?php echo $total_appts; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-card stat-card">
                        <div class="stat-icon icon-orange">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-medium mb-0">Pending Reviews</p>
                            <h3 class="fw-bold mb-0"><?php echo $pending_appts; ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Latest Appointment -->
                <div class="col-lg-12">
                    <div class="dashboard-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Recent Appointment</h5>
                            <a href="my-appointments.php"
                                class="text-primary small fw-semibold text-decoration-none">View All</a>
                        </div>

                        <?php if ($latest_appt): ?>
                            <div class="p-4 rounded-4 bg-slate-50 border border-slate-100">
                                <div class="d-flex align-items-center gap-4 flex-wrap">
                                    <img src="<?php echo $latest_appt['doctor_image'] ? '../doctor/assets/images/' . $latest_appt['doctor_image'] : 'https://ui-avatars.com/api/?name=' . urlencode($latest_appt['doctor_name']) . '&background=4f46e5&color=fff'; ?>"
                                        class="rounded-circle shadow-sm"
                                        style="width: 64px; height: 64px; object-fit: cover; border: 2px solid white;">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Dr.
                                            <?php echo htmlspecialchars($latest_appt['doctor_name']); ?></h6>
                                        <p class="text-muted small mb-0">
                                            <?php echo htmlspecialchars($latest_appt['specialization']); ?></p>
                                    </div>
                                    <div>
                                        <span class="status-badge <?php
                                        echo $latest_appt['status'] == 'approved' ? 'badge-success' :
                                            ($latest_appt['status'] == 'pending' ? 'badge-warning' : 'badge-info');
                                        ?>">
                                            <?php echo $latest_appt['status']; ?>
                                        </span>
                                    </div>
                                </div>
                                <hr class="my-4 opacity-10">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <p class="text-muted small mb-1"><i class="fa fa-calendar-o me-2"></i> Date</p>
                                        <p class="fw-semibold mb-0">
                                            <?php echo date('D, M d, Y', strtotime($latest_appt['appointment_date'])); ?>
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="text-muted small mb-1"><i class="fa fa-clock-o me-2"></i> Time Slot</p>
                                        <p class="fw-semibold mb-0">
                                            <?php echo date('h:i A', strtotime($latest_appt['appointment_time'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <p class="text-muted mb-0">No appointments found.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>


<style>
    .bg-slate-50 {
        background-color: #f8fafc;
    }

    .bg-blue-50 {
        background-color: #eff6ff;
    }

    .text-blue-800 {
        color: #1e40af;
    }

    .text-orange-600 {
        color: #ea580c;
    }

    .border-slate-100 {
        border-color: #f1f5f9;
    }

    .border-blue-100 {
        border-color: #dbeafe;
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .hover-shadow-sm:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border-color: var(--primary-light) !important;
        transform: translateX(5px);
    }
</style>

<script>
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('.dashboard-sidebar').toggleClass('active');
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
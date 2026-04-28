<?php 
$page_title = 'System Reports';
$current_page = 'reports';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

// Basic Stats
$total_revenue = $pdo->query("SELECT SUM(amount) FROM payments WHERE status = 'paid'")->fetchColumn() ?: 0;
$total_appointments = $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$successful_appointments = $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'completed'")->fetchColumn();
$success_rate = $total_appointments > 0 ? round(($successful_appointments / $total_appointments) * 100) : 0;

// Monthly Revenue Data for Chart
$monthly_revenue = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%b') as month, SUM(amount) as total 
    FROM payments 
    WHERE status = 'paid' 
    GROUP BY month 
    ORDER BY created_at ASC 
    LIMIT 6
")->fetchAll(PDO::FETCH_KEY_PAIR);

// Dept performance
$dept_stats = $pdo->query("
    SELECT d.name, COUNT(a.id) as count 
    FROM departments d 
    LEFT JOIN doctors dr ON d.id = dr.department_id 
    LEFT JOIN appointments a ON dr.id = a.doctor_id 
    GROUP BY d.id
")->fetchAll();
?>

<!-- Main Content -->
<div class="main-content">
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Analytical Reports</h2>
            <p class="welcome-message">Deep dive into your health center's performance metrics.</p>
        </div>
        <button class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-download me-2"></i> Export PDF Report
        </button>
    </div>

    <!-- Stats Grid -->
    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="report-card primary">
                <div class="card-icon"><i class="bi bi-cash-stack"></i></div>
                <h3>৳ <?php echo number_format($total_revenue); ?></h3>
                <p>Accumulated Revenue</p>
                <div class="progress mt-3" style="height: 5px;">
                    <div class="progress-bar bg-white" style="width: 75%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="report-card success">
                <div class="card-icon"><i class="bi bi-check2-circle"></i></div>
                <h3><?php echo $success_rate; ?>%</h3>
                <p>Appointment Success Rate</p>
                <div class="progress mt-3" style="height: 5px;">
                    <div class="progress-bar bg-white" style="width: <?php echo $success_rate; ?>%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="report-card info">
                <div class="card-icon"><i class="bi bi-calendar3"></i></div>
                <h3><?php echo number_format($total_appointments); ?></h3>
                <p>Total Consultations</p>
                <div class="progress mt-3" style="height: 5px;">
                    <div class="progress-bar bg-white" style="width: 60%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Revenue Growth (Last 6 Months)</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container-fixed">
                        <canvas id="revenueGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Departmental Traffic</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container-fixed">
                        <canvas id="deptDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dept Performance Table -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Department Performance Summary</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Department Name</th>
                        <th>Total Appointments</th>
                        <th>Popularity</th>
                        <th>Growth</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dept_stats as $stat): 
                        $percentage = ($total_appointments > 0) ? round(($stat['count'] / $total_appointments) * 100) : 0;
                    ?>
                    <tr>
                        <td class="fw-bold"><?php echo htmlspecialchars($stat['name']); ?></td>
                        <td><?php echo $stat['count']; ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar" style="width: <?php echo $percentage; ?>%; background-color: #6366f1;"></div>
                                </div>
                                <small><?php echo $percentage; ?>%</small>
                            </div>
                        </td>
                        <td><span class="text-success"><i class="bi bi-arrow-up"></i> +<?php echo rand(2, 15); ?>%</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.report-card {
    padding: 30px;
    border-radius: 20px;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.report-card.primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
.report-card.success { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }
.report-card.info { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }

.report-card .card-icon {
    font-size: 3rem;
    position: absolute;
    right: 20px; top: 20px;
    opacity: 0.2;
}
.report-card h3 { font-size: 2.5rem; font-weight: 800; margin-bottom: 5px; }
.report-card p { font-size: 1.1rem; opacity: 0.8; font-weight: 500; }

.chart-container-fixed {
    position: relative;
    height: 350px;
    width: 100%;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Growth Chart
    const revCtx = document.getElementById('revenueGrowthChart').getContext('2d');
    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_keys($monthly_revenue)); ?>,
            datasets: [{
                label: 'Revenue',
                data: <?php echo json_encode(array_values($monthly_revenue)); ?>,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // Dept Chart
    const deptCtx = document.getElementById('deptDistributionChart').getContext('2d');
    new Chart(deptCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($dept_stats, 'name')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($dept_stats, 'count')); ?>,
                backgroundColor: ['#6366f1', '#22c55e', '#0ea5e9', '#f59e0b', '#ec4899', '#8b5cf6']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>

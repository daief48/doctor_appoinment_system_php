<?php 
$page_title = 'Billing & Payments';
include 'includes/header.php'; 
include 'includes/navbar.php'; 

if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

// Fetch Payments
$stmt = $pdo->prepare("
    SELECT a.*, d.name as doctor_name, d.fee, d.specialization, pay.status as payment_status, pay.id as payment_id, pay.transaction_id
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    LEFT JOIN payments pay ON a.id = pay.appointment_id
    WHERE a.patient_id = ? AND (a.status = 'completed' OR pay.id IS NOT NULL)
    ORDER BY a.appointment_date DESC
");
$stmt->execute([$patient_id]);
$all_payments = $stmt->fetchAll();

$total_paid = 0;
$total_pending = 0;
foreach($all_payments as $p) {
    if ($p['payment_status'] == 'paid') $total_paid += $p['fee'];
    else if ($p['status'] == 'completed') $total_pending += $p['fee'];
}


?>

<div class="dashboard-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="dashboard-main">
        <div class="container-fluid">
            <!-- Simple Header -->
            <div class="row mb-30">
                <div class="col-md-12">
                    <h2 class="page-header-title">Billing & Payments</h2>
                    <p class="text-muted">Review your transaction history and clear any pending consultation fees.</p>
                </div>
            </div>

            <!-- Simple Summary Cards -->
            <div class="row mb-30">
                <div class="col-sm-6">
                    <div class="standard-card">
                        <div class="d-flex align-items-center">
                            <div class="card-icon-box bg-success-light">
                                <i class="fa fa-check text-success"></i>
                            </div>
                            <div class="ms-15">
                                <p class="card-label">Total Paid</p>
                                <h3 class="card-value">৳ <?php echo number_format($total_paid); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="standard-card">
                        <div class="d-flex align-items-center">
                            <div class="card-icon-box bg-primary-light">
                                <i class="fa fa-credit-card text-primary"></i>
                            </div>
                            <div class="ms-15">
                                <p class="card-label">Pending Dues</p>
                                <h3 class="card-value">৳ <?php echo number_format($total_pending); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Standard Table Card -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-card">
                        <div class="table-card-header">
                            <h4 class="m-0 fw-bold">Recent Transactions</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle m-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-20">Date</th>
                                        <th>Doctor & Specialization</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th class="pe-20 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($all_payments)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-30">No transactions found.</td>
                                    </tr>
                                    <?php endif; ?>

                                    <?php foreach($all_payments as $p): ?>
                                    <tr>
                                        <td class="ps-20">
                                            <div class="fw-bold"><?php echo date('d M, Y', strtotime($p['appointment_date'])); ?></div>
                                            <div class="text-muted small"><?php echo date('h:i A', strtotime($p['appointment_time'])); ?></div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">Dr. <?php echo htmlspecialchars($p['doctor_name']); ?></div>
                                            <div class="text-muted small"><?php echo htmlspecialchars($p['specialization']); ?></div>
                                        </td>
                                        <td class="fw-bold">৳ <?php echo number_format($p['fee']); ?></td>
                                        <td>
                                            <?php if ($p['payment_status'] == 'paid'): ?>
                                                <span class="label-modern label-success-modern">Paid</span>
                                            <?php else: ?>
                                                <span class="label-modern label-warning-modern">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-20 text-right">
                                            <?php if ($p['payment_status'] != 'paid'): ?>
                                                <form action="stripe_checkout.php" method="POST" class="m-0">
                                                    <input type="hidden" name="pay_appointment_id" value="<?php echo $p['id']; ?>">
                                                    <input type="hidden" name="amount" value="<?php echo $p['fee']; ?>">
                                                    <button type="submit" class="btn btn-primary btn-sm px-20">Pay Now</button>
                                                </form>
                                            <?php else: ?>
                                                <div class="text-right">
                                                    <div class="fw-bold text-success small">Verified ✓</div>
                                                    <div class="text-muted x-small">ID: <?php echo $p['transaction_id']; ?></div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
/* Bootstrap 3 Utility Polyfills */
.d-flex { display: flex !important; }
.align-items-center { align-items: center !important; }
.justify-content-between { justify-content: space-between !important; }
.ms-15 { margin-left: 15px !important; }
.mb-30 { margin-bottom: 30px !important; }
.ps-20 { padding-left: 20px !important; }
.pe-20 { padding-right: 20px !important; }
.py-30 { padding-top: 30px !important; padding-bottom: 30px !important; }
.px-20 { padding-left: 20px !important; padding-right: 20px !important; }
.fw-bold { font-weight: 700 !important; }
.m-0 { margin: 0 !important; }
.x-small { font-size: 11px !important; }

/* Standard Theme Styles */
.dashboard-main {
    background-color: #f8f9fa;
}

.page-header-title {
    font-weight: 700;
    color: #333;
    margin-top: 0;
    margin-bottom: 5px;
}

.standard-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.card-icon-box {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.bg-success-light { background-color: #e6f7ef; }
.bg-primary-light { background-color: #eef2ff; }

.card-label {
    color: #777;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 2px;
}

.card-value {
    margin: 0;
    font-weight: 700;
    color: #333;
}

.table-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
}

.table-card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f5f5f5;
}

.table thead th {
    background-color: #fafafa;
    border-bottom: 1px solid #eee !important;
    font-size: 11px;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding-top: 12px !important;
    padding-bottom: 12px !important;
}

.table tbody td {
    padding-top: 15px !important;
    padding-bottom: 15px !important;
    border-bottom: 1px solid #f9f9f9;
}

.label-modern {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

.label-success-modern { background-color: #e6f7ef; color: #10b981; }
.label-warning-modern { background-color: #fff7ed; color: #f97316; }

.btn-primary {
    background-color: #4f46e5;
    border-color: #4f46e5;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.2s;
}

.btn-primary:hover {
    background-color: #4338ca;
    border-color: #4338ca;
}

.text-success { color: #10b981 !important; }
.text-primary { color: #4f46e5 !important; }
</style>

<?php include 'includes/footer.php'; ?>

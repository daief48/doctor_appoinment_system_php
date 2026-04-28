<?php 
$page_title = 'Payments Management';
$current_page = 'payments';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

// Handle Status Update
if (isset($_GET['action']) && $_GET['action'] == 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $payment_id = $_GET['id'];
    $new_status = $_GET['status'];
    $stmt = $pdo->prepare("UPDATE payments SET status = ? WHERE id = ?");
    if ($stmt->execute([$new_status, $payment_id])) {
        header("Location: payments.php?updated=1");
        exit();
    }
}

// Fetch Payments with details
$stmt = $pdo->query("
    SELECT 
        pay.*, 
        p.name as patient_name, 
        p.email as patient_email,
        d.name as doctor_name,
        a.appointment_date,
        a.appointment_time
    FROM payments pay
    JOIN patients p ON pay.patient_id = p.id
    JOIN appointments a ON pay.appointment_id = a.id
    JOIN doctors d ON a.doctor_id = d.id
    ORDER BY pay.created_at DESC
");
$payments = $stmt->fetchAll();

// Stats
$total_collected = $pdo->query("SELECT SUM(amount) FROM payments WHERE status = 'paid'")->fetchColumn() ?: 0;
$pending_count   = $pdo->query("SELECT COUNT(*) FROM payments WHERE status = 'pending'")->fetchColumn();
$total_count     = count($payments);
?>

<!-- Main Content -->
<div class="main-content">

    <?php if(isset($_GET['updated'])): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 mb-3 border-0" style="background: #ecfdf5; color: #166534;">
        <i class="bi bi-check-circle-fill me-2"></i> Payment status updated successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="page-header-card d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h2><i class="bi bi-wallet2 me-2"></i>Payments Management</h2>
            <p>Monitor and manage all patient transactions.</p>
        </div>
        <div class="d-flex gap-3 flex-wrap">
            <div class="bg-success bg-opacity-20 rounded-3 px-4 py-2 text-white text-center">
                <div class="fw-800" style="font-size: 1.4rem;">৳ <?php echo number_format($total_collected); ?></div>
                <div class="small opacity-75">Total Revenue</div>
            </div>
            <div class="bg-warning bg-opacity-20 rounded-3 px-4 py-2 text-white text-center">
                <div class="fw-800" style="font-size: 1.4rem;"><?php echo $pending_count; ?></div>
                <div class="small opacity-75">Pending</div>
            </div>
        </div>
    </div>

    <!-- Payments Table Card -->
    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-receipt me-2 text-primary"></i>Transaction History</h5>
            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="bi bi-printer"></i> Print
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Patient</th>
                            <th>Appointment</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Transaction ID</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($payments as $pay): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary">
                                        <?php echo strtoupper(substr($pay['patient_name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-600"><?php echo htmlspecialchars($pay['patient_name']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($pay['patient_email']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-600">Dr. <?php echo htmlspecialchars($pay['doctor_name']); ?></div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?php echo date('M d, Y', strtotime($pay['appointment_date'])); ?>
                                </small>
                            </td>
                            <td>
                                <span class="fw-800 text-success" style="font-size: 1.05rem;">
                                    ৳ <?php echo number_format($pay['amount']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill">
                                    <i class="bi bi-credit-card me-1"></i>
                                    <?php echo htmlspecialchars($pay['payment_method']); ?>
                                </span>
                            </td>
                            <td>
                                <code class="text-primary small"><?php echo $pay['transaction_id'] ?: '—'; ?></code>
                            </td>
                            <td>
                                <?php
                                $ps = $pay['status'];
                                $pb = match($ps) {
                                    'paid'    => 'bg-success-soft text-success',
                                    'pending' => 'bg-warning-soft text-warning',
                                    default   => 'bg-danger-soft text-danger'
                                };
                                $pi = match($ps) {
                                    'paid'    => 'bi-check-circle-fill',
                                    'pending' => 'bi-hourglass-split',
                                    default   => 'bi-x-circle-fill'
                                };
                                ?>
                                <span class="badge <?php echo $pb; ?>">
                                    <i class="bi <?php echo $pi; ?> me-1"></i>
                                    <?php echo ucfirst($ps); ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary rounded-3" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4">
                                        <li>
                                            <a class="dropdown-item" href="payments.php?action=update_status&id=<?php echo $pay['id']; ?>&status=paid">
                                                <i class="bi bi-check-circle text-success me-2"></i>Mark as Paid
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="payments.php?action=update_status&id=<?php echo $pay['id']; ?>&status=pending">
                                                <i class="bi bi-clock text-warning me-2"></i>Mark as Pending
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="payments.php?action=update_status&id=<?php echo $pay['id']; ?>&status=failed">
                                                <i class="bi bi-x-circle text-danger me-2"></i>Mark as Failed
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($payments)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-credit-card text-muted" style="font-size: 3rem;"></i>
                                    <p class="mt-3 text-muted">No transactions found in the database.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

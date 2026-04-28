<?php 
$page_title = 'Patient History';
$current_page = 'patients';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

$patient_id = $_GET['id'] ?? null;
if (!$patient_id) {
    echo "Patient ID required.";
    exit();
}

// Fetch Patient Details
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->execute([$patient_id]);
$patient_data = $stmt->fetch();

if (!$patient_data) {
    echo "Patient not found.";
    exit();
}

// Fetch Visit History (Appointments + Prescriptions)
$stmt = $pdo->prepare("SELECT a.*, pr.diagnosis, pr.medicines, pr.tests, pr.advice, pr.id as prescription_id 
                       FROM appointments a 
                       LEFT JOIN prescriptions pr ON a.id = pr.appointment_id 
                       WHERE a.patient_id = ? AND a.doctor_id = ? 
                       ORDER BY a.appointment_date DESC");
$stmt->execute([$patient_id, $doctor_id]);
$history = $stmt->fetchAll();
?>

<div class="main-content-section active" style="display:block;">
    <a href="patients.php" class="btn btn-light mb-4"><i class="bi bi-arrow-left"></i> Back to Patients</a>
    
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 d-flex align-items-center gap-4 flex-wrap">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($patient_data['name']); ?>&size=100" class="rounded-circle shadow-sm">
            <div class="flex-grow-1">
                <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($patient_data['name']); ?></h3>
                <p class="text-muted mb-2">Age: <?php echo $patient_data['dob'] ? date_diff(date_create($patient_data['dob']), date_create('today'))->y : 'N/A'; ?> | Gender: <?php echo htmlspecialchars($patient_data['gender'] ?: 'N/A'); ?></p>
                <p class="mb-0">
                    <i class="bi bi-envelope text-primary me-2"></i> <?php echo htmlspecialchars($patient_data['email']); ?> &nbsp;&nbsp; 
                    <i class="bi bi-telephone text-primary mx-2"></i> <?php echo htmlspecialchars($patient_data['phone'] ?: 'N/A'); ?>
                </p>
                <p class="mt-2 text-secondary small"><i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($patient_data['address'] ?: 'N/A'); ?></p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-12">
            <h5 class="fw-bold mb-3">Visit History with Me</h5>
            <div class="timeline ps-3 border-start border-2 border-primary">
                <?php if(empty($history)): ?>
                    <p class="text-muted ms-3">No history found for this patient.</p>
                <?php endif; ?>
                <?php foreach($history as $h): ?>
                <div class="position-relative mb-4 ms-3">
                    <div class="position-absolute bg-primary rounded-circle" style="width:12px; height:12px; left:-23px; top:5px;"></div>
                    <h6 class="fw-bold"><?php echo $h['diagnosis'] ?: 'Unspecified Diagnosis'; ?></h6>
                    <p class="text-muted small mb-1"><?php echo date('M d, Y', strtotime($h['appointment_date'])); ?> at <?php echo date('h:i A', strtotime($h['appointment_time'])); ?></p>
                    <div class="small text-secondary">
                        <?php if($h['medicines']): ?>
                            <div class="mt-2"><strong>Prescription:</strong> 
                                <ul>
                                    <?php $meds = json_decode($h['medicines'], true); 
                                    foreach($meds as $m): if(empty($m['name'])) continue; ?>
                                        <li><?php echo $m['name']; ?> (<?php echo $m['dosage']; ?>)</li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if($h['advice']): ?>
                            <p class="mb-0"><strong>Advice:</strong> <?php echo htmlspecialchars($h['advice']); ?></p>
                        <?php endif; ?>
                        <span class="badge <?php echo $h['status'] == 'completed' ? 'bg-success' : 'bg-warning'; ?> mt-2"><?php echo ucfirst($h['status']); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

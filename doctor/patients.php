<?php 
$page_title = 'Doctor - Patients';
$current_page = 'patients';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

// Fetch Patients who have appointments with this doctor
$stmt = $pdo->prepare("SELECT DISTINCT p.* 
                       FROM patients p 
                       JOIN appointments a ON p.id = a.patient_id 
                       WHERE a.doctor_id = ? 
                       ORDER BY p.name ASC");
$stmt->execute([$doctor_id]);
$patients = $stmt->fetchAll();
?>

<div class="main-content-section active" style="display:block;">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h3 class="fw-bold mb-0">My Patients</h3>
        <div class="input-group" style="max-width: 300px;">
            <input type="text" class="form-control" placeholder="Search name...">
            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        </div>
    </div>

    <div class="custom-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($patients as $pt): 
                        $age = $pt['dob'] ? date_diff(date_create($pt['dob']), date_create('today'))->y : 'N/A';
                    ?>
                    <tr>
                        <td class="fw-semibold">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($pt['name']); ?>" class="rounded-circle me-2" width="30"> <?php echo htmlspecialchars($pt['name']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($pt['phone']); ?></td>
                        <td><?php echo htmlspecialchars($pt['gender']); ?></td>
                        <td><?php echo $age; ?> Years</td>
                        <td>
                            <a href="patient-history.php?id=<?php echo $pt['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View History</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($patients)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">No patients found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

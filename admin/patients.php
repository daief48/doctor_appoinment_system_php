<?php 
$page_title = 'Patient Management';
$current_page = 'patients';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

// Fetch Patients
$stmt = $pdo->query("SELECT * FROM patients ORDER BY created_at DESC");
$patients = $stmt->fetchAll();
$total_patients = count($patients);
?>

<!-- Main Content -->
<div class="main-content">
    <div class="page-header-card d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="bi bi-people-fill me-2"></i>Patients Management</h2>
            <p>View and manage all registered patients.</p>
        </div>
        <div class="text-end">
            <div class="bg-white bg-opacity-20 rounded-3 px-4 py-2 d-inline-block text-white">
                <div class="fw-800" style="font-size: 1.8rem;"><?php echo $total_patients; ?></div>
                <div class="small opacity-75">Total Patients</div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if(function_exists('displayFlashMessage')) displayFlashMessage(); ?>

    <!-- Search -->
    <div class="search-filter-section">
        <div class="flex-grow-1 position-relative">
            <i class="bi bi-search position-absolute text-muted" style="left: 14px; top: 50%; transform: translateY(-50%);"></i>
            <input type="text" class="form-control ps-5" id="patientSearch" placeholder="Search by name, email, or phone...">
        </div>
        <select class="form-select" id="genderFilter" style="width: auto;">
            <option value="">All Genders</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <!-- Patients Table -->
    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-table me-2 text-primary"></i>Patients List</h5>
            <small class="text-muted"><?php echo $total_patients; ?> records found</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="patientsTable">
                    <thead>
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Patient</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Date of Birth</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($patients as $i => $patient): ?>
                        <tr>
                            <td class="ps-4 text-muted"><?php echo $i + 1; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary">
                                        <?php echo strtoupper(substr($patient['name'], 0, 1)); ?>
                                    </div>
                                    <span class="fw-600"><?php echo htmlspecialchars($patient['name']); ?></span>
                                </div>
                            </td>
                            <td class="text-muted"><?php echo htmlspecialchars($patient['email']); ?></td>
                            <td><?php echo htmlspecialchars($patient['phone'] ?? 'N/A'); ?></td>
                            <td>
                                <?php $g = $patient['gender'] ?? 'N/A'; ?>
                                <span class="badge <?php echo $g=='Male' ? 'bg-info-soft text-info' : ($g=='Female' ? 'bg-success-soft text-success' : 'bg-secondary bg-opacity-10 text-secondary'); ?>">
                                    <?php echo $g; ?>
                                </span>
                            </td>
                            <td class="text-muted"><?php echo $patient['dob'] ? date('M d, Y', strtotime($patient['dob'])) : 'N/A'; ?></td>
                            <td class="text-muted small"><?php echo date('M d, Y', strtotime($patient['created_at'])); ?></td>
                            <td>
                                <a href="api/admin_actions.php?action=delete_patient&id=<?php echo $patient['id']; ?>" 
                                   class="btn btn-sm btn-delete-modern action-btn" 
                                   onclick="return confirm('Delete this patient? This action cannot be undone.')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($patients)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                    <p class="mt-3 text-muted">No patients registered yet.</p>
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
$(document).ready(function() {
    function filterTable() {
        const search = $('#patientSearch').val().toLowerCase();
        const gender = $('#genderFilter').val().toLowerCase();
        $('#patientsTable tbody tr').each(function() {
            const text = $(this).text().toLowerCase();
            const rowGender = $(this).find('td:eq(4)').text().trim().toLowerCase();
            const matchSearch = text.includes(search);
            const matchGender = gender === '' || rowGender.includes(gender);
            $(this).toggle(matchSearch && matchGender);
        });
    }
    $('#patientSearch, #genderFilter').on('input change', filterTable);
});
</script>

<?php include 'includes/footer.php'; ?>

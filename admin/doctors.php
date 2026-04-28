<?php 
$page_title = 'Doctor Management';
$current_page = 'doctors';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

// Fetch Departments
$dept_stmt = $pdo->query("SELECT * FROM departments");
$departments = $dept_stmt->fetchAll();

// Fetch Doctors
$doctor_stmt = $pdo->query("SELECT d.*, dept.name as dept_name FROM doctors d LEFT JOIN departments dept ON d.department_id = dept.id ORDER BY d.created_at DESC");
$doctors = $doctor_stmt->fetchAll();
?>

<style>
/* Doctors page - overrides using shared design system */
.dashboard-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(79, 70, 229, 0.2);
}
.dashboard-header h2 { font-weight: 800; margin-bottom: 5px; }
.welcome-message { opacity: 0.9; margin: 0; font-size: 1.1rem; }
.btn-light-modern {
    background: white; color: #4f46e5; border-radius: 12px; font-weight: 600; padding: 10px 24px; transition: all 0.3s; border: none; text-decoration: none;
}
.btn-light-modern:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); color: #4f46e5; }

/* Search & Filter Section */
.search-filter-section {
    background: white; border-radius: 16px; padding: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); margin-bottom: 30px; display: flex; gap: 15px; align-items: center;
}
.search-filter-section .form-control {
    border-radius: 12px; border: 1px solid #e2e8f0; padding: 12px 20px; background: #f8fafc; transition: all 0.3s;
}
.search-filter-section .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); background: white; }

/* Table Styling */
.modern-card {
    border: none; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); overflow: hidden; background: white;
}
.modern-card .card-header {
    background: white; border-bottom: 1px solid #f1f5f9; padding: 25px 30px; display: flex; justify-content: space-between; align-items: center;
}
.modern-card .card-header h5 { font-weight: 700; color: #1e293b; margin: 0; font-size: 1.25rem; }
.modern-card .card-header .text-muted { font-size: 0.9rem; font-weight: 500; }

.table > :not(caption) > * > * { padding: 18px 25px; border-bottom-color: #f1f5f9; vertical-align: middle; }
.table thead th { font-weight: 600; color: #64748b; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; }
.table tbody tr { transition: all 0.2s; }
.table tbody tr:hover { background-color: #f8fafc; transform: scale(1.001); }

/* Badges */
.badge-soft-success { background: #dcfce7; color: #166534; padding: 8px 12px; border-radius: 30px; font-weight: 600; font-size: 0.8rem; }
.badge-soft-secondary { background: #f1f5f9; color: #475569; padding: 8px 12px; border-radius: 30px; font-weight: 600; font-size: 0.8rem; }

/* Action Buttons */
.action-btn { display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.2s; border: none; font-weight: 600; }
.btn-edit-modern { background: #e0e7ff; color: #4f46e5; margin-right: 8px; }
.btn-edit-modern:hover { background: #4f46e5; color: white; transform: translateY(-2px); }
.btn-delete-modern { background: #fee2e2; color: #ef4444; }
.btn-delete-modern:hover { background: #ef4444; color: white; transform: translateY(-2px); }

/* Modal Styling */
.modal-content { border-radius: 24px; border: none; box-shadow: 0 25px 50px rgba(0,0,0,0.15); overflow: hidden; }
.modal-header { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 25px 30px; }
.modal-header .modal-title { font-weight: 800; color: #1e293b; }
.modal-body { padding: 30px; }
.modal-footer { padding: 20px 30px; border-top: 1px solid #e2e8f0; background: #f8fafc; }
.modal-body .form-label { font-weight: 600; color: #475569; font-size: 0.9rem; margin-bottom: 8px; }
.modal-body .form-control { border-radius: 12px; border: 1px solid #e2e8f0; padding: 12px 18px; transition: all 0.2s; }
.modal-body .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
</style>

<!-- Main Content -->
<div class="main-content">
    <div class="page-header-card d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="bi bi-person-badge me-2"></i>Doctors Management</h2>
            <p>Manage and organize all healthcare professionals.</p>
        </div>
        <button class="btn btn-light-modern" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
            <i class="bi bi-plus-circle"></i> Add New Doctor
        </button>
    </div>

    <!-- Flash Messages -->
    <?php displayFlashMessage(); ?>

    <!-- Search & Filter -->
    <div class="search-filter-section">
        <input type="text" class="form-control" id="doctorSearch" placeholder="Search by name, email, or specialization...">
        <select class="form-control" id="specializationFilter">
            <option value="">All Specializations</option>
            <?php foreach($departments as $dept): ?>
                <option value="<?php echo $dept['name']; ?>"><?php echo $dept['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <select class="form-control" id="statusFilter">
            <option value="">All Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
        <button class="btn btn-secondary" onclick="resetFilters()">
            <i class="bi bi-arrow-clockwise"></i> Reset
        </button>
    </div>

    <!-- Doctors Table -->
    <div class="modern-card">
        <div class="card-header">
            <h5>Doctors List</h5>
            <small class="text-muted">Total Doctors: <?php echo count($doctors); ?></small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="doctorsTable">
                    <thead>
                        <tr>
                            <th>Doctor Name</th>
                            <th>Specialization</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Fee</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($doctors as $doctor): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    
<?php if(!empty($doctor['profile_image']) && file_exists(__DIR__ . '/../assets/images/doctors/' . $doctor['profile_image'])): ?>
    <img src="../assets/images/doctors/<?php echo $doctor['profile_image']; ?>" class="avatar-sm me-3 shadow-sm" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" alt="Doctor">
<?php else: ?>
    <div class="avatar-sm me-3 shadow-sm" style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
        <?php 
            $names = explode(' ', $doctor['name']);
            echo strtoupper(substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : ''));
        ?>
    </div>
<?php endif; ?>

                                    <?php echo htmlspecialchars($doctor['name']); ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($doctor['dept_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($doctor['email']); ?></td>
                            <td><?php echo htmlspecialchars($doctor['phone'] ?? 'N/A'); ?></td>
                            <td>৳ <?php echo number_format($doctor['fee'], 2); ?></td>
                            <td>
                                <?php if($doctor['status'] == 'active'): ?>
                                    <span class="badge-soft-success"><i class="bi bi-check-circle"></i> Active</span>
                                <?php else: ?>
                                    <span class="badge-soft-secondary"><i class="bi bi-x-circle"></i> Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm action-btn btn-edit-modern edit-doctor-btn" 
                                            data-id="<?php echo $doctor['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($doctor['name']); ?>"
                                            data-email="<?php echo htmlspecialchars($doctor['email']); ?>"
                                            data-phone="<?php echo htmlspecialchars($doctor['phone']); ?>"
                                            data-dept="<?php echo $doctor['department_id']; ?>"
                                            data-fee="<?php echo $doctor['fee']; ?>"
                                            data-status="<?php echo $doctor['status']; ?>"
                                            data-bs-toggle="modal" data-bs-target="#editDoctorModal">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </button>
                                    <a href="api/admin_actions.php?action=delete_doctor&id=<?php echo $doctor['id']; ?>" 
                                       class="btn btn-sm action-btn btn-delete-modern" 
                                       onclick="return confirm('Are you sure you want to delete this doctor?')">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Doctor Modal -->
<div class="modal fade" id="addDoctorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="api/admin_actions.php?action=add_doctor" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name*</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address*</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password*</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number*</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Department*</label>
                            <select name="department_id" class="form-control" required>
                                <option value="">Select Department</option>
                                <?php foreach($departments as $dept): ?>
                                    <option value="<?php echo $dept['id']; ?>"><?php echo $dept['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Consultation Fee (৳)*</label>
                            <input type="number" name="fee" class="form-control" required step="0.01">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status*</label>
                            <select name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="profile_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Doctor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Doctor Modal -->
<div class="modal fade" id="editDoctorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="api/admin_actions.php?action=edit_doctor" method="POST">
                <input type="hidden" name="doctor_id" id="edit_doctor_id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name*</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address*</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number*</label>
                            <input type="tel" name="phone" id="edit_phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Department*</label>
                            <select name="department_id" id="edit_department_id" class="form-control" required>
                                <?php foreach($departments as $dept): ?>
                                    <option value="<?php echo $dept['id']; ?>"><?php echo $dept['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Consultation Fee (৳)*</label>
                            <input type="number" name="fee" id="edit_fee" class="form-control" required step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status*</label>
                            <select name="status" id="edit_status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 text-muted">
                        <small>Leave password blank to keep current password.</small>
                        <input type="password" name="password" class="form-control" placeholder="New Password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.edit-doctor-btn').on('click', function() {
        $('#edit_doctor_id').val($(this).data('id'));
        $('#edit_name').val($(this).data('name'));
        $('#edit_email').val($(this).data('email'));
        $('#edit_phone').val($(this).data('phone'));
        $('#edit_department_id').val($(this).data('dept'));
        $('#edit_fee').val($(this).data('fee'));
        $('#edit_status').val($(this).data('status'));
    });
});
</script>

<?php include 'includes/footer.php'; ?>

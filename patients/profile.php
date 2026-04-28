<?php
$page_title = 'My Profile';
include 'includes/header.php';
include 'includes/navbar.php';

if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $profile_image = $patient['profile_image'];

    // Handle Image Upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "assets/uploads/patients/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_ext = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
        $file_name = "patient_" . $patient_id . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $file_name;

        // Allow certain file formats
        $allow_types = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array(strtolower($file_ext), $allow_types)) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image = $file_name;
            }
        }
    }

    try {
        $stmt = $pdo->prepare("UPDATE patients SET name = ?, phone = ?, gender = ?, dob = ?, address = ?, profile_image = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $gender, $dob, $address, $profile_image, $patient_id]);
        setFlashMessage('success', 'Profile updated successfully!');
        
        // Refresh patient data
        $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
        $stmt->execute([$patient_id]);
        $patient = $stmt->fetch();
    } catch (PDOException $e) {
        setFlashMessage('danger', 'Update failed: ' . $e->getMessage());
    }
}
?>

<style>

    .form-section-title {
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--primary);
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .input-group-modern {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .input-group-modern i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        transition: color 0.3s ease;
    }

    .form-control-modern {
        padding-left: 3rem !important;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background-color: #f8fafc;
    }

    .form-control-modern:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        background-color: white;
    }

    .form-control-modern:focus + i {
        color: var(--primary);
    }

    .profile-summary-card {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        text-align: center;
        border: 1px solid #e2e8f0;
        box-shadow: var(--shadow-soft);
        height: 100%;
    }

    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 1.5rem;
    }

    .avatar-wrapper img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        padding: 4px;
        background: white;
        border: 4px solid #eef2ff;
        object-fit: cover;
    }

    .avatar-badge {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 24px;
        height: 24px;
        background: #10b981;
        border: 3px solid white;
        border-radius: 50%;
    }

    .info-list {
        text-align: left;
        margin-top: 2rem;
        padding: 0;
        list-style: none;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        background: #f1f5f9;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 1rem;
    }

    .gender-option {
        flex: 1;
        cursor: pointer;
    }

    .gender-option input {
        display: none;
    }

    .gender-box {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        transition: all 0.3s ease;
        font-weight: 500;
        color: #64748b;
    }

    .gender-option input:checked + .gender-box {
        background: #eef2ff;
        border-color: var(--primary);
        color: var(--primary);
    }
</style>

<div class="dashboard-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="dashboard-main">
        <div class="container-fluid">
            <div class="row g-4">
                <!-- Profile Form -->
                <div class="col-lg-8">
                    <div class="dashboard-card border-0 shadow-sm p-4 p-md-5">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-section-title">
                                <i class="fa fa-user-o"></i> Personal Details
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-slate-700">Full Name</label>
                                    <div class="input-group-modern">
                                        <i class="fa fa-user"></i>
                                        <input type="text" name="name" class="form-control form-control-modern"
                                            value="<?php echo htmlspecialchars($patient['name']); ?>" required placeholder="Enter your full name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-slate-700">Email Address</label>
                                    <div class="input-group-modern">
                                        <i class="fa fa-envelope"></i>
                                        <input type="email" class="form-control form-control-modern bg-slate-50"
                                            value="<?php echo htmlspecialchars($patient['email']); ?>" readonly disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-slate-700">Phone Number</label>
                                    <div class="input-group-modern">
                                        <i class="fa fa-phone"></i>
                                        <input type="text" name="phone" class="form-control form-control-modern"
                                            value="<?php echo htmlspecialchars($patient['phone']); ?>" placeholder="e.g. +880 1XXX-XXXXXX">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-slate-700">Date of Birth</label>
                                    <div class="input-group-modern">
                                        <i class="fa fa-calendar"></i>
                                        <input type="date" name="dob" class="form-control form-control-modern"
                                            value="<?php echo htmlspecialchars($patient['dob']); ?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-slate-700 mb-3">Gender</label>
                                    <div class="d-flex gap-3">
                                        <label class="gender-option">
                                            <input type="radio" name="gender" value="Male" <?php echo $patient['gender'] == 'Male' ? 'checked' : ''; ?>>
                                            <div class="gender-box">
                                                <i class="fa fa-mars"></i> Male
                                            </div>
                                        </label>
                                        <label class="gender-option">
                                            <input type="radio" name="gender" value="Female" <?php echo $patient['gender'] == 'Female' ? 'checked' : ''; ?>>
                                            <div class="gender-box">
                                                <i class="fa fa-venus"></i> Female
                                            </div>
                                        </label>
                                        <label class="gender-option">
                                            <input type="radio" name="gender" value="Other" <?php echo $patient['gender'] == 'Other' ? 'checked' : ''; ?>>
                                            <div class="gender-box">
                                                <i class="fa fa-genderless"></i> Other
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <label class="form-label small fw-bold text-slate-700">Profile Picture</label>
                                    <div class="input-group-modern">
                                        <i class="fa fa-camera"></i>
                                        <input type="file" name="profile_image" class="form-control form-control-modern" accept="image/*">
                                    </div>
                                    <div class="small text-muted mt-n2 mb-3">Recommended size: 200x200px. Formats: JPG, PNG, GIF.</div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-slate-700">Residential Address</label>
                                    <div class="input-group-modern">
                                        <i class="fa fa-map-marker" style="top: 20px; transform: none;"></i>
                                        <textarea name="address" class="form-control form-control-modern"
                                            rows="3" style="padding-top: 0.75rem;" placeholder="Enter your full address"><?php echo htmlspecialchars($patient['address']); ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button type="submit" class="btn-primary-modern px-5 py-3 w-100 w-md-auto" style="border-radius: 12px; box-shadow: 0 8px 20px rgba(79, 70, 229, 0.2);">
                                        <i class="fa fa-save me-2"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Profile Summary -->
                <div class="col-lg-4">
                    <div class="profile-summary-card">
                        <div class="avatar-wrapper">
                            <img src="<?php echo $patient['profile_image'] ? 'assets/uploads/patients/' . $patient['profile_image'] : 'https://ui-avatars.com/api/?name=' . urlencode($patient['name']) . '&size=200&background=4f46e5&color=fff&font-size=0.33'; ?>"
                                alt="<?php echo htmlspecialchars($patient['name']); ?>">
                            <div class="avatar-badge"></div>
                        </div>
                        <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($patient['name']); ?></h4>
                        <p class="text-muted small mb-0">Patient Account</p>
                        
                        <div class="info-list">
                            <div class="info-item">
                                <div class="info-icon"><i class="fa fa-id-badge"></i></div>
                                <div>
                                    <div class="text-muted small">Patient ID</div>
                                    <div class="fw-bold">#<?php echo str_pad($patient['id'], 5, '0', STR_PAD_LEFT); ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fa fa-calendar-check-o"></i></div>
                                <div>
                                    <div class="text-muted small">Member Since</div>
                                    <div class="fw-bold"><?php echo date('M d, Y', strtotime($patient['created_at'])); ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fa fa-check-circle text-success"></i></div>
                                <div>
                                    <div class="text-muted small">Status</div>
                                    <div class="fw-bold text-success">Verified Profile</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-top">
                            <p class="small text-muted mb-3">Looking for your health records?</p>
                            <a href="medical-history.php" class="btn btn-outline-primary btn-sm w-100 py-2" style="border-radius: 10px; border-width: 2px; font-weight: 600;">
                                View Medical History
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
<?php 
$page_title = 'Doctor Profile';
$current_page = 'profile';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $specialization = $_POST['specialization'];
    $fee = $_POST['fee'];
    $bio = $_POST['bio'];
    
    $signature_image = $doctor['signature_image'];
    $profile_image = $doctor['profile_image'];

    // Handle Signature Upload
    if (isset($_FILES['signature']) && $_FILES['signature']['error'] == 0) {
        $target_dir = "assets/images/signatures/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_ext = pathinfo($_FILES["signature"]["name"], PATHINFO_EXTENSION);
        $file_name = "sig_" . $doctor_id . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["signature"]["tmp_name"], $target_file)) {
            $signature_image = $file_name;
        }
    }

    // Handle Profile Image Upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "assets/images/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_ext = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
        $file_name = "doc_" . $doctor_id . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $profile_image = $file_name;
        }
    }

    try {
        $stmt = $pdo->prepare("UPDATE doctors SET name = ?, phone = ?, specialization = ?, fee = ?, bio = ?, signature_image = ?, profile_image = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $specialization, $fee, $bio, $signature_image, $profile_image, $doctor_id]);
        setFlashMessage('success', 'Profile updated successfully!');
        
        // Refresh doctor data
        $stmt = $pdo->prepare("SELECT * FROM doctors WHERE id = ?");
        $stmt->execute([$doctor_id]);
        $doctor = $stmt->fetch();
    } catch (PDOException $e) {
        setFlashMessage('danger', 'Update failed: ' . $e->getMessage());
    }
}
?>

<div class="main-content-section active" style="display:block;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Profile Settings</h3>
    </div>

    <?php displayFlashMessage(); ?>

    <div class="row">
        <div class="col-md-4 text-center">
            <div class="card p-4 shadow-sm mb-4">
                <div class="position-relative d-inline-block mb-3">
                    <img src="<?php echo $doctor['profile_image'] ? 'assets/images/'.$doctor['profile_image'] : 'https://ui-avatars.com/api/?name='.urlencode($doctor['name']).'&background=0b5ed7&color=fff&size=128'; ?>" class="rounded-circle border shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <h4><?php echo htmlspecialchars($doctor['name']); ?></h4>
                <p class="text-muted mb-0"><?php echo htmlspecialchars($doctor['specialization']); ?></p>
            </div>
            
            <div class="card p-4 shadow-sm">
                <h5 class="fw-bold">Your Signature</h5>
                <hr>
                <?php if ($doctor['signature_image']): ?>
                    <img src="assets/images/signatures/<?php echo $doctor['signature_image']; ?>" class="img-fluid mb-3 border p-2" style="max-height: 100px; background: white;">
                    <p class="small text-success">Signature uploaded ✓</p>
                <?php else: ?>
                    <div class="py-4 border rounded bg-light mb-3">
                        <i class="fa fa-pencil-square-o fa-2x text-muted"></i>
                        <p class="small text-muted mt-2">No signature uploaded yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-4 shadow-sm">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($doctor['name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Email (Read-only)</label>
                            <input type="email" class="form-control bg-light" value="<?php echo htmlspecialchars($doctor['email']); ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($doctor['phone']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Consultation Fee (৳)</label>
                            <input type="number" name="fee" class="form-control" value="<?php echo htmlspecialchars($doctor['fee']); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Specialization</label>
                        <input type="text" name="specialization" class="form-control" value="<?php echo htmlspecialchars($doctor['specialization']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Bio / Experience</label>
                        <textarea name="bio" class="form-control" rows="4"><?php echo htmlspecialchars($doctor['bio']); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold"><i class="fa fa-camera"></i> Profile Picture</label>
                            <input type="file" name="profile_image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold"><i class="fa fa-pencil"></i> Signature Image</label>
                            <input type="file" name="signature" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="text-end border-top pt-4">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill">
                            <i class="fa fa-save me-2"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

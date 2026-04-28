<?php 
$page_title = 'System Settings';
$current_page = 'settings';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

// Mock settings data
$settings = [
    'site_name' => 'Health Center Management System',
    'contact_email' => 'support@healthcenter.com',
    'contact_phone' => '+880 1712-345678',
    'currency' => 'BDT (৳)',
    'address' => '123 Medical Road, Dhaka, Bangladesh',
    'opening_hours' => '09:00 AM - 08:00 PM',
    'timezone' => 'Asia/Dhaka'
];
?>

<!-- Main Content -->
<div class="main-content">
    <div class="dashboard-header">
        <h2>System Settings</h2>
        <p class="welcome-message">Configure your hospital management system preferences.</p>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 text-primary">General Configuration</h5>
                </div>
                <div class="card-body">
                    <form action="api/admin_actions.php?action=update_settings" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Site Name</label>
                                <input type="text" name="site_name" class="form-control" value="<?php echo $settings['site_name']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">System Email</label>
                                <input type="email" name="contact_email" class="form-control" value="<?php echo $settings['contact_email']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Contact Phone</label>
                                <input type="text" name="contact_phone" class="form-control" value="<?php echo $settings['contact_phone']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Base Currency</label>
                                <select class="form-select">
                                    <option selected><?php echo $settings['currency']; ?></option>
                                    <option>USD ($)</option>
                                    <option>EUR (€)</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Physical Address</label>
                            <textarea class="form-control" rows="2"><?php echo $settings['address']; ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Business Hours</label>
                                <input type="text" class="form-control" value="<?php echo $settings['opening_hours']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Timezone</label>
                                <input type="text" class="form-control" value="<?php echo $settings['timezone']; ?>" disabled>
                            </div>
                        </div>

                        <div class="mt-4 pt-2">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                                <i class="bi bi-save me-2"></i> Save All Changes
                            </button>
                            <button type="reset" class="btn btn-light px-4 py-2 ms-2">Discard Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 bg-primary text-white mb-4">
                <div class="card-body p-4">
                    <h5><i class="bi bi-shield-lock me-2"></i> Security Setup</h5>
                    <p class="small opacity-75">Update your administrator password and two-factor authentication settings.</p>
                    <a href="#" class="btn btn-sm btn-light text-primary fw-bold">Manage Security</a>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">System Info</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">PHP Version</span>
                            <span class="fw-bold"><?php echo phpversion(); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">MySQL Version</span>
                            <span class="fw-bold">8.0.x</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Server OS</span>
                            <span class="fw-bold">Windows x64</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Last Backup</span>
                            <span class="text-success fw-bold">Today, 04:00 AM</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

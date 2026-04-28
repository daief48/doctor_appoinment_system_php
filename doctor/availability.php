<?php 
$page_title = 'Manage Availability';
$current_page = 'schedule';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_availability'])) {
    $doctor_id = $_SESSION['doctor_id'];
    
    try {
        $pdo->beginTransaction();
        
        // Remove existing availability
        $stmt = $pdo->prepare("DELETE FROM doctor_availability WHERE doctor_id = ?");
        $stmt->execute([$doctor_id]);
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($days as $day) {
            if (isset($_POST['available_days'][$day])) {
                $start = $_POST['start_time'][$day];
                $end = $_POST['end_time'][$day];
                
                $stmt = $pdo->prepare("INSERT INTO doctor_availability (doctor_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)");
                $stmt->execute([$doctor_id, $day, $start, $end]);
            }
        }
        
        $pdo->commit();
        setFlashMessage('success', 'Availability updated successfully!');
    } catch (Exception $e) {
        $pdo->rollBack();
        setFlashMessage('danger', 'Error updating availability: ' . $e->getMessage());
    }
}

// Fetch current availability
$stmt = $pdo->prepare("SELECT * FROM doctor_availability WHERE doctor_id = ?");
$stmt->execute([$doctor_id]);
$availability_rows = $stmt->fetchAll();
$current_availability = [];
foreach ($availability_rows as $row) {
    $current_availability[$row['day_of_week']] = $row;
}
?>

<div class="main-content-section active" style="display:block;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Manage My Availability</h3>
        <a href="schedule.php" class="btn btn-light"><i class="bi bi-arrow-left"></i> Back to Schedule</a>
    </div>

    <?php displayFlashMessage(); ?>

    <div class="card p-4 shadow-sm">
        <p class="text-muted mb-4">Set your working hours for each day of the week. This will affect when patients can book appointments with you.</p>
        
        <form method="POST">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th width="50">Select</th>
                            <th>Day of Week</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        foreach($days as $day): 
                            $is_available = isset($current_availability[$day]);
                            $start = $is_available ? $current_availability[$day]['start_time'] : '09:00:00';
                            $end = $is_available ? $current_availability[$day]['end_time'] : '17:00:00';
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="available_days[<?php echo $day; ?>]" value="1" <?php echo $is_available ? 'checked' : ''; ?> class="form-check-input">
                            </td>
                            <td><strong><?php echo $day; ?></strong></td>
                            <td>
                                <input type="time" name="start_time[<?php echo $day; ?>]" class="form-control form-control-sm" value="<?php echo $start; ?>">
                            </td>
                            <td>
                                <input type="time" name="end_time[<?php echo $day; ?>]" class="form-control form-control-sm" value="<?php echo $end; ?>">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="text-end mt-3">
                <button type="submit" name="update_availability" class="btn btn-primary px-5">Save My Availability</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<?php 
$page_title = 'Write Prescription';
$current_page = 'prescriptions';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

$appointment_id = $_GET['appointment_id'] ?? null;
if (!$appointment_id) {
    echo "Appointment ID required.";
    exit();
}

// Fetch Appointment Details
$stmt = $pdo->prepare("SELECT a.*, p.name as patient_name, p.id as patient_id FROM appointments a JOIN patients p ON a.patient_id = p.id WHERE a.id = ? AND a.doctor_id = ?");
$stmt->execute([$appointment_id, $doctor_id]);
$appt = $stmt->fetch();

if (!$appt) {
    echo "Invalid appointment.";
    exit();
}
?>

<div class="main-content-section active" style="display:block;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Write Prescription</h3>
    </div>

    <div class="card p-4 shadow-sm">
        <form action="api/doctor_actions.php?action=save_prescription" method="POST">
            <input type="hidden" name="appointment_id" value="<?php echo $appt['id']; ?>">
            <input type="hidden" name="patient_id" value="<?php echo $appt['patient_id']; ?>">
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5><strong>Patient:</strong> <?php echo htmlspecialchars($appt['patient_name']); ?></h5>
                </div>
                <div class="col-md-6 text-end text-muted">
                    Date: <?php echo date('d M, Y'); ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Diagnosis</label>
                <textarea name="diagnosis" class="form-control" rows="3" placeholder="Enter patient diagnosis..." required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Medicines (Rx)</label>
                <div id="medicine-list">
                    <div class="row mb-2 medicine-item">
                        <div class="col-md-5">
                            <input type="text" name="medicines[0][name]" class="form-control" placeholder="Medicine Name" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="medicines[0][dosage]" class="form-control" placeholder="Dosage (e.g. 1+0+1)">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="medicines[0][duration]" class="form-control" placeholder="Duration (e.g. 7 days)">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addMedicine()">+ Add More Medicine</button>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Recommended Tests</label>
                    <textarea name="tests" class="form-control" rows="3" placeholder="Blood test, X-Ray, etc."></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">General Advice</label>
                    <textarea name="advice" class="form-control" rows="3" placeholder="Rest, diet plan, etc."></textarea>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="reset" class="btn btn-light me-2">Reset</button>
                <button type="submit" class="btn btn-primary px-5">Submit Prescription</button>
            </div>
        </form>
    </div>
</div>

<script>
let medCount = 1;
function addMedicine() {
    const html = `
    <div class="row mb-2 medicine-item">
        <div class="col-md-5">
            <input type="text" name="medicines[${medCount}][name]" class="form-control" placeholder="Medicine Name">
        </div>
        <div class="col-md-3">
            <input type="text" name="medicines[${medCount}][dosage]" class="form-control" placeholder="Dosage">
        </div>
        <div class="col-md-3">
            <input type="text" name="medicines[${medCount}][duration]" class="form-control" placeholder="Duration">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('.medicine-item').remove()">×</button>
        </div>
    </div>`;
    $('#medicine-list').append(html);
    medCount++;
}
</script>

<?php include 'includes/footer.php'; ?>

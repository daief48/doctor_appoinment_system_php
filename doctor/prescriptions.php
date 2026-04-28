<?php 
$page_title = 'My Prescriptions';
$current_page = 'prescriptions';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/navbar.php'; 

// Fetch Prescriptions written by this doctor
$stmt = $pdo->prepare("SELECT pr.*, p.name as patient_name, a.appointment_date, d.signature_image, d.name as doctor_name
                       FROM prescriptions pr 
                       JOIN patients p ON pr.patient_id = p.id 
                       JOIN appointments a ON pr.appointment_id = a.id 
                       JOIN doctors d ON pr.doctor_id = d.id
                       WHERE pr.doctor_id = ? 
                       ORDER BY pr.created_at DESC");
$stmt->execute([$doctor_id]);
$prescriptions = $stmt->fetchAll();
?>

<div class="main-content-section active" style="display:block;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Prescription History</h3>
        <a href="appointments.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> New Prescription</a>
    </div>

    <?php displayFlashMessage(); ?>

    <div class="custom-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Diagnosis</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($prescriptions as $p): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($p['appointment_date'])); ?></td>
                        <td><strong><?php echo htmlspecialchars($p['patient_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($p['diagnosis']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewPrescription<?php echo $p['id']; ?>">
                                <i class="bi bi-eye"></i> View
                            </button>

                            <!-- View Modal -->
                            <div class="modal fade" id="viewPrescription<?php echo $p['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content" id="pr-content-<?php echo $p['id']; ?>">
                                        <div class="modal-header d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <img src="../images/logo.png" alt="Logo" style="height: 50px; margin-right: 15px;">
                                                <div>
                                                    <h5 class="modal-title mb-0">Medi Cares Hospital</h5>
                                                    <small class="text-muted">Premium Medical Care</small>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body" style="padding-bottom: 40px;">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <p><strong>Patient:</strong> <?php echo htmlspecialchars($p['patient_name']); ?></p>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($p['appointment_date'])); ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="mb-3">
                                                <p><strong>Diagnosis:</strong></p>
                                                <p><?php echo nl2br(htmlspecialchars($p['diagnosis'])); ?></p>
                                            </div>
                                            <div class="mb-3">
                                                <p><strong>Medicines:</strong></p>
                                                <ul>
                                                    <?php 
                                                    $meds = json_decode($p['medicines'], true);
                                                    if ($meds) {
                                                        foreach($meds as $m): if(empty($m['name'])) continue; ?>
                                                            <li><strong><?php echo htmlspecialchars($m['name']); ?></strong> - <?php echo htmlspecialchars($m['dosage']); ?> (<?php echo htmlspecialchars($m['duration']); ?>)</li>
                                                        <?php endforeach; 
                                                    } ?>
                                                </ul>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Tests:</strong></p>
                                                    <p><?php echo nl2br(htmlspecialchars($p['tests'] ?: 'None')); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Advice:</strong></p>
                                                    <p><?php echo nl2br(htmlspecialchars($p['advice'] ?: 'None')); ?></p>
                                                </div>
                                            </div>

                                            <!-- Signature Section -->
                                            <div class="row mt-4">
                                                <div class="col-md-4 offset-md-8 text-center">
                                                    <?php if ($p['signature_image']): ?>
                                                        <img src="assets/images/signatures/<?php echo $p['signature_image']; ?>" style="max-height: 70px; width: auto;" alt="Signature">
                                                        <hr class="mt-1 mb-1">
                                                        <p class="small mb-0"><?php echo htmlspecialchars($p['doctor_name']); ?></p>
                                                        <p class="small text-muted mb-0">Authorized Signature</p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" onclick="downloadPrescription('pr-content-<?php echo $p['id']; ?>', 'Prescription_<?php echo date('Ymd', strtotime($p['appointment_date'])); ?>_<?php echo $p['id']; ?>.pdf')"><i class="bi bi-download"></i> Download PDF</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($prescriptions)): ?>
                        <tr><td colspan="4" class="text-center py-4 text-muted">No prescriptions found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add html2pdf library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPrescription(elementId, filename) {
    const element = document.getElementById(elementId);
    
    // Temporarily hide the footer buttons during PDF generation
    const footer = element.querySelector('.modal-footer');
    const closeBtn = element.querySelector('.btn-close');
    
    if (footer) footer.style.display = 'none';
    if (closeBtn) closeBtn.style.display = 'none';
    
    var opt = {
      margin:       0.5,
      filename:     filename,
      image:        { type: 'jpeg', quality: 0.98 },
      html2canvas:  { scale: 2, useCORS: true },
      jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
    };

    // Generate and save the PDF
    html2pdf().set(opt).from(element).save().then(function() {
        // Restore the buttons after generation
        if (footer) footer.style.display = 'flex';
        if (closeBtn) closeBtn.style.display = 'block';
    });
}
</script>

<?php include 'includes/footer.php'; ?>

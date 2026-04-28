<?php 
$page_title = 'My Prescriptions';
include 'includes/header.php'; 
include 'includes/navbar.php'; 

if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

// Fetch Prescriptions
$stmt = $pdo->prepare("SELECT pr.*, d.name as doctor_name, d.signature_image, d.specialization, a.appointment_date 
                       FROM prescriptions pr 
                       JOIN doctors d ON pr.doctor_id = d.id 
                       JOIN appointments a ON pr.appointment_id = a.id 
                       WHERE pr.patient_id = ? 
                       ORDER BY pr.created_at DESC");
$stmt->execute([$patient_id]);
$prescriptions = $stmt->fetchAll();
?>

<div class="dashboard-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="dashboard-main">
        <div class="container-fluid">
            <div class="mb-5">
                <h2 class="fw-bold mb-1">My Prescriptions</h2>
                <p class="text-muted mb-0">Secure access to all your medical advice and medication plans.</p>
            </div>

            <div class="dashboard-card p-0 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Date</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">Consulting Doctor</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">Diagnosis</th>
                                <th class="pe-4 py-3 text-end text-uppercase small fw-bold text-muted">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($prescriptions as $pr): ?>
                            <tr class="align-middle">
                                <td class="ps-4 py-4 fw-semibold text-dark">
                                    <?php echo date('M d, Y', strtotime($pr['appointment_date'])); ?>
                                </td>
                                <td class="py-4">
                                    <h6 class="fw-bold mb-0">Dr. <?php echo htmlspecialchars($pr['doctor_name']); ?></h6>
                                    <p class="text-muted small mb-0"><?php echo htmlspecialchars($pr['specialization']); ?></p>
                                </td>
                                <td class="py-4">
                                    <span class="text-slate-700"><?php echo htmlspecialchars($pr['diagnosis']); ?></span>
                                </td>
                                <td class="pe-4 py-4 text-end">
                                    <button class="btn btn-sm btn-primary-modern px-4" data-toggle="modal" data-target="#viewPr<?php echo $pr['id']; ?>">
                                        View Prescription
                                    </button>

                                    <!-- Modern Modal -->
                                    <div class="modal fade" id="viewPr<?php echo $pr['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                                <div class="modal-content custom-modal-content" id="pr-content-<?php echo $pr['id']; ?>">
                                                <div class="modal-header custom-modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <div class="header-brand">
                                                        <img src="../images/logo.png" alt="Logo">
                                                        <div>
                                                            <h5 class="mb-0">Medi Cares Hospital</h5>
                                                            <p class="small mb-0 text-muted">Medical Prescription Report</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-body custom-modal-body">
                                                    <div class="row info-row text-left">
                                                        <div class="col-sm-6">
                                                            <p class="label">Doctor</p>
                                                            <h6>Dr. <?php echo htmlspecialchars($pr['doctor_name']); ?></h6>
                                                            <p class="small text-muted"><?php echo htmlspecialchars($pr['specialization']); ?></p>
                                                        </div>
                                                        <div class="col-sm-6 text-right">
                                                            <p class="label">Date</p>
                                                            <h6><?php echo date('M d, Y', strtotime($pr['created_at'])); ?></h6>
                                                        </div>
                                                    </div>

                                                    <div class="diagnosis-box text-left">
                                                        <p class="label">Primary Diagnosis</p>
                                                        <h5><?php echo htmlspecialchars($pr['diagnosis']); ?></h5>
                                                    </div>

                                                    <h6 class="section-title text-left"><i class="fa fa-list-ul text-primary"></i> Prescribed Medication</h6>
                                                    <div class="table-responsive text-left">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Medicine</th>
                                                                    <th>Dosage</th>
                                                                    <th>Duration</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                $meds = json_decode($pr['medicines'], true);
                                                                if ($meds) {
                                                                    foreach($meds as $med): if(empty($med['name'])) continue;
                                                                    ?>
                                                                        <tr>
                                                                            <td><strong><?php echo htmlspecialchars($med['name']); ?></strong></td>
                                                                            <td><?php echo htmlspecialchars($med['dosage']); ?></td>
                                                                            <td><?php echo htmlspecialchars($med['duration']); ?></td>
                                                                        </tr>
                                                                    <?php endforeach; 
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="row additional-info text-left">
                                                        <div class="col-md-6">
                                                            <p class="label">Recommended Tests</p>
                                                            <div class="info-box"><?php echo nl2br(htmlspecialchars($pr['tests'] ?: 'None')); ?></div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="label">Additional Advice</p>
                                                            <div class="info-box"><?php echo nl2br(htmlspecialchars($pr['advice'] ?: 'None')); ?></div>
                                                        </div>
                                                    </div>

                                                    <div class="signature-section">
                                                        <div class="signature-box">
                                                            <?php if ($pr['signature_image']): ?>
                                                                <img src="../doctor/assets/images/signatures/<?php echo $pr['signature_image']; ?>">
                                                            <?php else: ?>
                                                                <div class="empty-sig"></div>
                                                            <?php endif; ?>
                                                            <p><strong>Dr. <?php echo htmlspecialchars($pr['doctor_name']); ?></strong></p>
                                                            <p class="small text-muted">Medical Practitioner</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer custom-modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn-primary-modern" onclick="downloadPrescription('pr-content-<?php echo $pr['id']; ?>', 'Prescription_<?php echo date('Ymd', strtotime($pr['created_at'])); ?>_<?php echo $pr['id']; ?>.pdf')">
                                                        <i class="fa fa-download"></i> Download PDF
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
/* Custom Modal Styling for Bootstrap 3 */
.custom-modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}
.custom-modal-header {
    background: #ffffff;
    color: #1e293b;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    padding: 20px 30px;
    border-bottom: 3px solid #4f46e5;
}
.custom-modal-header .close {
    color: #1e293b;
    opacity: 0.5;
    text-shadow: none;
    font-size: 24px;
    margin-top: 5px;
}
.custom-modal-header .close:hover { opacity: 1; }
.header-brand {
    display: flex;
    align-items: center;
}
.header-brand img {
    height: 40px;
    margin-right: 15px;
}
.header-brand h5 { margin: 0; font-weight: 800; font-size: 18px; color: #1e293b; }
.header-brand p { margin: 0; opacity: 0.7; }
.custom-modal-body { padding: 30px; }
.info-row { border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 30px; }
.label { font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 1px; margin-bottom: 5px; }
.diagnosis-box {
    background: #f8fafc;
    border-left: 4px solid #4f46e5;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}
.diagnosis-box h5 { margin: 0; font-weight: 700; color: #1e293b; font-size: 18px; }
.section-title { font-weight: 700; margin-bottom: 15px; font-size: 16px; }
.section-title i { margin-right: 10px; }
.info-box { background: #f8fafc; padding: 15px; border-radius: 8px; font-size: 13px; min-height: 80px; border: 1px solid #e2e8f0;}
.additional-info { margin-top: 30px; margin-bottom: 40px; }
.signature-section { text-align: right; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
.signature-box { display: inline-block; text-align: center; min-width: 200px; }
.signature-box img { max-height: 60px; filter: grayscale(1) contrast(1.2); margin-bottom: 10px; }
.signature-box .empty-sig { height: 60px; margin-bottom: 10px; }
.signature-box p { margin: 0; line-height: 1.4; color: #1e293b; }
.custom-modal-footer {
    background: #f8fafc;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    padding: 20px 30px;
}
/* Print specific styles */
@media print {
    /* Hide all elements on the page */
    body * {
        visibility: hidden;
    }
    
    /* Only show elements inside the ACTIVE modal */
    .modal.in, .modal.in * {
        visibility: visible;
    }
    
    /* Position the active modal at the top left of the page */
    .modal.in {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: visible !important;
    }
    
    .modal-dialog {
        width: 100%;
        margin: 0;
        padding: 0;
    }
    
    .modal-content {
        border: none;
        box-shadow: none;
        border-radius: 0;
    }

    /* Hide the scrollbars and backgrounds that aren't needed */
    .modal-backdrop {
        display: none !important;
    }
    
    /* Hide the action buttons during print */
    .custom-modal-footer, .close {
        display: none !important;
    }
    
    /* Force background colors and gradients to print */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    @page {
        margin: 0.5cm;
    }
}
</style>

<!-- Add html2pdf library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPrescription(elementId, filename) {
    const element = document.getElementById(elementId);
    
    // Temporarily hide the footer buttons during PDF generation
    const footer = element.querySelector('.custom-modal-footer');
    const closeBtn = element.querySelector('.close');
    
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
        if (footer) footer.style.display = 'block';
        if (closeBtn) closeBtn.style.display = 'block';
    });
}
</script>

<?php include 'includes/footer.php'; ?>

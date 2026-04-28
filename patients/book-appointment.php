<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = 'Book Appointment';
include 'includes/header.php'; 
include 'includes/navbar.php'; 

$depts = $pdo->query("SELECT * FROM departments")->fetchAll();
$pre_doctor_id = $_GET['doctor_id'] ?? null;
$pre_dept_id = null;
if ($pre_doctor_id) {
    $stmt = $pdo->prepare("SELECT department_id FROM doctors WHERE id = ?");
    $stmt->execute([$pre_doctor_id]);
    $pre_dept_id = $stmt->fetchColumn();
}
?>

<style>
    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.5rem;
    }

    .section-header .icon-box {
        width: 35px;
        height: 35px;
        background: #eef2ff;
        color: var(--primary);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .section-header h6 {
        margin: 0;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #1e293b;
    }

    .input-group-modern {
        position: relative;
    }

    .input-group-modern i.input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        pointer-events: none;
        transition: color 0.3s ease;
    }

    .form-control-modern {
        padding-left: 2.8rem !important;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding-top: 0.8rem;
        padding-bottom: 0.8rem;
        background-color: #f8fafc;
        transition: all 0.3s ease;
        font-size: 15px;
        color: #334155;
    }

    .form-control-modern:focus, .form-control-modern:not(:disabled):active {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        background-color: #ffffff;
    }

    .form-control-modern:focus ~ i.input-icon, .form-control-modern:not(:disabled):active ~ i.input-icon {
        color: var(--primary);
    }

    select.form-control-modern {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
    }
    
    select.form-control-modern:disabled {
        background-color: #f1f5f9;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .form-label-modern {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.5rem;
        display: block;
    }

    .submit-btn {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.2);
    }

    .submit-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(79, 70, 229, 0.3);
        background: #4338ca;
    }

    .submit-btn:disabled {
        background: #94a3b8;
        box-shadow: none;
        cursor: not-allowed;
    }

    .bg-slate-900 { background-color: #0f172a; }
    .text-slate-400 { color: #94a3b8; }
</style>

<div class="dashboard-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="dashboard-main">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-lg-10 mx-auto">
                    <div class="dashboard-card border-0 shadow-sm p-4 p-md-5" style="border-radius: 20px;">
                        <form id="bookAppointmentForm" method="POST" action="api/patient_actions.php?action=book_appointment">
                            
                            <!-- Physician Selection -->
                            <div class="mb-5">
                                <div class="section-header">
                                    <div class="icon-box"><i class="fa fa-stethoscope"></i></div>
                                    <h6 class="text-uppercase">Physician Selection</h6>
                                </div>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label-modern">Medical Department</label>
                                        <div class="input-group-modern">
                                            <select name="department_id" id="deptSelect" class="form-control form-control-modern" required>
                                                <option value="">Select Department</option>
                                                <?php foreach($depts as $dept): ?>
                                                    <option value="<?php echo $dept['id']; ?>" <?php echo $pre_dept_id == $dept['id'] ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($dept['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <i class="fa fa-building-o input-icon"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-modern">Consulting Doctor</label>
                                        <div class="input-group-modern">
                                            <select name="doctor_id" id="doctorSelect" class="form-control form-control-modern" required <?php echo $pre_doctor_id ? '' : 'disabled'; ?>>
                                                <option value="">Select Doctor</option>
                                                <?php if($pre_doctor_id): 
                                                    $stmt = $pdo->prepare("SELECT id, name, fee FROM doctors WHERE department_id = ?");
                                                    $stmt->execute([$pre_dept_id]);
                                                    $doctors = $stmt->fetchAll();
                                                    foreach($doctors as $dr): ?>
                                                        <option value="<?php echo $dr['id']; ?>" <?php echo $pre_doctor_id == $dr['id'] ? 'selected' : ''; ?>>
                                                            Dr. <?php echo htmlspecialchars($dr['name']); ?> (৳<?php echo $dr['fee']; ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <i class="fa fa-user-md input-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule Details -->
                            <div class="mb-5">
                                <div class="section-header">
                                    <div class="icon-box"><i class="fa fa-calendar-check-o"></i></div>
                                    <h6 class="text-uppercase">Schedule Details</h6>
                                </div>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label-modern">Appointment Date</label>
                                        <div class="input-group-modern">
                                            <input type="date" name="appointment_date" id="apptDate" class="form-control form-control-modern" required min="<?php echo date('Y-m-d'); ?>">
                                            <i class="fa fa-calendar input-icon"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-modern">Time Slot</label>
                                        <div class="input-group-modern">
                                            <select name="appointment_time" id="apptTime" class="form-control form-control-modern" required <?php echo $pre_doctor_id ? '' : 'disabled'; ?>>
                                                <option value="">Select Time</option>
                                            </select>
                                            <i class="fa fa-clock-o input-icon"></i>
                                        </div>
                                        <div id="availability-badge" class="mt-2 text-end small fw-bold"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            <div class="mb-5">
                                <div class="section-header">
                                    <div class="icon-box"><i class="fa fa-pencil-square-o"></i></div>
                                    <h6 class="text-uppercase">Additional Notes</h6>
                                </div>
                                <div class="input-group-modern">
                                    <textarea name="message" class="form-control form-control-modern" rows="3" placeholder="Describe your symptoms or specific health concerns..." style="padding-top: 1rem;"></textarea>
                                    <i class="fa fa-commenting-o input-icon" style="top: 25px; transform: none;"></i>
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="submit-btn w-100" id="submitBtn" disabled>
                                    <i class="fa fa-check-circle me-2"></i> Confirm & Book Appointment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </main>
</div>

<script src="js/jquery.js"></script>
<script>
$(document).ready(function() {
    function loadAvailableSlots() {
        const doctorId = $('#doctorSelect').val();
        const date = $('#apptDate').val();

        if (doctorId && date) {
            $('#apptTime').prop('disabled', true).html('<option value="">Loading slots...</option>');
            $('#submitBtn').prop('disabled', true);
            $('#availability-badge').text('');
            
            $.ajax({
                url: 'api/patient_actions.php?action=get_available_slots',
                type: 'POST',
                data: { doctor_id: doctorId, date: date },
                success: function(response) {
                    const slots = JSON.parse(response);
                    let html = '<option value="">Select Time</option>';
                    if (slots.length > 0) {
                        slots.forEach(slot => {
                            // Format HH:mm:ss to h:mm A
                            const timeParts = slot.split(':');
                            let hours = parseInt(timeParts[0]);
                            const minutes = timeParts[1];
                            const ampm = hours >= 12 ? 'PM' : 'AM';
                            hours = hours % 12;
                            hours = hours ? hours : 12; 
                            const formattedTime = hours + ':' + minutes + ' ' + ampm;
                            
                            html += `<option value="${slot}">${formattedTime}</option>`;
                        });
                        $('#apptTime').html(html).prop('disabled', false);
                    } else {
                        $('#apptTime').html('<option value="">No slots available</option>');
                        $('#availability-badge').html('<i class="fa fa-times-circle"></i> Doctor is fully booked or off on this day').css('color', '#ef4444');
                    }
                }
            });
        }
    }

    $('#deptSelect').on('change', function() {
        const deptId = $(this).val();
        if (deptId) {
            $.ajax({
                url: 'api/patient_actions.php?action=get_doctors_by_dept',
                type: 'POST',
                data: { department_id: deptId },
                success: function(response) {
                    const doctors = JSON.parse(response);
                    let html = '<option value="">Select Doctor</option>';
                    doctors.forEach(dr => {
                        html += `<option value="${dr.id}">Dr. ${dr.name} (৳${dr.fee})</option>`;
                    });
                    $('#doctorSelect').html(html).prop('disabled', false);
                    $('#apptTime').prop('disabled', true).html('<option value="">Select Time</option>');
                    $('#availability-badge').text('');
                }
            });
        }
    });

    $('#doctorSelect, #apptDate').on('change', function() {
        loadAvailableSlots();
    });

    $('#apptTime').on('change', function() {
        if ($(this).val()) {
            $('#submitBtn').prop('disabled', false);
        } else {
            $('#submitBtn').prop('disabled', true);
        }
    });

    // If doctor/date is pre-selected, load slots immediately
    if ($('#doctorSelect').val() && $('#apptDate').val()) {
        loadAvailableSlots();
    }
});
</script>

<?php include 'includes/footer.php'; ?>

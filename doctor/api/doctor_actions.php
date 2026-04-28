<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['doctor_id'])) {
    die("Unauthorized access.");
}

$doctor_id = $_SESSION['doctor_id'];
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'update_status':
        $id = $_GET['id'];
        $status = $_GET['status'];
        
        try {
            // Ensure this appointment belongs to this doctor
            $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = ?");
            $stmt->execute([$status, $id, $doctor_id]);
            setFlashMessage('success', 'Appointment status updated to ' . $status . '!');
        } catch (PDOException $e) {
            setFlashMessage('danger', 'Error updating status: ' . $e->getMessage());
        }
        redirect('../appointments.php');
        break;

    case 'save_prescription':
        $appointment_id = $_POST['appointment_id'];
        $patient_id = $_POST['patient_id'];
        $diagnosis = $_POST['diagnosis'];
        $medicines = $_POST['medicines']; // This should be JSON or array
        $tests = $_POST['tests'];
        $advice = $_POST['advice'];

        try {
            $stmt = $pdo->prepare("INSERT INTO prescriptions (appointment_id, patient_id, doctor_id, diagnosis, medicines, tests, advice) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$appointment_id, $patient_id, $doctor_id, $diagnosis, json_encode($medicines), $tests, $advice]);
            
            // Also mark appointment as completed
            $stmt = $pdo->prepare("UPDATE appointments SET status = 'completed' WHERE id = ?");
            $stmt->execute([$appointment_id]);
            
            setFlashMessage('success', 'Prescription saved and appointment completed!');
        } catch (PDOException $e) {
            setFlashMessage('danger', 'Error saving prescription: ' . $e->getMessage());
        }
        redirect('../appointments.php');
        break;

    default:
        redirect('../index.php');
        break;
}
?>

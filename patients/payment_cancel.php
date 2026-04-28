<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['appt_id'])) {
    $appt_id = $_GET['appt_id'];
    // Mark the appointment as cancelled so it frees up the slot
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND patient_id = ? AND status = 'awaiting_payment'");
    $stmt->execute([$appt_id, $_SESSION['patient_id']]);
}

setFlashMessage('warning', 'Payment was cancelled. Your appointment booking was not completed.');
header("Location: book-appointment.php");
exit();
?>

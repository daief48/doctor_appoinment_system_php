<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['session_id'])) {
    header("Location: payments.php");
    exit();
}

$session_id = $_GET['session_id'];
$patient_id = $_SESSION['patient_id'];
$stripe_secret_key = 'YOUR_STRIPE_SECRET_KEY';

// Verify the session with Stripe
$ch = curl_init("https://api.stripe.com/v1/checkout/sessions/" . $session_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $stripe_secret_key
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 200) {
    $session_data = json_decode($response, true);
    
    // Check if payment was successful
    if ($session_data['payment_status'] === 'paid') {
        // client_reference_id contains "apptId_patientId"
        $ref_parts = explode('_', $session_data['client_reference_id']);
        $appt_id = $ref_parts[0];
        $ref_patient_id = $ref_parts[1];
        
        // Security check
        if ($ref_patient_id == $patient_id) {
            $amount = $session_data['amount_total'] / 100; // Convert back from cents/poisha
            $tx_id = $session_data['payment_intent']; // Use payment intent as transaction ID
            
            try {
                $check = $pdo->prepare("SELECT id FROM payments WHERE appointment_id = ?");
                $check->execute([$appt_id]);
                
                if ($check->rowCount() > 0) {
                    $stmt = $pdo->prepare("UPDATE payments SET status = 'paid', transaction_id = ?, payment_method = 'Stripe', amount = ? WHERE appointment_id = ?");
                    $stmt->execute([$tx_id, $amount, $appt_id]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO payments (appointment_id, patient_id, amount, transaction_id, status, payment_method) VALUES (?, ?, ?, ?, 'paid', 'Stripe')");
                    $stmt->execute([$appt_id, $patient_id, $amount, $tx_id]);
                }
                
                // Update the appointment status to pending (meaning paid, awaiting doctor approval)
                $stmt = $pdo->prepare("UPDATE appointments SET status = 'pending' WHERE id = ?");
                $stmt->execute([$appt_id]);
                
                setFlashMessage('success', 'Payment successful! Your appointment is now booked and awaiting doctor approval.');
            } catch (PDOException $e) {
                setFlashMessage('danger', 'Payment recorded by Stripe, but failed to update local database: ' . $e->getMessage());
            }
        } else {
            setFlashMessage('danger', 'Payment verification failed: Invalid user reference.');
        }
    } else {
        setFlashMessage('warning', 'Payment not completed.');
    }
} else {
    setFlashMessage('danger', 'Failed to verify payment with Stripe.');
}

header("Location: dashboard.php");
exit();
?>

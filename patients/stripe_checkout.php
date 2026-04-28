<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['pay_appointment_id'])) {
    header("Location: payments.php");
    exit();
}

$appt_id = $_POST['pay_appointment_id'];
$amount = (int)$_POST['amount']; // Amount in BDT
$patient_id = $_SESSION['patient_id'];

// Stripe API credentials
$stripe_secret_key = 'YOUR_STRIPE_SECRET_KEY';

// Determine the base URL dynamically
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

// Stripe API endpoint
$stripe_url = "https://api.stripe.com/v1/checkout/sessions";

// Prepare the request data
$data = http_build_query([
    'payment_method_types' => ['card'],
    'line_items' => [
        [
            'price_data' => [
                'currency' => 'bdt',
                'product_data' => [
                    'name' => 'Doctor Consultation Fee',
                    'description' => 'Appointment ID: ' . $appt_id,
                ],
                'unit_amount' => $amount * 100, // Stripe expects amount in the smallest currency unit
            ],
            'quantity' => 1,
        ]
    ],
    'mode' => 'payment',
    'success_url' => $base_url . '/payment_success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $base_url . '/payment_cancel.php?appt_id=' . $appt_id,
    'client_reference_id' => $appt_id . '_' . $patient_id // Passing appointment and patient ID for verification
]);

// Initialize cURL
$ch = curl_init($stripe_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $stripe_secret_key,
    'Content-Type: application/x-www-form-urlencoded'
]);

// Execute cURL request
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($http_code === 200) {
    $result = json_decode($response, true);
    if (isset($result['url'])) {
        // Redirect to Stripe Checkout page
        header("Location: " . $result['url']);
        exit();
    }
} else {
    // If there's an error, redirect back with error message
    // You could also log $response or $curl_error for debugging
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash_messages'] = [
        ['type' => 'danger', 'message' => 'Failed to initialize payment gateway. Please try again.']
    ];
    header("Location: payments.php");
    exit();
}
?>

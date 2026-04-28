<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_doctors_by_dept':
        $dept_id = $_POST['department_id'];
        $stmt = $pdo->prepare("SELECT id, name, fee FROM doctors WHERE department_id = ? AND status = 'active'");
        $stmt->execute([$dept_id]);
        echo json_encode($stmt->fetchAll());
        break;

    case 'get_available_slots':
        $doctor_id = $_POST['doctor_id'];
        $date = $_POST['date'];
        $day_of_week = date('l', strtotime($date));

        $stmt = $pdo->prepare("SELECT start_time, end_time FROM doctor_availability WHERE doctor_id = ? AND day_of_week = ?");
        $stmt->execute([$doctor_id, $day_of_week]);
        $work_hours = $stmt->fetch();

        // Default slots
        $all_slots = ["09:00:00", "10:00:00", "11:00:00", "12:00:00", "14:00:00", "15:00:00", "16:00:00", "17:00:00"];
        
        if ($work_hours) {
            $start = strtotime($work_hours['start_time']);
            $end = strtotime($work_hours['end_time']);
            $all_slots = [];
            for ($t = $start; $t < $end; $t += 3600) {
                $all_slots[] = date('H:i:s', $t);
            }
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM doctor_availability WHERE doctor_id = ?");
            $stmt->execute([$doctor_id]);
            if ($stmt->fetchColumn() > 0) { $all_slots = []; }
        }

        $stmt = $pdo->prepare("SELECT appointment_time FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND status != 'cancelled'");
        $stmt->execute([$doctor_id, $date]);
        $booked_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $available_slots = array_values(array_filter($all_slots, function($slot) use ($booked_slots) {
            return !in_array($slot, $booked_slots);
        }));

        echo json_encode($available_slots);
        break;

    case 'check_availability':
        $doctor_id = $_POST['doctor_id'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $day_of_week = date('l', strtotime($date));

        // 1. Check if already booked
        $stmt = $pdo->prepare("SELECT id FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'cancelled'");
        $stmt->execute([$doctor_id, $date, $time]);
        $already_booked = ($stmt->rowCount() > 0);

        // 2. Check if doctor works this day/time
        $stmt = $pdo->prepare("SELECT start_time, end_time FROM doctor_availability WHERE doctor_id = ? AND day_of_week = ?");
        $stmt->execute([$doctor_id, $day_of_week]);
        $work_hours = $stmt->fetch();

        $in_working_hours = true;
        if ($work_hours) {
            $start = strtotime($work_hours['start_time']);
            $end = strtotime($work_hours['end_time']);
            $check = strtotime($time);
            if ($check < $start || $check > $end) {
                $in_working_hours = false;
            }
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM doctor_availability WHERE doctor_id = ?");
            $stmt->execute([$doctor_id]);
            if ($stmt->fetchColumn() > 0) {
                $in_working_hours = false;
            }
        }
        
        $available = (!$already_booked && $in_working_hours);
        $reason = '';
        if ($already_booked) $reason = 'This slot is already booked.';
        else if (!$in_working_hours) $reason = 'Doctor is not available on this day/time.';

        echo json_encode(['available' => $available, 'reason' => $reason]);
        break;

    case 'book_appointment':
        if (!isset($_SESSION['patient_id'])) {
            die("Unauthorized");
        }

        $patient_id = $_SESSION['patient_id'];
        $doctor_id = $_POST['doctor_id'];
        $date = $_POST['appointment_date'];
        $time = $_POST['appointment_time'];
        $message = $_POST['message'];

        $stmt = $pdo->prepare("SELECT id FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'cancelled'");
        $stmt->execute([$doctor_id, $date, $time]);
        
        if ($stmt->rowCount() > 0) {
            setFlashMessage('danger', 'Error: That time slot was just taken. Please try another.');
            header("Location: ../book-appointment.php");
            exit();
        }

        try {
            $fee_stmt = $pdo->prepare("SELECT fee FROM doctors WHERE id = ?");
            $fee_stmt->execute([$doctor_id]);
            $amount = (int)$fee_stmt->fetchColumn();

            $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, message, status) VALUES (?, ?, ?, ?, ?, 'awaiting_payment')");
            $stmt->execute([$patient_id, $doctor_id, $date, $time, $message]);
            $appt_id = $pdo->lastInsertId();

            $stripe_secret_key = 'YOUR_STRIPE_SECRET_KEY';
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF']));
            $stripe_url = "https://api.stripe.com/v1/checkout/sessions";

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
                            'unit_amount' => $amount * 100,
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => $base_url . '/payment_success.php?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $base_url . '/payment_cancel.php?appt_id=' . $appt_id,
                'client_reference_id' => $appt_id . '_' . $patient_id
            ]);

            $ch = curl_init($stripe_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $stripe_secret_key,
                'Content-Type: application/x-www-form-urlencoded'
            ]);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code === 200) {
                $result = json_decode($response, true);
                if (isset($result['url'])) {
                    header("Location: " . $result['url']);
                    exit();
                }
            }
            
            $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ?")->execute([$appt_id]);
            setFlashMessage('danger', 'Failed to initialize payment gateway. Please try again.');
            header("Location: ../book-appointment.php");
            exit();

        } catch (PDOException $e) {
            setFlashMessage('danger', 'Booking failed: ' . $e->getMessage());
            header("Location: ../book-appointment.php");
            exit();
        }
        break;

    default:
        header("Location: ../index.php");
        break;
}
?>

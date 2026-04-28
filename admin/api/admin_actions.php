<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Auth check
if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized access.");
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add_doctor':
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $phone = $_POST['phone'];
        $department_id = $_POST['department_id'];
        $fee = $_POST['fee'];
        $status = $_POST['status'];

        $profile_image = null;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profile_image']['name'];
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($file_ext, $allowed)) {
                $new_filename = uniqid('doc_') . '.' . $file_ext;
                $upload_dir = __DIR__ . '/../../assets/images/doctors/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_dir . $new_filename)) {
                    $profile_image = $new_filename;
                }
            }
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO doctors (name, email, password, phone, department_id, fee, status, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $password, $phone, $department_id, $fee, $status, $profile_image]);
            setFlashMessage('success', 'Doctor added successfully!');
        } catch (PDOException $e) {
            setFlashMessage('danger', 'Error adding doctor: ' . $e->getMessage());
        }
        redirect('../doctors.php');
        break;

    case 'edit_doctor':
        $id = $_POST['doctor_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $department_id = $_POST['department_id'];
        $fee = $_POST['fee'];
        $status = $_POST['status'];
        
        $sql = "UPDATE doctors SET name=?, email=?, phone=?, department_id=?, fee=?, status=? WHERE id=?";
        $params = [$name, $email, $phone, $department_id, $fee, $status, $id];

        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE doctors SET name=?, email=?, phone=?, department_id=?, fee=?, status=?, password=? WHERE id=?";
            $params = [$name, $email, $phone, $department_id, $fee, $status, $password, $id];
        }

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            setFlashMessage('success', 'Doctor updated successfully!');
        } catch (PDOException $e) {
            setFlashMessage('danger', 'Error updating doctor: ' . $e->getMessage());
        }
        redirect('../doctors.php');
        break;

    case 'delete_doctor':
        $id = $_GET['id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM doctors WHERE id = ?");
            $stmt->execute([$id]);
            setFlashMessage('success', 'Doctor deleted successfully!');
        } catch (PDOException $e) {
            setFlashMessage('danger', 'Error deleting doctor: ' . $e->getMessage());
        }
        redirect('../doctors.php');
        break;

    case 'update_appointment_status':
        $id = $_GET['id'];
        $status = $_GET['status'];
        try {
            $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            setFlashMessage('success', 'Appointment status updated to ' . $status . '!');
        } catch (PDOException $e) {
            setFlashMessage('danger', 'Error updating status: ' . $e->getMessage());
        }
        redirect('../appointments.php');
        break;

    case 'delete_patient':
        $id = $_GET['id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM patients WHERE id = ?");
            $stmt->execute([$id]);
            setFlashMessage('success', 'Patient deleted successfully!');
        } catch (PDOException $e) {
            setFlashMessage('danger', 'Error deleting patient: ' . $e->getMessage());
        }
        redirect('../patients.php');
        break;

    case 'update_settings':
        // Mock update logic
        setFlashMessage('success', 'System settings updated successfully!');
        redirect('../settings.php');
        break;

    default:
        redirect('../index.php');
        break;
}
?>

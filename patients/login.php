<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (isset($_SESSION['patient_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM patients WHERE email = ?");
        $stmt->execute([$email]);
        $patient = $stmt->fetch();

        if ($patient && password_verify($password, $patient['password'])) {
            $_SESSION['patient_id'] = $patient['id'];
            $_SESSION['patient_name'] = $patient['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Patient Login - Health Center</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/tooplate-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .auth-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8f9fa; position: relative; }
        .auth-card { width: 100%; max-width: 400px; padding: 40px; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s ease;
        }
        .back-home:hover {
            color: #3f51b5;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <a href="index.php" class="back-home">
            <i class="bi bi-arrow-left"></i> Back to Home
        </a>
        <div class="auth-card">
            <h2 class="text-center mb-4">Patient Login</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group mb-3">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="patient@example.com" required>
                </div>
                <div class="form-group mb-4">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="text-center mt-3">
                Don't have an account? <a href="register.php">Register now</a>
            </div>
            <div class="text-center mt-3">
                <a href="index.php" class="text-decoration-none small text-muted">Back to Homepage</a>
            </div>
        </div>
    </div>
</body>
</html>

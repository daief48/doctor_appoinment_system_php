<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (isset($_SESSION['doctor_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM doctors WHERE email = ? AND status = 'active'");
        $stmt->execute([$email]);
        $doctor = $stmt->fetch();

        if ($doctor && password_verify($password, $doctor['password'])) {
            $_SESSION['doctor_id'] = $doctor['id'];
            $_SESSION['doctor_name'] = $doctor['name'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email, password, or account inactive.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Login - Health Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .login-wrapper {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f7fe;
        }
        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .back-home {
            position: fixed;
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
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <a href="../index.php" class="back-home">
        <i class="bi bi-arrow-left"></i> Back to Home
    </a>

    <div class="login-wrapper">
        <div class="login-box">
            <div class="text-center mb-4">
                <i class="bi bi-heart-pulse-fill text-primary" style="font-size: 3rem;"></i>
                <h4 class="mt-2 fw-bold">Doctor Portal</h4>
                <p class="text-muted">Welcome back, Doctor</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="doctor@example.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary p-2 fw-bold">Sign In</button>
                </div>
            </form>
            <div class="text-center mt-4">
                <a href="../index.php" class="text-decoration-none small text-muted">Back to Homepage</a>
            </div>
        </div>
    </div>
</body>
</html>

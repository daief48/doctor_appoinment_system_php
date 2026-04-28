<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header("Location: index.php");
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Health Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: #0f172a;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            margin: 0;
            overflow-x: hidden;
            overflow-y: auto;
            color: white;
            min-height: 100vh;
        }

        /* Animated gradient blobs */
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.5;
            animation: move 10s infinite alternate ease-in-out;
        }
        .blob-1 { top: -10%; left: -10%; width: 500px; height: 500px; background: #3b82f6; border-radius: 50%; }
        .blob-2 { bottom: -10%; right: -10%; width: 600px; height: 600px; background: #8b5cf6; border-radius: 50%; animation-delay: -5s; }
        .blob-3 { top: 30%; left: 40%; width: 400px; height: 400px; background: #10b981; border-radius: 50%; animation-duration: 15s; }

        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(50px, 50px) scale(1.1); }
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
            position: relative;
            z-index: 1;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-logo i {
            font-size: 3.5rem;
            background: linear-gradient(135deg, #4ade80 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
        }
        
        .login-logo h2 {
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            font-size: 2rem;
            letter-spacing: -0.5px;
        }
        
        .form-control {
            height: 50px;
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 12px !important;
            margin-bottom: 25px;
            padding: 12px 20px;
            font-size: 1rem;
            color: white !important;
            transition: all 0.3s ease;
        }
        
        .form-control::placeholder {
            color: #94a3b8 !important;
            opacity: 1;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: #4ade80 !important;
            box-shadow: 0 0 0 4px rgba(74, 222, 128, 0.1) !important;
        }

        .input-group {
            margin-bottom: 25px;
        }
        .input-group .form-control { margin-bottom: 0 !important; border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important; border-right: none !important; }
        
        .input-group .btn {
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: #94a3b8;
            border-left: none;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .input-group .btn:hover { background: rgba(255, 255, 255, 0.1); color: white; }
        
        .form-label {
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 10px;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 0.9rem;
            color: #cbd5e1;
        }
        
        .remember-forgot a {
            color: #4ade80;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .remember-forgot a:hover {
            color: #22c55e;
        }
        
        .login-btn {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #4ade80 0%, #3b82f6 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(74, 222, 128, 0.4);
            color: white;
        }

        .signup-text {
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .back-home-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            color: white !important;
            text-decoration: none !important;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.05);
            padding: 10px 20px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        .back-home-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #4ade80 !important;
            transform: translateX(-5px);
        }
    </style>
</head>
<body>
    <a href="../index.php" class="back-home-btn">
        <i class="bi bi-arrow-left"></i> Back to Home
    </a>

    <!-- Abstract Animated Background -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <i class="bi bi-shield-lock"></i>
                <h2>Admin Portal</h2>
                <p class="text-muted mt-2" style="color: #94a3b8 !important;">Secure Access Control</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #fca5a5; border-radius: 12px; font-weight: 500;"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email"
                        class="form-control" 
                        placeholder="admin@healthcenter.com" 
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="form-control" 
                            placeholder="Enter your password" 
                            required
                        >
                        <button 
                            class="btn btn-outline-secondary" 
                            type="button" 
                            onclick="togglePassword()"
                        >
                            <i class="bi bi-eye password-toggle"></i>
                        </button>
                    </div>
                </div>
                
                <div class="remember-forgot">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" style="background-color: transparent; border-color: rgba(255,255,255,0.3);">
                        <label class="form-check-label" style="margin-left: 8px;" for="rememberMe">
                            Remember me
                        </label>
                    </div>
                    <a href="#">Forgot Password?</a>
                </div>
                
                <button type="submit" class="login-btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Secure Login
                </button>
            </form>
            
            <div class="signup-text text-center">
                System Administrator Access Only
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.add('bi-eye');
                icon.classList.remove('bi-eye-slash');
            }
        }
    </script>
</body>
</html>

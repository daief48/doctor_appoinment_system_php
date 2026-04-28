<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';

$is_logged_in = isset($_SESSION['patient_id']);
if ($is_logged_in) {
    $patient_id = $_SESSION['patient_id'];
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
    $stmt->execute([$patient_id]);
    $patient = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo isset($page_title) ? $page_title : 'Health Center'; ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/tooplate-style.css">
    <link rel="stylesheet" href="css/modern-premium.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            --sidebar-bg: rgba(255, 255, 255, 0.9);
            --sidebar-text: #64748b;
            --sidebar-active-text: #ffffff;
            --background: #f1f5f9;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.04);
            --shadow-active: 0 10px 20px rgba(79, 70, 229, 0.25);
            --background: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            margin: 0;
        }

        /* Pro Dashboard Layout */
        .dashboard-wrapper {
            display: flex !important;
            min-height: 100vh !important;
            width: 100% !important;
            max-width: 100% !important;
            position: relative !important;
            margin-top: 110px !important; /* Push below fixed navbar */
        }

        .dashboard-sidebar {
            width: 280px !important;
            min-width: 280px !important;
            background: var(--sidebar-bg) !important;
            backdrop-filter: blur(20px) !important;
            color: var(--sidebar-text) !important;
            display: flex !important;
            flex-direction: column !important;
            padding: 2rem 1.25rem !important;
            margin: 1.5rem !important;
            margin-right: 0 !important;
            border-radius: 30px !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            box-shadow: var(--shadow-soft) !important;
            transition: all 0.4s ease !important;
        }

        .dashboard-main {
            flex: 1 !important;
            width: 100% !important;
            min-width: 0 !important;
            padding: 2rem !important;
            background-color: var(--background) !important;
            overflow-x: hidden !important;
            display: block !important;
        }

        .dashboard-main .container,
        .dashboard-main .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 100% !important;
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
            display: block !important;
        }

        .dashboard-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: box-shadow 0.2s ease;
        }

        .dashboard-card:hover {
            box-shadow: var(--shadow-md);
        }

        /* Sidebar Nav */
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-item {
            margin-bottom: 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 0.9rem 1.25rem;
            color: var(--sidebar-text);
            text-decoration: none !important;
            border-radius: 18px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 6px;
        }

        .sidebar-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            background-color: rgba(0, 0, 0, 0.02);
            color: var(--text-main);
            transform: translateX(5px);
        }

        .sidebar-link.active {
            background: var(--primary-gradient) !important;
            color: white !important;
            box-shadow: var(--shadow-active);
            font-weight: 600;
        }

        .sidebar-link.active i {
            transform: scale(1.1);
        }

        .sidebar-link.active::before {
            display: none;
        }

        /* Modern UI Components */
        .btn-primary-modern {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary-modern:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .icon-blue {
            background-color: #eff6ff;
            color: #3b82f6;
        }

        .icon-green {
            background-color: #ecfdf5;
            color: #10b981;
        }

        .icon-orange {
            background-color: #fff7ed;
            color: #f97316;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }

        .badge-warning {
            background-color: #fef9c3;
            color: #a16207;
        }

        .badge-info {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        /* Global Reset for Dashboard Stability */
        .dashboard-main *,
        .dashboard-main {
            box-sizing: border-box !important;
        }

        .container,
        .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 100% !important;
        }

        @media (max-width: 991px) {
            .dashboard-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 1000;
                transform: translateX(-100%);
            }

            .dashboard-sidebar.active {
                transform: translateX(0);
            }

            .dashboard-main {
                padding: 1rem !important;
            }
        }
    </style>
</head>

<body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">

    <script>
        // Neutralize WOW.js in dashboard to prevent width collapse bugs
        if (typeof WOW !== 'undefined') {
            WOW.prototype.init = function () { return this; };
        }
    </script>
<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);

// Security check
if (!isset($_SESSION['admin_logged_in']) && $current_page !== 'login.php') {
    header("Location: login.php");
    exit();
}

// Connect to database
include_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedCare | Hospital Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        /* Professional Sidebar CSS */
        .sidebar { 
            width: 260px; 
            height: 100vh; 
            position: fixed; 
            background: #1e1e2d; 
            padding-top: 20px; 
        }
        .sidebar-brand {
            color: #fff;
            font-weight: bold;
            font-size: 1.5rem;
            padding: 0 20px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .sidebar a { 
            color: #a1a5b7; 
            text-decoration: none; 
            display: block; 
            padding: 12px 20px; 
            font-weight: 500; 
            margin: 0 10px 5px 10px;
            border-radius: 8px;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active { 
            background: #0d6efd; 
            color: #fff; 
        }
        .main-content { 
            margin-left: 260px; 
            padding: 40px; 
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<?php if ($current_page !== 'login.php'): ?>
<div class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-hospital-symbol text-primary me-2"></i> MedCare
    </div>
    <a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
        <i class="fas fa-chart-pie me-2"></i> Dashboard
    </a>
    <a href="add_patient.php" class="<?= ($current_page == 'add_patient.php') ? 'active' : '' ?>">
        <i class="fas fa-user-plus me-2"></i> Add Patient
    </a>
    <a href="view_patients.php" class="<?= ($current_page == 'view_patients.php') ? 'active' : '' ?>">
        <i class="fas fa-users me-2"></i> Patient List
    </a>
    <a href="view_doctors.php" class="<?= ($current_page == 'view_doctors.php') ? 'active' : '' ?>">
        <i class="fas fa-user-md me-2"></i> Doctor List
    </a>
    
    <a href="book_appointment.php" class="<?= ($current_page == 'book_appointment.php') ? 'active' : '' ?>">
        <i class="fas fa-calendar-plus me-2"></i> Book Appointment
    </a>
    <a href="view_appointments.php" class="<?= ($current_page == 'view_appointments.php') ? 'active' : '' ?>">
        <i class="fas fa-calendar-check me-2"></i> View Appointments
    </a>

    <a href="logout.php" class="text-danger mt-4">
        <i class="fas fa-sign-out-alt me-2"></i> Logout
    </a>
</div>

<div class="main-content">
<?php endif; ?>
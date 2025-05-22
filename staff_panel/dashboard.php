<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch system reports
$sqlBookings = "SELECT COUNT(*) AS total_bookings FROM bookings";
$sqlPayments = "SELECT COUNT(*) AS total_payments FROM payments";
$sqlDailyTasks = "SELECT COUNT(*) AS total_tasks FROM daily_activities";
$sqlAdmins = "SELECT COUNT(*) AS total_admins FROM admin";

$resultBookings = $conn->query($sqlBookings)->fetch_assoc();
$resultPayments = $conn->query($sqlPayments)->fetch_assoc();
$resultDailyTasks = $conn->query($sqlDailyTasks)->fetch_assoc();
$resultAdmins = $conn->query($sqlAdmins)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Staff Dashboard - Transport Management</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; text-align: center; padding: 30px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0px 0px 15px rgba(0,0,0,0.1); }
        h2 { color: #007BFF; }
        .stats { font-size: 22px; margin-top: 10px; font-weight: bold; color: #333; }
        .quick-links { display: flex; flex-wrap: wrap; justify-content: space-between; margin-top: 30px; gap: 20px; }
        .link-card { background-color: #007BFF; color: white; padding: 15px; border-radius: 8px; text-decoration: none; font-size: 18px; width: 30%; text-align: center; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); }
        .link-card:hover { background-color: #0056b3; }
        .logout-btn { background-color: #dc3545; color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 16px; color: gray; }
    </style>
</head>
<body>
    <div class="container">
        <h2>👷 Staff Dashboard - System Management</h2>
        <p>View system reports and access management tools.</p>

        <h3>📊 System Reports</h3>
        <p class="stats">Total Admins Registered: <?= $resultAdmins["total_admins"] ?></p>
        <p class="stats">Total Bookings: <?= $resultBookings["total_bookings"] ?></p>
        <p class="stats">Total Payments Processed: <?= $resultPayments["total_payments"] ?></p>
        <p class="stats">Daily Activities Logged: <?= $resultDailyTasks["total_tasks"] ?></p>

        <h3>🔗 Staff Management</h3>
        <div class="quick-links">
            <a href="view_reports.php" class="link-card">📊 View Reports</a>
            <a href="manage_users.php" class="link-card">👥 Manage Passengers & Users</a>
            <a href="track_activities.php" class="link-card">📅 Track Daily Operations</a>
            <a href="register_admin.php" class="link-card">🛡️ Register Admin</a>
            <a href="manage_admins.php" class="link-card">⚙️ Manage Admin Accounts</a>
            <a href="assign_roles.php" class="link-card">🔑 Assign Roles & Permissions</a>
            <a href="settings.php" class="link-card">⚙️ System Settings</a>
            <a href="logs.php" class="link-card">📜 View Activity Logs</a>
        </div>

        <br><br>
        <a href="../logout.php" class="logout-btn">Logout</a>

        <div class="footer">© 2025 Transport Booking System | Designed for Efficiency & Security</div>
    </div>
</body>
</html>

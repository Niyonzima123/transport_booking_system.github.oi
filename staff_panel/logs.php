<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch activity logs
$sqlLogs = "SELECT * FROM activity_logs ORDER BY created_at DESC";
$resultLogs = $conn->query($sqlLogs);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Activity Logs - Transport Booking System</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; text-align: center; padding: 30px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: white; }
        .logout-btn { background-color: #dc3545; color: white; padding: 12px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📜 Activity Logs</h2>
        <p>Track system actions and administrator activities.</p>

        <table>
            <tr><th>Log ID</th><th>Admin Name</th><th>Action</th><th>Date & Time</th></tr>
            <?php while ($row = $resultLogs->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= htmlspecialchars($row["admin_name"]) ?></td>
                    <td><?= htmlspecialchars($row["action"]) ?></td>
                    <td><?= $row["created_at"] ?></td>
                </tr>
            <?php } ?>
        </table>

        <br>
        <a href="dashboard.php">🔙 Back to Dashboard</a>
        <br><br>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>

<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch all system reports
$sqlBookings = "SELECT * FROM bookings ORDER BY booking_date DESC";
$sqlPayments = "SELECT * FROM payments ORDER BY payment_date DESC";
$sqlDailyTasks = "SELECT * FROM daily_activities ORDER BY activity_date DESC";

$resultBookings = $conn->query($sqlBookings);
$resultPayments = $conn->query($sqlPayments);
$resultDailyTasks = $conn->query($sqlDailyTasks);
?>
<form method="GET">
    <input type="text" name="search" placeholder="Search by ID or Name">
    <button type="submit">🔍 Search</button>
</form>

<?php
$searchQuery = isset($_GET["search"]) ? $_GET["search"] : "";
$sqlBookings = "SELECT * FROM bookings WHERE passengers LIKE ? OR id LIKE ?";
$stmt = $conn->prepare($sqlBookings);
$searchParam = "%$searchQuery%";
$stmt->bind_param("ss", $searchParam, $searchParam);
$stmt->execute();
$resultBookings = $stmt->get_result();
?>
<form method="POST">
    <button type="submit" name="download_csv">📥 Export CSV</button>
</form>

<?php
if (isset($_POST["download_csv"])) {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=bookings_report.csv");
    $output = fopen("php://output", "w");
    fputcsv($output, ["Booking ID", "Passenger", "Route", "Date"]);

    while ($row = $resultBookings->fetch_assoc()) {
        fputcsv($output, [$row["id"], $row["passenger"], $row["route"], $row["date"]]);
    }
    
    fclose($output);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Reports - Transport Booking System</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        h2 { color: #007BFF; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: white; }
        .logout-btn { background-color: #dc3545; color: white; padding: 12px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📊 System Reports</h2>
        <p>View recent bookings, payments, and daily activities.</p>

        <h3>🚀 Recent Bookings</h3>
        <table>
            <tr><th>Booking ID</th><th>Passenger</th><th>Route</th><th>Date</th></tr>
            <?php while ($row = $resultBookings->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= htmlspecialchars($row["passenger_name"]) ?></td>
                    <td><?= htmlspecialchars($row["route"]) ?></td>
                    <td><?= $row["booking_date"] ?></td>
                </tr>
            <?php } ?>
        </table>

        <h3>💵 Recent Payments</h3>
        <table>
            <tr><th>Payment ID</th><th>Amount</th><th>Method</th><th>Date</th></tr>
            <?php while ($row = $resultPayments->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td>$<?= $row["amount"] ?></td>
                    <td><?= htmlspecialchars($row["payment_method"]) ?></td>
                    <td><?= $row["payment_date"] ?></td>
                </tr>
            <?php } ?>
        </table>

        <h3>📅 Staff Daily Activities</h3>
        <table>
            <tr><th>Activity ID</th><th>Staff</th><th>Description</th><th>Date</th></tr>
            <?php while ($row = $resultDailyTasks->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= htmlspecialchars($row["staff_id"]) ?></td>
                    <td><?= htmlspecialchars($row["activity_description"]) ?></td>
                    <td><?= $row["activity_date"] ?></td>
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

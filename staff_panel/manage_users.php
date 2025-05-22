<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch passengers
$sqlPassengers = "SELECT * FROM passengers ORDER BY created_at DESC";
$resultPassengers = $conn->query($sqlPassengers);

// Fetch bookings
$sqlBookings = "SELECT * FROM bookings ORDER BY booking_date DESC";
$resultBookings = $conn->query($sqlBookings);

// Download CSV function
if (isset($_POST["download_csv"])) {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=users_data.csv");
    $output = fopen("php://output", "w");
    fputcsv($output, ["ID", "Name", "Email", "Date Registered"]);
    $users = $conn->query($sqlPassengers);
    while ($row = $users->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users - Transport Booking System</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 30px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0px 0px 15px rgba(0,0,0,0.1); }
        h2 { color: #007BFF; text-align: center; }
        .action-btn { text-decoration: none; font-weight: bold; padding: 10px; border-radius: 5px; margin: 5px; display: inline-block; }
        .edit-btn { background-color: #ffc107; color: black; }
        .delete-btn { background-color: #dc3545; color: white; }
        .approve-btn { background-color: #28a745; color: white; }
        .reject-btn { background-color: #ff4c4c; color: white; }
        .download-btn { background-color: #007BFF; color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: white; }
        .footer { margin-top: 30px; text-align: center; font-size: 16px; color: gray; }
    </style>
</head>
<body>
    <div class="container">
        <h2>👥 Manage Passengers & Bookings</h2>

        <form method="POST">
            <button type="submit" name="download_csv" class="action-btn download-btn">📥 Download User Data (CSV)</button>
        </form>

        <h3>🚀 Passenger List</h3>
        <table>
            <tr><th>ID</th><th>Full Name</th><th>Email</th><th>Date Registered</th><th>Actions</th></tr>
            <?php while ($row = $resultPassengers->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= isset($row["name"]) ? htmlspecialchars($row["name"]) : "N/A"; ?></td>
                    <td><?= htmlspecialchars($row["email"]) ?></td>
                    <td><?= $row["created_at"] ?></td>
                    <td>
                        <form method="POST" action="delete_user.php">
                            <input type="hidden" name="user_id" value="<?= $row["id"] ?>">
                            <button type="submit" class="action-btn delete-btn">❌ Delete</button>
                        </form>
                        <form method="POST" action="edit_user.php">
                            <input type="hidden" name="user_id" value="<?= $row["id"] ?>">
                            <button type="submit" class="action-btn edit-btn">✏️ Edit</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <h3>📅 Bookings & Approval</h3>
        <table>
            <tr><th>Booking ID</th><th>Passenger</th><th>Route</th><th>Date</th><th>Status</th><th>Actions</th></tr>
            <?php while ($row = $resultBookings->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= htmlspecialchars($row["passenger_name"]) ?></td>
                    <td><?= htmlspecialchars($row["route"]) ?></td>
                    <td><?= $row["booking_date"] ?></td>
                    <td><?= htmlspecialchars($row["status"]) ?></td>
                    <td>
                        <form method="POST" action="approve_booking.php">
                            <input type="hidden" name="booking_id" value="<?= $row["id"] ?>">
                            <button type="submit" class="action-btn approve-btn">✅ Approve</button>
                        </form>
                        <form method="POST" action="reject_booking.php">
                            <input type="hidden" name="booking_id" value="<?= $row["id"] ?>">
                            <button type="submit" class="action-btn reject-btn">❌ Reject</button>
                        </form>
                        <form method="POST" action="delete_booking.php">
                            <input type="hidden" name="booking_id" value="<?= $row["id"] ?>">
                            <button type="submit" class="action-btn delete-btn">🗑️ Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <br>
        <div class="footer">
            <a href="dashboard.php">🔙 Back to Dashboard</a> | <a href="../logout.php">Logout</a>
        </div>
    </div>
</body>
</html>

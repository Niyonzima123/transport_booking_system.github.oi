<?php
session_start();
include '../config.php';

// Ensure admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION["admin_id"];

// Fetch bookings for the transport company
$sqlBookings = "SELECT bookings.id, passengers.first_name, passengers.last_name, buses.bus_number, bookings.seat_number, bookings.payment_status 
                FROM bookings 
                JOIN passengers ON bookings.passenger_id = passengers.id
                JOIN buses ON bookings.bus_id = buses.id
                WHERE bookings.company_id = (SELECT company_id FROM admins WHERE id = ?)";

$stmtBookings = $conn->prepare($sqlBookings);
$stmtBookings->bind_param("i", $admin_id);
$stmtBookings->execute();
$resultBookings = $stmtBookings->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Bookings</title>
</head>
<body>
    <h2>Passenger Booking Requests</h2>

    <table border="1">
        <tr>
            <th>Passenger Name</th>
            <th>Bus Number</th>
            <th>Seat Number</th>
            <th>Payment Status</th>
            <th>Action</th>
        </tr>
        <?php while ($booking = $resultBookings->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($booking['first_name']) . ' ' . htmlspecialchars($booking['last_name']) ?></td>
                <td><?= htmlspecialchars($booking['bus_number']) ?></td>
                <td><?= htmlspecialchars($booking['seat_number']) ?></td>
                <td><?= htmlspecialchars($booking['payment_status']) ?></td>
                <td><a href="confirm_booking.php?id=<?= urlencode($booking['id']) ?>">Confirm Booking</a></td>
            </tr>
        <?php } ?>
    </table>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

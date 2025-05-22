<?php
session_start();
include '../config.php';

// Ensure admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch all bookings
$sqlBookings = "SELECT b.id AS booking_id, p.first_name, p.last_name, b.seat_number, bu.bus_number, 
                r.start_location, r.end_location, r.departure_time, r.arrival_time, b.payment_status 
                FROM bookings b 
                JOIN passengers p ON b.passenger_id = p.id
                JOIN buses bu ON b.bus_id = bu.id 
                JOIN routes r ON bu.id = r.bus_id";
$resultBookings = $conn->query($sqlBookings);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Passenger Bookings</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: white; }
        a, button { text-decoration: none; font-weight: bold; padding: 8px 12px; border-radius: 5px; display: inline-block; cursor: pointer; }
        .edit-btn { background-color: #ffc107; color: black; }
        .delete-btn { background-color: #dc3545; color: white; }
        .update-btn { background-color: #28a745; color: white; }
        .edit-btn:hover { background-color: #e0a800; }
        .delete-btn:hover { background-color: #c82333; }
        .update-btn:hover { background-color: #218838; }
    </style>
</head>
<body>
    <h2>🛠 Manage Passenger Bookings</h2>
    <p>View and manage all ticket reservations.</p>

    <table>
        <tr>
            <th>Passenger Name</th>
            <th>Bus Number</th>
            <th>Seat Number</th>
            <th>Start Location</th>
            <th>End Location</th>
            <th>Departure Time</th>
            <th>Arrival Time</th>
            <th>Payment Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($booking = $resultBookings->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($booking['first_name']) . ' ' . htmlspecialchars($booking['last_name']) ?></td>
                <td><?= htmlspecialchars($booking['bus_number']) ?></td>
                <td><?= htmlspecialchars($booking['seat_number']) ?></td>
                <td><?= htmlspecialchars($booking['start_location']) ?></td>
                <td><?= htmlspecialchars($booking['end_location']) ?></td>
                <td><?= htmlspecialchars($booking['departure_time']) ?></td>
                <td><?= htmlspecialchars($booking['arrival_time']) ?></td>
                <td><?= htmlspecialchars($booking['payment_status']) ?></td>
                <td>
                    <a href="edit_booking.php?booking_id=<?= urlencode($booking['booking_id']) ?>" class="edit-btn">Edit</a>
                    <a href="delete_booking.php?booking_id=<?= urlencode($booking['booking_id']) ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                    <a href="update_booking.php?booking_id=<?= urlencode($booking['booking_id']) ?>" class="update-btn">Update</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <br>
    <a href="dashboard.php">Back to Admin Panel</a>
</body>
</html>

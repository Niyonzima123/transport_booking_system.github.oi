<?php
session_start();
include '../config.php';

// Ensure admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch buses, routes, and bookings
$sqlBuses = "SELECT * FROM buses";
$sqlRoutes = "SELECT * FROM routes";
$sqlBookings = "SELECT b.id AS booking_id, p.first_name, p.last_name, b.seat_number, bu.bus_number, 
                r.start_location, r.end_location, r.departure_time, r.arrival_time, b.payment_status 
                FROM bookings b 
                JOIN passengers p ON b.passenger_id = p.id
                JOIN buses bu ON b.bus_id = bu.id 
                JOIN routes r ON bu.id = r.bus_id";

$resultBuses = $conn->query($sqlBuses);
$resultRoutes = $conn->query($sqlRoutes);
$resultBookings = $conn->query($sqlBookings);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - Transport Management</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        h2 { color: #007BFF; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: white; }
        a, button { text-decoration: none; font-weight: bold; padding: 8px 12px; border-radius: 5px; display: inline-block; cursor: pointer; }
        .action-btn { background-color: #28a745; color: white; }
        .action-btn:hover { background-color: #218838; }
        .logout-btn { background-color: #dc3545; color: white; }
        .logout-btn:hover { background-color: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🚏 Admin Dashboard - Transport Management</h2>
        <p>Manage buses, routes, and passenger bookings efficiently.</p>

        <h3>🚌 Available Buses</h3>
        <table>
            <tr>
                <th>Bus Number</th>
                <th>Total Seats</th>
                <th>Status</th>
            </tr>
            <?php while ($bus = $resultBuses->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($bus['bus_number']) ?></td>
                    <td><?= htmlspecialchars($bus['total_seats']) ?></td>
                    <td><?= htmlspecialchars($bus['status']) ?></td>
                </tr>
            <?php } ?>
        </table>

        <h3>🗺 Available Routes</h3>
        <table>
            <tr>
                <th>Start Location</th>
                <th>End Location</th>
                <th>Departure Time</th>
                <th>Arrival Time</th>
            </tr>
            <?php while ($route = $resultRoutes->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($route['start_location']) ?></td>
                    <td><?= htmlspecialchars($route['end_location']) ?></td>
                    <td><?= htmlspecialchars($route['departure_time']) ?></td>
                    <td><?= htmlspecialchars($route['arrival_time']) ?></td>
                </tr>
            <?php } ?>
        </table>

        <h3>📌 Booking Requests</h3>
        <table>
            <tr>
                <th>Passenger Name</th>
                <th>Bus Number</th>
                <th>Seat Number</th>
                <th>Payment Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($booking = $resultBookings->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($booking['first_name']) . ' ' . htmlspecialchars($booking['last_name']) ?></td>
                    <td><?= htmlspecialchars($booking['bus_number']) ?></td>
                    <td><?= htmlspecialchars($booking['seat_number']) ?></td>
                    <td><?= htmlspecialchars($booking['payment_status']) ?></td>
                    <td>
                        <a href="edit_booking.php?booking_id=<?= urlencode($booking['booking_id']) ?>" class="action-btn">Edit</a>
                        <a href="delete_booking.php?booking_id=<?= urlencode($booking['booking_id']) ?>" class="action-btn">Delete</a>
                        <a href="update_booking.php?booking_id=<?= urlencode($booking['booking_id']) ?>" class="action-btn">Update</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <br>
        <a href="add_bus.php" class="action-btn">Add New Bus</a>
        <a href="add_route.php" class="action-btn">Add New Route</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>

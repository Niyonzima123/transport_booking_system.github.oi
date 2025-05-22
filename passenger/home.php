<?php
session_start();
include '../config.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../passenger/login.php");
    exit();
}

$passenger_id = $_SESSION["user_id"];

// Fetch available transport companies
$sqlCompanies = "SELECT * FROM companies";
$resultCompanies = $conn->query($sqlCompanies);

// Fetch passenger's bookings
$sqlBookings = "SELECT b.id AS booking_id, b.seat_number, b.payment_status, r.start_location, r.end_location, 
                r.departure_time, r.arrival_time, bu.bus_number 
                FROM bookings b 
                JOIN buses bu ON b.bus_id = bu.id 
                JOIN routes r ON bu.id = r.bus_id 
                WHERE b.passenger_id = ?";
$stmtBookings = $conn->prepare($sqlBookings);
$stmtBookings->bind_param("i", $passenger_id);
$stmtBookings->execute();
$resultBookings = $stmtBookings->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Passenger Dashboard - Transport Booking</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; text-align: center; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        h2 { color: #007BFF; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: white; }
        a, button { text-decoration: none; font-weight: bold; padding: 8px 12px; border-radius: 5px; display: inline-block; cursor: pointer; }
        .book-btn { background-color: #28a745; color: white; }
        .edit-btn { background-color: #ffc107; color: black; }
        .delete-btn { background-color: #dc3545; color: white; }
        .logout-btn { background-color: #dc3545; color: white; }
        .help-btn { background-color: #007BFF; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🚏 Passenger Dashboard - Book & Manage Your Transport</h2>
        <p>Select a transport company to view available routes and book tickets.</p>

        <h3>📌 Available Transport Companies</h3>
        <table>
            <tr>
                <th>Company Name</th>
                <th>Description</th>
                <th>Book Ticket</th>
            </tr>
            <?php while ($company = $resultCompanies->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($company['company_name']) ?></td>
                    <td><?= htmlspecialchars($company['company_description']) ?></td>
                    <td><a href="view_company.php?company_id=<?= urlencode($company['id']) ?>" class="book-btn">View & Book</a></td>
                </tr>
            <?php } ?>
        </table>

        <h3>🚀 Your Booked Tickets</h3>
        <table>
            <tr>
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
                    <td><?= htmlspecialchars($booking['bus_number']) ?></td>
                    <td><?= htmlspecialchars($booking['seat_number']) ?></td>
                    <td><?= htmlspecialchars($booking['start_location']) ?></td>
                    <td><?= htmlspecialchars($booking['end_location']) ?></td>
                    <td><?= htmlspecialchars($booking['departure_time']) ?></td>
                    <td><?= htmlspecialchars($booking['arrival_time']) ?></td>
                    <td><?= htmlspecialchars($booking['payment_status']) ?></td>
                    <td>
                        <a href="edit_booking.php?booking_id=<?= urlencode($booking['booking_id']) ?>" class="edit-btn">Edit</a>
                        <a href="delete_booking.php?booking_id=<?= urlencode($booking['booking_id']) ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this ticket?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <h3>🔗 Quick Links</h3>
        <p>
            <a href="book_ticket.php" class="book-btn">📌 Book a Ticket</a> |
            <a href="payment_status.php" class="book-btn">💰 Track Payments</a> |
            <a href="help_center.php" class="help-btn">❓ Help Center</a>
        </p>

        <br>
        <a href="../logout.php" class="logout-btn">Logout</a>
        <a href="../passenger/ragister.php">🏠 Register</a>
        <a href="../admin_panel/ragister.php">🏠 Register</a
    </div>
</body>
</html>

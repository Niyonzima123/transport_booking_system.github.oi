<?php
session_start();
include '../config.php';

// Validate booking selection
$booking_id = $_GET['booking_id'] ?? null;

if (!$booking_id || !is_numeric($booking_id)) {
    header("Location: home.php?error=BookingNotFound");
    exit();
}

$booking_id = intval($booking_id);
$passenger_id = $_SESSION["user_id"] ?? null;

// Fetch booking details
$sqlBooking = "SELECT b.id, b.seat_number, b.payment_status, p.first_name, p.last_name, bu.bus_number
            FROM bookings b
            JOIN passengers p ON b.passenger_id = p.id
            JOIN buses bu ON b.bus_id = bu.id
        WHERE b.id = ?";

$stmtBooking = $conn->prepare($sqlBooking);
$stmtBooking->bind_param("i", $booking_id);
$stmtBooking->execute();
$resultBooking = $stmtBooking->get_result();

if ($resultBooking->num_rows == 0) {
    header("Location: home.php?error=InvalidBooking");
    exit();
}

$booking = $resultBooking->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Booking Confirmation</title>
</head>
<body>
    <h2>🎉 Booking Confirmed!</h2>
    <p><strong>Passenger:</strong> <?= htmlspecialchars($booking['first_name']) . ' ' . htmlspecialchars($booking['last_name']) ?></p>
    <p><strong>Bus Number:</strong> <?= htmlspecialchars($booking['bus_number']) ?></p>
    <p><strong>Seat Number:</strong> <?= htmlspecialchars($booking['seat_number']) ?></p>
    <p><strong>Payment Status:</strong> <?= htmlspecialchars($booking['payment_status']) ?></p>

    <h3>Thank you for booking! 🚀 Have a great trip!</h3>

    <br>
    <a href="home.php">Back to Home</a>
</body>
</html>

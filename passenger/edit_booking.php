<?php
session_start();
include '../config.php';

$booking_id = $_GET['booking_id'] ?? null;

if (!$booking_id || !is_numeric($booking_id)) {
    header("Location: dashboard.php?error=InvalidBooking");
    exit();
}

$booking_id = intval($booking_id);

// Fetch booking details
$sqlBooking = "SELECT seat_number FROM bookings WHERE id=?";
$stmtBooking = $conn->prepare($sqlBooking);
$stmtBooking->bind_param("i", $booking_id);
$stmtBooking->execute();
$resultBooking = $stmtBooking->get_result();

if ($resultBooking->num_rows == 0) {
    header("Location: dashboard.php?error=BookingNotFound");
    exit();
}

$booking = $resultBooking->fetch_assoc();

// Update seat number
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_seat_number = $_POST["seat_number"];

    // Check seat availability
    $sqlCheck = "SELECT id FROM bookings WHERE seat_number=? AND id<>?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("si", $new_seat_number, $booking_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        die("Error: This seat is already booked.");
    }

    // Update booking
    $sqlUpdate = "UPDATE bookings SET seat_number=? WHERE id=?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("si", $new_seat_number, $booking_id);

    if ($stmtUpdate->execute()) {
        header("Location: dashboard.php?success=BookingUpdated");
        exit();
    } else {
        die("Error: Unable to update booking.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Booking</title>
</head>
<body>
    <h2>Edit Your Booking</h2>
    <form method="POST">
        <label>New Seat Number:</label>
        <input type="text" name="seat_number" required value="<?= htmlspecialchars($booking['seat_number']) ?>"><br>
        <button type="submit">Update Booking</button>
    </form>
</body>
</html>

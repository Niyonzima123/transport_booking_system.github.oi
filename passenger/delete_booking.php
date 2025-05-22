<?php
session_start();
include '../config.php';

$booking_id = $_GET['booking_id'] ?? null;

if (!$booking_id || !is_numeric($booking_id)) {
    header("Location: dashboard.php?error=InvalidBooking");
    exit();
}

$booking_id = intval($booking_id);

// Delete booking
$sqlDelete = "DELETE FROM bookings WHERE id=?";
$stmtDelete = $conn->prepare($sqlDelete);
$stmtDelete->bind_param("i", $booking_id);

if ($stmtDelete->execute()) {
    header("Location: dashboard.php?success=BookingDeleted");
    exit();
} else {
    die("Error: Unable to delete booking.");
}
?>

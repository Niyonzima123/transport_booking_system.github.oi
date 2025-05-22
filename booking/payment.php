<?php
session_start();
include '../config.php';

// Validate booking selection
$booking_id = $_GET['booking_id'] ?? null;
$bus_id = $_GET['bus_id'] ?? null;

if (!$booking_id || !is_numeric($booking_id) || !$bus_id || !is_numeric($bus_id)) {
    header("Location: reserve.php?error=BookingOrBusNotSelected");
    exit();
}

$booking_id = intval($booking_id);
$bus_id = intval($bus_id);
$passenger_id = $_SESSION["user_id"];

// Fetch booking details
$sqlBooking = "SELECT * FROM bookings WHERE id=?";
$stmtBooking = $conn->prepare($sqlBooking);
$stmtBooking->bind_param("i", $booking_id);
$stmtBooking->execute();
$resultBooking = $stmtBooking->get_result();

if ($resultBooking->num_rows == 0) {
    header("Location: reserve.php?error=InvalidBooking");
    exit();
}

$booking = $resultBooking->fetch_assoc();

// Handle payment processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = $_POST["payment_method"];
    $transaction_id = $_POST["transaction_id"];
    $amount = 50;

    // Insert payment record
    $sqlPayment = "INSERT INTO payments (booking_id, amount, transaction_id, payment_method, status) 
                VALUES (?, ?, ?, ?, 'pending')";
    $stmtPayment = $conn->prepare($sqlPayment);
    $stmtPayment->bind_param("idss", $booking_id, $amount, $transaction_id, $payment_method);

    if ($stmtPayment->execute()) {
        header("Location: confirmation.php?booking_id=" . urlencode($booking_id));
        exit();
    } else {
        die("Error: Unable to process payment. " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Complete Payment</title>
</head>
<body>
    <h2>Payment for Seat Reservation</h2>
    <p>Booking ID: <?= htmlspecialchars($booking['id']) ?></p>
    <p>Bus ID: <?= htmlspecialchars($bus_id) ?></p>
    <p>Amount: $50</p>

    <form method="POST">
        <label>Select Payment Method:</label>
        <select name="payment_method" required>
            <option value="mtn_momo">MTN MoMo</option>
            <option value="airtel_money">Airtel Money</option>
        </select><br>
        <label>Transaction ID:</label>
        <input type="text" name="transaction_id" required><br>
        <button type="submit">Confirm Payment</button>
    </form>
</body>
</html>

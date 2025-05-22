<?php
session_start();
include '../config.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$passenger_id = $_SESSION["user_id"];

// Fetch passenger's payments
$sqlPayments = "SELECT p.transaction_id, p.amount, p.payment_method, p.status, b.seat_number, bu.bus_number, 
                r.start_location, r.end_location, r.departure_time, r.arrival_time 
                FROM payments p 
                JOIN bookings b ON p.booking_id = b.id 
                JOIN buses bu ON b.bus_id = bu.id 
                JOIN routes r ON bu.id = r.bus_id 
                WHERE b.passenger_id = ?";
$stmtPayments = $conn->prepare($sqlPayments);
$stmtPayments->bind_param("i", $passenger_id);
$stmtPayments->execute();
$resultPayments = $stmtPayments->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment Status</title>
</head>
<body>
    <h2>💰 Payment Status</h2>
    <p>View and track your payments for booked tickets.</p>

    <table border="1">
        <tr>
            <th>Transaction ID</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Seat Number</th>
            <th>Bus Number</th>
            <th>Start Location</th>
            <th>End Location</th>
            <th>Departure Time</th>
            <th>Arrival Time</th>
        </tr>
        <?php while ($payment = $resultPayments->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($payment['transaction_id']) ?></td>
                <td>$<?= htmlspecialchars($payment['amount']) ?></td>
                <td><?= htmlspecialchars($payment['payment_method']) ?></td>
                <td><?= htmlspecialchars($payment['status']) ?></td>
                <td><?= htmlspecialchars($payment['seat_number']) ?></td>
                <td><?= htmlspecialchars($payment['bus_number']) ?></td>
                <td><?= htmlspecialchars($payment['start_location']) ?></td>
                <td><?= htmlspecialchars($payment['end_location']) ?></td>
                <td><?= htmlspecialchars($payment['departure_time']) ?></td>
                <td><?= htmlspecialchars($payment['arrival_time']) ?></td>
            </tr>
        <?php } ?>
    </table>

    <br>
    <a href="home.php">Back to Dashboard</a>
</body>
</html>

<?php
session_start();
include '../config.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../passenger/login.php");
    exit();
}

// Validate bus selection
if (!isset($_GET['bus_id']) || empty($_GET['bus_id'])) {
    // Redirect back to transport selection if no valid bus ID is selected
    header("Location: view_company.php?error=BusNotSelected");
    exit();
}

$bus_id = intval($_GET['bus_id']);
$passenger_id = $_SESSION["user_id"];

// Fetch bus details
$sqlBus = "SELECT * FROM buses WHERE id=?";
$stmtBus = $conn->prepare($sqlBus);
$stmtBus->bind_param("i", $bus_id);
$stmtBus->execute();
$resultBus = $stmtBus->get_result();

if ($resultBus->num_rows == 0) {
    die("Error: Bus not found.");
}

$bus = $resultBus->fetch_assoc();

// Handle seat reservation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seat_number = $_POST["seat_number"];

    // Check if the seat is already booked
    $sqlCheck = "SELECT id FROM bookings WHERE bus_id=? AND seat_number=?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("is", $bus_id, $seat_number);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        die("Error: This seat is already booked.");
    }

    // Insert booking record
    $sqlBooking = "INSERT INTO bookings (passenger_id, company_id, bus_id, seat_number, payment_status) 
                VALUES (?, ?, ?, ?, 'pending')";
    $stmtBooking = $conn->prepare($sqlBooking);
    $stmtBooking->bind_param("iiis", $passenger_id, $bus["company_id"], $bus_id, $seat_number);

    if ($stmtBooking->execute()) {
        header("Location: payment.php?booking_id=" . urlencode($stmtBooking->insert_id) . "&bus_id=" . urlencode($bus_id));
        exit();
    } else {
        die("Error: Unable to process booking. " . $conn->error);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reserve Seat</title>
</head>
<body>
    <h2>Reserve Your Seat</h2>
    <p>Bus: <?= htmlspecialchars($bus['bus_number']) ?></p>
<form method="POST">
    <label>Select Seat:</label>
    <select name="seat_number" required>
        <?php 
        while ($seat = $resultAvailableSeats->fetch_assoc()) {
            if (!in_array($seat["seat_number"], $bookedSeats)) { ?>
                <option value="<?= htmlspecialchars($seat['seat_number']) ?>">
                    <?= htmlspecialchars($seat['seat_number']) ?>
                </option>
            <?php } 
        } ?>
    </select>
    <button type="submit">Confirm Booking</button>
</form>
</body>
</html>

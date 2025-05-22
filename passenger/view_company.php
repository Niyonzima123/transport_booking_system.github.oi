<?php
session_start();
include '../config.php';

// Validate company selection
$company_id = $_GET['company_id'] ?? null;

if (!$company_id || !is_numeric($company_id)) {
    header("Location: home.php");
    exit();
}

$company_id = intval($company_id);

// Fetch company details
$sqlCompany = "SELECT * FROM companies WHERE id = ?";
$stmtCompany = $conn->prepare($sqlCompany);
$stmtCompany->bind_param("i", $company_id);
$stmtCompany->execute();
$resultCompany = $stmtCompany->get_result();

if ($resultCompany->num_rows == 0) {
    header("Location: home.php?error=CompanyNotFound");
    exit();
}

$company = $resultCompany->fetch_assoc();

// Fetch available buses and routes
$sqlRoutes = "SELECT buses.id AS bus_id, buses.bus_number, buses.total_seats, routes.start_location, 
            routes.end_location, routes.departure_time, routes.arrival_time, routes.id AS route_id 
            FROM buses JOIN routes ON buses.id = routes.bus_id WHERE buses.company_id = ?";
$stmtRoutes = $conn->prepare($sqlRoutes);
$stmtRoutes->bind_param("i", $company_id);
$stmtRoutes->execute();
$resultRoutes = $stmtRoutes->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= htmlspecialchars($company['company_name']) ?> - Available Transport</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: white; }
        a { color: #007BFF; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Transport by <?= htmlspecialchars($company['company_name']) ?></h2>
    <p><?= htmlspecialchars($company['company_description']) ?></p>

    <h3>Available Buses & Routes</h3>
    <table>
        <tr>
            <th>Bus Number</th>
            <th>Total Seats</th>
            <th>Start Location</th>
            <th>End Location</th>
            <th>Departure Time</th>
            <th>Arrival Time</th>
            <th>Booked Seats</th>
            <th>Reserve Seat</th>
        </tr>
        <?php while ($route = $resultRoutes->fetch_assoc()) { 
            $bus_id = $route['bus_id'];

            // Fetch booked seats safely
            $sqlBookedSeats = "SELECT seat_number FROM bookings WHERE bus_id=?";
            $stmtBookedSeats = $conn->prepare($sqlBookedSeats);
            $stmtBookedSeats->bind_param("i", $bus_id);
            $stmtBookedSeats->execute();
            $resultBookedSeats = $stmtBookedSeats->get_result();
            $bookedSeats = [];
            while ($row = $resultBookedSeats->fetch_assoc()) {
                $bookedSeats[] = $row["seat_number"];
            }
        ?>
            <tr>
                <td><?= htmlspecialchars($route['bus_number']) ?></td>
                <td><?= htmlspecialchars($route['total_seats']) ?></td>
                <td><?= htmlspecialchars($route['start_location']) ?></td>
                <td><?= htmlspecialchars($route['end_location']) ?></td>
                <td><?= htmlspecialchars($route['departure_time']) ?></td>
                <td><?= htmlspecialchars($route['arrival_time']) ?></td>
                <td><?= implode(", ", $bookedSeats) ?: "None" ?></td>
                <td><a href="reserve.php?bus_id=<?= urlencode($bus_id) ?>">Book Ticket</a></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>


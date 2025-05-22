<?php
session_start();
include '../config.php';

// Ensure admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION["admin_id"];

// Fetch company ID linked to the admin
$sqlCompany = "SELECT company_id FROM admin WHERE id = ?";
$stmtCompany = $conn->prepare($sqlCompany);
$stmtCompany->bind_param("i", $admin_id);
$stmtCompany->execute();
$resultCompany = $stmtCompany->get_result();
$company = $resultCompany->fetch_assoc();
$company_id = $company["company_id"];

// Fetch available buses under the company
$sqlBuses = "SELECT id, bus_number FROM buses WHERE company_id = ?";
$stmtBuses = $conn->prepare($sqlBuses);
$stmtBuses->bind_param("i", $company_id);
$stmtBuses->execute();
$resultBuses = $stmtBuses->get_result();

// Handle route addition
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bus_id = $_POST["bus_id"];
    $start_location = $_POST["start_location"];
    $end_location = $_POST["end_location"];
    $departure_time = $_POST["departure_time"];
    $arrival_time = $_POST["arrival_time"];

    // Insert route into database
    $sqlRoute = "INSERT INTO routes (company_id, bus_id, start_location, end_location, departure_time, arrival_time) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtRoute = $conn->prepare($sqlRoute);
    $stmtRoute->bind_param("iissss", $company_id, $bus_id, $start_location, $end_location, $departure_time, $arrival_time);

    if ($stmtRoute->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        die("Error adding route: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add New Route</title>
</head>
<body>
    <h2>Add a New Transport Route</h2>
    <form method="POST">
        <label>Select Bus:</label>
        <select name="bus_id" required>
            <?php while ($bus = $resultBuses->fetch_assoc()) { ?>
                <option value="<?= $bus['id'] ?>"><?= htmlspecialchars($bus['bus_number']) ?></option>
            <?php } ?>
        </select><br>
        <label>Start Location:</label>
        <input type="text" name="start_location" required><br>
        <label>End Location:</label>
        <input type="text" name="end_location" required><br>
        <label>Departure Time:</label>
        <input type="time" name="departure_time" required><br>
        <label>Arrival Time:</label>
        <input type="time" name="arrival_time" required><br>
        <button type="submit">Add Route</button>
    </form>
</body>
</html>

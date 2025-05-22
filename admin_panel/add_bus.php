<?php
session_start();
include '../config.php';

// Ensure admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION["admin_id"];

// Handle bus addition
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bus_number = $_POST["bus_number"];
    $total_seats = $_POST["total_seats"];
    $status = $_POST["status"];

    // Get company ID linked to the admin
    $sqlCompany = "SELECT company_id FROM admin WHERE id = ?";
    $stmtCompany = $conn->prepare($sqlCompany);
    $stmtCompany->bind_param("i", $admin_id);
    $stmtCompany->execute();
    $resultCompany = $stmtCompany->get_result();
    $company = $resultCompany->fetch_assoc();
    $company_id = $company["company_id"];

    // Insert bus into the database
    $sqlBus = "INSERT INTO buses (company_id, bus_number, total_seats, status) VALUES (?, ?, ?, ?)";
    $stmtBus = $conn->prepare($sqlBus);
    $stmtBus->bind_param("isss", $company_id, $bus_number, $total_seats, $status);

    if ($stmtBus->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        die("Error adding bus: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add New Bus</title>
</head>
<body>
    <h2>Add a New Bus</h2>
    <form method="POST">
        <label>Bus Number:</label>
        <input type="text" name="bus_number" required><br>
        <label>Total Seats:</label>
        <input type="number" name="total_seats" required><br>
        <label>Status:</label>
        <select name="status" required>
            <option value="active">Active</option>
            <option value="maintenance">Maintenance</option>
            <option value="inactive">Inactive</option>
        </select><br>
        <button type="submit">Add Bus</button>
    </form>
</body>
</html>

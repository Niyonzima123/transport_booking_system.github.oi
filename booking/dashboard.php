<?php
session_start();
include '../config.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../passenger/login.php");
    exit();
}

// Fetch transport companies
$sqlCompanies = "SELECT * FROM companies";
$resultCompanies = $conn->query($sqlCompanies);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Passenger Booking Dashboard</title>
</head>
<body>
    <h2>Available Transport Companies for Booking</h2>

    <table border="1">
        <tr>
            <th>Company Name</th>
            <th>Description</th>
            <th>Book a Seat</th>
        </tr>
        <?php while ($company = $resultCompanies->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($company['company_name']) ?></td>
                <td><?= htmlspecialchars($company['company_description']) ?></td>
                <td><a href="view_company.php?company_id=<?= urlencode($company['id']) ?>">View Routes & Book</a></td>
            </tr>
        <?php } ?>
    </table>

    <br>
    <a href="../logout.php">Logout</a>
</body>
</html>

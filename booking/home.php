<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Transport Booking System - Home</title>
</head>
<body>
    <h2>Welcome to the Transport Booking System</h2>
    <p>Select your transport company to book a seat.</p>

    <table border="1">
        <tr>
            <th>Company Name</th>
            <th>Description</th>
            <th>Book a Seat</th>
        </tr>
        <?php
        include '../config.php';
        $sqlCompanies = "SELECT * FROM companies";
        $resultCompanies = $conn->query($sqlCompanies);
        while ($company = $resultCompanies->fetch_assoc()) {
        ?>
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

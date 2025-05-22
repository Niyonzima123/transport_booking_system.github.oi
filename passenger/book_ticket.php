<?php
session_start();
include '../config.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$passenger_id = $_SESSION["user_id"];

// Fetch available transport companies
$sqlCompanies = "SELECT * FROM companies";
$resultCompanies = $conn->query($sqlCompanies);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Book a Ticket</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        h2 { color: #007BFF; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: white; }
        a { text-decoration: none; font-weight: bold; padding: 8px 12px; border-radius: 5px; display: inline-block; }
        .book-btn { background-color: #28a745; color: white; }
        .book-btn:hover { background-color: #218838; }
        .back-btn { background-color: #007BFF; color: white; }
        .back-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🚏 Book a Ticket</h2>
        <p>Select a transport company to view routes and book your seat.</p>

        <h3>Available Transport Companies</h3>
        <table>
            <tr>
                <th>Company Name</th>
                <th>Description</th>
                <th>Book Seat</th>
            </tr>
            <?php while ($company = $resultCompanies->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($company['company_name']) ?></td>
                    <td><?= htmlspecialchars($company['company_description']) ?></td>
                    <td><a href="view_company.php?company_id=<?= urlencode($company['id']) ?>" class="book-btn">View Routes & Book</a></td>
                </tr>
            <?php } ?>
        </table>

        <br>
        <a href="home.php" class="back-btn">🔙 Back to Dashboard</a>
    </div>
</body>
</html>

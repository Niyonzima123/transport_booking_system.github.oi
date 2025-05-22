<?php
session_start();
include '../config.php';

// Validate user search input
$search_query = $_GET["query"] ?? '';

if (empty($search_query)) {
    header("Location: home.php?error=NoSearchQuery");
    exit();
}

// Search for transport options
$sqlSearch = "SELECT buses.bus_number, routes.start_location, routes.end_location, 
            routes.departure_time, routes.arrival_time 
            FROM buses 
            JOIN routes ON buses.id = routes.bus_id 
        WHERE routes.start_location LIKE ? OR routes.end_location LIKE ?";

$stmtSearch = $conn->prepare($sqlSearch);
$searchTerm = "%" . $search_query . "%";
$stmtSearch->bind_param("ss", $searchTerm, $searchTerm);
$stmtSearch->execute();
$resultSearch = $stmtSearch->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Results</title>
</head>
<body>
    <h2>Search Results for "<?= htmlspecialchars($search_query) ?>"</h2>

    <table border="1">
        <tr>
            <th>Bus Number</th>
            <th>Start Location</th>
            <th>End Location</th>
            <th>Departure Time</th>
            <th>Arrival Time</th>
            <th>Reserve Seat</th>
        </tr>
        <?php while ($route = $resultSearch->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($route['bus_number']) ?></td>
                <td><?= htmlspecialchars($route['start_location']) ?></td>
                <td><?= htmlspecialchars($route['end_location']) ?></td>
                <td><?= htmlspecialchars($route['departure_time']) ?></td>
                <td><?= htmlspecialchars($route['arrival_time']) ?></td>
                <td><a href="reserve.php?bus_id=<?= urlencode($route['bus_number']) ?>">Book Ticket</a></td>
            </tr>
        <?php } ?>
    </table>

    <br>
    <a href="home.php">Back to Home</a>
</body>
</html>

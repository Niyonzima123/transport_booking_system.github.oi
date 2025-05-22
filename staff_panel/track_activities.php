<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

$staff_id = $_SESSION["staff_id"];

// Handle activity logging
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["activity_description"])) {
    $activity_description = $_POST["activity_description"];
    $sqlInsert = "INSERT INTO daily_activities (staff_id, activity_description) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("is", $staff_id, $activity_description);
    $stmtInsert->execute();
}

// Handle activity deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_activity"])) {
    $delete_id = intval($_POST["delete_activity"]);
    $sqlDelete = "DELETE FROM daily_activities WHERE id=?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $delete_id);
    $stmtDelete->execute();
}

// Fetch staff activities
$sqlActivities = "SELECT * FROM daily_activities WHERE staff_id=? ORDER BY activity_date DESC";
$stmtActivities = $conn->prepare($sqlActivities);
$stmtActivities->bind_param("i", $staff_id);
$stmtActivities->execute();
$resultActivities = $stmtActivities->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Track Activities - Transport Booking System</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; text-align: center; padding: 30px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: white; }
        button, input[type="text"] { padding: 10px; border-radius: 5px; border: 1px solid #ddd; width: 100%; }
        .action-btn { background-color: #dc3545; color: white; padding: 10px; border: none; cursor: pointer; font-weight: bold; }
        .edit-btn { background-color: #007BFF; }
        .delete-btn { background-color: #dc3545; }
        .print-btn { background-color: #28a745; }
        .download-btn { background-color: #ffc107; }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>📅 Track Staff Activities</h2>
        <p>Log, update, and manage activities below.</p>

        <form method="POST">
            <input type="text" name="activity_description" placeholder="Describe your activity..." required>
            <button type="submit">✅ Log Activity</button>
        </form>

        <form method="POST">
            <button type="submit" name="download_csv" class="download-btn">📥 Download Activities (CSV)</button>
        </form>
        <button onclick="printPage()" class="print-btn">🖨️ Print Activities</button>

        <h3>🚀 Recent Activities</h3>
        <table>
            <tr><th>Activity ID</th><th>Description</th><th>Date</th><th>Actions</th></tr>
            <?php while ($row = $resultActivities->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><input type="text" name="edit_description" value="<?= htmlspecialchars($row["activity_description"]) ?>"></td>
                    <td><?= $row["activity_date"] ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="delete_activity" value="<?= $row["id"] ?>">
                            <button type="submit" class="delete-btn">❌ Delete</button>
                        </form>
                        <button type="submit" class="edit-btn">✏️ Edit</button>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <br>
        <a href="dashboard.php">🔙 Back to Dashboard</a>
        <br><br>
        <a href="../logout.php" class="action-btn">Logout</a>
    </div>
</body>
</html>

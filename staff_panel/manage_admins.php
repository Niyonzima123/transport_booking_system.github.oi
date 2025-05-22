<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch admins (fixed table reference)
$sqlAdmins = "SELECT id, first_name, last_name, phone_number, national_id, email, company_id, created_at, username FROM admin ORDER BY created_at DESC";
$resultAdmins = $conn->query($sqlAdmins);




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Admins - Transport Booking System</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; text-align: center; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: white; }
        .action-btn { padding: 10px; border-radius: 5px; font-weight: bold; text-decoration: none; }
        .edit-btn { background-color: #ffc107; }
        .delete-btn { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🛡️ Manage Admins</h2>

       <table>
    <tr>
        <th>Admin ID</th><th>First Name</th><th>Last Name</th><th>Phone</th><th>National ID</th><th>Email</th><th>Company ID</th><th>Created At</th><th>Username</th><th>Actions</th>
    </tr>
    
    <?php while ($row = $resultAdmins->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row["id"]) ?></td>
            <td><?= htmlspecialchars($row["first_name"]) ?></td>
            <td><?= htmlspecialchars($row["last_name"]) ?></td>
            <td><?= htmlspecialchars($row["phone_number"]) ?></td>
            <td><?= htmlspecialchars($row["national_id"]) ?></td>
            <td><?= htmlspecialchars($row["email"]) ?></td>
            <td><?= htmlspecialchars($row["company_id"]) ?></td>
            <td><?= htmlspecialchars($row["created_at"]) ?></td>
            <td><?= htmlspecialchars($row["username"]) ?></td>
            <td>
                <form method="POST" action="edit_admin.php">
                    <input type="hidden" name="admin_id" value="<?= htmlspecialchars($row["id"]) ?>">
                    <button type="submit" class="action-btn edit-btn">✏️ Edit</button>
                </form>
                <form method="POST" action="delete_admin.php">
                    <input type="hidden" name="admin_id" value="<?= htmlspecialchars($row["id"]) ?>">
                    <button type="submit" class="action-btn delete-btn">❌ Delete</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>


        <br>
        <a href="dashboard.php">🔙 Back to Dashboard</a>
    </div>
</body>
</html>

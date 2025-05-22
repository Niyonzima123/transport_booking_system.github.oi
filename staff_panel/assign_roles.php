<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch all users
$sqlUsers = "SELECT * FROM admin ORDER BY created_at DESC";
$resultUsers = $conn->query($sqlUsers);

// Handle role assignment
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["admin_id"], $_POST["role"])) {
    $admin_id = intval($_POST["admin_id"]);
    $role = $_POST["role"];

    $sqlUpdate = "UPDATE admin SET role=? WHERE id=?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("si", $role, $admin_id);

    if ($stmtUpdate->execute()) {
        header("Location: assign_roles.php?success=RoleAssigned");
        exit();
    } else {
        echo "❌ Error: Could not assign role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Assign Roles - Transport Booking System</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 30px; background-color: #f5f5f5; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: white; }
        button, select { padding: 10px; border-radius: 5px; border: 1px solid #ddd; width: 100%; }
        .action-btn { background-color: #28a745; color: white; padding: 10px; border: none; cursor: pointer; font-weight: bold; }
        .logout-btn { background-color: #dc3545; color: white; padding: 12px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔑 Assign Roles</h2>
        <p>Assign administrative roles to registered staff members.</p>

        <h3>🚀 Admin List</h3>
        <table>
            <tr><th>ID</th><th>Full Name</th><th>Email</th><th>Current Role</th><th>Actions</th></tr>
            <?php while ($row = $resultUsers->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= htmlspecialchars($row["first_name"]) . ' ' . htmlspecialchars($row["last_name"]) ?></td>
                    <td><?= htmlspecialchars($row["email"]) ?></td>
                    <td><?= htmlspecialchars($row["role"] ?? "Not Assigned") ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="admin_id" value="<?= $row["id"] ?>">
                            <select name="role">
                                <option value="Administrator">Administrator</option>
                                <option value="Manager">Manager</option>
                                <option value="Staff">Staff</option>
                            </select>
                            <button type="submit" class="action-btn">✅ Assign Role</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <br>
        <a href="dashboard.php">🔙 Back to Dashboard</a>
        <br><br>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>

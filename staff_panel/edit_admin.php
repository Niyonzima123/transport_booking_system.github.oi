<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST["admin_id"])) {
    die("❌ Error: Admin ID missing.");
}

$admin_id = intval($_POST["admin_id"]);

// Fetch admin details
$sql = "SELECT * FROM admin WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Error: Admin not found.");
}

$row = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"], $_POST["email"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];

    $sqlUpdate = "UPDATE admin SET name=?, email=? WHERE id=?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ssi", $name, $email, $admin_id);

    if ($stmtUpdate->execute()) {
        header("Location: manage_admins.php?success=AdminUpdated");
        exit();
    } else {
        echo "❌ Error: Could not update admin.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Admin - Transport Booking System</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
        .container { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <h2>✏️ Edit Admin</h2>

        <form method="POST">
            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>
            <button type="submit">✅ Save Changes</button>
        </form>

        <br>
        <a href="manage_admins.php">🔙 Back to Manage Admins</a>
    </div>
</body>
</html>

<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

// Validate user ID
if (!isset($_GET["user_id"])) {
    die("❌ Error: User ID missing.");
}

$user_id = intval($_GET["user_id"]);

// Fetch user details
$sql = "SELECT * FROM passengers WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Error: User not found.");
}

$row = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];

    $sqlUpdate = "UPDATE passengers SET name=?, email=? WHERE id=?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ssi", $name, $email, $user_id);

    if ($stmtUpdate->execute()) {
        header("Location: manage_users.php?success=UserUpdated");
        exit();
    } else {
        echo "❌ Error: Could not update user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit User - Transport Booking System</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
        button { background-color: #007BFF; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>✏️ Edit User</h2>
        <p>Update passenger details below.</p>

        <form method="POST">
            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>
            <button type="submit">✅ Save Changes</button>
        </form>

        <br>
        <a href="manage_users.php">🔙 Back to Manage Users</a>
    </div>
</body>
</html>

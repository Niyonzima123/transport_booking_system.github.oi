<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch settings data
$sqlSettings = "SELECT * FROM system_settings LIMIT 1";
$resultSettings = $conn->query($sqlSettings);
$settings = $resultSettings->fetch_assoc() ?? ["site_name" => "Default System Name", "contact_email" => "admin@example.com"];

// Handle settings update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $site_name = $_POST["site_name"];
    $contact_email = $_POST["contact_email"];

    $sqlUpdate = "UPDATE system_settings SET site_name=?, contact_email=? WHERE id=1";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ss", $site_name, $contact_email);

    if ($stmtUpdate->execute()) {
        header("Location: settings.php?success=SettingsUpdated");
        exit();
    } else {
        echo "❌ Error: Could not update settings.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>System Settings - Transport Booking</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; text-align: center; padding: 30px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
        button { background-color: #007BFF; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>⚙️ System Settings</h2>
        <p>Modify system configurations below.</p>

        <form method="POST">
            <input type="text" name="site_name" placeholder="Site Name" value="<?= htmlspecialchars($settings['site_name']) ?>" required>
            <input type="email" name="contact_email" placeholder="Contact Email" value="<?= htmlspecialchars($settings['contact_email']) ?>" required>
            <button type="submit">✅ Save Changes</button>
        </form>

        <br>
        <a href="dashboard.php">🔙 Back to Dashboard</a>
    </div>
</body>
</html>

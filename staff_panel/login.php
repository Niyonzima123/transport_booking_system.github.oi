<?php
session_start();
include '../config.php';

// Predefined staff usernames for validation
$allowedStaffUsernames = ["staff001", "staff002", "staff003", "staff004", "staff005", 
                        "staff006", "staff007", "staff008", "staff009", "staff010"];

$errorMessage = '';

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate staff username
    if (!in_array($username, $allowedStaffUsernames)) {
        $errorMessage = "❌ Error: Invalid staff username. Please contact admin.";
    } else {
        // Query database securely
        $sqlStaff = "SELECT id, password FROM staff WHERE username=?";
        $stmtStaff = $conn->prepare($sqlStaff);
        $stmtStaff->bind_param("s", $username);
        $stmtStaff->execute();
        $resultStaff = $stmtStaff->get_result();

        if ($resultStaff->num_rows > 0) {
            $row = $resultStaff->fetch_assoc();
            
            // Verify encrypted password
            if (password_verify($password, $row["password"])) {
                $_SESSION["staff_id"] = $row["id"];
                header("Location: dashboard.php");
                exit();
            } else {
                $errorMessage = "❌ Error: Incorrect password.";
            }
        } else {
            $errorMessage = "❌ Error: Username not found.";
        }
    }
}
?>

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
        button { background-color: #007BFF; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error-message { color: red; font-weight: bold; }
        a { display: inline-block; margin-top: 15px; font-size: 14px; }
    </style>
    <title>Staff Login - Transport Booking System</title>
    
</head>
<body>
    <h2>👷 Staff Login</h2>

    <?php if (!empty($errorMessage)) { ?>
        <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
    <?php } ?>

    <div>
        <form method="POST">
        <input type="text" name="username" placeholder="Enter staff username" required>
        <input type="password" name="password" placeholder="Enter password" required>
        <button type="submit">Login</button>
    </form>
    </div>

    <br>
    <a href="../login.php">🏠 Login</a>
    <a href="../index.php">🏠 Back to Home</a>
</body>
</html>

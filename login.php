<?php
session_start();
include 'config.php';

$errorMessage = '';

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check for passenger login
    $sqlPassenger = "SELECT id FROM passengers WHERE email=? AND password=?";
    $stmtPassenger = $conn->prepare($sqlPassenger);
    $stmtPassenger->bind_param("ss", $email, $password);
    $stmtPassenger->execute();
    $resultPassenger = $stmtPassenger->get_result();

    if ($resultPassenger->num_rows > 0) {
        $_SESSION["user_id"] = $resultPassenger->fetch_assoc()["id"];
        header("Location: passenger/home.php");
        exit();
    }

    // Check for staff login
    $sqlStaff = "SELECT id FROM staff WHERE email=? AND password=?";
    $stmtStaff = $conn->prepare($sqlStaff);
    $stmtStaff->bind_param("ss", $username, $password);
    $stmtStaff->execute();
    $resultStaff = $stmtStaff->get_result();

    if ($resultStaff->num_rows > 0) {
        $_SESSION["staff_id"] = $resultStaff->fetch_assoc()["id"];
        header("Location: staff_panel/dashboard.php");
        exit();
    }

    // Check for admin login (only staff can register admins)
    $sqlAdmin = "SELECT id FROM admin WHERE email=? AND password=?";
    $stmtAdmin = $conn->prepare($sqlAdmin);
    $stmtAdmin->bind_param("ss", $email, $password);
    $stmtAdmin->execute();
    $resultAdmin = $stmtAdmin->get_result();

    if ($resultAdmin->num_rows > 0) {
        $_SESSION["admin_id"] = $resultAdmin->fetch_assoc()["id"];
        header("Location: admin_panel/dashboard.php");
        exit();
    }

    // Login failed
    $errorMessage = "Invalid Email or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Transport Booking System</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
        button { background-color: #007BFF; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error-message { color: red; font-weight: bold; }
        a { display: inline-block; margin-top: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔐 Login to Transport Booking System</h2>

        <?php if (!empty($errorMessage)) { ?>
            <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
        <?php } ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Enter password" required>
            <button type="submit">Login</button>
        </form>

        <a href="register.php">📝 Register as a Passenger</a>
    </div>
</body>
</html>

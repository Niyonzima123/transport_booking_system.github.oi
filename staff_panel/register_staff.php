<?php
session_start();
include '../config.php';

// Handle staff registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO staff (name, email, username, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $username, $password);

    if ($stmt->execute()) {
        header("Location: login.php?success=StaffRegistered");
        exit();
    } else {
        die("❌ Error: Could not register staff.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register Staff - Transport Booking System</title>
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
        <h2>👷 Staff Registration</h2>
        <p>Register yourself as staff by filling in the required details.</p>

        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register as Staff</button>
        </form>

        <br>
        <a href="../index.php">🏠 Back to Home</a>
    </div>
</body>
</html>

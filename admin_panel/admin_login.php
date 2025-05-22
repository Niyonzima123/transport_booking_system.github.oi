<?php
session_start();
include '../config.php';

// Handle admin login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check admin credentials
    $sql = "SELECT * FROM admin WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin["password"])) {
            $_SESSION["admin_id"] = $admin["id"];
            header("Location: dashboard.php");
            exit();
        } else {
            die("Error: Incorrect password.");
        }
    } else {
        die("Error: Email not found.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
        button { background-color: #007BFF; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h2>Login as a Transport Admin</h2>
<div class="container">                                                                
        
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    </div>
</body>
</html>

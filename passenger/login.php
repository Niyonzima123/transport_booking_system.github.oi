<?php
session_start();
include '../config.php';

// Handle user login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check user credentials
    $sql = "SELECT * FROM passengers WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            header("Location: home.php");
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
    <title>Passenger Login</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
        button { background-color: #007BFF; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h2>Login as a Passenger</h2>
    <div>                      
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <a href="../register.php">🏠 Register</a>
    </div>
</body>
</html>

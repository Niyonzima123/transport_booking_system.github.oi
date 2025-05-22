<?php
session_start();
include '../config.php';

// Handle passenger registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $middle_name = $_POST["middle_name"] ?? ''; // Optional
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $province = $_POST["province"];
    $district = $_POST["district"];
    $sector = $_POST["sector"];
    $place = $_POST["place"];
    $other = $_POST["other"] ?? ''; // Optional
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Secure Hashing

    // Insert passenger into database
    $sql = "INSERT INTO passengers (first_name, middle_name, last_name, email, phone_number, province, district, sector, place, other, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $first_name, $middle_name, $last_name, $email, $phone_number, $province, $district, $sector, $place, $other, $password);

    if ($stmt->execute()) {
        $_SESSION["user_id"] = $stmt->insert_id;
        header("Location: home.php");
        exit();
    } else {
        die("Error: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Passenger Registration</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
        button { background-color: #007BFF; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h2>Register as a Passenger</h2>
    <div class="container">
    <form method="POST">
        <label>First Name:</label>
        <input type="text" name="first_name" required><br>
        <label>Middle Name (Optional):</label>
        <input type="text" name="middle_name"><br>
        <label>Last Name:</label>
        <input type="text" name="last_name" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Phone Number:</label>
        <input type="text" name="phone_number" required><br>
        <label>Province:</label>
        <input type="text" name="province" required><br>
        <label>District:</label>
        <input type="text" name="district" required><br>
        <label>Sector:</label>
        <input type="text" name="sector" required><br>
        <label>Place:</label>
        <input type="text" name="place" required><br>
        <label>Other Details (Optional):</label>
        <textarea name="other"></textarea><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
    </>
</body>
<a href="../login.php">🏠 Login</a>
</html>

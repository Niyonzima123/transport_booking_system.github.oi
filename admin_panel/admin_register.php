<?php
session_start();
include '../config.php';

// Handle admin registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $phone_number = $_POST["phone_number"];
    $national_id = $_POST["national_id"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $company_id = $_POST["company_id"];

    // Insert admin into database
    $sql = "INSERT INTO admins (first_name, last_name, phone_number, national_id, email, password, company_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $first_name, $last_name, $phone_number, $national_id, $email, $password, $company_id);

    if ($stmt->execute()) {
        $_SESSION["admin_id"] = $stmt->insert_id;
        header("Location: dashboard.php");
        exit();
    } else {
        die("Error: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
        button { background-color: #007BFF; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
    <title>Admin Registration</title>
</head>
<body>
    <h2>Register as a Transport Admin</h2>
    <div class="container">
    <form method="POST">
        <label>First Name:</label>
        <input type="text" name="first_name" required><br>
        <label>Last Name:</label>
        <input type="text" name="last_name" required><br>
        <label>Phone Number:</label>
        <input type="text" name="phone_number" required><br>
        <label>National ID:</label>
        <input type="text" name="national_id" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <label>Select Transport Company:</label>
        <select name="company_id" required>
            <?php
            $sqlCompanies = "SELECT * FROM companies";
            $resultCompanies = $conn->query($sqlCompanies);
            while ($company = $resultCompanies->fetch_assoc()) {
                echo "<option value='" . $company['id'] . "'>" . htmlspecialchars($company['company_name']) . "</option>";
            }
            ?>
        </select><br>
        <button type="submit">Register</button>
    </form>
    </div>
</body>
</html>

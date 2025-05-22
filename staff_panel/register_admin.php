<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

// Handle admin registration
$errorMessage = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $phone_number = $_POST["phone_number"];
    $national_id = $_POST["national_id"];
    $email = $_POST["email"];
    $company_id = $_POST["company_id"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Secure encryption

    // Check if email or national ID already exists
    $sqlCheck = "SELECT * FROM admin WHERE email=? OR national_id=?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ss", $email, $national_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        $errorMessage = "❌ Error: Admin with this email or national ID already exists.";
    } else {
        // Insert new admin with all details
        $sqlInsert = "INSERT INTO admin (first_name, last_name, phone_number, national_id, email, password, company_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("ssssssi", $first_name, $last_name, $phone_number, $national_id, $email, $password, $company_id);

        if ($stmtInsert->execute()) {
            header("Location: manage_admins.php?success=AdminRegistered");
            exit();
        } else {
            $errorMessage = "❌ Error: Could not register admin.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register Admin - Transport Booking System</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
        button { background-color: #007BFF; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🛡️ Admin Registration</h2>
        <p>Only staff members can add new admins.</p>

        <?php if (!empty($errorMessage)) { ?>
            <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
        <?php } ?>

        <form method="POST">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="text" name="phone_number" placeholder="Phone Number" required>
            <input type="text" name="national_id" placeholder="National ID" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Set Admin Password" required>
            <input type="number" name="company_id" placeholder="Company ID" required>
            <input type="text" name="username" placeholder="Username" required>
            <button type="submit">✅ Register Admin</button>
        </form>

        <br>
        <a href="dashboard.php">🔙 Back to Dashboard</a>
    </div>
</body>
</html>

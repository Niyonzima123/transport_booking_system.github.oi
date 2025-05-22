<!DOCTYPE html>
<html lang="en">
<head>
    <title>Transport Booking System - Welcome</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; text-align: center; padding: 30px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        h1 { color: #007BFF; }
        p { font-size: 18px; }
        .role-links { display: flex; justify-content: center; flex-wrap: wrap; gap: 20px; margin-top: 20px; }
        a { text-decoration: none; font-weight: bold; padding: 12px; border-radius: 5px; width: 250px; display: inline-block; text-align: center; }
        .admin-btn { background-color: #343a40; color: white; }
        .staff-btn { background-color: #28a745; color: white; }
        .passenger-btn { background-color: #007BFF; color: white; }
        .passenger-register { background-color: #ffc107; color: black; }
        .staff-register { background-color: #17a2b8; color: white; }
        .error-message { color: red; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 16px; color: gray; }
        .header img { width: 200px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="images.png" alt="Transport Booking Logo" width="800" height="100">
            <h1>🚏 Welcome to Transport Booking System</h1>
            <p>Choose your role to proceed:</p>
        </div>

        <div class="role-links">
            <a href="admin_panel/admin_login.php" class="admin-btn">🔹 Admin Login</a>
            <a href="staff_panel/login.php" class="staff-btn">👷 Staff Login</a>
            <a href="passenger/login.php" class="passenger-btn">🚀 Passenger Login</a>
            <a href="passenger/register.php" class="passenger-register">📝 Passenger Registration</a>
            <a href="staff_panel/register_staff.php" class="staff-register">📝 Staff Registration</a>
        </div>

        <div class="footer">
            <p>📞 Need Help? Contact Support at support@transportbooking.com | +250 788 123 456</p>
        </div>
    </div>
</body>
</html>

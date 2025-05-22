<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Help Center</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; text-align: center; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        h2 { color: #007BFF; }
        .contact-info { font-size: 18px; margin-top: 20px; }
        .back-btn { background-color: #007BFF; color: white; padding: 10px; border-radius: 5px; text-decoration: none; display: inline-block; }
        .back-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>❓ Help Center</h2>
        <p>Need assistance with your transport booking? Here are ways to get help:</p>

        <h3>📞 Contact Support</h3>
        <p class="contact-info">📧 Email: support@transportbooking.com</p>
        <p class="contact-info">📞 Phone: +250 788 123 456</p>

        <h3>🔗 Quick Links</h3>
        <p>
            <a href="home.php" class="back-btn">🏠 Back to Dashboard</a> |
            <a href="book_ticket.php" class="back-btn">📌 Book a Ticket</a> |
            <a href="payment_status.php" class="back-btn">💰 Payment Status</a>
        </p>
    </div>
</body>
</html>

<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password is empty
$dbname = "transport_booking_system";

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for successful connection
if ($conn->connect_error) {
    die("Error: Unable to connect to database. " . $conn->connect_error);
}
?>

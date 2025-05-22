<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["admin_id"])) {
    $admin_id = intval($_POST["admin_id"]);

    // Secure deletion query
    $sql = "DELETE FROM admin WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);

    if ($stmt->execute()) {
        header("Location: manage_admins.php?success=AdminDeleted");
        exit();
    } else {
        echo "❌ Error: Could not delete admin.";
    }
} else {
    echo "❌ Error: Invalid request.";
}
?>

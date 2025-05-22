<?php
session_start();
include '../config.php';

// Ensure staff is logged in
if (!isset($_SESSION["staff_id"])) {
    header("Location: login.php");
    exit();
}

// Validate user ID
if (isset($_POST["user_id"])) {
    $user_id = intval($_POST["user_id"]);
    
    // Prepare deletion query
    $sql = "DELETE FROM passengers WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header("Location: manage_users.php?success=UserDeleted");
        exit();
    } else {
        echo "❌ Error: Could not delete user.";
    }
} else {
    echo "❌ Error: Invalid request.";
}
?>

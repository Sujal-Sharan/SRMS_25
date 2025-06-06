<?php
require_once("DB_Connect.php");
session_start();

// Checking for proper role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$user_id = $_POST['user_id'];
$new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

$sql = "UPDATE login SET password = '$new_password' WHERE user_id = $user_id";
if ($conn->query($sql)) {
    echo "✅ Password reset successfully. <a href='reset_password_UI.php'>Reset another</a>";
} else {
    echo "❌ Failed to reset password.";
}
?>

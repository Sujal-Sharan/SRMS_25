<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "srms");

$user_id = (int)$_POST['user_id'];
$new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

$sql = "UPDATE users SET password = '$new_password' WHERE user_id = $user_id";
if ($conn->query($sql)) {
    echo "✅ Password reset successfully. <a href='admin_reset_password.php'>Reset another</a>";
} else {
    echo "❌ Failed to reset password.";
}
?>

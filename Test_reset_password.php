<?php
$conn = new mysqli("localhost", "root", "", "srms");

$username = $conn->real_escape_string($_POST['username']);
$new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

$sql = "UPDATE users SET password = '$new_password' WHERE username = '$username'";
if ($conn->query($sql)) {
    echo "✅ Password reset! <a href='Test_login.php'>Login now</a>";
} else {
    echo "❌ Error resetting password.";
}
?>

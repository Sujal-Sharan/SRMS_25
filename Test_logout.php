<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Clear cookies if set
setcookie("user_id", "", time() - 3600, "/");
setcookie("username", "", time() - 3600, "/");
setcookie("role", "", time() - 3600, "/");

// Redirect to login page
header("Location: Test_login.php");
exit;
?>

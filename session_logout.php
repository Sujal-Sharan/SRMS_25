<?php
require_once("DB_Connect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if (time() - $_SESSION['login_time'] > $_SESSION['expire_after']) {
    session_unset();
    session_destroy();
    header("Location: login.php?session_expired=1");
    exit();
} else {
    // Reset login_time to extend session
    $_SESSION['login_time'] = time();
}
?>

<script>
    setTimeout(() => {
        alert("Your session is about to expire!");
    }, 2 * 60 * 1000); // Warn after 25 minutes


    // Listen for logout events from other tabs
    window.addEventListener('storage', function(event) {
        if (event.key === 'logout-event') {
            alert("You have been logged out from another tab.");
            window.location.href = "login.php";
        }
    });
</script>
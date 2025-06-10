<?php
session_start();
session_unset();
session_destroy();
?>

<script>
    // Trigger logout event in all tabs using localStorage
    localStorage.setItem('logout-event', Date.now());
    window.location.href = "login.php";
</script>

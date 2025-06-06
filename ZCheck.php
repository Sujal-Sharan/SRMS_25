<?php
// Working on session timeouts
session_start();
$timeout = 60; // 60 seconds

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: logout.php?reason=timeout");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time(); // Update activity timestamp
?>

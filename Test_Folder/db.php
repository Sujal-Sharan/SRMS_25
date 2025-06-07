<?php
$pdo = new PDO("mysql:host=localhost; dbname=srms", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

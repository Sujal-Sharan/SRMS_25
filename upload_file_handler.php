<?php
require_once("DB_Connect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $customName = $_POST['file_name'];
    $file = $_FILES['document'];

    if ($file['error'] == 0) {
        $originalName = basename($file['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        // Customize file name
        $customName = $customName . "." . $extension;
        $target_dir = "uploads/";
        $target_path = $target_dir . $customName;

        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            $stmt = $conn->prepare("INSERT INTO documents (student_id, filename, filepath) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $student_id, $customName, $target_path);
            $stmt->execute();

            echo "Upload successful! Saved as $customName <a href='admin_dashboard.php'> Back </a>";
        } else {
            echo "Failed to move file. <a href='admin_dashboard.php'> Back </a>";
        }
    } else {
        echo "Error uploading file. <a href='admin_dashboard.php'> Back </a>";
    }
}
?>

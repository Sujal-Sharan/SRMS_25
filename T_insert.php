<?php
require_once("DB_Connect.php");
session_start();

// Check if data was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['id'])) {

    $idArray = $_POST['id'];
    $passwordArray = $_POST['password'];
    $roleArray = $_POST['role'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO login (user_id, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $id, $password, $role);

    // Loop through submitted data and insert each row
    for ($i = 0; $i < count($idArray); $i++) {

        $id = $idArray[$i];
        $password = password_hash($passwordArray[$i], PASSWORD_BCRYPT); // Storing hashed password
        $role = $roleArray[$i];

        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    echo "Data inserted successfully!";
    //TODO: ADD redirect to dashboard to prev page
} else {
    echo "No data submitted!";
}
?>

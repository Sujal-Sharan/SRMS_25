<?php
$servername = "localhost";  // e.g., "localhost"
$username = "root";    // e.g., "root"
$password = "";
$dbname = "srms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
} else {
    echo "No data submitted!";
}
?>

<?php
require_once("DB_Connect.php");
session_start();

$sql = "SELECT 
            student_id, 
            subject_id, 
            semester, 
            test_type,
            marks_obtained, 
            is_absent 
        FROM 
            marks
        WHERE 1";

$types = "";   // To hold bind_param types (e.g., "s" for string, "i" for integer)
$values = [];  // To hold the values for binding



// Prepare the query
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if (!empty($values)) {
    $stmt->bind_param($types, ...$values);  // Spread operator to pass the parameters
}

$stmt->execute();
$result = $stmt->get_result();
?>
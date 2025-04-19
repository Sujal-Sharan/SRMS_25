<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "test");

$course_code = $_GET['course_code'] ?? '';
$response = [];

if ($course_code) {
    $stmt = $conn->prepare("SELECT student_id, name FROM students WHERE course_code = ?");
    $stmt->bind_param("s", $course_code);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

echo json_encode($response);
?>
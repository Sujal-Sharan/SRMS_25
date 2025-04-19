<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "test");

$course_code = $_POST['course_code'] ?? '';
$marks = $_POST['marks'] ?? [];

if (!$course_code || empty($marks)) {
    echo json_encode([
        "success" => false,
        "message" => "Course or marks data missing.",
    ]);
    exit;
}

try {
    foreach ($marks as $student_id => $mark) {
        if (!is_numeric($mark)) continue;
        $stmt = $conn->prepare("REPLACE INTO marks (student_id, course_code, marks) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $student_id, $course_code, $mark);
        $stmt->execute();
    }
    echo json_encode([
        "success" => true,
        "message" => "Marks successfully saved.",
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error saving marks.",
    ]);
}
?>
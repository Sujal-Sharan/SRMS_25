<?php
$conn = new mysqli("localhost", "root", "", "srms");

$department = isset($_GET['department']) ? $conn->real_escape_string($_GET['department']) : '';

$sql = "SELECT s.student_id, s.name, s.attendance, s.department,
               ROUND((IFNULL(ca1,0)+IFNULL(ca2,0)+IFNULL(ca3,0)+IFNULL(ca4,0)+IFNULL(pca1,0)+IFNULL(pca2,0))/6, 2) AS average_marks
        FROM students s
        JOIN marks m ON s.student_id = m.student_id
        WHERE s.attendance < 75";

if ($department !== '') {
    $sql .= " AND s.department = '$department'";
}

$sql .= " GROUP BY s.student_id HAVING average_marks < 40";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>

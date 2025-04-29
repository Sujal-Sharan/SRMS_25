<?php
// Example PHP script
require_once("DB_Connect.php");
// $conn = new mysqli('localhost', 'username', 'password', 'dbname');

$semester = 1;

$sql = "SELECT 
            m.student_id AS student_id,
            s.name AS student_name,
            subj.subject_code AS subject_code,
            subj.subject_name AS subject_name,
            m.semester AS semester,
            
            CASE 
                WHEN MAX(m.test_type = 'CA1' AND m.is_absent = TRUE AND m.semester = $semester AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'CA1' AND m.semester = $semester THEN m.marks_obtained END) AS CHAR)
            END AS CA1,
            
            CASE 
                WHEN MAX(m.test_type = 'CA2' AND m.is_absent = TRUE AND m.semester = $semester AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'CA2' AND m.semester = $semester THEN m.marks_obtained END) AS CHAR)
            END AS CA2,
            
            CASE 
                WHEN MAX(m.test_type = 'CA3' AND m.is_absent = TRUE AND m.semester = $semester AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'CA3' AND m.semester = $semester THEN m.marks_obtained END) AS CHAR)
            END AS CA3,
            
            CASE 
                WHEN MAX(m.test_type = 'CA4' AND m.is_absent = TRUE AND m.semester = $semester AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'CA4' AND m.semester = $semester THEN m.marks_obtained END) AS CHAR)
            END AS CA4

        FROM 
            marks m
        JOIN 
            students s ON m.student_id = s.college_roll
        JOIN 
            subjects subj ON m.subject_id = subj.subject_id
        WHERE 1
        GROUP BY student_id"; 

// $sql .= " AND student_id = 101";    
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Output the data as JSON
echo json_encode($data);
?>

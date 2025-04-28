<?php
// Example PHP script
require_once("DB_Connect.php");
// $conn = new mysqli('localhost', 'username', 'password', 'dbname');

$sql1 = "SELECT mark_id, student_id, test_type, marks_obtained,
    FROM marks
    WHERE 1";

$sql2 = "SELECT mark_id, student_id, test_type, marks_obtained, is_absent,
    CASE 
        WHEN test_type = 'CA1' AND is_absent = TRUE AND marks_obtained IS NULL THEN 'ABSENT'
        ELSE CAST(mark_id AS CHAR)
    END AS CA1
FROM marks
WHERE 1";

$sql3 = "SELECT mark_id, student_id, test_type, marks_obtained, is_absent,
    CASE 
        WHEN SUM(CASE WHEN test_type = 'CA1' AND is_absent = TRUE AND marks_obtained IS NULL THEN 1 ELSE 0 END) > 0
            THEN 'ABSENT'
        ELSE CAST(MAX(CASE WHEN test_type = 'CA1' THEN marks_obtained END) AS CHAR)
    END AS CA1,
    CASE 
        WHEN SUM(CASE WHEN test_type = 'CA2' AND is_absent = TRUE AND marks_obtained IS NULL THEN 1 ELSE 0 END) > 0
            THEN 'ABSENT'
        ELSE CAST(MAX(CASE WHEN test_type = 'CA2' THEN marks_obtained END) AS CHAR)
    END AS CA2,
    CASE 
        WHEN SUM(CASE WHEN test_type = 'CA3' AND is_absent = TRUE AND marks_obtained IS NULL THEN 1 ELSE 0 END) > 0
            THEN 'ABSENT'
        ELSE CAST(MAX(CASE WHEN test_type = 'CA3' THEN marks_obtained END) AS CHAR)
    END AS CA3
FROM marks
GROUP BY student_id";

$sql = "SELECT 
            m.student_id,
            m.subject_id AS subject_id,
            m.semester AS semester,
            
            CASE 
                WHEN MAX(m.test_type = 'CA1' AND m.is_absent = TRUE AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'CA1' THEN m.marks_obtained END) AS CHAR)
            END AS CA1,
            
            CASE 
                WHEN MAX(m.test_type = 'CA2' AND m.is_absent = TRUE AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'CA2' THEN m.marks_obtained END) AS CHAR)
            END AS CA2,
            
            CASE 
                WHEN MAX(m.test_type = 'CA3' AND m.is_absent = TRUE AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'CA3' THEN m.marks_obtained END) AS CHAR)
            END AS CA3,
            
            CASE 
                WHEN MAX(m.test_type = 'CA4' AND m.is_absent = TRUE AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'CA4' THEN m.marks_obtained END) AS CHAR)
            END AS CA4

        FROM 
            marks m
        JOIN 
            students s ON m.student_id = s.college_roll
        WHERE 1
        GROUP BY student_id";  // 'WHERE 1' always return true

// $sql .= " AND student_id = 101";    
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Output the data as JSON
echo json_encode($data);
?>

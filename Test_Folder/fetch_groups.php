<?php
include 'db.php';
$dept = $_GET['department'] ?? '';
$section = $_GET['section'] ?? '';

if ($dept && $section) {
    $stmt = $pdo->prepare("SELECT DISTINCT group_name FROM students WHERE department = ? AND section = ?");
    $stmt->execute([$dept, $section]);
    echo '<option value="">Select Group</option>';
    while ($row = $stmt->fetch()) {
        echo "<option value='{$row['group_name']}'>{$row['group_name']}</option>";
    }
}
?>

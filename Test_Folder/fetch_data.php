<?php
include 'db.php';
$dept = $_GET['department'] ?? '';
$section = $_GET['section'] ?? '';
$group = $_GET['group'] ?? '';

if ($dept && $section && $group) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE department = ? AND section = ? AND group_name = ?");
    $stmt->execute([$dept, $section, $group]);

    echo "<h3>Students:</h3><ul>";
    while ($row = $stmt->fetch()) {
        echo "<li>{$row['name']}</li>";
    }
    echo "</ul>";
}
?>

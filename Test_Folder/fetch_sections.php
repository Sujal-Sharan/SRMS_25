<?php
include 'db.php';
$dept = $_GET['department'] ?? '';
if ($dept) {
    $stmt = $pdo->prepare("SELECT DISTINCT section FROM students WHERE department = ?");
    $stmt->execute([$dept]);
    echo '<option value="">Select Section</option>';
    while ($row = $stmt->fetch()) {
        echo "<option value='{$row['section']}'>{$row['section']}</option>";
    }
}
?>

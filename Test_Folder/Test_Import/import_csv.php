<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "srms";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$table = $_POST['table'];
$headers = json_decode($_POST['headers']);
$rows = json_decode($_POST['rows']);

function getColumnTypes($conn, $table, $columns) {
    $types = [];
    $placeholders = "'" . implode("','", $columns) . "'";
    $query = "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table' AND COLUMN_NAME IN ($placeholders)";
    $result = $conn->query($query);
    $columnTypes = [];
    while ($row = $result->fetch_assoc()) {
        $type = strtolower($row['DATA_TYPE']);
        $paramType = 's';
        if (in_array($type, ['int', 'tinyint', 'smallint', 'bigint'])) $paramType = 'i';
        elseif (in_array($type, ['float', 'double', 'decimal'])) $paramType = 'd';
        $columnTypes[$row['COLUMN_NAME']] = $paramType;
    }
    $bindTypes = '';
    foreach ($columns as $col) {
        $bindTypes .= $columnTypes[$col] ?? 's';
    }
    return $bindTypes;
}

$types = getColumnTypes($conn, $table, $headers);
$placeholders = implode(',', array_fill(0, count($headers), '?'));
$columns_sql = "`" . implode("`,`", $headers) . "`";
$stmt = $conn->prepare("INSERT INTO `$table` ($columns_sql) VALUES ($placeholders)");

$skipped = 0;
$inserted = 0;

foreach ($rows as $row) {
    // Basic validation: skip if any field is empty
    if (in_array("", $row, true)) {
        $skipped++;
        continue;
    }

    try {
        $stmt->bind_param($types, ...$row);
        $stmt->execute();
        $inserted++;
    } catch (Exception $e) {
        $skipped++;
        continue;
    }
}

echo "<script>
    alert('Imported $inserted rows. Skipped $skipped due to errors.');
    window.location.href = 'form.html';
</script>";

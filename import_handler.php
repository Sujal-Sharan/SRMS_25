<?php
require_once("DB_Connect.php");
session_start();

function getColumnTypes($conn, $table, $columns) {
    $placeholders = "'" . implode("','", $columns) . "'";

    $query = "SELECT COLUMN_NAME, DATA_TYPE 
              FROM INFORMATION_SCHEMA.COLUMNS 
              WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = '$table' 
              AND COLUMN_NAME IN ($placeholders)";

    $result = $conn->query($query);
    $columnTypes = [];

    while ($row = $result->fetch_assoc()) {
        $type = strtolower($row['DATA_TYPE']);
        $paramType = 's'; // default to string

        if (in_array($type, ['int', 'bigint', 'tinyint', 'smallint', 'mediumint'])) {
            $paramType = 'i';
        } elseif (in_array($type, ['float', 'double', 'decimal'])) {
            $paramType = 'd';
        }

        $columnTypes[$row['COLUMN_NAME']] = $paramType;
    }

    // Return types string like 'sisds' (based on original order)
    $bindTypes = '';
    foreach ($columns as $col) {
        $bindTypes .= $columnTypes[$col] ?? 's'; // fallback to 's'
    }

    return $bindTypes;
}

if (isset($_POST['import'])) {
    $table = $_POST['table'];
    $file = $_FILES["csv_file"]["tmp_name"];

    if ($_FILES["csv_file"]["size"] > 0) {
        $handle = fopen($file, "r");
        $columns = array_map('trim', fgetcsv($handle, 1000, ","));

        $columns_sql = implode("`, `", $columns);
        $columns_sql = "`" . $columns_sql . "`";
        $placeholders = rtrim(str_repeat("?,", count($columns)), ",");

        $sql = "INSERT INTO `$table` ($columns_sql) VALUES ($placeholders)";
        $stmt = $conn->prepare($sql);

        $types = getColumnTypes($conn, $table, $columns);

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $params = array_map('trim', $data);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
        }

        fclose($handle);
        echo "Imported successfully into `$table`.";
    } else {
        echo "Invalid or empty file.";
    }
}
?>

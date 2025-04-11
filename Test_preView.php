<?php
if (isset($_POST["preview"])) {
    $targetDir = "uploads/temp/";

    // Ensure the directory exists
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Sanitize file name
    $originalName = basename($_FILES["file"]["name"]);
    $safeName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $originalName);
    $timestamp = time();
    $targetFile = $targetDir . $timestamp . "_" . $safeName;

    // Move uploaded file
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        echo "✅ File uploaded successfully.<br>";

        // Absolute path for fopen
        $fullPath = __DIR__ . '/' . $targetFile;
        echo "Opening file: $fullPath<br>";

        // Start previewing CSV
        echo "<form action='Test_Confirm.php' method='POST'>";
        echo "<input type='hidden' name='filepath' value='$targetFile'>";
        echo "<table border='1'>";

        if (($handle = fopen($fullPath, "r")) !== FALSE) {
            $header = fgetcsv($handle);
            echo "<tr>";
            foreach ($header as $col) echo "<th>" . htmlspecialchars($col) . "</th>";
            echo "</tr>";

            while (($data = fgetcsv($handle)) !== FALSE) {
                echo "<tr>";
                foreach ($data as $value) echo "<td>" . htmlspecialchars($value) . "</td>";
                echo "</tr>";
            }

            fclose($handle);
            echo "</table><br>";
            echo "<button type='submit' name='import'>Confirm Import</button>";
            echo "</form>";
        } else {
            echo "❌ Error opening file.";
        }
    } else {
        echo "❌ File upload failed.";
    }
}
?>

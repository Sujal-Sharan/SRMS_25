<?php
if (isset($_POST["import"])) {
    $filepath = $_POST["filepath"];
    $conn = new mysqli("localhost", "root", "", "srms");

    if (($file = fopen($filepath, "r")) !== FALSE) {
        $header = fgetcsv($file); // skip header

        while (($data = fgetcsv($file)) !== FALSE) {
            $sql = "INSERT INTO test_data (id, name, course, cgpa)
                VALUES ($data[0], '$data[1]', '$data[2]', $data[3])";

            // $sql = "INSERT INTO test_data (first_name, last_name, email, enrollment_no)
            //         VALUES (
            //             '" . $conn->real_escape_string($data[0]) . "',
            //             '" . $conn->real_escape_string($data[1]) . "',
            //             '" . $conn->real_escape_string($data[2]) . "',
            //             '" . $conn->real_escape_string($data[3]) . "'
            //         )";
            $conn->query($sql);
        }
        fclose($file);
        unlink($filepath); // clean up the temp file
        echo "Data successfully imported!";
    } else {
        echo "Failed to open file.";
    }
}
?>

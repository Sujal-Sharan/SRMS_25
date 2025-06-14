<?php
if (isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $table = $_POST['table'];
    $rows = [];

    if (($handle = fopen($file, "r")) !== false) {
        $headers = fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = $data;
        }
        fclose($handle);
    }
}
?>

<h2>Preview Data for Table: <?= htmlspecialchars($table) ?></h2>
<form action="import_csv.php" method="POST">
    <input type="hidden" name="table" value="<?= htmlspecialchars($table) ?>">
    <input type="hidden" name="headers" value="<?= htmlspecialchars(json_encode($headers)) ?>">
    <input type="hidden" name="rows" value="<?= htmlspecialchars(json_encode($rows)) ?>">

    <table border="1">
        <thead><tr>
        <?php foreach ($headers as $h): ?>
            <th><?= htmlspecialchars($h) ?></th>
        <?php endforeach; ?>
        </tr></thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
            <?php foreach ($row as $value): ?>
                <td><?= htmlspecialchars($value) ?></td>
            <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit">Import</button>
</form>

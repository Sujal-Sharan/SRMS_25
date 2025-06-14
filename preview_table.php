<?php
require_once("DB_Connect.php");
session_start();

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Preview</title>
    <link rel="stylesheet" href="Styles/global_base.css" />
    <link rel="icon" type="image/x-icon" href="logo.png">

    <style>
        .card {
			background-color: white;
			padding: 20px;
			margin-top: 100px;
			border-radius: 10px;
			box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
			width: 100%;
			overflow-x: auto;
		}

		.card table {
			width: 100%;
			border-collapse: collapse;
		}

		.card th, .card td {
			border: 1px solid #ccc;
			padding: 10px;
			text-align: center;
		}

		.card input[type="text"] {
			border: none;
			outline: none;
			background: transparent;
			text-align: center;
			/* width: 100%;  */
			width: auto;
			font-size: 14px;
			color: #333;
		}

		.card input[type="text"] {
			background-color:rgb(241, 241, 241);
		}
		.card input[type="text"]:read-only {
			background-color: transparent;
		}

		.card input[type="text"]::selection {
			background: #1abc9c;
			color: white;
		}
    </style>
</head>
<body>
    <header>
        <img src="logo.png" alt="Logo" style="height: 100px; margin-right: 20px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">TECHNO INTERNATIONAL NEWTOWN</h1>
            <p style="margin: 0; font-size: 14px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 14px; margin-left: 5px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span>
                <p>&#9742; +338910530723 / 8910530723</p>
            </span>
        </div>
    </header>

    <div class="layout">
        <div class="card">
            <h2>Preview Data for Table</h2>
            <form action="import_handler.php" method="POST">

                <a href="<?php ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'faculty_dashboard.php') ?>">
                    <button name="dashboard" >Dashboard</button>
                </a><br>
                
                <button type="submit" name="import" >Import</button><br>

                <input type="hidden" name="table" value="<?= htmlspecialchars($table) ?>" hidden>
                <input type="hidden" name="headers" value="<?= htmlspecialchars(json_encode($headers)) ?>">
                <input type="hidden" name="rows" value="<?= htmlspecialchars(json_encode($rows)) ?>">

                <table>
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

            </form>
        </div>
    </div>
</body>
</html>
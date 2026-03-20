<?php
$host = getenv('DB_HOST');
$port = (int)getenv('DB_PORT'); 
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

if (!mysqli_real_connect($conn, $host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die();
}

$sql = "SELECT * FROM moje_projekty";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die();
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>SQL Portfolio</title>
    <style>
        body { font-family: sans-serif; background: #121212; color: #e0e0e0; padding: 40px; }
        .container { max-width: 900px; margin: auto; background: #1e1e1e; padding: 20px; border-radius: 12px; border: 1px solid #333; }
        h1 { color: #4a90e2; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; border-bottom: 1px solid #333; text-align: left; }
        th { background: #252525; color: #4a90e2; }

        .tooltip {
            position: relative;
            cursor: help;
            color: #4a90e2;
            text-decoration: underline dotted;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 10px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -125px;
            opacity: 0;
            transition: opacity 0.3s;
            border: 1px solid #4a90e2;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.5);
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Moje SQL Projekty</h1>
        <table>
            <thead>
                <tr>
                    <th>Projekt</th>
                    <th>Technológia</th>
                    <th>Rok</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <div class="tooltip">
                            <strong><?= htmlspecialchars($row['nazov_projektu']) ?></strong>
                            <span class="tooltiptext"><?= htmlspecialchars($row['popis']) ?></span>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($row['technologia']) ?></td>
                    <td><?= htmlspecialchars($row['rok_vytvorenia']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

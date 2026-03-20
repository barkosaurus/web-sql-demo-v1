<?php
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL); 

if (!mysqli_real_connect($conn, $host, $user, $pass, $dbname, $port)) {
    die("Chyba pripojenia: " . mysqli_connect_error());
}

$sql = "SELECT * FROM moje_projekty";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Moje SQL Portfolio</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #007bff; color: white; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Zoznam projektov z Cloud DB</h1>
        <table>
            <tr>
                <th>Názov</th>
                <th>Technológia</th>
                <th>Popis</th>
                <th>Rok</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><strong><?php echo $row['nazov_projektu']; ?></strong></td>
                <td><?php echo $row['technologia']; ?></td>
                <td><?php echo $row['popis']; ?></td>
                <td><?php echo $row['rok_vytvorenia']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php
$host = getenv('DB_HOST');
$port = (int)getenv('DB_PORT'); 
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

if (!mysqli_real_connect($conn, $host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Nepodarilo sa pripojiť k databáze.");
}

$sql = "SELECT * FROM moje_projekty";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Chyba v dopyte.");
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
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Moje SQL Projekty</h1>
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
                    <td><strong><?= htmlspecialchars($row['názov']) ?></strong></td>
                    <td><?= htmlspecialchars($row['technológia']) ?></td>
                    <td><?= htmlspecialchars($row['rok']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

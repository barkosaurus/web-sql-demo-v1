<?php
header('Content-Type: application/json');
$host = getenv('DB_HOST');
$port = (int)getenv('DB_PORT'); 
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
$is_connected = @mysqli_real_connect($conn, $host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);

if (!$is_connected) {
    echo json_encode(['db_connected' => false]);
    exit;
}

$filter_year = isset($_GET['year']) && $_GET['year'] !== '' ? (int)$_GET['year'] : null;

if ($filter_year) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM moje_projekty WHERE rok_vytvorenia = ? ORDER BY rok_vytvorenia DESC");
    mysqli_stmt_bind_param($stmt, "i", $filter_year);
} else {
    $stmt = mysqli_prepare($conn, "SELECT * FROM moje_projekty ORDER BY rok_vytvorenia DESC");
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$projects = [];
$latest = 0;

while($row = mysqli_fetch_assoc($result)) {
    $projects[] = $row;
    if($row['rok_vytvorenia'] > $latest) $latest = $row['rok_vytvorenia'];
}

echo json_encode([
    'db_connected' => true,
    'count' => count($projects),
    'latest_year' => $latest ?: '-',
    'data' => $projects
]);

mysqli_stmt_close($stmt);
mysqli_close($conn);

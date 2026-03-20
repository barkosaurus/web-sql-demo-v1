<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$start_time = microtime(true);

$host = getenv('DB_HOST');
$port = (int)getenv('DB_PORT'); 
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

try {
    $conn = mysqli_init();
    mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
    $conn->real_connect($host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(['db_connected' => false, 'error' => 'Connection failed']);
    exit;
}

$years_res = mysqli_query($conn, "SELECT DISTINCT rok_vytvorenia FROM moje_projekty ORDER BY rok_vytvorenia DESC");
if (!$years_res) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch available years']);
    exit;
}
$available_years = [];
while($yr = mysqli_fetch_assoc($years_res)) {
    $available_years[] = $yr['rok_vytvorenia'];
}

$filter_year = null;
if (isset($_GET['year']) && $_GET['year'] !== '') {
    $filter_year = filter_var($_GET['year'], FILTER_VALIDATE_INT);
    if ($filter_year === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid year parameter']);
        exit;
    }
}
$query_type = $filter_year ? "FILTER_YEAR" : "ALL_PROJECTS";

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

$execution_time = round((microtime(true) - $start_time) * 1000, 2);
$ip = $_SERVER['REMOTE_ADDR'];

try {
    $log_stmt = mysqli_prepare($conn, "INSERT INTO query_logs (visitor_ip, query_type, execution_time_ms) VALUES (?, ?, ?)");
    if (!$log_stmt) throw new Exception('Failed to prepare log statement');
    mysqli_stmt_bind_param($log_stmt, "ssd", $ip, $query_type, $execution_time);
    mysqli_stmt_execute($log_stmt);
    mysqli_stmt_close($log_stmt);
} catch (Exception $e) {
    error_log("Logging error: " . $e->getMessage());
}

echo json_encode([
    'db_connected' => true,
    'count' => count($projects),
    'latest_year' => !empty($projects) ? max(array_column($projects, 'rok_vytvorenia')) : null,
    'available_years' => $available_years,
    'data' => $projects
]);

mysqli_stmt_close($stmt);
mysqli_close($conn);

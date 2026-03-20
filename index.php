<?php
$start_time = microtime(true);
$host = getenv('DB_HOST');
$port = (int)getenv('DB_PORT'); 
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
$is_connected = @mysqli_real_connect($conn, $host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);

$all_projects = [];
$db_status = "Offline";
$status_color = "#EF4444";
$total_projects = 0;
$latest_year = "-";
$filter_year = isset($_GET['year']) ? (int)$_GET['year'] : null;

if ($is_connected) {
    $db_status = "Connected";
    $status_color = "#10B981";

    if ($filter_year) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM moje_projekty WHERE rok_vytvorenia = ? ORDER BY rok_vytvorenia DESC");
        mysqli_stmt_bind_param($stmt, "i", $filter_year);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM moje_projekty ORDER BY rok_vytvorenia DESC");
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $total_projects = mysqli_num_rows($result);

    while($row = mysqli_fetch_assoc($result)) {
        $all_projects[] = $row;
        if ($latest_year === "-" || $row['rok_vytvorenia'] > $latest_year) {
            $latest_year = $row['rok_vytvorenia'];
        }
    }
    mysqli_stmt_close($stmt);

    $execution_time = (microtime(true) - $start_time) * 1000;
    $log_stmt = mysqli_prepare($conn, "INSERT INTO query_logs (visitor_ip, query_type, execution_time_ms) VALUES (?, ?, ?)");
    $ip = $_SERVER['REMOTE_ADDR'];
    $type = $filter_year ? "Filtered Search" : "Full List";
    mysqli_stmt_bind_param($log_stmt, "ssd", $ip, $type, $execution_time);
    mysqli_stmt_execute($log_stmt);
    mysqli_stmt_close($log_stmt);
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Portfolio Professional</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0F172A;
            --card: #1E293B;
            --accent: #818CF8;
            --text: #E2E8F0;
            --muted: #94A3B8;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 20px; -webkit-font-smoothing: antialiased; }
        .container { max-width: 1000px; margin: auto; animation: fadeIn 0.6s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        header { text-align: center; margin-bottom: 40px; }
        h1 { font-family: 'Poppins', sans-serif; font-size: 2.5rem; color: #FFF; margin: 0; }

        .filters { display: flex; justify-content: center; gap: 10px; margin-bottom: 30px; flex-wrap: wrap; }
        .filter-btn { background: var(--card); border: 1px solid #334155; color: var(--text); padding: 10px 20px; border-radius: 25px; text-decoration: none; font-size: 0.9rem; transition: 0.3s; }
        .filter-btn:hover, .filter-btn.active { background: var(--accent); border-color: var(--accent); color: #FFF; box-shadow: 0 4px 12px rgba(129, 140, 248, 0.3); }

        .status-bar { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 15px; margin-bottom: 40px; }
        .stat-card { background: var(--card); padding: 20px; border-radius: 16px; border: 1px solid #334155; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .stat-v { display: block; font-size: 1.4rem; font-weight: 700; color: #FFF; }
        .stat-l { font-size: 0.75rem; text-transform: uppercase; color: var(--muted); letter-spacing: 1px; margin-top: 5px; display: block; }

        .desktop-table { width: 100%; border-collapse: collapse; background: var(--card); border-radius: 16px; overflow: hidden; border: 1px solid #334155; }
        .desktop-table th { background: #161E2E; color: var(--accent); padding: 20px; text-align: left; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
        .desktop-table td { padding: 20px; border-bottom: 1px solid #334155; }
        .desktop-table tr:last-child td { border-bottom: none; }
        .desktop-table tr:hover td { background: #2D3748; }

        .mobile-cards { display: none; flex-direction: column; gap: 15px; }
        .m-card { background: var(--card); border: 1px solid #334155; padding: 20px; border-radius: 16px; }
        .m-title { font-weight: 700; color: #FFF; font-size: 1.1rem; display: block; margin-bottom: 5px; }
        .m-desc { font-style: italic; color: var(--muted); font-size: 0.9rem; display: block; margin-bottom: 15px; }
        .m-meta { display: flex; justify-content: space-between; align-items: center; }

        .badge { background: rgba(129, 140, 248, 0.15); color: var(--accent); padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; border: 1px solid rgba(129, 140, 248, 0.3); }

        .tooltip { position: relative; cursor: help; color: #FFF; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .tooltip .tt { visibility: hidden; width: 280px; background: #1F2937; border: 1px solid var(--accent); color: var(--text); padding: 15px; border-radius: 12px; position: absolute; bottom: 135%; left: 0; opacity: 0; transition: 0.3s; z-index: 10; font-style: italic; font-weight: 400; box-shadow: 0 10px 15px rgba(0,0,0,0.4); }
        .tooltip:hover .tt { visibility: visible; opacity: 1; transform: translateY(-5px); }

        @media (max-width: 600px) {
            .desktop-table { display: none; }
            .mobile-cards { display: flex; }
            h1 { font-size: 1.8rem; }
            .container { padding: 10px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Data Engineering Portfolio</h1>
            <p style="color: var(--muted); font-size: 0.9rem; margin-top: 10px;">Automated Logging & Prepared Statements Active</p>
        </header>

        <nav class="filters">
            <a href="index.php" class="filter-btn <?= !$filter_year ? 'active' : '' ?>">Všetko</a>
            <a href="index.php?year=2025" class="filter-btn <?= $filter_year == 2025 ? 'active' : '' ?>">2025</a>
            <a href="index.php?year=2026" class="filter-btn <?= $filter_year == 2026 ? 'active' : '' ?>">2026</a>
        </nav>

        <section class="status-bar">
            <div class="stat-card"><span class="stat-v"><?= $total_projects ?></span><span class="stat-l">Projekty</span></div>
            <div class="stat-card"><span class="stat-v"><?= $latest_year ?></span><span class="stat-l">Posledný Rok</span></div>
            <div class="stat-card"><span class="stat-v" style="color: <?= $status_color ?>"><?= $db_status ?></span><span class="stat-l">TiDB Status</span></div>
        </section>

        <table class="desktop-table">
            <thead>
                <tr>
                    <th>Projekt</th>
                    <th>Technológia</th>
                    <th>Rok</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($all_projects as $row): ?>
                <tr>
                    <td>
                        <div class="tooltip">
                            <?= htmlspecialchars($row['nazov_projektu']) ?> <span>ⓘ</span>
                            <span class="tt"><?= htmlspecialchars($row['popis']) ?></span>
                        </div>
                    </td>
                    <td><span class="badge"><?= htmlspecialchars($row['technologia']) ?></span></td>
                    <td style="color: var(--muted);"><?= htmlspecialchars($row['rok_vytvorenia']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mobile-cards">
            <?php foreach($all_projects as $row): ?>
            <div class="m-card">
                <span class="m-title"><?= htmlspecialchars($row['nazov_projektu']) ?></span>
                <span class="m-desc"><?= htmlspecialchars($row['popis']) ?></span>
                <div class="m-meta">
                    <span class="badge"><?= htmlspecialchars($row['technologia']) ?></span>
                    <span style="color: var(--muted); font-size: 0.85rem;"><?= htmlspecialchars($row['rok_vytvorenia']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

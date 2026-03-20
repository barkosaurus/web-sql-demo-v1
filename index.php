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

$sql = "SELECT * FROM moje_projekty ORDER BY rok_vytvorenia DESC";
$result = mysqli_query($conn, $sql);

$total_projects = mysqli_num_rows($result);
$latest_year = 0;
$all_projects = [];

while($row = mysqli_fetch_assoc($result)) {
    $all_projects[] = $row;
    if ($row['rok_vytvorenia'] > $latest_year) {
        $latest_year = $row['rok_vytvorenia'];
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional SQL Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0F172A;
            --card-bg: #1E293B;
            --accent-color: #818CF8;
            --accent-hover: #A5B4FC;
            --text-main: #E2E8F0;
            --text-muted: #94A3B8;
            --hover-row: #2D3748;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-color); 
            color: var(--text-main); 
            padding: 40px 20px; 
            margin: 0;
            line-height: 1.6;
        }

        .container { 
            max-width: 1000px; 
            margin: auto; 
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        header {
            text-align: center;
            margin-bottom: 50px;
        }

        h1 { 
            font-family: 'Poppins', sans-serif; 
            font-size: 2.8rem;
            color: #FFFFFF;
            margin: 0;
            letter-spacing: -1px;
        }

        .status-bar {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 15px 25px;
            border-radius: 12px;
            border: 1px solid #334155;
            text-align: center;
            min-width: 150px;
        }

        .stat-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-color);
        }

        .stat-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 1px;
        }

        .table-wrapper {
            background-color: var(--card-bg); 
            border-radius: 16px; 
            border: 1px solid #334155;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
        }

        th { 
            background: #161E2E;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            color: var(--accent-color);
            padding: 20px; 
            text-align: left; 
        }

        td { 
            padding: 20px; 
            border-bottom: 1px solid #334155; 
        }

        tr:hover td { background-color: var(--hover-row); }

        .project-name {
            font-weight: 600;
            color: #FFFFFF;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            border: 1px solid var(--accent-color);
            border-radius: 50%;
            font-size: 12px;
            color: var(--accent-color);
            cursor: help;
        }

        .tech-badge {
            background: rgba(129, 140, 248, 0.1);
            color: var(--accent-color);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            border: 1px solid rgba(129, 140, 248, 0.2);
        }

        .tooltip { position: relative; display: inline-block; }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 280px;
            background-color: #1F2937;
            color: var(--text-main);
            border-radius: 8px;
            padding: 15px;
            position: absolute;
            z-index: 10;
            bottom: 140%;
            left: 0;
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            transform: translateY(10px) scale(0.95);
            border: 1px solid var(--accent-color);
            box-shadow: 0 10px 20px rgba(0,0,0,0.4);
            font-size: 0.9rem;
            font-style: italic;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Data Engineering Portfolio</h1>
            <p style="color: var(--text-muted);">Live pripojenie na TiDB Cloud via PHP 8.4</p>
        </header>

        <div class="status-bar">
            <div class="stat-card">
                <span class="stat-value"><?= $total_projects ?></span>
                <span class="stat-label">Projekty</span>
            </div>
            <div class="stat-card">
                <span class="stat-value"><?= $latest_year ?></span>
                <span class="stat-label">Posledný update</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">Active</span>
                <span class="stat-label">Database Status</span>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Názov projektu</th>
                        <th>Použitá technológia</th>
                        <th>Rok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($all_projects as $row): ?>
                    <tr>
                        <td>
                            <div class="tooltip">
                                <div class="project-name">
                                    <?= htmlspecialchars($row['nazov_projektu']) ?>
                                    <span class="info-icon">i</span>
                                </div>
                                <span class="tooltiptext"><?= htmlspecialchars($row['popis']) ?></span>
                            </div>
                        </td>
                        <td><span class="tech-badge"><?= htmlspecialchars($row['technologia']) ?></span></td>
                        <td style="color: var(--text-muted);"><?= htmlspecialchars($row['rok_vytvorenia']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

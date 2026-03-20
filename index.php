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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0F172A;
            --card-bg: #1E293B;
            --accent-color: #818CF8;
            --text-main: #E2E8F0;
            --text-muted: #94A3B8;
            --hover-row: #2D3748;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-color); 
            color: var(--text-main); 
            padding: 60px 20px; 
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }

        .container { 
            max-width: 1000px; 
            margin: auto; 
            background-color: var(--card-bg); 
            padding: 40px; 
            border-radius: 16px; 
            border: 1px solid #334155;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }

        h1 { 
            font-family: 'Poppins', sans-serif; 
            font-weight: 700;
            font-size: 2.5rem;
            color: #FFFFFF;
            text-align: center; 
            margin: 0 0 30px 0;
            letter-spacing: -1px;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
        }

        th { 
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            color: var(--accent-color);
            padding: 15px 20px; 
            border-bottom: 2px solid #334155; 
            text-align: left; 
        }

        td { 
            padding: 20px; 
            border-bottom: 1px solid #334155; 
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: var(--hover-row); transition: background-color 0.2s ease; }

        .project-name {
            font-weight: 600;
            color: #FFFFFF;
            font-size: 1.1rem;
        }

        .project-year {
            color: var(--text-muted);
        }

        .tooltip {
            position: relative;
            cursor: help;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 300px;
            background-color: #1F2937;
            color: var(--text-main);
            text-align: left;
            border-radius: 8px;
            padding: 15px;
            position: absolute;
            z-index: 10;
            bottom: 130%;
            left: 50%;
            margin-left: -150px;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: translateY(10px);
            border: 1px solid var(--accent-color);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            font-size: 0.9rem;
            line-height: 1.5;
            font-style: italic;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: var(--accent-color) transparent transparent transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
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
                    <th>Rok vytvorenia</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <div class="tooltip">
                            <span class="project-name"><?= htmlspecialchars($row['nazov_projektu']) ?></span>
                            <span class="tooltiptext"><?= htmlspecialchars($row['popis']) ?></span>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($row['technologia']) ?></td>
                    <td class="project-year"><?= htmlspecialchars($row['rok_vytvorenia']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$current_title = "JV portfolio";
if(isset($_GET['year']) && $_GET['year'] != "") {
    $current_title = "JV portfolio | " . htmlspecialchars($_GET['year']);
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $current_title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@700&display=swap" rel="stylesheet">
    <style>
        :root { 
            --bg: #0D0F14; 
            --bg-gradient: radial-gradient(circle at top center, #1C1F26 0%, #0D0F14 100%);
            --card: #161B22; 
            --card-hover: #1C2128;
            --accent: #8B5CF6; 
            --text: #F8FAFC; 
            --muted: #94A3B8;
            --success: #10B981;
            --border: #30363D;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg);
            background-image: var(--bg-gradient);
            background-attachment: fixed;
            color: var(--text); 
            margin: 0; padding: 20px; min-height: 100vh;
        }
        .container { max-width: 1000px; margin: auto; }
        header { text-align: center; margin-bottom: 50px; padding-top: 20px; }
        h1 { font-family: 'Poppins', sans-serif; font-size: 2.8rem; letter-spacing: -1px; margin: 0; background: linear-gradient(to right, #FFF, #8B5CF6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .status-bar { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: var(--card); padding: 25px; border-radius: 20px; border: 1px solid var(--border); text-align: center; }
        .stat-v { display: block; font-size: 1.8rem; font-weight: 700; color: #FFF; }
        .stat-l { font-size: 0.7rem; text-transform: uppercase; color: var(--muted); letter-spacing: 2px; margin-top: 8px; display: block; }
        
        .filters { display: flex; justify-content: center; gap: 12px; margin-bottom: 35px; flex-wrap: wrap; }
        .filter-btn { background: var(--card); border: 1px solid var(--border); color: var(--muted); padding: 10px 22px; border-radius: 12px; cursor: pointer; transition: 0.3s; font-size: 0.9rem; white-space: nowrap; }
        .filter-btn.active { background: var(--accent); color: #FFF; border-color: var(--accent); box-shadow: 0 0 15px rgba(139, 92, 246, 0.3); }

        .table-wrapper { position: relative; overflow: visible; min-height: 100px; }
        table { width: 100%; border-collapse: separate; border-spacing: 0; background: var(--card); border-radius: 24px; border: 1px solid var(--border); overflow: visible; }
        th { background: rgba(0,0,0,0.2); color: var(--accent); padding: 22px; text-align: left; font-size: 0.75rem; text-transform: uppercase; border-bottom: 1px solid var(--border); }
        th:first-child { border-top-left-radius: 24px; }
        th:last-child { border-top-right-radius: 24px; }
        tr:first-child th { border-top: 1px solid var(--border); }
        td { padding: 20px; border-bottom: 1px solid var(--border); position: relative; }
        tr:hover td { background: var(--card-hover); }
        tr:last-child td { border-bottom: none; }
        tr:last-child td:first-child { border-bottom-left-radius: 24px; }
        tr:last-child td:last-child { border-bottom-right-radius: 24px; }

        .tooltip { position: relative; display: inline-flex; align-items: center; color: #FFF; font-weight: 500; }
        .info-icon { width: 16px; height: 16px; margin-left: 10px; color: var(--accent); flex-shrink: 0; cursor: help; }
        .tooltip .tt { visibility: hidden; width: 280px; background: #1C2128; border: 1px solid var(--accent); color: var(--muted); padding: 15px; border-radius: 12px; position: absolute; bottom: 130%; left: 0; opacity: 0; transition: 0.2s; z-index: 10000; font-size: 0.85rem; font-style: italic; box-shadow: 0 10px 30px rgba(0,0,0,0.5); pointer-events: none; }
        .info-icon:hover ~ .tt { visibility: visible; opacity: 1; transform: translateY(-5px); }
        
        .badge { background: rgba(139, 92, 246, 0.1); color: #C084FC; padding: 6px 14px; border-radius: 10px; font-size: 0.75rem; font-weight: 600; border: 1px solid rgba(139, 92, 246, 0.2); flex-shrink: 0; }
        #loader-overlay { display: none; position: absolute; inset: 0; background: rgba(13, 15, 20, 0.7); backdrop-filter: blur(4px); z-index: 20; justify-content: center; align-items: center; color: var(--accent); font-weight: 600; border-radius: 24px; }
        .mobile-cards { display: none; }

        @media (max-width: 600px) {
            .status-bar { grid-template-areas: "a b" "c c"; grid-template-columns: 1fr 1fr; }
            .stat-card:nth-child(1) { grid-area: a; }
            .stat-card:nth-child(2) { grid-area: b; }
            .stat-card:nth-child(3) { grid-area: c; max-width: 200px; margin: auto; width: 100%; }
            .filters { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; padding: 0 5px; }
            .filter-btn { padding: 10px 5px; text-align: center; font-size: 0.8rem; }
            .desktop-table { display: none; }
            .mobile-cards { display: flex; flex-direction: column; gap: 15px; padding: 20px; }
            .m-card { background: var(--card); border: 1px solid var(--border); padding: 20px; border-radius: 18px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1 id="main-title"><?php echo $current_title; ?></h1>
            <p style="color: var(--muted); letter-spacing: 1px;">DBeaver • TiDB Cloud • PHP • HTML/CSS • Railway</p>
        </header>
        <section class="status-bar">
            <div class="stat-card"><span class="stat-v" id="stat-count">0</span><span class="stat-l">Projekty</span></div>
            <div class="stat-card"><span class="stat-v" id="stat-year">-</span><span class="stat-l">Aktualizace</span></div>
            <div class="stat-card"><span class="stat-v" id="stat-db">...</span><span class="stat-l">DB Status</span></div>
        </section>
        <nav class="filters" id="filter-container">
            <button class="filter-btn active" data-year="" onclick="loadData('')">Vše</button>
        </nav>
        <div class="table-wrapper">
            <div id="loader-overlay">Načítávám data...</div>
            <table class="desktop-table">
                <thead><tr><th>Projekt</th><th>Stack</th><th>Rok</th></tr></thead>
                <tbody id="t-body"></tbody>
            </table>
            <div id="m-body" class="mobile-cards"></div>
        </div>
    </div>
    <script>
        const STRINGS = {
            ALL_PROJECTS: 'Vše',
            SYNCING: 'Načítávám data...',
            CONNECTED: 'Connected',
            OFFLINE: 'Offline',
            NO_PROJECTS: 'No projects found',
            ERROR_LOADING: 'Failed to load projects. Please try again.'
        };

        const TIMEOUT = 10000;
        let isLoading = false;

        function sanitizeYear(year) {
            if (!year) return '';
            return String(year).replace(/[^0-9]/g, '');
        }

        function fetchWithTimeout(url, timeout = TIMEOUT) {
            return Promise.race([
                fetch(url),
                new Promise((_, reject) =>
                    setTimeout(() => reject(new Error('Request timeout')), timeout)
                )
            ]);
        }

        function setFiltersDisabled(disabled) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => {
                btn.disabled = disabled;
                btn.style.opacity = disabled ? '0.5' : '1';
                btn.style.cursor = disabled ? 'not-allowed' : 'pointer';
            });
        }

        async function loadData(year) {
            if (isLoading) return;
            isLoading = true;
            setFiltersDisabled(true);

            const loader = document.getElementById('loader-overlay');
            const tBody = document.getElementById('t-body');
            const mBody = document.getElementById('m-body');
            const filterContainer = document.getElementById('filter-container');
            const mainTitle = document.getElementById('main-title');

            year = sanitizeYear(year);
            loader.style.display = 'flex';
            loader.textContent = STRINGS.SYNCING;

            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + (year ? '?year=' + year : '');
            window.history.pushState({path: newUrl}, '', newUrl);
            const titleText = year ? `JV portfolio | ${year}` : 'JV portfolio';
            document.title = titleText;
            mainTitle.textContent = titleText;

            try {
                const res = await fetchWithTimeout(`api.php?year=${year}`);
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                const result = await res.json();

                document.getElementById('stat-count').textContent = result.count || 0;
                document.getElementById('stat-year').textContent = result.latest_year || '-';

                const dbStat = document.getElementById('stat-db');
                const isConnected = result.db_connected;
                dbStat.textContent = isConnected ? STRINGS.CONNECTED : STRINGS.OFFLINE;
                dbStat.style.color = isConnected ? 'var(--success)' : '#EF4444';
                dbStat.setAttribute('aria-label', `Database status: ${isConnected ? 'connected' : 'offline'}`);

                let filterHtml = `<button class="filter-btn ${year === '' ? 'active' : ''}" data-year="">` +
                    `${STRINGS.ALL_PROJECTS}</button>`;
                if (result.available_years) {
                    result.available_years.forEach(y => {
                        filterHtml += `<button class="filter-btn ${String(year) === String(y) ? 'active' : ''}" ` +
                            `data-year="${y}">${y}</button>`;
                    });
                }
                filterContainer.innerHTML = filterHtml;

                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.addEventListener('click', () => loadData(btn.dataset.year));
                });

                let tHtml = '';
                let mHtml = '';
                if (result.data && result.data.length > 0) {
                    result.data.forEach(p => {
                        tHtml += `<tr><td><div class="tooltip">${p.nazov_projektu}<svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span class="tt">${p.popis}</span></div></td><td><span class="badge">${p.technologia}</span></td><td style="color: var(--muted)">${p.rok_vytvorenia}</td></tr>`;
                        mHtml += `<div class="m-card"><strong style="color:#FFF">${p.nazov_projektu}</strong><p style="color:var(--muted); font-size:0.85rem; margin:10px 0; font-style:italic;">${p.popis}</p><span class="badge">${p.technologia}</span></div>`;
                    });
                } else {
                    tHtml = `<tr><td colspan="3" style="text-align:center; color:var(--muted)">${STRINGS.NO_PROJECTS}</td></tr>`;
                    mHtml = `<div style="text-align:center; color:var(--muted); padding:40px 20px">${STRINGS.NO_PROJECTS}</div>`;
                }
                tBody.innerHTML = tHtml;
                mBody.innerHTML = mHtml;

            } catch (e) {
                console.error('Error loading data:', e);
                const errorMsg = `<tr><td colspan="3" style="text-align:center; color:#EF4444">${STRINGS.ERROR_LOADING}</td></tr>`;
                tBody.innerHTML = errorMsg;
                mBody.innerHTML = `<div style="text-align:center; color:#EF4444; padding:40px 20px">${STRINGS.ERROR_LOADING}</div>`;
            } finally {
                loader.style.display = 'none';
                isLoading = false;
                setFiltersDisabled(false);
            }
        }

        const urlParams = new URLSearchParams(window.location.search);
        loadData(urlParams.get('year') || '');
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barkosaurus SQL Portfolio</title>
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
            margin: 0; 
            padding: 20px; 
            min-height: 100vh;
        }
        .container { max-width: 1000px; margin: auto; }
        header { text-align: center; margin-bottom: 50px; padding-top: 20px; }
        h1 { font-family: 'Poppins', sans-serif; font-size: 2.8rem; letter-spacing: -1px; margin: 0; background: linear-gradient(to right, #FFF, #8B5CF6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .status-bar { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 20px; 
            margin-bottom: 40px; 
        }
        .stat-card { 
            background: var(--card); 
            padding: 25px; 
            border-radius: 20px; 
            border: 1px solid var(--border); 
            text-align: center; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-5px); border-color: var(--accent); }
        .stat-v { display: block; font-size: 1.8rem; font-weight: 700; color: #FFF; }
        .stat-l { font-size: 0.7rem; text-transform: uppercase; color: var(--muted); letter-spacing: 2px; margin-top: 8px; display: block; }
        .filters { display: flex; justify-content: center; gap: 12px; margin-bottom: 35px; }
        .filter-btn { background: var(--card); border: 1px solid var(--border); color: var(--muted); padding: 12px 28px; border-radius: 14px; cursor: pointer; transition: 0.3s; font-weight: 500; }
        .filter-btn:hover { color: #FFF; border-color: var(--accent); }
        .filter-btn.active { background: var(--accent); color: #FFF; border-color: var(--accent); box-shadow: 0 0 20px rgba(139, 92, 246, 0.3); }
        .table-wrapper { 
            background: var(--card); 
            border-radius: 24px; 
            border: 1px solid var(--border); 
            overflow: hidden; 
            position: relative;
            backdrop-filter: blur(10px);
        }
        table { width: 100%; border-collapse: collapse; }
        th { background: rgba(0,0,0,0.2); color: var(--accent); padding: 22px; text-align: left; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 1px solid var(--border); }
        td { padding: 20px; border-bottom: 1px solid var(--border); font-size: 0.95rem; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--card-hover); }
        .info-icon { width: 16px; height: 16px; vertical-align: middle; margin-left: 8px; color: var(--accent); opacity: 0.7; cursor: help; }
        .tooltip { position: relative; display: inline-flex; align-items: center; color: #FFF; font-weight: 500; }
        .tooltip .tt { visibility: hidden; width: 280px; background: #1C2128; border: 1px solid var(--accent); color: var(--muted); padding: 15px; border-radius: 12px; position: absolute; bottom: 140%; left: 0; opacity: 0; transition: 0.3s; z-index: 10; font-size: 0.85rem; line-height: 1.5; font-weight: 400; box-shadow: 0 15px 35px rgba(0,0,0,0.5); }
        .tooltip:hover .tt { visibility: visible; opacity: 1; transform: translateY(-5px); }
        .badge { background: rgba(139, 92, 246, 0.1); color: #C084FC; padding: 6px 14px; border-radius: 10px; font-size: 0.75rem; font-weight: 600; border: 1px solid rgba(139, 92, 246, 0.2); }
        #loader { display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: var(--accent); font-weight: 600; z-index: 5; }
        .loading-state { opacity: 0.1; pointer-events: none; filter: blur(4px); }
        @media (max-width: 600px) {
            .status-bar { 
                grid-template-areas: "a b" "c c";
                grid-template-columns: 1fr 1fr;
            }
            .stat-card:nth-child(1) { grid-area: a; }
            .stat-card:nth-child(2) { grid-area: b; }
            .stat-card:nth-child(3) { grid-area: c; max-width: 200px; margin: auto; width: 100%; }
            .desktop-table { display: none; }
            .mobile-cards { display: flex; flex-direction: column; gap: 15px; padding: 20px; }
            .m-card { background: rgba(255,255,255,0.02); border: 1px solid var(--border); padding: 20px; border-radius: 18px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Barkosaurus SQL Portfolio</h1>
            <p style="color: var(--muted); letter-spacing: 1px;">Full-stack PHP Engine • TiDB Cloud</p>
        </header>
        <section class="status-bar">
            <div class="stat-card"><span class="stat-v" id="stat-count">0</span><span class="stat-l">Projekty</span></div>
            <div class="stat-card"><span class="stat-v" id="stat-year">-</span><span class="stat-l">Update</span></div>
            <div class="stat-card"><span class="stat-v" id="stat-db" style="color: var(--muted)">...</span><span class="stat-l">DB Status</span></div>
        </section>
        <nav class="filters">
            <button class="filter-btn active" data-year="" onclick="loadData('')">Všetko</button>
            <button class="filter-btn" data-year="2025" onclick="loadData('2025')">2025</button>
            <button class="filter-btn" data-year="2026" onclick="loadData('2026')">2026</button>
        </nav>
        <div class="table-wrapper">
            <div id="loader">Syncing Data...</div>
            <table class="desktop-table" id="d-table">
                <thead><tr><th>Projekt</th><th>Stack</th><th>Rok</th></tr></thead>
                <tbody id="t-body"></tbody>
            </table>
            <div id="m-body" class="mobile-cards"></div>
        </div>
    </div>
    <script>
        async function loadData(year) {
            const loader = document.getElementById('loader');
            const tBody = document.getElementById('t-body');
            const mBody = document.getElementById('m-body');
            const table = document.getElementById('d-table');
            loader.style.display = 'block';
            table.classList.add('loading-state');
            mBody.classList.add('loading-state');
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-year') === String(year));
            });
            try {
                const res = await fetch(`api.php?year=${year}`);
                const result = await res.json();
                document.getElementById('stat-count').textContent = result.count || 0;
                document.getElementById('stat-year').textContent = result.latest_year || '-';
                const dbStat = document.getElementById('stat-db');
                if(result.db_connected) {
                    dbStat.textContent = 'Connected';
                    dbStat.style.color = 'var(--success)';
                } else {
                    dbStat.textContent = 'Offline';
                    dbStat.style.color = '#EF4444';
                }
                let tHtml = '';
                let mHtml = '';
                if(result.data) {
                    result.data.forEach(p => {
                        tHtml += `<tr>
                            <td>
                                <div class="tooltip">
                                    ${p.nazov_projektu}
                                    <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="tt">${p.popis}</span>
                                </div>
                            </td>
                            <td><span class="badge">${p.technologia}</span></td>
                            <td style="color: var(--muted); font-weight: 500;">${p.rok_vytvorenia}</td>
                        </tr>`;
                        mHtml += `<div class="m-card">
                            <strong style="color:#FFF; font-size:1.1rem;">${p.nazov_projektu}</strong>
                            <p style="color:var(--muted); font-size:0.85rem; margin:12px 0; line-height:1.4;">${p.popis}</p>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span class="badge">${p.technologia}</span>
                                <span style="color:var(--muted); font-size:0.8rem;">${p.rok_vytvorenia}</span>
                            </div>
                        </div>`;
                    });
                }
                tBody.innerHTML = tHtml;
                mBody.innerHTML = mHtml;
            } catch (e) { console.error(e); } finally {
                loader.style.display = 'none';
                table.classList.remove('loading-state');
                mBody.classList.remove('loading-state');
            }
        }
        loadData('');
    </script>
</body>
</html>

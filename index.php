<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barkosaurus SQL Portfolio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@700&display=swap" rel="stylesheet">
    <style>
        :root { 
            --bg: #0B0E14; 
            --card: #161B22; 
            --accent: #7C3AED; 
            --text: #F8FAFC; 
            --muted: #94A3B8;
            --success: #10B981;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; }
        
        header { text-align: center; margin-bottom: 40px; }
        h1 { font-family: 'Poppins', sans-serif; font-size: 2.5rem; color: #FFF; margin: 0; }

        .status-bar { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 15px; margin-bottom: 40px; }
        .stat-card { background: var(--card); padding: 20px; border-radius: 16px; border: 1px solid #2D333B; text-align: center; }
        .stat-v { display: block; font-size: 1.4rem; font-weight: 700; color: #FFF; }
        .stat-l { font-size: 0.75rem; text-transform: uppercase; color: var(--muted); letter-spacing: 1px; margin-top: 5px; display: block; }

        .filters { display: flex; justify-content: center; gap: 10px; margin-bottom: 30px; flex-wrap: wrap; }
        .filter-btn { background: var(--card); border: 1px solid #2D333B; color: var(--text); padding: 10px 24px; border-radius: 25px; cursor: pointer; transition: 0.3s; font-size: 0.9rem; }
        .filter-btn:hover { border-color: var(--accent); }
        .filter-btn.active { background: var(--accent); color: #FFF; border-color: var(--accent); box-shadow: 0 0 15px rgba(124, 58, 237, 0.4); }

        .table-wrapper { background: var(--card); border-radius: 16px; border: 1px solid #2D333B; overflow: hidden; position: relative; min-height: 200px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #0D1117; color: var(--accent); padding: 20px; text-align: left; font-size: 0.85rem; text-transform: uppercase; }
        td { padding: 20px; border-bottom: 1px solid #21262D; }

        #loader { display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: var(--accent); font-weight: 600; z-index: 5; }
        .loading-state { opacity: 0.2; pointer-events: none; }

        .badge { background: rgba(124, 58, 237, 0.15); color: #A78BFA; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; border: 1px solid rgba(124, 58, 237, 0.3); }
        
        .tooltip { position: relative; cursor: help; color: #FFF; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .tooltip .tt { visibility: hidden; width: 260px; background: #161B22; border: 1px solid var(--accent); color: var(--text); padding: 12px; border-radius: 10px; position: absolute; bottom: 130%; left: 0; opacity: 0; transition: 0.3s; z-index: 10; font-weight: 400; font-size: 0.85rem; font-style: italic; box-shadow: 0 10px 20px rgba(0,0,0,0.5); }
        .tooltip:hover .tt { visibility: visible; opacity: 1; transform: translateY(-5px); }

        .mobile-cards { display: none; flex-direction: column; gap: 15px; padding: 15px; }
        .m-card { background: var(--card); border: 1px solid #2D333B; padding: 20px; border-radius: 16px; }

        @media (max-width: 600px) {
            .desktop-table { display: none; }
            .mobile-cards { display: flex; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Barkosaurus SQL Portfolio</h1>
            <p style="color: var(--muted);">Full-stack PHP with TiDB Cloud Engine</p>
        </header>

        <section class="status-bar">
            <div class="stat-card"><span class="stat-v" id="stat-count">0</span><span class="stat-l">Projekty</span></div>
            <div class="stat-card"><span class="stat-v" id="stat-year">-</span><span class="stat-l">Update</span></div>
            <div class="stat-card"><span class="stat-v" id="stat-db" style="color: var(--muted)">Offline</span><span class="stat-l">DB Status</span></div>
        </section>

        <nav class="filters">
            <button class="filter-btn active" data-year="" onclick="loadData('')">Všetko</button>
            <button class="filter-btn" data-year="2025" onclick="loadData('2025')">2025</button>
            <button class="filter-btn" data-year="2026" onclick="loadData('2026')">2026</button>
        </nav>

        <div class="table-wrapper">
            <div id="loader">Načítavam dáta...</div>
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
                            <td><div class="tooltip">${p.nazov_projektu} ⓘ<span class="tt">${p.popis}</span></div></td>
                            <td><span class="badge">${p.technologia}</span></td>
                            <td style="color: var(--muted)">${p.rok_vytvorenia}</td>
                        </tr>`;
                        mHtml += `<div class="m-card">
                            <strong style="color:#FFF">${p.nazov_projektu}</strong><p style="color:var(--muted); font-size:0.9rem; margin:10px 0">${p.popis}</p>
                            <span class="badge">${p.technologia}</span> <small style="float:right; color:var(--muted)">${p.rok_vytvorenia}</small>
                        </div>`;
                    });
                }

                tBody.innerHTML = tHtml;
                mBody.innerHTML = mHtml;
            } catch (e) {
                console.error(e);
            } finally {
                loader.style.display = 'none';
                table.classList.remove('loading-state');
                mBody.classList.remove('loading-state');
            }
        }
        loadData('');
    </script>
</body>
</html>

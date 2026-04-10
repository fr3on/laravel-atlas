<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Atlas — {{ ucfirst($panel) }}</title>
    <style>
        :root {
            --bg: #faf9f7; --bg2: #f1efe8; --bg3: #fff;
            --text: #1a1a18; --text2: #5f5e5a; --text3: #888780;
            --border: rgba(0,0,0,0.10); --border2: rgba(0,0,0,0.07);
            --red-bg: #fcebeb; --red-text: #a32d2d;
            --amber-bg: #faeeda; --amber-text: #854f0b;
            --green-bg: #eaf3de; --green-text: #3b6d11;
            --blue-bg: #e6f1fb; --blue-text: #185fa5;
            --teal-bg: #e1f5ee; --teal-text: #0f6e56;
            --purple-bg: #eeedfe; --purple-text: #534ab7;
            --accent: #0f6e56; --accent-light: #e1f5ee;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #1c1c1a; --bg2: #242422; --bg3: #2c2c2a;
                --text: #e8e6dc; --text2: #a8a69e; --text3: #6e6d68;
                --border: rgba(255,255,255,0.10); --border2: rgba(255,255,255,0.06);
                --red-bg: #501313; --red-text: #f7c1c1;
                --amber-bg: #412402; --amber-text: #fac775;
                --green-bg: #173404; --green-text: #c0dd97;
                --blue-bg: #042c53; --blue-text: #b5d4f4;
                --teal-bg: #04342c; --teal-text: #9fe1cb;
                --purple-bg: #26215c; --purple-text: #cecbf6;
                --accent: #1d9e75; --accent-light: #04342c;
            }
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: var(--bg); color: var(--text);
            font-size: 15px; line-height: 1.7;
            min-height: 100vh; overflow-x: hidden;
        }

        .container {
            max-width: 1100px; margin: 0 auto;
            padding: 40px 20px; transition: filter 0.3s;
        }

        @media (max-width: 768px) {
            .container { padding: 20px 12px; }
            .header h1 { font-size: 22px !important; }
        }

        /* Mockup Header */
        .mockup-header {
            background: var(--bg3); border: 0.5px solid var(--border);
            border-bottom: none; border-radius: 12px 12px 0 0;
            padding: 12px 16px; display: flex; align-items: center; gap: 8px;
        }

        .dot { width: 10px; height: 10px; border-radius: 50%; opacity: 0.6; }
        .dot-red { background: #ff5f56; }
        .dot-amber { background: #ffbd2e; }
        .dot-green { background: #27c93f; }

        .mockup-title {
            font-size: 12px; font-weight: 600; color: var(--text2);
            margin-left: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .main-card {
            background: var(--bg3); border: 0.5px solid var(--border);
            border-radius: 0 0 12px 12px; overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1);
        }

        .tabs {
            display: flex; background: var(--bg2); border-bottom: 0.5px solid var(--border);
            padding: 0 16px; overflow-x: auto; scrollbar-width: none;
        }
        .tabs::-webkit-scrollbar { display: none; }

        .tab {
            padding: 14px 18px; font-size: 13px; font-weight: 600;
            color: var(--text2); text-decoration: none;
            border-bottom: 2px solid transparent; transition: all 0.2s;
            display: flex; align-items: center; gap: 6px; white-space: nowrap;
        }
        .tab-count { font-size: 11px; opacity: 0.5; font-weight: 500; }
        .tab:hover { color: var(--text); }
        .tab.active { color: var(--accent); border-bottom-color: var(--accent); }

        .toolbar {
            padding: 24px 28px; background: var(--bg3); border-bottom: 0.5px solid var(--border2);
        }

        .search {
            width: 100%; padding: 12px 18px; border-radius: 10px;
            border: 0.5px solid var(--border); background: var(--bg);
            color: var(--text); font-size: 14px; outline: none;
        }
        .search:focus { border-color: var(--accent); box-shadow: 0 0 0 4px var(--accent-light); }

        .content { padding: 20px 24px 32px; }
        @media (max-width: 640px) { .content { padding: 12px; } }

        /* Original Proposal Style Rows */
        .list-row {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 6px; margin-bottom: 4px;
            background: var(--bg3); border: 0.5px solid var(--border2);
            transition: all 0.2s; cursor: pointer;
        }
        .list-row:hover {
            border-color: var(--border); background: var(--bg);
            transform: translateX(4px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .badge {
            font-size: 10px; font-weight: 700; padding: 2px 6px;
            border-radius: 3px; min-width: 36px; text-align: center;
            text-transform: uppercase;
        }
        .method-get { background: var(--green-bg); color: var(--green-text); }
        .method-post { background: var(--blue-bg); color: var(--blue-text); }
        .method-delete { background: var(--red-bg); color: var(--red-text); }
        .method-put, .method-patch { background: var(--amber-bg); color: var(--amber-text); }
        .status-applied { background: var(--green-bg); color: var(--green-text); }
        .status-pending { background: var(--blue-bg); color: var(--blue-text); }

        .path { font-family: monospace; font-size: 12px; flex: 1; color: var(--text); }
        .meta-text { font-size: 11px; color: var(--text3); }

        .tag {
            font-size: 10px; font-weight: 600; padding: 2px 7px;
            border-radius: 3px; background: var(--bg2); color: var(--text2);
            border: 0.5px solid var(--border2); margin-left: 4px;
        }
        .tag-accent { background: var(--teal-bg); color: var(--teal-text); border: none; }

        /* Inspector Drawer */
        .inspector {
            position: fixed; top: 0; right: -500px; width: 500px;
            height: 100vh; background: var(--bg3);
            box-shadow: -10px 0 40px rgba(0, 0, 0, 0.1);
            z-index: 1000; transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 0.5px solid var(--border); overflow-y: auto;
        }
        @media (max-width: 768px) { .inspector { width: 100%; right: -100%; } }
        .inspector.open { right: 0; }
        .inspector-header {
            padding: 24px; border-bottom: 0.5px solid var(--border);
            position: sticky; top: 0; background: var(--bg3); z-index: 10;
            display: flex; align-items: flex-start; justify-content: space-between;
        }
        .inspector-body { padding: 32px 24px; }
        .inspector-section { margin-bottom: 32px; }
        .inspector-label {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; color: var(--text3); margin-bottom: 8px;
        }
        .inspector-value {
            font-size: 14px; color: var(--text); border-radius: 8px;
            background: var(--bg2); padding: 12px 14px; word-break: break-all;
        }
        .path-link { color: var(--accent); text-decoration: none; font-weight: 500; display: block; }
        .path-link:hover { opacity: 0.7; text-decoration: underline; }

        .overlay {
            position: fixed; inset: 0; background: rgba(0, 0, 0, 0.2);
            z-index: 999; display: none; backdrop-filter: blur(2px);
        }
        .overlay.open { display: block; }

        /* Card Footer */
        .card-footer {
            padding: 16px 28px; background: var(--bg2); border-top: 0.5px solid var(--border2);
            display: flex; justify-content: space-between; align-items: center;
            font-size: 13px; color: var(--text2);
        }
        .action-btn { color: var(--accent); text-decoration: none; font-weight: 600; padding: 6px 12px; }
    </style>
</head>

<body>
    <div class="container" id="mainContainer">
        <div class="header" style="margin-bottom: 24px;">
            <h1 style="font-size: 28px; font-weight: 600; letter-spacing: -0.5px; opacity: 0.9;">Laravel Atlas</h1>
            <div style="font-family: monospace; font-size: 12px; opacity: 0.5;">{{ app()->version() }} · PHP {{ PHP_VERSION }}</div>
        </div>

        <div class="mockup-header">
            <div class="dot dot-red"></div><div class="dot dot-amber"></div><div class="dot dot-green"></div>
            <span class="mockup-title">{{ config('app.name') }} · {{ $panel }}</span>
        </div>

        <div class="main-card">
            <div class="tabs">
                @foreach($panels as $p)
                    <a href="{{ route('atlas.show', $p) }}" class="tab {{ $panel === $p ? 'active' : '' }}">
                        {{ ucfirst($p) }}
                        <span class="tab-count">{{ $stats[$p . '_count'] ?? 0 }}</span>
                    </a>
                @endforeach
            </div>

            <div class="toolbar">
                <input type="text" class="search" placeholder="Search {{ $total_items }} {{ $panel }}..." id="atlasSearch" value="{{ $search }}" autofocus>
            </div>

            <div class="content">
                @includeFirst(["atlas::panels.{$panel}", "atlas::panels.default"])
            </div>

                @if($data->hasPages())
                    <div style="display:flex; justify-content:center; gap:8px; margin-top:24px;">
                        <a href="{{ $data->previousPageUrl() }}" class="action-btn">Previous</a>
                        <span style="font-size:13px; color:var(--text3)">{{ $data->currentPage() }} / {{ $data->lastPage() }}</span>
                        <a href="{{ $data->nextPageUrl() }}" class="action-btn">Next</a>
                    </div>
                @endif
            </div>

            <div class="card-footer">
                <div>
                    @if($panel === 'routes')
                        <strong>{{ $stats['routes_count'] }}</strong> routes · <strong>{{ $stats['throttled_count'] }}</strong> throttled · <strong>{{ $stats['public_count'] }}</strong> public
                    @else
                        <strong>{{ $total_items }}</strong> items
                    @endif
                </div>
                <div class="actions">
                    <a href="{{ route('atlas.export', ['format' => 'json']) }}" class="action-btn">Export JSON</a>
                    ·
                    <a href="{{ route('atlas.export', ['format' => 'markdown']) }}" class="action-btn">Export Markdown</a>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay" onclick="closeInspector()"></div>
    <div class="inspector" id="inspector">
        <div class="inspector-header">
            <div>
                <h2 style="font-size: 20px; font-weight: 600;" id="inspTitle">Item Detail</h2>
                <div id="inspSubtitle" style="font-size: 14px; color: var(--text2); margin-top: 4px;"></div>
            </div>
            <div class="inspector-close" onclick="closeInspector()" style="cursor:pointer">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </div>
        </div>
        <div class="inspector-body" id="inspBody"></div>
    </div>

    <script>
        let searchTimer;
        document.getElementById('atlasSearch').addEventListener('input', function (e) {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                const url = new URL(window.location.href);
                url.searchParams.set('q', e.target.value);
                url.searchParams.delete('page');
                window.location.href = url.toString();
            }, 600);
        });

        function inspect(data, type) {
            document.getElementById('inspTitle').innerText = data.uri || data.name || data.event || data.command || data.title || data.key;
            document.getElementById('inspSubtitle').innerText = type.charAt(0).toUpperCase() + type.slice(1);
            let html = '';
            if (data.file) {
                html += `<div class="inspector-section"><div class="inspector-label">Source File (Click to Open)</div><div class="inspector-value"><a href="vscode://file${data.file}:${data.line || 1}" class="path-link">${data.file}</a></div></div>`;
            }
            if (type === 'models') {
                html += `<div class="inspector-section"><div class="inspector-label">Table</div><div class="inspector-value">${data.table}</div></div>`;
                if (data.relations.length > 0) {
                    html += `<div class="inspector-section"><div class="inspector-label">Relationships</div>${data.relations.map(r => `<div class="inspector-value" style="margin-bottom:8px"><strong>${r.name}</strong> (${r.type})</div>`).join('')}</div>`;
                }
            }
            if (type === 'config') {
                html += `<div class="inspector-section"><div class="inspector-label">Value</div><div class="inspector-value" style="background:var(--bg3); border:1px solid var(--border)">${data.value}</div></div>`;
            }
            if (data.middleware) {
                html += `<div class="inspector-section"><div class="inspector-label">Middleware</div><div style="display:flex; flex-wrap:wrap; gap:6px;">${data.middleware.map(mw => `<span class="tag" style="margin:0">${mw}</span>`).join('')}</div></div>`;
            }
            document.getElementById('inspBody').innerHTML = html;
            document.getElementById('inspector').classList.add('open');
            document.getElementById('overlay').classList.add('open');
            document.getElementById('mainContainer').style.filter = 'blur(4px)';
        }

        function closeInspector() {
            document.getElementById('inspector').classList.remove('open');
            document.getElementById('overlay').classList.remove('open');
            document.getElementById('mainContainer').style.filter = 'none';
        }
    </script>
</body>
</html>
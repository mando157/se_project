<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Space Management Dashboard</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* CSS Variables */
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --accent-blue: #38bdf8;
            --accent-green: #22c55e;
            --text-main: #f1f5f9;
            --text-dim: #94a3b8;
            --sidebar-width: 240px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* --- Sidebar Navigation --- */
        .sidebar {
            width: var(--sidebar-width);
            background: #020617;
            height: 100vh;
            position: fixed;
            left: 0;
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-blue);
            text-align: center;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-item {
            padding: 0.8rem 1rem;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-dim);
            text-decoration: none;
        }

        .nav-item.active, .nav-item:hover {
            background: rgba(56, 189, 248, 0.1);
            color: var(--accent-blue);
        }

        /* --- Main Content --- */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 2rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        .search-bar {
            background: var(--card-bg);
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            width: 320px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .search-bar input {
            background: none;
            border: none;
            color: white;
            outline: none;
            width: 100%;
            font-size: 0.9rem;
        }

        /* --- Dashboard Cards --- */
        .dashboard-grid {
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
        }

        .card {
            background: var(--card-bg);
            border-radius: 40px;
            padding: 2.5rem;
            display: flex;
            gap: 2.5rem;
            align-items: center;
            max-width: 950px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.03);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            transform: translateY(-8px);
        }

        .card-img {
            width: 40%;
            height: 280px;
            object-fit: cover;
            border-radius: 32px;
        }

        .card-info {
            flex: 1;
        }

        .space-id {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--accent-blue);
            text-shadow: 0 0 25px rgba(56, 189, 248, 0.4);
            margin-bottom: 0.5rem;
            font-family: monospace;
        }

        .tags {
            display: flex;
            gap: 12px;
            margin: 1.2rem 0;
        }

        .tags span {
            background: rgba(56, 189, 248, 0.1);
            color: var(--accent-blue);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .action-btn {
            background: var(--accent-blue);
            color: #020617;
            border: none;
            padding: 0.9rem 2.2rem;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 1.5rem;
            transition: 0.3s;
        }

        .action-btn:hover {
            background: #7dd3fc;
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
        }

        /* --- Toggle Switch --- */
        .control-panel {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 1.2rem;
            font-size: 0.9rem;
            color: var(--text-dim);
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }

        .switch input { display: none; }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #334155;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px; width: 18px;
            left: 4px; bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider { background-color: var(--accent-green); }
        input:checked + .slider:before { transform: translateX(24px); }

        /* --- Modal Window --- */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(2, 6, 23, 0.85);
            backdrop-filter: blur(8px);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: var(--card-bg);
            padding: 3rem;
            border-radius: 40px;
            width: 450px;
            text-align: center;
            position: relative;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 25px;
            cursor: pointer;
            font-size: 1.8rem;
            color: var(--text-dim);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; width: 100%; }
            .card { flex-direction: column; text-align: center; }
            .card-img { width: 100%; height: 220px; }
            .tags { justify-content: center; }
            .control-panel { justify-content: center; }
        }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <nav class="sidebar" id="sidebar">
        <div class="logo">URBAN CORE</div>
        <ul class="nav-links">
            <li class="nav-item active" onclick="setActive(this)">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </li>
            <li class="nav-item" onclick="setActive(this)">
                <i class="fa-solid fa-map"></i> Zone Mapping
            </li>
            <li class="nav-item" onclick="setActive(this)">
                <i class="fa-solid fa-city"></i> Smart Spaces
            </li>
            <li class="nav-item" onclick="setActive(this)">
                <i class="fa-solid fa-chart-simple"></i> Analytics
            </li>
            <li class="nav-item" onclick="setActive(this)">
                <i class="fa-solid fa-sliders"></i> Settings
            </li>
        </ul>
    </nav>

    <!-- Main Workspace -->
    <main class="main-content">
        <header>
            <div>
                <h1>Urban Management</h1>
                <p style="color: var(--text-dim);">Monitoring city zones in real-time</p>
            </div>
            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search areas, sensors...">
            </div>
        </header>

        <div class="dashboard-grid">
            <!-- Card 1 -->
            <div class="card">
                <img src="https://images.unsplash.com/photo-1449824913935-59a10b8d2000?auto=format&fit=crop&w=800" alt="CBD Area" class="card-img">
                <div class="card-info">
                    <div class="space-id">#102</div>
                    <h3>Central Business District</h3>
                    <div class="tags">
                        <span>High Traffic</span>
                        <span>Commercial</span>
                    </div>
                    <div class="control-panel">
                        <span>Smart Lighting:</span>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <button class="action-btn" onclick="openModal('Central Business District')">View Insights</button>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <img src="https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=800" alt="Residential Zone" class="card-img">
                <div class="card-info">
                    <div class="space-id">#085</div>
                    <h3>North Residential Zone</h3>
                    <div class="tags">
                        <span>Low Noise</span>
                        <span>Eco-Friendly</span>
                    </div>
                    <div class="control-panel">
                        <span>Auto Irrigation:</span>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <button class="action-btn" onclick="openModal('North Residential Zone')">View Insights</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Details -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Area Analytics</h2>
            <p style="margin: 1.5rem 0; color: var(--text-dim); line-height: 1.6;">
                Accessing live telemetry from <strong id="zoneName" style="color: var(--accent-blue);"></strong>. 
                Data includes power consumption, pedestrian density, and air quality metrics.
            </p>
            <button class="action-btn" onclick="closeModal()" style="width: 100%;">Close Terminal</button>
        </div>
    </div>

    <script>
        // Toggle Sidebar Active State
        function setActive(element) {
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            element.classList.add('active');
        }

        // Modal Controls
        const modal = document.getElementById("detailsModal");
        const zoneNameSpan = document.getElementById("zoneName");

        function openModal(zoneName) {
            zoneNameSpan.innerText = zoneName;
            modal.style.display = "flex";
        }

        function closeModal() {
            modal.style.display = "none";
        }

        // Close on Outside Click
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        // Sensor Toggle Notification
        document.querySelectorAll('.switch input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const status = this.checked ? "ON" : "OFF";
                console.log(`System Status: ${status}`);
            });
        });
    </script>
</body>
</html>
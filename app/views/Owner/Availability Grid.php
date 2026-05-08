<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Dashboard</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-color: #0a0a12;
            --sidebar-color: #11111a;
            --card-color: #191925;
            --primary-color: #8b7bff;
            --text-color: #ffffff;
            --secondary-text: #8d8d99;
            --border-color: #2b2b38;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        .dashboard {
            display: grid;
            grid-template-columns: 250px 1fr 300px;
            min-height: 100vh;
        }

        .sidebar {
            background-color: var(--sidebar-color);
            padding: 25px;
            border-right: 1px solid var(--border-color);
        }

        .main-content {
            padding: 30px;
        }

        .right-panel {
            padding: 25px;
            border-left: 1px solid var(--border-color);
        }

        .logo-section h2 {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .logo-section p {
            color: var(--secondary-text);
            font-size: 12px;
            margin-bottom: 40px;
        }

        .sidebar-nav ul {
            list-style: none;
        }

        .sidebar-nav li {
            padding: 14px 16px;
            margin-bottom: 10px;
            border-radius: 12px;
            cursor: pointer;
            color: var(--secondary-text);
        }

        .sidebar-nav .active {
            background: rgba(139, 123, 255, 0.15);
            color: var(--primary-color);
        }

        .add-space-btn,
        .release-btn,
        .bulk-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 12px;
            font-weight: bold;
        }

        .add-space-btn {
            margin-top: 40px;
            width: 100%;
            padding: 14px;
        }

        .release-btn {
            padding: 14px 18px;
        }

        .bulk-btn {
            margin-top: 25px;
            width: 100%;
            padding: 16px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
        }

        .topbar input {
            flex: 1;
            padding: 14px 18px;
            background: var(--card-color);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: white;
        }

        .top-links {
            display: flex;
            gap: 20px;
        }

        .top-links a {
            color: var(--secondary-text);
            text-decoration: none;
        }

        .section-header h1 {
            font-size: 42px;
            margin-bottom: 10px;
        }

        .section-header p {
            color: var(--secondary-text);
            margin-bottom: 30px;
        }

        .grid-container,
        .card,
        .quick-actions,
        .active-space-card,
        .chart-card {
            background: var(--card-color);
            padding: 20px;
            border-radius: 18px;
        }

        .stats-cards {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .card h3 {
            margin-top: 10px;
            font-size: 28px;
        }

        .grid-topbar,
        .view-modes {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .view-modes button {
            padding: 10px 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            background: #111;
            color: white;
        }

        .active-mode {
            background: var(--primary-color);
        }

        .table-row {
            display: grid;
            grid-template-columns: 120px repeat(7, 1fr);
            gap: 10px;
            align-items: center;
            margin-bottom: 12px;
        }

        .table-header {
            color: var(--secondary-text);
            font-size: 13px;
            margin-bottom: 20px;
        }

        .slot {
            height: 45px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            cursor: pointer;
        }

        .slot.active {
            background: rgba(139, 123, 255, 0.18);
            border-color: var(--primary-color);
        }

        .slot.booked {
            background: rgba(0, 200, 255, 0.15);
        }

        .slot.blocked {
            background: rgba(255, 255, 255, 0.05);
        }

        .toggle-item {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .space-image {
            margin-top: 20px;
            height: 160px;
            border-radius: 16px;
            background: linear-gradient(135deg, #2a2a38, #171720);
        }

        .chart-card canvas {
            margin-top: 20px;
            width: 100% !important;
            max-height: 220px;
        }

        @media (max-width: 1100px) {
            .dashboard {
                grid-template-columns: 220px 1fr;
            }

            .right-panel {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .topbar {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>

<body>

    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo-section">
                <h2>UrbanKinetic</h2>
                <p>Space Owner</p>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li>Dashboard</li>
                    <li>My Spaces</li>
                    <li class="active">Availability</li>
                    <li>Bookings</li>
                    <li>Earnings</li>
                </ul>
            </nav>

            <button class="add-space-btn">Add New Space</button>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <input type="text" placeholder="Search commands or slots...">

                <div class="top-links">
                    <a href="#">Analytics</a>
                    <a href="#">Reports</a>
                    <a href="#">Live Map</a>
                </div>

                <button class="release-btn">Release All Slots</button>
            </header>

            <section class="availability-section">
                <div class="section-header">
                    <h1>Availability Grid</h1>
                    <p>Manage your commercial parking assets in real-time.</p>
                </div>

                <div class="grid-container">
                    <div class="grid-topbar">
                        <button>&lt;</button>
                        <span>Oct 24 - Oct 30, 2023</span>
                        <button>&gt;</button>
                    </div>

                    <div class="view-modes">
                        <button class="active-mode">Weekly</button>
                        <button>Monthly</button>
                    </div>

                    <div class="availability-table">
                        <div class="table-row table-header">
                            <span>Space / Time</span>
                            <span>08:00</span>
                            <span>10:00</span>
                            <span>12:00</span>
                            <span>14:00</span>
                            <span>16:00</span>
                            <span>18:00</span>
                            <span>20:00</span>
                        </div>

                        <div class="table-row">
                            <span>P1-A001</span>
                            <div class="slot active"></div>
                            <div class="slot booked"></div>
                            <div class="slot booked"></div>
                            <div class="slot active"></div>
                            <div class="slot active"></div>
                            <div class="slot blocked"></div>
                            <div class="slot blocked"></div>
                        </div>

                        <div class="table-row">
                            <span>P1-A002</span>
                            <div class="slot active"></div>
                            <div class="slot active"></div>
                            <div class="slot active"></div>
                            <div class="slot active"></div>
                            <div class="slot active"></div>
                            <div class="slot active"></div>
                            <div class="slot active"></div>
                        </div>
                    </div>

                    <button class="bulk-btn">Bulk Update Availability</button>
                </div>
            </section>
        </main>

        <aside class="right-panel">
            <div class="stats-cards">
                <div class="card available-card">
                    <span>Available</span>
                    <h3>142</h3>
                </div>

                <div class="card booked-card">
                    <span>Booked</span>
                    <h3>58</h3>
                </div>
            </div>

            <div class="chart-card">
                <h3>Occupancy Analytics</h3>
                <canvas id="occupancyChart"></canvas>
            </div>

            <div class="quick-actions">
                <h3>Quick Batch Actions</h3>
                <p>Apply changes instantly.</p>

                <div class="toggle-item">
                    <span>Weekend Surge Mode</span>
                    <input type="checkbox">
                </div>
            </div>

            <div class="active-space-card">
                <h4>Current Active Space</h4>
                <h2>P1-A001</h2>
                <div class="space-image"></div>
            </div>
        </aside>
    </div>

    <script>
        const slots = document.querySelectorAll(".slot");
        const availableCount = document.querySelector(".available-card h3");
        const bookedCount = document.querySelector(".booked-card h3");
        const bulkButton = document.querySelector(".bulk-btn");
        const releaseButton = document.querySelector(".release-btn");
        const activeSpaceTitle = document.querySelector(".active-space-card h2");

        function updateCounts() {
            availableCount.textContent = document.querySelectorAll(".slot.active").length;
            bookedCount.textContent = document.querySelectorAll(".slot.booked").length;
        }

        slots.forEach((slot) => {
            slot.addEventListener("click", () => {
                if (slot.classList.contains("active")) {
                    slot.classList.replace("active", "booked");
                } else if (slot.classList.contains("booked")) {
                    slot.classList.replace("booked", "blocked");
                } else {
                    slot.classList.replace("blocked", "active");
                }

                const row = slot.parentElement;
                activeSpaceTitle.textContent = row.querySelector("span").textContent;

                updateCounts();
            });
        });

        bulkButton.addEventListener("click", () => {
            slots.forEach((slot) => {
                slot.classList.remove("booked", "blocked");
                slot.classList.add("active");
            });

            updateCounts();
        });

        releaseButton.addEventListener("click", () => {
            alert("All parking slots released successfully!");
        });

        new Chart(document.getElementById("occupancyChart"), {
            type: "line",
            data: {
                labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                datasets: [{
                    data: [65, 59, 80, 81, 56, 72, 90],
                    borderColor: "#8b7bff",
                    tension: 0.4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        updateCounts();
    </script>

</body>

</html>
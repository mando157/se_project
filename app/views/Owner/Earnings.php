<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Urban Kinetic Dashboard</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css">


    <style>
        :root {
            --card-color: #19191F;
            --text-color: #F6F2FA;
            --text-secondary-color: #675DF9;
            --tertiary-color: #2E2E3D;
            --light-color: #44C3F4;
            --border-color: #A8A4FF;
            --hover-color: #25252C;
            --black-color: #000000;
            --paragraph-color: #ACAAB1;

            --bg-main: var(--black-color);
            --bg-card: var(--card-color);
            --bg-hover: var(--hover-color);

            --text-main: var(--text-color);
            --text-muted: var(--paragraph-color);

            --border: var(--border-color);
            --radius: 15px;
            --transition: all 0.3s ease;
            --gradient: linear-gradient(45deg, #A8A4FF, #675DF9);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: var(--bg-main);
            color: var(--text-main);
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: var(--bg-card);
            padding: 25px;
            border-right: 1px solid var(--border);
        }

        .sidebar__logo {
            text-decoration: none;
            color: white;
            font-size: 28px;
            font-weight: bold;
        }

        .sidebar__logo span {
            color: var(--light-color);
        }

        .sidebar__role {
            color: var(--text-muted);
            margin: 10px 0 30px;
        }

        .sidebar__nav {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .nav-item {
            text-decoration: none;
            color: white;
            padding: 14px;
            border-radius: 12px;
            transition: var(--transition);
        }

        .nav-item:hover {
            background: var(--bg-hover);
        }

        .nav-item.active {
            background: var(--gradient);
        }

        /* BUTTON */
        button {
            background: var(--gradient);
            border: none;
            padding: 12px 18px;
            color: white;
            border-radius: var(--radius);
            cursor: pointer;
        }

        button:hover {
            opacity: .9;
        }

        /* MAIN */
        .main {
            flex: 1;
            padding: 30px;
        }

        /* HEADER */
        .page-header {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .page-header p {
            color: var(--text-muted);
            margin-top: 8px;
        }

        .actions {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        input[type="search"] {
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 12px 15px;
            border-radius: var(--radius);
            color: white;
        }

        .top-nav {
            display: flex;
            gap: 15px;
        }

        .top-nav a {
            color: white;
            text-decoration: none;
        }

        .top-nav a:hover {
            color: var(--light-color);
        }

        /* NOTIFICATION */
        .notification-box {
            position: relative;
        }

        #notificationCount {
            position: absolute;
            top: -8px;
            right: -8px;
            background: red;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* DROPDOWN */
        .dropdown {
            position: relative;
        }

        #dropdownMenu {
            display: none;
            position: absolute;
            top: 45px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 10px;
            width: 150px;
        }

        #dropdownMenu.show {
            display: block;
        }

        #dropdownMenu p {
            padding: 8px;
            cursor: pointer;
        }

        #dropdownMenu p:hover {
            background: var(--bg-hover);
        }

        /* STATS */
        .stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .stats article {
            flex: 1;
            min-width: 220px;
            background: var(--bg-card);
            padding: 25px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        .stats h3 {
            color: var(--text-muted);
            margin-bottom: 15px;
        }

        /* CHART */
        .chart {
            background: var(--bg-card);
            padding: 25px;
            border-radius: var(--radius);
            margin-bottom: 30px;
        }

        .chart-bars {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
            gap: 15px;
        }

        .bar {
            width: 70px;
            height: 120px;
            background: var(--gradient);
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 10px;
            cursor: pointer;
        }

        .bar:hover {
            transform: scale(1.05);
        }

        #tooltip {
            margin-top: 10px;
            color: var(--light-color);
            font-weight: bold;
        }

        /* TABLE */
        .table-section {
            background: var(--bg-card);
            padding: 25px;
            border-radius: var(--radius);
        }

        .filters {
            margin: 15px 0;
            display: flex;
            gap: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 16px;
            border-bottom: 1px solid var(--tertiary-color);
        }

        th {
            cursor: pointer;
            color: var(--light-color);
        }

        tbody tr:hover {
            background: var(--bg-hover);
        }

        /* MOBILE */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
                display: none;
            }

            .sidebar.show {
                display: block;
            }

            .chart-bars {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <header>
            <a href="<?= BASE_URL ?>/owner/dashboard" class="sidebar__logo">Urban <span>Kinetic</span></a>
            <p class="sidebar__role">Space Owner</p>
        </header>

        <nav class="sidebar__nav">

            <a href="<?= BASE_URL ?>/owner/index" class="nav-item">Dashboard</a>
            <a href="<?= BASE_URL ?>/owner/spaces" class="nav-item">My Spaces</a>
            <a href="<?= BASE_URL ?>/owner/availability" class="nav-item">Availability</a>
            <a href="<?= BASE_URL ?>/owner/earnings" class="nav-item">earnings</a>
        </nav>

    </aside>

    <main class="main">
        <header class="page-header">
            <div>
                <h1>earnings Overview</h1>
                <p>Monitor your revenue flow across urban zones.</p>
            </div>

            <div class="actions">
                <button id="menuToggle">☰</button>

                <input type="search" placeholder="Search transactions..." id="searchInput">

                <nav class="top-nav">
                    <a href="#">Analytics</a>
                    <a href="#">Reports</a>
                    <a href="#">Live Map</a>
                </nav>

                <div class="notification-box">
                    <button id="notificationBell">
                        <i class="fa-solid fa-bell"></i>
                    </button>
                    <span id="notificationCount"><?= count($notifications ?? []) ?></span>
                </div>

                <div class="dropdown">
                    <button id="dropdownButton">Last 30 Days</button>
                    <div id="dropdownMenu">
                        <p>Last 7 Days</p>
                        <p>Last 30 Days</p>
                        <p>Last 90 Days</p>
                    </div>
                </div>
            </div>
        </header>

        <section class="stats">
            <article>
                <h3>Total Revenue</h3>
                <p id="revenue" data-target="<?= $totalRevenue ?>">0</p>
            </article>

            <article>
                <h3>Active Spaces</h3>
                <p id="spaces" data-target="<?= $activeSpaces ?>">0</p>
            </article>

            <article>
                <h3>Pending Payout</h3>
                <p id="payout" data-target=>0</p>
            </article>
        </section>

        <section class="chart">
            <h2>Revenue Trends</h2>
            <p id="tooltip"></p>

            <div class="chart-bars">
                <?php
                $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

                foreach ($weeklyRevenue as $index => $value):
                    $height = max(40, $value / 5);
                    ?>
                    <div class="bar" style="height: <?= $height ?>px" data-value="$<?= $value ?>">
                        <?= $days[$index] ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <script>
        const navItems = document.querySelectorAll(".nav-item");
        navItems.forEach(item => {
            if (window.location.href.includes(item.getAttribute("href"))) {
                item.classList.add("active");
            }
        });
        document.getElementById("dropdownButton").onclick = () => {
            document.getElementById("dropdownMenu").classList.toggle("show");
        };

        const counters = document.querySelectorAll("[data-target]");
        counters.forEach(counter => {
            const update = () => {
                const target = +counter.dataset.target;
                const current = +counter.innerText;
                const increment = Math.ceil(target / 100);

                if (current < target) {
                    counter.innerText = current + increment;
                    setTimeout(update, 20);
                } else {
                    counter.innerText = target;
                }
            };
            update();
        });

        const bars = document.querySelectorAll(".bar");
        const tooltip = document.getElementById("tooltip");

        bars.forEach(bar => {
            bar.addEventListener("mouseover", () => {
                tooltip.innerText = bar.dataset.value;
            });

            bar.addEventListener("mouseout", () => {
                tooltip.innerText = "";
            });
        });

        document.getElementById("notificationBell").onclick = () => {
            document.getElementById("notificationCount").innerText = "0";
        };

        document.getElementById("menuToggle").onclick = () => {
            document.querySelector(".sidebar").classList.toggle("show");
        };
    </script>

</body>

</html>
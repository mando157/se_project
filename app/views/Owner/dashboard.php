<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Page</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/owner.css">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-top">
        <div class="sidebar-title">
            <i class="fa-solid fa-square-parking"></i>
            Urban Kinetic
        </div>
        <div class="sidebar-subtitle">Space Owner</div>
    </div>

    <a href="#" class="active">
        <i class="fa-solid fa-house"></i> Dashboard
    </a>
    <a href="#">
        <i class="fa-solid fa-building"></i> My Spaces
    </a>
    <a href="#">
        <i class="fa-solid fa-calendar-days"></i> Availability
    </a>
    <a href="#">
        <i class="fa-solid fa-book"></i> Bookings
    </a>
    <a href="#">
        <i class="fa-solid fa-dollar-sign"></i> Earnings
    </a>

    <div class="sidebar-actions">
        <button class="add-space-btn">
            <i class="fa-solid fa-plus"></i> Add New Space
        </button>
    </div>
</div>

<div class="main-content">
    <nav class="navbar px-4 py-3">
        <div class="navbar-left">
            <button class="show-sidebar-btn-inline" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
            <input class="search" placeholder="Search Command Center...">
        </div>
        <div class="ms-auto d-flex align-items-center gap-3">
            <a href="#" class="nav-link">Analytics</a>
            <a href="#" class="nav-link">Reports</a>
            <a href="#" class="nav-link">Live Map</a>
            <button class="btn">Release All Slots</button>
            <a href="#"><i class="fa-regular fa-bell"></i></a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Command Center</h2>

        <!-- الكروت -->
        <div class="dashboard">
            <div class="card earnings">
                <div class="card-top">
                    <span class="title">TOTAL EARNINGS</span>
                    <div class="icon">💵</div>
                </div>
                <h2>$<span id="earningsAmount"><?= number_format($totalEarnings ?? 0, 2) ?></span>
                    <span id="earningsChangeSpan" class="<?= ($earningsChange ?? 0) >= 0 ? 'up' : 'down' ?>">
                        <?= (($earningsChange ?? 0) >= 0 ? '+' : '') . ($earningsChange ?? 0) ?>%
                    </span>
                </h2>
                <p>vs. $<?= number_format($lastMonthEarnings ?? 0, 2) ?> last month</p>
            </div>

            <div class="card bookings">
                <div class="card-top">
                    <span class="title">TOTAL BOOKINGS</span>
                    <div class="icon">📊</div>
                </div>
                <h2><span id="totalBookingsValue"><?= ($activeBookings ?? 0) + ($pendingBookings ?? 0) ?></span>
                    <span id="bookingsChangeSpan" class="<?= ($bookingsChange ?? 0) >= 0 ? 'up' : 'down' ?>">
                        <?= (($bookingsChange ?? 0) >= 0 ? '+' : '') . ($bookingsChange ?? 0) ?>%
                    </span>
                </h2>
                <p>Active sessions: <span id="activeBookingsValue"><?= $activeBookings ?? 0 ?></span></p>
            </div>

            <div class="card occupancy">
                <div class="card-top">
                    <span class="title">OCCUPANCY RATE</span>
                    <div class="icon">📱</div>
                </div>
                <h2><span id="occupancyValue"><?= $occupancyRate ?? 0 ?></span>%
                    <span id="occupancyChangeSpan" class="<?= ($occupancyChange ?? 0) >= 0 ? 'up' : 'down' ?>">
                        <?= (($occupancyChange ?? 0) >= 0 ? '+' : '') . ($occupancyChange ?? 0) ?>%
                    </span>
                </h2>
                <p>Peak hour: <span id="peakHourDisplay"><?= htmlspecialchars($peakHour ?? '14:00 - 16:00') ?></span></p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card dark-card">
                    <h5>Revenue Performance</h5>
                    <p>Weekly analytical breakdown</p>
                    <div>
                        <button id="weeklyBtn" class="active" onclick="setWeekly()">Weekly</button>
                        <button id="monthlyBtn" onclick="setMonthly()">Monthly</button>
                    </div>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="col-md-4">
                <div class="notifications-card">
                    <div class="notif-header">
                        <h5>Notifications</h5>
                        <button class="mark-read-btn" onclick="markAllRead()">Mark all read</button>
                    </div>
                    <div id="notificationsList">
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $notification): ?>
                                <div class="notif">
                                    <?= htmlspecialchars($notification['message']) ?>
                                    <small><?= date('M d, H:i', strtotime($notification['created_at'])) ?></small>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="notif">✨ No new notifications</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <h5>Recent Bookings</h5>
            <?php if (!empty($recentBookings)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Parking Space</th>
                            <th>Slots</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentBookings as $booking): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['spot_name'] ?? 'N/A') ?></td>
                                <td><?= $booking['booked_slots'] ?? 1 ?></td>
                                <td><?= date('M d, H:i', strtotime($booking['start_time'])) ?></td>
                                <td><?= date('M d, H:i', strtotime($booking['end_time'])) ?></td>
                                <td>$<?= number_format($booking['total_cost'] ?? 0, 2) ?></td>
                                <td>
                                    <span class="status-badge status-<?= $booking['status'] ?>">
                                        <?= $booking['status'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div>📭 No recent bookings found</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>


<script src="<?= BASE_URL ?>assets/js/owner.js"></script>
<script src="<?= BASE_URL ?>assets/js/spots.js"></script>

<script>
    const weeklyData = <?= json_encode($weeklyRevenue ?? [120, 200, 180, 300, 250, 400, 350]) ?>;
    const monthlyData = <?= json_encode($monthlyRevenue ?? [1200, 1800, 2200, 3000]) ?>;
    const baseUrl = '<?= BASE_URL ?>';
    
    const ctx = document.getElementById('revenueChart').getContext('2d');
    let revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Revenue ($)',
                data: weeklyData,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });
    
    function setWeekly() {
        revenueChart.data.labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        revenueChart.data.datasets[0].data = weeklyData;
        revenueChart.update();
        document.getElementById('weeklyBtn').classList.add('active');
        document.getElementById('monthlyBtn').classList.remove('active');
    }
    
    function setMonthly() {
        revenueChart.data.labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        revenueChart.data.datasets[0].data = monthlyData;
        revenueChart.update();
        document.getElementById('monthlyBtn').classList.add('active');
        document.getElementById('weeklyBtn').classList.remove('active');
    }
    
    function refreshDashboard() {
        fetch(baseUrl + 'owner/getDashboardData')
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('earningsAmount').innerText = parseFloat(data.totalearnings).toFixed(2);
                    const totalBookings = data.activeBookings + data.pendingBookings;
                    document.getElementById('totalBookingsValue').innerText = totalBookings;
                    document.getElementById('activeBookingsValue').innerText = data.activeBookings;
                    document.getElementById('occupancyValue').innerText = data.occupancyRate;
                    document.getElementById('peakHourDisplay').innerText = data.peakHour;
                    
                    const earningsSpan = document.getElementById('earningsChangeSpan');
                    earningsSpan.innerHTML = (data.earningsChange >= 0 ? '+' : '') + data.earningsChange + '%';
                    earningsSpan.className = data.earningsChange >= 0 ? 'up' : 'down';
                    
                    const bookingsSpan = document.getElementById('bookingsChangeSpan');
                    bookingsSpan.innerHTML = (data.bookingsChange >= 0 ? '+' : '') + data.bookingsChange + '%';
                    bookingsSpan.className = data.bookingsChange >= 0 ? 'up' : 'down';
                    
                    const occupancySpan = document.getElementById('occupancyChangeSpan');
                    occupancySpan.innerHTML = (data.occupancyChange >= 0 ? '+' : '') + data.occupancyChange + '%';
                    occupancySpan.className = data.occupancyChange >= 0 ? 'up' : 'down';
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    setInterval(refreshDashboard, 30000);
    setTimeout(refreshDashboard, 2000);
    
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('collapsed');
    }
    
    function markAllRead() {
        fetch(baseUrl + 'owner/markNotificationsRead', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if(data.success) location.reload();
            })
            .catch(error => console.error('Error:', error));
    }
</script>
</body>
</html>
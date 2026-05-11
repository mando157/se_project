<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - المالك</title>
    <!-- CSS بتاعك انت -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/owner.css">
</head>

<style>



.table-container {
    background: #f8fafc;
    border-radius: 20px;
    padding: 24px;
    margin-top: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    overflow-x: auto;
    border: 1px solid #e2e8f0;
}

.table-container h5 {
    color: #1e293b;
    font-size: 18px;
    margin-bottom: 20px;
    font-weight: 600;
    border-right: 4px solid #7c5cff;
    padding-right: 12px;
}


.table {
    width: 100%;
    border-collapse: collapse;
}


.table thead th {
    color: #000000 !important;
    font-size: 14px;
    font-weight: 700;
    padding: 15px 12px;
    text-align: right;
    background: #e2e8f0;
    border-bottom: 3px solid #7c5cff;
}


.table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #cbd5e1;
}

.table tbody tr:hover {
    background: #f1f5f9;
    transform: translateX(-3px);
}


.table tbody td {
    padding: 12px;
    color: #1e293b !important;
    font-size: 14px;
    font-weight: 500;
}


.table tbody td:first-child {
    color: #0f172a !important;
    font-weight: 700;
    font-size: 15px;
}

.time-cell {
    color: #1e293b !important;
    font-size: 13px;
    font-weight: 500;
    font-family: monospace;
}


.amount-cell {
    color: #000000 !important;
    font-weight: 800;
    font-size: 15px;
}


.status-badge {
    display: inline-block;
    padding: 5px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    text-align: center;
}

.status-active {
    background: #22c55e !important;
    color: #000000 !important;
    border: none;
}


.status-completed {
    background: #3b82f6 !important;
    color: #ffffff !important;
    border: none;
}

.status-pending {
    background: #f59e0b !important;
    color: #000000 !important;
    border: none;
}


.status-cancelled {
    background: #ef4444 !important;
    color: #ffffff !important;
    border: none;
}


.spot-icon {
    color: #7c5cff;
    margin-left: 8px;
    font-size: 14px;
}


.empty-state {
    text-align: center;
    padding: 50px;
}

.empty-state i {
    font-size: 56px;
    margin-bottom: 15px;
    color: #cbd5e1;
}

.empty-state p {
    color: #64748b;
    font-size: 14px;
}


.table tbody tr:nth-child(even) {
    background: #f8fafc;
}

.table tbody tr:nth-child(odd) {
    background: #ffffff;
}




.table th:nth-child(1),
.table td:nth-child(1) {
    width: 25%;
    min-width: 150px;
}

.table th:nth-child(2),
.table td:nth-child(2) {
    width: 8%;
    text-align: center;
}

.table th:nth-child(3),
.table td:nth-child(3) {
    width: 18%;
}

.table th:nth-child(4),
.table td:nth-child(4) {
    width: 18%;
}

.table th:nth-child(5),
.table td:nth-child(5) {
    width: 15%;
}

.table th:nth-child(6),
.table td:nth-child(6) {
    width: 16%;
}


.table th:nth-child(2),
.table td:nth-child(2) {
    text-align: center;
}

.table th:nth-child(5),
.table td:nth-child(5) {
    text-align: right;
    padding-left: 0px;
}

.table th:nth-child(6),
.table td:nth-child(6) {
    text-align: center;
}


.table td:first-child {
    font-weight: 600;
    white-space: nowrap;
}


.amount-cell {
    text-align: right;
    font-weight: 700;
}


.spot-icon {
    margin-left: 0;
    margin-right: 8px;
}

.table tbody td {
    vertical-align: middle;
    padding: 12px 8px;
}
</style>
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
        <button class="add-space-btn" onclick="openModal()">
            <i class="fa-solid fa-plus"></i> Add New Space
        </button>
    </div>
</div>

<div class="main-content">
   <nav class="navbar px-4 py-3 f1">
    <div class="navbar-left">
        <button class="show-sidebar-btn-inline" onclick="toggleSidebar()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <input class="search f2" placeholder="Search Command Center...">
    </div>
    <div class="ms-auto d-flex align-items-center gap-3 f3">
        <a href="#" class="nav-link f15 f4">Analytics</a>
        <a href="#" class="nav-link f4">Reports</a>
        <a href="#" class="nav-link f4">Live Map</a>
        <button class="btn f5">Release All Slots</button>
        <a href="#"><i class="fa-regular fa-bell icon f6"></i></a>
    </div>
</nav>

    <div class="container mt-4">
        <h2>Command Center</h2>

        
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
                    <div class="f20">
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
    <h5>📋 Recent Bookings</h5>
    <?php if (!empty($recentBookings)): ?>
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th style="width: 25%">Parking Space</th>
                    <th style="width: 8%">Slots</th>
                    <th style="width: 18%">Start</th>
                    <th style="width: 18%">End</th>
                    <th style="width: 15%">Amount</th>
                    <th style="width: 16%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentBookings as $booking): ?>
                    <tr>
                        <td>
                            <i class="fa-solid fa-square-parking spot-icon"></i>
                            <?= htmlspecialchars($booking['spot_name'] ?? $booking['location'] ?? 'N/A') ?>
                        </td>
                        <td class="text-center time-cell"><?= $booking['booked_slots'] ?? 1 ?></td>
                        <td class="time-cell"><?= date('M d, H:i', strtotime($booking['start_time'])) ?></td>
                        <td class="time-cell"><?= date('M d, H:i', strtotime($booking['end_time'])) ?></td>
                        <td class="amount-cell text-end">$<?= number_format($booking['total_cost'] ?? 0, 2) ?></td>
                        <td class="text-center">
                            <span class="status-badge status-<?= $booking['status'] ?>">
                                <?php
                                switch($booking['status']) {
                                    case 'active': echo '✓ Active'; break;
                                    case 'completed': echo '✔ Completed'; break;
                                    case 'pending': echo '⏳ Pending'; break;
                                    default: echo $booking['status'];
                                }
                                ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-calendar-xmark"></i>
            <p>No recent bookings found</p>
        </div>
    <?php endif; ?>
</div>

<div class="parking-section">
    <img src="<?= BASE_URL ?>assets/images/ezgif.com-webp-to-png-1558x1558.webp" alt="Parking Cars">
</div>
    </div>
</div>


<div class="modal" id="modal">
    <div class="modal-box">
        <span class="close" onclick="closeModal()">×</span>
        <h2 class="modal-title">New Space</h2>
        
        <form id="addSpaceForm">
            <label>LOCATION REFERENCE</label>
            <div class="input-with-icon">
                <i class="fa-solid fa-location-dot"></i>
                <input type="text" id="location" name="location" placeholder="e.g. 5th Avenue Loft" required>
            </div>

            <div class="model-row">
                <div>
                    <label>PRICE PER HOUR</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-dollar-sign"></i>
                        <input type="number" id="price_per_hour" name="price_per_hour" step="0.01" placeholder="0.00" required>
                    </div>
                </div>
                <div>
                    <label>TOTAL SLOTS</label>
                    <input type="number" id="total_slots" name="total_slots" value="1" min="1">
                </div>
            </div>

            <label>SPACE ATTRIBUTES</label>
            <div class="tags">
                <button type="button" data-attribute="EV Charging" onclick="toggleAttribute(this)">
                    <i class="fa-solid fa-bolt"></i> EV Charging
                </button>
                <button type="button" data-attribute="CCTV Security" class="active" onclick="toggleAttribute(this)">
                    <i class="fa-solid fa-shield-halved"></i> CCTV Security
                </button>
                <button type="button" data-attribute="Disabled Access" onclick="toggleAttribute(this)">
                    <i class="fa-solid fa-wheelchair"></i> Disabled Access
                </button>
                <button type="button" data-attribute="Indoor" onclick="toggleAttribute(this)">
                    <i class="fa-solid fa-warehouse"></i> Indoor
                </button>
            </div>
            <input type="hidden" id="attributes" name="attributes" value='["CCTV Security"]'>

            <div class="toggle-box">
                <div>
                    <h4>Instant Activation</h4>
                    <p>Listing will be live on the map immediately after verification.</p>
                </div>
                <label class="modal-switch">
                    <input type="checkbox" id="instant_activation" name="instant_activation" checked>
                    <span></span>
                </label>
            </div>

            <button type="submit" class="submit">Register Space</button>
        </form>
    </div>
</div>


<script>
    window.dbWeeklyRevenue = <?= json_encode($weeklyRevenue ?? [120, 200, 180, 300, 250, 400, 350]) ?>;
    window.dbMonthlyRevenue = <?= json_encode($monthlyRevenue ?? [1200, 1800, 2200, 3000]) ?>;
    window.baseUrl = '<?= BASE_URL ?>';

    
    function toggleAttribute(btn) {
        btn.classList.toggle('active');
        updateAttributesInput();
    }
    
    function updateAttributesInput() {
        const activeAttributes = [];
        document.querySelectorAll('.tags button.active').forEach(btn => {
            activeAttributes.push(btn.getAttribute('data-attribute'));
        });
        document.getElementById('attributes').value = JSON.stringify(activeAttributes);
    }
    
   
    document.getElementById('addSpaceForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('location', document.getElementById('location').value);
        formData.append('price_per_hour', document.getElementById('price_per_hour').value);
        formData.append('total_slots', document.getElementById('total_slots').value);
        formData.append('attributes', document.getElementById('attributes').value);
        formData.append('instant_activation', document.getElementById('instant_activation').checked ? 1 : 0);
        
        const submitBtn = document.querySelector('#addSpaceForm .submit');
        const originalText = submitBtn.innerText;
        submitBtn.innerText = 'Adding...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch('<?= BASE_URL ?>owner/addSpace', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                alert('✓ Space added successfully!');
                closeModal();
                document.getElementById('addSpaceForm').reset();
                // تحديث الداشبورد
                setTimeout(() => location.reload(), 500);
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error adding space: ' + error.message);
        } finally {
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
        }
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/owner.js"></script>

<script>

    document.addEventListener('DOMContentLoaded', () => {
        initChart(window.dbWeeklyRevenue, window.dbMonthlyRevenue);
        setInterval(refreshDashboard, 30000);
        setTimeout(refreshDashboard, 2000);
    });
</script>
</body>
</html>
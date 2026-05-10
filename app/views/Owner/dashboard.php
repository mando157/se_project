<!DOCTYPE html>
<html lang="en">

<head>

  
   <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--* CSS Style Sheets -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/owner.css?v=1">
  <!--* Font Awsome -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css">
  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
  <title>Owner Dashboard | Urban Kinetic</title>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-title">
      🅿️ Urban Kinetic
    </div>
    <div class="sidebar-subtitle">
      Space Owner
    </div>
    
    <a href="<?= BASE_URL ?>owner/dashboard" class="active">
      📊 Dashboard
    </a>
    <a href="<?= BASE_URL ?>owner/spaces">
      🏢 My Spaces
    </a>
    <a href="<?= BASE_URL ?>owner/bookings">
      📖 Bookings
    </a>
    <a href="<?= BASE_URL ?>owner/notifications">
      🔔 Notifications
    </a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    
    <!-- Navbar -->
    <div class="navbar">
      <div class="navbar-left">
        <button onclick="toggleSidebar()">☰</button>
      </div>
      <div class="ms-auto">
        <small id="lastUpdate"></small>
      </div>
    </div>
    
    <h2>Command Center</h2>
    
    <!-- Dashboard Cards -->
    <div class="dashboard">
      
      <div class="card">
        <div class="card-top">
          <span class="title">TOTAL EARNINGS</span>
          <span class="icon">💰</span>
        </div>
        <h2>
          $<span id="earningsAmount"><?= number_format($totalEarnings ?? 0, 2) ?></span>
          <span id="earningsChangeSpan">
            <?= (($earningsChange ?? 0) >= 0 ? '+' : '') . ($earningsChange ?? 0) ?>%
          </span>
        </h2>
        <p>vs. $<?= number_format($lastMonthEarnings ?? 0, 2) ?> last month</p>
      </div>
      
      <div class="card">
        <div class="card-top">
          <span class="title">TOTAL BOOKINGS</span>
          <span class="icon">📊</span>
        </div>
        <h2>
          <span id="totalBookingsValue"><?= ($activeBookings ?? 0) + ($pendingBookings ?? 0) ?></span>
          <span id="bookingsChangeSpan">
            <?= (($bookingsChange ?? 0) >= 0 ? '+' : '') . ($bookingsChange ?? 0) ?>%
          </span>
        </h2>
        <p>Active sessions: <span id="activeBookingsValue"><?= $activeBookings ?? 0 ?></span></p>
      </div>
      
      <div class="card">
        <div class="card-top">
          <span class="title">OCCUPANCY RATE</span>
          <span class="icon">📱</span>
        </div>
        <h2>
          <span id="occupancyValue"><?= $occupancyRate ?? 0 ?></span>%
          <span id="occupancyChangeSpan">
            <?= (($occupancyChange ?? 0) >= 0 ? '+' : '') . ($occupancyChange ?? 0) ?>%
          </span>
        </h2>
        <p>Peak hour: <span id="peakHourDisplay"><?= htmlspecialchars($peakHour ?? '14:00 - 16:00') ?></span></p>
      </div>
      
    </div>
    
    <!-- Chart + Notifications Row -->
    <div>
      
      <div>
        <h5>Revenue Performance</h5>
        <p>Weekly analytical breakdown</p>
        
        <div>
          <button id="weeklyBtn" onclick="setWeekly()">Weekly</button>
          <button id="monthlyBtn" onclick="setMonthly()">Monthly</button>
        </div>
        
        <canvas id="revenueChart"></canvas>
      </div>
      
      <div>
        <div>
          <h5>Notifications</h5>
          <button onclick="markAllRead()">Mark all read</button>
        </div>
        
        <div id="notificationsList">
          <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notification): ?>
              <div>
                <?= htmlspecialchars($notification['message']) ?>
                <small>
                  <?= date('M d, H:i', strtotime($notification['created_at'])) ?>
                </small>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div>✨ No new notifications</div>
          <?php endif; ?>
        </div>
      </div>
      
    </div>
    
    <!-- Recent Bookings Table -->
    <div>
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
                <td><?= $booking['booked_slots'] ?? 0 ?></td>
                <td><?= date('M d, H:i', strtotime($booking['start_time'])) ?></td>
                <td><?= date('M d, H:i', strtotime($booking['end_time'])) ?></td>
                <td>$<?= number_format($booking['total_cost'] ?? 0, 2) ?></td>
                <td>
                  <span>
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
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <script>
    const weeklyData = <?= json_encode($weeklyRevenue ?? [120, 200, 180, 300, 250, 400, 350]) ?>;
    const monthlyData = <?= json_encode($monthlyRevenue ?? [1200, 1800, 2200, 3000]) ?>;
    
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
        maintainAspectRatio: true,
        plugins: {
          legend: {
            labels: { color: '#fff' }
          }
        },
        scales: {
          y: {
            ticks: { color: '#fff' },
            grid: { color: 'rgba(255,255,255,0.1)' }
          },
          x: {
            ticks: { color: '#fff' },
            grid: { color: 'rgba(255,255,255,0.1)' }
          }
        }
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
      const baseUrl = '<?= BASE_URL ?>';
      fetch(baseUrl + 'owner/getDashboardData')
        .then(response => response.json())
        .then(data => {
          if(data.success) {
            document.getElementById('lastUpdate').innerText = 'Updated: ' + new Date().toLocaleTimeString();
            document.getElementById('earningsAmount').innerText = parseFloat(data.totalearnings).toFixed(2);
            const totalBookings = data.activeBookings + data.pendingBookings;
            document.getElementById('totalBookingsValue').innerText = totalBookings;
            document.getElementById('activeBookingsValue').innerText = data.activeBookings;
            document.getElementById('occupancyValue').innerText = data.occupancyRate;
            document.getElementById('peakHourDisplay').innerText = data.peakHour;
            document.getElementById('earningsChangeSpan').innerHTML = (data.earningsChange >= 0 ? '+' : '') + data.earningsChange + '%';
            document.getElementById('bookingsChangeSpan').innerHTML = (data.bookingsChange >= 0 ? '+' : '') + data.bookingsChange + '%';
            document.getElementById('occupancyChangeSpan').innerHTML = (data.occupancyChange >= 0 ? '+' : '') + data.occupancyChange + '%';
          }
        })
        .catch(error => console.error('Refresh error:', error));
    }
    
    setInterval(refreshDashboard, 30000);
    setTimeout(refreshDashboard, 2000);
    
    function toggleSidebar() {
      document.querySelector('.sidebar').classList.toggle('collapsed');
    }
    
    function markAllRead() {
      const baseUrl = '<?= BASE_URL ?>';
      fetch(baseUrl + 'owner/markNotificationsRead', {
        method: 'POST'
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          location.reload();
        }
      })
      .catch(error => console.error('Mark read error:', error));
    }
  </script>
  
</body>

</html>
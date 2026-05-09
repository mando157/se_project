<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/owner.css?v=1">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
  <title>Owner Dashboard - Urban Kinetic</title>
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

    <a href="<?= BASE_URL ?>owner/dashboard" class="active">
      <i class="fa-solid fa-house"></i> Dashboard
    </a>
    <a href="<?= BASE_URL ?>owner/spaces">
      <i class="fa-solid fa-building"></i> My Spaces
    </a>
    <a href="<?= BASE_URL ?>owner/availability">
      <i class="fa-solid fa-calendar-days"></i> Availability
    </a>
    <a href="<?= BASE_URL ?>owner/bookings">
      <i class="fa-solid fa-book"></i> Bookings
    </a>
    <a href="<?= BASE_URL ?>owner/earnings">
      <i class="fa-solid fa-dollar-sign"></i> Earnings
    </a>

    <div class="sidebar-actions">
      <button class="add-space-btn" onclick="openModal()">
        <span class="icon"><i class="fa-solid fa-plus"></i></span>
        Add New Space
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
        <a href="<?= BASE_URL ?>owner/notifications"><i class="fa-regular fa-bell icon"></i></a>
      </div>
    </nav>

    <div class="container mt-4">
      <h2 class="text-white">Command Center 
        <small style="font-size: 12px; color: #888;" id="lastUpdate"></small>
      </h2>

      <!-- Dashboard Cards -->
      <div class="dashboard">
        <!-- Card 1: TOTAL EARNINGS -->
        <div class="card earnings">
          <div class="card-top">
            <span class="title">TOTAL EARNINGS</span>
            <div class="icon">💵</div>
          </div>
          <h2>$<?php echo isset($data['totalearnings']) ? number_format($data['totalearnings'], 2) : '0.00'; ?> 
            <span class="<?php echo (($data['earningsChange'] ?? 0) >= 0) ? 'up' : 'down'; ?>">
              <?php echo (($data['earningsChange'] ?? 0) >= 0 ? '+' : ''); ?><?php echo number_format($data['earningsChange'] ?? 0, 1); ?>%
            </span>
          </h2>
          <p>vs. $<?php echo number_format($data['lastMonthEarnings'] ?? 0, 2); ?> last month</p>
        </div>

        <!-- Card 2: TOTAL BOOKINGS -->
        <div class="card bookings">
          <div class="card-top">
            <span class="title">TOTAL BOOKINGS</span>
            <div class="icon">📊</div>
          </div>
          <h2 id="totalBookingsCount"><?php 
            $active = isset($data['activeBookings']) ? $data['activeBookings'] : 0;
            $pending = isset($data['pendingBookings']) ? $data['pendingBookings'] : 0;
            echo $active + $pending;
          ?> 
            <span class="<?php echo (($data['bookingsChange'] ?? 0) >= 0) ? 'up' : 'down'; ?>">
              <?php echo (($data['bookingsChange'] ?? 0) >= 0 ? '+' : ''); ?><?php echo number_format($data['bookingsChange'] ?? 0, 1); ?>%
            </span>
          </h2>
          <p>Active sessions: <?php echo isset($data['activeBookings']) ? $data['activeBookings'] : 0; ?></p>
        </div>

        <!-- Card 3: OCCUPANCY RATE -->
        <div class="card occupancy">
          <div class="card-top">
            <span class="title">OCCUPANCY RATE</span>
            <div class="icon">📱</div>
          </div>
          <h2><?php echo isset($data['occupancyRate']) ? $data['occupancyRate'] : '0'; ?>% 
            <span class="<?php echo (($data['occupancyChange'] ?? 0) >= 0) ? 'up' : 'down'; ?>">
              <?php echo (($data['occupancyChange'] ?? 0) >= 0 ? '+' : ''); ?><?php echo number_format($data['occupancyChange'] ?? 0, 1); ?>%
            </span>
          </h2>
          <p>Peak hour: <span id="peakHourDisplay"><?php echo htmlspecialchars($data['peakHour'] ?? '14:00 - 16:00'); ?></span></p>
        </div>
      </div>

      <div class="row mt-4 g-3">
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
              <?php if (isset($data['notifications']) && !empty($data['notifications'])): ?>
                <?php foreach($data['notifications'] as $notification): ?>
                  <div class="notif <?php echo ($notification['is_read'] == 0) ? 'notification-unread' : ''; ?>" data-id="<?php echo $notification['id']; ?>">
                    <?php echo htmlspecialchars($notification['message']); ?>
                    <small style="display: block; font-size: 11px; color: #888;">
                      <?php echo date('M d, H:i', strtotime($notification['created_at'])); ?>
                    </small>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="notif">No new notifications</div>
              <?php endif; ?>
            </div>
            <button class="view-all-btn" onclick="viewAllNotifications()">View All Notifications</button>
          </div>
        </div>
      </div>

      <!-- Recent Bookings Section -->
      <div class="parking-section">
        <div class="recent-bookings-table">
          <h5 style="color: white; margin-bottom: 15px;">Recent Bookings</h5>
          <div id="recentBookingsTable">
            <?php if (isset($data['recentBookings']) && !empty($data['recentBookings'])): ?>
              <table class="table table-dark table-hover">
                <thead>
                  <tr>
                    <th>Parking Space</th>
                    <th>Booked Slots</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Amount</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($data['recentBookings'] as $booking): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($booking['name'] ?? $booking['parking_name'] ?? 'N/A'); ?></td>
                      <td><?php echo $booking['booked_slots'] ?? '0'; ?></td>
                      <td><?php echo date('M d, H:i', strtotime($booking['start_time'] ?? 'now')); ?></td>
                      <td><?php echo date('M d, H:i', strtotime($booking['end_time'] ?? 'now')); ?></td>
                      <td>$<?php echo number_format($booking['amount'] ?? 0, 2); ?></td>
                      <td>
                        <span class="badge bg-<?php 
                          $status = $booking['status'] ?? 'unknown';
                          echo $status == 'active' ? 'success' : ($status == 'pending' ? 'warning' : 'secondary'); 
                        ?>">
                          <?php echo $status; ?>
                        </span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else: ?>
              <p style="color: white; text-align: center;">No recent bookings found</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for Add New Space -->
  <div class="modal" id="modal">
    <div class="modal-box">
      <span class="close" onclick="closeModal()">×</span>
      <h2 class="modal-title">New Space</h2>
      <form id="addSpaceForm">
        <label>LOCATION REFERENCE</label>
        <div class="input-with-icon">
          <i class="fa-solid fa-location-dot"></i>
          <input type="text" name="location_reference" placeholder="e.g. 5th Avenue Loft" required>
        </div>
        <div class="model-row">
          <div>
            <label>PRICE PER HOUR</label>
            <div class="input-with-icon">
              <i class="fa-solid fa-dollar-sign"></i>
              <input type="number" name="price_per_hour" step="0.01" placeholder="0.00" required>
            </div>
          </div>
          <div>
            <label>TOTAL SLOTS</label>
            <input type="number" name="total_slots" value="1" required>
          </div>
        </div>
        <label>SPACE ATTRIBUTES</label>
        <div class="tags">
          <button type="button" onclick="toggleAttribute(this)"><i class="fa-solid fa-bolt"></i> EV Charging</button>
          <button type="button" class="active" onclick="toggleAttribute(this)"><i class="fa-solid fa-shield-halved"></i> CCTV Security</button>
          <button type="button" onclick="toggleAttribute(this)"><i class="fa-solid fa-wheelchair"></i> Disabled Access</button>
          <button type="button" onclick="toggleAttribute(this)"><i class="fa-solid fa-warehouse"></i> Indoor</button>
        </div>
        <input type="hidden" name="attributes" id="selectedAttributes" value="CCTV Security">
        <div class="toggle-box">
          <div>
            <h4>Instant Activation</h4>
            <p>Listing will be live on the map immediately after verification.</p>
          </div>
          <label class="modal-switch">
            <input type="checkbox" name="instant_activation" checked>
            <span></span>
          </label>
        </div>
        <button type="submit" class="submit">Register Space</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>assets/js/owner.js"></script>
  <script>
    // Dynamic chart data from PHP
    const weeklyData = <?php echo json_encode($data['weeklyRevenue'] ?? [650, 720, 810, 940, 1120, 1340, 1280]); ?>;
    const monthlyData = <?php echo json_encode($data['monthlyRevenue'] ?? [4850, 5320, 6780, 8140]); ?>;
    const chartLabels = <?php echo json_encode($data['chartLabels'] ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']); ?>;
    
    // Chart.js configuration
    const ctx = document.getElementById('revenueChart').getContext('2d');
    let revenueChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: chartLabels,
        datasets: [{
          label: 'Revenue ($)',
          data: weeklyData,
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
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
      fetch('<?= BASE_URL ?>owner/getDashboardData', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {

          document.getElementById('lastUpdate').innerText = 'Last updated: ' + new Date().toLocaleTimeString();
          
          
          document.querySelector('.earnings h2').innerHTML = '$' + parseFloat(data.totalearnings).toFixed(2) + 
            ' <span class="' + (data.earningsChange >= 0 ? 'up' : 'down') + '">' + 
            (data.earningsChange >= 0 ? '+' : '') + data.earningsChange + '%</span>';
          
        
          const totalBookings = data.activeBookings + data.pendingBookings;
          document.querySelector('.bookings h2').innerHTML = totalBookings + 
            ' <span class="' + (data.bookingsChange >= 0 ? 'up' : 'down') + '">' + 
            (data.bookingsChange >= 0 ? '+' : '') + data.bookingsChange + '%</span>';
          document.querySelector('.bookings p').innerHTML = 'Active sessions: ' + data.activeBookings;
          

          document.querySelector('.occupancy h2').innerHTML = data.occupancyRate + '%' + 
            ' <span class="' + (data.occupancyChange >= 0 ? 'up' : 'down') + '">' + 
            (data.occupancyChange >= 0 ? '+' : '') + data.occupancyChange + '%</span>';
          document.getElementById('peakHourDisplay').innerText = data.peakHour;
          
        
          if(data.recentBookings && data.recentBookings.length > 0) {
            let tableHtml = `<table class="table table-dark table-hover">
              <thead><tr><th>Parking Space</th><th>Booked Slots</th><th>Start Time</th><th>End Time</th><th>Amount</th><th>Status</th></tr></thead>
              <tbody>`;
            data.recentBookings.forEach(booking => {
              tableHtml += `<tr>
                <td>${booking.name || booking.parking_name || 'N/A'}</td>
                <td>${booking.booked_slots}</td>
                <td>${new Date(booking.start_time).toLocaleString()}</td>
                <td>${new Date(booking.end_time).toLocaleString()}</td>
                <td>$${parseFloat(booking.amount).toFixed(2)}</td>
                <td><span class="badge bg-${booking.status == 'active' ? 'success' : (booking.status == 'pending' ? 'warning' : 'secondary')}">${booking.status}</span></td>
              </tr>`;
            });
            tableHtml += `</tbody></table>`;
            document.getElementById('recentBookingsTable').innerHTML = tableHtml;
          }
          
          // Update notifications
          if(data.notifications && data.notifications.length > 0) {
            let notifHtml = '';
            data.notifications.forEach(notif => {
              notifHtml += `<div class="notif ${notif.is_read == 0 ? 'notification-unread' : ''}" data-id="${notif.id}">
                ${notif.message}
                <small style="display: block; font-size: 11px; color: #888;">${new Date(notif.created_at).toLocaleString()}</small>
              </div>`;
            });
            document.getElementById('notificationsList').innerHTML = notifHtml;
          }
        }
      })
      .catch(error => console.error('Error refreshing dashboard:', error));
    }

    
    setInterval(refreshDashboard, 30000);

    function openModal() {
      document.getElementById('modal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('modal').style.display = 'none';
    }

    function toggleAttribute(btn) {
      btn.classList.toggle('active');
      const selected = Array.from(document.querySelectorAll('.tags button.active'))
        .map(b => b.innerText.trim());
      document.getElementById('selectedAttributes').value = selected.join(',');
    }

    function markAllRead() {
      fetch('<?= BASE_URL ?>owner/markNotificationsRead', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) location.reload();
      })
      .catch(error => console.error('Error:', error));
    }

    function viewAllNotifications() {
      window.location.href = '<?= BASE_URL ?>owner/notifications';
    }

    document.getElementById('addSpaceForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      
      fetch('<?= BASE_URL ?>owner/addSpace', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          alert('✓ Space added successfully!');
          closeModal();
          location.reload();
        } else {
          alert('✗ Error: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
      });
    });

    window.onclick = function(event) {
      if (event.target == document.getElementById('modal')) {
        closeModal();
      }
    }
  </script>
</body>

</html>
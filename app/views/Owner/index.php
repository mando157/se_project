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

  <!-- SIDEBAR (unchanged) -->
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
    <a href="<?= BASE_URL ?>owner/bookings">
      <i class="fa-solid fa-book"></i> Bookings
    </a>
    <a href="<?= BASE_URL ?>owner/notifications">
      <i class="fa-solid fa-bell"></i> Notifications
    </a>
  </div>

  <!-- MAIN -->
  <div class="main-content">

    <!-- NAV -->
    <nav class="navbar px-4 py-3">
      <div class="navbar-left">
        <input class="search" placeholder="Search Command Center...">
      </div>
    </nav>

    <div class="container mt-4">
      <h2 class="text-white">Command Center</h2>

      <!-- DASH CARDS -->
      <div class="dashboard">

        <!-- EARNINGS -->
        <div class="card earnings">
          <div class="card-top">
            <span class="title">TOTAL EARNINGS</span>
            <div class="icon">💵</div>
          </div>

          <h2>
            $<?= number_format($data['totalEarnings'] ?? 0, 2) ?>
          </h2>

          <p>Completed bookings revenue</p>
        </div>

        <!-- BOOKINGS -->
        <div class="card bookings">
          <div class="card-top">
            <span class="title">TOTAL BOOKINGS</span>
            <div class="icon">📊</div>
          </div>

          <h2>
            <?= ($data['activeBookings'] ?? 0) + ($data['pendingBookings'] ?? 0) ?>
          </h2>

          <p>Active + Pending bookings</p>
        </div>

        <!-- SPACES -->
        <div class="card occupancy">
          <div class="card-top">
            <span class="title">TOTAL SPACES</span>
            <div class="icon">📍</div>
          </div>

          <h2><?= $data['totalSpaces'] ?? 0 ?></h2>

          <p>Total parking locations</p>
        </div>

      </div>

      <!-- RECENT BOOKINGS -->
      <div class="card dark-card mt-4">
        <h5 class="text-white">Recent Bookings</h5>

        <table class="table table-dark table-hover mt-3">
          <thead>
            <tr>
              <th>Space</th>
              <th>Start</th>
              <th>End</th>
              <th>Total Cost</th>
              <th>Status</th>
            </tr>
          </thead>

          <tbody>
            <?php if (!empty($data['recentBookings'])): ?>
              <?php foreach ($data['recentBookings'] as $booking): ?>
                <tr>
                  <td><?= htmlspecialchars($booking['spot_name']) ?></td>

                  <td><?= $booking['start_time'] ?></td>
                  <td><?= $booking['end_time'] ?></td>

                  <td>$<?= number_format($booking['total_cost'], 2) ?></td>

                  <td>
                    <span class="badge bg-<?=
                      $booking['status'] == 'active' ? 'success' :
                      ($booking['status'] == 'pending' ? 'warning' : 'secondary')
                      ?>">
                      <?= $booking['status'] ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">No bookings yet</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- NOTIFICATIONS -->
      <div class="card dark-card mt-4">
        <h5 class="text-white">Notifications</h5>

        <?php if (!empty($data['notifications'])): ?>
          <?php foreach ($data['notifications'] as $n): ?>
            <div class="notif <?= $n['is_read'] == 0 ? 'notification-unread' : '' ?>">
              <?= htmlspecialchars($n['message']) ?>
              <small><?= $n['created_at'] ?></small>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-white">No notifications</p>
        <?php endif; ?>
      </div>

    </div>
  </div>

</body>

</html>
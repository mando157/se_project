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
  <title>OWNER PAGE</title>
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


  <a href="dashboard.html" class="active" onclick="setActive(this)">
    <i class="fa-solid fa-house"></i> Dashboard
  </a>

  <a href="spaces.html" onclick="setActive(this)">
    <i class="fa-solid fa-building"></i> My Spaces
  </a>

  <a href="availability.html" onclick="setActive(this)">
    <i class="fa-solid fa-calendar-days"></i> Availability
  </a>

  <a href="bookings.html" onclick="setActive(this)">
    <i class="fa-solid fa-book"></i> Bookings
  </a>

  <a href="earnings.html" onclick="setActive(this)">
    <i class="fa-solid fa-dollar-sign"></i> Earnings
  </a>


<div class="sidebar-actions">
    <button class="add-space-btn" onclick="openModal()">
  <span class="icon">
    <i class="fa-solid fa-plus"></i>
  </span>
  Add New Space
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

<div class="container mt-4 f7">

<h2 class="text-white f8">Command Center</h2>


<div class="dashboard">

  <div class="card earnings">
    <div class="card-top">
      <span class="title">TOTAL EARNINGS</span>
      <div class="icon">💵</div>
    </div>

    <h2>$42,890 <span class="up">+12.5%</span></h2>
    <p>vs. $38,120 last month</p>
  </div>

  <div class="card bookings">
    <div class="card-top">
      <span class="title">TOTAL BOOKINGS</span>
      <div class="icon">📊</div>
    </div>

    <h2>1,402 <span class="up">+8.1%</span></h2>
    <p>Active sessions: 284</p>
  </div>

  <div class="card occupancy">
    <div class="card-top">
      <span class="title">OCCUPANCY RATE</span>
      <div class="icon">📱</div>
    </div>

    <h2>94.2% <span class="down">-0.4%</span></h2>
    <p>Peak hour: 14:00 - 16:00</p>
  </div>

</div>

<div class="row mt-4 g-3">


<div class="col-md-8">
<div class="card dark-card f10">

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
<button class="mark-read-btn">Mark all read</button>
</div>

<div class="notif">New review received</div>
<div class="notif">Capacity warning triggered</div>
<div class="notif">Payment successfully completed</div>

<button class="view-all-btn">View All Notifications</button>

</div>

</div>

</div>


<div class="parking-section">
  <img src="assets/images/ezgif.com-webp-to-png-1558x1558.webp" alt="Parking Cars">
</div>

</div>

</div>



<div class="modal" id="modal">
  <div class="modal-box">

    <span class="close" onclick="closeModal()">×</span>

    <h2 class="modal-title">New Space</h2>


   <label>LOCATION REFERENCE</label>

<div class="input-with-icon">
  <i class="fa-solid fa-location-dot"></i>
  <input type="text" placeholder="e.g. 5th Avenue Loft">
</div>

    <div class="model-row">
      <div>
        <label>PRICE PER HOUR</label>
        <div class="input-with-icon">
  <i class="fa-solid fa-dollar-sign"></i>
  <input type="number" placeholder="0.00">
</div>
      </div>

      <div>
        <label>TOTAL SLOTS</label>
        <input type="number" value="1">
      </div>
    </div>

   <label>SPACE ATTRIBUTES</label>

<div class="tags">
  <button><i class="fa-solid fa-bolt"></i> EV Charging</button>

  <button class="active">
    <i class="fa-solid fa-shield-halved"></i> CCTV Security
  </button>

  <button>
    <i class="fa-solid fa-wheelchair"></i> Disabled Access
  </button>

  <button>
    <i class="fa-solid fa-warehouse"></i> Indoor
  </button>
</div>


    <div class="toggle-box">
      <div>
        <h4>Instant Activation</h4>
        <p>Listing will be live on the map immediately after verification.</p>
      </div>
      <label class="modal-switch">
        <input type="checkbox" checked>
        <span></span>
      </label>
    </div>

   
    <button class="submit">Register Space</button>

  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>assets/js/owner.js"></script>

</body>

</html>
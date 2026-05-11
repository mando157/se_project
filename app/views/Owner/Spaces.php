<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
   <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--* CSS Style Sheets -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/spots.css?v=1">
  <!--* Font Awsome -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css">
  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <title>Urban Space Management Dashboard</title>

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
<div class="header-row">

  <div class="card-header-text">
    <h2>Space Management</h2>
    <p>
      Oversee your urban parking assets, adjust pricing, and control real-time availability from a single interface.
    </p>
  </div>
<div class="spaces-container">

  <div class="space-box">
    <div class="space-icon">
      <i class="fa-solid fa-square-parking"></i>
    </div>

    <div class="space-title">Total Spaces</div>
    <div class="space-number">42</div>
  </div>

  <div class="space-box">
    <div class="space-icon active-icon">
      <i class="fa-solid fa-arrow-trend-up"></i>
    </div>

    <div class="space-title">Active Now</div>
    <div class="space-number active">38</div>
  </div>

</div>

</div>

<div class="cards-container">
  <div class="card">
    <div class="card-img">
      <img src="assets/images/smart-parking-system-with-automated-payment-space-management-without-physical-interaction_995578-22148.avif">
    </div>

    <div class="content">
      <div class="header">
        <i class="fas fa-crown"></i>
        PREMIUM ZONE
      </div>

      <div class="title">Grand Central Underground</div>

      <div class="info">
        <span><i class="fas fa-map-marker-alt"></i> 42nd St, New York, NY</span>
      </div>

<div class="price">
  <span>PRICE</span>
  <span>
    <span class="price-number">$12.50</span>
    <span class="price-unit">/hr</span>
  </span>
</div>

<div class="status">
  <div class="dot"></div>
  <span class="status-text">STATUS • Available</span>
</div>

<div class="toggle">
  <span>Auto-Renewing Availability</span>
  <div class="switch" id="toggle">
    <span></span>
  </div>
</div>

    </div>
  </div>


 <div class="portfolio-card">


  <div class="portfolio-icon">
    <i class="fas fa-plus"></i>
  </div>

  <div class="portfolio-content">
    <h3>Grow Your Portfolio</h3>
    <p>
      Instantly list a new parking bay or lot in under 2 minutes.
    </p>
  </div>


  <button class="portfolio-btn">
    <i class="fas fa-magic"></i> Launch Setup Wizard
  </button>

</div>

</div>




<div class="zones-card-container">

 <div class="zones-wrapper">


  <div class="zones-header">
    <h2>Managed Zones</h2>

    <div class="header-actions">
      <button>FILTER BY CITY</button>
      <button>SORT BY REVENUE</button>
    </div>
  </div>


  <div class="zone-row">
    
    <img src="assets/images/OIP.webp" class="zone-img">

    <div class="zone-info">
      <h3>Brooklyn High-Rise Bay 14</h3>
      <p>Zone B · Level 4 · Lot 1401</p>
    </div>

    <div class="zone-rate">
      <span>HOURLY RATE</span>
      <h4>$8.00</h4>
    </div>

    <div class="zone-occupancy">
      <span>OCCUPANCY</span>

      <div class="bar">
        <div class="fill" style="width:64%"></div>
      </div>

      <small class="occupancy-value mid">64%</small>
    </div>

    <div class="zone-actions">
      <label class="switch">
        <input type="checkbox" checked>
        <span></span>
      </label>

      <i class="fa fa-pen"></i>
      <i class="fa fa-trash"></i>
    </div>

  </div>



  <div class="zone-row">
    
    <img src="assets/images/OIP (2).webp" class="zone-img">

    <div class="zone-info">
      <h3>The Glass House Private Drive</h3>
      <p>Zone A · Entrance 2</p>
    </div>

    <div class="zone-rate">
      <span>HOURLY RATE</span>
      <h4>$15.75</h4>
    </div>

    <div class="zone-occupancy">
      <span>OCCUPANCY</span>

      <div class="bar">
        <div class="fill pink" style="width:100%"></div>
      </div>

      <small class="occupancy-value high">100%</small>
    </div>

    <div class="zone-actions">
      <label class="switch">
        <input type="checkbox" checked>
        <span></span>
      </label>

      <i class="fa fa-pen"></i>
      <i class="fa fa-trash"></i>
    </div>

  </div>


  <div class="zone-row">
    
    <img src="assets/images/download.webp" class="zone-img">

    <div class="zone-info">
      <h3>Old Town Alley (Reserved)</h3>
      <p>Zone C • Back Access</p>
    </div>

    <div class="zone-rate">
      <span>HOURLY RATE</span>
      <h4>$5.25</h4>
    </div>

    <div class="zone-occupancy">
      <span>OCCUPANCY</span>

      <div class="bar">
        <div class="fill" style="width:0%"></div>
      </div>
      <small class="occupancy-value low">0%</small>
    </div>

    <div class="zone-actions">
      <label class="switch">
        <input type="checkbox" checked>
        <span></span>
      </label>

      <i class="fa fa-pen"></i>
      <i class="fa fa-trash"></i>
    </div>

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

    <div class="row">
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
</body>


<script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>assets/js/spots.js"></script>

    
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owner.css">
    <title>OWNER PAGE</title>


    <style>
        /* --- CSS START --- */
        body {
            background: #0f172a;
            color: #f1f5f9;
            font-family: sans-serif;
            margin: 0;
        }

        .f1 {
            background: #0f172a;
            border-bottom: 1px solid #334155;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .f2 {
            width: 300px;
            border-radius: 20px;
            border: none;
            padding: 8px 12px;
            background: #1b1d24;
            color: white;
        }

        .f3 {
            display: flex;
            align-items: center;
        }

        .f4 {
            color: #aaa !important;
            margin-right: 20px !important;
            /* تعديل المسافة لتكون منطقية */
        }

        .f4.f15 {
            color: white !important;
            border-bottom: 2px solid #7c5cff;
        }

        .f5 {
            border-radius: 50px;
            background: #7c5cff;
            color: white;
        }

        .f5:hover {
            background: white;
            color: black;
        }

        .f6 {
            font-size: 20px;
            color: white;
        }

        .f6:hover {
            color: #7c5cff;
        }

        .f10 {
            background: #1e293b;
            border-radius: 15px;
            padding: 20px;
            border: 1px solid #334155;
        }

        h2,
        h3,
        h5 {
            color: #ffffff;
        }

        p {
            color: #94a3b8;
        }

        .notif {
            background: #0f172a;
            padding: 10px;
            border-radius: 10px;
            margin-top: 10px;
            font-size: 14px;
            color: #e2e8f0;
            border-left: 4px solid #3b82f6;
        }

        .notifications-card {
            background: #0b1220;
            border: 1px solid #1f2937;
            border-radius: 15px;
            padding: 20px;
        }

        .notif-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mark-read-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
            opacity: 0.75;
            transition: 0.2s;
        }

        .view-all-btn {
            width: 100%;
            margin-top: 12px;
            padding: 6px 10px;
            border: none;
            border-radius: 8px;
            background: #1e40af;
            color: white;
            font-size: 12px;
            cursor: pointer;
        }

        .f20 {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
        }

        .f20 button {
            background: #1e3a8a;
            color: white;
            border: none;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 12px;
            opacity: 0.6;
            transition: 0.2s;
            cursor: pointer;
        }

        .f20 button.active {
            opacity: 1;
            background: #1d4ed8;
            box-shadow: 0 0 10px rgba(29, 78, 216, 0.4);
        }

        /* Sidebar Styles */
        .sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #0b1220;
            border-right: 1px solid #1f2937;
            padding: 20px;
            display: flex;
            flex-direction: column;
            transition: 0.3s;
            z-index: 1100;
        }

        .sidebar.hide {
            transform: translateX(-100%);
        }

        .sidebar-top {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding-bottom: 15px;
            margin-bottom: 15px;
            border-bottom: 1px solid #1f2937;
        }

        .sidebar-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
        }

        .sidebar-title i {
            color: #7c5cff;
            text-shadow: 0 0 10px rgba(124, 92, 255, 0.5);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #94a3b8;
            text-decoration: none;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 6px;
            font-size: 14px;
            transition: 0.2s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #1d4ed8;
            color: white;
        }

        .add-space-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            border: none;
            border-radius: 30px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.35);
            cursor: pointer;
            width: 100%;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 220px;
            transition: 0.3s;
        }

        .main-content.full {
            margin-left: 0;
        }

        .dashboard {
            display: flex;
            gap: 20px;
            padding: 20px;
            flex-wrap: wrap;
        }

        .card {
            width: 280px;
            padding: 20px;
            border-radius: 15px;
            background: #1a1b23;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            border: none;
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .up {
            color: #00d68f;
        }

        .down {
            color: #ff4d4d;
        }

        #revenueChart {
            width: 100% !important;
            height: 350px !important;
        }

        .parking-section {
            margin: 40px 20px;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            background: #1a1b23;
        }

        .parking-section img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            opacity: 0.8;
        }

        /* Modal Customization */
        .modal {
            position: fixed;
            top: 0;
            right: 0;
            width: 380px;
            height: 100vh;
            background: #0f172a;
            transform: translateX(100%);
            transition: 0.3s ease;
            z-index: 2000;
            display: block;
            visibility: hidden;
            border-left: 1px solid #1f2937;
        }

        .modal.show {
            transform: translateX(0);
            visibility: visible;
            opacity: 1;
        }

        .modal-box {
            padding: 25px;
            overflow-y: auto;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: none;
            background: #1f2937;
            color: #fff;
            margin-bottom: 5px;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #7c5cff;
        }

        .input-with-icon input {
            padding-left: 35px;
        }

        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tags button {
            padding: 8px 12px;
            border-radius: 10px;
            border: none;
            background: #1f2937;
            color: #fff;
            cursor: pointer;
            font-size: 13px;
        }

        .tags button.active {
            background: #6366f1;
        }

        .submit {
            width: 100%;
            padding: 15px;
            border-radius: 15px;
            border: none;
            background: linear-gradient(45deg, #6366f1, #8b5cf6);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }

        .modal-switch {
            position: relative;
            width: 40px;
            height: 20px;
            display: inline-block;
        }

        .modal-switch input {
            display: none;
        }

        .modal-switch span {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #555;
            border-radius: 20px;
            cursor: pointer;
        }

        .modal-switch span::before {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            background: #fff;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            transition: 0.3s;
        }

        .modal-switch input:checked+span {
            background: #6366f1;
        }

        .modal-switch input:checked+span::before {
            transform: translateX(20px);
        }

        .close {
            position: absolute;
            right: 15px;
            top: 15px;
            cursor: pointer;
            font-size: 24px;
            color: #fff;
            z-index: 10;
        }

        /* --- CSS END --- */
    </style>
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

    <h2>
  <span id="earningsValue"></span>
  <span class="up">+12.5%</span>
</h2>
    <p>vs. $38,120 last month</p>
  </div>

  <div class="card bookings">
    <div class="card-top">
      <span class="title">TOTAL BOOKINGS</span>
      <div class="icon">📊</div>
    </div>

   <h2>
  <span id="bookingsValue"></span>
  <span class="up">+8.1%</span>
</h2>
    <p>Active sessions: 284</p>
  </div>

  <div class="card occupancy">
    <div class="card-top">
      <span class="title">OCCUPANCY RATE</span>
      <div class="icon">📱</div>
    </div>

    <h2>
  <span id="occupancyValue"></span>
  <span class="down">-0.4%</span>
</h2>
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
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/owner.js"></script>


    <script>
        /* --- JS START --- */
       /* =========================
   GLOBAL DATA (MY SPACES)
========================= */

let spaces = [
  {
    id: 1,
    totalSlots: 10,
    bookedSlots: 7,
    earnings: 5000,
    bookings: 20
  },
  {
    id: 2,
    totalSlots: 5,
    bookedSlots: 3,
    earnings: 2000,
    bookings: 10
  }
];

let chart;

/* =========================
   INIT
========================= */

window.onload = function () {
  setWeekly();
  loadDashboardValues();
};

/* =========================
   DASHBOARD STATS
========================= */

function getTotalSpaces() {
  return spaces.length;
}

function getTotalSlots() {
  let total = 0;
  spaces.forEach(s => total += s.totalSlots);
  return total;
}

function getActiveBookings() {
  let total = 0;
  spaces.forEach(s => total += s.bookings);
  return total;
}

function getTotalEarnings() {
  let total = 0;
  spaces.forEach(s => total += s.earnings);
  return "$" + total;
}

function getPendingBookings() {
  return Math.floor(getActiveBookings() * 0.2);
}

function getOccupancyRate() {
  let totalSlots = 0;
  let bookedSlots = 0;

  spaces.forEach(s => {
    totalSlots += s.totalSlots;
    bookedSlots += s.bookedSlots;
  });

  if (totalSlots === 0) return "0%";

  let rate = (bookedSlots / totalSlots) * 100;
  return rate.toFixed(1) + "%";
}

/* =========================
   LOAD DASHBOARD UI
========================= */

function loadDashboardValues() {
  // لو ضفتهم في HTML تقدر تعرضهم
  const earningsEl = document.getElementById("earningsValue");
  const bookingsEl = document.getElementById("bookingsValue");
  const occupancyEl = document.getElementById("occupancyValue");

  if (earningsEl) earningsEl.innerHTML = getTotalEarnings();
  if (bookingsEl) bookingsEl.innerHTML = getActiveBookings();
  if (occupancyEl) occupancyEl.innerHTML = getOccupancyRate();
}

/* =========================
   CHART (WEEKLY / MONTHLY)
========================= */

function setWeekly() {
  setChartActive("weekly");

  drawChart(
    ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
    [1200, 1500, 1800, 2200, 2000, 2500, 2700]
  );
}

function setMonthly() {
  setChartActive("monthly");

  drawChart(
    ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
    [8000, 9500, 11000, 12000]
  );
}

function setChartActive(type) {
  const weeklyBtn = document.getElementById("weeklyBtn");
  const monthlyBtn = document.getElementById("monthlyBtn");

  if (!weeklyBtn || !monthlyBtn) return;

  weeklyBtn.classList.remove("active");
  monthlyBtn.classList.remove("active");

  if (type === "weekly") {
    weeklyBtn.classList.add("active");
  } else {
    monthlyBtn.classList.add("active");
  }
}

function drawChart(labels, data) {
  const ctx = document.getElementById("revenueChart");
  if (!ctx) return;

  if (chart) {
    chart.destroy();
  }

  chart = new Chart(ctx, {
    type: "line",
    data: {
      labels: labels,
      datasets: [{
        data: data,
        borderWidth: 2,
        tension: 0.4,
        borderColor: "#7c5cff",
        backgroundColor: "rgba(124, 92, 255, 0.15)",
        fill: true
      }]
    },
    options: {
      plugins: {
        legend: { display: false }
      },
      responsive: true,
      maintainAspectRatio: false
    }
  });
}

/* =========================
   UI FUNCTIONS
========================= */

function setActive(el) {
  document.querySelectorAll(".sidebar a")
    .forEach(a => a.classList.remove("active"));

  el.classList.add("active");
}

function toggleSidebar() {
  document.querySelector(".sidebar")?.classList.toggle("hide");
  document.querySelector(".main-content")?.classList.toggle("full");
}

/* =========================
   MODAL
========================= */

function openModal() {
  document.getElementById("modal")?.classList.add("show");
}

function closeModal() {
  document.getElementById("modal")?.classList.remove("show");
}

window.openModal = openModal;
window.closeModal = closeModal;

/* close modal when clicking outside */
window.addEventListener("click", function (e) {
  const modal = document.getElementById("modal");
  if (e.target === modal) {
    modal.classList.remove("show");
  }
});
document.querySelectorAll(".tags button").forEach(btn => {
  btn.addEventListener("click", () => {
    btn.classList.toggle("active");
  });
});

        /* --- JS END --- */
    </script>
</body>

</html>
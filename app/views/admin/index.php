<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css?v=1">
<title>UrbanKinetic</title>
</head>

<body>

<div class="container">

<div class="sidebar">
<div>
<div class="logo">Control Center</div>

<div class="menu">
<a href="<?= BASE_URL ?>Admin/index" class="active"><i class="fa-solid fa-table-columns"></i> Dashboard</a>
<a href="<?= BASE_URL ?>Admin/earnings"><i class="fa-solid fa-wallet"></i> Earnings</a>
<a href="<?= BASE_URL ?>Admin/users"><i class="fa-solid fa-users"></i> User Management</a>
</div>
</div>

<a href="<?= BASE_URL ?>Auth/logout" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<div class="main">

<div class="topbar">
<h1>UrbanKinetic</h1>
    <div class="se-pro">
        <div class="search">
        <i class="fa-solid fa-search"></i>
        <input type="search" placeholder="Search grid nodes...">
        </div>
        <img class="profile" src="<?= BASE_URL ?>assets/admin-images/profile.svg" alt="">
    </div>
</div>

<div class="cards">

<div class="card big-card">
<h4>TOTAL NETWORK REVENUE</h4>
<h2>$142,840.50</h2>
<span class="badge">+12.4% vs last month</span>
</div>

<div class="card small-card">
<p>ACTIVE SESSIONS</p>
<h3>1,204</h3>
<p>UTILIZATION 76%</p>
<div class="progress"><span></span></div>
</div>

</div>

<h2 class="section-title">Real-time Bookings</h2>

<table>
<tr>
<th>System Identity</th>
<th>Grid Location</th>
<th>Usage Time</th>
<th>Status</th>
<th>Fee</th>
</tr>

<tr>
<td>James Smith</td>
<td>North Plaza</td>
<td>3h 20m</td>
<td><span class="status active-status">ACTIVE</span></td>
<td>$24.50</td>
</tr>

<tr>
<td>Alice Miller</td>
<td>Underground A14</td>
<td>1h 45m</td>
<td><span class="status reserve">RESERVED</span></td>
<td>$12.00</td>
</tr>

<tr>
<td>Robert Kane</td>
<td>Downtown Garage</td>
<td>45m</td>
<td><span class="status overdue">OVERDUE</span></td>
<td>$35.00</td>
</tr>
</table>

<div class="bottom">

<div class="chart">
<h2>Analysis</h2>
<div class="bars">
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
</div>
</div>

<div class="sector">
<div class="overlay">
<h2>Sector 7 Central</h2>
<p>System-optimized dynamic pricing is currently yielding peak efficiency across all 600 nodes in this sector.</p>
</div>
</div>

</div>

</div>
</div>

</body>
</html>
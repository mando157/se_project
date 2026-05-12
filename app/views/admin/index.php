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
<?php
$stats = $stats ?? [];
$recentBookings = $recentBookings ?? [];
$totalRevenue = (float)($stats['totalRevenue'] ?? 0);
$lastMonthRevenue = (float)($stats['lastMonthRevenue'] ?? 0);
$activeBookings = (int)($stats['activeBookings'] ?? 0);
$totalSlots = (int)($stats['totalSlots'] ?? 0);
$utilization = $totalSlots > 0 ? round(($activeBookings / $totalSlots) * 100, 1) : 0;
$revenueChange = $lastMonthRevenue > 0
    ? round((($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
    : ($totalRevenue > 0 ? 100 : 0);
?>

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
<h2>$<?= number_format($totalRevenue, 2) ?></h2>
<span class="badge"><?= $revenueChange >= 0 ? '+' : '' ?><?= $revenueChange ?>% vs last month</span>
</div>

<div class="card small-card">
<p>ACTIVE SESSIONS</p>
<h3><?= $activeBookings ?></h3>
<p>UTILIZATION <?= $utilization ?>%</p>
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
<?php if (!empty($recentBookings)): ?>
<?php foreach ($recentBookings as $booking): ?>
<?php
$status = strtolower($booking['status'] ?? 'pending');
$statusClass = 'reserve';
if ($status === 'active') {
    $statusClass = 'active-status';
} elseif ($status === 'completed') {
    $statusClass = 'active-status';
} elseif ($status === 'cancelled') {
    $statusClass = 'overdue';
}
?>
<tr>
<td><?= htmlspecialchars($booking['fullName'] ?? 'Unknown') ?></td>
<td><?= htmlspecialchars($booking['location'] ?? '-') ?></td>
<td><?= number_format((float)($booking['duration'] ?? 0), 1) ?>h</td>
<td><span class="status <?= $statusClass ?>"><?= strtoupper($status) ?></span></td>
<td>$<?= number_format((float)($booking['total_cost'] ?? 0), 2) ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td colspan="5">No bookings found.</td>
</tr>
<?php endif; ?>
</table>

<div style="margin-top: 16px; color: #c9d5ff;">
<small>
Owners: <?= (int)($stats['activeOwners'] ?? 0) ?> |
Drivers: <?= (int)($stats['activeDrivers'] ?? 0) ?> |
Spots: <?= (int)($stats['totalSpots'] ?? 0) ?> |
Slots: <?= (int)($stats['totalSlots'] ?? 0) ?>
</small>
</div>

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">
    <title>Urban kinetic</title>
</head>
<body>
<?php
$totalRevenue = (float)($totalRevenue ?? 0);
$monthlyRevenue = $monthlyRevenue ?? [];
$topOwners = $topOwners ?? [];
$recentCompletedBookings = $recentCompletedBookings ?? [];
$latestMonthTotal = !empty($monthlyRevenue) ? (float)$monthlyRevenue[count($monthlyRevenue) - 1]['total'] : 0;
$prevMonthTotal = count($monthlyRevenue) > 1 ? (float)$monthlyRevenue[count($monthlyRevenue) - 2]['total'] : 0;
$monthlyChange = $prevMonthTotal > 0 ? round((($latestMonthTotal - $prevMonthTotal) / $prevMonthTotal) * 100, 1) : ($latestMonthTotal > 0 ? 100 : 0);
?>
<div class="container">
    <div class="sidebar">
<div>
<div class="logo">Control Center</div>

<div class="menu">
<a href="<?= BASE_URL ?>Admin/index"><i class="fa-solid fa-table-columns"></i> Dashboard</a>
<a href="<?= BASE_URL ?>Admin/earnings" class="active"><i class="fa-solid fa-wallet"></i> Earnings</a>
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
<span class="badge"><?= $monthlyChange >= 0 ? '+' : '' ?><?= $monthlyChange ?>% vs last month</span>
</div>

<div class="sector">
<div class="overlay">
<h2>Top Owners</h2>
<p><?= count($topOwners) ?> owner(s) contributing highest revenue right now.</p>
</div>
</div>

</div>

<h2 class="section-title">Recent Completed Bookings</h2>

<table>
    <tr>
        <th>User</th>
        <th>Spot</th>
        <th>Date</th>
        <th>Rent</th>
    </tr>
    <?php if (!empty($recentCompletedBookings)): ?>
    <?php foreach ($recentCompletedBookings as $row): ?>
    <tr>
        <td><?= htmlspecialchars($row['fullName'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['spot_name'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['date'] ?? '-') ?></td>
        <td>$<?= number_format((float)($row['total_cost'] ?? 0), 2) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
        <td colspan="4">No completed bookings found.</td>
    </tr>
    <?php endif; ?>
</table>

<h2 class="section-title" style="margin-top:24px;">Top Owners By Revenue</h2>
<table>
    <tr>
        <th>Owner</th>
        <th>Email</th>
        <th>Revenue</th>
    </tr>
    <?php if (!empty($topOwners)): ?>
    <?php foreach ($topOwners as $owner): ?>
    <tr>
        <td><?= htmlspecialchars($owner['fullName'] ?? '-') ?></td>
        <td><?= htmlspecialchars($owner['email'] ?? '-') ?></td>
        <td>$<?= number_format((float)($owner['revenue'] ?? 0), 2) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
        <td colspan="3">No owners found.</td>
    </tr>
    <?php endif; ?>
</table>


</div>
</body>
</html>
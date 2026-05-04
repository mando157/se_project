<?php
include("config/db.php");

$user_id = 1;

$sql = "SELECT b.*, p.spot_name 
        FROM bookings b
        JOIN parking_spots p ON b.spot_id = p.spot_id
        WHERE b.user_id = $user_id";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/booking.css">

    <title>My Bookings</title>
</head>

<body>

<section class="header">
    <nav class="navbar">
        <div class="logo">
            <h2>ParkFlow</h2>
        </div>
        <ul class="nav-links">
            <li><a href="./Driver.php">live Map</a></li>
            <li><a href="./map.php">Find Parking</a></li>
            <li><a href="./booking.php" class="active">My Bookings</a></li>
            <li><a href="./notify.html">Notifications</a></li>
        </ul>
    </nav>
</section>

<div class="container content">

    <div class="page-head">
        <h1>Booking History</h1>
        <p>Manage your active reservations and review past urban stays.</p>
    </div>

    <div class="filters">
        <button class="active">All</button>
        <button>Active</button>
        <button>Completed</button>
        <button>Cancelled</button>
    </div>

    <!-- ACTIVE BOOKINGS -->
    <?php while($row = mysqli_fetch_assoc($result)) { ?>

    <div class="booking-card">

        <div class="card-img">
            <img src="./assets/images/login-bg.jpg">
            <span class="badge"><?php echo strtoupper($row['status']); ?></span>
        </div>

        <div class="card-body">

            <div class="top">
                <div>
                    <h3><?php echo $row['spot_name']; ?></h3>

                    <span class="location">
                        <i class="fa fa-location-dot"></i>
                        Downtown
                    </span>
                </div>

                <div class="price">
                    <h2>$<?php echo $row['total_cost']; ?></h2>
                    <span>Total Paid</span>
                </div>
            </div>

            <div class="grid-info">

                <div>
                    <small>Date</small>
                    <p><?php echo $row['created_at']; ?></p>
                </div>

                <div>
                    <small>Duration</small>
                    <p><?php echo $row['duration']; ?> Hours</p>
                </div>

                <div>
                    <small>Start</small>
                    <p><?php echo $row['start_time']; ?></p>
                </div>

                <div>
                    <small>End</small>
                    <p><?php echo $row['end_time']; ?></p>
                </div>

            </div>

            <div class="actions">
                <button class="btn-primary">Extend Booking</button>
                <button class="btn-dark">Get Directions</button>
            </div>

        </div>

    </div>

    <?php } ?>

</div>

</body>
</html>
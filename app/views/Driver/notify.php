<?php
include("/config/db.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ParkFlow Notifications</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="./assets/css/notify.css">
</head>

<body>

    <!-- ===================== NAVBAR ===================== -->
    <section class="header">
        <nav class="navbar">
            <div class="logo">
                <h2>ParkFlow</h2>
            </div>
            <ul class="nav-links">
                <li><a href="./Driver.php">Find Parking</a></li>
                <li><a href="./booking.html">My Bookings</a></li>
                <li><a href="#" class="active">Notifications</a></li>
            </ul>
        </nav>
    </section>
    <!-- ===================== MAIN ===================== -->
    <div class="container mt-5">

        <!-- ===== HEADER ===== -->
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h1 class="title">Notification Center</h1>
                <p class="sub">Stay updated with your parking activity</p>
            </div>

            <button class="btn btn-dark">Mark all as read</button>
        </div>

        <div class="row">

            <!-- ===================== LEFT ===================== -->
            <div class="col-md-8">
                <!-- ===== NOTIFICATION CARD ===== -->
                <div class="col-md-8">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="notification <?= $row['type']; ?>">
                            <div class="line"></div>
                            <div class="icon">
                                <i class="fa 
                <?php
                if ($row['type'] == 'danger')
                    echo 'fa-exclamation-triangle';
                elseif ($row['type'] == 'warning')
                    echo 'fa-clock';
                else
                    echo 'fa-check';
                ?>
            "></i>
                            </div>
                            <div class="content">
                                <div class="top">
                                    <h5>
                                        <?= $row['title']; ?>
                                    </h5>
                                    <?php if ($row['type'] == 'danger'): ?>
                                        <span class="badge bg-danger">Urgent</span>
                                    <?php endif; ?>
                                </div>
                                <p>
                                    <?= $row['message']; ?>
                                </p>
                                <div class="actions">
                                    <button class="btn btn-danger btn-sm">Extend</button>
                                    <button class="btn btn-secondary btn-sm">Details</button>
                                </div>
                            </div>
                            <span class="time">
                                <?= $row['time']; ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>
                <!-- ===================== RIGHT ===================== -->
                <div class="col-md-4">

                    <!-- ===== SESSION CARD ===== -->
                    <div class="session-card">
                        <h4>Active Session</h4>
                        <h2>Skyline Plaza</h2>
                        <p>Level 3 • Spot B-14</p>

                        <div class="timer" id="timer">00:15:00</div>

                        <button class="btn btn-light w-100">Extend</button>
                    </div>

                    <!-- ===== SUMMARY ===== -->
                    <div class="summary mt-4">
                        <h5>Activity</h5>

                        <div class="d-flex justify-content-between">
                            <span>Savings</span>
                            <b>$42</b>
                        </div>

                        <div class="progress mt-2">
                            <div class="progress-bar bg-success" style="width:75%"></div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
        <script src="./assets/js/notify.js"></script>
</body>

</html>
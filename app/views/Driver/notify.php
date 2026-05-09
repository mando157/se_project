<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ParkFlow Notifications</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/notify.css">
</head>

<body>

    <!-- ===================== NAVBAR ===================== -->
    <section class="header">
        <nav class="navbar">
            <div class="logo">
                <h2>ParkFlow</h2>
            </div>
            <ul class="nav-links">
                <li><a href="<?= BASE_URL ?>Driver">Find Parking</a></li>
                <li><a href="<?= BASE_URL ?>Driver/MyBookings">My Bookings</a></li>
                <li><a href="<?= BASE_URL ?>Driver/notify" class="active">Notifications</a></li>
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
                <div>
                    <?php foreach ($notifications as $row): ?>
                        <div class="notification <?= htmlspecialchars($row['type']); ?>">
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
        ?>"></i>
                            </div>
                            <div class="content">
                                <div class="top">
                                    <h5>
                                        <?= htmlspecialchars($row['title']); ?>
                                    </h5>
                                    <?php if ($row['type'] == 'danger'): ?>
                                        <span class="badge bg-danger">
                                            Urgent
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p>
                                    <?= htmlspecialchars($row['message']); ?>
                                </p>
                                <div class="actions">
                                    <button class="btn btn-danger btn-sm">
                                        Extend
                                    </button>
                                    <button class="btn btn-secondary btn-sm">
                                        Details
                                    </button>
                                </div>
                            </div>
                            <span class="time">
                                <?= htmlspecialchars($row['time']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- ===================== RIGHT ===================== -->
                <div class="col-md-4">

                    <!-- ===== SESSION CARD ===== -->
                    <div class="session-card">
                        <h4>Active Session</h4>
                        <h2>
                            <?= $booking['spot_name'] ?? 'No active session'; ?>
                        </h2>
                        <p>Level <?= $booking['level'] ?? '' ?> • Spot B-14</p>

                        <div class="timer" id="timer">
                            <?= $booking['duration'] ?? '00:00:00' ?>
                        </div>

                        <a class="btn btn-light w-100" href="<?= BASE_URL ?>Driver/MyBookings ?>">Extend</a>
                    </div>
                </div>
            </div>
        </div>

        <script>

            const bookingDate =
                "<?= $booking['date'] ?? '' ?>";

            const startTime =
                "<?= $booking['start_time'] ?? '' ?>";

            const endTime =
                "<?= $booking['end_time'] ?? '' ?>";

        </script>
        <script src="<?= BASE_URL ?>assets/js/notify.js?<?= time() ?>"></script>
</body>

</html>
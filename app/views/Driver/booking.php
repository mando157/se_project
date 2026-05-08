<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/booking.css?v=1.0">
    <title>My Bookings</title>
</head>

<body>

    <section class="header">
        <nav class="navbar">
            <div class="logo">
                <h2>ParkFlow</h2>
            </div>
            <ul class="nav-links">
                <li>
                    <a href="<?= BASE_URL ?>Driver">
                        live Map
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>Driver/map">
                        Find Parking
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>Driver/MyBookings" class="active">
                        My Bookings
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>Driver/notify">
                        Notifications
                    </a>
                </li>
            </ul>
        </nav>
    </section>
    <div class="container content">
        <div class="page-head">
            <h1>Booking History</h1>
            <p>
                Manage your active reservations
                and review past urban stays.
            </p>
        </div>
        <div class="filters">
            <button class="active">
                All
            </button>
            <button>
                Active
            </button>
            <button>
                Completed
            </button>
            <button>
                Cancelled
            </button>
        </div>
        <!-- BOOKINGS -->
        <?php foreach ($bookings as $row) { ?>
            <div class="booking-card">
                <div class="card-img">
                    <img src="<?= BASE_URL ?>assets/images/login-bg.jpg">
                    <span class="badge">
                        <?= htmlspecialchars(strtoupper($row['status'])) ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="top">
                        <div>
                            <h3>
                                <?= htmlspecialchars($row['spot_name']) ?>
                            </h3>
                            <span class="location">
                                <i class="fa fa-location-dot"></i>
                                Downtown
                            </span>
                        </div>
                        <div class="price">
                            <h2>
                                $<?= htmlspecialchars($row['total_cost']) ?>
                            </h2>
                            <span>
                                Total Paid
                            </span>
                        </div>
                    </div>
                    <!-- INFO -->
                    <div class="grid-info">
                        <div>
                            <small>Date</small>
                            <p>
                                <?= htmlspecialchars($row['date']) ?>
                            </p>
                        </div>
                        <div>
                            <small>Duration</small>
                            <p>
                                <?= htmlspecialchars($row['duration']) ?>
                                Hours
                            </p>
                        </div>
                        <div>
                            <small>Start</small>
                            <p>
                                <?= htmlspecialchars($row['start_time']) ?>
                            </p>
                        </div>
                        <div>
                            <small>End</small>
                            <p>
                                <?= htmlspecialchars($row['end_time']) ?>
                            </p>
                        </div>
                    </div>
                    <!-- ACTIONS -->
                    <div class="actions">
                        <!-- CANCEL -->
                        <?php if ($row['status'] != 'cancelled') { ?>
                            <form action="<?= BASE_URL ?>Driver/cancelBooking" method="POST">
                                <input type="hidden" name="booking_id" value="<?= $row['booking_id'] ?>">
                                <button class="btn-dark">
                                    Cancel Booking
                                </button>
                            </form>
                        <?php } ?>
                        <!-- EXTEND -->
                        <?php if ($row['status'] == 'paid') { ?>

                            <form action="<?= BASE_URL ?>Driver/payment" method="GET">

                                <input type="hidden" name="type" value="extend">

                                <input type="hidden" name="booking_id" value="<?= $row['booking_id'] ?>">

                                <input type="number" name="extra_hours" min="1" value="1" class="extend-input" required>

                                <button class="btn-primary">

                                    Extend Booking

                                </button>

                            </form>

                        <?php } ?>
                        <!-- PAY NOW -->
                        <?php if ($row['status'] == 'pending') { ?>
                            <form action="<?= BASE_URL ?>Driver/payment" method="GET">
                                <input type="hidden" name="booking_id" value="<?= $row['booking_id'] ?>">
                                <button class="btn-success">
                                    Pay Now
                                </button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</body>

</html>
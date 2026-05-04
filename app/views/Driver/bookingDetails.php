<?php
include("config/db.php");

if (!isset($_GET['spot_id'])) {
    die("No spot selected");
}

$spot_id = intval($_GET['spot_id']);

$sql = "SELECT * FROM parking_spots WHERE spot_id = $spot_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['spot_name'];
    $price = $row['price'];
} else {
    die("Spot not found");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/booking-details.css">

    <title>Booking Details</title>
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
                <li><a href="./booking.php">My Bookings</a></li>
                <li><a href="./notify.html">Notifications</a></li>
            </ul>
        </nav>
    </section>

    <div class="container-fluid main">

        <div class="row">

            <div class="back" onclick="history.back()">
                <i class="fa fa-arrow-left"></i> Back
            </div>

            <h1><?php echo $name; ?></h1>
            <p class="sub">⭐ 4.8 • Premium Parking</p>

            <div class="col-lg-7 left">

                <div class="main-img">
                    <img src="./assets/images/login-bg.jpg">
                </div>

                <div class="features">
                    <div><i class="fa fa-video"></i> CCTV</div>
                    <div><i class="fa fa-wheelchair"></i> Accessible</div>
                    <div><i class="fa fa-bolt"></i> EV Charging</div>
                </div>

            </div>

            <div class="col-lg-5 right">

                <div class="card-box">

                    <h3>Reservation Details</h3>

                    <label>Date</label>
                    <input type="date" id="date">

                    <div class="row">
                        <div class="col">
                            <label>Start</label>
                            <input type="time" id="start">
                        </div>
                        <div class="col">
                            <label>End</label>
                            <input type="time" id="end">
                        </div>
                    </div>

                    <div class="price-box">
                        <p class="price">Rate: $<?php echo $price; ?>/hr</p>

                        <p id="duration">Duration: --</p>

                        <hr>

                        <h2 id="total">$0.00</h2>
                    </div>

                    <form action="confirm-booking.php" method="POST">

                        <input type="hidden" name="spot_id" value="<?php echo $spot_id; ?>">
                        <input type="hidden" name="total" id="totalInput">
                        <input type="hidden" name="duration" id="durationInput">

                        <input type="hidden" name="start" id="startInput">
                        <input type="hidden" name="end" id="endInput">

                        <button type="submit" class="confirm-btn">
                            Confirm Booking
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <script>
        const price = <?php echo $price; ?>;

        function calc() {
            let s = document.getElementById("start").value;
            let e = document.getElementById("end").value;

            if (s && e) {
                let st = new Date("1970-01-01T" + s);
                let en = new Date("1970-01-01T" + e);

                let diff = (en - st) / (1000 * 60 * 60);

                if (diff < 0) diff = 0;

                document.getElementById("duration").innerText = "Duration: " + diff + " hrs";
                document.getElementById("total").innerText = "$" + (diff * price).toFixed(2);

                document.getElementById("totalInput").value = diff * price;
                document.getElementById("durationInput").value = diff;

                document.getElementById("startInput").value = s;
                document.getElementById("endInput").value = e;
            }
        }

        document.getElementById("start").addEventListener("change", calc);
        document.getElementById("end").addEventListener("change", calc);
    </script>

</body>

</html>
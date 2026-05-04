<?php
include("/config/db.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$booking_id = intval($_GET['booking_id']);

$sql = "SELECT * FROM bookings WHERE booking_id = $booking_id";
$result = $conn->query($sql);
$booking = $result->fetch_assoc();

$spot_id = $booking['spot_id'];

$sql2 = "SELECT * FROM parking_spots WHERE spot_id = $spot_id";
$result2 = $conn->query($sql2);
$spot = $result2->fetch_assoc();
?>
<?php
if (isset($_POST['pay'])) {

    $sql = "UPDATE bookings 
            SET status='paid' 
            WHERE booking_id=$booking_id";

    if ($conn->query($sql)) {
        echo "<script>alert('Payment Successful');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>UrbanKinetic Checkout</title>
    <link rel="stylesheet" href="./assets/css/payment.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <header class="header">
        <div class="logo">
            <span class="brand">UrbanKinetic</span>
            <span class="divider"></span>
            <span class="sub">Secure Checkout</span>
        </div>
        <a href="./booking-details.html" class="back"><i class="fa fa-arrow-left"></i> Cancel Booking</a>
    </header>
    <main class="container">

        <!-- ===================== LEFT SECTION ===================== -->
        <section class="left">

            <!-- ===== Steps ===== -->
            <div class="steps">
                <div class="step active">1</div>
                <div class="line" id="line"></div>
                <div class="step" id="step-2">2</div>
            </div>

            <h2>Select Payment Method</h2>

            <!-- ===== Payment Methods ===== -->
            <div class="methods">

                <!-- Card -->
                <label class="card-option">
                    <input type="radio" name="pay" checked onclick="toggleForm(true)" required>
                    <div class="box">
                        <i class="fa fa-credit-card icon"></i>
                        <h3>Credit / Debit Card</h3>
                        <p>Visa, Mastercard, Amex</p>
                    </div>
                </label>

                <!-- Cash -->
                <label class="card-option">
                    <input type="radio" name="pay" onclick="toggleForm(false)" required>
                    <div class="box">
                        <i class="fa fa-money-bill icon"></i>
                        <h3>Cash</h3>
                        <p>Pay on arrival</p>
                    </div>
                </label>

            </div>

            <!-- ===== CARD FORM ===== -->
            <form class="form glass" id="cardForm">

                <label>Cardholder Name</label>
                <input id="name" required>

                <label>Card Number</label>
                <div class="input-icon">
                    <i class="fa fa-lock"></i>
                    <input id="number" placeholder="**** **** **** ****" required>
                </div>

                <div class="row">
                    <div>
                        <label>Expiry</label>
                        <input id="expiry" placeholder="MM / YY" required>
                    </div>
                    <div>
                        <label>CVV</label>
                        <input id="cvv" placeholder="***" type="text" required>
                    </div>
                </div>

            </form>

            <!-- ===== PAY BUTTON ===== -->
            <form method="POST">
                <button class="pay-btn" name="pay">
                    Confirm & Pay $<?= $booking['total_cost']; ?>
                </button>
            </form>

            <!-- ===== SECURITY NOTE ===== -->
            <div class="secure">
                <i class="fa fa-shield"></i>
                AES-256 Bit Encrypted Processing
            </div>

        </section>

        <aside class="right">

            <div class="card">

                <!-- ===== MAP ===== -->
                <div class="map" style="background: url(./assets/images/District-photo.png);">
                    <span id="location"><?= $spot['name']; ?></span>
                </div>

                <div class="content">

                    <!-- ===== INFO ===== -->
                    <div class="info">
                        <span>Parking Level</span>
                        <b id="level"><?= $spot['level']; ?></b>
                    </div>

                    <div class="info">
                        <span>Reservation</span>
                        <b id="duration"><?= $booking['duration']; ?> Hours</b>
                    </div>

                    <hr>

                    <!-- ===== PRICES (DYNAMIC) ===== -->
                    <div class="price">
                        <span>Base Rate</span>
                        <span id="base">$0</span>
                    </div>
                    <div class="price">
                        <span>Surcharge</span>
                        <span id="surcharge">$0</span>
                    </div>
                    <div class="price">
                        <span>Service</span>
                        <span id="service">$0</span>
                    </div>

                    <!-- ===== TOTAL ===== -->
                    <div class="total">
                        <div>
                            <p>Total</p>
                            <small>Incl. taxes</small>
                        </div>
                        <h2 id="total">$<?= $booking['total_cost']; ?></h2>
                    </div>

                    <!-- ===== PERK ===== -->
                    <div class="perk">
                        <i class="fa fa-bolt"></i>
                        EV Charging Included
                    </div>

                </div>

            </div>

        </aside>

    </main>
    <script src="./assets/js/payment.js"></script>
</body>

</html>
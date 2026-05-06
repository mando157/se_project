<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>UrbanKinetic Checkout</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/payment.css?v=1">
</head>

<body>
    <header class="header">
        <div class="logo">
            <span class="brand">UrbanKinetic</span>
            <span class="divider"></span>
            <span class="sub">Secure Checkout</span>
        </div>
        <a href="<?= BASE_URL ?>Driver/bookingDetails" class="back"><i class="fa fa-arrow-left"></i> Cancel Booking</a>
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
            <form class="form glass" id="cardForm" action="<?= BASE_URL ?>Driver/bookings" method="POST">

                <label>Cardholder Name</label>
                <input id="name" required>

                <label>Card Number</label>
                <div class="input-icon">
                    <i class="fa fa-lock"></i>
                    <input id="number" placeholder="**** **** **** ****" required>
                </div>

                <div class="pay-row">
                    <div class="row-1">
                        <label>Expiry</label>
                        <input id="expiry" placeholder="MM / YY" required>
                    </div>
                    <div class="row-2">
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
                <div class="map" style="background: url(<?= BASE_URL ?>/assets/images/District-photo.png);">
                    <span id="location"><?= $name; ?></span>
                </div>

                <div class="content">

                    <!-- ===== INFO ===== -->
                    <div class="info">
                        <span>Parking Level</span>
                        <b id="level"><?= $level ?></b>
                    </div>

                    <div class="info">
                        <span>Reservation</span>
                        <b id="duration"><?= $booking['duration']; ?> Hours</b>
                    </div>

                    <hr>

                    <!-- ===== PRICES (DYNAMIC) ===== -->
                    <div class="price">
                        <span>Price /hr</span>
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
    <script>
        const booking = {
            price_per_hour: <?= floatval($booking['price_per_hour']) ?>,
            hours: <?= intval($booking['duration']) ?>,
            surcharge: 0,
            service: 0
        };
    </script>
    <script src="<?= BASE_URL ?>/assets/js/pay.js"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home — ParkFlow</title>
    <!--* CSS Style Sheets -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/driver.css?v=1.1">
    <!--* Font Awsome -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css?v=1">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css?v=1">

</head>

<body>

    <!--* Header Section -->
    <section class="header" id="header">
        <nav class="navbar">
            <div class="logo">
                <img src="<?= BASE_URL ?>assets/images/Logo.png" alt="UrbanKinetic Logo">
                <h2>UrbanKinetic</h2>
            </div>

            <ul class="nav-links">
                <li><a href="<?= BASE_URL ?>Driver" class="active">Live Map</a></li>
                <li><a href="<?= BASE_URL ?>Driver/map">Find Parking</a></li>
                <li><a href="<?= BASE_URL ?>Driver/MyBookings">My Bookings</a></li>
                <li><a href="<?= BASE_URL ?>Driver/support">Support</a></li>
            </ul>

            <div class="nav-btn">
                <a href="<?= BASE_URL ?>Driver/notify" class="notification-bell"><i class="fa-regular fa-bell"></i></a>
                <h5><?= $user['name'] ?? '' ?></h5>
                <a href="<?= BASE_URL ?>Driver/profile" class="profile"><img src="<?= BASE_URL ?>assets/images/Account.png"
                        alt=""></a>
                <a href="<?= BASE_URL ?>Auth/logout" class="logout-btn">Logout</a>
            </div>
        </nav>
    </section>
    <!--* Landing Section -->
    <section class="landing" id="landing">
        <div class="section-content">
            <div class="light-1"></div>
            <div class="light-2"></div>

            <h2>Urban Flow Design System</h2>
            <h1 class="title">
                Find Parking Easily
                <span class="active">in Your City</span>
            </h1>
            <p>Eliminate the urban search. Connect with real-time parking intelligence
                and reclaim your time with the world's most advanced parking
                network.</p>
            <div class="landing-btn">
                <a href="<?= BASE_URL ?>Driver/map" class="btn active-btn">Get Started</a>
                <a href="#" class="btn learn-more-btn">Learn More</a>
            </div>
        </div>
    </section>
    <!--* Precision Section -->
    <section class="Precision" id="precision">
        <div class="section-container">
            <div class="Precision-title">
                <h2>Precision Engineering</h2>
                <p>Harnessing advanced data sets to provide a seamless urban transit
                    experience.</p>
            </div>
            <div class="section-content grid row ">
                <div class="map col-lg-8" style="background: url(<?= BASE_URL ?>assets/images/map.png);">
                    <div class="pop-up">
                        <a href="<?= BASE_URL ?>Driver/map">
                            park flow <i class="fa-solid fa-arrow-right-long"></i>
                        </a>
                    </div>
                    <div class="map-content">
                        <h4>•Real-time Intelligence</h4>
                        <h2>Urban Flow Live</h2>
                        <p>Dynamic heatmaps and occupancy sensors update
                            every 1.5 seconds across the grid.</p>
                    </div>
                </div>
                <div class="booking-box col-lg-4">
                    <div class="booking">
                        <i class="fa-regular fa-calendar-check"></i>
                        <div class="booking-content">
                            <h2>Seamless Booking</h2>
                            <p>One-tap reservations with instant digital
                                credentials for gated access systems.</p>
                        </div>
                    </div>
                    <div class="pricing">
                        <i class="fa-solid fa-money-bills"></i>
                        <div class="pricing-content">
                            <h2>Smart Pricing</h2>
                            <p>Algorithmic pricing models that adapt to
                                demand, saving you up to 40% daily.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>
    <!--* Feedback Section -->
    <section class="feedback-section" id="feedback">
        <div class="section-container">
            <div class="feedback">
                <span class="num">90</span>

                <div class="feedback-title col-lg-5">
                    <h2>What the <span class="active">City</span> is Saying</h2>
                    <div class="active-urbanites">
                        <img src="<?= BASE_URL ?>assets/images/photo-container.png" alt="">
                        <p>+12k Active Urbanites</p>
                    </div>
                </div>

                <div class="feedback-content col-lg-5">
                    <p>"The Urban Flow interface is unmatched. I
                        used to spend 20 minutes circling the
                        block; now I book a spot before I even
                        leave. A literal game changer for downtown
                        life."</p>
                    <div class="account">
                        <span class="account-photo">JD</span>
                        <div class="account-info">
                            <h4>Julian D'Amico</h4>
                            <p>Architect, NY Grid</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--* CTA Section -->
    <section class="cta" id="cta">
        <div class="section-container">
            <div class="cta-box">
                <span class="litter-p">P</span>
                <h2>Ready to claim your spot?</h2>
                <p>
                    Join the network today and experience the future of urban
                    movement. No stress, just flow.
                </p>
                <a href="#" class="cta-btn">Get Started Now</a>
            </div>
        </div>
    </section>

    <!--* Footer -->
    <footer class="footer" id="footer">
        <div class="section-container footer-content">

            <div class="footer-left">
                <h3>UrbanKinetic</h3>
                <p>
                    Redefining city transit through intelligent spatial
                    data and frictionless parking management systems.
                </p>

                <div class="social">
                    <i class="fa-solid fa-share-nodes"></i>
                    <i class="fa-solid fa-globe"></i>
                </div>
            </div>

            <div class="footer-links">
                <div>
                    <h4>PLATFORM</h4>
                    <a href="#">Live Map</a>
                    <a href="#">Enterprise</a>
                    <a href="#">Smart Nodes</a>
                    <a href="#">API Access</a>
                </div>

                <div>
                    <h4>COMPANY</h4>
                    <a href="#feedback">About Us</a>
                    <a href="#">Careers</a>
                    <a href="#">Privacy</a>
                    <a href="#">Contact</a>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <p>
                © 2024 URBANKINETIC TECHNOLOGIES. ALL RIGHTS RESERVED.
                DESIGNED FOR THE FUTURE OF CITIES.
            </p>
        </div>
    </footer>
</body>
<script src="<?= BASE_URL ?>assets/js/all.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>

</html>
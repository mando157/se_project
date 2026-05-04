<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Smart Urban Parking</title>
    <!--* CSS Style Sheets -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/driver.css?v=1">
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
                <li><a href="<?= BASE_URL ?>Map">Find Parking</a></li>
                <li><a href="<?= BASE_URL ?>Booking">My Bookings</a></li>
                <li><a href="<?= BASE_URL ?>Support">Support</a></li>
            </ul>

            <div class="nav-btn">
                <a href="<?= BASE_URL ?>Notify" class="notification-bell"><i class="fa-regular fa-bell"></i></a>
                <a href="<?= BASE_URL ?>Profile" class="profile"><img src="<?= BASE_URL ?>assets/images/Account.png"
                        alt=""></a>
                <a href="<?= BASE_URL ?>Auth/login" class="logout-btn">Login</a>
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
                <a href="<?= BASE_URL ?>Auth/login" class="btn active-btn">Get Started</a>
                <a href="#" class="btn learn-more-btn">Learn More</a>
            </div>
        </div>
    </section>

</body>

</html>
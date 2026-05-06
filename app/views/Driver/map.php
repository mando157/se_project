<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/map.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css">

  <!-- Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

  <title>Find Parking</title>
</head>

<body>

  <section class="header">
    <nav class="navbar">
      <div class="logo">
        <h2>ParkFlow</h2>
      </div>

      <ul class="nav-links">
        <li><a href="<?= BASE_URL ?>Driver">Live Map</a></li>
        <li><a href="<?= BASE_URL ?>Driver/map" class="active">Find Parking</a></li>
        <li><a href="<?= BASE_URL ?>Driver/booking">My Bookings</a></li>
        <li><a href="<?= BASE_URL ?>Driver/notify">Notifications</a></li>
      </ul>
    </nav>
  </section>

  <div class="main">

    <!-- LEFT -->
    <div class="left">

      <div class="search-box">
        <span class="material-symbols-outlined">search</span>
        <input type="text" placeholder="Search parking location...">
      </div>

      <div class="filters">
        <button class="active">Price</button>
        <button>Distance</button>
        <button>EV Charging</button>
      </div>

      <div class="title">
        <h3>Nearby Parking</h3>
      </div>

      <div class="cards">

        <?php if (!empty($spots)) { ?>
          <?php foreach ($spots as $row) { ?>

            <div class="card">
              <div class="card-top">
                <div>
                  <h4><?= $row['spot_name']; ?></h4>
                  <p>Distance will be calculated later</p>
                </div>

                <div class="price">
                  <h4>$<?= $row['price']; ?><span>/hr</span></h4>
                </div>
              </div>

              <div class="card-bottom">
                <span>Available Spots</span>

                <a class="primary" href="<?= BASE_URL ?>Driver/bookingDetails<?= $row['spot_id'] ?>">
                  Book Now
                </a>
              </div>
            </div>

          <?php } ?>
        <?php } else { ?>
          <p>No parking spots available.</p>
        <?php } ?>

      </div>
    </div>

    <div class="right">
      <div class="overlay">
      </div>
    </div>

  </div>

</body>

</html>
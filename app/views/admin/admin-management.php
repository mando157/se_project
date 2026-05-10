<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/ad-manage.css">
<title>UrbanKinetic </title>


</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <div class="logo">
                <h2>Control Center</h2>
            </div>

            <div class="menu">
                <a href="<?= BASE_URL ?>Admin/index"> <i class="fa-solid fa-table-columns"></i>Dashboard</a>
                <a href="<?= BASE_URL ?>Admin/earnings"> <i class="fa-solid fa-wallet"></i> Earnings</a>
                <a href="<?= BASE_URL ?>Admin/users" class="active"> <i class="fa-solid fa-users"></i> User Management</a>
            </div>
        </div>

<a href="<?= BASE_URL ?>Auth/logout" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>

    <!-- Main -->
    <div class="main">


<div class="topbar">
<h1>UrbanKinetic</h1>
    <div class="se-pro">
        <div class="search">
        <i class="fa-solid fa-search"></i>
        <input type="search" placeholder="Search grid nodes...">
        </div>
        <img class="profile" src="<?= BASE_URL ?>assets/admin-images/profile.svg" alt="">
    </div>
</div>


        <!-- Owner Access -->
        <div class="section-title">Owner Access</div>

        <div class="card">
            <div class="user">
                <img src="https://i.pravatar.cc/100?img=12">
                <div class="user-info">
                    <h3>Marcus Sterling</h3>
                    <p>Central Hub Parking</p>
                </div>
            </div>
            <div class="actions">
                <button class="reject">REJECT</button>
                <button class="approve">APPROVE</button>
            </div>
        </div>

        <div class="card">
            <div class="user">
                <img src="https://i.pravatar.cc/100?img=15">
                <div class="user-info">
                    <h3>James Rodriguez</h3>
                    <p>Skyline View Garage</p>
                </div>
            </div>
            <div class="actions">
                <button class="reject">REJECT</button>
                <button class="approve">APPROVE</button>
            </div>
        </div>

        <div class="card">
            <div class="user">
                <img src="https://i.pravatar.cc/100?img=16">
                <div class="user-info">
                    <h3>James Rodriguez</h3>
                    <p>Skyline View Garage</p>
                </div>
            </div>
            <div class="actions">
                <button class="reject">REJECT</button>
                <button class="approve">APPROVE</button>
            </div>
        </div>

        <!-- Drivers -->
        <div class="section-title">Drivers</div>

        <div class="card">
            <div class="user">
                <img src="https://i.pravatar.cc/100?img=13">
                <div class="user-info">
                    <h3>Marcus Sterling</h3>
                    <p>Central Hub Parking</p>
                </div>
            </div>
            <div class="actions">
                <button class="fine">Generate Fines</button>
                <button class="block">Block</button>
            </div>
        </div>

<div class="card">
    
    <div class="user">

        <img src="https://i.pravatar.cc/100?img=13">

        <div class="user-info">

            <div class="top-row">
                <h3>Marcus Sterling</h3>

                <div class="timer" id="timer">
                    00:00
                </div>
            </div>

            <p>Central Hub Parking</p>

        </div>

    </div>

    <div class="actions">
        <button class="fine">Generate Fines</button>
        <button class="block">Block</button>
    </div>

</div>

        <div class="card">
            <div class="user">
                <img src="https://i.pravatar.cc/100?img=13">
                <div class="user-info">
                    <h3>Marcus Sterling</h3>
                    <p>Central Hub Parking</p>
                </div>
            </div>
            <div class="actions">
                <button class="fine">Generate Fines</button>
                <button class="block">Block</button>
            </div>
        </div>

    </div>

</div>


 <script>
   let seconds = 0;

    const timer = document.getElementById("timer");

    function updateTimer() {

      let mins = Math.floor(seconds / 60);
      let secs = seconds % 60;

      mins = mins < 10 ? "0" + mins : mins;
      secs = secs < 10 ? "0" + secs : secs;

      timer.innerHTML = `${mins}:${secs}`;

      seconds++;
      timer.style.color="red";
    }

    // تشغيل التايمر تلقائي
    setInterval(updateTimer, 1000);
    </script>

</body>
</html>
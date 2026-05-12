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
<?php
$owners = $owners ?? [];
$drivers = $drivers ?? [];
?>

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
        <?php if (!empty($owners)): ?>
        <?php foreach ($owners as $owner): ?>
        <div class="card">
            <div class="user">
                <img src="https://i.pravatar.cc/100?img=12">
                <div class="user-info">
                    <h3><?= htmlspecialchars($owner['fullName'] ?? '-') ?></h3>
                    <p><?= htmlspecialchars($owner['email'] ?? '-') ?></p>
                    <p>Spots: <?= (int)($owner['spots_count'] ?? 0) ?> | Active: <?= (int)($owner['active_spots'] ?? 0) ?></p>
                </div>
            </div>
            <div class="actions">
                <button class="reject owner-status-btn" data-owner-id="<?= (int)($owner['id'] ?? 0) ?>" data-status="inactive">REJECT</button>
                <button class="approve owner-status-btn" data-owner-id="<?= (int)($owner['id'] ?? 0) ?>" data-status="active">APPROVE</button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="card">
            <div class="user-info">
                <h3>No owners found.</h3>
            </div>
        </div>
        <?php endif; ?>

        <!-- Drivers -->
        <div class="section-title">Drivers</div>
        <?php if (!empty($drivers)): ?>
        <?php foreach ($drivers as $driver): ?>
        <div class="card">
            <div class="user">
                <img src="https://i.pravatar.cc/100?img=13">
                <div class="user-info">
                    <h3><?= htmlspecialchars($driver['fullName'] ?? '-') ?></h3>
                    <p><?= htmlspecialchars($driver['email'] ?? '-') ?></p>
                    <p>Bookings: <?= (int)($driver['bookings_count'] ?? 0) ?> | Unpaid fines: $<?= number_format((float)($driver['unpaid_fines'] ?? 0), 2) ?></p>
                </div>
            </div>
            <div class="actions">
                <?php if (!empty($driver['last_booking_id'])): ?>
                <button
                    class="fine create-fine-btn"
                    data-booking-id="<?= (int)$driver['last_booking_id'] ?>"
                    data-driver-name="<?= htmlspecialchars($driver['fullName'] ?? '-', ENT_QUOTES) ?>"
                >
                    Generate Fine
                </button>
                <button
                    class="block block-driver-btn"
                    data-driver-id="<?= (int)($driver['id'] ?? 0) ?>"
                    data-driver-name="<?= htmlspecialchars($driver['fullName'] ?? '-', ENT_QUOTES) ?>"
                >
                    Block
                </button>
                <?php else: ?>
                <button class="fine" disabled>No Booking For Fine</button>
                <button class="block" disabled>Block</button>
                <?php endif; ?>
            </div>
            <?php if (!empty($driver['active_started_at'])): ?>
            <div class="timer driver-timer" data-started-at="<?= htmlspecialchars($driver['active_started_at'], ENT_QUOTES) ?>">00:00</div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="card">
            <div class="user-info">
                <h3>No drivers found.</h3>
            </div>
        </div>
        <?php endif; ?>

    </div>

</div>


<script>
const baseUrl = '<?= BASE_URL ?>';

async function postForm(url, payload) {
    const formData = new FormData();
    Object.keys(payload).forEach((key) => formData.append(key, payload[key]));
    const response = await fetch(url, { method: 'POST', body: formData });
    return response.json();
}

document.querySelectorAll('.owner-status-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const ownerId = btn.dataset.ownerId;
        const status = btn.dataset.status;
        btn.disabled = true;

        try {
            const result = await postForm(baseUrl + 'Admin/updateOwnerStatus', {
                owner_id: ownerId,
                status: status
            });
            alert(result.message || (result.success ? 'Updated.' : 'Failed.'));
            if (result.success) {
                window.location.reload();
            }
        } catch (error) {
            alert('Request failed: ' + error.message);
        } finally {
            btn.disabled = false;
        }
    });
});

document.querySelectorAll('.create-fine-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const bookingId = btn.dataset.bookingId;
        const driverName = btn.dataset.driverName || 'Driver';
        const amount = prompt('Fine amount for ' + driverName + ':', '50');
        if (amount === null) {
            return;
        }

        const reason = prompt('Fine reason:', 'Violation');
        if (reason === null || reason.trim() === '') {
            return;
        }

        btn.disabled = true;
        try {
            const result = await postForm(baseUrl + 'Admin/createFine', {
                booking_id: bookingId,
                amount: amount,
                reason: reason
            });
            alert(result.message || (result.success ? 'Fine created.' : 'Failed.'));
            if (result.success) {
                window.location.reload();
            }
        } catch (error) {
            alert('Request failed: ' + error.message);
        } finally {
            btn.disabled = false;
        }
    });
});

document.querySelectorAll('.block-driver-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const driverId = btn.dataset.driverId;
        const driverName = btn.dataset.driverName || 'driver';
        const confirmed = confirm('Block ' + driverName + ' and cancel active/pending bookings?');
        if (!confirmed) {
            return;
        }

        btn.disabled = true;
        try {
            const result = await postForm(baseUrl + 'Admin/blockDriver', {
                driver_id: driverId
            });
            alert(result.message || (result.success ? 'Driver blocked.' : 'Failed.'));
            if (result.success) {
                window.location.reload();
            }
        } catch (error) {
            alert('Request failed: ' + error.message);
        } finally {
            btn.disabled = false;
        }
    });
});

function formatClock(totalSeconds) {
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;
    const hh = hours < 10 ? '0' + hours : '' + hours;
    const mm = minutes < 10 ? '0' + minutes : '' + minutes;
    const ss = seconds < 10 ? '0' + seconds : '' + seconds;
    return hh + ':' + mm + ':' + ss;
}

function startDriverTimers() {
    const timers = document.querySelectorAll('.driver-timer');
    if (!timers.length) {
        return;
    }

    const tick = () => {
        timers.forEach((timer) => {
            const startedAt = timer.dataset.startedAt;
            if (!startedAt) {
                timer.textContent = '00:00:00';
                return;
            }

            const startDate = new Date(startedAt.replace(' ', 'T'));
            if (Number.isNaN(startDate.getTime())) {
                timer.textContent = '00:00:00';
                return;
            }

            const now = new Date();
            const diff = Math.max(0, Math.floor((now.getTime() - startDate.getTime()) / 1000));
            timer.textContent = formatClock(diff);
        });
    };

    tick();
    setInterval(tick, 1000);
}

startDriverTimers();
</script>

</body>
</html>
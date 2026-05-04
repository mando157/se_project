<?php
include("config/db.php");

$spot_id = $_POST['spot_id'];
$start = $_POST['start'];
$end = $_POST['end'];
$total = $_POST['total'];
$duration = $_POST['duration'];

// مؤقتًا user_id = 1
$user_id = 1;

$sql = "INSERT INTO bookings (user_id, spot_id, start_time, end_time, duration, total_cost, status)
VALUES ($user_id, $spot_id, '$start', '$end', $duration, $total, 'active')";

if (mysqli_query($conn, $sql)) {
    header("Location: booking.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
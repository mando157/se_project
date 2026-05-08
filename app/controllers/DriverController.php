<?php

require_once "../app/helpers/Auth.php";

class DriverController extends Controller
{
    public function __construct()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');
    }

    public function index()
    {
        $user = Auth::user();

        $this->view("driver/index", [
            'user' => $user
        ]);
    }

    public function map()
    {
        $db = new Database();
        $conn = $db->getConnection();

        $query = "SELECT * FROM parking_spots";

        $result = mysqli_query($conn, $query);

        $spots = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $spots[] = $row;
        }

        $this->view("driver/map", [
            'spots' => $spots
        ]);
    }

    public function bookingDetails()
    {
        $db = new Database();
        $conn = $db->getConnection();

        if (!isset($_GET['spot_id'])) {
            die("Invalid Spot ID");
        }

        $spot_id = intval($_GET['spot_id']);

        $stmt = $conn->prepare(
            "SELECT * FROM parking_spots
             WHERE spot_id = ?"
        );

        $stmt->bind_param("i", $spot_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            die("Spot not found");
        }

        $row = $result->fetch_assoc();

        $this->view("driver/bookingDetails", [

            'spot_id' => $row['spot_id'],

            'name' => $row['spot_name'],

            'price' => $row['price']

        ]);
    }

    public function confirmBooking()
    {
        $db = new Database();
        $conn = $db->getConnection();

        $user = Auth::user();

        $user_id = $user['id'];

        $spot_id = intval($_POST['spot_id']);

        $date = $_POST['date'];

        $start = $_POST['start'];

        $end = $_POST['end'];

        $stmt = $conn->prepare(
            "SELECT price FROM parking_spots
             WHERE spot_id = ?"
        );

        $stmt->bind_param("i", $spot_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $spot = $result->fetch_assoc();

        if (!$spot) {
            die("Parking Spot Not Found");
        }

        $price_per_hour = $spot['price'];

        $startTime = strtotime($start);

        $endTime = strtotime($end);

        if ($endTime <= $startTime) {
            $endTime += 86400;
        }

        $duration = ($endTime - $startTime) / 3600;

        $total = $duration * $price_per_hour;

        $status = "pending";

        $stmt = $conn->prepare(
            "INSERT INTO bookings
            (
                user_id,
                spot_id,
                date,
                start_time,
                end_time,
                duration,
                price_per_hour,
                total_cost,
                status
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "iisssddds",
            $user_id,
            $spot_id,
            $date,
            $start,
            $end,
            $duration,
            $price_per_hour,
            $total,
            $status
        );

        $stmt->execute();

        $booking_id = $conn->insert_id;

        header(
            "Location: " .
            BASE_URL .
            "Driver/payment?booking_id=$booking_id"
        );

        exit;
    }

    public function payment()
    {
        $db = new Database();
        $conn = $db->getConnection();
        $booking_id = intval($_GET['booking_id']);
        $type = $_GET['type'] ?? 'booking';
        $extra_hours =
            intval($_GET['extra_hours'] ?? 0);
        $stmt = $conn->prepare(
            "SELECT b.*, p.spot_name, p.price
         FROM bookings b
         JOIN parking_spots p
         ON b.spot_id = p.spot_id
         WHERE b.booking_id = ?"
        );
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();
        if (!$booking) {
            die("Booking not found");
        }
        $extra_cost = 0;
        if ($type == 'extend') {
            $extra_cost =
                $booking['price_per_hour']
                *
                $extra_hours;
        }
        $this->view("driver/payment", [
            'booking' => $booking,
            'name' => $booking['spot_name'],
            'level' => $booking['spot_id'],
            'price' => $booking['price'],
            'type' => $type,
            'extra_hours' => $extra_hours,
            'extra_cost' => $extra_cost
        ]);
    }

    public function pay()
    {
        $db = new Database();
        $conn = $db->getConnection();
        $booking_id =
            intval($_POST['booking_id']);
        $type =
            $_POST['type'];
        if ($type == 'extend') {
            $extra_hours =
                intval($_POST['extra_hours']);
            $stmt = $conn->prepare(
                "UPDATE bookings
             SET
             end_time =
             ADDTIME(
                end_time,
                SEC_TO_TIME(? * 3600)
             ),
             duration =
             duration + ?,
             total_cost =
             total_cost +
             (price_per_hour * ?)
             WHERE booking_id = ?"
            );
            $stmt->bind_param(
                "iiii",
                $extra_hours,
                $extra_hours,
                $extra_hours,
                $booking_id
            );
            $stmt->execute();
        } else {
            $stmt = $conn->prepare(
                "UPDATE bookings
             SET status = 'paid'
             WHERE booking_id = ?"
            );
            $stmt->bind_param(
                "i",
                $booking_id
            );
            $stmt->execute();
        }
        header(
            "Location: " .
            BASE_URL .
            "Driver/MyBookings"
        );
        exit;
    }

    public function MyBookings()
    {
        $user = Auth::user();
        $user_id = $user['id'];

        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare(
            "SELECT b.*, p.spot_name
             FROM bookings b
             JOIN parking_spots p
             ON b.spot_id = p.spot_id
             WHERE b.user_id = ?
             ORDER BY b.booking_id DESC"
        );

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $bookings = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }
        $this->view("driver/booking", [
            'bookings' => $bookings
        ]);
    }

    public function extendBooking()
    {
        $db = new Database();
        $conn = $db->getConnection();
        $booking_id = intval($_POST['booking_id']);

        $stmt = $conn->prepare(
            "UPDATE bookings
         SET
         end_time =
         ADDTIME(end_time, '01:00:00'),
         duration = duration + 1,
         total_cost =
         total_cost + price_per_hour
         WHERE booking_id = ?"
        );

        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        header(
            "Location: " .
            BASE_URL .
            "Driver/MyBookings"
        );
        exit;
    }

    public function cancelBooking()
    {
        $db = new Database();
        $conn = $db->getConnection();

        $booking_id = intval($_POST['booking_id']);

        $stmt = $conn->prepare(
            "UPDATE bookings
             SET status = 'cancelled'
             WHERE booking_id = ?"
        );

        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        header("Location: " . BASE_URL . "Driver/MyBookings");

        exit;
    }

    public function notify()
    {
        $db = new Database();
        $conn = $db->getConnection();

        $user = Auth::user();
        $user_id = $user['id'];

        $stmt = $conn->prepare(
            "SELECT n.*, p.spot_name
         FROM notifications n
         LEFT JOIN bookings b
         ON n.booking_id = b.booking_id
         LEFT JOIN parking_spots p
         ON b.spot_id = p.spot_id
         WHERE n.user_id = ?
         ORDER BY n.created_at DESC"
        );

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $notifications = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = $row;
        }

        $this->view("driver/notify", [
            'notifications' => $notifications
        ]);
    }
}
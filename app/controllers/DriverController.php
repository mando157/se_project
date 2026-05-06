<?php
require_once "../app/helpers/Auth.php";

class DriverController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $this->view("driver/index", ['user' => $user]);
    }

    public function map()
    {
        $user = Auth::user();

        $db = new Database();
        $conn = $db->getConnection();

        $query = "SELECT * FROM parking_spots";
        $result = mysqli_query($conn, $query);

        $spots = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $spots[] = $row;
        }

        $this->view("driver/map", [
            'user' => $user,
            'spots' => $spots
        ]);
    }

    public function bookingDetails()
    {
        $db = new Database();
        $conn = $db->getConnection();


        if (!isset($_GET['spot_id']) || empty($_GET['spot_id'])) {
            die("Invalid Spot ID");
        }

        $spot_id = intval($_GET['spot_id']);

        $query = "SELECT * FROM parking_spots WHERE spot_id = $spot_id";
        $result = mysqli_query($conn, $query);

        if (!$result || mysqli_num_rows($result) == 0) {
            die("Spot not found");
        }

        $row = mysqli_fetch_assoc($result);

        $this->view("driver/bookingDetails", [
            'spot_id' => $spot_id,
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
        $start = $_POST['start'];
        $end = $_POST['end'];
        $total = floatval($_POST['total']);
        $duration = floatval($_POST['duration']);

        $priceQuery = "SELECT price FROM parking_spots WHERE spot_id = $spot_id";
        $priceResult = mysqli_query($conn, $priceQuery);
        $priceRow = mysqli_fetch_assoc($priceResult);
        $price_per_hour = $priceRow['price'];

        $sql = "INSERT INTO bookings 
    (user_id, spot_id, start_time, end_time, duration, total_cost, status)
    VALUES 
    ($user_id, $spot_id, '$start', '$end', $duration, $total, 'pending')";

        mysqli_query($conn, $sql);

        $booking_id = mysqli_insert_id($conn);

        header("Location: " . BASE_URL . "Driver/payment?booking_id=$booking_id");
        exit;
    }
    public function payment()
    {
        $db = new Database();
        $conn = $db->getConnection();

        $booking_id = $_GET['booking_id'];

        $query = "SELECT b.*, p.spot_name, p.price 
              FROM bookings b
              JOIN parking_spots p ON b.spot_id = p.spot_id
              WHERE b.booking_id = $booking_id";

        $result = mysqli_query($conn, $query);
        $booking = mysqli_fetch_assoc($result);

        $this->view("driver/payment", [
            'booking' => $booking,
            'name' => $booking['spot_name'],
            'level' => $booking['spot_id'],
            'price' => $booking['price_per_hour']
        ]);
    }

    public function pay()
    {
        $db = new Database();
        $conn = $db->getConnection();

        $booking_id = $_POST['booking_id'];

        $sql = "UPDATE bookings SET status='paid' WHERE booking_id=$booking_id";
        mysqli_query($conn, $sql);

        header("Location: " . BASE_URL . "Driver/booking");
    }

    public function MyBookings()
    {
        $user = Auth::user();
        $user_id = $user['id'];

        $db = new Database();
        $conn = $db->getConnection();

        $query = "SELECT b.*, p.spot_name 
                  FROM bookings b
                  JOIN parking_spots p ON b.spot_id = p.spot_id
                  WHERE b.user_id = $user_id
                  ORDER BY b.booking_id DESC";

        $result = mysqli_query($conn, $query);

        $bookings = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }

        $this->view("driver/booking", [
            'bookings' => $bookings
        ]);
    }

    public function bookingHistory()
    {
        $db = new Database();
        $conn = $db->getConnection();

        $user = Auth::user();
        $user_id = $user['id'];

        $query = "SELECT b.*, p.spot_name 
                  FROM bookings b
                  JOIN parking_spots p ON b.spot_id = p.spot_id
                  WHERE b.user_id = $user_id AND b.status IN ('paid', 'cancelled')
                  ORDER BY b.booking_id DESC";

        $result = mysqli_query($conn, $query);

        $bookings = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }

        $this->view("driver/booking", [
            'bookings' => $bookings
        ]);
    }

    public function cancelBooking()
    {
        $db = new Database();
        $conn = $db->getConnection();

        $booking_id = intval($_POST['booking_id']);

        $sql = "UPDATE bookings SET status='cancelled' WHERE booking_id=$booking_id";
        mysqli_query($conn, $sql);

        header("Location: " . BASE_URL . "Driver/booking");
    }

    public function notify()
    {
        $db = new Database();
        $conn = $db->getConnection();

        $user = Auth::user();
        $user_id = $user['id'];

        $query = "SELECT n.*, b.spot_id, p.spot_name 
                  FROM notifications n
                  JOIN bookings b ON n.booking_id = b.booking_id
                  JOIN parking_spots p ON b.spot_id = p.spot_id
                  WHERE n.user_id = $user_id
                  ORDER BY n.created_at DESC";

        $result = mysqli_query($conn, $query);

        $notifications = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = $row;
        }

        $this->view("driver/notify", [
            'notifications' => $notifications
        ]);
    }
}
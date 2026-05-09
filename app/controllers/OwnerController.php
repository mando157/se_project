<?php

require_once "../app/helpers/Auth.php";

class OwnerController extends Controller
{
    private $ownerModel;

    public function __construct()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('owner');
    }

    public function index()
    {
        $user = Auth::user();

        $this->view("Owner/index", [
            'user' => $user
        ]);
    }

    public function dashboard()
    {
        $db = new Database();
        $conn = $db->getConnection();
        $owner = Auth::user();
        $owner_id = $owner['id'];


        $totalSpacesQuery = "SELECT COUNT(*) as total FROM parking_spots WHERE owner_id = ?";
        $stmt = $conn->prepare($totalSpacesQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $totalSpacesResult = $stmt->get_result()->fetch_assoc();
        $totalSpaces = $totalSpacesResult['total'] ?? 0;


        $totalSlotsQuery = "SELECT SUM(total_slots) as total FROM parking_spots WHERE owner_id = ?";
        $stmt = $conn->prepare($totalSlotsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $totalSlots = $stmt->get_result()->fetch_assoc()['total'] ?? 0;


        $totalEarningsQuery = "SELECT SUM(amount) as total FROM bookings b 
                               JOIN parking_spots p ON b.parking_spot_id = p.id 
                               WHERE p.owner_id = ? AND b.status = 'completed'
                               AND b.created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')";
        $stmt = $conn->prepare($totalEarningsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $totalEarnings = $stmt->get_result()->fetch_assoc()['total'] ?? 0;


        $activeBookingsQuery = "SELECT COUNT(*) as total FROM bookings b 
                                JOIN parking_spots p ON b.parking_spot_id = p.id 
                                WHERE p.owner_id = ? AND b.status = 'active'";
        $stmt = $conn->prepare($activeBookingsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $activeBookings = $stmt->get_result()->fetch_assoc()['total'] ?? 0;


        $pendingBookingsQuery = "SELECT COUNT(*) as total FROM bookings b 
                                 JOIN parking_spots p ON b.parking_spot_id = p.id 
                                 WHERE p.owner_id = ? AND b.status = 'pending'";
        $stmt = $conn->prepare($pendingBookingsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $pendingBookings = $stmt->get_result()->fetch_assoc()['total'] ?? 0;


        $bookedSlotsQuery = "SELECT SUM(b.booked_slots) as total FROM bookings b 
                             JOIN parking_spots p ON b.parking_spot_id = p.id 
                             WHERE p.owner_id = ? AND b.status = 'active' 
                             AND NOW() BETWEEN b.start_time AND b.end_time";
        $stmt = $conn->prepare($bookedSlotsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $bookedSlots = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

        $occupancyRate = ($totalSlots > 0) ? round(($bookedSlots / $totalSlots) * 100, 1) : 0;


        $recentBookingsQuery = "SELECT b.*, p.name as parking_name FROM bookings b 
                                JOIN parking_spots p ON b.parking_spot_id = p.id 
                                WHERE p.owner_id = ? 
                                ORDER BY b.created_at DESC LIMIT 5";
        $stmt = $conn->prepare($recentBookingsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $recentBookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


        $notificationsQuery = "SELECT * FROM notifications 
                               WHERE owner_id = ? 
                               ORDER BY created_at DESC LIMIT 5";
        $stmt = $conn->prepare($notificationsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


        $this->view("owner/dashboard", [
            'totalSpaces' => $totalSpaces,
            'totalSlots' => $totalSlots,
            'totalEarnings' => $totalEarnings,
            'activeBookings' => $activeBookings,
            'bookingsChange' => round($bookingsChange, 1),
            'occupancyRate' => $occupancyRate,
            'occupancyChange' => round($occupancyChange, 1),
            'peakHour' => $peakHour,
            'recentBookings' => $recentBookings,
            'notifications' => $notifications
        ]);
    }

    public function addSpace()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $conn = $db->getConnection();
            $owner = Auth::user();
            $owner_id = $owner['id'];

            $location_reference = $_POST['location_reference'] ?? '';
            $price_per_hour = $_POST['price_per_hour'] ?? 0;
            $total_slots = $_POST['total_slots'] ?? 1;
            $attributes = $_POST['attributes'] ?? '';
            $instant_activation = isset($_POST['instant_activation']) ? 1 : 0;

            $query = "INSERT INTO parking_spots (owner_id, name, price_per_hour, total_slots, attributes, status, created_at) 
                      VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $status = $instant_activation ? 'active' : 'pending';

            $stmt = $conn->prepare($query);
            $stmt->bind_param("isdiss", $owner_id, $location_reference, $price_per_hour, $total_slots, $attributes, $status);

            if ($stmt->execute()) {

                $notifQuery = "INSERT INTO notifications (owner_id, message, created_at) VALUES (?, ?, NOW())";
                $notifStmt = $conn->prepare($notifQuery);
                $message = "New parking space '{$location_reference}' has been added successfully";
                $notifStmt->bind_param("is", $owner_id, $message);
                $notifStmt->execute();

                echo json_encode(['success' => true, 'message' => 'Space added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }


    public function markNotificationsRead()
    {
        header('Content-Type: application/json');

        $db = new Database();
        $conn = $db->getConnection();
        $owner = Auth::user();
        $owner_id = $owner['id'];

        $query = "UPDATE notifications SET is_read = 1 WHERE owner_id = ? AND is_read = 0";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $owner_id);
        $result = $stmt->execute();

        echo json_encode(['success' => $result]);
    }


    public function notifications()
    {
        $db = new Database();
        $conn = $db->getConnection();
        $owner = Auth::user();
        $owner_id = $owner['id'];

        $notificationsQuery = "SELECT * FROM notifications 
                               WHERE owner_id = ? 
                               ORDER BY created_at DESC";
        $stmt = $conn->prepare($notificationsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $this->view("Owner/notifications", [
            'notifications' => $notifications
        ]);
    }


    public function spaces()
    {
        $db = new Database();
        $conn = $db->getConnection();
        $owner = Auth::user();
        $owner_id = $owner['id'];

        $spacesQuery = "SELECT * FROM parking_spots WHERE owner_id = ? ORDER BY created_at DESC";
        $stmt = $conn->prepare($spacesQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $spaces = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $this->view("Owner/spaces", [
            'spaces' => $spaces
        ]);
    }


    public function bookings()
    {
        $db = new Database();
        $conn = $db->getConnection();
        $owner = Auth::user();
        $owner_id = $owner['id'];

        $bookingsQuery = "SELECT b.*, p.name as parking_name FROM bookings b 
                          JOIN parking_spots p ON b.parking_spot_id = p.id 
                          WHERE p.owner_id = ? 
                          ORDER BY b.created_at DESC";
        $stmt = $conn->prepare($bookingsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $this->view("Owner/bookings", [
            'bookings' => $bookings
        ]);
    }
}
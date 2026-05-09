<?php

require_once "../app/helpers/Auth.php";

class OwnerController extends Controller
{
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

        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM parking_spots WHERE owner_id = ?");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $totalSpaces = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

        $stmt = $conn->prepare("SELECT SUM(total_slots) as total FROM parking_spots WHERE owner_id = ?");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $totalSlots = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

        $stmt = $conn->prepare("
            SELECT SUM(total_cost) as total 
            FROM bookings b
            JOIN parking_spots p ON b.spot_id = p.spot_id
            WHERE p.owner_id = ? AND b.status = 'completed'
        ");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $totalEarnings = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

        $stmt = $conn->prepare("
            SELECT COUNT(*) as total 
            FROM bookings b
            JOIN parking_spots p ON b.spot_id = p.spot_id
            WHERE p.owner_id = ? AND b.status = 'active'
        ");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $activeBookings = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

        $stmt = $conn->prepare("
            SELECT COUNT(*) as total 
            FROM bookings b
            JOIN parking_spots p ON b.spot_id = p.spot_id
            WHERE p.owner_id = ? AND b.status = 'pending'
        ");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $pendingBookings = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

        $stmt = $conn->prepare("
            SELECT COUNT(*) as total
            FROM bookings b
            JOIN parking_spots p ON b.spot_id = p.spot_id
            WHERE p.owner_id = ? AND b.status = 'active'
        ");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $bookedSlots = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

        $occupancyRate = ($totalSlots > 0)
            ? round(($bookedSlots / $totalSlots) * 100, 1)
            : 0;

        $stmt = $conn->prepare("
            SELECT b.*, p.spot_name 
            FROM bookings b
            JOIN parking_spots p ON b.spot_id = p.spot_id
            WHERE p.owner_id = ?
            ORDER BY b.created_at DESC
            LIMIT 5
        ");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $recentBookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt = $conn->prepare("
            SELECT * FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT 5
        ");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $this->view("owner/dashboard", [
            'totalSpaces' => $totalSpaces,
            'totalSlots' => $totalSlots,
            'totalEarnings' => $totalEarnings,
            'activeBookings' => $activeBookings,
            'pendingBookings' => $pendingBookings,
            'occupancyRate' => $occupancyRate,
            'recentBookings' => $recentBookings,
            'notifications' => $notifications
        ]);
    }

    public function notifications()
    {
        $db = new Database();
        $conn = $db->getConnection();

        $owner = Auth::user();
        $owner_id = $owner['id'];

        $stmt = $conn->prepare("
            SELECT * FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
        ");
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

        $stmt = $conn->prepare("
            SELECT * FROM parking_spots
            WHERE owner_id = ?
            ORDER BY created_at DESC
        ");
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

        $stmt = $conn->prepare("
            SELECT b.*, p.spot_name
            FROM bookings b
            JOIN parking_spots p ON b.spot_id = p.spot_id
            WHERE p.owner_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();

        $bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $this->view("Owner/bookings", [
            'bookings' => $bookings
        ]);
    }
}
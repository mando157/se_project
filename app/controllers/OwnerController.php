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
        $totalSpaces = $stmt->get_result()->fetch_assoc()['total'];


        $totalSlotsQuery = "SELECT SUM(total_slots) as total FROM parking_spots WHERE owner_id = ?";
        $stmt = $conn->prepare($totalSlotsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $totalSlots = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

   
        $totalEarningsQuery = "SELECT SUM(amount) as total FROM bookings b 
                               JOIN parking_spots p ON b.parking_spot_id = p.id 
                               WHERE p.owner_id = ? AND b.status = 'completed'";
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
            'pendingBookings' => $pendingBookings,
            'occupancyRate' => $occupancyRate,
            'recentBookings' => $recentBookings,
            'notifications' => $notifications
        ]);
    }

}

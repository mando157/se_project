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
        $totalSlotsResult = $stmt->get_result()->fetch_assoc();
        $totalSlots = $totalSlotsResult['total'] ?? 0;

       
        $totalEarningsQuery = "SELECT SUM(amount) as total FROM bookings b 
                               JOIN parking_spots p ON b.parking_spot_id = p.id 
                               WHERE p.owner_id = ? AND b.status = 'completed'
                               AND b.created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')";
        $stmt = $conn->prepare($totalEarningsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $totalEarningsResult = $stmt->get_result()->fetch_assoc();
        $totalearnings = $totalEarningsResult['total'] ?? 0;

  
        $lastMonthEarningsQuery = "SELECT SUM(amount) as total FROM bookings b 
                                   JOIN parking_spots p ON b.parking_spot_id = p.id 
                                   WHERE p.owner_id = ? AND b.status = 'completed'
                                   AND b.created_at >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m-01')
                                   AND b.created_at < DATE_FORMAT(NOW(), '%Y-%m-01')";
        $stmt = $conn->prepare($lastMonthEarningsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $lastMonthEarningsResult = $stmt->get_result()->fetch_assoc();
        $lastMonthEarnings = $lastMonthEarningsResult['total'] ?? 0;
        
       
        $earningsChange = $lastMonthEarnings > 0 ? (($totalearnings - $lastMonthEarnings) / $lastMonthEarnings) * 100 : 0;

        $activeBookingsQuery = "SELECT COUNT(*) as total FROM bookings b 
                                JOIN parking_spots p ON b.parking_spot_id = p.id 
                                WHERE p.owner_id = ? AND b.status = 'active'";
        $stmt = $conn->prepare($activeBookingsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $activeBookingsResult = $stmt->get_result()->fetch_assoc();
        $activeBookings = $activeBookingsResult['total'] ?? 0;

        $pendingBookingsQuery = "SELECT COUNT(*) as total FROM bookings b 
                                 JOIN parking_spots p ON b.parking_spot_id = p.id 
                                 WHERE p.owner_id = ? AND b.status = 'pending'";
        $stmt = $conn->prepare($pendingBookingsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $pendingBookingsResult = $stmt->get_result()->fetch_assoc();
        $pendingBookings = $pendingBookingsResult['total'] ?? 0;
        
        $totalBookingsCount = $activeBookings + $pendingBookings;
        
        
        $lastMonthBookingsQuery = "SELECT COUNT(*) as total FROM bookings b 
                                   JOIN parking_spots p ON b.parking_spot_id = p.id 
                                   WHERE p.owner_id = ? 
                                   AND b.created_at >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m-01')
                                   AND b.created_at < DATE_FORMAT(NOW(), '%Y-%m-01')";
        $stmt = $conn->prepare($lastMonthBookingsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $lastMonthBookingsResult = $stmt->get_result()->fetch_assoc();
        $lastMonthBookings = $lastMonthBookingsResult['total'] ?? 0;
        
        
        $bookingsChange = $lastMonthBookings > 0 ? (($totalBookingsCount - $lastMonthBookings) / $lastMonthBookings) * 100 : 0;

        
        $bookedSlotsQuery = "SELECT SUM(b.booked_slots) as total FROM bookings b 
                             JOIN parking_spots p ON b.parking_spot_id = p.id 
                             WHERE p.owner_id = ? AND b.status = 'active' 
                             AND NOW() BETWEEN b.start_time AND b.end_time";
        $stmt = $conn->prepare($bookedSlotsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $bookedSlotsResult = $stmt->get_result()->fetch_assoc();
        $bookedSlots = $bookedSlotsResult['total'] ?? 0;
        
        $occupancyRate = ($totalSlots > 0) ? round(($bookedSlots / $totalSlots) * 100, 1) : 0;
        
        $lastMonthOccupancyQuery = "SELECT AVG(daily_rate) as avg_rate FROM (
                                    SELECT DATE(b.start_time) as date, 
                                           SUM(b.booked_slots) / ? * 100 as daily_rate
                                    FROM bookings b 
                                    JOIN parking_spots p ON b.parking_spot_id = p.id 
                                    WHERE p.owner_id = ? AND b.status = 'active'
                                    AND b.start_time >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m-01')
                                    AND b.start_time < DATE_FORMAT(NOW(), '%Y-%m-01')
                                    GROUP BY DATE(b.start_time)
                                    ) as daily_rates";
        $stmt = $conn->prepare($lastMonthOccupancyQuery);
        $stmt->bind_param("ii", $totalSlots, $owner_id);
        $stmt->execute();
        $lastMonthOccupancyResult = $stmt->get_result()->fetch_assoc();
        $lastMonthOccupancy = round($lastMonthOccupancyResult['avg_rate'] ?? 0, 1);
        
        
        $occupancyChange = $occupancyRate - $lastMonthOccupancy;

     
        $peakHourQuery = "SELECT HOUR(b.start_time) as hour, COUNT(*) as booking_count
                          FROM bookings b
                          JOIN parking_spots p ON b.parking_spot_id = p.id
                          WHERE p.owner_id = ? 
                          AND b.status IN ('active', 'completed')
                          AND b.start_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                          GROUP BY HOUR(b.start_time)
                          ORDER BY booking_count DESC
                          LIMIT 1";
        $stmt = $conn->prepare($peakHourQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $peakHourResult = $stmt->get_result()->fetch_assoc();
        
        if ($peakHourResult && isset($peakHourResult['hour'])) {
            $peakHourStart = $peakHourResult['hour'];
            $peakHourEnd = $peakHourStart + 2;
            $peakHour = sprintf("%02d:00 - %02d:00", $peakHourStart, $peakHourEnd);
        } else {
            $peakHour = "14:00 - 16:00"; 
        }

        // 8. Recent Bookings
        $recentBookingsQuery = "SELECT b.*, p.name as parking_name FROM bookings b 
                                JOIN parking_spots p ON b.parking_spot_id = p.id 
                                WHERE p.owner_id = ? 
                                ORDER BY b.created_at DESC LIMIT 5";
        $stmt = $conn->prepare($recentBookingsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $recentBookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // 9. Notifications
        $notificationsQuery = "SELECT * FROM notifications 
                               WHERE owner_id = ? 
                               ORDER BY created_at DESC LIMIT 5";
        $stmt = $conn->prepare($notificationsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
      
        $weeklyRevenue = [];
        $weeklyLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $weeklyLabels[] = date('D', strtotime($date));
            
            $revenueQuery = "SELECT SUM(amount) as total FROM bookings b
                             JOIN parking_spots p ON b.parking_spot_id = p.id
                             WHERE p.owner_id = ? AND b.status = 'completed'
                             AND DATE(b.created_at) = ?";
            $stmt = $conn->prepare($revenueQuery);
            $stmt->bind_param("is", $owner_id, $date);
            $stmt->execute();
            $revenueResult = $stmt->get_result()->fetch_assoc();
            $weeklyRevenue[] = $revenueResult['total'] ?? 0;
        }
    
        $monthlyRevenue = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = date('Y-m-d', strtotime("-$i weeks -" . (date('N')-1) . " days"));
            $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));
            
            $revenueQuery = "SELECT SUM(amount) as total FROM bookings b
                             JOIN parking_spots p ON b.parking_spot_id = p.id
                             WHERE p.owner_id = ? AND b.status = 'completed'
                             AND DATE(b.created_at) BETWEEN ? AND ?";
            $stmt = $conn->prepare($revenueQuery);
            $stmt->bind_param("iss", $owner_id, $weekStart, $weekEnd);
            $stmt->execute();
            $revenueResult = $stmt->get_result()->fetch_assoc();
            $monthlyRevenue[] = $revenueResult['total'] ?? 0;
        }

      
        $this->view("Owner/index", [
            'totalSpaces' => $totalSpaces,
            'totalSlots' => $totalSlots,
            'totalearnings' => $totalearnings,
            'lastMonthEarnings' => $lastMonthEarnings,
            'earningsChange' => round($earningsChange, 1),
            'activeBookings' => $activeBookings,
            'pendingBookings' => $pendingBookings,
            'totalBookingsCount' => $totalBookingsCount,
            'bookingsChange' => round($bookingsChange, 1),
            'lastMonthBookings' => $lastMonthBookings,
            'occupancyRate' => $occupancyRate,
            'occupancyChange' => round($occupancyChange, 1),
            'lastMonthOccupancy' => $lastMonthOccupancy,
            'peakHour' => $peakHour,
            'recentBookings' => $recentBookings,
            'notifications' => $notifications,
            'weeklyRevenue' => $weeklyRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'chartLabels' => $weeklyLabels
        ]);
    }

    public function getDashboardData()
    {
        header('Content-Type: application/json');
        
        $db = new Database();
        $conn = $db->getConnection();
        $owner = Auth::user();
        $owner_id = $owner['id'];
        
    
        $totalEarningsQuery = "SELECT SUM(amount) as total FROM bookings b 
                               JOIN parking_spots p ON b.parking_spot_id = p.id 
                               WHERE p.owner_id = ? AND b.status = 'completed'
                               AND b.created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')";
        $stmt = $conn->prepare($totalEarningsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $totalearnings = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
        
        $lastMonthEarningsQuery = "SELECT SUM(amount) as total FROM bookings b 
                                   JOIN parking_spots p ON b.parking_spot_id = p.id 
                                   WHERE p.owner_id = ? AND b.status = 'completed'
                                   AND b.created_at >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m-01')
                                   AND b.created_at < DATE_FORMAT(NOW(), '%Y-%m-01')";
        $stmt = $conn->prepare($lastMonthEarningsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $lastMonthEarnings = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
        
        $earningsChange = $lastMonthEarnings > 0 ? (($totalearnings - $lastMonthEarnings) / $lastMonthEarnings) * 100 : 0;
        
        
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
        
        $totalBookings = $activeBookings + $pendingBookings;
        
       
        $lastMonthBookingsQuery = "SELECT COUNT(*) as total FROM bookings b 
                                   JOIN parking_spots p ON b.parking_spot_id = p.id 
                                   WHERE p.owner_id = ? 
                                   AND b.created_at >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m-01')
                                   AND b.created_at < DATE_FORMAT(NOW(), '%Y-%m-01')";
        $stmt = $conn->prepare($lastMonthBookingsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $lastMonthBookings = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
        
        $bookingsChange = $lastMonthBookings > 0 ? (($totalBookings - $lastMonthBookings) / $lastMonthBookings) * 100 : 0;
        
        
        $totalSlotsQuery = "SELECT SUM(total_slots) as total FROM parking_spots WHERE owner_id = ?";
        $stmt = $conn->prepare($totalSlotsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $totalslots = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
        
        $bookedSlotsQuery = "SELECT SUM(b.booked_slots) as total FROM bookings b 
                             JOIN parking_spots p ON b.parking_spot_id = p.id 
                             WHERE p.owner_id = ? AND b.status = 'active' 
                             AND NOW() BETWEEN b.start_time AND b.end_time";
        $stmt = $conn->prepare($bookedSlotsQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $bookedSlots = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
        
        $occupancyRate = ($totalslots > 0) ? round(($bookedSlots / $totalslots) * 100, 1) : 0;
        
        
        $lastMonthOccupancyQuery = "SELECT AVG(daily_rate) as avg_rate FROM (
                                    SELECT DATE(b.start_time) as date, 
                                           SUM(b.booked_slots) / ? * 100 as daily_rate
                                    FROM bookings b 
                                    JOIN parking_spots p ON b.parking_spot_id = p.id 
                                    WHERE p.owner_id = ? AND b.status = 'active'
                                    AND b.start_time >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m-01')
                                    AND b.start_time < DATE_FORMAT(NOW(), '%Y-%m-01')
                                    GROUP BY DATE(b.start_time)
                                    ) as daily_rates";
        $stmt = $conn->prepare($lastMonthOccupancyQuery);
        $stmt->bind_param("ii", $totalslots, $owner_id);
        $stmt->execute();
        $lastMonthOccupancyResult = $stmt->get_result()->fetch_assoc();
        $lastMonthOccupancy = round($lastMonthOccupancyResult['avg_rate'] ?? 0, 1);
        
        $occupancyChange = $occupancyRate - $lastMonthOccupancy;
        
        
        $peakHourQuery = "SELECT HOUR(b.start_time) as hour, COUNT(*) as booking_count
                          FROM bookings b
                          JOIN parking_spots p ON b.parking_spot_id = p.id
                          WHERE p.owner_id = ? 
                          AND b.status IN ('active', 'completed')
                          AND b.start_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                          GROUP BY HOUR(b.start_time)
                          ORDER BY booking_count DESC
                          LIMIT 1";
        $stmt = $conn->prepare($peakHourQuery);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $peakHourResult = $stmt->get_result()->fetch_assoc();
        
        if ($peakHourResult && isset($peakHourResult['hour'])) {
            $peakHourStart = $peakHourResult['hour'];
            $peakHourEnd = $peakHourStart + 2;
            $peakHour = sprintf("%02d:00 - %02d:00", $peakHourStart, $peakHourEnd);
        } else {
            $peakHour = "14:00 - 16:00";
        }
     
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
        
        echo json_encode([
            'success' => true,
            'totalearnings' => number_format($totalearnings, 2),
            'earningsChange' => round($earningsChange, 1),
            'totalBookings' => $totalBookings,
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
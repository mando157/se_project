<?php

require_once "../app/helpers/Auth.php";

class OwnerController extends Controller
{
    private $conn;
    
    public function __construct()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('owner');
        
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }
    
    public function index()
    {
        $this->dashboard();
    }
    
    private function getOwnerId()
    {
        $user = Auth::user();
        return $user['id'] ?? 0;
    }
    
    public function dashboard()
    {
        $ownerId = $this->getOwnerId();
        
        $totalEarnings = $this->getTotalEarnings($ownerId);
        $lastMonthEarnings = $this->getLastMonthEarnings($ownerId);
        $earningsChange = $this->calculatePercentageChange($lastMonthEarnings, $totalEarnings);
        
        $activeBookings = $this->getActiveBookingsCount($ownerId);
        $pendingBookings = $this->getPendingBookingsCount($ownerId);
        $prevActiveBookings = $this->getPreviousActiveBookingsCount($ownerId);
        $bookingsChange = $this->calculatePercentageChange($prevActiveBookings, $activeBookings);
        
        $totalSlots = $this->getTotalSlots($ownerId);
        $occupiedSlots = $this->getOccupiedSlots($ownerId);
        $occupancyRate = $totalSlots > 0 ? round(($occupiedSlots / $totalSlots) * 100, 1) : 0;
        $prevOccupancyRate = $this->getPreviousOccupancyRate($ownerId);
        $occupancyChange = $this->calculatePercentageChange($prevOccupancyRate, $occupancyRate);
        
        $peakHour = $this->getPeakHour($ownerId);
        $weeklyRevenue = $this->getWeeklyRevenue($ownerId);
        $monthlyRevenue = $this->getMonthlyRevenue($ownerId);
        $recentBookings = $this->getRecentBookings($ownerId);
        $notifications = $this->getNotifications($ownerId);
        
        $this->view("Owner/dashboard", [
            'totalEarnings' => $totalEarnings,
            'lastMonthEarnings' => $lastMonthEarnings,
            'earningsChange' => $earningsChange,
            'activeBookings' => $activeBookings,
            'pendingBookings' => $pendingBookings,
            'bookingsChange' => $bookingsChange,
            'occupancyRate' => $occupancyRate,
            'occupancyChange' => $occupancyChange,
            'peakHour' => $peakHour,
            'weeklyRevenue' => $weeklyRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'recentBookings' => $recentBookings,
            'notifications' => $notifications
        ]);
    }
    
    private function getTotalEarnings($ownerId)
    {
        $query = "SELECT COALESCE(SUM(b.total_cost), 0) as total 
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? AND b.status IN ('active', 'completed')";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return (float)($row['total'] ?? 0);
    }
    
    private function getLastMonthEarnings($ownerId)
    {
        $query = "SELECT COALESCE(SUM(b.total_cost), 0) as total 
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? 
                    AND b.status IN ('active', 'completed')
                    AND b.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 2 MONTH) AND DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return (float)($row['total'] ?? 0);
    }
    
    private function getActiveBookingsCount($ownerId)
    {
        $query = "SELECT COUNT(*) as count 
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? AND b.status = 'active'";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return (int)($row['count'] ?? 0);
    }
    
    private function getPendingBookingsCount($ownerId)
    {
        $query = "SELECT COUNT(*) as count 
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? AND b.status = 'pending'";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return (int)($row['count'] ?? 0);
    }
    
    private function getPreviousActiveBookingsCount($ownerId)
    {
        $query = "SELECT COUNT(*) as count 
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? 
                    AND b.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 2 MONTH) AND DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return (int)($row['count'] ?? 0);
    }
    
    private function getTotalSlots($ownerId)
    {
        $query = "SELECT COALESCE(SUM(total_slots), 0) as total 
                  FROM parking_spots
                  WHERE owner_id = ?";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return (int)($row['total'] ?? 0);
    }
    
    private function getOccupiedSlots($ownerId)
    {
        $query = "SELECT COUNT(*) as occupied
FROM slots s
INNER JOIN parking_spots ps ON s.spot_id = ps.spot_id
WHERE ps.owner_id = ? AND s.status = 'booked'";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return (int)($row['occupied'] ?? 0);
    }
    
    private function getPreviousOccupancyRate($ownerId)
    {
        $query = "SELECT COUNT(DISTINCT b.booking_id) as occupied
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? 
                    AND b.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 2 MONTH) AND DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        $totalSlots = $this->getTotalSlots($ownerId);
        $occupied = (int)($row['occupied'] ?? 0);
        
        return $totalSlots > 0 ? round(($occupied / $totalSlots) * 100, 1) : 0;
    }
    
    private function getPeakHour($ownerId)
    {
        $query = "SELECT HOUR(CONCAT(b.date, ' ', b.start_time)) as hour, COUNT(*) as count
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ?
                  GROUP BY HOUR(CONCAT(b.date, ' ', b.start_time))
                  ORDER BY count DESC
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return '14:00 - 16:00';
        }
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        if ($row && isset($row['hour'])) {
            $hour = (int)$row['hour'];
            return sprintf('%02d:00 - %02d:00', $hour, $hour + 2);
        }
        return '14:00 - 16:00';
    }
    
    private function getWeeklyRevenue($ownerId)
    {
        $weekly = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $query = "SELECT COALESCE(SUM(b.total_cost), 0) as total
                      FROM bookings b
                      INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                      WHERE ps.owner_id = ? 
                        AND b.date = ?
                        AND b.status IN ('active', 'completed')";
            
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                $weekly[] = 0;
                continue;
            }
            $stmt->bind_param("is", $ownerId, $date);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            
            $weekly[] = (float)($row['total'] ?? 0);
        }
        return $weekly;
    }
    
    private function getMonthlyRevenue($ownerId)
    {
        $monthly = [];
        for ($i = 3; $i >= 0; $i--) {
            $monthStart = date('Y-m-01', strtotime("-$i months"));
            $monthEnd = date('Y-m-t', strtotime("-$i months"));
            
            $query = "SELECT COALESCE(SUM(b.total_cost), 0) as total
                      FROM bookings b
                      INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                      WHERE ps.owner_id = ? 
                        AND b.date BETWEEN ? AND ?
                        AND b.status IN ('active', 'completed')";
            
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                $monthly[] = 0;
                continue;
            }
            $stmt->bind_param("iss", $ownerId, $monthStart, $monthEnd);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            
            $monthly[] = (float)($row['total'] ?? 0);
        }
        return $monthly;
    }
    
    private function getRecentBookings($ownerId, $limit = 10)
    {
        $query = "SELECT b.*, ps.spot_name
FROM bookings b
INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ?
                  ORDER BY b.created_at DESC
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [];
        }
        $stmt->bind_param("ii", $ownerId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $row['booked_slots'] = 1;
            $bookings[] = $row;
        }
        $stmt->close();
        
        return $bookings;
    }
    
    private function getNotifications($ownerId, $limit = 10)
    {
        $query = "SELECT * FROM notifications 
                  WHERE user_id = ? 
                  ORDER BY created_at DESC 
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [];
        }
        $stmt->bind_param("ii", $ownerId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        $stmt->close();
        
        return $notifications;
    }
    
    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) return $newValue > 0 ? 100 : 0;
        return round((($newValue - $oldValue) / $oldValue) * 100, 1);
    }
    
    public function getDashboardData()
    {
        $ownerId = $this->getOwnerId();
        
        $totalEarnings = $this->getTotalEarnings($ownerId);
        $activeBookings = $this->getActiveBookingsCount($ownerId);
        $pendingBookings = $this->getPendingBookingsCount($ownerId);
        $totalSlots = $this->getTotalSlots($ownerId);
        $occupiedSlots = $this->getOccupiedSlots($ownerId);
        $occupancyRate = $totalSlots > 0 ? round(($occupiedSlots / $totalSlots) * 100, 1) : 0;
        $peakHour = $this->getPeakHour($ownerId);
        
        $lastMonthEarnings = $this->getLastMonthEarnings($ownerId);
        $earningsChange = $this->calculatePercentageChange($lastMonthEarnings, $totalEarnings);
        
        $prevActiveBookings = $this->getPreviousActiveBookingsCount($ownerId);
        $bookingsChange = $this->calculatePercentageChange($prevActiveBookings, $activeBookings);
        
        $prevOccupancyRate = $this->getPreviousOccupancyRate($ownerId);
        $occupancyChange = $this->calculatePercentageChange($prevOccupancyRate, $occupancyRate);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'totalearnings' => $totalEarnings,
            'activeBookings' => $activeBookings,
            'pendingBookings' => $pendingBookings,
            'occupancyRate' => $occupancyRate,
            'peakHour' => $peakHour,
            'earningsChange' => $earningsChange,
            'bookingsChange' => $bookingsChange,
            'occupancyChange' => $occupancyChange
        ]);
    }
    
    public function markNotificationsRead()
    {
        $ownerId = $this->getOwnerId();
        
        $query = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $ownerId);
            $stmt->execute();
            $stmt->close();
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }
    
    public function notifications()
    {
        $ownerId = $this->getOwnerId();
        $notifications = $this->getNotifications($ownerId, 50);
        
        $this->view("Owner/notifications", [
            'notifications' => $notifications
        ]);
    }
    
    public function bookings()
    {
        $ownerId = $this->getOwnerId();
        $bookings = $this->getRecentBookings($ownerId, 50);
        
        $this->view("Owner/bookings", [
            'bookings' => $bookings
        ]);
    }
    
    public function spaces()
    {
        $ownerId = $this->getOwnerId();
        
        $query = "SELECT * FROM parking_spots WHERE owner_id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            $spaces = [];
        } else {
            $stmt->bind_param("i", $ownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            $spaces = [];
            while ($row = $result->fetch_assoc()) {
                $spaces[] = $row;
            }
            $stmt->close();
        }
        
        $this->view("Owner/spaces", [
            'spaces' => $spaces
        ]);
    }
// ========================= EARNINGS PAGE =========================

public function earnings()
{
    // Get current owner ID from session/auth
    $ownerId = $this->getOwnerId();

    // Get main cards data (KPIs)
    $totalRevenue = $this->getTotalRevenue($ownerId);
    $activeSpaces = $this->getActiveSpacesCount($ownerId);
    $pendingPayout = $this->getPendingPayout($ownerId);

    // Get weekly revenue data for chart
    // (This function already exists in your controller)
    $weeklyRevenue = $this->getWeeklyRevenue($ownerId);

    // Send all data to the Earnings view
    $this->view("Owner/earnings", [
        'totalRevenue' => $totalRevenue,
        'activeSpaces' => $activeSpaces,
        'pendingPayout' => $pendingPayout,
        'weeklyRevenue' => $weeklyRevenue
    ]);
}


// ========================= TOTAL REVENUE =========================

private function getTotalRevenue($ownerId)
{
    // Sum all completed + active bookings revenue for this owner
    $query = "SELECT COALESCE(SUM(b.total_cost), 0) AS total
              FROM bookings b
              INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
              WHERE ps.owner_id = ?
              AND b.status IN ('active', 'completed')";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return 0;
    }

    $stmt->bind_param("i", $ownerId);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();

    return (float)($row['total'] ?? 0);
}


// ========================= ACTIVE SPACES =========================

private function getActiveSpacesCount($ownerId)
{
    // Count all active parking spaces owned by user
    $query = "SELECT COUNT(*) AS count
              FROM parking_spots
              WHERE owner_id = ?
              AND status = 'active'";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return 0;
    }

    $stmt->bind_param("i", $ownerId);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();

    return (int)($row['count'] ?? 0);
}


// ========================= PENDING PAYOUT =========================

private function getPendingPayout($ownerId)
{
    // Sum all pending booking payments
    $query = "SELECT COALESCE(SUM(b.total_cost), 0) AS total
              FROM bookings b
              INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
              WHERE ps.owner_id = ?
              AND b.status = 'pending'";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return 0;
    }

    $stmt->bind_param("i", $ownerId);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();

    return (float)($row['total'] ?? 0);
}
// ===============================
// Availability Page
// ===============================
public function availability()
{
    $ownerId = $this->getOwnerId();

    // Get all owner parking spaces with slots
    $spaces = $this->getSpacesWithSlots($ownerId);

    // Count available slots
    $availableSlots = $this->countSlotsByStatus($ownerId, 'active');

    // Count booked slots
    $bookedSlots = $this->countSlotsByStatus($ownerId, 'booked');

    // Get first space name as default active space
    $currentSpaceName = !empty($spaces)
        ? $spaces[0]['spot_name']
        : 'No Active Space';

    // Get chart data for occupancy analytics
    $chartData = $this->getAvailabilityChartData($ownerId);

    // Send data to view
    $this->view("Owner/availability", [
        'spaces' => $spaces,
        'availableSlots' => $availableSlots,
        'bookedSlots' => $bookedSlots,
        'currentSpaceName' => $currentSpaceName,
        'chartData' => $chartData
    ]);
}


// ===============================
// Get spaces with all slots
// ===============================
private function getSpacesWithSlots($ownerId)
{
    $query = "
        SELECT ps.spot_id, ps.spot_name, s.slot_id, s.status
        FROM parking_spots ps
        LEFT JOIN slots s ON ps.spot_id = s.spot_id
        WHERE ps.owner_id = ?
        ORDER BY ps.spot_id
    ";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return [];
    }

    $stmt->bind_param("i", $ownerId);
    $stmt->execute();
    $result = $stmt->get_result();

    $spaces = [];

    while ($row = $result->fetch_assoc()) {
        $spotId = $row['spot_id'];

        // Create space if not exists
        if (!isset($spaces[$spotId])) {
            $spaces[$spotId] = [
                'spot_id' => $row['spot_id'],
                'spot_name' => $row['spot_name'],
                'slots' => []
            ];
        }

        // Add slot to current space
        if ($row['slot_id']) {
            $spaces[$spotId]['slots'][] = [
                'slot_id' => $row['slot_id'],
                'status' => $row['status']
            ];
        }
    }

    $stmt->close();

    return array_values($spaces);
}


// ===============================
// Count slots by status
// active / booked / blocked
// ===============================
private function countSlotsByStatus($ownerId, $status)
{
    $query = "
        SELECT COUNT(*) as total
        FROM slots s
        INNER JOIN parking_spots ps ON s.spot_id = ps.spot_id
        WHERE ps.owner_id = ? AND s.status = ?
    ";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return 0;
    }

    $stmt->bind_param("is", $ownerId, $status);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();

    return (int)($row['total'] ?? 0);
}


// ===============================
// Get weekly chart data
// Example: active bookings count
// ===============================
private function getAvailabilityChartData($ownerId)
{
    $chartData = [];

    for ($i = 6; $i >= 0; $i--) {

        $date = date('Y-m-d', strtotime("-$i days"));

        $query = "
            SELECT COUNT(*) as total
            FROM bookings b
            INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
            WHERE ps.owner_id = ?
            AND b.date = ?
            AND b.status = 'active'
        ";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            $chartData[] = 0;
            continue;
        }

        $stmt->bind_param("is", $ownerId, $date);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $chartData[] = (int)($row['total'] ?? 0);

        $stmt->close();
    }

    return $chartData;
}
}

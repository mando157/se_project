<?php

require_once "../app/helpers/Auth.php";
require_once "../core/Database.php";
require_once "../core/Controller.php";

class OwnerController extends Controller
{
    private $conn;
    
    public function __construct()
    {
       
        
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }
    
    private function getOwnerId()
    {
                $query = "SELECT id FROM users WHERE role = 'owner' LIMIT 1";
        $result = $this->conn->query($query);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['id'];
        }
        return 1; 
    }
    
    public function dashboard()
    {
        $ownerId = $this->getOwnerId();
        
        
        echo "<!-- Owner ID: " . $ownerId . " -->";
        
        $data = [
            'totalearnings' => $this->getTotalearnings($ownerId),
            'lastMonthearnings' => $this->getLastMonthearnings($ownerId),
            'earningsChange' => 0,
            'activeBookings' => $this->getActiveBookingsCount($ownerId),
            'pendingBookings' => $this->getPendingBookingsCount($ownerId),
            'bookingsChange' => 0,
            'occupancyRate' => $this->getOccupancyRate($ownerId),
            'occupancyChange' => 0,
            'peakHour' => $this->getPeakHour($ownerId),
            'weeklyRevenue' => $this->getWeeklyRevenue($ownerId),
            'monthlyRevenue' => $this->getMonthlyRevenue($ownerId),
            'recentBookings' => $this->getRecentBookings($ownerId),
            'notifications' => $this->getNotifications($ownerId)
        ];
        
       
        $lastMonthearnings = $this->getLastMonthearnings($ownerId);
        $data['earningsChange'] = $this->calculatePercentageChange($lastMonthearnings, $data['totalearnings']);
        
        $prevActiveBookings = $this->getPreviousActiveBookingsCount($ownerId);
        $data['bookingsChange'] = $this->calculatePercentageChange($prevActiveBookings, $data['activeBookings']);
        
        $prevOccupancyRate = $this->getPreviousOccupancyRate($ownerId);
        $data['occupancyChange'] = $this->calculatePercentageChange($prevOccupancyRate, $data['occupancyRate']);
        
        $this->view("Owner/dashboard", $data);
    }
    
    
    
    private function getTotalearnings($ownerId)
    {
        $query = "SELECT COALESCE(SUM(b.total_cost), 0) as total 
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? AND b.status IN ('active', 'completed')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (float)($row['total'] ?? 0);
    }
    
    private function getLastMonthearnings($ownerId)
    {
        $query = "SELECT COALESCE(SUM(b.total_cost), 0) as total 
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? 
                    AND b.status IN ('active', 'completed')
                    AND b.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 2 MONTH) AND DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (float)($row['total'] ?? 0);
    }
    
    private function getActiveBookingsCount($ownerId)
    {
        $query = "SELECT COUNT(*) as count 
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? AND b.status = 'active'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)($row['count'] ?? 0);
    }
    
    private function getPendingBookingsCount($ownerId)
    {
        $query = "SELECT COUNT(*) as count 
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? AND b.status = 'pending'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
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
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)($row['count'] ?? 0);
    }
    
    private function getTotalSlots($ownerId)
    {
        $query = "SELECT COALESCE(SUM(total_slots), 0) as total 
                  FROM parking_spots
                  WHERE owner_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)($row['total'] ?? 0);
    }
    
    private function getOccupiedSlots($ownerId)
    {
        $query = "SELECT COUNT(DISTINCT b.booking_id) as occupied
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? AND b.status = 'active'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)($row['occupied'] ?? 0);
    }
    
    private function getOccupancyRate($ownerId)
    {
        $totalSlots = $this->getTotalSlots($ownerId);
        $occupiedSlots = $this->getOccupiedSlots($ownerId);
        return $totalSlots > 0 ? round(($occupiedSlots / $totalSlots) * 100, 1) : 0;
    }
    
    private function getPreviousOccupancyRate($ownerId)
    {
        $query = "SELECT COUNT(DISTINCT b.booking_id) as occupied
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? 
                    AND b.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 2 MONTH) AND DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $totalSlots = $this->getTotalSlots($ownerId);
        $occupied = (int)($row['occupied'] ?? 0);
        return $totalSlots > 0 ? round(($occupied / $totalSlots) * 100, 1) : 0;
    }
    
    private function getPeakHour($ownerId)
    {
        $query = "SELECT HOUR(start_time) as hour, COUNT(*) as count
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ?
                  GROUP BY HOUR(start_time)
                  ORDER BY count DESC
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
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
            $stmt->bind_param("is", $ownerId, $date);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
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
            $stmt->bind_param("iss", $ownerId, $monthStart, $monthEnd);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
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
        $stmt->bind_param("ii", $ownerId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $row['booked_slots'] = 1;
            $bookings[] = $row;
        }
        return $bookings;
    }
    
    private function getNotifications($ownerId, $limit = 10)
    {
        $query = "SELECT * FROM notifications 
                  WHERE user_id = ? 
                  ORDER BY created_at DESC 
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $ownerId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
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
        
        $data = [
            'success' => true,
            'totalearnings' => $this->getTotalearnings($ownerId),
            'activeBookings' => $this->getActiveBookingsCount($ownerId),
            'pendingBookings' => $this->getPendingBookingsCount($ownerId),
            'occupancyRate' => $this->getOccupancyRate($ownerId),
            'peakHour' => $this->getPeakHour($ownerId),
            'earningsChange' => $this->calculatePercentageChange($this->getLastMonthearnings($ownerId), $this->getTotalearnings($ownerId)),
            'bookingsChange' => $this->calculatePercentageChange($this->getPreviousActiveBookingsCount($ownerId), $this->getActiveBookingsCount($ownerId)),
            'occupancyChange' => $this->calculatePercentageChange($this->getPreviousOccupancyRate($ownerId), $this->getOccupancyRate($ownerId))
        ];
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    public function markNotificationsRead()
    {
        $ownerId = $this->getOwnerId();
        
        $query = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $ownerId);
            $stmt->execute();
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }
    // ========================= EARNINGS PAGE =========================
public function earnings()
{
    $ownerId = $this->getOwnerId();

    $totalRevenue = $this->getTotalRevenue($ownerId);
    $activeSpaces = $this->getActiveSpacesCount($ownerId);
    $pendingPayout = $this->getPendingPayout($ownerId);
    $weeklyRevenue = $this->getWeeklyRevenue($ownerId);
    $notifications = $this->getNotifications($ownerId);


    $this->view("Owner/earnings", [
        'totalRevenue'   => $totalRevenue,
        'activeSpaces'   => $activeSpaces,
        'pendingPayout'  => $pendingPayout,
        'weeklyRevenue'  => $weeklyRevenue, 
        'notifications'  => $notifications
    ]);
}


// ========================= TOTAL REVENUE =========================
private function getTotalRevenue($ownerId)
{
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
// AVAILABILITY PAGE
// ===============================
public function availability()
{
    $ownerId = $this->getOwnerId();

    $spaces = $this->getSpacesWithSlots($ownerId);
    $availableSlots = $this->countSlotsByStatus($ownerId, 'active');
    $bookedSlots = $this->countSlotsByStatus($ownerId, 'booked');
    $blockedSlots = $this->countSlotsByStatus($ownerId, 'blocked');

    $currentSpaceName = !empty($spaces)
        ? $spaces[0]['spot_name']
        : 'No Active Space';

    $chartData = $this->getAvailabilityChartData($ownerId);

    $this->view("Owner/availability", [
        'spaces' => $spaces,
        'availableSlots' => $availableSlots,
        'bookedSlots' => $bookedSlots,
        'blockedSlots' => $blockedSlots,
        'currentSpaceName' => $currentSpaceName,
        'chartData' => $chartData
    ]);
}



// ===============================
// GET SPACES WITH SLOTS
// ===============================
private function getSpacesWithSlots($ownerId)
{
    $query = "
        SELECT 
            ps.spot_id,
            ps.spot_name,
            s.slot_id,
            s.slot_name,
            s.status
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

        if (!isset($spaces[$spotId])) {
            $spaces[$spotId] = [
                'spot_id' => $row['spot_id'],
                'spot_name' => $row['spot_name'],
                'slots' => []
            ];
        }

        if (!empty($row['slot_id'])) {
            $spaces[$spotId]['slots'][] = [
                'slot_id' => $row['slot_id'],
                'slot_name' => $row['slot_name'],
                'status' => $row['status']
            ];
        }
    }

    $stmt->close();

    return array_values($spaces);
}



// ===============================
// COUNT SLOTS BY STATUS
// ===============================
private function countSlotsByStatus($ownerId, $status)
{
    $query = "
        SELECT COUNT(*) as total
        FROM slots s
        INNER JOIN parking_spots ps ON s.spot_id = ps.spot_id
        WHERE ps.owner_id = ?
        AND s.status = ?
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
// AVAILABILITY CHART DATA
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

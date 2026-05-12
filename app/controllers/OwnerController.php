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
        $user = Auth::user();
        return $user['id'];
    }
    
public function index()
{
    $ownerId = $this->getOwnerId();

    $data = $this->buildIndexData($ownerId);

    $data['user'] = Auth::user();

    $this->view("Owner/index", $data);
}
    
    
    
    private function getTotalEarnings($ownerId)
{
    return $this->getRevenue($ownerId);
}
    
    private function getLastMonthEarnings($ownerId)
    {
        $query = "SELECT COALESCE(SUM(b.total_cost), 0) as total 
                  FROM bookings b
                  INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
                  WHERE ps.owner_id = ? 
                    AND b.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 2 MONTH) AND DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (float)($row['total'] ?? 0);
    }
private function getRevenue($ownerId)
{
    $query = "SELECT COALESCE(SUM(b.total_cost),0) as total
              FROM bookings b
              INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
              WHERE ps.owner_id = ?";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $ownerId);
    $stmt->execute();

    return (float) $stmt->get_result()->fetch_assoc()['total'];
}
    private function getActiveBookingsCount($ownerId)
    {
            return $this->getBookingsCountByStatus($ownerId, 'active');
    }
    private function getTotalBookingsCount($ownerId)
{
    $query = "
        SELECT COUNT(*) as count
        FROM bookings b
        INNER JOIN parking_spots ps
            ON b.spot_id = ps.spot_id
        WHERE ps.owner_id = ?
    ";

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
    return $this->getBookingsCountByStatus($ownerId, 'pending');
}
    
    private function getBookingsCountByStatus($ownerId, $status)
{
    $query = "SELECT COUNT(*) as count
              FROM bookings b
              INNER JOIN parking_spots ps ON b.spot_id = ps.spot_id
              WHERE ps.owner_id = ? AND b.status = ?";

    $stmt = $this->conn->prepare($query);

    $stmt->bind_param("is", $ownerId, $status);

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
    
private function getOccupancyRate($ownerId)
{
    $query = "
        SELECT 
            COUNT(*) as total_slots,

            SUM(
                CASE 
                    WHEN s.status = 'booked' THEN 1
                    ELSE 0
                END
            ) as occupied_slots

        FROM slots s

        INNER JOIN parking_spots ps
            ON s.spot_id = ps.spot_id

        WHERE ps.owner_id = ?
    ";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return 0;
    }

    $stmt->bind_param("i", $ownerId);

    $stmt->execute();

    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

    $totalSlots = (int)($row['total_slots'] ?? 0);

    $occupiedSlots = (int)($row['occupied_slots'] ?? 0);

    if ($totalSlots <= 0) {
        return 0;
    }

    return round(($occupiedSlots / $totalSlots) * 100, 1);
}
public function addSpace()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die("Invalid Request");
    }

    $ownerId = $this->getOwnerId();

    $location = trim($_POST['location'] ?? '');

    $price = (float) ($_POST['price_per_hour'] ?? 0);

    $totalSlots = (int) ($_POST['total_slots'] ?? 1);

    if (
        empty($location) ||
        $price <= 0 ||
        $totalSlots <= 0
    ) {
        die("Please fill all fields correctly");
    }

    $query = "
        INSERT INTO parking_spots
        (
            owner_id,
            spot_name,
            location,
            price,
            total_slots,
            status
        )
        VALUES
        (
            ?, ?, ?, ?, ?, 'active'
        )
    ";

    $stmt = $this->conn->prepare($query);

    $stmt->bind_param(
        "issdi",
        $ownerId,
        $location,
        $location,
        $price,
        $totalSlots
    );

    if ($stmt->execute()) {

        $spotId = $this->conn->insert_id;

        for ($i = 1; $i <= $totalSlots; $i++) {

            $slotName = 'A' . $i;

            $slotQuery = "
                INSERT INTO slots
                (
                    spot_id,
                    slot_name,
                    status
                )
                VALUES
                (
                    ?, ?, 'active'
                )
            ";

            $slotStmt = $this->conn->prepare($slotQuery);

            $slotStmt->bind_param(
                "is",
                $spotId,
                $slotName
            );

            $slotStmt->execute();
        }

        header("Location: " . BASE_URL . "owner/index");
        exit;
    }

    die("Failed To Add Space");
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
                        AND b.date = ?";
            
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
                        AND b.date BETWEEN ? AND ?";
            
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
private function buildIndexData($ownerId)
{
    $totalEarnings = $this->getTotalEarnings($ownerId);
    $lastMonthEarnings = $this->getLastMonthEarnings($ownerId);
    $totalBookings = $this->getTotalBookingsCount($ownerId);
    $activeBookings = $this->getActiveBookingsCount($ownerId);
    $pendingBookings = $this->getPendingBookingsCount($ownerId);

    $occupancyRate = $this->getOccupancyRate($ownerId);

    return [

        'totalEarnings' => $totalEarnings,
        'lastMonthEarnings' => $lastMonthEarnings,

        'activeBookings' => $activeBookings,
        'pendingBookings' => $pendingBookings,
        'totalBookings' => $totalBookings,
        'occupancyRate' => $occupancyRate,

        'peakHour' => $this->getPeakHour($ownerId),

        'weeklyRevenue' => $this->getWeeklyRevenue($ownerId),

        'monthlyRevenue' => $this->getMonthlyRevenue($ownerId),

        'recentBookings' => $this->getRecentBookings($ownerId),

        'notifications' => $this->getNotifications($ownerId),

        'earningsChange' => $this->calculatePercentageChange(
            $lastMonthEarnings,
            $totalEarnings
        ),

        'bookingsChange' => $this->calculatePercentageChange(
            $this->getPreviousActiveBookingsCount($ownerId),
            $activeBookings
        ),

        'occupancyChange' => $this->calculatePercentageChange(
            $this->getPreviousOccupancyRate($ownerId),
            $occupancyRate
        )
    ];
}
    public function getIndexData()
{
    $ownerId = $this->getOwnerId();

    $data = $this->buildIndexData($ownerId);

    $data['success'] = true;

    $this->jsonResponse($data);
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
        
       $this->jsonResponse(['success' => true]);
    }
    // ========================= EARNINGS PAGE =========================
public function earnings()
{
    $ownerId = $this->getOwnerId();

    $totalRevenue = $this->getTotalRevenue($ownerId);
    $activeSpaces = $this->getActiveSpacesCount($ownerId);
    $weeklyRevenue = $this->getWeeklyRevenue($ownerId);
    $notifications = $this->getNotifications($ownerId);


    $this->view("Owner/earnings", [
        'totalRevenue'   => $totalRevenue,
        'activeSpaces'   => $activeSpaces,
        'weeklyRevenue'  => $weeklyRevenue, 
        'notifications'  => $notifications
    ]);
}


// ========================= TOTAL REVENUE =========================
private function getTotalRevenue($ownerId)
{
    $query = "
        SELECT COALESCE(SUM(b.total_cost), 0) AS totalRevenue
        FROM bookings b
        INNER JOIN parking_spots ps
            ON b.spot_id = ps.spot_id
        WHERE ps.owner_id = ?
    ";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return 0;
    }

    $stmt->bind_param("i", $ownerId);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();

    return (float)($row['totalRevenue'] ?? 0);
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
private function jsonResponse($data)
{
    ob_clean();

    header('Content-Type: application/json');

    echo json_encode($data);

    exit;
}
}
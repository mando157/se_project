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



public function availability()
{
    /*
    =====================================
    STEP 1) Connect to database
    =====================================
    */
    $db = new Database();
    $conn = $db->getConnection();


    /*
    =====================================
    STEP 2) Get logged owner
    =====================================
    */
    $owner = Auth::user();
    $owner_id = $owner['id'];


    /*
    =====================================
    STEP 3) Get owner parking spaces
    =====================================
    */
    $spacesQuery = "
        SELECT *
        FROM parking_spots
        WHERE owner_id = ?
        ORDER BY created_at DESC
    ";

    $stmt = $conn->prepare($spacesQuery);
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();

    $spaces = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


    /*
    =====================================
    STEP 4) Get slots for each space
    =====================================
    */
    foreach ($spaces as &$space) {

        $slotQuery = "
            SELECT *
            FROM slots
            WHERE spot_id = ?
            ORDER BY slot_id ASC
        ";

        $slotStmt = $conn->prepare($slotQuery);
        $slotStmt->bind_param("i", $space['spot_id']);
        $slotStmt->execute();

        $space['slots'] = $slotStmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }

    unset($space);


    /*
    =====================================
    STEP 5) Available slots count
    =====================================
    */
    $availableQuery = "
        SELECT COUNT(*) as total
        FROM slots s
        JOIN parking_spots p ON s.spot_id = p.spot_id
        WHERE p.owner_id = ?
        AND s.status = 'active'
    ";

    $stmt = $conn->prepare($availableQuery);
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();

    $availableSlots = $stmt->get_result()->fetch_assoc()['total'] ?? 0;


    /*
    =====================================
    STEP 6) Booked slots count
    =====================================
    */
    $bookedQuery = "
        SELECT COUNT(*) as total
        FROM slots s
        JOIN parking_spots p ON s.spot_id = p.spot_id
        WHERE p.owner_id = ?
        AND s.status = 'booked'
    ";

    $stmt = $conn->prepare($bookedQuery);
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();

    $bookedSlots = $stmt->get_result()->fetch_assoc()['total'] ?? 0;


    /*
    =====================================
    STEP 7) Blocked slots count
    =====================================
    */
    $blockedQuery = "
        SELECT COUNT(*) as total
        FROM slots s
        JOIN parking_spots p ON s.spot_id = p.spot_id
        WHERE p.owner_id = ?
        AND s.status = 'blocked'
    ";

    $stmt = $conn->prepare($blockedQuery);
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();

    $blockedSlots = $stmt->get_result()->fetch_assoc()['total'] ?? 0;


    /*
    =====================================
    STEP 8) Total slots
    =====================================
    */
    $totalSlots = $availableSlots + $bookedSlots + $blockedSlots;


    /*
    =====================================
    STEP 9) Occupancy Rate
    =====================================
    */
    $occupancyRate = 0;

    if ($totalSlots > 0) {
        $occupancyRate = round(($bookedSlots / $totalSlots) * 100, 2);
    }


    /*
    =====================================
    STEP 10) Default current space
    =====================================
    */
    $currentSpaceName = !empty($spaces)
        ? $spaces[0]['spot_name']
        : "No Space Available";


    /*
    =====================================
    STEP 11) Temporary chart data
    =====================================
    */
    $chartData = [65, 59, 80, 81, 56, 72, 90];


    /*
    =====================================
    STEP 12) Load view
    =====================================
    */
    $this->view("owner/Availability", [
        'owner' => $owner,
        'spaces' => $spaces,
        'availableSlots' => $availableSlots,
        'bookedSlots' => $bookedSlots,
        'blockedSlots' => $blockedSlots,
        'totalSlots' => $totalSlots,
        'occupancyRate' => $occupancyRate,
        'currentSpaceName' => $currentSpaceName,
        'chartData' => $chartData
    ]);
}

public function Earnings()
{
    /*
    =====================================
    STEP 1: Connect to database
    Why?
    Needed to fetch earnings data
    =====================================
    */
    $db = new Database();
    $conn = $db->getConnection();


    /*
    =====================================
    STEP 2: Get logged owner
    Why?
    So owner only sees his own data
    =====================================
    */
    $owner = Auth::user();
    $owner_id = $owner['id'];


    /*
    =====================================
    STEP 3: Calculate Total Revenue
    Why?
    Sum all completed + paid bookings
    =====================================
    */
    $revenueQuery = "
        SELECT SUM(total_cost) AS totalRevenue
        FROM bookings b
        JOIN parking_spots p
        ON b.spot_id = p.spot_id
        WHERE p.owner_id = ?
        AND b.status = 'completed'
        AND b.payment_status = 'paid'
    ";

    $stmt = $conn->prepare($revenueQuery);
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();

    $totalRevenue = $stmt
        ->get_result()
        ->fetch_assoc()['totalRevenue'] ?? 0;


    /*
    =====================================
    STEP 4: Count Active Spaces
    Why?
    Display active spaces card
    =====================================
    */
    $spacesQuery = "
        SELECT COUNT(*) AS activeSpaces
        FROM parking_spots
        WHERE owner_id = ?
        AND status = 'active'
    ";

    $stmt = $conn->prepare($spacesQuery);
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();

    $activeSpaces = $stmt
        ->get_result()
        ->fetch_assoc()['activeSpaces'] ?? 0;


    /*
    =====================================
    STEP 5: Calculate Pending Payout
    Why?
    Money not paid yet
    =====================================
    */
    $pendingQuery = "
        SELECT SUM(total_cost) AS pendingPayout
        FROM bookings b
        JOIN parking_spots p
        ON b.spot_id = p.spot_id
        WHERE p.owner_id = ?
        AND b.payment_status = 'pending'
    ";

    $stmt = $conn->prepare($pendingQuery);
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();

    $pendingPayout = $stmt
        ->get_result()
        ->fetch_assoc()['pendingPayout'] ?? 0;


    /*
    =====================================
    STEP 6: Weekly Revenue Chart Data
    Why?
    Used for revenue graph
    Static حاليا
    Later can be dynamic
    =====================================
    */
    $chartData = [8500, 9200, 10100, 12400, 9800, 7500, 6900];


    /*
    =====================================
    STEP 7: Get Recent Transactions
    Why?
    Show latest bookings/revenue activity
    =====================================
    */
    $transactionsQuery = "
        SELECT
            b.booking_id,
            b.total_cost,
            b.payment_status,
            b.created_at,
            p.spot_name
        FROM bookings b
        JOIN parking_spots p
        ON b.spot_id = p.spot_id
        WHERE p.owner_id = ?
        ORDER BY b.created_at DESC
        LIMIT 5
    ";

    $stmt = $conn->prepare($transactionsQuery);
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();

    $transactions = $stmt
        ->get_result()
        ->fetch_all(MYSQLI_ASSOC);


    /*
    =====================================
    STEP 8: Send all data to view
    Why?
    So earnings page can display data
    =====================================
    */
    $this->view("owner/earnings", [
        'owner' => $owner,
        'totalRevenue' => $totalRevenue,
        'activeSpaces' => $activeSpaces,
        'pendingPayout' => $pendingPayout,
        'chartData' => $chartData,
        'transactions' => $transactions
    ]);
}
}
<?php
require_once "../app/helpers/Validator.php";
require_once "../app/models/User.php";
require_once "../app/helpers/Auth.php";
require_once "../core/Database.php";

class AdminController extends Controller
{
    private $conn;

    public function __construct()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('admin');

        $this->conn = Database::getInstance()->getConnection();
    }

    public function index()
    {
        $this->view("admin/index", [
            'user' => Auth::user(),
            'stats' => $this->getDashboardStats(),
            'recentBookings' => $this->getRecentBookings(8)
        ]);
    }

    public function earnings()
    {
        $this->view("admin/admin-earnings", [
            'user' => Auth::user(),
            'totalRevenue' => $this->getTotalRevenue(),
            'monthlyRevenue' => $this->getMonthlyRevenue(6),
            'topOwners' => $this->getTopOwnersByRevenue(5),
            'recentCompletedBookings' => $this->getRecentCompletedBookings(10)
        ]);
    }

    public function users()
    {
        $this->view("admin/admin-management", [
            'user' => Auth::user(),
            'owners' => $this->getOwnersSummary(),
            'drivers' => $this->getDriversSummary()
        ]);
    }

    public function updateOwnerStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method.']);
        }

        $ownerId = (int)($_POST['owner_id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if ($ownerId <= 0 || !in_array($status, ['active', 'inactive'], true)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid owner data.']);
        }

        $query = "UPDATE parking_spots SET status = ? WHERE owner_id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to prepare owner status query.']);
        }

        $stmt->bind_param("si", $status, $ownerId);
        $ok = $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        if (!$ok) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to update owner status.']);
        }

        $this->jsonResponse([
            'success' => true,
            'message' => $affected > 0 ? 'Owner status updated.' : 'No parking spaces found for this owner.'
        ]);
    }

    public function createFine()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method.']);
        }

        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $reason = trim($_POST['reason'] ?? '');
        $amount = (float)($_POST['amount'] ?? 0);

        if ($bookingId <= 0 || $reason === '' || $amount <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid fine payload.']);
        }

        $booking = $this->getBookingById($bookingId);
        if (!$booking) {
            $this->jsonResponse(['success' => false, 'message' => 'Booking not found.']);
        }

        $query = "INSERT INTO fines (booking_id, user_id, reason, amount, status) VALUES (?, ?, ?, ?, 'unpaid')";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to prepare fine query.']);
        }

        $userId = (int)$booking['user_id'];
        $stmt->bind_param("iisd", $bookingId, $userId, $reason, $amount);
        $ok = $stmt->execute();
        $stmt->close();

        if (!$ok) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to create fine.']);
        }

        $title = "Fine Issued";
        $message = "A fine of $" . number_format($amount, 2) . " was added. Reason: {$reason}.";
        $this->createNotification($userId, $bookingId, $title, $message);

        $this->jsonResponse(['success' => true, 'message' => 'Fine created successfully.']);
    }

    public function blockDriver()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method.']);
        }

        $driverId = (int)($_POST['driver_id'] ?? 0);
        if ($driverId <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid driver id.']);
        }

        $query = "SELECT booking_id
                  FROM bookings
                  WHERE user_id = ?
                  AND status IN ('pending', 'active')";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to prepare block query.']);
        }

        $stmt->bind_param("i", $driverId);
        $stmt->execute();
        $result = $stmt->get_result();
        $bookingIds = [];
        while ($row = $result->fetch_assoc()) {
            $bookingIds[] = (int)$row['booking_id'];
        }
        $stmt->close();

        if (empty($bookingIds)) {
            $this->jsonResponse(['success' => false, 'message' => 'No active or pending bookings to block.']);
        }

        $this->conn->begin_transaction();
        try {
            $cancelQuery = "UPDATE bookings
                            SET status = 'cancelled'
                            WHERE user_id = ?
                            AND status IN ('pending', 'active')";
            $cancelStmt = $this->conn->prepare($cancelQuery);
            if (!$cancelStmt) {
                throw new Exception('Failed to prepare cancellation query.');
            }

            $cancelStmt->bind_param("i", $driverId);
            if (!$cancelStmt->execute()) {
                throw new Exception('Failed to cancel driver bookings.');
            }
            $cancelStmt->close();

            $title = "Account Temporarily Blocked";
            $message = "Your active/pending bookings were blocked by admin review.";
            foreach ($bookingIds as $bookingId) {
                $this->createNotification($driverId, $bookingId, $title, $message);
            }

            $this->conn->commit();
            $this->jsonResponse(['success' => true, 'message' => 'Driver blocked and bookings cancelled.']);
        } catch (Exception $e) {
            $this->conn->rollback();
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getDashboardData()
    {
        $this->jsonResponse([
            'success' => true,
            'stats' => $this->getDashboardStats(),
            'recentBookings' => $this->getRecentBookings(10)
        ]);
    }

    private function getDashboardStats()
    {
        return [
            'totalRevenue' => $this->getTotalRevenue(),
            'lastMonthRevenue' => $this->getLastMonthRevenue(),
            'activeBookings' => $this->getBookingsCountByStatus('active'),
            'pendingBookings' => $this->getBookingsCountByStatus('pending'),
            'completedBookings' => $this->getBookingsCountByStatus('completed'),
            'totalSpots' => $this->countRows('parking_spots'),
            'totalSlots' => $this->countRows('slots'),
            'activeOwners' => $this->countUsersByRole('owner'),
            'activeDrivers' => $this->countUsersByRole('driver')
        ];
    }

    private function getTotalRevenue()
    {
        $query = "SELECT COALESCE(SUM(total_cost), 0) AS total FROM bookings";
        return $this->fetchSingleFloat($query, "total");
    }

    private function getLastMonthRevenue()
    {
        $query = "SELECT COALESCE(SUM(total_cost), 0) AS total
                  FROM bookings
                  WHERE date BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE()";
        return $this->fetchSingleFloat($query, "total");
    }

    private function getMonthlyRevenue($months = 6)
    {
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthStart = date('Y-m-01', strtotime("-{$i} months"));
            $monthEnd = date('Y-m-t', strtotime("-{$i} months"));
            $monthLabel = date('M Y', strtotime($monthStart));

            $query = "SELECT COALESCE(SUM(total_cost), 0) AS total
                      FROM bookings
                      WHERE date BETWEEN ? AND ?";
            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                $data[] = ['month' => $monthLabel, 'total' => 0];
                continue;
            }

            $stmt->bind_param("ss", $monthStart, $monthEnd);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            $data[] = [
                'month' => $monthLabel,
                'total' => (float)($row['total'] ?? 0)
            ];
        }

        return $data;
    }

    private function getBookingsCountByStatus($status)
    {
        $query = "SELECT COUNT(*) AS total FROM bookings WHERE status = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("s", $status);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int)($row['total'] ?? 0);
    }

    private function getRecentBookings($limit = 10)
    {
        $query = "SELECT b.booking_id, b.user_id, u.fullName, b.location, b.duration, b.total_cost, b.status, b.date, b.start_time, b.end_time
                  FROM bookings b
                  INNER JOIN users u ON u.id = b.user_id
                  ORDER BY b.created_at DESC
                  LIMIT ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $stmt->close();
        return $rows;
    }

    private function getRecentCompletedBookings($limit = 10)
    {
        $query = "SELECT b.booking_id, u.fullName, ps.spot_name, b.total_cost, b.date, b.start_time, b.end_time
                  FROM bookings b
                  INNER JOIN users u ON u.id = b.user_id
                  INNER JOIN parking_spots ps ON ps.spot_id = b.spot_id
                  WHERE b.status = 'completed'
                  ORDER BY b.created_at DESC
                  LIMIT ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $stmt->close();
        return $rows;
    }

    private function getTopOwnersByRevenue($limit = 5)
    {
        $query = "SELECT u.id, u.fullName, u.email, COALESCE(SUM(b.total_cost), 0) AS revenue
                  FROM users u
                  LEFT JOIN parking_spots ps ON ps.owner_id = u.id
                  LEFT JOIN bookings b ON b.spot_id = ps.spot_id
                  WHERE u.role = 'owner'
                  GROUP BY u.id, u.fullName, u.email
                  ORDER BY revenue DESC
                  LIMIT ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $stmt->close();
        return $rows;
    }

    private function getOwnersSummary()
    {
        $query = "SELECT u.id, u.fullName, u.email, COUNT(ps.spot_id) AS spots_count,
                         SUM(CASE WHEN ps.status = 'active' THEN 1 ELSE 0 END) AS active_spots
                  FROM users u
                  LEFT JOIN parking_spots ps ON ps.owner_id = u.id
                  WHERE u.role = 'owner'
                  GROUP BY u.id, u.fullName, u.email
                  ORDER BY u.id DESC";
        return $this->fetchRows($query);
    }

    private function getDriversSummary()
    {
        $query = "SELECT u.id, u.fullName, u.email,
                         MAX(b.booking_id) AS last_booking_id,
                         MAX(CASE WHEN b.status = 'active' THEN CONCAT(b.date, ' ', b.start_time) END) AS active_started_at,
                         COUNT(b.booking_id) AS bookings_count,
                         COALESCE(SUM(CASE WHEN f.status = 'unpaid' THEN f.amount ELSE 0 END), 0) AS unpaid_fines
                  FROM users u
                  LEFT JOIN bookings b ON b.user_id = u.id
                  LEFT JOIN fines f ON f.user_id = u.id
                  WHERE u.role = 'driver'
                  GROUP BY u.id, u.fullName, u.email
                  ORDER BY u.id DESC";
        return $this->fetchRows($query);
    }

    private function getBookingById($bookingId)
    {
        $query = "SELECT booking_id, user_id FROM bookings WHERE booking_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $bookingId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }

    private function countRows($tableName)
    {
        $allowed = ['parking_spots', 'slots', 'bookings', 'users', 'notifications', 'fines'];
        if (!in_array($tableName, $allowed, true)) {
            return 0;
        }

        $query = "SELECT COUNT(*) AS total FROM {$tableName}";
        return $this->fetchSingleInt($query, "total");
    }

    private function countUsersByRole($role)
    {
        $query = "SELECT COUNT(*) AS total FROM users WHERE role = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("s", $role);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int)($row['total'] ?? 0);
    }

    private function fetchSingleFloat($query, $key)
    {
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return 0;
        }

        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (float)($row[$key] ?? 0);
    }

    private function fetchSingleInt($query, $key)
    {
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return 0;
        }

        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int)($row[$key] ?? 0);
    }

    private function fetchRows($query)
    {
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return [];
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $stmt->close();
        return $rows;
    }

    private function createNotification($userId, $bookingId, $title, $message)
    {
        $query = "INSERT INTO notifications (user_id, booking_id, title, message, is_read) VALUES (?, ?, ?, ?, 0)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception('Failed to prepare notification query.');
        }

        $stmt->bind_param("iiss", $userId, $bookingId, $title, $message);
        if (!$stmt->execute()) {
            $stmt->close();
            throw new Exception('Failed to send notification.');
        }

        $stmt->close();
    }

    private function jsonResponse($data)
    {
        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

<?php

class RealtimeBooking
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getRealtimeBookings()
    {
        $sql = "
            SELECT 
                users.fullName AS system_identity,
                parking_spots.location,
                bookings.total_cost AS rent

            FROM realtime_booking

            JOIN users 
                ON realtime_booking.user_id = users.id

            JOIN bookings 
                ON realtime_booking.booking_id = bookings.booking_id

            JOIN parking_spots 
                ON realtime_booking.spot_id = parking_spots.spot_id
        ";

        $result = $this->db->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
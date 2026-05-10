<?php
require_once "../app/helpers/Validator.php";
require_once "../app/models/User.php";
require_once "../app/helpers/Auth.php";
require_once "../app/models/RealtimeBooking.php";

class AdminController extends Controller
{
    public function __construct()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('admin');
    }

    public function index()
    {
        $user = Auth::user();

        $this->view("admin/index", [
            'user' => $user
        ]);
    }

    public function earnings()
    {
        $user = Auth::user();

        $this->view("admin/admin-earnings", [
            'user' => $user
        ]);
    }

    public function users()
    {
        $user = Auth::user();

        $this->view("admin/admin-management", [
            'user' => $user
        ]);
    }

    public function realtimeBookings()
    {
        $user = Auth::user();

        $realtimeModel = new RealtimeBooking();

        $bookings = $realtimeModel->getRealtimeBookings();

        $this->view("admin/admin-earnings", [
            'user' => $user,
            'bookings' => $bookings
        ]);
    }
}


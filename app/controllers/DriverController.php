<?php
require_once "../app/helpers/Auth.php";

class DriverController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $this->view("driver/index", ['user' => $user]);
    }

    public function map()
    {

        // if (!isset($_SESSION['role']) || $_SESSION['role'] != 'driver') {
        //     header("Location: " . BASE_URL . "home");
        //     exit;
        // }

        $user = Auth::user();

        $db = new Database();
        $conn = $db->getConnection();

        $query = "SELECT * FROM parking_spots";
        $result = mysqli_query($conn, $query);

        $spots = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $spots[] = $row;
            }
        }

        $this->view("driver/map", [
            'user' => $user,
            'spots' => $spots
        ]);
    }
}
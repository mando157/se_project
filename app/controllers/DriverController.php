<?php
require_once "../app/helpers/Auth.php";


class DriverController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $this->view("Driver/index", ['user' => $user]);
    }
}

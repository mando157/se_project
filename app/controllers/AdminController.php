<?php
require_once "../app/helpers/Validator.php";
require_once "../app/models/User.php";
require_once "../app/helpers/Auth.php";

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
}


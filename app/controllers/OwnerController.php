<?php

require_once "../app/helpers/Auth.php";

class OwnerController extends Controller
{
    private $ownerModel;

    public function __construct()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('owner');

        $this->ownerModel = $this->model("OwnerModel");
    }

    public function index()
    {
        $user = Auth::user();

        $this->view("owner/Dashboard", [
            'user' => $user
        ]);
    }
}
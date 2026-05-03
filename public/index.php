<?php
session_start();

require_once '../core/App.php';
require_once '../core/Controller.php';
require_once '../core/Database.php';

define("BASE_URL", "http://localhost/se_project/public/");

$app = new App();
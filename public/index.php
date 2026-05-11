<?php
session_start();

define('BASE_URL', '/SE_PROJECT/public/');

require_once "../core/Database.php";
require_once "../core/Controller.php";
require_once "../core/App.php";

$app = new App();
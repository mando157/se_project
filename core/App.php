<?php

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // ======================
        // CONTROLLER
        // ======================
        if (!empty($url[0])) {

            $controllerName = ucfirst(strtolower($url[0])) . "Controller";
            $controllerPath = "../app/controllers/" . $controllerName . ".php";

            if (file_exists($controllerPath)) {
                require_once $controllerPath;
                $this->controller = $controllerName;
            } else {
                require_once "../app/controllers/HomeController.php";
                $this->controller = "HomeController";
            }

            unset($url[0]);

        } else {
            require_once "../app/controllers/HomeController.php";
            $this->controller = "HomeController";
        }

        // ======================
        // CREATE OBJECT
        // ======================
        $this->controller = new $this->controller;

        // ======================
        // METHOD
        // ======================
        if (!empty($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // ======================
        // PARAMS
        // ======================
        $this->params = $url ? array_values($url) : [];

        // ======================
        // CALL METHOD
        // ======================
        call_user_func_array(
            [$this->controller, $this->method],
            $this->params
        );
    }

    private function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode(
                '/',
                filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)
            );
        }
        return [];
    }
}
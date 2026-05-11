<?php

class App
{
    protected $controller = 'OwnerController';  
    protected $method = 'dashboard';            
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

       
        if (!empty($url[0])) {
            $controllerName = ucfirst(strtolower($url[0])) . "Controller";
            $controllerPath = "../app/controllers/" . $controllerName . ".php";

            if (file_exists($controllerPath)) {
                require_once $controllerPath;
                $this->controller = $controllerName;
            }
            unset($url[0]);
        } else {
          
            $controllerPath = "../app/controllers/" . $this->controller . ".php";
            if (file_exists($controllerPath)) {
                require_once $controllerPath;
            }
        }

    
        if (class_exists($this->controller)) {
            $this->controller = new $this->controller;
        } else {
            die("Controller not found: " . $this->controller);
        }

        // تحديد الميثود
        if (!empty($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

 
        $this->params = $url ? array_values($url) : [];

      
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
<?php

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        
        $path = dirname(__DIR__) . '/app/views/' . $view . '.php';
        
        if (file_exists($path)) {
            require_once $path;
        } else {
            die("View not found: " . $view);
        }
    }
}
<?php


class Auth
{
    public static function login($user)
    {
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'] ?? $user['fullName'] ?? '',
            'email' => $user['email'],
            'role'  => $user['role']
        ];
    }
    
    public static function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
    }
    
    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }
    
    public static function isLogged()
    {
        return isset($_SESSION['user']);
    }
    
    public static function role($requiredRole)
    {
        return self::isLogged() && $_SESSION['user']['role'] === $requiredRole;
    }
    
    public static function redirectIfNotLogged()
    {
        if (!self::isLogged()) {
            header('Location: ' . BASE_URL . 'Auth/login');
            exit();
        }
    }
    
    public static function forbidIfNotRole($role)
    {
        if (!self::role($role)) {
            header('HTTP/1.1 403 Forbidden');
            die('غير مصرح لك بالدخول');
        }
    }
}
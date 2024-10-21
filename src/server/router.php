<?php

require_once '../controllers/UserController.php';
require_once '../controllers/CarController.php';
require_once './database.php';

Database::connect();

class Router
{
    public static $routes = [
        '/user/login' => ['UserController', 'login'],
        '/user/signup' => ['UserController', 'signup'],
        '/user/logout' => ['UserController', 'logout'],
        '/user/users' => ['UserController', 'list'],
        '/user/update/car' => ['UserController', 'car_update'],
        '/car/add' => ['CarController', 'add'],
        '/car/delete' => ['CarController', 'delete'],
        '/car/search' => ['CarController', 'search'],
        '/car/cars' => ['CarController', 'list'],
        '/car/stats' => ['CarController', 'stats'],
    ];

    public static function resolve()
    {
        foreach (static::$routes as $key => $value) {
            if ($key == $_SERVER['REQUEST_URI']) {
                call_user_func($value);
                break;
            }
        }
    }
}

Router::resolve();

<?php

    require_once '../controllers/UserController.php';
    require_once '../controllers/CarController.php';
    require_once './database.php';

    Database::connect();

    class Router {
        public static $routes = [
            '/user/login' => ['UserController', 'login'],
            '/user/signup' => ['UserController', 'signup'],
            '/car/add' => ['Car', 'add'],
            '/car/delete' => ['Car', 'delete'],
            '/car/search' => ['Car', 'search'],
            '/car/all' => ['Car', 'all']
        ];

        public static function resolve(){
            foreach (static::$routes as $key => $value){
                if ($key == $_SERVER['REQUEST_URI']){
                    call_user_func($value);
                    break;
                }
            }
        }
    }

    Router::resolve();


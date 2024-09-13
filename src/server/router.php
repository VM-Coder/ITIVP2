<?php

    require_once '../controllers/UserController.php';
    require_once './database.php';

    Database::connect();

    class Router {
        public static $routes = [
            '/login' => ['UserController', 'login'],
            '/signup' => ['UserController', 'signup']
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


<?php

    session_start(); //используется во всех файлах, где необходим доступ к переменной $_SESSION

    require_once '../models/User.php';

    Database::connect();

    switch ($_SERVER['REQUEST_URI']){
        case '/login':
            UserController::login();
            break;

        case '/signup':
            UserController::signup();
            break;
    }

    class UserController {
        public static function login(){
            try {
                $users = User::where([
                    'email = \'' . $_POST['email'] . '\'',
                    'password = \'' . sha1($_POST['password']) . '\''
                ]);

                if (!$users)
                    throw new Exception("Неправильный логин / пароль");
                
                $_SESSION['user'] = $users[0];

                header('location: profile', false);
            } 
            catch (Exception $ex){
                $_SESSION['error'] = $ex->getMessage();
                header('location: authorization', false);
            }
        }
        public static function signup(){
            try {
                if ($_POST['password'] != $_POST['password_confirm'])
                    throw new Exception("Пароли не совпадают");        

                $users = User::where([
                    'email = \'' . $_POST['email'] . '\''
                ]);

                if ($users)
                    throw new Exception("Пользователь с данной почтой уже существует");

                $user = new User();

                $user->id = 0;
                $user->email = $_POST['email'];
                $user->password = $_POST['password'];
                $user->firstname = $_POST['firstname'];
                $user->lastname = $_POST['lastname'];
                $user->is_admin = false;

                $user->save();

                header('location: authorization', false);
            } 
            catch (Exception $ex){
                $_SESSION['error'] = $ex->getMessage();
                header('location: registration', replace: false);
            }
        } 
    }
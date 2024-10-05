<?php

    session_start(); //используется во всех файлах, где необходим доступ к переменной $_SESSION

    require_once '../models/User.php';
    require_once '../server/validator.php';

    class UserController {
        public static function login(){
            try {
                $users = User::where([
                    'email = \'' . $_POST['email'] . '\'',
                    'password = \'' . sha1($_POST['password']) . '\''
                ]);

                if (!$users['status']){
                    if ($users['data'] == 'Пользователи не найдены')
                        throw new Exception('Неправильный логин/пароль');
                    else
                        throw new Exception($users['data']);
                }
                
                $_SESSION['user'] = $users['data'][0];

                header('location: ../profile', false);
            } 
            catch (Exception $ex){
                $_SESSION['error'] = $ex->getMessage();
                header('location: ../authorization', false);
            }
        }
        public static function signup(){
            try {
                if ($_POST['password'] != $_POST['password_confirm'])
                    throw new Exception("Пароли не совпадают");        

                $validator = new Validator(
                    [
                        $_POST['email'], 
                        $_POST['password'], 
                        $_POST['firstname'], 
                        $_POST['lastname']
                    ],
                    [
                        '/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/',
                        '/^[\w\-\.#?!@$%^&*]{8,32}$/',
                        '/^[A-ZА-Я][a-zа-я]{1,32}$/',
                        '/^[A-ZА-Я][a-zа-я]{1,32}$/'
                    ],
                    [
                        'Адрес почты недействителен',
                        'Пароль должен состоять из букв латинского алфавита, цифр, символов .#?!@$%^&*- и иметь длину от 8 до 32 символов',
                        'Имя должно состоять из букв латинского или кириллического алфавитов, начинаться с заглавной буквы и иметь длину от 0 до 32 символов',
                        'Фамилия должна состоять из букв латинского или кириллического алфавитов, начинаться с заглавной буквы и иметь длину от 0 до 32 символов'
                    ] 
                );

                if (!$validator->validate())
                    throw new Exception($validator->last_message);

                $users = User::where([
                    'email = \'' . $_POST['email'] . '\''
                ]);

                if (!$users['status'] && $users['data'] != 'Пользователи не найдены')
                    throw new Exception($users['data']);

                if ($users['status'])
                    throw new Exception('Пользователь с данной почтой уже существует');

                $user = new User();

                $user->id = 0;
                $user->email = $_POST['email'];
                $user->password = $_POST['password'];
                $user->firstname = $_POST['firstname'];
                $user->lastname = $_POST['lastname'];
                $user->is_admin = false;

                $user->save();

                header('location: ../authorization', false);
            } 
            catch (Exception $ex){
                $_SESSION['error'] = $ex->getMessage();
                header('location: ../registration', replace: false);
            }
        } 
    }
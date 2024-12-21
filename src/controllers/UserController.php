<?php

require_once '../models/Car.php';
require_once '../models/User.php';
require_once '../models/Point.php';
require_once '../models/Road.php';
require_once '../models/TrafficLight.php';
require_once '../models/Param.php';

session_start();

require_once '../server/validator.php';

class UserController
{
    public static function login()
    {
        try {
            $users = User::where([
                'email = \'' . $_POST['email'] . '\'',
                'password = \'' . sha1($_POST['password']) . '\''
            ]);

            if (!$users['status']) {
                if ($users['data'] == 'Пользователи не найдены')
                    throw new Exception('Неправильный логин/пароль');
                else
                    throw new Exception($users['data']);
            }

            $_SESSION['user'] = $users['data'][0];
            $_SESSION['key'] = md5($_SESSION['user']->id . ' ' . $_SESSION['user']->password . ' ' . $_SESSION['user']->email);

            $json = json_encode(['firstname' => $_SESSION['user']->firstname, 'lastname' => $_SESSION['user']->lastname]);
            
            $cipher = sodium_crypto_aead_aes256gcm_encrypt($json, 'user',  'abcdefabcdef', $_SESSION['key']);
            setcookie('user', $cipher, time() + 86400, '/', '', true);

            $theme = isset($_COOKIE['theme']) ? sodium_crypto_aead_aes256gcm_decrypt($_COOKIE['theme'], 'theme', 'abcdefabcdef', $_SESSION['key']) : 'light';

            if ($theme == 'light') {
                $dark = sodium_crypto_aead_aes256gcm_encrypt('dark', 'theme', 'abcdefabcdef', $_SESSION['key']); 
                setcookie('theme', $dark, time() + 30 * 86400, '/', '', true, true);
            } else {
                $light = sodium_crypto_aead_aes256gcm_encrypt('dark', 'theme', 'abcdefabcdef', $_SESSION['key']); 
                setcookie('theme', $light, time() + 30 * 86400, '/',  '', true, true);
            }

            if ($_SESSION['user']->role == 'A') {
                CarController::stats();
                CarController::list();
                UserController::list();

                ParamController::list();

                $sorted_roads = Road::allCoefOrder();
                if ($sorted_roads['status']) {
                    $_SESSION['sorted_roads'] = $sorted_roads['data'];
                }

                header('location: ../admin', false);
            } else {
                $points = Point::all();
                $roads = Road::all();
                $traffic_lights = TrafficLight::all();
                $params = Param::all();

                if ($points['status'] && $roads['status'] && $traffic_lights['status'] && $params['status']) {
                    $_SESSION['points'] = $points['data'];
                    $_SESSION['roads'] = $roads['data'];
                    $_SESSION['traffic_lights'] = $traffic_lights['data'];
                    $_SESSION['params'] = $params['data'];
                } else
                    throw new Error("Ошибка при получении карты");

                $cars = Car::all();

                if ($cars['status'] && $cars['status']) {
                    $_SESSION['cars'] = $cars['data'];
                } else
                    throw new Error("Ошибка при получении автомобилей");

                if ($_SESSION['user']->role == 'U') {
                    if ($_SESSION['user']->car_id) {
                        $car = Car::get($_SESSION['user']->car_id);

                        if (!$car['status']) {
                            if ($car['data'] == 'Автомобиль не найден') {
                                $_SESSION['car'] = null;
                            } else {
                                throw new Exception($car['data']);
                            }
                        } else {
                            $_SESSION['car'] = $car['data'];
                        }
                    } else {
                        $_SESSION['car'] = null;
                    }
                } else {
                    $current_lights = TrafficLight::where([
                        'position = ' . $_SESSION['user']->point_id
                    ]);

                    if (!$current_lights['status']) {
                        if ($current_lights['data'] == 'Светофоры не найдены') {
                            $_SESSION['current_lights'] = null;
                        } else {
                            throw new Exception($current_lights['data']);
                        }
                    } else {
                        $_SESSION['current_lights'] = $current_lights['data'];
                    }
                }

                header('location: ../profile', false);
            }
        } catch (Exception $ex) {
            $_SESSION['error'] = $ex->getMessage();
            header('location: ../authorization', false);
        }
    }
    public static function signup()
    {
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
                    'Имя должно состоять из букв латинского или кириллического алфавитов, начинаться с заглавной буквы и иметь длину от 1 до 32 символов',
                    'Фамилия должна состоять из букв латинского или кириллического алфавитов, начинаться с заглавной буквы и иметь длину от 1 до 32 символов'
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
            $user->role = 'U';

            $user->save();

            header('location: ../authorization', false);
        } catch (Exception $ex) {
            $_SESSION['error'] = $ex->getMessage();
            header('location: ../registration', replace: false);
        }
    }
    public static function logout()
    {
        try {
            setcookie('user', '', time() + 86400, '/', '', true);
            unset($_SESSION['user']);
            header('location: ../authorization', false);
        } catch (Exception $ex) {
            $_SESSION['error'] = $ex->getMessage();
            header('location: ../authorization', replace: false);
        }
    }
    public static function car_update()
    {
        try {
            $user = $_SESSION['user'];

            if ($user->car_id) {
                $car = Car::get($user->car_id);

                if (!$car['status']) {
                    if ($car['data'] == 'Автомобиль не найден') {
                        $car = new Car();
                        $car->model = '';
                        $car->class = '';
                        $car->save();

                        $user->car_id = $car->id;
                        $user->save();

                        $_SESSION['user'] = $user;
                        $_SESSION['car'] = $car;
                    } else {
                        throw new Exception($car['data']);
                    }
                } else {
                    $validator = new Validator(
                        [
                            $_POST['class'],
                            $_POST['model'],
                            $_POST['road_id'],
                            $_POST['distance']
                        ],
                        [
                            '/^[A-Z]$/',
                            '/^[A-Za-z\s\d]{1,32}$/',
                            '/^\d+$/',
                            '/^[0](\.\d{1,7})?$|^[1](\.[0]{1,7})?$/'
                        ],
                        [
                            'Класс машины должен состоять из одной заглавной буквы (A-Z).',
                            'Модель машины может содержать только латинские буквы, цифры и пробелы.',
                            'Неверный номер дороги',
                            'Дистанция задана неверно. Дистанция должна быть числом от 0 до 1'
                        ]
                    );

                    if (!$validator->validate())
                        throw new Exception($validator->last_message);

                    $car['data']->model = $_POST['model'];
                    $car['data']->class = $_POST['class'];
                    $car['data']->road_id = $_POST['road_id'];
                    $car['data']->distance = $_POST['distance'];

                    if ($_FILES['car_image']['tmp_name'] != '') {
                        $info = pathinfo($_FILES['car_image']['name']);
                        $ext = $info['extension'];

                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                            $car['data']->image = $_FILES['car_image']['name'];

                            if ($_FILES['car_image']['size'] > 16777215) {
                                throw new Exception('Изображение слишком большое. Размер изображения не должен превышать 16 Мб');
                            }

                            if (!getimagesize($_FILES['car_image']['tmp_name'])) {
                                throw new Exception('Файл повреждён или не является изображением');
                            }

                            if (!is_dir('../uploads')) {
                                throw new Exception('Указанная директория не существует');
                            }

                            if (!is_writable('../uploads')) {
                                throw new Exception('Отсутствуют права доступа к директории');
                            }

                            if (!is_dir('../uploads/cars')) {
                                throw new Exception('Указанная директория не существует');
                            }

                            if (!is_writable('../uploads/cars')) {
                                throw new Exception('Отсутствуют права доступа к директории');
                            }

                            if (!move_uploaded_file($_FILES['car_image']['tmp_name'], '../uploads/cars/' . $_FILES['car_image']['name'])) {
                                throw new Exception('Ошибка загрузки');
                            }
                        } else {
                            throw new Exception('Недопустимый формат файла');
                        }
                    }

                    $status = $car['data']->save()['status'];
                    if ($status) {
                        $_SESSION['car'] = $car['data'];
                        $_SESSION['success'] = 'Автомобиль обновлён';
                    } else {
                        throw new Exception('Не удалось обновить автомобиль');
                    }
                }
            } else {
                $car = new Car();
                $car->model = '';
                $car->class = '';
                $car->distance = 0;
                $car->road_id = null;
                $car->save();

                $user->car_id = $car->id;
                $user->save();

                $_SESSION['user'] = $user;
                $_SESSION['car'] = $car;
            }
        } catch (Exception $ex) {
            $_SESSION['error'] = $ex->getMessage();
        }

        header('location: ../../profile', false);
    }

    public static function avatar_update()
    {
        try {
            $user = $_SESSION['user'];

            if (!isset($_FILES['avatar']) || $_FILES['avatar']['tmp_name'] == '') {
                throw new Exception('Файл не был загружен');
            }

            $file = $_FILES['avatar'];
            $info = pathinfo($file['name']);
            $ext = strtolower($info['extension']);

            $allowed_exts = ['jpg', 'jpeg', 'png'];
            if (!in_array($ext, $allowed_exts)) {
                throw new Exception('Недопустимый формат файла. Разрешены: jpg, jpeg, png');
            }

            if ($file['size'] > 16777215) {
                throw new Exception('Изображение слишком большое. Размер изображения не должен превышать 16 Мб');
            }

            if (!getimagesize($file['tmp_name'])) {
                throw new Exception('Файл повреждён или не является изображением');
            }

            $user->avatar = $ext . ';base64, ' . base64_encode(file_get_contents($file['tmp_name']));

            $result = $user->save();

            if (!$result['status']) {
                throw new Exception('Не удалось обновить аватар пользователя');
            }

            $_SESSION['user'] = $user;
            $_SESSION['success_avatar'] = 'Аватар успешно обновлён';
        } catch (Exception $ex) {
            $_SESSION['error_avatar'] = $ex->getMessage();
        }

        header('location: ../../profile', false);
    }

    public static function map_update()
    {
        try {
            $points = Point::all();
            $roads = Road::all();
            $traffic_lights = TrafficLight::all();
            $params = Param::all();

            if ($points['status'] && $roads['status'] && $traffic_lights['status'] && $params['status']) {
                $_SESSION['points'] = $points['data'];
                $_SESSION['roads'] = $roads['data'];
                $_SESSION['traffic_lights'] = $traffic_lights['data'];
                $_SESSION['params'] = $params['data'];

                $cars = Car::all();

                if ($cars['status']) {
                    $_SESSION['cars'] = $cars['data'];
                } else {
                    throw new Error("Ошибка при получении автомобилей");
                }
            } else {
                throw new Error("Ошибка при получении карты");
            }
        } catch (Exception $ex) {
            $_SESSION['error'] = $ex->getMessage();
        }

        header('location: ../../profile', false);
    }
    public static function tl_update()
    {
        try {
            $traffic_light = TrafficLight::get($_POST['id']);

            if ($traffic_light['status']) {
                $new_traffic_light = new TrafficLight();

                $new_traffic_light->id = $_POST['id'];
                $new_traffic_light->direction = $traffic_light['data']->direction;
                $new_traffic_light->position = $traffic_light['data']->position;
                $new_traffic_light->color = $_POST['color'];

                $new_traffic_light->save();

                $current_lights = TrafficLight::where([
                    'position = ' . $_SESSION['user']->point_id
                ]);

                if (!$current_lights['status']) {
                    if ($current_lights['data'] == 'Светофоры не найдены') {
                        $_SESSION['current_lights'] = null;
                    } else {
                        throw new Exception($current_lights['data']);
                    }
                } else {
                    $_SESSION['current_lights'] = $current_lights['data'];
                }
            }
        } catch (Exception $ex) {
            $_SESSION['error'] = $ex->getMessage();
        }

        header('location: ../../profile', false);
    }
    public static function list()
    {
        $result = User::all();

        if ($result['status']) {
            $_SESSION['users'] = $result['data'];
        } else {
            $_SESSION['error'] = $result['data'];
        }
    }

    public static function theme_update()
    {
        try {            
            $theme = isset($_COOKIE['theme']) ? sodium_crypto_aead_aes256gcm_decrypt($_COOKIE['theme'], 'theme', 'abcdefabcdef', $_SESSION['key']) : 'light';

            if ($theme == 'light') {
                $dark = sodium_crypto_aead_aes256gcm_encrypt('dark', 'theme', 'abcdefabcdef', $_SESSION['key']); 
                setcookie('theme', $dark, time() + 30 * 86400, '/', '', true, true);
            } else {
                $light = sodium_crypto_aead_aes256gcm_encrypt('light', 'theme', 'abcdefabcdef', $_SESSION['key']); 
                setcookie('theme', $light, time() + 30 * 86400, '/',  '', true, true);
            }
        } catch (Exception $ex) {
            $_SESSION['error'] = $ex->getMessage();
        }

        header('location: ../../profile');
    }
}

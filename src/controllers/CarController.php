<?php

session_start();

require_once '../models/Car.php';
require_once '../server/validator.php';

class CarController
{
    public static function add()
    {
        try {
            $validator = new Validator(
                [
                    $_POST['class'],
                    $_POST['model']
                ],
                [
                    '/^[A-Z]$/',
                    '/^[A-Za-z\s]{1,32}$/'
                ],
                [
                    'Класс машины должен состоять из одной заглавной буквы (A-Z).',
                    'Модель машины может содержать только латинские буквы и пробелы.'
                ]
            );

            if (!$validator->validate())
                throw new Exception($validator->last_message);

            $car = new Car();
            $car->id = 0;
            $car->class = $_POST['class'];
            $car->model = $_POST['model'];
            $result = $car->save();

            if (!$result['status'])
                throw new Exception($result['data']);

            $_SESSION['success'] = 'Машина успешно добавлена!';
        } catch (Exception $ex) {
            $_SESSION['error'] = 'Ошибка при добавлении машины: ' . $ex->getMessage();
        }

        header('location: ../car/search', false);
    }
    public static function search()
    {
        try {
            $model = $_POST['model'];

            $validator = new Validator(
                [
                    $model
                ],
                [
                    '/^[A-Za-z\s]{0,}$/'
                ],
                [
                    'Модель машины может содержать только латинские буквы и пробелы.'
                ]
            );

            if (!$validator->validate())
                throw new Exception($validator->last_message);

            $cars = $model != '' ?
                Car::where([
                    'model = \'' . $model . '\''
                ]) :
                Car::all();

            if ($cars['data'] == 'Автомобили не найдены') {
                throw new Exception('Машины с моделью "' . htmlspecialchars($model) . '" не найдены');
            } else if (!$cars['status']) {
                throw new Exception($cars['data']);
            } else {
                $_SESSION['cars'] = $cars['data'];
            }
        } catch (Exception $ex) {
            $_SESSION['error'] = 'Ошибка при поиске машины: ' . $ex->getMessage();
        }

        header('location: ../admin', false);
    }
    public static function delete()
    {
        try {
            if (!isset($_POST['id']))
                throw new Exception('ID для удаления машины не передан');

            $id = (int) $_POST['id'];
            $cars = Car::where([
                'id = ' . $id
            ]);

            if ($cars['data'] == 'Автомобиль не найден') {
                throw new Exception('Машина с ID "' . $id . '" не найдена');
            } else if (!$cars['status']) {
                throw new Exception($cars['data']);
            } else {
                foreach ($cars['data'] as $car) {
                    if (!$car->destroy()) {
                        throw new Exception('Ошибка при удалении машины');
                    }
                }
                $_SESSION['success'] = 'Машина успешно удалена!';
                header('location: ../car/search', false);
                exit;
            }
        } catch (Exception $ex) {
            $_SESSION['error'] = 'Ошибка при удалении машины: ' . $ex->getMessage();
        }

        header('location: ../admin', false);
    }
    public static function list()
    {
        $result = Car::all();

        if ($result['status']) {
            $_SESSION['cars'] = $result['data'];
        } else {
            $_SESSION['error'] = $result['data'];
        }
    }
    public static function stats()
    {
        try {
            $stats_model = Car::group([
                'model, COUNT(*) AS count'
            ], [
                'model'
            ]);

            $stats_class = Car::group([
                'class, COUNT(*) AS count'
            ], [
                'class'
            ]);

            $count = Car::all();

            $sorted_cars = Car::sorted(['movement_count DESC']);

            if ($stats_model['status'] && $stats_class['status'] && $count['status'] && $sorted_cars['status']) {
                $_SESSION['stats_model'] = $stats_model['data'];
                $_SESSION['stats_class'] = $stats_class['data'];
                $_SESSION['count'] = count($count['data']);
                $_SESSION['sorted_cars'] = $sorted_cars['data'];
            } else {
                $_SESSION['error'] = $stats_model['data'];
            }

            header('location: ../admin', false);
        } catch (Exception $ex) {
            $_SESSION['error'] = $ex->getMessage();

            header('location: ../admin', false);
        }
    }
}

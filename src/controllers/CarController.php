<?php

session_start();

require_once '../models/Car.php';

class CarController
{

    public static function add()
    {
        try {
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

        header('location: ../profile', false);
    }

    public static function search()
    {
        try {
            $model = $_POST['model'];

            $cars = $model != '' ?
                Car::where(["model LIKE '%" . $model . "%'"]) :
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

        header('location: ../profile', false);
    }

    public static function delete()
    {
        try {
            $id = $_POST['id'];
            $cars = Car::where(["id = '" . $id . "'"]);

            if ($cars['data'] == 'Автомобиль не найден') {
                throw new Exception('Машина с ID "' . (int)$id . '" не найдена');
            } else if (!$cars['status']) {
                throw new Exception($cars['data']);
            } else {
                foreach ($cars['data'] as $car) {
                    $car->destroy();
                }
                $_SESSION['success'] = 'Машина успешно удалена!';
            }
        } catch (Exception $ex) {
            $_SESSION['error'] = 'Ошибка при удалении машины: ' . $ex->getMessage();
        }
      
        header('location: ../profile', false);
    }

    public static function list() {
        $result = Car::all(); 

        if ($result['status']) {
            $_SESSION['cars'] = $result['data']; 
        } else {
            $_SESSION['error'] = $result['data']; 
        }
    }
}

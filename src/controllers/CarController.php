<?php 

    session_start(); 

    require_once '../models/Car.php';

    Database::connect();

    class CarController {
        
        public static function add() {
            try {
                $car = new Car();
                $car->id = 0;
                $car->class = $_POST['class'];
                $car->model = $_POST['model'];
                $car->save(); 

                $_SESSION['success'] = 'Машина успешно добавлена!';
            } catch (Exception $ex) {
                $_SESSION['error'] = 'Ошибка при добавлении машины: ' . $ex->getMessage();
            }

            header('location: ../profile', false);
        }

        public static function search() {
            try {
                $model = $_POST['model'];
                $cars = Car::where(["model LIKE '%" . $model . "%'"]); 

                if (!$cars) {
                    $_SESSION['error'] = 'Машины с моделью "' . htmlspecialchars($model) . '" не найдены';
                } else {
                    $_SESSION['cars'] = $cars; 
                }
            } catch (Exception $ex) {
                $_SESSION['error'] = 'Ошибка при поиске машины: ' . $ex->getMessage();
            }

            header('location: ../profile', false);
        }

        public static function delete() {
            try {
                $id = $_POST['id'];
                $cars = Car::where(["id = '" . $id . "'"]);

                if (!$cars) {
                    $_SESSION['error'] = 'Машина с ID "' . (int)$id . '" не найдена';
                } else {
                    foreach ($cars as $car) {
                        $car->destroy(); 
                    }
                    $_SESSION['success'] = 'Машина успешно удалена!';
                }
            } catch (Exception $ex) {
                $_SESSION['error'] = 'Ошибка при удалении машины: ' . $ex->getMessage();
            }

            header('location: ../profile', false);
        }

        public static function all() {
            $_SESSION['cars'] = Car::all() ?? [];
            header('Location: index.php');
        }
    }

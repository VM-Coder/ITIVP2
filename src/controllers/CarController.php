<?php 

    session_start(); 

    require_once '../models/Car.php';

    Database::connect();

    switch ($_SERVER['REQUEST_URI']){
        case '/add_car':
            CarController::addCar();
            break;
        case '/search_car':
            CarController::searchCar();
            break;
        case '/delete_car':
            CarController::deleteCar();
            break;
        default:
            CarController::showAll();
            break;
    }

    class CarController {
        
        public static function addCar() {
            try {
                $car = new Car();
                $car->id = 0;
                $car->class = $_POST['car_class'];
                $car->model = $_POST['car_model'];
                $car->save(); 

                $_SESSION['success'] = 'Машина успешно добавлена!';
            } catch (Exception $ex) {
                $_SESSION['error'] = 'Ошибка при добавлении машины: ' . $ex->getMessage();
            }

            header('location: profile', false);
        }

        public static function searchCar() {
            try {
                $model = $_POST['search_model'];
                $cars = Car::where(["model LIKE '%" . $model . "%'"]); 

                if (!$cars) {
                    $_SESSION['error'] = 'Машины с моделью "' . htmlspecialchars($model) . '" не найдены';
                } else {
                    $_SESSION['cars'] = $cars; 
                }
            } catch (Exception $ex) {
                $_SESSION['error'] = 'Ошибка при поиске машины: ' . $ex->getMessage();
            }

            header('location: profile', false);
        }

        public static function deleteCar() {
            try {
                $id = $_POST['delete_id'];
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

            header('location: profile', false);
        }

        public static function showAll() {
            $_SESSION['cars'] = Car::all() ?? [];
            header('Location: index.php');
        }
    }

?>

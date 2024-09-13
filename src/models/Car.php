<?php

    require_once 'Model.php';

    class Car extends Model {
        protected static string $table = 'car';
        public ?int $id = null;
        public string $class;
        public string $model;

        public function __construct() {
        }

        public static function get(int $primary_key) {
            $data = Database::select(
                static::$table,
                ['id = ' . $primary_key]
            );

            if ($data->num_rows > 0) {
                $data = $data->fetch_all()[0];

                $car = new Car();
                $car->id = $data['id'];
                $car->class = $data['class'];
                $car->model = $data['model'];

                return $car;
            }

            return null;
        }

        public static function where(array $conditions) {
            $data = Database::select(
                static::$table,
                $conditions
            );

            if ($data->num_rows > 0) {
                $data = $data->fetch_all(MYSQLI_ASSOC);

                $cars = [];
                foreach ($data as $row) {
                    $car = new Car();
                    $car->id = $row['id'];
                    $car->class = $row['class'];
                    $car->model = $row['model'];

                    array_push($cars, $car);
                }

                return $cars;
            }

            return null;
        }

        public static function all() {
            $data = Database::select(
                static::$table
            );

            if ($data->num_rows > 0) {
                $data = $data->fetch_all(MYSQLI_ASSOC);

                $cars = [];
                foreach ($data as $row) {
                    $car = new Car();
                    $car->id = $row['id'];
                    $car->class = $row['class'];
                    $car->model = $row['model'];

                    array_push($cars, $car);
                }

                return $cars;
            }

            return null;
        }

        public function save() {
            $values = [
                'class' => '\'' . $this->class . '\'',
                'model' => '\'' . $this->model . '\''
            ];

            if (static::get($this->id) == null) {
                Database::insert(
                    static::$table,
                    $values
                );
            } else {
                Database::update(
                    static::$table,
                    $values,
                    ['id = ' . $this->id]
                );
            }
        }

        public function destroy() {
            Database::delete(
                static::$table,
                ['id = ' . $this->id]
            );
        }
    }

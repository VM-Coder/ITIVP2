<?php

require_once 'Model.php';

class Car extends Model
{
    protected static string $table = 'car';
    public int $id = 0;
    public string $class;
    public string $model;
    public ?int $road_id;
    public float $distance;
    public ?string $image = null;
    public function __construct() {}
    public static function get(int $primary_key)
    {
        $data = Database::select(
            static::$table,
            [
                'id = ' . $primary_key
            ],
            [
                'id',
                'class',
                'model',
                'road_id',
                'distance',
                'image'
            ]
        );

        if (gettype($data) == 'string')
            return [
                'data' => $data,
                'status' => false
            ];

        if ($data->num_rows > 0) {
            $data = $data->fetch_all(MYSQLI_ASSOC)[0];

            $car = new Car();
            $car->id = $data['id'];
            $car->class = $data['class'];
            $car->model = $data['model'];

            $car->road_id = $data['road_id'];
            $car->distance = $data['distance'];

            $car->image = $data['image'];

            return [
                'data' => $car,
                'status' => true
            ];
        }

        return [
            'data' => 'Автомобиль не найден',
            'status' => false
        ];
    }
    public static function sorted(array $sort_fields)
    {
        $data = Database::select(
            static::$table,
            null,
            [
                'id',
                'class',
                'model',
                'distance'
            ],
            null,
            null,
            $sort_fields
        );

        if (gettype($data) == 'string') {
            return [
                'data' => $data,
                'status' => false
            ];
        }

        if ($data->num_rows > 0) {
            $cars = [];
            foreach ($data->fetch_all(MYSQLI_ASSOC) as $row) {
                $car = new Car();
                $car->id = $row['id'];
                $car->class = $row['class'];
                $car->model = $row['model'];
                $car->distance = $row['distance'];

                $cars[] = $car;
            }

            return [
                'data' => $cars,
                'status' => true
            ];
        }

        return [
            'data' => 'Автомобили не найдены',
            'status' => false
        ];
    }
    public static function where(array $conditions)
    {
        $data = Database::select(
            static::$table,
            $conditions,
            [
                'id',
                'class',
                'model',
                'road_id',
                'distance',
                'image'
            ]
        );

        if (gettype($data) == 'string')
            return [
                'data' => $data,
                'status' => false
            ];

        if ($data->num_rows > 0) {
            $data = $data->fetch_all(MYSQLI_ASSOC);

            $cars = [];
            foreach ($data as $row) {
                $car = new Car();
                $car->id = $row['id'];
                $car->class = $row['class'];
                $car->model = $row['model'];

                $car->road_id = $data['road_id'];
                $car->distance = $data['distance'];

                $car->image = $data['image'];

                array_push($cars, $car);
            }

            return [
                'data' => $cars,
                'status' => true
            ];
        }

        return [
            'data' => 'Автомобили не найдены',
            'status' => false
        ];
    }
    public static function all()
    {
        $data = Database::select(
            static::$table,
            null,
            [
                'id',
                'class',
                'model',
                'road_id',
                'distance',
                'image'
            ]
        );

        if (gettype($data) == 'string')
            return [
                'data' => $data,
                'status' => false
            ];

        if ($data->num_rows > 0) {
            $data = $data->fetch_all(MYSQLI_ASSOC);

            $cars = [];
            foreach ($data as $row) {
                $car = new Car();
                $car->id = $row['id'];
                $car->class = $row['class'];
                $car->model = $row['model'];

                $car->road_id = $row['road_id'];
                $car->distance = $row['distance'];

                $car->image = $row['image'];

                array_push($cars, $car);
            }

            return [
                'data' => $cars,
                'status' => true
            ];
        }

        return [
            'data' => 'Автомобили не найдены',
            'status' => false
        ];
    }
    public static function group($fields, $group_fields)
    {
        $data = Database::select(
            static::$table,
            null,
            $fields,
            null,
            $group_fields
        );

        if (gettype($data) == 'string')
            return [
                'data' => $data,
                'status' => false
            ];

        if ($data->num_rows > 0) {
            $data = $data->fetch_all(MYSQLI_ASSOC);

            return [
                'data' => $data,
                'status' => true
            ];
        }

        return [
            'data' => 'Автомобили не найдены',
            'status' => false
        ];
    }
    public function save()
    {
        $values = [
            'class' => '\'' . $this->class . '\'',
            'model' => '\'' . $this->model . '\'',
            'road_id' => $this->road_id ?? 'NULL',
            'distance' => $this->distance,
            'image' => ($this->image ? '\'' . $this->image . '\'' : 'NULL')
        ];

        if (static::get($this->id)['data'] == 'Автомобиль не найден') {
            $result = Database::insert(
                static::$table,
                $values
            );

            if (gettype(value: $result) == 'string')
                return [
                    'data' => $result,
                    'status' => false
                ];

            $this->id = $result;

            return [
                'data' => 'Автомобиль создан',
                'status' => true
            ];
        } else {
            $result = Database::update(
                static::$table,
                $values,
                [
                    'id = ' . $this->id
                ]
            );

            if (gettype(value: $result) == 'string')
                return [
                    'data' => $result,
                    'status' => false
                ];

            return [
                'data' => 'Автомобиль обновлён',
                'status' => true
            ];
        }
    }
    public function destroy()
    {
        $result = Database::delete(
            static::$table,
            [
                'id = ' . $this->id
            ]
        );

        if (gettype(value: $result) == 'string')
            return [
                'data' => $result,
                'status' => false
            ];

        return [
            'data' => 'Автомобиль удалён',
            'status' => true
        ];
    }
}

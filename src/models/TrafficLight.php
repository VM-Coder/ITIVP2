<?php

require_once 'Model.php';

class TrafficLight extends Model
{
    protected static string $table = 'traffic_light';
    public int $id = 0;
    public int $position;
    public int $direction;
    public string $color;

    public function __construct() {}

    public static function get(int $primary_key)
    {
        $data = Database::select(
            static::$table,
            [
                'id = ' . $primary_key
            ]
        );

        if (gettype($data) == 'string')
            return [
                'data' => $data,
                'status' => false
            ];

        if ($data->num_rows > 0) {
            $data = $data->fetch_all(MYSQLI_ASSOC)[0];

            $traffic_light = new TrafficLight();

            $traffic_light->id = $data['id'];
            $traffic_light->position = $data['position'];
            $traffic_light->direction = $data['direction'];
            $traffic_light->color = $data['color'];

            return [
                'data' => $traffic_light,
                'status' => true
            ];
        }

        return [
            'data' => 'Светофор не найден',
            'status' => false
        ];
    }

    public static function where(array $conditions)
    {
        $data = Database::select(
            static::$table,
            $conditions
        );

        if (gettype($data) == 'string')
            return [
                'data' => $data,
                'status' => false
            ];

        if ($data->num_rows > 0) {
            $data = $data->fetch_all(MYSQLI_ASSOC);

            $traffic_lights = [];
            foreach ($data as $row) {
                $traffic_light = new TrafficLight();
                $traffic_light->id = $row['id'];
                $traffic_light->position = $row['position'];
                $traffic_light->direction = $row['direction'];
                $traffic_light->color = $row['color'];

                array_push($traffic_lights, $traffic_light);
            }

            return [
                'data' => $traffic_lights,
                'status' => true
            ];
        }

        return [
            'data' => 'Светофоры не найдены',
            'status' => false
        ];
    }
    public static function all()
    {
        $data = Database::select(
            static::$table
        );

        if (gettype($data) == 'string')
            return [
                'data' => $data,
                'status' => false
            ];

        if ($data->num_rows > 0) {
            $data = $data->fetch_all(MYSQLI_ASSOC);

            $traffic_lights = [];
            foreach ($data as $row) {
                $traffic_light = new TrafficLight();
                $traffic_light->id = $row['id'];
                $traffic_light->position = $row['position'];
                $traffic_light->direction = $row['direction'];
                $traffic_light->color = $row['color'];

                array_push($traffic_lights, $traffic_light);
            }

            return [
                'data' => $traffic_lights,
                'status' => true
            ];
        }

        return [
            'data' => 'Светофоры не найдены',
            'status' => false
        ];
    }

    public function save()
    {
        if (static::get($this->id)['data'] == 'Светофор не найден') {
            $result = Database::insert(
                static::$table,
                [
                    'position' => '\'' . $this->position . '\'',
                    'direction' => '\'' . $this->direction . '\'',
                    'color' => '\'' . $this->color . '\''
                ]
            );

            if (gettype(value: $result) == 'string')
                return [
                    'data' => $result,
                    'status' => false
                ];

            $this->id = $result;

            return [
                'data' => 'Светофор создан',
                'status' => true
            ];
        } else {
            $result = Database::update(
                static::$table,
                [
                    'position' => '\'' . $this->position . '\'',
                    'direction' => '\'' . $this->direction . '\'',
                    'color' => '\'' . $this->color . '\''
                ],
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
                'data' => 'Светофор обновлён',
                'status' => true
            ];
        }
    }
}

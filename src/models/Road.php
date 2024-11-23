<?php

require_once 'Model.php';

class Road extends Model
{
    protected static string $table = 'road';
    public int $id = 0;
    public int $start_point;
    public int $end_point;
    public float $coefficient = 0.0;

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
                'start_point',
                'end_point',
                'coefficient'
            ]
        );

        if (gettype($data) == 'string')
            return [
                'data' => $data,
                'status' => false
            ];

        if ($data->num_rows > 0) {
            $data = $data->fetch_all(MYSQLI_ASSOC)[0];

            $road = new Road();
            $road->id = $data['id'];
            $road->start_point = $data['start_point'];
            $road->end_point = $data['end_pont'];
            $road->coefficient = $data['coefficient'];

            return [
                'data' => $road,
                'status' => true
            ];
        }

        return [
            'data' => 'Дорога не найдена',
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

            $roads = [];
            foreach ($data as $row) {
                $road = new Road();
                $road->id = $row['id'];
                $road->start_point = $row['start_point'];
                $road->end_point = $row['end_point'];
                $road->coefficient = $row['coefficient'];

                array_push($roads, $road);
            }

            return [
                'data' => $roads,
                'status' => true
            ];
        }

        return [
            'data' => 'Дороги не найдены',
            'status' => false
        ];
    }

    public static function allCoefOrder()
    {
        $data = Database::select(
            static::$table,
            null,
            [
                'id',
                'start_point',
                'end_point',
                'coefficient'
            ],
            null,
            null,
            ['coefficient ASC']
        );

        if (gettype($data) == 'string')
            return [
                'data' => $data,
                'status' => false
            ];

        if ($data->num_rows > 0) {
            $data = $data->fetch_all(MYSQLI_ASSOC);

            $sorted_roads = [];
            foreach ($data as $row) {
                $road = new Road();
                $road->id = $row['id'];
                $road->start_point = $row['start_point'];
                $road->end_point = $row['end_point'];
                $road->coefficient = $row['coefficient'];

                array_push($sorted_roads, $road);
            }

            return [
                'data' => $sorted_roads,
                'status' => true
            ];
        }

        return [
            'data' => 'Дороги не найдены',
            'status' => false
        ];
    }

    public static function updateCoefficient(int $roadId, float $newCoefficient)
    {
        $result = Database::update(
            'road',
            ['coefficient' => $newCoefficient],
            ['id = ' . $roadId]
        );

        if (gettype($result) == 'string') {
            return [
                'data' => $result,
                'status' => false
            ];
        }

        return [
            'data' => 'Дорога обновлена',
            'status' => true
        ];
    }
}

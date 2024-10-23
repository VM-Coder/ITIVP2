<?php

require_once 'Model.php';

class Point extends Model
{
    protected static string $table = 'point';
    public int $id = 0;
    public int $x;
    public int $y;

    public function __construct() {}

    public static function all()
    {
        $data = Database::select(
            static::$table,
            null,
            [
                'id',
                'ST_X(coords) AS x',
                'ST_Y(coords) AS y'
            ]
        );

        if (gettype($data) == 'string')
            return [
                'data' => $data,
                'status' => false
            ];

        if ($data->num_rows > 0) {
            $data = $data->fetch_all(MYSQLI_ASSOC);

            $points = [];
            foreach ($data as $row) {
                $point = new Point();
                $point->id = $row['id'];
                $point->x = $row['x'];
                $point->y = $row['y'];

                array_push($points, $point);
            }

            return [
                'data' => $points,
                'status' => true
            ];
        }

        return [
            'data' => 'Пункты не найдены',
            'status' => false
        ];
    }
}

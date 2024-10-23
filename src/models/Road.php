<?php

require_once 'Model.php';

class Road extends Model
{
    protected static string $table = 'road';
    public int $id = 0;
    public int $start_point;
    public int $end_point;

    public function __construct() {}

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
}

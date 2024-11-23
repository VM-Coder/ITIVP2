<?php

require_once 'Model.php';

class Param extends Model
{
    protected static string $table = 'params';
    public int $id;
    public string $param;
    public float $value;

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

            $param = new Param();

            $param->id = $data['id'];
            $param->param = $data['param'];
            $param->value = $data['value'];

            return [
                'data' => $param,
                'status' => true
            ];
        }

        return [
            'data' => 'Параметр не найден',
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

            $params = [];
            foreach ($data as $row) {
                $param = new Param();
                $param->id = $row['id'];
                $param->param = $row['param'];
                $param->value = $row['value'];

                array_push($params, $param);
            }

            return [
                'data' => $params,
                'status' => true
            ];
        }

        return [
            'data' => 'Параметры не найдены',
            'status' => false
        ];
    }
    public function save()
    {
        if (static::get($this->id)['data'] == 'Параметр не найден') {
            $result = Database::insert(
                static::$table,
                [
                    'param' => '\'' . $this->param . '\'',
                    'value' => $this->value
                ]
            );

            if (gettype(value: $result) == 'string')
                return [
                    'data' => $result,
                    'status' => false
                ];

            $this->id = $result;

            return [
                'data' => 'Параметр создан',
                'status' => true
            ];
        } else {
            $result = Database::update(
                static::$table,
                [
                    'param' => '\'' . $this->param . '\'',
                    'value' => $this->value
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
                'data' => 'Параметр обновлён',
                'status' => true
            ];
        }
    }
}

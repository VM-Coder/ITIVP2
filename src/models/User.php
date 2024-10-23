<?php

require_once 'Model.php';

class User extends Model
{
    protected static string $table = 'user';
    public int $id = 0;
    public string $email;
    public string $password = '';
    public string $firstname = '';
    public string $lastname;
    public string $role = 'U';
    public ?int $car_id = null;
    public ?int $point_id = null;
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

            $user = new User();

            $user->id = $data['id'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->firstname = $data['firstname'];
            $user->lastname = $data['lastname'];
            $user->role = $data['role'];
            $user->car_id = $data['car_id'];
            $user->point_id = $data['point_id'];

            return [
                'data' => $user,
                'status' => true
            ];
        }

        return [
            'data' => 'Пользователь не найден',
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

            $users = [];

            foreach ($data as $row) {
                $user = new User();

                $user->id = $row['id'];
                $user->email = $row['email'];
                $user->password = $row['password'];
                $user->firstname = $row['firstname'];
                $user->lastname = $row['lastname'];
                $user->role = $row['role'];
                $user->car_id = $row['car_id'];
                $user->point_id = $row['point_id'];

                array_push($users, $user);
            }

            return [
                'data' => $users,
                'status' => true
            ];
        }

        return [
            'data' => 'Пользователи не найдены',
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

            $users = [];

            foreach ($data as $row) {
                $user = new User();

                $user->id = $row['id'];
                $user->email = $row['email'];
                $user->password = $row['password'];
                $user->firstname = $row['firstname'];
                $user->lastname = $row['lastname'];
                $user->role = $row['is_admin'];
                $user->car_id = $row['car_id'];
                $user->point_id = $row['point_id'];

                array_push($users, $user);
            }

            return [
                'data' => $users,
                'status' => true
            ];
        }

        return [
            'data' => 'Пользователи не найдены',
            'status' => false
        ];
    }
    public function save()
    {
        if (static::get($this->id)['data'] == 'Пользователь не найден') {
            $result = Database::insert(
                static::$table,
                [
                    'email' => '\'' . $this->email . '\'',
                    'password' => '\'' . sha1($this->password) . '\'',
                    'firstname' => '\'' . $this->firstname . '\'',
                    'lastname' => '\'' . $this->lastname . '\'',
                    'role' => '\'' . $this->role . '\''
                ]
            );

            if (gettype(value: $result) == 'string')
                return [
                    'data' => $result,
                    'status' => false
                ];

            $this->id = $result;

            return [
                'data' => 'Пользователь создан',
                'status' => true
            ];
        } else {
            $result = Database::update(
                static::$table,
                [
                    'email' => '\'' . $this->email . '\'',
                    'password' => '\'' . $this->password . '\'',
                    'firstname' => '\'' . $this->firstname . '\'',
                    'lastname' => '\'' . $this->lastname . '\'',
                    'role' => '\'' . $this->role . '\'',
                    'car_id' => $this->car_id,
                    'point_id' => $this->point_id
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
                'data' => 'Пользователь обновлён',
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
            'data' => 'Пользователь удалён',
            'status' => true
        ];
    }
}

<?php

    require_once 'Model.php';

    class User extends Model {
        protected static string $table = 'user';
        public int $id;
        public string $email;
        public string $password = '';
        public string $firstname = '';
        public string $lastname;
        public bool $is_admin;
        public int $car_id;
        public function __construct(){

        }
        public static function get(int $primary_key){
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

            if ($data->num_rows > 0){
                $data = $data->fetch_all()[0];
                
                $user = new User();

                $user->id = $data['id'];
                $user->email = $data['email'];
                $user->password = $data['password'];
                $user->firstname = $data['firstname'];
                $user->lastname = $data['lastname'];
                $user->is_admin = $data['admin'];

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
        public static function where(array $conditions){
            $data = Database::select(
                static::$table,
                $conditions
            );

            if (gettype($data) == 'string')
                return [
                    'data' => $data,
                    'status' => false
                ];

            if ($data->num_rows > 0){
                $data = $data->fetch_all(MYSQLI_ASSOC);

                $users = [];

                foreach ($data as $row) {
                    $user = new User();

                    $user->id = $row['id'];
                    $user->email = $row['email'];
                    $user->password = $row['password'];
                    $user->firstname = $row['firstname'];
                    $user->lastname = $row['lastname'];
                    $user->is_admin = $row['is_admin'];

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
        public static function all(){
            $data = Database::select(
                static::$table
            );

            if (gettype($data) == 'string')
                return [
                    'data' => $data,
                    'status' => false
                ];

            if ($data->num_rows > 0){
                $data = $data->fetch_all(MYSQLI_ASSOC);

                $users = [];

                foreach ($data as $row) {
                    $user = new User();

                    $user->id = $row['id'];
                    $user->email = $row['email'];
                    $user->password = $row['password'];
                    $user->firstname = $row['firstname'];
                    $user->lastname = $row['lastname'];
                    $user->is_admin = $row['is_admin'];

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
        public function save() {
            $values = [
                'email' => '\'' . $this->email . '\'', 
                'password' => '\'' . sha1($this->password) . '\'', 
                'firstname' => '\'' . $this->firstname . '\'', 
                'lastname' => '\'' . $this->lastname . '\'', 
                'is_admin' => $this->is_admin ? 1 : 0
            ];

            if (static::get($this->id)['data'] == 'Пользователь не найден'){
                $result = Database::insert(
                    static::$table,
                    $values
                );

                if (gettype(value: $result) == 'string')
                    return [
                        'data' => $result,
                        'status' => false
                    ];

                return [
                    'data' => 'Пользователь создан',
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
                    'data' => 'Пользователь обновлён',
                    'status' => true
                ];
            }
        }
        public function destroy() {
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

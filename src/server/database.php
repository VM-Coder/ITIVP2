<?php

    class Database {
        static string $username = "root";
        static string $hostname = "127.127.126.26";
        static string $password = "";
        static string $database = "traffic_control";
        static int $port = 3306;
        static bool|mysqli $connection;
        public static function connect(): bool{
            static::$connection = mysqli_connect(
                static::$hostname, 
                static::$username, 
                static::$password, 
                static::$database, 
                static::$port
            );

            if (static::$connection)
                return true;

            return false;
        }
        /**
         * @param string $table - таблица из которой выбираются элементы
         * @param array|null $where - массив условий, которые объединяются оператором AND. Пример: ['id > 5', 'car_id BETWEEN 5 AND 10'] == '...WHERE id > 5 AND car_id BETWEEN 5 AND 10'
         * @param array|null $joins - массив join'ов, указанных в виде массивов вида ['table1', 'field1', 'table2', field2']. Пример: [['user', 'car_id', 'car', 'id']] == '...FROM user INNER JOIN car ON user.car_id = car.id
         * @param array|null $group_by - массив полей по которым ведётся группировка. Пример ['model', 'class'] == '...GROUP BY model, class'
         * @param array|null $order_by - массив полей по которым сортируется результирующий набор. Направление сортировки указывается вместе с полем. Пример ['id', 'firstname DESC'] == '...ORDER BY id, firstname DESC'
         */
        public static function select(string $table, array $where = null, array $joins = null, array $group_by = null, array $order_by = null){
            $sql = 'SELECT * FROM ' . $table;
            
            $join = '';

            if ($joins != null){
                foreach ($joins as $j) {
                    $join = 'INNER JOIN ' . $j[2] . ' ON ' . $j[0] . '.' . $j[1] . ' = ' . $j[2] . '.' . $j[3];
                }                
            }

            $conditions = '';

            if ($where != null)
                $conditions = 'WHERE ' . implode(' AND ', $where);

            $group = '';

            if ($group_by != null)
                $group = 'GROUP BY ' . implode(', ', $group_by);

            $order = '';

            if ($order_by != null)
                $order = 'ORDER BY ' . implode(', ', $order_by);

            $sql = $sql . ' ' . $join . ' ' . $conditions . ' ' . $group . ' ' . $order;

            return static::$connection->query($sql);
        }
        /**
         * @param string $table - таблица в которую вставляются значения
         * @param array $values - ассоциативный массив, где ключи являются полями таблицы, а значения - значениями полей вставляемой записи
         */
        public static function insert(string $table, array $values){
            $sql = 'INSERT INTO ' . $table;

            $keys = array_keys($values);

            $fields = '(' . implode(', ', $keys) . ')';

            $vals = array_values($values);

            $record = '(' . implode(', ', $vals) . ')';

            $sql = $sql . ' ' . $fields . ' VALUES ' . $record; 

            return static::$connection->query($sql);
        }
        /**
         * @param string $table - таблица для которой выполняется обновление
         * @param array $values - ассоциативный массив, где ключи являются полями таблицы, а значения - новыми значениями полей
         * @param array|null $where - массив условий аналогично методу select
         */
        public static function update(string $table, array $values, array $where = null){
            $sql = 'UPDATE ' . $table;

            $pairs = [];

            foreach ($values as $key => $value) {
                array_push($pairs, $key . ' = ' . $value);
            }

            $fields = implode(', ', $pairs);

            $conditions = '';

            if ($where != null)
                $conditions = 'WHERE ' . implode(' AND ', $where);

            $sql = $sql . ' SET ' . $fields . ' ' . $conditions; 
            
            return static::$connection->query($sql);
        }
        /**
         * @param string $table - таблица, из которой удаляются значения
         * @param array $where - массив условий аналогично методу select. Наличие $where обязательно!
         */
        public static function delete(string $table, array $where){
            $sql = 'DELETE FROM ' . $table; 

            $conditions = 'WHERE ' . implode(' AND ', $where);

            $sql = $sql . ' ' . $conditions; 

            return static::$connection->query($sql);
        }
    }
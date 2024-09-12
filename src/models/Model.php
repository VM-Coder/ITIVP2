<?php

    require_once '../server/database.php';

    abstract class Model {
        protected static string $table;
        public static function get(int $primary_key){ //единственная запись по первичному ключу

        }
        public static function where(array $conditions){ //фильтрация записей по условиям
            
        }
        public static function all(){ //все записи

        }
        public function save() { //создание или изменение записи

        }
        public function destroy() { //удаление записи

        }
    }
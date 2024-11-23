<?php

session_start();

require_once '../models/Road.php';
require_once '../models/TrafficLight.php';
require_once '../models/Car.php';
require_once '../models/Point.php';
require_once '../models/Param.php';

class ParamController
{
    public static function list()
    {
        $result = Param::all();

        if ($result['status']) {
            $_SESSION['params'] = $result['data'];
        } else {
            $_SESSION['error'] = $result['data'];
        }
    }
    public static function map_update()
    {
        try {
            $params = Param::all();

            if ($params['status']) {
                foreach ($params['data'] as $param) {
                    $param->value = $_POST[$param->param];
                    $param->save();
                }
                $_SESSION['params'] = $params['data'];
            } else {
                $_SESSION['error'] = $params['data'];
            }
        } catch (Exception $ex) {
            $_SESSION['error'] = $ex->getMessage();
        }

        header('location: ../../admin', false);
    }
}

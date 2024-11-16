<?php

session_start();

require_once '../models/Road.php';
require_once '../models/TrafficLight.php';
require_once '../models/Car.php';
require_once '../models/Point.php';

class RoadController
{
    public static function list()
    {
        $result = Road::all();

        if ($result['status']) {
            $_SESSION['roads'] = $result['data'];
        } else {
            $_SESSION['error'] = $result['data'];
        }
    }
}

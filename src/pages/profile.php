<?php 

    require_once '../models/User.php';

    session_start();

    require_once 'template.php';

    head(title: "Профиль");
    body_top();

    $user = $_SESSION['user'];

    echo $user->firstname;
    echo '<br>';
    echo $user->lastname;
 
    body_bottom();

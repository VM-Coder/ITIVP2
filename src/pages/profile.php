<?php 

    require_once '../models/User.php';
    require_once '../models/Car.php';

    session_start();

    require_once 'template.php';

    head(title: "Профиль");
    body_top();

    $user = $_SESSION['user'];

    echo '<br>';
    echo '<div style="text-align: center;">';
    echo $user->firstname;
    echo '<br>';
    echo $user->lastname;
    echo '</div>';    
    

    include( '../ui/forms/profile.php');
 
    body_bottom();

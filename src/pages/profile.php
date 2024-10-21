<?php

require_once '../models/User.php';
require_once '../models/Car.php';

session_start();

if (!isset($_SESSION['user'])) {
    header('location: ../authorization', replace: false);
    $_SESSION['error'] = 'Вы не авторизованы';
    exit;
}

require_once 'template.php';

head(title: "Профиль");
body_top();

$user = $_SESSION['user'];

$input_style = "p-4 h-12 bg-slate-100 border-slate-100 focus:border-indigo-500 focus:bg-white outline-none border-2 rounded-lg";
$button_style = "p-2 h-12 text-white hover:bg-gradient-to-r hover:from-sky-400 hover:to-indigo-400 bg-gradient-to-r from-sky-500 to-indigo-500 rounded-lg";

$form_action = ''
?>

<?php if ($user->is_admin): ?>


<?php else: ?>

    <? include '../ui/layout/header.php' ?>

    <br>
    <br>
    <br>
    <? include '../ui/forms/car_user.php' ?>

<?php endif; ?>

<?php

body_bottom();

unset($_SESSION['error']);
unset($_SESSION['success']);

?>
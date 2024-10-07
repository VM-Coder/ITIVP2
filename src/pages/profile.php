<?php

require_once '../models/User.php';
require_once '../models/Car.php';

session_start();

require_once 'template.php';

head(title: "Профиль");
body_top();

$user = $_SESSION['user'];

$input_style = "p-4 h-12 bg-slate-100 border-slate-100 focus:border-indigo-500 focus:bg-white outline-none border-2 rounded-lg";
$button_style = "p-2 h-12 text-white hover:bg-gradient-to-r hover:from-sky-400 hover:to-indigo-400 bg-gradient-to-r from-sky-500 to-indigo-500 rounded-lg";
?>

<?php if ($user->is_admin) : ?>
    <div class="flex flex-col p-12 box-content inset-0 w-3/4 h-fit m-auto gap-4 rounded-lg shadow-lg bg-white">
        <h1 class="font-bold text-slate-700 text-xl text-center">Взаимодействие с таблицей</h1>
        <hr class="border-slate-700 border-[1px]">

        <div class="flex flex-row justify-between gap-8 bg-white">
            <?php
            include '../ui/forms/car_add.php';
            include '../ui/forms/car_search.php';
            include '../ui/forms/car_delete.php';
            ?>
        </div>

        <p class="text-center text-red-600"><?= !isset($_SESSION['error']) ? '' : $_SESSION['error'] ?></p>
        <p class="text-center text-green-600"><?= !isset($_SESSION['success']) ? '' : $_SESSION['success'] ?></p>

        <a href="../authorization" class="text-center hover:underline hover:cursor-pointer text-indigo-500">Выйти</a>
    </div>

    <div class="mt-12">
        <h1 class="font-bold text-slate-700 text-xl text-center">Таблица машин</h1>

        <? include '../ui/table/table_cars.php' ?>
    </div>
<?php else : ?>

    <br>
    <div style="text-align: center;">
        <?= $user->firstname ?>;
        <br>
        <?= $user->lastname ?>;
    </div>

    <? include '../ui/forms/car_user.php' ?>

<?php endif; ?>

<?php

body_bottom();

unset($_SESSION['error']);
unset($_SESSION['success']);

?>
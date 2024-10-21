<?php

require_once '../models/User.php';
require_once '../models/Car.php';

session_start();

if (!isset($_SESSION['user'])) {
    header('location: ../authorization', replace: false);
    $_SESSION['error'] = 'Вы не авторизованы';
    exit;
}

if (!$_SESSION['user']->is_admin) {
    header('location: ../authorization', replace: false);
    $_SESSION['error'] = 'Вы не являетесь администратором';
    exit;
}

require_once 'template.php';

head(title: "Администратор");
body_top();

?>

<script>
    const showBlock = (block) => {
        Array.from(document.querySelectorAll('.content-block')).forEach((elem) => {
            elem.style.display = 'none';
        })

        document.querySelector(block).style.display = 'block';
    }
</script>

<div class="fixed flex h-screen w-screen">
    <aside class="w-80 h-full bg-gray-600">
        <details class="open:bg-gray-500">
            <summary class="p-4 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300">Таблицы</summary>
            <ul>
                <li><button class="p-4 pl-8 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300" onclick="showBlock('#table_cars')">Автомобили</button></li>
                <li><button class="p-4 pl-8 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300" onclick="showBlock('#table_users')">Пользователи</button></li>
            </ul>
        </details>
        <details class="open:bg-gray-500">
            <summary class="p-4 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300">Статистика</summary>
            <ul>
                <li><button class="p-4 pl-8 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300" onclick="showBlock('#stats_cars')">Автомобили</button></li>
            </ul>
        </details>
        <a class="p-4 pl-8 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300"></a>
    </aside>

    <div class="rounded-2xl grow bg-white m-4 overflow-y-scroll">
        <section id="table_cars" class="content-block hidden">
            <div class="flex flex-col p-12 box-content inset-0 w-3/4 h-fit m-auto gap-4 rounded-lg shadow-lg bg-white">
                <h1 class="font-bold text-slate-700 text-xl text-center">Взаимодействие с таблицей машин</h1>

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
            </div>

            <div class="mt-12">
                <div class="mt-8">
                    <h2 class="font-bold text-slate-700 text-xl text-center">Таблица машин</h2>
                    <?php include '../ui/table/table_cars.php'; ?>
                </div>
            </div>
        </section>

        <section id="table_users" class="content-block hidden">
            <div class="mt-12">
                <div class="mt-8">
                    <h2 class="font-bold text-slate-700 text-xl text-center">Таблица пользователей</h2>
                    <?php include '../ui/table/table_users.php'; ?>
                </div>
            </div>
        </section>

        <section id="stats_cars" class="content-block hidden">
            <?php if (isset($_SESSION['stats_model'])): ?>
                <table class="w-full mt-4 table-auto border-collapse">
                    <tr class="bg-slate-300">
                        <th class="border border-slate-300 px-4 py-2">Модель</th>
                        <th class="border border-slate-300 px-4 py-2">%</th>
                    </tr>
                    <?php
                    foreach ($_SESSION['stats_model'] as $val) {
                        echo '<tr class="bg-white">';
                        echo '<td class="border border-slate-300 px-4 py-2">' . $val['model'] . '</td>';
                        echo '<td class="border border-slate-300 px-4 py-2">' . (100 * (int)$val['count'] / $_SESSION['count']) . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            <?php endif; ?>

            <?php if (isset($_SESSION['stats_class'])): ?>
                <table class="w-full mt-4 table-auto border-collapse">
                    <tr class="bg-slate-300">
                        <th class="border border-slate-300 px-4 py-2">Класс</th>
                        <th class="border border-slate-300 px-4 py-2">%</th>
                    </tr>
                    <?php
                    foreach ($_SESSION['stats_class'] as $val) {
                        echo '<tr class="bg-white">';
                        echo '<td class="border border-slate-300 px-4 py-2">' . $val['class'] . '</td>';
                        echo '<td class="border border-slate-300 px-4 py-2">' . (100 * (int)$val['count'] / $_SESSION['count']) . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php

body_bottom();

unset($_SESSION['error']);
unset($_SESSION['success']);

?>
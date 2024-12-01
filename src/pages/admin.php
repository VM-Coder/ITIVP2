<?php

require_once '../models/User.php';
require_once '../models/Car.php';
require_once '../models/Road.php';
require_once '../models/Param.php';

session_start();

if (!isset($_SESSION['user'])) {
    header('location: ../authorization', replace: false);
    $_SESSION['error'] = 'Вы не авторизованы';
    exit;
}

if ($_SESSION['user']->role != 'A') {
    header('location: ../authorization', replace: false);
    $_SESSION['error'] = 'Вы не являетесь администратором';
    exit;
}

require_once 'template.php';

head(title: "Администратор");
body_top();

$button_style = "p-2 h-12 text-white hover:bg-gradient-to-r hover:from-sky-400 hover:to-indigo-400 bg-gradient-to-r from-sky-500 to-indigo-500 rounded-lg";

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
                <li><button class="p-2 pl-8 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300"
                        onclick="showBlock('#table_cars')">Автомобили</button></li>
                <li><button class="p-2 pl-8 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300"
                        onclick="showBlock('#table_roads')">Дороги</button></li>
                <li><button class="p-2 pl-8 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300"
                        onclick="showBlock('#table_users')">Пользователи</button></li>
            </ul>
        </details>
        <details class="open:bg-gray-500">
            <summary class="p-4 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300">Статистика</summary>
            <ul>
                <li><button class="p-2 pl-8 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300"
                        onclick="showBlock('#stats_cars')">Автомобили</button></li>
            </ul>
        </details>
        <details class="open:bg-gray-500">
            <summary class="p-4 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300">Параметры</summary>
            <ul>
                <li><button class="p-2 pl-8 text-xl text-gray-200 hover:cursor-pointer hover:text-gray-300"
                        onclick="showBlock('#settings_map')">Карта дорог</button></li>
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
                <p class="text-center text-green-600"><?= !isset($_SESSION['success']) ? '' : $_SESSION['success'] ?>
                </p>
            </div>

            <div class="mt-12">
                <div class="mt-8">
                    <h2 class="font-bold text-slate-700 text-xl text-center">Таблица машин</h2>
                    <?php include '../ui/table/table_cars.php'; ?>
                </div>
            </div>
        </section>

        <section id="table_roads" class="content-block hidden">
            <div class="mt-12">
                <div class="mt-8">
                    <h2 class="font-bold text-slate-700 text-xl text-center">Таблица дорог</h2>
                    <?php include '../ui/table/table_roads.php'; ?>
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
                        echo '<td class="border border-slate-300 px-4 py-2">' . (100 * (int) $val['count'] / $_SESSION['count']) . '</td>';
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
                        echo '<td class="border border-slate-300 px-4 py-2">' . (100 * (int) $val['count'] / $_SESSION['count']) . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            <?php endif; ?>

        </section>

        <section id="settings_map" class="content-block hidden">
            <div class="mt-12">
                <div class="mt-8">
                    <h2 class="font-bold text-slate-700 text-xl text-center">Настройка коэффициентов карты</h2>
                </div>
            </div>
            <form class="flex flex-col m-auto mt-12 w-1/2 h-fit gap-4 items-center" method="post"
                action="param/update/map">
                <label>Количество автомобилей: <span><?= $_SESSION['params'][0]->value ?></span></label>
                <input class="w-full" type="range" name="AUTO_COEF" min="-2" max="6" step="0.1"
                    value="<?= $_SESSION['params'][0]->value ?>"
                    onchange="this.previousElementSibling.lastChild.innerText = this.value" />
                <label>Длина дороги: <span><?= $_SESSION['params'][1]->value ?></span></label>
                <input class="w-full" type="range" name="LENGTH_COEF" min="-0.1" max="0.1" step="0.01"
                    value="<?= $_SESSION['params'][1]->value ?>"
                    onchange="this.previousElementSibling.lastChild.innerText = this.value" />
                <label>Состояния светофоров: <span><?= $_SESSION['params'][2]->value ?></span></label>
                <input class="w-full" type="range" name="LIGHT_COEF" min="-10" max="10" step="0.1"
                    value="<?= $_SESSION['params'][2]->value ?>"
                    onchange="this.previousElementSibling.lastChild.innerText = this.value" />
                <label>Смещение: <span><?= $_SESSION['params'][3]->value ?></span></label>
                <input class="w-full" type="range" name="BIAS" min="-20" max="20" step="1"
                    value="<?= $_SESSION['params'][3]->value ?>"
                    onchange="this.previousElementSibling.lastChild.innerText = this.value" />
                <label>Соотношение автомобилей к длине дороги: <span><?= $_SESSION['params'][4]->value ?></span></label>
                <input class="w-full" type="range" name="A/L_RATIO" min="0" max="15" step="0.1"
                    value="<?= $_SESSION['params'][4]->value ?>"
                    onchange="this.previousElementSibling.lastChild.innerText = this.value" />
                <button type="submit" class="<?= $button_style ?>">Сохранить изменения</button>
            </form>
        </section>
    </div>
</div>

<?php

body_bottom();

unset($_SESSION['error']);
unset($_SESSION['success']);

?>
<?php

session_start();

?>

<table class="w-full mt-4 table-auto border-collapse">
    <thead>
        <tr class="bg-slate-100">
            <th class="border border-slate-300 px-4 py-2">ID</th>
            <th class="border border-slate-300 px-4 py-2">Класс</th>
            <th class="border border-slate-300 px-4 py-2">Модель</th>
        </tr>
    </thead>

    <tbody>
        <?php
        if (!empty($_SESSION['cars'])) {
            foreach ($_SESSION['cars'] as $car) {
                echo '
                    <tr class="bg-white">
                        <td class="border border-slate-300 px-4 py-2">' . htmlspecialchars($car->id) . '</td>
                        <td class="border border-slate-300 px-4 py-2">' . htmlspecialchars($car->class) . '</td>
                        <td class="border border-slate-300 px-4 py-2">' . htmlspecialchars($car->model) . '</td>
                    </tr>';
            }
        } else {
            echo '
                <tr>
                    <td colspan="3" class="text-center border border-slate-300 px-4 py-2">Нет данных для отображения</td>
                </tr>';
        }
        ?>
    </tbody>
</table>
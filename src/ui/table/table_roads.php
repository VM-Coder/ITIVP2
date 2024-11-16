<?php

session_start();

?>

<table class="w-full mt-4 table-auto border-collapse">
    <thead>
        <tr class="bg-slate-100">
            <th class="border border-slate-300 px-4 py-2">ID</th>
            <th class="border border-slate-300 px-4 py-2">Начало</th>
            <th class="border border-slate-300 px-4 py-2">Конец</th>
            <th class="border border-slate-300 px-4 py-2">Удобство</th>
        </tr>
    </thead>

    <tbody>
        <?php
        if (!empty($_SESSION['roads'])) {
            foreach ($_SESSION['roads'] as $road) {
                echo '
                    <tr class="bg-white">
                        <td class="border border-slate-300 px-4 py-2">' . htmlspecialchars($road->id) . '</td>
                        <td class="border border-slate-300 px-4 py-2">' . htmlspecialchars($road->start_point) . '</td>
                        <td class="border border-slate-300 px-4 py-2">' . htmlspecialchars($road->end_point) . '</td>
                        <td class="border border-slate-300 px-4 py-2">' . htmlspecialchars($road->coefficient) . '</td>
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
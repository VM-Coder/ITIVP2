<?php

    session_start();

?>

<table class="w-full mt-4 table-auto border-collapse">
    <thead>
        <tr class="bg-slate-100">
            <th class="border border-slate-300 px-4 py-2">ID пользователя</th>
            <th class="border border-slate-300 px-4 py-2">Почта</th>
            <th class="border border-slate-300 px-4 py-2">Фамилия</th>
            <th class="border border-slate-300 px-4 py-2">Имя</th>
        </tr>
    </thead>

    <tbody>
        <?php 
            if (!empty($_SESSION['users'])){
                foreach ($_SESSION['users'] as $user){
                    echo '
                    <tr class="bg-white">
                        <td class="border border-slate-300 px-4 py-2">' . htmlspecialchars($user->id) . '</td>
                        <td class="border border-slate-300 px-4 py-2">' . htmlspecialchars($user->email) . '</td>
                        <td class="border border-slate-300 px-4 py-2">' . htmlspecialchars($user->lastname) . '</td>
                        <td class="border border-slate-300 px-4 py-2">' . htmlspecialchars($user->firstname) . '</td>
                    </tr>';
                }
            }
            else {
                echo '
                <tr>
                    <td colspan="4" class="text-center border border-slate-300 px-4 py-2">Нет данных для отображения</td>
                </tr>';
            }
        ?>
    </tbody>
</tab>
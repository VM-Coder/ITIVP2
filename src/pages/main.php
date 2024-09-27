<?php
require_once 'template.php';

session_start();

head(title: "Главная");
body_top();
?>

<div class="flex flex-col gap-4 absolute inset-0 m-auto w-fit h-fit">
    <div class="bg-white p-4 box-content inset-0 w-fit h-fit m-auto gap-4 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center text-indigo-500">Добро пожаловать</h1>
        <p class="text-center text-gray-700 mt-4">Для доступа нужно войти в профиль или зарегестрироваться.</p>
        <div class="flex flex-row gap-4 justify-center mt-4">
            <a href="../authorization" class="
        p-2 h-10 text-white hover:bg-gradient-to-r hover:from-sky-400 hover:to-indigo-400 bg-gradient-to-r from-sky-500 to-indigo-500 rounded-lg">Авторизация</a>
            <a href="../registration" class="
        p-2 h-10 text-white hover:bg-gradient-to-r hover:from-sky-400 hover:to-indigo-400 bg-gradient-to-r from-sky-500 to-indigo-500 rounded-lg">Регистрация</a>
        </div>
    </div>
</div>

<?php
body_bottom();
?>

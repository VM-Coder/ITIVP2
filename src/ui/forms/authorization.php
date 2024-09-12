<?php
    session_start();

    $input_style = "p-4 h-12 bg-slate-100 border-slate-100 focus:border-indigo-500 focus:bg-white outline-none border-2 rounded-lg";
?>

<form method="POST" class="flex flex-col absolute p-12 box-content inset-0 w-64 h-fit m-auto gap-4 rounded-lg shadow-lg" action="login">
    <h1 class="font-bold text-slate-700 text-xl">Авторизация</h1>
    <hr class="border-slate-700 border-[1px]">
    <input type="text" name="email" class="<? echo $input_style ?>" placeholder="Электронная почта"/>
    <input type="password" name="password" class="<? echo $input_style ?>" placeholder="Пароль"/>
    <p class="text-center text-red-600"><? echo !isset($_SESSION['error']) ? '' : $_SESSION['error'] ?></p>
    <button type="submit" class="
        p-2 h-12 text-white hover:bg-gradient-to-r hover:from-sky-400 hover:to-indigo-400 bg-gradient-to-r from-sky-500 to-indigo-500 rounded-lg
    ">Войти</button>   
    <a href="../registration" class="text-center hover:underline hover:cursor-pointer text-indigo-500">Зарегистрироваться</a>
</form>

<?php
    unset($_SESSION['error']);
?>


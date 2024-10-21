<?
session_start();

$user = $_SESSION['user'];
?>

<header class="bg-white h-14 shadow-md">
    <div class="container">
        <div class="relative flex flex-row-reverse gap-4 fixed py-4">
            <a class=" text-sky-600 hover:text-amber-600" href="https://traffic-control.local/user/logout">Выйти</a>
            <p><?= $user->firstname ?> <?= $user->lastname ?></p>
        </div>
    </div>
</header>
<?
session_start();

$user = $_SESSION['user'];
?>

<header class="bg-white h-12">
    <div class="container">
        <div class="flex flex-row-reverse gap-4 fixed h-15 py-4">
            <a class=" text-sky-600 hover:text-amber-600" href="https://traffic-control.local/user/logout">Выйти</a>
            <p><?= $user->firstname ?> <?= $user->lastname ?></p>
        </div>
    </div>
</header>
<?php
session_start();

$user = $_SESSION['user'];
?>

<header class="bg-white h-14 shadow-md">
    <div class="container">
        <div class="relative flex flex-row-reverse gap-4 fixed py-4 items-center h-full">
            <a class="text-sky-600 hover:text-amber-600" href="https://traffic-control.local/user/logout">Выйти</a>
            <div class="flex items-center gap-2">
                <img
                    class="w-10 h-10 rounded-full bg-gray-300 flex-shrink-0 cursor-pointer"
                    <? if ($user->avatar): ?>
                    src="data:image/<?= $user->avatar ?>"
                    <? else: ?>
                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNcVQ8AAdkBKwhkHIIAAAAASUVORK5CYII="
                    <? endif; ?>
                    style="background-size: cover; background-position: center;"
                    onclick="document.getElementById('avatar-modal').classList.remove('hidden');" />
                <p><?= htmlspecialchars($user->firstname) ?> <?= htmlspecialchars($user->lastname) ?></p>
            </div>
        </div>
    </div>
</header>

<div id="avatar-modal" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <?php include '../ui/forms/avatar_user.php'; ?>
    <button
        class="absolute top-4 right-4 text-white font-bold text-xl"
        onclick="document.getElementById('avatar-modal').classList.add('hidden');">
        &times;
    </button>
</div>
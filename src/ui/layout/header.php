<?php
session_start();

$user = $_SESSION['user'];

$theme = isset($_COOKIE['theme']) ? sodium_crypto_aead_aes256gcm_decrypt($_COOKIE['theme'], 'theme', 'abcdefabcdef', $_SESSION['key']) : 'light';

$vars = [
    'bg' => 'bg-white',
    'link' => 'text-sky-600',
    'text' => 'text-black',
    'button' => 'text-neutral-700 bg-neutral-100'

];

if ($theme == 'dark') {
    $vars = [
        'bg' => 'bg-neutral-500',
        'link' => 'text-white',
        'text' => 'text-white',
        'button' => 'text-neutral-500 bg-neutral-300'
    ];
}

?>

<header class="<?= $vars['bg'] ?> h-14 shadow-md">
    <div class="container">
    <? 
        $json = null;
        try {
            if (isset($_COOKIE['user'])){
                $json = sodium_crypto_aead_aes256gcm_decrypt($_COOKIE['user'], 'user', 'abcdefabcdef', $_SESSION['key']); 
                $json = json_decode($json);                 
            }
        } catch (Exception){

        }
    ?>
        <div class="relative flex flex-row-reverse gap-4 fixed py-4 items-center h-full">
            <form class="w-fit" action="user/update/theme" method="post">
                <button class="rounded-xl p-2 <?= $vars['button'] ?>">Тема: <?= $theme ?></button>
            </form>
            <a class="<?= $vars['link'] ?> hover:text-amber-600" href="https://traffic-control.local/user/logout">Выйти</a>
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
                <p class="<?= $vars['text'] ?>"><?= htmlspecialchars(isset($json) ? $json->firstname : '') ?> <?= htmlspecialchars(isset($json) ? $json->lastname : '') ?></p>
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
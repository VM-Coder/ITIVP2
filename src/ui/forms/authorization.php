<?php
    session_start();

    $theme = isset($_COOKIE['theme']) ? sodium_crypto_aead_aes256gcm_decrypt($_COOKIE['theme'], 'theme', 'abcdefabcdef', $_SESSION['key']) : 'light';

    $vars = [
        'bg' => 'bg-white',
        'text' => 'text-slate-700',
        'link' => 'text-indigo-500',
        'bg-input' => 'bg-slate-100',
        'success' => 'text-green-500',
        'error' => 'text-red-500'
    ];
    
    if ($theme == 'dark') {
        $vars = [
            'bg' => 'bg-neutral-500',
            'text' => 'text-slate-100',
            'link' => 'text-white',
            'bg-input' => 'bg-neutral-700',
            'success' => 'text-green-300',
            'error' => 'text-red-300'
        ];
    }

    $input_style = "p-4 h-12 " . $vars['bg-input'] . " border-slate-100 focus:border-indigo-500 focus:bg-white outline-none border-2 rounded-lg";
?>

<form method="POST" class="flex flex-col <?= $vars['bg'] ?> absolute p-12 box-content inset-0 w-64 h-fit m-auto gap-4 rounded-lg shadow-lg" action="user/login">
    <h1 class="font-bold <?= $vars['text'] ?> text-xl">Авторизация</h1>
    <hr class="border-slate-700 border-[1px]">
    <input type="text" name="email" class="<?= $input_style ?>" placeholder="Электронная почта" required/>
    <input type="password" name="password" class="<?= $input_style ?>" placeholder="Пароль" required/>
    <p class="text-center text-red-600"><?= !isset($_SESSION['error']) ? '' : $_SESSION['error'] ?></p>
    <button type="submit" class="
        p-2 h-12 text-white hover:bg-gradient-to-r hover:from-sky-400 hover:to-indigo-400 bg-gradient-to-r from-sky-500 to-indigo-500 rounded-lg
    ">Войти</button>   
    <a href="../registration" class="text-center hover:underline hover:cursor-pointer <?= $vars['link'] ?>">Зарегистрироваться</a>
</form>

<?php
    unset($_SESSION['error']);
?>


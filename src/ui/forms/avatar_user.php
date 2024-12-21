<?
session_start();

$user = $_SESSION['user'];
$theme = $_COOKIE['theme'] ?? 'light';

$vars = [
    'bg' => 'bg-white',
    'text' => 'text-slate-700',
    'success' => 'text-green-500',
    'error' => 'text-red-500'
];

if ($theme == 'dark') {
    $vars = [
        'bg' => 'bg-neutral-500',
        'text' => 'text-slate-100',
        'success' => 'text-green-300',
        'error' => 'text-red-300'
    ];
}

?>

<form method="POST" enctype="multipart/form-data" action="user/update/avatar"
    class="rounded-xl flex flex-col gap-4 w-1/2 <?= $vars['bg'] ?> inset-0 m-auto p-12 ">
    <h2 class="font-bold <?= $vars['text'] ?> text-lg text-center">Аватар</h2>
    <input type="file" name="avatar" class="<?= $vars['text'] ?>" />
    <button type="submit" class="<?= $button_style ?>">Сохранить изменения</button>
    <p class="text-center <?= $vars['error'] ?>"><?= !isset($_SESSION['error_avatar']) ? '' : $_SESSION['error_avatar'] ?></p>
    <p class="text-center <?= $vars['success'] ?>"><?= !isset($_SESSION['success_avatar']) ? '' : $_SESSION['success_avatar'] ?></p>
</form>
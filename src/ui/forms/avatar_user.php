<?
session_start();

$user = $_SESSION['user'];

?>

<form method="POST" enctype="multipart/form-data" action="user/update/avatar"
    class="rounded-xl flex flex-col gap-4 w-1/2 bg-white inset-0 m-auto p-12 ">
    <h2 class="font-bold text-slate-700 text-lg text-center">Аватар</h2>
    <input type="file" name="avatar" />
    <button type="submit" class="<?= $button_style ?>">Сохранить изменения</button>
    <p class="text-center text-red-600"><?= !isset($_SESSION['error']) ? '' : $_SESSION['error'] ?></p>
    <p class="text-center text-green-600"><?= !isset($_SESSION['success']) ? '' : $_SESSION['success'] ?></p>
</form>
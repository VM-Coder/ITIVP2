<?
session_start();

$car = $_SESSION['car'];
$car_position = isset($car->x) && isset($car->y) ? $car->x . ' ' . $car->y : '';

?>

<form method="POST" action="user/update/car" class="rounded-xl flex flex-col gap-4 w-1/2 bg-white inset-0 m-auto p-12 ">
    <?php if (!isset($car)) : ?>
        <h2>Автомобиль отсутствует</h2>
        <button type="submit" class="<?= $button_style ?>">Добавить автомобиль</button>
    <?php else : ?>
        <h2 class="font-bold text-slate-700 text-lg text-center">Автомобиль</h2>
        <input type="text" value="<?= $car->class ?>" name="class" class="<?= $input_style ?>" placeholder="Класс" required />
        <input type="text" value="<?= $car->model ?>" name="model" class="<?= $input_style ?>" placeholder="Модель" required />
        <input type="text" value="<?= $car_position ?>" name="position" class="<?= $input_style ?>" placeholder="Координаты: x y" />
        <button type="submit" class="<?= $button_style ?>">Сохранить изменения</button>
    <?php endif; ?>

    <p class="text-center text-red-600"><?= !isset($_SESSION['error']) ? '' : $_SESSION['error'] ?></p>
    <p class="text-center text-green-600"><?= !isset($_SESSION['success']) ? '' : $_SESSION['success'] ?></p>
</form>
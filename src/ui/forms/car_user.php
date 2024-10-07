<?
session_start();

$car = $_SESSION['car'];
?>

<form method="POST" action="user/update/car" class="flex flex-col gap-4 w-1/3 bg-white">
    <?php if (!isset($car)) : ?>
        <h2>Автомобиль отсутствует</h2>
        <button type="submit" class="<?= $button_style ?>">Добавить автомобиль</button>
    <?php else : ?>
        <h2 class="font-bold text-slate-700 text-lg text-center">Автомобиль</h2>
        <input type="text" value="<?= $car->class ?>" name="class" class="<?= $input_style ?>" placeholder="Класс" required />
        <input type="text" value="<?= $car->model ?>" name="model" class="<?= $input_style ?>" placeholder="Модель" required />
        <button type="submit" class="<?= $button_style ?>">Сохранить изменения</button>
    <?php endif; ?>
</form>
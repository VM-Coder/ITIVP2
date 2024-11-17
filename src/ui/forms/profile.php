<?php 

session_start();

$input_style = "p-4 h-12 bg-slate-100 border-slate-100 focus:border-indigo-500 focus:bg-white outline-none border-2 rounded-lg";
$button_style = "p-2 h-12 text-white hover:bg-gradient-to-r hover:from-sky-400 hover:to-indigo-400 bg-gradient-to-r from-sky-500 to-indigo-500 rounded-lg";
?>

<div class="flex flex-col p-12 box-content inset-0 w-3/4 h-fit m-auto gap-4 rounded-lg shadow-lg">
    <h1 class="font-bold text-slate-700 text-xl text-center">Взаимодействие с таблицей</h1>
    <hr class="border-slate-700 border-[1px]">

    <div class="flex flex-row justify-between gap-8">
        <form method="POST" action="add_car" class="flex flex-col gap-4 w-1/3">
            <h2 class="font-bold text-slate-700 text-lg text-center">Добавление машины</h2>
            <input type="text" name="car_class" class="<?php echo $input_style ?>" placeholder="Класс" required/>
            <input type="text" name="car_model" class="<?php echo $input_style ?>" placeholder="Модель" required/>
            <button type="submit" name="action" value="add_car" class="<?php echo $button_style ?>">Добавить</button>
        </form>

        <form method="POST" action="search_car" class="flex flex-col gap-4 w-1/3">
            <h2 class="font-bold text-slate-700 text-lg text-center">Поиск машины</h2>
            <input type="text" name="search_model" class="<?php echo $input_style ?>" placeholder="Введите модель для поиска"/>
            <button type="submit" name="action" value="search_car" class="<?php echo $button_style ?>">Найти</button>
        </form>

        <form method="POST" action="delete_car" class="flex flex-col gap-4 w-1/3">
            <h2 class="font-bold text-slate-700 text-lg text-center">Удаление машины</h2>
            <input type="number" name="delete_id" class="<?php echo $input_style ?>" placeholder="Введите ID для удаления" required/>
            <button type="submit" name="action" value="delete_car" class="<?php echo $button_style ?>">Удалить</button>
        </form>
    </div>

    <p class="text-center text-red-600"><?php echo !isset($_SESSION['error']) ? '' : $_SESSION['error'] ?></p>
    <p class="text-center text-green-600"><?php echo !isset($_SESSION['success']) ? '' : $_SESSION['success'] ?></p>

    <a href="../authorization" class="text-center hover:underline hover:cursor-pointer text-indigo-500">Выйти</a>
</div>

<div class="mt-12"> 
    <h1 class="font-bold text-slate-700 text-xl text-center">Таблица машин</h1>
    <table class="w-full mt-4 table-auto border-collapse">
        <thead>
            <tr class="bg-slate-100">
                <th class="border border-slate-300 px-4 py-2">ID</th>
                <th class="border border-slate-300 px-4 py-2">Класс</th>
                <th class="border border-slate-300 px-4 py-2">Модель</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($_SESSION['cars'])): ?>
                <?php foreach ($_SESSION['cars'] as $car): ?>
                    <tr>
                        <td class="border border-slate-300 px-4 py-2"><?php echo htmlspecialchars($car->id); ?></td>
                        <td class="border border-slate-300 px-4 py-2"><?php echo htmlspecialchars($car->class); ?></td>
                        <td class="border border-slate-300 px-4 py-2"><?php echo htmlspecialchars($car->model); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center border border-slate-300 px-4 py-2">Нет данных для отображения</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
unset($_SESSION['error']);
unset($_SESSION['success']);
?>
